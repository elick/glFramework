<?php
/**
 * yato 系统配置
 *
 * User: gui lin <306261312@qq.com>
 * link: http://www.glxuexi.com
 * Date: 2018/11/15
 * Version: 1.0
 */
return [
    'defaultController' => 'index',//默认控制器
    'defaultAction'     => 'index',//默认访问方法
    'defaultModule'     => 'app',//默认访问模块
    'urlType'=>2,
    'components'        => [
        'db' => [
            'type'     => 'mysql',
            'host'     => 'localhost',
            'dbname'   => 'mysql',
            'username' => 'root',
            'password' => '',
            //            'port'     => '',
            //            'charset'  => '',
            'class'    => 'db',
        ],
    ],
];