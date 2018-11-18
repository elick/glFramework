<?php
/**
 * yato model.php.
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/18
 * Version: 1.0
 */

namespace core\base;


class model extends component
{
    public $name = '';
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}