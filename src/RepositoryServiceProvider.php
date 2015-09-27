<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 23.
 * Time: 22:12
 */

namespace Janez89\Repository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //$this->publishes([
        //    __DIR__.'/config/repository.php' => config_path('repository.php'),
        //]);
    }

    public function register()
    {
        return [];
    }
}