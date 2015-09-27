<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 24.
 * Time: 0:40
 */
class AuthorRepository extends Janez89\Repository\AbstractEloquentRepository
{
    public function getModelClass()
    {
        return Author::class;
    }
}
