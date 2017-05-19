<?php
namespace App\Controller\Member;

use App\Controller\Controller;
use App\Model\User;
use System\Lib\DB;

class MemberController extends Controller
{
    protected $user;
    protected $user_id;
    public function __construct()
    {
        parent::__construct();

        if($this->is_wap){
            $this->template = 'member_wap';
        }else{
            $this->template = 'member';
        }
        if($this->control !='login' && $this->control !='logout'){
            if(empty($this->user_id)){
                $url=urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                redirect("/login?url={$url}");
                exit;
            }
        }
        $this->user=(new User())->findOrFail($this->user_id);
        if(trim($this->user->headimgurl)==''){
            $this->user->headimgurl='/themes/member/images/no-img.jpg';
        }
    }

    public function error()
    {
        echo 'not find page';
    }
}