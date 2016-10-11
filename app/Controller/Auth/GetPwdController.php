<?php
namespace App\Controller\Auth;

use App\Model\User;
use System\Lib\Request;

class GetPwdController extends AuthController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request,User $user)
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
        $logUrl="/auth/login/".$_url;
        if($_POST){
            $error='';
            $request->checkToken();
            $data = array(
                'username' => $request->post('username'),
                'email' => $request->post('email')
            );
            if(empty($data['username']) || empty($data['email'])){
                $error='用户名和邮箱不能为空！';
            }
            $user = $user->where($data)->first();
            if(!$user->is_exist){
                $error='用户不存在！';
            }
            if($error==''){
                $data=array(
                    'user_id'=>$user->id,
                    'username'=>$user->username,
                    'email'=>$user->email,
                    'type'=>'getPwd'
                );
                $data['msg'] = $this->getPwdMsg($data+$get);

                $sendEmailTime=session()->get('sendEmailTime');
                if(!empty($sendEmailTime) && $sendEmailTime+60*2>time()){
                    $error="请2分钟后再次请求。";
                }else{
                    $result = mail_send($data['email'],'用户找回密码',$data['msg']);
                    if ($result == true) {
                        session()->set('sendEmailTime', time());
                        redirect()->back()->with('msg', "信息已发送到{$data['email']}，请注意查收您邮箱的邮件");
                    } else {
                        $error = "发送失败，请跟管理员联系";
                    }
                }
            }
            redirect()->back()->with('error',$error);
        }else{
            $data['logUrl']=$logUrl;
            $data['title_herder']='找回密码';
            $this->view('getPwd',$data);
        }
    }

    public function updatePwd(Request $request,User $user)
    {
        $id = $request->id;
        if (empty($id)) {
            $error = '您的操作有误，请勿乱操作';
        } else {
            $data = explode(",", authcode(trim($id), "DECODE"));
            $user_id = (int)$data[0];
            $start_time = $data[1];
            if($user_id==0){
                $error = '您的操作有误，请勿乱操作';
            }
            if (time() > $start_time + 60*60) {
                $error = '此链接已经过期，请重新申请';
            }
        }
        if ($_POST) {
            if ($error != '') {
                redirect()->back()->with('error', $error);
                exit;
            }
            if ($request->post('password') != $request->post('sure_password')) {
                $error = "两次输入密码不同！";
            } else {
                $post = array(
                    'id' => $user_id,
                    'password' => $request->post('password'),
                );
                $result = $user->updatePwd($post);
                if ($result === true) {
                    redirect()->back()->with('msg', '密码重置成功，请登陆!');
                } else {
                    $error = $result;
                }
            }
            redirect()->back()->with('error', $error);
        } else {
            $get=array(
                'appid'=>$request->appid,
                'sign'=>$request->sign,
                'redirect_uri'=>$request->redirect_uri
            );
            $sign=$this->getSign($get);
            $_url="?appid={$get['appid']}&redirect_uri={$get['redirect_uri']}&sign={$sign}";
            if(isset($_GET['r'])){
                $get['r']=$request->get('r');
                $_url.="&r={$get['r']}";
            }
            $logUrl="/auth/login/".$_url;

            if($error!=''){
                echo $error;
                exit;
            }
            $data['logUrl']=$logUrl;
            $data['title_herder']='重置密码';
            $data['user']=$user->findOrFail($user_id);
            $this->view('getPwd', $data);
        }
    }

    //找回密码邮件内容
    private function getPwdMsg($data = array())
    {
        $user_id = $data['user_id'];
        $username = $data['username'];
        $webname = '找回密码';
        $active_id = authcode($user_id . "," . time(), "ENCODE");

        $get=array(
            'appid'=>$data['appid'],
            'sign'=>$data['sign'],
            'redirect_uri'=>$data['redirect_uri']
        );
        $_url="&appid={$get['appid']}&redirect_uri={$get['redirect_uri']}";
        if(isset($data['r'])){
            $get['r']=$data['r'];
            $_url.="&r={$data['r']}";
        }
        $get['id']="".$active_id;

        $sign=$this->getSign($get);
        $_url.="&sign={$sign}";

        $_url = "http://{$_SERVER['HTTP_HOST']}/index.php/auth/getPwd/updatePwd?id={$active_id}{$_url}";
        $tital = "修改登录密码";
        $send_email_msg = '
	<div style="font-size:14px; ">
	<div style="padding: 10px 0px;">
		<h1 style="padding: 0px 15px; margin: 0px;">
			<a title="用户中心" href="http://' . $_SERVER['HTTP_HOST'] . '/" target="_blank" swaped="true">' . $webname . '</a>
		</h1>

		<div style="padding: 2px 20px 30px;">
			<p>亲爱的 <span style="color: rgb(196, 0, 0);">' . $username . '</span> , 您好！</p>
			<p>请点击下面的链接' . $tital . '。</p>
			<p style="overflow: hidden; width: 100%; word-wrap: break-word;"><a title="点击' . $tital . '" href="' . $_url . '" target="_blank" swaped="true">' . $_url . '</a>
			<br><span style="color: rgb(153, 153, 153);">(如果链接无法点击，请将它拷贝到浏览器的地址栏中)</span></p>

			<p style="text-align: right;"><br>用户中心 敬启</p>
			<p><br>此为自动发送邮件，请勿直接回复！</p>
		</div>
	</div>
</div>
		';
        return $send_email_msg;
    }
}