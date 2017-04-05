<?php
namespace App\Controller\Auth;

use System\Lib\Controller as BaseController;
use System\Lib\DB;

class AuthController extends BaseController
{
    protected $app_id;//app表里的id（int）
    protected $appsecret;
    public function __construct()
    {
        parent::__construct();
        $host = strtolower($_SERVER['HTTP_HOST']);
        $this->site=DB::table('subsite')->where("domain like '%{$host}|%'")->row();
        if(empty($this->site)){
            echo 'The site was not found！';
            exit;
        }
        $this->checkSign($_GET);

        if (strpos($host, 'wap.') === false) {
            $this->is_wap = false;
            $this->template = 'auth';
        } else {
            $this->is_wap = true;
            $this->template = 'auth_wap';
        }
    }
    public function error()
    {
        echo 'not find page';
    }


    //签名
    protected function checkSign($data)
    {
        $row=DB::table('app')->where('appid=?')->bindValues($data['appid'])->row();
        $this->appsecret=$row['appsecret'];
        $this->app_id=$row['id'];
        if(empty($this->appsecret)){
            echo 'check sign with appid error!';
            exit;
        }
        if($data['sign'] !=$this->getSign($data)){
            echo 'check sign error';
            exit;
        }
    }
    protected function getSign($data)
    {
        if(isset($data['sign'])){
            unset($data['sign']);
        }
        ksort($data);
        if(isset($data['id']) && is_string($data['id'])){
            $data['id']=urlencode($data['id']);
        }
        $jsonStr = json_encode($data);
        $str = strtoupper(md5($jsonStr.$this->appsecret));
        return $str;
    }
}


