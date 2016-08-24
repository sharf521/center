<?php
namespace App\Controller\Member;

use App\Model\User;
use System\Lib\Controller as BaseController;

class MemberController extends BaseController
{
    protected $user;
    public function __construct()
    {
        parent::__construct();
        global $_G;
        $this->base_url='/member/';
        if (strpos(strtolower($_SERVER['HTTP_HOST']), 'wap.') === false) {
            $this->is_wap = false;
            $this->template = 'member';
        } else {
            $this->is_wap = true;
            $this->template = 'member_wap';
        }
        $this->control	=$_G['class'];
        $this->func		=$_G['func'];
        if($this->control !='login' && $this->control !='logout'){
            if(empty($this->user_id)){
                $url=urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                redirect("/login?url={$url}");
                exit;
            }
        }
        $user=new User();
        $this->user=$user->findOrFail($this->user_id);
        if(trim($this->user->headimgurl)==''){
            $this->user->headimgurl='/themes/member/images/no-img.jpg';
        }
    }

    public function error()
    {
        echo 'not find page';
    }
}