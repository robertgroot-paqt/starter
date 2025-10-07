<?php

namespace App\Models\Traits;

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

trait HasQueryBuilder
{
    /** @return list<string|AllowedSort> */
    public function allowedSorts(): array
    {
        return [];
    }

    /** @return list<string|AllowedInclude> */
    public function allowedIncludes(): array
    {
        return [];
    }

    /** @return list<string|AllowedFilter> */
    public function allowedFilters(): array
    {
        return [];
    }
}
