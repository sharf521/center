<?php
namespace App\Controller\Admin;


use App\Model\AccountLog;
use App\Model\LinkPage;
use System\Lib\Request;

class AccountController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function log(AccountLog $accountLog,Request $request,LinkPage $linkPage)
    {
        $arr=array(
            'pay_no'=>$request->get('pay_no'),
            'app_order_no'=>$request->get('app_order_no'),
            'user_id'=>(int)$request->get('user_id'),
            'label'=>$request->get('label'),
            'type'=>$request->get('type'),
            'starttime'=>$request->get('starttime'),
            'endtime'=>$request->get('endtime')
        );
        $data['result']=$accountLog->getList($arr);
        $data['account_type']=$linkPage->echoLink('account_type',$_GET['type'],array('name'=>'type'));
        $this->view('account',$data);
    }
}