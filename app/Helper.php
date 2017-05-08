<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/6
 * Time: 11:42
 */

namespace App;


use PHPQRCode\QRcode;

class Helper
{
    public static function getQqLink($qq=123456)
    {
        return "<a href='http://wpa.qq.com/msgrd?v=3&uin={$qq}&site=qq&menu=yes' target='_blank'><img src='http://wpa.qq.com/pa?p=1:{$qq}:4' alt='QQ'></a>";
    }

    public static function QRcode($txt,$type='goods',$type_id=0,$level='L')
    {
        $img_url="/data/QRcode/{$type}/".ceil(intval($type_id)/2000)."/";
        $file_dir = ROOT . "/public".$img_url;
        if (!is_dir($file_dir)) {
            mkdir($file_dir, 0777, true);
        }
        $file_name=$type_id.'.png';
        $file_path=$file_dir.$file_name;
        $img_url.=$file_name;
        if(!file_exists($file_path)){
            QRcode::png($txt,$file_path,$level,4,2);
        }
        return $img_url;
    }

    /**
     * //获取顶级域名
     * @return array|string
     */
    public static function getTopDomain($port=0)
    {
        $domain=strtolower($_SERVER['HTTP_HOST']);
        if($port==0 && strpos($domain,':')!==false){
            //去除端口
            $domain=explode(':',$domain);
            $domain=$domain[0];
        }
        $domain_arr=explode('.',$domain);
        if($domain_arr[count($domain_arr)-2]=='com'){
            $domain=$domain_arr[count($domain_arr)-3].'.'.$domain_arr[count($domain_arr)-2].'.'.$domain_arr[count($domain_arr)-1];
        }else{
            $domain=$domain_arr[count($domain_arr)-2].'.'.$domain_arr[count($domain_arr)-1];
        }
        return $domain;
    }
}