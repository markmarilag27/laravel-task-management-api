<?php

declare(strict_types=1);

namespace App\QueryFilters\Task;

use App\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class OnlyTrashed extends Filter
{
    /**
     * Get the trashed result only
     *
     * @param Builder $query
     * @return Builder
     */
    protected function applyFilters(Builder $query): Builder
    {
        return $query->onlyTrashed();
    }
}
