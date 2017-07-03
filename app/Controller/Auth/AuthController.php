<?php
namespace App\Controller\Auth;

use App\Model\SubSite;
use System\Lib\Controller as BaseController;
use System\Lib\DB;

class AuthController extends BaseController
{
    protected $app_id;//app表里的id（int）
    protected $appsecret;
    public function __construct()
    {
        parent::__construct();
        $this->user_id = session('user_id');
        $this->username = session('username');
        $this->user_typeid = session('usertype');
        $host = strtolower($_SERVER['HTTP_HOST']);
        $this->site=(new SubSite())->where("domain like '%{$host}|%'")->orderBy('id')->first();
        if($this->site->is_exist){
            $arr_domain=explode('|',$this->site->domain);
            $this->site->pc_url='http://'.$arr_domain[0];
            $this->site->wap_url='http://'.$arr_domain[1];
        }else{
            echo 'The site was not found！';
            exit;
        }
        if(isset($_GET['id']) && is_string($_GET['id'])){
            $_GET['id']=urlencode($_GET['id']);
        }
        $this->checkSign($_GET);
        if (strpos($host, 'wap.') === false) {
            $this->is_wap = false;
            $this->template = 'auth';
        } else {
            $this->is_wap = true;
            $this->template = 'auth_wap';
        }
        $agent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if(strpos($agent, 'MicroMessenger') === false && strpos($agent, 'Windows Phone') === false)
        {
            $this->is_inWeChat=false;
            //die('Sorry！非微信浏览器不能访问');
        }else{
            $this->is_inWeChat=true;
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
        if(isset($data['wechat_openid'])){
            unset($data['wechat_openid']);
        }
        if(isset($data['sign'])){
            unset($data['sign']);
        }
        ksort($data);
        $jsonStr = json_encode($data);
        $str = strtoupper(md5($jsonStr.$this->appsecret));
        return $str;
    }
}


