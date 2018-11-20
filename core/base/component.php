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
    public $_components = [];//注册的组件
    public $config = [];//配置的组件
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

    /**
     * 设置系统配置
     * @param $config
     */
    public function setConfig($config)
    {
        //
        if(is_array($config)) {
            foreach($config as $key=>$value)
                $this->$key=$value;
        }
    }
    public function create($config)
    {
        if (is_string($config)) {
            $type=$config;
            $config=array();
        } else if(isset($config['class'])) {
            $type=$config['class'];
            unset($config['class']);
        } else {
            throw new cExeception(
                'Object configuration must be an array containing a "class" element.'
            );
        }
        if (($n=func_num_args())>1) {
            $args=func_get_args();
            if($n===2)
                $object=new $type($args[1]);
            else if($n===3)
                $object=new $type($args[1],$args[2]);
            else if($n===4)
                $object=new $type($args[1],$args[2],$args[3]);
            else {
                unset($args[0]);
                $class=new ReflectionClass($type);
                // Note: ReflectionClass::newInstanceArgs() is available for PHP 5.1.3+
                 $object=$class->newInstanceArgs($args);
//                $object=call_user_func_array(array($class,'newInstance'),$args);
            }
        } else
            $object=new $type;

        foreach($config as $key=>$value)
            $object->$key=$value;

        return $object;
    }

    /**
     * 获取组件
     * @param      $id
     * @param bool $create
     *
     * @return mixed
     */
    public function getComponet($id,$create=true)
    {
        //获取
        if(isset($this->sytem[$id])) {
            return $this->_components[$id];
        } else if(isset($this->config[$id]) && $create) {
            $component=$this->create($this->config[$id]);
            $component->init();
            return $this->_components[$id]=$component;
        }
    }

    /**
     * 设置组件
     * @param $id
     * @param $component
     */
    public function setComponent($id,$component)
    {
        if($component===null)
            unset($this->_components[$id]);
        else
        {
            $this->_components[$id]=$component;
//            if(!$component->getIsInitialized())
//                $component->init();
        }
    }
    public function hasComponet($name)
    {
        //
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