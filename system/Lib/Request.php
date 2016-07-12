<?php
namespace System\Lib;

class Request
{
    private $gets=array();
    private $posts=array();
    function __construct()
    {
        //index.php/class/func
        $_path=$_SERVER['PATH_INFO'];
        $arr=explode("/",trim($_path,'/'));
        //index.php/class/func/a/1/b/2  --> $_GET[a]=1 $_GET[b]=2
        foreach ($arr as $i => $v) {
            $v = strip_tags(trim($v));
            $this->gets[$i] = $v;
            //index.php/class/func/a/1/b/2
            //a和b位置 不能为数字
            if ($i > 1 && $i % 2 == 0 && !is_numeric($v)) {
                $v = htmlspecialchars(strip_tags(trim($arr[$i + 1])));
                $this->gets[$arr[$i]] = $v;
            }
        }
        foreach ($_GET as $key=>$val){
            $this->gets[$key] = htmlspecialchars(strip_tags($val));
        }
        foreach ($_POST as $key=>$val){
            //$val=$this->safe_str(strip_tags($val));
            $this->posts[$key] = htmlspecialchars($val);
        }
    }
    public function get($key,$type='')
    {
        $val=isset($this->gets[$key])?$this->gets[$key]:'';
        if($type!==''){
            if($type=='int'){
                $val=(int)$val;
            }
            elseif($type=='float'){
                $val=(float)$val;
            }
            elseif($type===true){
                $val=strip_tags($val);
            }
        }
        return $val;
    }
    public function post($key,$type=''){
        $val=isset($this->posts[$key])?$this->posts[$key]:'';
        if($type!==''){
            if($type=='int'){
                $val=(int)$val;
            }
            elseif($type=='float'){
                $val=(float)$val;
            }
            elseif($type===true){
                $val=strip_tags($val);
            }
        }
        return $val;
    }
    private function safe_str($str)
    {
        if(!get_magic_quotes_gpc())	{
            if( is_array($str) ) {
                foreach($str as $key => $value) {
                    $str[$key] = safe_str($value);
                }
            }else{
                $str = addslashes($str);
            }
        }
        return $str;
    }
}