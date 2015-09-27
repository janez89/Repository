<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 26.
 * Time: 5:35
 */
class OldThen2000Criteria implements \Janez89\Repository\Contracts\CriteriaInterface
{
    const DATE = '2000-01-01';
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $repo
     */
    public function apply($query, $repo)
    {
        $query->where('created_at', '<=', new DateTime(self::DATE));
    }
}