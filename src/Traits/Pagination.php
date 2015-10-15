<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 10. 15.
 * Time: 18:58
 */

namespace Janez89\Repository\Traits;


/**
 * Class Pagination
 * require illuminate/pagination module
 *
 * @package Janez89\Repository\Traits
 */
trait Pagination
{
    /**
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function getPaginate($perPage = 15, $columns = ['*'])
    {
        return $this->getQuery()->paginate($perPage, $columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function simplePaginate($perPage = 15, $columns = ['*'])
    {
        return $this->getQuery()->paginate($perPage, $columns);
    }
}