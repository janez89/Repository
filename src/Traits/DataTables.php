<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 10. 05.
 * Time: 14:52
 */

namespace Janez89\Repository\Traits;


use Illuminate\Support\Facades\Request;

trait DataTables
{
    public function dataTable(Request $request)
    {
        $response = new \stdClass();

        $response->data = $this->getQuery()
            ->skip($request->get('start'))
            ->take($request->get('length'))
            ->get()
            ->toArray();

        $response->recordsTotal = $this->getQuery()->count();
        $response->recordsFiltered = $response->recordsTotal;
        $response->draw = (int) $request->get('draw');

        return $response;
    }
}