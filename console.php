<?php
/**
 * yato 脚本入口文件.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/15
 * Version: 1.0
 */

php_sapi_name() == 'cli' or die('No access.');
$args = isset($_SERVER['argv'])?$_SERVER['argv']:'';
if(empty($args) || empty($args[1]) || empty($args[2])){
    die('no params');
}
//路径分割符
define('DS', DIRECTORY_SEPARATOR);
//应用目录
define('APP_PATH', __DIR__ . DS);
define('MODULE_NAME', 'module');
//是否开启调试模式
define('APP_DEBUG', true);
//网站域名
define('WEB_DOMAIN', 'http://www.glxuexi.com');
//错误输出
define('ERROR_OUTPUT_PATH', APP_PATH . 'error/error.log');
//加载框架核心代码
require APP_PATH . 'core' . DS . 'base' . DS . 'yato.php';
$config = require APP_PATH . 'config' . DS . 'config.php';
$config['urlType'] = 1;
$_REQUEST['c'] = $args[1];
$_REQUEST['a'] = $args[2];
core\base\yato::createApp($config)->run();
