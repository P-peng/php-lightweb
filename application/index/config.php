<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板后缀
        // 'view_suffix'  => 'htm',
    ],

    //替换全局变量
    'view_replace_str'  =>  [
        '__PUBLIC__'=>'/public/',
        '__ROOT__' => '/',
        '__INDEX__' => 'http://localhost/lightweb/public/static/index',
        '__IMG__' => 'http://localhost'
    ]
];
