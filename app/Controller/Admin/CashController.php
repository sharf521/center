<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/11
 * Time: 17:14
 */

namespace App\Controller\Admin;

use App\Model\AccountCash;
use App\Model\AccountLog;
use App\Model\User;
use System\Lib\Request;

class CashController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(AccountCash $accountCash,Request $request,User $user)
    {
        $where=" 1=1";
        $page=$request->get('page');
        $type=$request->get('type');
        $status=$request->get('status');
        $username=$request->get('username');
        $starttime=$request->get('starttime');
        $endtime=$request->get('endtime');
        if(!empty($username)){
            $user=$user->where("username='{$username}'")->first();
            $where.=" and user_id='{$user->id}'";
        }
        if(!empty($status)){
            $where.=" and status=".$status;
        }
        if(!empty($type)){
            $where.=" and type=".$type;
        }
        if(!empty($starttime)){
            $where.=" and created_at>=".strtotime($starttime);
        }
        if(!empty($endtime)){
            $where.=" and created_at<".strtotime($endtime);
        }
        $data['result']=$accountCash->where($where)->orderBy('id desc')->pager($page);
        $this->view('cash',$data);
    }

    public function check(AccountCash $accountCash,Request $request)
    {
        $id=$request->id;
        $cash = $accountCash->findOrFail($id);
        if ($_POST) {
            $verify_remark=$request->post('verify_remark');
            if (empty($_POST['status'])) {
                redirect()->back()->with('error', '审核状态必选');
            }
            if (empty($verify_remark)) {
                redirect()->back()->with('error', '审核备注不能为空');
            }
            if ($cash->status != 1) {
                redirect()->back()->with('error', '己处理，勿重复处理！');
            }
            if ($request->post('status') == 2) {
                $cash->status = 2;
                $cash->verify_userid=$this->user_id;
                $cash->verify_at=time();
                $cash->verify_remark=$verify_remark;
                $cash->save();
            } else {
                $accountLog = new AccountLog();
                $log = array(
                    'user_id' => $cash->user_id,
                    'type' => 'cash_fail',
                    'funds_available' =>$cash->total,
                    'funds_freeze' =>'-'.$cash->total,
                    'label'=>"cash_{$cash->id}",
                    'remark' => '提现ID：' . $cash->id
                );
                $accountLog->addLog($log);
            }
            redirect("cash/?page={$request->page}")->with('msg', '操作成功！！');
        } else {
            $data['row'] = $cash;
            $this->view('cash', $data);
        }
    }

    //打款
    public function checkEnd(AccountCash $accountCash,Request $request)
    {
        $id=$request->id;
        $cash = $accountCash->findOrFail($id);
        if ($_POST) {
            $verify_remark=$request->post('verify_remark');
            if (empty($_POST['status'])) {
                redirect()->back()->with('error', '审核状态必选');
            }
            if (empty($verify_remark)) {
                redirect()->back()->with('error', '审核备注不能为空');
            }
            if ($cash->status != 2) {
                redirect()->back()->with('error', '己处理，勿重复处理！');
            }
            if ($request->post('status') == 4) {
                $cash->status = 4;
                $cash->remittance_userid=$this->user_id;
                $cash->remittance_at=time();
                $cash->remittance_remark=$verify_remark;
                $cash->save();

                $accountLog = new AccountLog();
                $log = array(
                    'user_id' => $cash->user_id,
                    'type' => 'cash_success',
                    'funds_freeze' =>'-'.$cash->total,
                    'label'=>"cash_{$cash->id}",
                    'remark' => '提现ID：' . $cash->id
                );
                $accountLog->addLog($log);
            } else {
                $cash->status = 1;
                $cash->save();
            }
            redirect("cash/?page={$request->page}")->with('msg', '操作成功！！');
        } else {
            $data['row'] = $cash;
            $this->view('cash', $data);
        }
    }
}