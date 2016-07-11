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
$inputClass = new \System\Lib\Input();
require __DIR__ . '/system/page.class.php';
$pager = new Page();
//参数
$_G['system'] = DB::table('system')->orderBy("`showorder`,id")->lists('value', 'code');
$_G['class'] = ($inputClass->get(0) != '') ? $inputClass->get(0) : 'index';
$_G['func'] = ($inputClass->get(1) != '') ? $inputClass->get(1) : 'index';

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
    $_G['class'] = ($inputClass->get(1) != '') ? $inputClass->get(1) : 'index';
    $_G['func'] = ($inputClass->get(2) != '') ? $inputClass->get(2) : 'index';
    if (file_exists(__DIR__ . '/app/Controller/'.$_path.'/' . ucfirst($_G['class']) . 'Controller.php')) {
        $_classpath = "\\App\\Controller\\" .$_path.'\\'. ucfirst($_G['class']) . "Controller";
        //$class = new $_classpath();
        $method = $_G['func'];
    } else {
        $_classpath = "\\App\\Controller\\" .$_path."\\IndexController";
        //$class = new $_classpath();
        $method = $_G['class'];
    }
}
$_G['Controller'] = $class;
app($_classpath,$method);
exit;

$rMethod = new \ReflectionMethod($class, $method);
$params = $rMethod->getParameters();
$dependencies = array();
foreach ($params as $param) {
    if ($param->getClass()) {
        $_name = $param->getClass()->name;
        array_push($dependencies, new $_name());
    } elseif ($param->isDefaultValueAvailable()) {
        array_push($dependencies, $param->getDefaultValue());
    } else {
        array_push($dependencies, null);
    }
}
return call_user_func_array(array($class, $method), $dependencies);

$t2 = microtime(true);
//echo '<hr>耗时'.round($t2-$t1,3).'秒';


