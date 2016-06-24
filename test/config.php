<?php
namespace Config;
class Db
{
    // 数据库实例1
    public static $db1 = array(
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'user'    => 'root',
        'password' => 'root',
        'dbname'  => 'chat',
        'prefix'=>'plf_',
        'charset'    => 'utf8',
    );
}