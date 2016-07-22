<?php
namespace App;

class Config
{
    // 数据库实例1
    public static $db1 = array(
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'user'    => 'root',
        'password' => 'root',
        'dbname'  => 'user_center',
        'charset'    => 'utf8',
		'dbfix' => 'plf_'
    );
    public static $siteKeys=array(
        '1'=>'B86FB8864DF1F6F5386F5E70362139ED',
        '2'=>'696ACAE0A1E4B958B7988C5D2FF66971'
    );
}