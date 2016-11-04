<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/3
 * Time: 14:35
 */

namespace App\Controller\Platform;


use App\Model\Account;
use App\Model\TeaLog;
use App\Model\TeaMoney;
use App\Model\TeaUser;
use App\Model\User;
use System\Lib\DB;
use System\Lib\Request;

class AccountController extends PlatformController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function convertIn(Request $request,Account $account,TeaMoney $teaMoney)
    {
        $user_id=$this->user_id;
        $account =$account->find($user_id);
        if($_POST){
            $total=(float)$request->post('total');
            if($total < 50){
                redirect()->back()->with('error','最少兑换50电子币！');
            }
            if($total > $account->funds_available){
                redirect()->back()->with('error','您的可用余额不足！');
            }
            $checkPwd=$this->user->checkPayPwd($request->post('zf_password'),$this->user);
            if($checkPwd!==true){
                redirect()->back()->with('error','支付密码错误！');
            }
            try {
                DB::beginTransaction();

                $log = array(
                    'user_id' => $user_id,
                    'type' => 'fundsToTeaMoney',
                    'funds_available' => '-'.$total,
                    'remark' => '余额兑换电子币'
                );
                $account->addLog($log);

                $log = array(
                    'user_id' => $user_id,
                    'type' => 'fundsToTeaMoney',
                    'money' => $total,
                    'remark' => '兑换电子币'
                );
                $teaMoney->addLog($log);

                DB::commit();
                redirect('account/log')->with('msg','兑换完成！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        }else{
            $data['account']=$account;
            $this->view('account',$data);
        }
    }

    public function convertOut(Request $request,Account $account,TeaMoney $teaMoney)
    {
        $user_id=$this->user_id;
        $teaMoney =$teaMoney->find($user_id);
        if($_POST){
            $total=(float)$request->post('total');
            if($total < 50){
                redirect()->back()->with('error','最少兑换50！');
            }
            if($total > $teaMoney->money){
                redirect()->back()->with('error','您的电子币不足！');
            }
            $checkPwd=$this->user->checkPayPwd($request->post('zf_password'),$this->user);
            if($checkPwd!==true){
                redirect()->back()->with('error','支付密码错误！');
            }
            try {
                DB::beginTransaction();

                $log = array(
                    'user_id' => $user_id,
                    'type' => 'teaMoneyToFunds',
                    'funds_available' =>math($total,0.95,'*',2),
                    'remark' => $total.'电子币兑换现金'
                );
                $account->addLog($log);

                $log = array(
                    'user_id' => $user_id,
                    'type' => 'teaMoneyToFunds',
                    'money' => '-'.$total,
                    'remark' => '兑换现金'
                );
                $teaMoney->addLog($log);

                DB::commit();
                redirect('account/log')->with('msg','兑换完成！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        }else{
            $data['teaMoney']=$teaMoney;
            $this->view('account',$data);
        }
    }

    //站内转帐
    public function payToUser(TeaMoney $teaMoney,Request $request,TeaUser $teaUser,User $user)
    {
        $user_id=$this->user_id;
        $teaMoney =$teaMoney->find($user_id);
        if($_POST){
            $total=(float)$request->post('total');
            $to_username=$request->post('to_username');
            $remark=$request->post('remark');
            if($this->username==$to_username){
                redirect()->back()->with('error','不能给自己转帐！');
            }
            if(empty($to_username)){
                redirect()->back()->with('error','输入对方用户名！');
            }else{
                $to_uid=$user->where('username=?')->bindValues($to_username)->value('id','int');
                if($to_uid==0){
                    redirect()->back()->with('error','对方用户名不存在！');
                }else{
                    $teaUser=$teaUser->find($to_uid);
                    if(!$teaUser->is_exist){
                        redirect()->back()->with('error','对方不存在！');
                    }
                }
            }

                if($total > $teaMoney->money){
                    redirect()->back()->with('error','电子币不足！');
                }


            $checkPwd=$this->user->checkPayPwd($request->post('zf_password'),$this->user);
            if($checkPwd!==true){
                redirect()->back()->with('error','支付密码错误！');
            }
            try {
                DB::beginTransaction();

                $log = array(
                    'user_id' => $user_id,
                    'type' => 'sendToUser',
                    'money' =>'-'.$total,
                    'remark' => "转出给：{$to_username}，备注：{$remark}"
                );
                $teaMoney->addLog($log);
                $log = array(
                    'user_id' => $to_uid,
                    'type' => 'getFromUser',
                    'money' =>$total,
                    'remark' => "{$this->username}：转入，备注：{$remark}"
                );
                $teaMoney->addLog($log);

                DB::commit();
                redirect('account/log')->with('msg','站内转帐完成！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        }else{
            $data['teaMoney']=$teaMoney;
            $this->view('account',$data);
        }
    }

    public function log(TeaLog $teaLog)
    {
        $arr=array(
            'type'		=>$_GET['type'],
            'user_id'		=>(int)$_GET['user_id'],
            'money'		=>(int)$_GET['money']
        );

        $where = " user_id={$this->user_id}";
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if (!empty($arr['money'])) {
            $where .= " and money={$arr['money']}";
        }
        if (!empty($arr['type'])) {
            $where .= " and type='{$arr['type']}'";
        }

        $data['result']=$teaLog->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('account',$data);
    }
}