<?php
/**
 * 控制器基类
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
    public $view;

    // 构造函数，初始化属性，并实例化对应模型
    public function __construct($module,$controller,$action)
    {
        $this->module = $module;
        $this->action = $action;
        $this->controller = $controller;
        $this->view = new view($controller, $action);

    }
    // 分配变量
    public function assign($name, $value)
    {
        $this->view->assign($name, $value);
    }

    // 渲染视图
    public function render()
    {
        $this->view->render();
    }
}