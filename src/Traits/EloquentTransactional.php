<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 27.
 * Time: 2:26
 */

namespace Janez89\Repository\Traits;

use Closure;

trait EloquentTransactional
{
    use EloquentRepositoryHelper;

    public function beginTransaction()
    {
        $db = $this->resolveDatabase();
        $db::beginTransaction();
    }

    public function rollback()
    {
        $db = $this->resolveDatabase();
        $db::rollback();
    }

    public function commit()
    {
        $db = $this->resolveDatabase();
        $db::commit();
    }

    /**
     * @param Closure $closure
     * @return mixed
     */
    public function transaction(Closure $closure)
    {
        $db = $this->resolveDatabase();
        return $db::transaction($closure);
    }
}