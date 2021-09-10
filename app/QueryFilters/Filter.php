<?php

declare(strict_types=1);

namespace App\QueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class Filter
{
    /**
     * Handle the filter
     *
     * @param $request
     * @param Closure $next
     * @return Builder|mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var $filterName */
        $filterName = $this->filterName();

        if (! request()->has($filterName)) {
            return $next($request);
        }

        /** @var $query */
        $query = $next($request);

        return $this->applyFilters($query);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    abstract protected function applyFilters(Builder $query): Builder;

    /**
     * Get the filter name in snake case
     *
     * @return string
     */
    protected function filterName(): string
    {
        return Str::snake(class_basename($this));
    }
}
