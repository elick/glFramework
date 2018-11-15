<?php
/**
 * yato 入口文件.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/15
 * Version: 1.0
 */

//路径分割符
define('DS',DIRECTORY_SEPARATOR);
//应用目录
define('APP_PATH',__DIR__.DS);
//是否开启调试模式
define('APP_DEBUG',true);
//网站域名
define('WEB_DOMAIN','http://www.glxuexi.com');
//加载框架核心代码
require APP_PATH.'core'.DS.'yato.php';
core\yato::createApp( APP_PATH.DS.'config'.DS.'.config.php')->run();