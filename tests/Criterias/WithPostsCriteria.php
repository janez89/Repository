<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 26.
 * Time: 5:13
 */

use \Janez89\Repository\Contracts\EloquentCriteriaInterface;
use \Janez89\Repository\Contracts\RepositoryInterface;
use \Illuminate\Database\Eloquent\Builder;

class WithPostsCriteria implements EloquentCriteriaInterface
{
    public function apply(Builder $query, RepositoryInterface $repository)
    {
        $query->with('posts');
    }
}