<?php

/*
   //连接本地的 Redis 服务
   $redis = new Redis();
   $redis->connect('127.0.0.1', 6379);
   echo "Connection to server sucessfully";
   //设置 redis 字符串数据
   $redis->set("tutorial-name", "Redis tutorial");
   // 获取存储的数据并输出
   echo "Stored string in redis:: " . $redis->get("tutorial-name");

exit;*/
$t1 = microtime(true);

use System\Lib\DB;
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(7);
//define('ROOT', dirname(__FILE__).'/');
//define('ROOT', dirname($_SERVER['SCRIPT_FILENAME']) . '/');
define('ROOT', __DIR__);

$_G = array();
require ROOT. '/system/init.php';
require ROOT . '/system/Autoloader.php';
//require __DIR__.'/app/Config.php';
$mysql = DB::instance('db1');

require __DIR__ . '/system/function.php';
require __DIR__ . '/system/helper.php';
$inputClass = new \System\Lib\Input();
require __DIR__ . '/system/page.class.php';
$pager = new Page();
//联动值
//$_G['linkpage']=m('linkpage/getlinkpage');
//参数
$_G['system'] = DB::table('system')->orderBy("`showorder`,id")->lists('value', 'code');
$_G['class'] = ($inputClass->get(0) != '') ? $inputClass->get(0) : 'index';
$_G['func'] = ($inputClass->get(1) != '') ? $inputClass->get(1) : 'index';

if ($_G['class'] == 'api') {
    $_G['class'] = ($inputClass->get(1) != '') ? $inputClass->get(1) : 'index';
    $_G['func'] = ($inputClass->get(2) != '') ? $inputClass->get(2) : 'index';
    require __DIR__ . '/app/Controller/Api/index.php';
}elseif ($_G['class'] == 'data') {
    $_G['class'] = ($inputClass->get(1) != '') ? $inputClass->get(1) : 'index';
    $_G['func'] = ($inputClass->get(2) != '') ? $inputClass->get(2) : 'index';
    require __DIR__ . '/app/Controller/Data/index.php';
} 
 elseif ($_G['class'] == $_G['system']['houtai']) {
    $_G['class'] = ($inputClass->get(1) != '') ? $inputClass->get(1) : 'index';
    $_G['func'] = ($inputClass->get(2) != '') ? $inputClass->get(2) : 'index';
    require __DIR__ . '/app/Controller/Admin/index.php';
} elseif (file_exists(__DIR__ . '/app/Controller/' . ucfirst($_G['class']) . 'Controller.php')) {
    $_classpath = "\\App\\Controller\\" . ucfirst($_G['class']) . "Controller";
    $class = new $_classpath();
    if (method_exists($class, $_G['func'])) {
        return call_user_func(array($class, $_G['func']), array());
    } else {
        return call_user_func(array($class, 'error'), array());
    }
} else {
    $class = new \App\Controller\IndexController();
    if (method_exists($class, $_G['class'])) {
        return call_user_func(array($class, $_G['class']), array());
    } else {
        return call_user_func(array($class, 'error'), array());
    }
}

$t2 = microtime(true);
//echo '<hr>耗时'.round($t2-$t1,3).'秒';


