<?php
/**
 * Created by PhpStorm.
 * User: guilin01
 * Date: 2019/3/31
 * Time: 16:05
 */

namespace core\base;


/**
 * 视图基类
 */
class view
{
    protected $variables = array();
    protected $_controller;
    protected $_action;
    protected $layout = 'main';

    function __construct($controller, $action)
    {
        $this->_controller = strtolower($controller);
        $this->_action = strtolower($action);
    }

    // 分配变量
    public function assign($name, $value)
    {
        $this->variables[$name] = $value;
    }

    /**
     * 获取模板文件
     * @param $layoutName
     * @return bool
     */
    public function getLayoutFile($layoutName)
    {
        if($layoutName===false)
            return false;
        if(empty($layoutName))
        {
            $layoutName=$this->_controller->layout;
        }
        if($layoutName){
            $extension = '.php';
            $viewFile=APP_PATH.DS.'app'.DS.'view'.DS.'layout'.DS.$layoutName.$extension;

            if(is_file($viewFile)){
                return $viewFile;
            }
        }
        return false;
    }

    /**
     * @param $_viewFile_
     * @param null $_data_
     * @param bool $_return_
     * @return string
     */
    public function renderInternal($_viewFile_,$_data_=null,$_return_=false)
    {
        // we use special variable names here to avoid conflict when extracting data
        if(is_array($_data_))
            extract($_data_,EXTR_PREFIX_SAME,'data');
        else
            $data=$_data_;
        if($_return_)
        {
            ob_start();
            ob_implicit_flush(false);
            require($_viewFile_);
            return ob_get_clean();
        }
        else
            require($_viewFile_);
    }

    // 渲染显示
    public function render($view='')
    {
        extract($this->variables);
        $view = $view?$view:$this->_action;
        $controllerLayout = APP_PATH . 'app/views/' . $this->_controller . '/' . $view . '.php';
        if($this->layout) {
            if (($layoutFile = $this->getLayoutFile($this->layout . '_header')) !== false)
                include($layoutFile);
        }

        //判断视图文件是否存在
        if (is_file($controllerLayout)) {
            include ($controllerLayout);
        } else {
            echo "<h1>无法找到视图文件</h1>";
        }

        // 页脚文件
        if($this->layout) {
            if (($layoutFile = $this->getLayoutFile($this->layout . '_footer')) !== false)
                include($layoutFile);
        }
    }
}