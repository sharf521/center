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
        $data['account'] = DB::table('account')->where('user_id=?')->bindValues($this->user_id)->row();
        $data['title_herder']='帐户中心';
        $this->view('manage', $data);
    }

    public function logout(User $user)
    {
        $user->logout();
        $this->redirect('/login');
        exit;
    }
}