<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用入口文件
// 检测PHP环境

if ($_SERVER['HTTP_HOST'] == 'www.jszapi.com' || $_SERVER['HTTP_HOST'] == 'www.jszgame.com' ) {
//skip
} else {
    if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'http') {
        Header('Location: https://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . '');
        exit;
    }
}
if (version_compare(PHP_VERSION, '5.3.0', '<'))
    die('require PHP > 5.3.0 !');

//检查url地址
$query = $_SERVER["QUERY_STRING"];
if (false !== strpos($query, "execute")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "update")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "chr")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "mid")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "master")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "truncate")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "char")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "declare")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "select")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "create")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "delete")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "insert")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "'")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "\"")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "union")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "%20")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "Btype")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "Bname")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "/*")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
} else if (false !== strpos($query, "*/")) {
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    exit;
}
//目录安全文件
define('DIR_SECURE_FILENAME', 'index.html');

define('DIR_SECURE_CONTENT', 'deney Access!');
//导入Dnode autoload.php 实现node与php之间的通信
require_once("./vendor/autoload.php");

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);

// 定义应用目录
define('APP_PATH', './Application/');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';
// 亲^_^ 后面不需要任何代码了 就是如此简单
