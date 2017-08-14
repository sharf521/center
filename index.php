<?php

//quantity:0.1511496000，0，60，to_quantity：0.0025191600.
/*$total=0;
for($i=0;$i<15;$i++){
    $row=pow(2,$i);
    echo "第".($i+1) ."层：数量：".$row.'，';
    $total+=$row;
    echo '当前总计：'.$total.'个<br>';
}
exit;*/
$t1 = microtime(true);
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(7);
header('Content-language: zh');
header('Content-type: text/html; charset=utf-8');
header('X-Powered-By: JAVA');
header('Pragma: no-cache');
header('Cache-Control: private',false); // required for certain browsers
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: '.gmdate('D, d M Y H:i:s') . ' GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

 //session 里user_id 和其它应用里user_id 不一致 ，不能共享session
 //获取域名
$domain=strtolower($_SERVER['HTTP_HOST']);
if(strpos($domain,':')!==false){
    //去除端口
    $domain=explode(':',$domain);
    $domain=$domain[0];
}
/*$domain_arr=explode('.',$domain);
if($domain_arr[count($domain_arr)-2]=='com'){
    $domain=$domain_arr[count($domain_arr)-3].'.'.$domain_arr[count($domain_arr)-2].'.'.$domain_arr[count($domain_arr)-1];
}else{
    $domain=$domain_arr[count($domain_arr)-2].'.'.$domain_arr[count($domain_arr)-1];
}*/
//session_start();之前设置  php.ini 里 session.auto_start=0
//ini_set('session.cookie_domain', $domain);//域名不需要端口
session_cache_limiter('private,must-revalidate');
session_start();
date_default_timezone_set('Asia/Shanghai');//时区配置
set_time_limit($set_time = 3600);

define('ROOT', __DIR__);
require 'vendor/autoload.php';
require 'function.php';
\System\Lib\DB::instance(\App\Config::$db1);
$pager = app('\System\Lib\Page');
$routes=array(
    'api'=>'Api',
    'auth'=>'Auth',
    'member'=>'Member',
    'platform'=>'Platform',
);
\System\Lib\Application::start($routes);
$t2 = microtime(true);
//echo '<hr>耗时'.round($t2-$t1,3).'秒';