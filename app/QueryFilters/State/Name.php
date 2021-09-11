<?php

declare(strict_types=1);

namespace App\QueryFilters\State;

use App\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class Name extends Filter
{
    /**
     * Get the term result only
     *
     * @param Builder $query
     * @return Builder
     */
    protected function applyFilters(Builder $query): Builder
    {
        /** @var $request */
        $request = request(strtolower($this->filterName()));
        /** @var $term */
        $term = preg_replace('/[^A-Za-z0-9\d]/', '', $request);
        return $query->whereRaw("regexp_replace(name, '[^A-Za-z0-9\d]', '') like ?", [$term . '%']);
    }
}
