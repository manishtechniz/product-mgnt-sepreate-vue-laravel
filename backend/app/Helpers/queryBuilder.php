<?php

/**
 * Define Queries.
 */
if (! function_exists('setQueryBuilder')) {
    function setQueryBuilder(&$query): void
    {
        $queryParams = request()->query() ?? null;

        $sort = $queryParams["sort"] ?? "asc";

        $columnSort = $queryParams["columnSort"] ?? null;
        
        $filter = $queryParams["filter"] ?? null;

        $columnFilters = json_decode($queryParams["columnFilters"] ?? "[]");

        $filter = request()->query('filter') ?? null;

        /**
         * Apply Filters
         */
        if (! empty($filter)
            && ! empty($columnFilters)
        ) {
            foreach ($columnFilters as $columnFilter) {
                $query->orWhere($columnFilter, 'like', '%' . $filter . '%');
            }
        }

        /**
         * Apply Sort
         */
        if (! empty($columnSort)) {
            $query->orderBy($columnSort, $sort ?? "asc");
        }
    }
}

/**
 * Apply paginate.
 */
if (! function_exists('applyPaginate')) {
    function applyPaginate($query) {
        return $query->paginate(getPaginateItemLength(), ['*'], 'page', request()->query('page') ?? null);
    }
}