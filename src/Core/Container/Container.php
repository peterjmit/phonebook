<?php

namespace Core\Container;

use Pimple;

class Container extends Pimple implements ContainerInterface
{
    public function set($id, \Closure $callable)
    {
        $this->setParam($id, $this->share($callable));
    }

    public function get($id)
    {
        return $this->getParam($id);
    }

    public function has($id)
    {
        return $this->hasParam($id);
    }

    public function setParam($id, $param)
    {
        $this->offsetSet($id, $param);
    }

    public function getParam($id)
    {
        return $this->offsetGet($id);
    }

    public function hasParam($id)
    {
        return $this->offsetExists($id);
    }
}
