<?php
namespace App\Controller\Member;

use System\Lib\Controller as BaseController;

class MemberController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->base_url='/member/';
        $this->template=($this->is_wap===true)?'member_wap':'member';
        $this->control	=($this->uri->get(1)!='')?$this->uri->get(1):'index';
        $this->func		=($this->uri->get(2)!='')?$this->uri->get(2):'index';
        if($this->control !='login' && $this->control !='logout')
        {
            if(empty($this->user_id))
            {
                //$this->redirect('login');
                $url=urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                header("location:/login?url={$url}");
                exit;
            }
            $this->user=m('user/one',array('user_id'=>$this->user_id));
        }
    }
}