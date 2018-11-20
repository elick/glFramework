<?php
/**
 * yato 错误输出设置.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/20
 * Version: 1.0
 */

namespace core\base;


class reporting
{
    public static function create(){
        return new self();
    }

    /**
     * 初始化错误输出
     */
    public function init()
    {
        if (APP_DEBUG === true) {
            error_reporting(E_ALL);
            ini_set('display_errors','On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors','Off');
            ini_set('log_errors', 'On');
            ini_set('error_log', ERROR_OUTPUT_PATH);
        }
        set_error_handler([$this,'errorHandler']);
        set_exception_handler([$this,'exceptionHandler']);
        register_shutdown_function([$this,'shupDownHandler']);
    }

    /**
     * 设置自定义的错误处理函数
     */
    public function errorHandler()
    {
        //自定义错误
    }

    /**
     * 设置自定的异常处理函数
     */
    public function exceptionHandler()
    {
        //
    }
    /**
     * 设置程序终止后自定义处理函数
     */
    public function shupDownHandler()
    {
        //
    }
}