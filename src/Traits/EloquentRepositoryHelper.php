<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 23.
 * Time: 22:57
 */

namespace Janez89\Repository\Traits;


trait EloquentRepositoryHelper
{
    /**
     * make instance with DI
     * @param $class
     * @return mixed
     */
    protected function makeInstance($class)
    {
        if (class_exists('\App'))
            return \App::make($class);

        return new $class(); // NO DI
    }

    /**
     * Resolve Database Connection
     * @return mixed
     */
    protected function resolveDatabase()
    {
        if (class_exists('\DB'))
            return \DB::class;

        return \Illuminate\Database\Capsule\Manager::class;
    }

    /**
     * @param $model
     * @return bool
     */
    public function isEloquentModel($model)
    {
        return $model != NULL && $model instanceof \Illuminate\Database\Eloquent\Model;
    }

    /**
     * @return string
     */
    public function getKeyName()
    {
        return $this->model->getKeyName();
    }
}