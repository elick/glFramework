<?php
/**
 * yato 系统核心文件入口.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/15
 * Version: 1.0
 */

namespace core;


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
        spl_autoload_register(array($this,'autoload'));
    }

}