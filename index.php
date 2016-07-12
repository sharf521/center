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
require ROOT . '/system/init.php';
require ROOT . '/system/Autoloader.php';
//require __DIR__.'/app/Config.php';

$mysql = DB::instance('db1');
require __DIR__ . '/system/function.php';
require __DIR__ . '/system/helper.php';
require __DIR__ . '/system/page.class.php';
$pager = new Page();
//参数
$request=app('\System\Lib\Request');
$_G['system'] = DB::table('system')->orderBy("`showorder`,id")->lists('value', 'code');
$_G['class'] = ($request->get(0) != '') ? $request->get(0) : 'index';
$_G['func'] = ($request->get(1) != '') ? $request->get(1) : 'index';

$_path='';
if ($_G['class'] == 'api') {
    $_path='Api';
} elseif ($_G['class'] == $_G['system']['houtai']) {
    $_path='Admin';
}
if($_path==''){
    if (file_exists(__DIR__ . '/app/Controller/' . ucfirst($_G['class']) . 'Controller.php')) {
        $_classpath = "\\App\\Controller\\" . ucfirst($_G['class']) . "Controller";
        //$class = new $_classpath();
        $method = $_G['func'];
    } else {
        $_classpath='\App\Controller\IndexController';
        //$class = new \App\Controller\IndexController();
        $method = $_G['class'];
    }
}else{
    $_G['class'] = ($request->get(1) != '') ? $request->get(1) : 'index';
    $_G['func'] = ($request->get(2) != '') ? $request->get(2) : 'index';
    if (file_exists(__DIR__ . '/app/Controller/'.$_path.'/' . ucfirst($_G['class']) . 'Controller.php')) {
        $_classpath = "\\App\\Controller\\" .$_path.'\\'. ucfirst($_G['class']) . "Controller";
        $method = $_G['func'];
    } else {
        $_classpath = "\\App\\Controller\\" .$_path."\\IndexController";
        $method = $_G['class'];
    }
}
controller($_classpath,$method);
$t2 = microtime(true);
echo '<hr>耗时'.round($t2-$t1,3).'秒';


