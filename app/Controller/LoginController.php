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