<?php
/**
 * yato index.php.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/27
 * Version: 1.0
 */

namespace module\app\controller;


use core\base\controller;
use core\base\yato;

class index extends controller
{
    public function actionIndex()
    {
        $t = yato::config()->db;
        var_dump($t->connect()->select('user')->from('user')->getOne());
        echo 1;
        exit;
    }

}