<?php

namespace App\Models\Traits;

use App\Support\MoroccoMarket;
use Illuminate\Database\Eloquent\Builder;

trait MoroccanLocation
{
    protected static function bootMoroccanLocation(): void
    {
        static::addGlobalScope('morocco', function (Builder $query) {
            if (!MoroccoMarket::enabled()) return;
            $column = $query->getModel()->getTable() === 'property_countries' ? 'id' : 'country_id';
            $query->where($query->getModel()->qualifyColumn($column), MoroccoMarket::countryId() ?? 0);
        });
    }
}
