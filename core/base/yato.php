<?php
/**
 * yato 系统核心文件入口.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/15
 * Version: 1.0
 */

namespace core\base;

spl_autoload_register('core\base\yato::autoLoad');

class yato
{
    public static $_config = '';

    public static function createApp($config)
    {

        return new application($config);
    }

    public static function setConfig($app)
    {
        self::$_config = $app;
    }

    public static function config()
    {
        return self::$_config;
    }

    /*
     * 自动加载类文件
     */
    public static function autoLoad($className)
    {
        //
        $filePath = APP_PATH . $className . '.php';
//        echo APP_PATH . 'core' . DS . 'lib' . DS . $className . '.php';
        if (file_exists($filePath)) {
            include $filePath;
        } elseif (file_exists(
            APP_PATH . 'core' . DS . 'base' . DS . $className . '.php'
        )
        ) {
            include APP_PATH . 'core' . DS . 'base' . DS . $className . '.php';
        } elseif (
        file_exists(
            APP_PATH . 'core' . DS . 'lib' . DS . $className . '.php'
        )
        ) {
            include APP_PATH . 'core' . DS . 'lib' . DS . $className . '.php';
        } else {
            return false;
        }
        return true;
    }
}


class application extends component
{
    private static $_config = [];

    public function __construct($config)
    {
//        $this->setConfig($config);
        self::$_config = $config;
        //开始注册事件
    }

    /**
     * 入口执行
     */
    public function run()
    {
        yato::setConfig($this);
        //设置配置文件
//        $this->setConfig(self::$_config);
        //初始化
        $this->preinit();

        $components = [
            'db' => [
                'class' => 'core\lib\db',
            ],
        ];
//        $componet = new component();
        $this->setConfig(self::$_config['components']);
//        foreach ($components as $id => $com) {
//            $this->getComponet($id);
//        }
//        var_dump($this);
//        exit;
        //http组件
//        $this->processRequest();
        //设置路由
        $this->route();
        //输出
//        $this->response();
    }

    /**
     * 注册组件
     */
    public function registerComponents()
    {
        //
    }

    public function route()
    {
        //
        $route = new router($this->config);
        $parth = $route->getUrlParam();
        $controller = strtolower($parth['c']);
        $action = strtolower($parth['a']);
        $module = strtolower($parth['m']);
        
        $controllerClass = $this->getModuleName($module, $controller);
        $conObj = new $controllerClass($controller, $action);

        if (method_exists($conObj, $action)) {
            call_user_func([$conObj, $action]);
        } else {
            throw new cException('访问方法不存在');
        }
    }

    /**
     * 获取模块的路径
     *
     * @param $module
     * @param $controller
     */
    public function getModuleName($module, $controller)
    {
        return MODULE_NAME . DS . $module . DS . 'controller' . DS
            . $controller;
    }


    /*
     * 初始化应用信息
     */
    public function init()
    {
        //错误处理函数
    }

    public function preinit()
    {
        //错误处理函数
        reporting::create()->init();
    }

    //处理参数
    public function processRequest()
    {
        //
    }
}

