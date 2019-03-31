<?php
/**
 * Created by PhpStorm.
 * User: guilin01
 * Date: 2019/3/31
 * Time: 17:43
 */

namespace app\models;
use core\base\model;

class SymbolDayModel extends model
{
    protected  $table = "symbol_day";

    public function getRowByDay($where){
        return $this->getAll($where);
    }
}