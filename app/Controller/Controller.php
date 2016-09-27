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

        $this->site=DB::table('subsite')->where("domain like '%{$host}%'")->row();

        if (strpos($host, 'wap.') === false) {
            $this->is_wap = false;
            $this->template = 'default';
        } else {
            $this->is_wap = true;
            $this->template = 'default_wap';
        }
    }
}