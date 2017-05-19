<?php
namespace App\Controller;

use App\Model\SubSite;
use System\Lib\Controller as BaseController;
use System\Lib\DB;

class Controller extends BaseController
{
    protected $user_id;
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
        if (strpos($host, 'wap.') === false) {
            $this->is_wap = false;
            $this->template = 'default';
        } else {
            $this->is_wap = true;
            $this->template = 'default_wap';
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
}