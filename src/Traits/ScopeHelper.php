<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 24.
 * Time: 3:46
 */

namespace Janez89\Repository\Traits;

use Closure;

trait ScopeHelper
{
    /**
     * @var Closure
     */
    protected $queryScope;

    /**
     * @param Closure $scope
     * @return $this
     */
    public function scope(Closure $scope)
    {
        $this->queryScope = $scope;
        return $this;
    }

    /**
     * @param $model
     * @return mixed
     */
    protected function applyScope($model)
    {
        if (isset($this->queryScope) && is_callable($this->queryScope)) {
            $scope = ($this->queryScope);
            return $scope($model, $this);
        }

        return $model;
    }

    /**
     * clear query scope
     */
    protected function clearScope()
    {
        $this->queryScope = NULL;
    }
}