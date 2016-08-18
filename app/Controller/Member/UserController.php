<?php
namespace App\Controller\Member;

use App\Model\AccountBank;
use App\Model\LinkPage;
use App\Model\Region;
use App\Model\User;
use App\Model\UserInfo;
use System\Lib\Request;

class UserController  extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        
    }
    public function userInfo(Request $request)
    {
        if ($_POST) {
            $this->user->tel = $request->post('tel');
            $this->user->qq = $request->post('qq');
            $this->user->address = $request->post('address');
            $this->user->headimgurl=$request->post('headimgurl');
            $this->user->save();
            redirect()->back()->with('msg', '保存成功！');
        } else {
            $data['user'] = $this->user;
            $this->view('user', $data);
        }
    }
    public function realName(Request $request,Region $region)
    {
        $user= $this->user;
        $userInfo=$user->UserInfo();
        if($_POST){
            $name=$request->post('name');
            $sex=$request->post('sex');
            $card_no=$request->post('card_no');
            $province=$request->post('province');
            $city=$request->post('city');
            $county=$request->post('county');
            $card_pic1=$request->post('card_pic1');
            $card_pic2=$request->post('card_pic2');
            if(empty($name)){
                redirect()->back()->with('error', '姓名不能为空！');
            }
            if(! $userInfo->isIdCard($card_no)){
                redirect()->back()->with('error', '请输入正确的身份证号！');
            }
            if(empty($province) || empty($city) || empty($county)){
                redirect()->back()->with('error', '请选择籍贯！');
            }
            if(empty($card_pic1) || empty($card_pic2)){
                redirect()->back()->with('error', '请上传身份证照片！');
            }

            $userInfo->name=$name;
            $userInfo->sex=$sex;
            $userInfo->card_no=$card_no;
            $userInfo->province=$province;
            $userInfo->city=$city;
            $userInfo->county=$county;
            $userInfo->card_pic1=$card_pic1;
            $userInfo->card_pic2=$card_pic2;
            $userInfo->card_status=1;
            $userInfo->user_id=$this->user_id;
            $userInfo->save();
            redirect()->back()->with('msg', '操作成功，等待管理员审核！');
        }else{
            $userInfo->provinceName=$region->getName($userInfo->province);
            $userInfo->cityName=$region->getName($userInfo->city);
            $userInfo->countyName=$region->getName($userInfo->county);
            $data['provinceArray']=$region->getList(0);
            $data['userInfo']=$userInfo;
            $data['user'] = $user;

            $data['title_herder']='实名认证';
            $this->view('realName', $data);
        }
    }
    
    public function bank(AccountBank $accountBank,Request $request,LinkPage $linkPage)
    {
        $bank=$accountBank->find($this->user->id);
        if($_POST){
            $bank->bank = $request->post('bank');
            $bank->branch = $request->post('branch');
            $bank->card_no = $request->post('card_no');
            if ($bank->save()) {
                redirect()->back()->with('msg', '保存成功！');
            } else {
                redirect()->back()->with('error', '保存失败！');
            }
        }else{
            $bank->selBank=$linkPage->echoLink('account_bank',$bank->bank,array('name'=>'bank'));
            $data['bank']=$bank;
            $data['userInfo']=$bank->UserInfo();
            $data['title_herder']='我的银行卡';
            $this->view('user',$data);
        }
    }

    public function changePwd(User $user)
    {
        if ($_POST) {
            if ($_POST['password'] != $_POST['sure_password']) {
                $error = "两次输入密码不同！";
            } else {
                $post = array(
                    'id' => $this->user_id,
                    'old_password' => $_POST['old_password'],
                    'password' => $_POST['password'],
                );
                $result=$user->updatePwd($post);
                if ($result === true) {
                    redirect()->back()->with('msg','修改成功!');
                } else {
                    $error = $result;
                }
            }
            redirect()->back()->with('error',$error);
        } else {
            $data['title_herder']='修改密码';
            $this->view('user',$data);
        }
    }
    
    public function changePayPwd(User $user)
    {
        if ($_POST) {
            if ($_POST['zf_password'] != $_POST['sure_password']) {
                $error = "两次输入密码不同！";
            } else {
                $post = array(
                    'id' => $this->user_id,
                    'old_password' => $_POST['old_password'],
                    'zf_password' => $_POST['zf_password'],
                );
                $result=$user->updateZfPwd($post);
                if ($result === true) {
                    redirect()->back()->with('msg','修改成功!');
                } else {
                    $error = $result;
                }
            }
            redirect()->back()->with('error',$error);
        } else {
            $data['title_herder']='修改支付密码';
            $this->view('user',$data);
        }
    }
    
    /*  找回支付密码step1  获取邮件*/
    public function getPayPwd()
    {
        if ($_POST) {
            if ($_POST['valicode'] != $_SESSION['randcode']) {
                $error = '验证码不正确！';
            } else {
                $data=array(
                    'user_id'=>$this->user_id,
                    'username'=>$this->user->username,
                    'email'=>$this->user->email,
                    'type'=>'getPayPwd'
                );
                $data['msg'] = GetpwdMsg($data);
                $sendEmailTime=session()->get('sendEmailTime');
                if(!empty($sendEmailTime) && $sendEmailTime+60*2>time()){
                    $error="请2分钟后再次请求。";
                }else{
                    $result = mail_send($data['email'],'用户找回支付密码',$data['msg']);
                    if ($result == true) {
                        session()->set('sendEmailTime', time());
                        redirect()->back()->with('msg', "信息已发送到{$data['email']}，请注意查收您邮箱的邮件");
                    } else {
                        $error = "发送失败，请跟管理员联系";
                    }
                }
            }
            redirect()->back()->with('error',$error);
        } else {
            $data['user']=$this->user;
            $data['title_herder']='找回支付密码';
            $this->view('user',$data);
        }
    }

    /*  找回支付密码step2  重置密码*/
    public function resetPayPwd(Request $request)
    {
        $id = $request->id;
        if (empty($id)) {
            $error = '您的操作有误，请勿乱操作';
        } else {
            $data = explode(",", authcode(trim($id), "DECODE"));
            $user_id = (int)$data[0];
            $start_time = $data[1];
            if ($user_id != $this->user_id) {
                $error = '您的操作有误，请勿乱操作';
            } elseif (time() > $start_time + 60 * 60) {
                $error = '此链接已经过期，请重新申请';
            }
        }
        if ($_POST) {
            if ($error != '') {
                redirect()->back()->with('error', $error);
                exit;
            }
            if ($request->post('zf_password') != $request->post('sure_password')) {
                $error = "两次输入密码不同！";
            } else {
                $post = array(
                    'id' => $this->user_id,
                    'zf_password' => $request->post('zf_password'),
                );
                $result = $this->user->updateZfPwd($post);
                if ($result === true) {
                    redirect()->back()->with('msg', '修改成功!');
                } else {
                    $error = $result;
                }
            }
            redirect()->back()->with('error', $error);
        } else {
            $data['error'] = $error;
            $data['title_herder']='重置支付密码';
            $this->view('user', $data);
        }
    }

    public function logout()
    {
        $this->user->logout();
        $this->redirect('/');
        exit;
    }
}