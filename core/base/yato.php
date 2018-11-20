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
        //http组件
        $this->processRequest();
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