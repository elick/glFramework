<?php
/**
 * yato component.php.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/18
 * Version: 1.0
 */

namespace core\base;


class component
{
    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $setter = 'set'.ucfirst($name);
        if (method_exists($this,$setter)) {
            return $this->$setter;
        } elseif (method_exists($this,'get'.$name)) {
            throw new cException('Exception:'.get_class($this).$name.' readOnly');
        } else {
            throw new cException(get_class().$name.' no defined');
        }
    }
    public function __get($name)
    {
        // TODO: Implement __get() method.
        $getter = 'get'.ucfirst($name);
        if (method_exists($this,$getter)) {
            return $this->$getter;
        } elseif (method_exists($this,'set'.$name)) {
            throw new cException('Exception:'.get_class($this).$name.' writeOnly');
        } else {
            throw new cException(get_class().$name.' no defined');
        }
    }
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
    public function __isset($name)
    {
        // TODO: Implement __isset() method.
    }
    public function __unset($name)
    {
        // TODO: Implement __unset() method.
    }
}