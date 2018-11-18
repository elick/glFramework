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
        return new self();
    }
    public function run()
    {
        //自动加载类文件
        spl_autoload_register([$this,'autoLoad']);
        //初始化
        $this->init();
        //http组件
        $this->processRequest();
        //设置路由
        $this->route();
        //输出
//        $this->response();
    }
    public function route()
    {
        //
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
        //
        //设置自定义的错误处理函数
//        set_error_handler('_error_handler');
        //设置自定的异常处理函数
//        set_exception_handler('_exception_handler');
        //设置程序终止后函数
//        register_shutdown_function('_shutdown_handler');
    }
    //处理参数
    public function processRequest()
    {
        //
    }

}