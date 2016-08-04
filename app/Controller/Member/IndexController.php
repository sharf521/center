<?php
namespace App\Controller\Member;

use App\Model\User;
use System\Lib\DB;

class IndexController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['account'] = DB::table('account')->where('user_id')->bindValues($this->user_id)->row();
        $data['title_herder']='帐户中心';
        $this->view('manage', $data);
    }

    public function logout(User $user)
    {
        $user->logout();
        $this->redirect('/login');
        exit;
    }

    //修改密码
    public function changepwd(User $user)
    {
        if ($_POST) {
            $id = $this->user_id;
            if ($_POST['password'] != $_POST['sure_password']) {
                $error = "两次输入密码不同！";
            } else {
                $post = array(
                    'id' => $id,
                    'old_password' => $_POST['old_password'],
                    'password' => $_POST['password'],
                );
                $result = $user->updatePwd($post);
                if ($result === true) {
                    redirect()->back()->with('msg', '修改成功!');
                } else {
                    $error = $result;
                }
            }
            redirect()->back()->with('error', $error);
        } else {
            $this->view('pwd');
        }
    }
}