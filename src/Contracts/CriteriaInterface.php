<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 23.
 * Time: 22:52
 */

namespace Janez89\Repository\Contracts;


interface CriteriaInterface
{
    public function apply($query, $repository);
}