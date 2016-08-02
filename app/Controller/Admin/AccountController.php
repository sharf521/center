<?php
namespace App\Controller\Admin;


use App\Model\AccountLog;
use System\Lib\Request;

class AccountController extends AdminController
{

    public function log(AccountLog $accountLog,Request $request)
    {
        $_GET['user_id']=(int)$request->get('user_id');
        $arr=array(
            'user_id'=>$_GET['user_id'],
            'label'=>$request->get('label'),
            'starttime'=>$request->get('starttime'),
            'endtime'=>$request->get('endtime')
        );
        $data['result']=$accountLog->getList($arr);
        $this->view('account',$data);
    }
}