<?php
/**
 * yato index.php.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/27
 * Version: 1.0
 */

namespace app\controller;


use core\base\controller;
use app\models\SymbolDayModel;

class indexController extends controller
{
    public function index()
    {
//        $data = $t->connect()->select('*')->from('db')->getRow();
//        $data = $this->loadModel('test');
        $mod = new SymbolDayModel();
        $data = $mod->getRowByDay("days='20190329' and close=high and percent>7");
        foreach($data as $val){
            echo $val['code'];
            echo "<br/>";
            $tmp = $mod->getRow("days='20190329' and code='{$val['code']}'");
            if($tmp && abs($tmp['close']-$val['close'])/$val['close']<0.05){
                echo $tmp['code'];
                echo "<br/>";
            }
        }
        exit;
    }

}