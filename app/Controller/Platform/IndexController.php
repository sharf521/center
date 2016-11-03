<?php
namespace App\Controller\Platform;

use App\Model\TeaUser;
use App\Model\User;

class IndexController extends PlatformController
{
    public function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view('manage', '');
    }

    function logout(User $user)
    {
        $user->logout();
        $this->redirect('login');
        exit;
    }

    public function login(User $user)
    {
        if ($_POST) {
            if ($_POST['valicode'] != $_SESSION['randcode']) {
                $error = '验证码不正确！';
            } else {
                $data = array(
                    'admin' => true,
                    'username' => trim($_POST['username']),
                    'password' => $_POST['password']
                );
                $result = $user->login($data);
                if ($result === true) {
                    redirect('index');
                } else {
                    $error = $result;
                }
            }
            redirect()->back()->with('error',$error);
        } else {
            $this->view('login');
        }
    }


    public function top()
    {
        $this->view('manage.top');
    }
    public function left()
    {
        $this->view('manage.left');
    }
    public function manage()
    {
        $this->view('manage.middle');
    }
    public function end()
    {
        $this->view('manage.end');
    }
}