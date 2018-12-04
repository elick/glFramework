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


class yato
{
    private static $_config = [];
    public static function createApp($config)
    {
        self::$_config = $config;
        //开始注册事件
        return new self();
    }

    /**
     * 入口执行
     */
    public function run()
    {
        //自动加载类文件
        spl_autoload_register([$this,'autoLoad']);
        //初始化
        $this->preinit();

        //初始化组件
//        $componet = new component();
//        $componet->setConfig(self::$_config);

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
        $controller = self::$_config['defaultController'];
        $action = self::$_config['defaultController'];
        $module = self::$_config['defaultModule'];
        if(isset($_GET['c']) && $_GET['c']){
            $controller = strtolower($_GET['c']);
        }
        if(isset($_GET['a']) && $_GET['a']){
            $action = strtolower($_GET['a']);
        }
        $action = 'action'.ucfirst($action);
        if(isset($_GET['m']) && $_GET['m']){
            $module = strtolower($_GET['m']);
        }

        $controllerClass = $this->getModuleName($module,$controller);
        $conObj =  new $controllerClass($controller,$action);

        if(method_exists($conObj,$action)){
            call_user_func([$conObj,$action]);
        }else{
            throw new cException('访问方法不存在');
        }
    }

    /**
     * 获取模块的路径
     * @param $module
     * @param $controller
     */
    public function getModuleName($module,$controller)
    {
        return MODULE_NAME.DS.$module.DS.'controller'.DS.$controller;
    }
    /*
     * 自动加载类文件
     */
    public static function autoLoad($className)
    {
        //
        $filePath = APP_PATH.$className.'.php';
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            return false;
        }
        return true;
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