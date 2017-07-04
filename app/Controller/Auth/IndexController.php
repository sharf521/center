<?php
namespace App\Controller\Auth;

use App\Helper;
use App\Model\AppUser;
use App\Model\User;
use System\Lib\DB;
use System\Lib\Request;

class IndexController extends AuthController
{
    public function __construct()
    {
        parent::__construct();
    }

    private function doLogin($data)
    {
        $user=new User();
        $request=new Request();
        $appUser=new AppUser();
        $redirect_uri=$request->redirect_uri;
        $result = $user->login($data);
        if ($result === true) {
            $user_id=session('user_id');
            $openid=$appUser->getOpenId($user_id,$this->app_id);
            if($openid==''){
                $openid=$appUser->create($user_id,$this->app_id);
            }
            $sign=$this->getSign(array('openid'=>$openid));
            if(strpos($redirect_uri,'?')===false){
                $url=$redirect_uri.'?openid='.$openid.'&sign='.$sign;
            }else{
                $url=$redirect_uri.'&openid='.$openid.'&sign='.$sign;
            }
            redirect($url);
        } else {
            $error = $result;
        }
        redirect()->back()->with('error',$error);
    }

    //index.php/auth/login/?appid=shop&redirect_uri=http://www.yuantuwang.com/&sign=1F07E77550FEBAD99EC245CBDBC7EF29
    //http://center.test.cn:8000/index.php/auth/login/?appid=shop&redirect_uri=/&sign=6F25A41551491FF8A05112E9709688B4&r=admin
    public function login(Request $request,User $user,AppUser $appUser)
    {
        $get=array(
            'appid'=>$request->appid,
            'sign'=>$request->sign,
            'redirect_uri'=>$request->redirect_uri
        );
        $_url="?appid={$get['appid']}&redirect_uri={$get['redirect_uri']}&sign={$get['sign']}";
        if(isset($_GET['r'])){
            $get['r']=$request->get('r');
            $_url.="&r={$get['r']}";
        }
        $regUrl="/auth/register/".$_url;
        $getPwdUrl="/auth/getPwd/".$_url;
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
            $data['regUrl']=$regUrl;
            $data['getPwdUrl']=$getPwdUrl;
            $data['title_herder']='用户登陆';
            $this->view('login',$data);
        }
    }

    public function register(Request $request,User $user,AppUser $appUser)
    {
        $get=array(
            'appid'=>$request->appid,
            'sign'=>$request->sign,
            'redirect_uri'=>$request->redirect_uri
        );
        $_url="/auth/login/?appid={$get['appid']}&redirect_uri={$get['redirect_uri']}&sign={$get['sign']}";
        if(isset($_GET['r'])){
            $get['r']=$request->get('r');
            $data['r']=$get['r'];
            $_url.="&r={$get['r']}";
        }
        //$this->checkSign($get);
        if($_POST){
            $request->checkToken();
            $data = array(
                'username' => $request->post('username'),
                'email'=>$request->post('email'),
                'password' => $request->post('password'),
                'sure_password'=>$request->post('sure_password'),
                'invite_user'=>$request->post('invite_user'),
                'app_id'=>$this->app_id
            );
            $result = $user->register($data);
            if ($result === true) {
                $user_id=session('user_id');
                $openid=$appUser->create($user_id,$this->app_id);
                $sign=$this->getSign(array('openid'=>$openid));
                if(strpos($get['redirect_uri'],'?')===false){
                    $url=$get['redirect_uri'].'?openid='.$openid.'&sign='.$sign;
                }else{
                    $url=$get['redirect_uri'].'&openid='.$openid.'&sign='.$sign;
                }
                redirect($url);
            } else {
                $error = $result;
            }
            redirect()->back()->with('error',$error);
        }else{
            $data['_url']=$_url;
            $data['title_herder']='新用户注册';
            $this->view('register',$data);
        }
    }
}