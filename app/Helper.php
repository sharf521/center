<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/6
 * Time: 11:42
 */

namespace App;


use App\Model\User;
use PHPQRCode\QRcode;
use System\Lib\Request;

class Helper
{
    public static function betweenDays ($date1, $date2)
    {
        $second1 = strtotime($date1);
        $second2 = strtotime($date2);
        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }

    public static function getWechatOpenId()
    {
        $request=new Request();
        session()->remove('wechat_openid');
        $get_wechat_openid = $request->get('wechat_openid');
        if(empty($get_wechat_openid)){
            $this_url='http://'.$_SERVER['HTTP_HOST'].urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
            $url = "http://wx02560f146a566747.wechat.yuantuwang.com/user/getWeChatOpenId/?url={$this_url}";
            redirect($url);
        }else{
            $wechat_openid=$get_wechat_openid;
            session()->set('wechat_openid',$wechat_openid);
            return $wechat_openid;
        }
    }
    public static function getSystemParam($code)
    {
        $value = app('\App\Model\System')->getCode($code);
        if ($code == 'convert_rate') {
            $value=(float)$value;
            if ($value==0) {
                $value = 2.52;
            }
        }
        return $value;
    }

    public static function getQqLink($qq=123456)
    {
        return "<a href='http://wpa.qq.com/msgrd?v=3&uin={$qq}&site=qq&menu=yes' target='_blank'><img src='http://wpa.qq.com/pa?p=1:{$qq}:4' alt='QQ'></a>";
    }

    public static function QRcode($txt,$filePath='goods',$fileName=0,$level='L')
    {
        if(is_int($fileName)){
            $img_url="/data/QRcode/{$filePath}/".ceil(intval($fileName)/2000)."/";
        }else{
            $img_url="/data/QRcode/{$filePath}/";
        }
        $file_dir = ROOT . "/public".$img_url;
        if (!is_dir($file_dir)) {
            mkdir($file_dir, 0777, true);
        }
        $file_name=$fileName.'.png';
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

    /**
     * @param $str
     * @param string $operation='D' 解密
     * @return string
     */
    public static function encrypt($str,$operation='E')
    {
        $key=self::getSystemParam('md5key').'06l3d3zZ';
        $key=substr($key,0,8);
        if($operation!='D'){
            //关键数据DES加密
            $encrypt=$str;
            // 根據 PKCS#7 RFC 5652 Cryptographic Message Syntax (CMS) 修正 Message 加入 Padding
            $block = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_ECB);
            $pad = $block - (strlen($encrypt) % $block);
            $encrypt .= str_repeat(chr($pad), $pad);
            // 不需要設定 IV 進行加密
            $passcrypt = mcrypt_encrypt(MCRYPT_DES, $key, $encrypt, MCRYPT_MODE_ECB);
            return base64_encode($passcrypt);
        }else{
            $str=base64_decode($str);
            $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
            $len = strlen($str);
            $block = mcrypt_get_block_size('des', 'ecb');
            $pad = ord($str[$len - 1]);
            return substr($str, 0, $len - $pad);
        }
    }
}