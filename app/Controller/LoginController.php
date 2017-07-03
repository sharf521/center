<?php
namespace App\Controller;

use App\Model\User;
use System\Lib\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public  function  index(Request $request,User $user)
    {
        if($_POST){
            $request->checkToken();
            $data = array(
                'username' => $request->post('username'),
                'password' => $request->post('password')
            );
            $result = $user->login($data);
            if ($result === true) {
                $url=$request->get('url');
                if(empty($url)){
                    redirect('member/');
                }else{
                    header("location:$url");exit;
                }
            } else {
                $error = $result;
            }
            redirect()->back()->with('error',$error);
        }else{
            if($this->is_wap && $this->is_inWeChat){
                $get_wechat_openid = $request->get('wechat_openid');
                if(empty($get_wechat_openid)){
                    $this_url='http://'.$_SERVER['HTTP_HOST'].urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                    $url = "http://wx02560f146a566747.wechat.yuantuwang.com/user/getWeChatOpenId/?url={$this_url}";
                    redirect($url);
                }else{
                    $wechat_openid=$get_wechat_openid;
                }
            }echo $wechat_openid;
            $regUrl="/register/";
            $getPwdUrl="/getPwd/";
            $url=$request->get('url');
            if($url){
                $url=urlencode($url);
                $regUrl.="?url={$url}";
                $getPwdUrl.="?url={$url}";
            }
            $data['regUrl']=$regUrl;
            $data['getPwdUrl']=$getPwdUrl;
            $data['title_herder']='用户登陆';
            $this->view('login',$data);
        }
    }
}