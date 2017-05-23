<?php

namespace App\Controller;


use App\Model\User;
use System\Lib\Request;

class GetPwdController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request,User $user)
    {
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
                $data['msg'] = GetpwdMsg($data);

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
            $loginUrl="/login/";
            $url=$request->get('url');
            if($url){
                $loginUrl.="?url={$url}";
            }
            echo $loginUrl;
            $data['loginUrl']=$loginUrl;
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
            $data['error'] = $error;
            if($error!=''){
                echo $error;
                exit;
            }
            $data['title_herder']='重置密码';
            $data['user']=$user->findOrFail($user_id);
            $this->view('getPwd', $data);
        }
    }
}