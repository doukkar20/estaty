<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConfigureSiteLanguages extends Command
{
    protected $signature = 'rafa:languages {--export-starter : Include the French records in the bundled installer SQL}';
    protected $description = 'Add French content records, make French the default, and retain Arabic RTL support';

    public function handle()
    {
        $dictionary = array_merge(
            json_decode(file_get_contents(resource_path('lang/fr.json')), true, 512, JSON_THROW_ON_ERROR),
            json_decode(file_get_contents(resource_path('localization/fr-content.json')), true, 512, JSON_THROW_ON_ERROR)
        );
        $tables = array_map(static fn ($row) => $row->TABLE_NAME, DB::select(
            "SELECT TABLE_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND COLUMN_NAME = 'language_id' ORDER BY TABLE_NAME",
            [DB::getDatabaseName()]
        ));
        // Blog information refers to language-specific categories; copy those first.
        $tables = array_values(array_unique(array_merge(['blog_categories'], $tables)));
        $frenchId = DB::transaction(function () use ($tables, $dictionary) {
            $french = DB::table('languages')->where('code', 'fr')->first();
            if (!$french) {
                $source = DB::table('languages')->where('code', 'en')->first()
                    ?? DB::table('languages')->where('is_default', 1)->first();
                if (!$source) throw new \RuntimeException('Install a source language before adding French.');
                $frenchId = DB::table('languages')->insertGetId([
                    'name' => 'Français', 'code' => 'fr', 'direction' => 0, 'is_default' => 0,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                $blogCategoryIds = [];
                foreach ($tables as $table) {
                    foreach (DB::table($table)->where('language_id', $source->id)->orderBy('id')->get() as $record) {
                        $row = (array) $record;
                        $sourceId = $row['id'];
                        unset($row['id']);
                        $row['language_id'] = $frenchId;
                        foreach ($row as $column => $value) {
                            // Keep identifiers, URLs, proper names, and authored text unchanged unless a translation exists.
                            if (is_string($value) && !in_array($column, ['slug', 'url', 'btn_url', 'button_url', 'image', 'icon'], true)) {
                                $row[$column] = $dictionary[$value] ?? $value;
                            }
                        }
                        if ($table === 'menu_builders') {
                            $menus = json_decode($record->menus, true, 512, JSON_THROW_ON_ERROR);
                            array_walk_recursive($menus, static function (&$value, $key) use ($dictionary) {
                                if (in_array($key, ['text', 'title'], true) && is_string($value)) $value = $dictionary[$value] ?? $value;
                            });
                            $row['menus'] = json_encode($menus, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
                        }
                        if ($table === 'blog_informations' && isset($blogCategoryIds[$row['blog_category_id']])) {
                            $row['blog_category_id'] = $blogCategoryIds[$row['blog_category_id']];
                        }
                        $newId = DB::table($table)->insertGetId($row);
                        if ($table === 'blog_categories') $blogCategoryIds[$sourceId] = $newId;
                    }
                }
            } else {
                // Existing French content may have been edited; never replace it on a repeat run.
                $frenchId = $french->id;
            }
            $this->repairArabicMenus();
            foreach (DB::table('page_contents')->where('language_id', $frenchId)->get() as $page) {
                $content = str_ireplace('Estaty', 'RAFAIMMOBILIER', $page->content ?? '');
                if ($content !== $page->content) DB::table('page_contents')->where('id', $page->id)->update(['content' => $content]);
            }
            DB::table('languages')->update(['is_default' => 0]);
            DB::table('languages')->where('id', $frenchId)->update(['name' => 'Français', 'direction' => 0, 'is_default' => 1]);
            DB::table('languages')->where('code', 'ar')->update(['name' => 'العربية', 'direction' => 1]);
            return $frenchId;
        });
        if ($this->option('export-starter')) $this->exportStarter($tables, $frenchId);
        $this->info('French is the default language. Arabic remains available with right-to-left layout.');
        return 0;
    }

    private function repairArabicMenus(): void
    {
        $arabic = DB::table('languages')->where('code', 'ar')->first();
        $english = DB::table('languages')->where('code', 'en')->first();
        if (!$arabic || !$english) return;
        $source = DB::table('menu_builders')->where('language_id', $english->id)->value('menus');
        $menus = json_decode($source ?? '[]', true);
        if (!is_array($menus)) return;
        $dictionary = array_merge(
            json_decode(file_get_contents(resource_path('lang/ar.json')), true, 512, JSON_THROW_ON_ERROR),
            json_decode(file_get_contents(resource_path('localization/ar-content.json')), true, 512, JSON_THROW_ON_ERROR)
        );
        array_walk_recursive($menus, static function (&$value, $key) use ($dictionary) {
            if (in_array($key, ['text', 'title'], true) && is_string($value)) $value = $dictionary[$value] ?? $value;
        });
        foreach (DB::table('menu_builders')->where('language_id', $arabic->id)->get() as $record) {
            if (is_array(json_decode($record->menus, true))) continue;
            $backup = storage_path('app/arabic-menu-before-repair-' . $record->id . '.json');
            if (!is_file($backup)) file_put_contents($backup, $record->menus);
            DB::table('menu_builders')->where('id', $record->id)->update([
                'menus' => json_encode($menus, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            ]);
        }
    }

    private function exportStarter(array $tables, int $frenchId): void
    {
        $marker = '-- RAFAIMMOBILIER French language defaults';
        $sql = $marker . "\nSTART TRANSACTION;\nUPDATE `languages` SET `is_default` = 0;\n";
        foreach (array_merge(['languages'], $tables) as $table) {
            $records = $table === 'languages'
                ? DB::table($table)->where('id', $frenchId)->get()
                : DB::table($table)->where('language_id', $frenchId)->get();
            foreach ($records as $record) {
                $row = (array) $record;
                $columns = implode(', ', array_map(static fn ($column) => '`' . $column . '`', array_keys($row)));
                $values = implode(', ', array_map(static fn ($value) => $value === null ? 'NULL' : DB::getPdo()->quote((string) $value), array_values($row)));
                $sql .= "INSERT INTO `$table` ($columns) VALUES ($values);\n";
            }
        }
        $arabicId = DB::table('languages')->where('code', 'ar')->value('id');
        foreach (DB::table('menu_builders')->where('language_id', $arabicId)->get() as $record) {
            $menus = DB::getPdo()->quote($record->menus);
            $sql .= "UPDATE `menu_builders` SET `menus` = $menus WHERE `id` = " . (int) $record->id . " AND `language_id` = " . (int) $arabicId . ";\n";
        }
        $sql .= "UPDATE `languages` SET `name` = 'العربية', `direction` = 1 WHERE `code` = 'ar';\nCOMMIT;\n";
        foreach ([public_path('installer/database.sql'), base_path('vendor/kreativdev/installer/files/public/installer/database.sql')] as $path) {
            $base = explode($marker, file_get_contents($path), 2)[0];
            file_put_contents($path, rtrim($base) . "\n\n" . $sql);
        }
    }
}
