<?php
namespace App\Controller\Api;

use App\Config;
use System\Lib\Controller as BaseController;

class ApiController extends BaseController
{
    public function __construct()
    {
        global $_G;
        parent::__construct();
        $this->control	=$_G['class'];
        $this->func		=$_G['func'];
        ///验证权限
        if(!in_array($this->control,array('index','plugin'))){
            if (abs(time() - $_POST['time']) > 600) {
                die('time over');
            }else{
                if($_POST['sign']!==$this->getSignature($_POST)){
                    die('sign error');
                }
            }
        }
    }
    public function error()
    {
        echo 'not find page';
    }


    //生成签名
    private function getSignature($data)
    {
        $MD5key=Config::$siteKeys[$data['site_id']];
//        $sign_params = array(
//            'site_id' => $data['site_id'],
//            'time' => $data['time'],
//            'user_id' => $data['user_id'],
//            "money" => sprintf("%.5f", $data['money']),
//        );
        unset($data['sign']);
        $sign_params = $data;
        $sign_str = "";
        ksort($sign_params);
        foreach ($sign_params as $key => $val) {
            $sign_str .= sprintf("%s=%s&", $key, $val);
        }
        // echo  $sign_str;print '<br/><br/><br/>';
        return strtoupper(md5($sign_str . strtoupper(md5($MD5key))));
    }
}


