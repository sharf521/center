<?php
use \GatewayWorker\Lib\Db;
require 'config.php';
require 'Db.php';
require 'DbConnection.php';

$db1 = Db::instance('db1');
$restult=$db1->select('id,name nam3')->from('plf_category')->limit(2)->offset(2)->query();
print_r($restult);




//$db1->update('plf_category')->cols(array('title'=>'F'))->where('id<10')->limit(4)->query();


/**
use \GatewayWorker\Lib\Db;
$db1 = Db::instance('db1');
$db2 = Db::instance('db2');

// 获取所有数据
$db1->select('ID,Sex')->from('Persons')->where('sex= :sex')->bindValues(array('sex'=>'M'))->query();
//等价于
$db1->select('ID,Sex')->from('Persons')->where("sex= 'F' ")->query();
//等价于
$db1->query("SELECT ID,Sex FROM `Persons` WHERE sex=‘M’");


// 获取一行数据
$db1->select('ID,Sex')->from('Persons')->where('sex= :sex')->bindValues(array('sex'=>'M'))->row();
//等价于
$db1->select('ID,Sex')->from('Persons')->where("sex= 'F' ")->row();
//等价于
$db1->row("SELECT ID,Sex FROM `Persons` WHERE sex=‘M’");


// 获取一列数据
$db1->select('ID')->from('Persons')->where('sex= :sex')->bindValues(array('sex'=>'M'))->column();
//等价于
$db1->select('ID')->from('Persons')->where("sex= 'F' ")->column();
//等价于
$db1->column("SELECT `ID` FROM `Persons` WHERE sex=‘M’");

// 获取单个值
$db1->select('ID,Sex')->from('Persons')->where('sex= :sex')->bindValues(array('sex'=>'M'))->single();
//等价于
$db1->select('ID,Sex')->from('Persons')->where("sex= 'F' ")->single();
//等价于
$db1->single("SELECT ID,Sex FROM `Persons` WHERE sex='M'");

// 复杂查询
$db1->select('*')->from('table1')->innerJoin('table2','table1.uid = table2.uid')->where('age > :age')->groupBy(array('aid'))->having('foo="foo"')->orderBy(array('did'))->limit(10)->offset(20)->bindValues(arra
y('age' => 13));
// 等价于
$db1->query(SELECT * FROM `table1` INNER JOIN `table2` ON `table1`.`uid` = `table2`.`uid` WHERE age > 13 GROUP BY aid HAVING foo="foo" ORDER BY did LIMIT 10 OFFSET 20“);

// 插入
$insert_id = $db1->insert('Persons')->cols(array('Firstname'=>'abc', 'Lastname'=>'efg', 'Sex'=>'M', 'Age'=>13))->query();
等价于
$insert_id = $db1->query("INSERT INTO `Persons` ( `Firstname`,`Lastname`,`Sex`,`Age`) VALUES ( 'abc', 'efg', 'M', 13)");

// 更新
$row_count = $db1->update('Persons')->cols(array('sex'))->where('ID=1')->bindValue('sex', 'F')->query();
// 等价于
$row_count = $db1->update('Persons')->cols(array('sex'=>'F'))->where('ID=1')->query();
// 等价于
$row_count = $db1->query("UPDATE `Persons` SET `sex` = 'F' WHERE ID=1");

// 删除
$row_count = $db1->delete('Persons')->where('ID=9')->query();
// 等价于
$row_count = $db1->query("DELETE FROM `Persons` WHERE ID=9");
 */