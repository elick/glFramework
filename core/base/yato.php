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

spl_autoload_register('core\base\yato::autoLoad');

class yato
{
    public static $_config = '';

    public static function createApp($config)
    {
        return new application($config);
    }

    public static function setConfig($app)
    {
        self::$_config = $app;
    }

    public static function config()
    {
        return self::$_config;
    }

    /**
     * 内核文件命名空间映射关系
     * @return array
     */
    protected static function classMap()
    {
        /*return [
            'app\controller' => APP_PATH . '/core/base/controller.php',
            'fastphp\base\Model' => CORE_PATH . '/base/Model.php',
            'fastphp\base\View' => CORE_PATH . '/base/View.php',
            'fastphp\db\Db' => CORE_PATH . '/db/Db.php',
            'fastphp\db\Sql' => CORE_PATH . '/db/Sql.php',
        ];*/
    }


    // 自动加载类
//    public function loadClass($className)
    public static function autoLoad($className)
    {
//        echo $className;
//        echo "<br/>";
        $classMap = self::classMap();

        if (isset($classMap[$className])) {
            // 包含内核文件
            $file = $classMap[$className];
        } elseif (strpos($className, '\\') !== false) {
            // 包含应用（application目录）文件
            $file = APP_PATH . str_replace('\\', '/', $className) . '.php';
//            echo $file;
//            echo "<br/>";
            if (!is_file($file)) {
                return;
            }
        } else {
            return;
        }

        include $file;

        // 这里可以加入判断，如果名为$className的类、接口或者性状不存在，则在调试模式下抛出错误
    }

    /*
     * 自动加载类文件
     */
    public static function autoLoad_bak($className)
    {
        $filePath = APP_PATH . $className . '.php';
//        echo APP_PATH . 'core' . DS . 'lib' . DS . $className . '.php';
        if (file_exists($filePath)) {
            include $filePath;
        } elseif (file_exists(
            APP_PATH . 'core' . DS . 'base' . DS . $className . '.php'
        )
        ) {
            include APP_PATH . 'core' . DS . 'base' . DS . $className . '.php';
        } elseif (
        file_exists(
            APP_PATH . 'core' . DS . 'lib' . DS . $className . '.php'
        )
        ) {
            include APP_PATH . 'core' . DS . 'lib' . DS . $className . '.php';
        } elseif (
        file_exists(
            APP_PATH .  'app' . DS . 'models' . DS . $className . '.php'
        )
        ) {
            //加载module类
            include APP_PATH .  'app'. DS . 'models' . DS . $className . '.php';

        } else {
            return false;
        }
        return true;
    }
}


class application extends component
{
    private static $_config = [];

    public function __construct($config)
    {
        $this->setConfig($config);
        self::$_config = $config;
        //开始注册事件
    }

    /**
     * 入口执行
     */
    public function run()
    {
        //设置配置文件
        yato::setConfig($this);
//        $this->setConfig(self::$_config);
        //初始化
        $this->preinit();

        $components = [
            'db' => [
                'class' => 'core\lib\db',
            ],
        ];
//        $componet = new component();
        $this->setConfig(self::$_config['components']);
//        foreach ($components as $id => $com) {
//            $this->getComponet($id);
//        }
//        var_dump($this);
//        exit;
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
        $route = new router(self::$_config);
        $parth = $route->getUrlParam();
        $controller = strtolower($parth['c']);
        $action = strtolower($parth['a']);
//        $module = strtolower($parth['m']);

//        $controllerClass = $this->getModuleName($module, $controller);
        $controllerName = $controller.'Controller';
        $file = $this->getControllerFileName($controllerName);
//        if(file_exists($file)){
//            include $file;
//        }else{
//            exit('文件不存在');
//        }


        $controller = 'app\\controller\\'. $controller . 'Controller';
        if (!class_exists($controller)) {
            exit($controller . '控制器不存在');
        }
        if (!method_exists($controller, $action)) {
            exit($action . '方法不存在');
        }
        $param = array(
            '',$controllerName,$action
        );

        // 如果控制器和操作名存在，则实例化控制器，因为控制器对象里面
        // 还会用到控制器名和操作名，所以实例化的时候把他们俩的名称也
        // 传进去。结合Controller基类一起看
        $dispatch = new $controller($controller, $action);

        // $dispatch保存控制器实例化后的对象，我们就可以调用它的方法，
        // 也可以像方法中传入参数，以下等同于：$dispatch->$actionName($param)
        call_user_func_array(array($dispatch, $action), $param);



        /*$conObj = new $controllerName($controller, $action);
        if (method_exists($conObj, $action)) {
            call_user_func([$conObj, $action]);
        } else {
            throw new cException('访问方法不存在');
        }*/
    }

    /**
     * 获取控制器文件名
     * @param $controller
     */
    public function getControllerFileName($controller){
        return APP_PATH.'app' . DS . 'controller' . DS. $controller .'.php';
    }

    /**
     * 获取模块的路径
     *
     * @param $module
     * @param $controller
     */
    public function getModuleName($module, $controller)
    {
        return MODULE_NAME . DS . $module . DS . 'controller' . DS
            . $controller;
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

