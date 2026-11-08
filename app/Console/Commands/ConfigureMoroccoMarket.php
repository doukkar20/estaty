<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConfigureMoroccoMarket extends Command
{
    protected $signature = 'rafa:morocco {--apply-defaults : Apply MAD, square metres and Moroccan defaults} {--enable : Enable the Moroccan market profile}';
    protected $description = 'Import the official HCP Moroccan regions and communes without deleting existing data';

    public function handle(): int
    {
        $data = json_decode(file_get_contents(database_path('data/morocco.json')), true, 512, JSON_THROW_ON_ERROR);
        if (count($data['regions']) !== 12 || count($data['cities']) !== 1503) {
            $this->error('The Moroccan reference failed its completeness check.');
            return self::FAILURE;
        }
        $regionCodes = array_column($data['regions'], 'code');
        $cityCodes = array_column($data['cities'], 'code');
        if (count(array_unique($regionCodes)) !== 12 || count(array_unique($cityCodes)) !== 1503
            || collect($data['cities'])->contains(fn ($city) => !in_array($city['region'], $regionCodes, true) || empty($city['fr']) || empty($city['ar']))) {
            $this->error('The Moroccan reference contains invalid or duplicate geographic codes.');
            return self::FAILURE;
        }
        if (!DB::table('languages')->exists()) {
            $this->error('Configure the site languages before importing Moroccan locations.');
            return self::FAILURE;
        }
        foreach (['country_id', 'state_id', 'city_id'] as $column) {
            if (!Schema::hasColumn('projects', $column)) {
                Schema::table('projects', function (Blueprint $table) use ($column) {
                    $table->unsignedBigInteger($column)->nullable()->index();
                });
            }
        }
        $languages = DB::table('languages')->get(['id', 'code']);
        $now = now();
        $result = DB::transaction(function () use ($data, $languages, $now) {
            $countryId = DB::table('property_country_contents')->where('slug', 'morocco')->value('country_id');
            if (!$countryId) $countryId = DB::table('property_country_contents')->whereIn('name', ['Morocco', 'Maroc', 'المغرب'])->value('country_id');
            if (!$countryId) $countryId = DB::table('property_countries')->insertGetId(['created_at' => $now, 'updated_at' => $now]);
            foreach ($languages as $language) {
                DB::table('property_country_contents')->updateOrInsert(['country_id' => $countryId, 'language_id' => $language->id], ['name' => $language->code === 'ar' ? 'المغرب' : ($language->code === 'fr' ? 'Maroc' : 'Morocco'), 'slug' => 'morocco', 'updated_at' => $now]);
            }
            $regionIds = [];
            foreach ($data['regions'] as $region) {
                $slug = 'ma-region-' . $region['code'];
                $id = DB::table('property_state_contents')->where('slug', $slug)->value('state_id');
                if (!$id) $id = DB::table('property_states')->insertGetId(['country_id' => $countryId, 'created_at' => $now, 'updated_at' => $now]);
                foreach ($languages as $language) {
                    DB::table('property_state_contents')->updateOrInsert(['state_id' => $id, 'language_id' => $language->id], ['name' => $language->code === 'ar' ? $region['ar'] : $region['fr'], 'slug' => $slug, 'updated_at' => $now]);
                }
                DB::table('property_states')->where('id', $id)->update(['country_id' => $countryId]);
                $regionIds[$region['code']] = $id;
            }
            $existing = DB::table('property_city_contents')->where('slug', 'like', 'ma-hcp-%')->pluck('city_id', 'slug')->all();
            $nextId = (int) DB::table('property_cities')->max('id') + 1;
            $cities = []; $contents = [];
            $existingContents = DB::table('property_city_contents')->where('slug', 'like', 'ma-hcp-%')->get(['city_id', 'language_id'])->mapWithKeys(fn ($row) => [$row->city_id . ':' . $row->language_id => true])->all();
            $priority = ['Casablanca', 'Rabat', 'Marrakech', 'Tanger', 'Agadir', 'Fès', 'Meknès', 'Oujda', 'Kénitra', 'Tétouan', 'Salé', 'Dakhla', 'Laâyoune'];
            foreach ($data['cities'] as $index => $city) {
                $slug = 'ma-hcp-' . $city['code'];
                $isNew = !isset($existing[$slug]);
                $id = $isNew ? $nextId++ : $existing[$slug];
                $rank = array_search($city['fr'], $priority, true);
                if ($isNew) $cities[] = ['id' => $id, 'country_id' => $countryId, 'state_id' => $regionIds[$city['region']], 'image' => 'morocco-city.svg', 'status' => 1, 'serial_number' => $rank === false ? 100 + $index : $rank + 1, 'featured' => $rank !== false && $rank < 8 ? 1 : 0, 'created_at' => $now, 'updated_at' => $now];
                foreach ($languages as $language) {
                    if (isset($existingContents[$id . ':' . $language->id])) continue;
                    $contents[] = ['city_id' => $id, 'language_id' => $language->id, 'name' => $language->code === 'ar' ? $city['ar'] : $city['fr'], 'slug' => $slug, 'created_at' => $now, 'updated_at' => $now];
                }
            }
            foreach (array_chunk($cities, 150) as $chunk) DB::table('property_cities')->insert($chunk);
            foreach (array_chunk($contents, 150) as $chunk) DB::table('property_city_contents')->insert($chunk);
            if ($this->option('apply-defaults')) {
                $fields = ['base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'timezone', 'property_country_status', 'property_state_status', 'address', 'contact_number', 'email_address'];
                $settings = DB::table('basic_settings')->first($fields);
                $backup = storage_path('app/morocco-settings-before.json');
                if (!is_file($backup)) file_put_contents($backup, json_encode($settings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                $changes = ['base_currency_symbol' => 'DH', 'base_currency_symbol_position' => 'right', 'base_currency_text' => 'MAD', 'base_currency_text_position' => 'right', 'timezone' => 'Africa/Casablanca', 'property_country_status' => 1, 'property_state_status' => 1];
                if (preg_match('/New York|USA|Dhaka|Bangladesh/i', (string) $settings->address)) $changes['address'] = 'Maroc';
                if (str_contains((string) $settings->email_address, '@example.com')) $changes['email_address'] = null;
                if (preg_match('/^\+?880/', (string) $settings->contact_number)) $changes['contact_number'] = null;
                DB::table('basic_settings')->update($changes);
                setEnvironmentValue(['APP_TIMEZONE' => 'Africa/Casablanca']);
            }
            return ['country_id' => (int) $countryId, 'regions' => count($regionIds), 'communes_added' => count($cities), 'communes_total' => count($data['cities'])];
        });
        if ($this->option('enable')) {
            setEnvironmentValue(['MOROCCO_MARKET' => 'true']);
            config(['morocco.enabled' => true]);
        }
        $this->line(json_encode($result));
        return self::SUCCESS;
    }
}
