<?php
namespace App\Controller\Member;

use App\Model\Account;
use App\Model\AccountCash;
use App\Model\AccountLog;
use App\Model\AccountRecharge;
use App\Model\Rebate;
use App\Model\System;
use System\Lib\DB;
use System\Lib\Request;

class AccountController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Account $account)
    {
        $data['account'] =$account->find($this->user_id);
        $this->view('account',$data);
    }

    //线下冲值
    public function recharge()
    {
        if ($_POST) {
            $error = "";
            if (empty($_POST['money'])) {
                $error .= "充值金额不能为空<br>";
            }
            if ($_POST['money'] < 1000) {
                $error .= "充值金额不能低于1000元<br>";
            }
            if (empty($_POST['remark'])) {
                $error .= "充值备注必填<br>";
            }
            if ($error != "") {
                redirect()->back()->with('error', $error);
            } else {
                $data = array(
                    'trade_no' => time() . rand(1000, 9999),
                    'user_id' =>$this->user_id,
                    'status' => 0,
                    'money' => sprintf("%.2f", (float)$_POST['money']),
                    'fee' => 0,
                    'payment' => $_POST['payment'],
                    'type' => 2,
                    'remark' => $_POST['remark'],
                    'created_at'=>time(),
                    'addip' => ip()
                );
                DB::table('account_recharge')->insert($data);
                redirect('account/rechargeLog')->with('msg', '操作成功，等待财务审核！');
            }
        } else {
            $data['title_herder']='我要充值';
            $data['user']=$this->user;
            $this->view('accountRecharge',$data);
        }
    }

    public function rechargeLog(AccountRecharge $recharge,Request $request)
    {
//        $log = array();
//        $log['user_id'] = 1;
//        $log['type'] = 1;
//        $log['funds_available'] = 10;
//        $log['remark'] = "在线充值：";
//        $log['label']='AA';
//        $accountLog->addLog($log);

        $page=$request->get('page');
        $starttime=$request->get('starttime');
        $endtime=$request->get('endtime');
        $where=" user_id=".$this->user_id;
        if(!empty($starttime)){
            $where.=" and created_at>=".strtotime($starttime);
        }
        if(!empty($endtime)){
            $where.=" and created_at<".strtotime($endtime);
        }
        $data['result']=$recharge->where($where)->orderBy('id desc')->pager($page);
        $data['title_herder']='充值记录';
        $this->view('accountRecharge',$data);
    }

    //提现
    public function cash(System $system,Request $request,AccountCash $accountCash)
    {
        $cash_rate=(float)$system->getCode('cash_rate');
        $account=$this->user->Account();
        $bank=$this->user->Bank();
        if ($_POST) {
            $total=(float)$request->post('total');
            if($total < 50 || $total > 50000){
                redirect()->back()->with('error','提现范围50元-50000元！');
            }
            if($total > $account->funds_available){
                redirect()->back()->with('error','提现金额超过可提现金额！');
            }
            $checkPwd=$this->user->checkPayPwd($request->post('zf_password'),$this->user);
            if($checkPwd!==true){
                redirect()->back()->with('error','支付密码错误！');
            }
            $fee=round_money(math($total,$cash_rate,'*',3),2,2);
            if($fee<5){$fee=5;}
            $accountCash->user_id=$this->user_id;
            $accountCash->name=$this->user->name;
            $accountCash->bank=$bank->bank;
            $accountCash->branch=$bank->branch;
            $accountCash->card_no=$bank->card_no;
            $accountCash->total=$total;
            $accountCash->credited=math($total,$fee,'-',2);
            $accountCash->fee=$fee;
            $accountCash->status=1;
            $accountCash->addip=ip();
            $insertId=$accountCash->save(true);

            $account=new Account();
            $log = array();
            $log['user_id'] = $this->user_id;
            $log['type'] = 'cash_frost';
            $log['funds_available'] ='-'.$total;
            $log['funds_freeze']=$total;
            $log['label'] = "cash_{$insertId}";
            $log['remark'] = "提现ID：{$insertId}";
            $account->addLog($log);
            redirect('account/cashLog')->with('msg','申请提现成功，静等审核！');
        } else {
            $data['cash_rate']=$cash_rate;
            $data['account']=$account;
            $data['bank']=$bank;
            $data['title_herder']='我要提现';
            $this->view('accountCash',$data);
        }
    }

    public function cashLog(Request $request,AccountCash $accountCash)
    {
        $page=$request->get('page');
        $starttime=$request->get('starttime');
        $endtime=$request->get('endtime');
        $where=" user_id=".$this->user_id;
        if(!empty($starttime)){
            $where.=" and created_at>=".strtotime($starttime);
        }
        if(!empty($endtime)){
            $where.=" and created_at<".strtotime($endtime);
        }
        $data['result']=$accountCash->where($where)->orderBy('id desc')->pager($page);

        $data['title_herder']='提现记录';
        $this->view('accountCash',$data);
    }

    //资金流水
    public function log(Request $request,AccountLog $accountLog)
    {
        $arr=array(
            'user_id'=>$this->user_id,
            'starttime'=>$request->get('starttime'),
            'endtime'=>$request->get('endtime')
        );
        $data['result']=$accountLog->getList($arr);
        $data['title_herder']='资金流水';
        $this->view('account',$data);
    }

    //积分换现金
    public function convert(Account $account,Request $request,AccountLog $accountLog,Rebate $rebate)
    {
        $user_id=$this->user_id;
        $account =$account->find($user_id);
        if($_POST){
            $total=(float)$request->post('total');
            if($total < 50){
                redirect()->back()->with('error','最少兑换50积分！');
            }
            if($total > $account->integral_available){
                redirect()->back()->with('error','要兑换的积分少于您的可用积分！');
            }
            $checkPwd=$this->user->checkPayPwd($request->post('zf_password'),$this->user);
            if($checkPwd!==true){
                redirect()->back()->with('error','支付密码错误！');
            }
            $money=math($total,2.52,'/',3);
            $money=math($money,0.69,'*',2);//保留两位小数，第三位舍去
            try {
                DB::beginTransaction();

                $log = array(
                    'user_id' => $user_id,
                    'type' => 'convertFunds',
                    'integral_available' =>'-'.$total,
                    'funds_available' => $money,
                    'remark' => '积分兑换现金'
                );
                $account->addLog($log);
                //加入对列
                $arr = array(
                    'site_id' => 0,
                    'typeid' => 3,
                    'user_id' =>$user_id,
                    'money' =>$total,
                );
                $rebate->addRebate($arr);

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
}