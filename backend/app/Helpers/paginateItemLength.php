<?php
/**
 * Get paginate item length
 */
if (! function_exists('getPaginateItemLength')) {
    function getPaginateItemLength()
    {
        $itemPerPage = $itemPerPage = request()->query('itemsPerPage') ?? 0;

        if (empty($itemPerPage)) {
            $itemPerPage = request('itemsPerPage');
        }

        return $itemPerPage > 50 ? 50 : $itemPerPage ?? 10;
    }
}