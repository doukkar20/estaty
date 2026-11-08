<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MoroccoMarket
{
    public static function enabled(): bool
    {
        return (bool) config('morocco.enabled', false);
    }

    public static function countryId(): ?int
    {
        if (app()->bound('morocco.country_id')) return app('morocco.country_id') ?: null;
        $id = DB::table('property_country_contents')->where('slug', 'morocco')->value('country_id');
        app()->instance('morocco.country_id', (int) $id);
        return $id ? (int) $id : null;
    }

    public static function areaUnit(): string
    {
        return self::enabled() ? 'm²' : __('Sqft');
    }

    public static function propertyRules(Request $request, array $rules): array
    {
        if (!self::enabled()) return $rules;
        $countryId = self::countryId() ?? 0;
        $rules['country_id'] = ['required', 'integer', Rule::in([$countryId])];
        $rules['state_id'] = ['required', 'integer', Rule::exists('property_states', 'id')->where('country_id', $countryId)];
        $rules['city_id'] = ['required', 'integer', Rule::exists('property_cities', 'id')->where('country_id', $countryId)->where('state_id', $request->input('state_id'))];
        return $rules;
    }

    public static function projectRules(array $rules): array
    {
        if (self::enabled()) {
            $rules['city_id'] = ['required', 'integer', Rule::exists('property_cities', 'id')->where('country_id', self::countryId() ?? 0)];
        }
        return $rules;
    }

    public static function projectLocation(Request $request): array
    {
        if (!self::enabled()) return [];
        $city = DB::table('property_cities')->where('country_id', self::countryId())->where('id', $request->input('city_id'))->first();
        abort_unless($city, 422, __('Select City'));
        return ['country_id' => $city->country_id, 'state_id' => $city->state_id, 'city_id' => $city->id];
    }
}
