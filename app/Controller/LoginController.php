<?php
namespace App\Controller;

use App\Helper;
use App\Model\User;
use System\Lib\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    private function doLogin($data)
    {
        $user=new User();
        $request=new Request();
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
    }

    public  function  index(Request $request,User $user)
    {
        if($_POST){
            $request->checkToken();
            $data = array(
                'username' => $request->post('username'),
                'password' => $request->post('password')
            );
            $this->doLogin($data);
        }else{
            if($this->is_wap && $this->is_inWeChat){
                $wechat_openid=Helper::getWechatOpenId();
                $user=(new User())->where('wechat_openid=?')->bindValues($wechat_openid)->first();
                if($user->is_exist){
                    $_data=array(
                        'direct'=>1,
                        'id'=>$user->id
                    );
                    $this->doLogin($_data);
                }
            }
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