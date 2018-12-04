<?php
/**
 * yato controller.php.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/27
 * Version: 1.0
 */

namespace core\base;


class controller
{
    public $module;
    public $action;
    public $controller;
    public function __construct($module,$controller,$action)
    {
        $this->module = $module;
        $this->action = $action;
        $this->controller = $controller;
    }
}