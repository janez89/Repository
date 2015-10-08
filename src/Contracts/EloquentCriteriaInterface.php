<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 26.
 * Time: 5:25
 */

namespace Janez89\Repository\Contracts;

interface EloquentCriteriaInterface
{
    public function apply($query, RepositoryInterface $repository);
}