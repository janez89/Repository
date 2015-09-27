<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 26.
 * Time: 5:25
 */

namespace Janez89\Repository\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface EloquentCriteriaInterface
{
    public function apply(Builder $query, RepositoryInterface $repository);
}