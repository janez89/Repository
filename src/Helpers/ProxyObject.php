<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 10. 03.
 * Time: 22:19
 */

namespace Janez89\Repository\Helpers;

use Closure;

class ProxyObject
{
    protected $proxyFunction;

    public function __construct(Closure $proxyFunction)
    {
        $this->proxyFunction = $proxyFunction;
    }

    public function __call($method, $args)
    {
        $func = $this->proxyFunction;
        $func($method, $args);

        return $this;
    }
}