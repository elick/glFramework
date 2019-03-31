<?php
/**
 * Created by PhpStorm.
 * User: guilin01
 * Date: 2019/3/31
 * Time: 14:08
 */


namespace app\models;

use core\base\model;

/**
 * 用户Model
 */
class testModel extends model
{

    /**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 item 表
     * @var string
     */
    protected $table = 'user';

    public function getRowBy()
    {
        return $this->db->select('*')->from('db')->getRow();

    }
}