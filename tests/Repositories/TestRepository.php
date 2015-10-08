<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 24.
 * Time: 0:40
 */
class TestRepository extends Janez89\Repository\AbstractEloquentRepository
{
    use \Janez89\Repository\Traits\EloquentTransactional;
    use \Janez89\Repository\Traits\DataTables;

    public function getModelClass()
    {
        return Test::class;
    }

    public function userDefinedQuery($seq)
    {
        return $this->findBy('seq', $seq);
    }
}
