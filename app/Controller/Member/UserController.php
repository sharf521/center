<?php
namespace App\Controller\Member;

use App\Model\AccountBank;
use App\Model\LinkPage;
use App\Model\User;
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

            if($_FILES['headimgurl']['name']!=''){
                $path='/data/upload/headimgurl/';
                $_path=ROOT.'/public'.$path;
                if (!file_exists($_path)) {
                    if (!mkdir($_path, 0777, true)) {
                        redirect()->back()->with('error', 'Can not create tempath directory');
                    }
                }
                $storage = new \Upload\Storage\FileSystem($_path);
                $file = new \Upload\File('headimgurl', $storage);
                $file->setName($this->user_id);
                $file->addValidations(array(
                    new \Upload\Validation\Mimetype(array('image/png', 'image/gif','image/jpeg')),
                    // Ensure file is no larger than 5M (use "B", "K", M", or "G")
                    new \Upload\Validation\Size('5M'),
                ));
                try {
                    if($file->upload()){
                        $this->user->headimgurl=$path.$file->getNameWithExtension();
                    }
                } catch (\Exception $e) {
                    $errors = $file->getErrors();
                    redirect()->back()->with('error', '上传文件失败：'.json_encode($errors));
                }
            }
            $this->user->save();
            redirect()->back()->with('msg', '保存成功！');
        } else {
            $data['user'] = $this->user;
            $this->view('user', $data);
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