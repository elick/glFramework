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

    public function __get($name)
    {
        if ($this->hasComponent($name)) {
            return $this->getComponent($name);
        }
    }

    public function __isset($name)
    {
        if ($this->hasComponent($name)) {
            return $this->getComponent($name) !== null;
        }
    }

    /**
     * 设置系统配置
     *
     * @param $config
     */
    public function setConfig($config)
    {
        //
        try {
            if (is_array($config)) {
                foreach ($config as $key => $value) {
                    $this->config[$key] = $value;
                }
            }
        } catch (\Exception $e) {
            var_dump($e);
        }
    }

    public function create($config)
    {
        if (is_string($config)) {
            $type = $config;
            $config = [];
        } else {
            if (isset($config['class'])) {
                $type = $config['class'];
                unset($config['class']);
            } else {
                throw new cExeception(
                    'Object configuration must be an array containing a "class" element.'
                );
            }
        }
        if (($n = func_num_args()) > 1) {
            $args = func_get_args();
            if ($n === 2) {
                $object = new $type($args[1]);
            } else {
                if ($n === 3) {
                    $object = new $type($args[1], $args[2]);
                } else {
                    if ($n === 4) {
                        $object = new $type($args[1], $args[2], $args[3]);
                    } else {
                        unset($args[0]);
                        $class = new ReflectionClass($type);
                        // Note: ReflectionClass::newInstanceArgs() is available for PHP 5.1.3+
                        $object = $class->newInstanceArgs($args);
//                $object=call_user_func_array(array($class,'newInstance'),$args);
                    }
                }
            }
        } else {
            $object = new $type;
        }

        foreach ($config as $key => $value) {
            $object->$key = $value;
        }

        return $object;
    }

    /**
     * 获取组件
     *
     * @param      $id
     * @param bool $create
     *
     * @return mixed
     */
    public function getComponent($id, $create = true)
    {
        //获取
        if (isset($this->_components[$id])) {
            return $this->_components[$id];
        } else {
            if (isset($this->config[$id]) && $create) {
                $component = $this->create($this->config[$id]);
                return $this->_components[$id] = $component->init(
                    $this->config[$id]
                );
            }
        }
    }

    /**
     * 设置组件
     *
     * @param $id
     * @param $component
     */
    public function setComponent($id, $component)
    {
        if ($component === null) {
            unset($this->_components[$id]);
        } else {
            $this->_components[$id] = $component;
//            if(!$component->getIsInitialized())
//                $component->init();
        }
    }

    public function hasComponent($id)
    {
        //
        return isset($this->_components[$id]) || isset($this->config[$id]);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    public function __unset($name)
    {
        // TODO: Implement __unset() method.
    }
}