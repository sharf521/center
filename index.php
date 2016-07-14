<?php
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(7);
$t1 = microtime(true);
define('ROOT', __DIR__);
$_G = array();
require ROOT . '/system/init.php';
require ROOT . '/system/Autoloader.php';
use System\Lib\DB;
DB::instance('db1');
require ROOT . '/system/function.php';
require ROOT . '/system/helper.php';
$pager = app('\System\Lib\Page');
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
    if (file_exists(ROOT . '/app/Controller/' . ucfirst($_G['class']) . 'Controller.php')) {
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
    if (file_exists(ROOT . '/app/Controller/'.$_path.'/' . ucfirst($_G['class']) . 'Controller.php')) {
        $_classpath = "\\App\\Controller\\" .$_path.'\\'. ucfirst($_G['class']) . "Controller";
        $method = $_G['func'];
    } else {
        $_classpath = "\\App\\Controller\\" .$_path."\\IndexController";
        $method = $_G['class'];
    }
}
controller($_classpath,$method);
$t2 = microtime(true);
//echo '<hr>耗时'.round($t2-$t1,3).'秒';