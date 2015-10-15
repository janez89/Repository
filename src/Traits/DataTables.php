<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 10. 05.
 * Time: 14:52
 */

namespace Janez89\Repository\Traits;


trait DataTables
{
    public function dataTable($request)
    {
        $response = new \stdClass();

        $limit = $request->get('length') > 1 ? (int) $request->get('length') : 1;
        $offset = (int) $request->get('start');

        $response->data = $this->getQuery()
            ->take($limit)
            ->skip($offset)
            ->get()
            ->toArray();


        $response->recordsTotal = $this->getQuery()->count();
        $response->recordsFiltered = $response->recordsTotal;
        $response->draw = (int) $request->get('draw');

        return $response;
    }
}