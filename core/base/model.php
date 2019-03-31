<?php
/**
 * model基类
 * yato model.php.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/18
 * Version: 1.0
 */

namespace core\base;

use core\db\db;

class model
{
    protected $model;

    protected $db='';

    public function __construct()
    {
        // 获取数据库表名
        if (!$this->table) {

            // 获取模型类名称
            $this->model = get_class($this);

            // 删除类名最后的 Model 字符
            $this->model = substr($this->model, 0, -5);

            // 数据库表名与类名一致
            $this->table = strtolower($this->model);
        }
        $this->db = new db();
        $this->db->connect();
    }

    public function getRow($where){
        return $this->db->select('*')->from($this->table)->where($where)->getRow();
    }

    public function getAll($where){
        return $this->db->select('*')->from($this->table)->where($where)->getAll();
    }
}