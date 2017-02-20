<?php
namespace App\Controller;

use System\Lib\Controller as BaseController;
use System\Lib\DB;

class Controller extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $host = strtolower($_SERVER['HTTP_HOST']);
        $this->site=DB::table('subsite')->where("domain like '%{$host}|%'")->row();
        if(empty($this->site)){
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