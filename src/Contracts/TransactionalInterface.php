<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 27.
 * Time: 4:00
 */

namespace Janez89\Repository\Contracts;


interface TransactionalInterface
{
    public function beginTransaction();
    public function rollback();
    public function commit();
    public function transaction(Closure $closure);
}