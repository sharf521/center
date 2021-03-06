<?php
namespace App\Controller\Member;

use App\Helper;
use App\Model\Account;
use App\Model\AccountCash;
use App\Model\AccountLog;
use App\Model\AccountRecharge;
use App\Model\Rebate;
use App\Model\System;
use App\Model\User;
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
        $data['account'] = $account->find($this->user_id);

        $convert_rate=Helper::getSystemParam('convert_rate');
        $rebate=(new Rebate())->select("sum(money) as money,sum(money_rebate) as money_rebate")->where("user_id=?")->bindValues($this->user_id)->first();
        $data['expectedIntegralReward']=math($rebate->money,$convert_rate,'*',5);
        $data['alreadyIntegralReward']=math($rebate->money_rebate,$convert_rate,'*',5);
        $this->view('account', $data);
    }

    //线下冲值
    public function recharge(Request $request)
    {
        if ($this->is_wap) {
            $wechat_openid = $this->user->wechat_openid;
            if (empty($wechat_openid)) {
                $get_wechat_openid = $request->get('wechat_openid');
                if(empty($get_wechat_openid)){
                    $this_url='http://'.$_SERVER['HTTP_HOST'].urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                    $url = "http://wx02560f146a566747.wechat.yuantuwang.com/user/getWeChatOpenId/?url={$this_url}";
                    redirect($url);
                }else{
                    $wechat_openid=$get_wechat_openid;
                }
            }
            $redirect_url = 'http://' . $_SERVER['HTTP_HOST'] . '/member/account/';
            $url = "http://centerwap.yuantuwang.com/wechat/recharge/?id={$this->user_id}&wechat_openid={$wechat_openid}&money=0&url={$redirect_url}";
            redirect($url);
            exit;
        }
        if ($_POST) {
            $error = "";
            $money = (float)$request->post('money');
            if ($money == 0) {
                $error .= "充值金额不能为空<br>";
            }
            if ($money < 1000 || $money > 50000) {
                $error .= "线下充值金额在1千至5万之间<br>";
            }
            if (empty($_POST['remark'])) {
                $error .= "充值备注必填<br>";
            }
            if ($error != "") {
                redirect()->back()->with('error', $error);
            } else {
                $data = array(
                    'trade_no' => time() . rand(1000, 9999),
                    'user_id' => $this->user_id,
                    'status' => 0,
                    'money' => sprintf("%.2f", $money),
                    'fee' => 0,
                    'payment' => $_POST['payment'],
                    'type' => 2,
                    'remark' => $_POST['remark'],
                    'created_at' => time(),
                    'addip' => ip()
                );
                DB::table('account_recharge')->insert($data);
                redirect('account/rechargeLog')->with('msg', '操作成功，等待财务审核！');
            }
        } else {
            $money = (float)$request->get('money');
            if ($money != 0) {
                $data['money'] = $money;
            }
            $data['title_herder'] = '我要充值';
            $data['user'] = $this->user;
            $this->view('accountRecharge', $data);
        }
    }

    public function rechargeLog(AccountRecharge $recharge, Request $request)
    {
//        $log = array();
//        $log['user_id'] = 1;
//        $log['type'] = 1;
//        $log['funds_available'] = 10;
//        $log['remark'] = "在线充值：";
//        $log['label']='AA';
//        $accountLog->addLog($log);

        $page = $request->get('page');
        $starttime = $request->get('starttime');
        $endtime = $request->get('endtime');
        $where = " user_id=" . $this->user_id;
        if (!empty($starttime)) {
            $where .= " and created_at>=" . strtotime($starttime);
        }
        if (!empty($endtime)) {
            $where .= " and created_at<" . strtotime($endtime);
        }
        $data['result'] = $recharge->where($where)->orderBy('id desc')->pager($page);
        $data['title_herder'] = '充值记录';
        $this->view('accountRecharge', $data);
    }

    //提现
    public function cash(System $system, Request $request, AccountCash $accountCash)
    {
        $cash_rate = (float)$system->getCode('cash_rate');
        $cash_tax_rate = (float)$system->getCode('cash_tax_rate');
        $cash_money_min = (float)$system->getCode('cash_money_min');
        $cash_money_max = (float)$system->getCode('cash_money_max');
        $cash_fee_min = (float)$system->getCode('cash_fee_min');
        $cash_fee_max = (float)$system->getCode('cash_fee_max');

        $account = $this->user->Account();
        $bank = $this->user->Bank();
        if ($_POST) {
            $total = (float)$request->post('total');
            if ($total < $cash_money_min || $total > $cash_money_max) {
                redirect()->back()->with('error', "提现范围{$cash_money_min}元-{$cash_money_max}元！");
            }
            if ($total > $account->funds_available) {
                redirect()->back()->with('error', '提现金额超过可提现金额！');
            }
            $checkPwd = $this->user->checkPayPwd($request->post('zf_password'), $this->user);
            if ($checkPwd !== true) {
                redirect()->back()->with('error', '支付密码错误！');
            }
            $fee = round_money(math($total, $cash_rate, '*', 3), 2, 2);
            if ($cash_fee_min>0 && $fee < $cash_money_min) {
                $fee = $cash_fee_min;
            }
            if ($cash_fee_max>0 && $fee > $cash_fee_max) {
                $fee = $cash_fee_max;
            }
            $taxFee = round_money(math($total, $cash_tax_rate, '*', 3), 2, 2);//税点
            $credited=math($total, $fee, '-', 2);
            $credited=math($credited, $taxFee, '-', 2);

            $accountCash->user_id = $this->user_id;
            $accountCash->name = $this->user->name;
            $accountCash->bank = $bank->bank;
            $accountCash->branch = $bank->branch;
            $accountCash->card_no = $bank->card_no;
            $accountCash->total = $total;
            $accountCash->credited = $credited;
            $accountCash->fee = $fee;
            $accountCash->tax_fee=$taxFee;
            $accountCash->status = 1;
            $accountCash->addip = ip();
            $insertId = $accountCash->save(true);

            $account = new Account();
            $log = array();
            $log['user_id'] = $this->user_id;
            $log['type'] = 'cash_apply';
            $log['funds_available'] = '-' . $total;
            $log['funds_freeze'] = $total;
            $log['label'] = "cash_{$insertId}";
            $log['remark'] = "提现ID：{$insertId}";
            $account->addLog($log);
            redirect('account/cashLog')->with('msg', '申请提现成功，静等审核！');
        } else {
            //提示说明
            $promptList=array();
            $promptList[0]="单笔提现金额最低{$cash_money_min}元，最高{$cash_money_max}元";
            $promptList[1]="单笔提现费率".math($cash_rate,100,'*',2)."%，单笔提现手续费最低{$cash_fee_min}元";
            if($cash_fee_max>0){
                $promptList[1].= "，单笔提现手续费最高{$cash_fee_max}元";
            }
            if($cash_tax_rate>0){
                $promptList[2].= "代扣税点：".math($cash_tax_rate,100,'*',2)."%";
            }
            //$promptList[3]="每个工作日的下午5点之前提交的提现申请，T+1个工作日到账，每个工作日的下午5点之后提交的提现申请，T+2个工作日到账";
            $data['promptList']=$promptList;

            $data['account'] = $account;
            $data['bank'] = $bank;
            $data['title_herder'] = '我要提现';
            $this->view('accountCash', $data);
        }
    }

    public function cashLog(Request $request, AccountCash $accountCash)
    {
        $page = $request->get('page');
        $starttime = $request->get('starttime');
        $endtime = $request->get('endtime');
        $where = " user_id=" . $this->user_id;
        if (!empty($starttime)) {
            $where .= " and created_at>=" . strtotime($starttime);
        }
        if (!empty($endtime)) {
            $where .= " and created_at<" . strtotime($endtime);
        }
        $data['result'] = $accountCash->where($where)->orderBy('id desc')->pager($page);

        $data['title_herder'] = '提现记录';
        $this->view('accountCash', $data);
    }

    //资金流水
    public function log(Request $request, AccountLog $accountLog)
    {
        $arr = array(
            'user_id' => $this->user_id,
            'starttime' => $request->get('starttime'),
            'endtime' => $request->get('endtime')
        );
        $data['result'] = $accountLog->getList($arr);
        $data['title_herder'] = '资金流水';
        $this->view('account', $data);
    }

    //积分换现金
    public function convert(Account $account, Request $request, AccountLog $accountLog, Rebate $rebate)
    {
        $user_id = $this->user_id;
        $account = $account->find($user_id);
        if ($_POST) {
            $total = (float)$request->post('total');
            if ($total < 50) {
                redirect()->back()->with('error', '最少兑换50积分！');
            }
            if ($total > $account->integral_available) {
                redirect()->back()->with('error', '要兑换的积分少于您的可用积分！');
            }
            $checkPwd = $this->user->checkPayPwd($request->post('zf_password'), $this->user);
            if ($checkPwd !== true) {
                redirect()->back()->with('error', '支付密码错误！');
            }
            $money = math($total, 2.52, '/', 3);
            $money = math($money, 0.69, '*', 2);//保留两位小数，第三位舍去
            try {
                DB::beginTransaction();

                $log = array(
                    'user_id' => $user_id,
                    'type' => 'convertFunds',
                    'integral_available' => '-' . $total,
                    'funds_available' => $money,
                    'remark' => '积分兑换现金'
                );
                $account->addLog($log);
                //加入对列
                $arr = array(
                    'site_id' => 0,
                    'typeid' => 3,
                    'user_id' => $user_id,
                    'money' => $total,
                    'remark'=>'积分兑换现金'
                );
                $rebate->addRebate($arr);

                DB::commit();
                redirect('account/log')->with('msg', '兑换完成！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error = "Failed: " . $e->getMessage();
                redirect()->back()->with('error', $error);
            }
        } else {
            $data['account'] = $account;
            $this->view('account', $data);
        }
    }

    //站内转帐
    public function payToUser(Account $account, Request $request, User $user)
    {
        $transferFundsRate=(float)Helper::getSystemParam('transferFundsRate');
        $transferIntegralRate=(float)Helper::getSystemParam('transferIntegralRate');
        $transferFundsRateText=math($transferFundsRate,100,'*').'%';
        $transferIntegralRateText=math($transferIntegralRate,100,'*').'%';
        $user_id = $this->user_id;
        $account = $account->find($user_id);
        if ($_POST) {
            $total = (float)$request->post('total');
            $type = (int)$request->post('type');
            $to_username = $request->post('to_username');
            $remark = $request->post('remark');
            if ($total < 0 || !in_array($type, array(1, 2))) {
                redirect()->back()->with('error', '输入错误！');
            }
            if ($this->username == $to_username) {
                redirect()->back()->with('error', '不能给自己转帐！');
            }
            if (empty($to_username)) {
                redirect()->back()->with('error', '输入对方用户名！');
            } else {
                $to_uid = $user->where('username=?')->bindValues($to_username)->value('id', 'int');
                if ($to_uid == 0) {
                    redirect()->back()->with('error', '对方用户名不存在！');
                }
            }
            if ($type == 1) {
                if ($total > $account->funds_available) {
                    redirect()->back()->with('error', '可用资金不足！');
                }
            } else {
                if ($total > $account->integral_available) {
                    redirect()->back()->with('error', '可用积分不足！');
                }
            }

            $checkPwd = $this->user->checkPayPwd($request->post('zf_password'), $this->user);
            if ($checkPwd !== true) {
                redirect()->back()->with('error', '支付密码错误！');
            }
            try {
                DB::beginTransaction();
                $_fRate=math(1,$transferFundsRate,'-');
                $_iRate=math(1,$transferIntegralRate,'-');
                if ($type == 1) {
                    $log = array(
                        'user_id' => $user_id,
                        'type' => 'sendToUser',
                        'funds_available' => '-' . $total,
                        'remark' => "转出现金给：{$to_username}，备注：{$remark}"
                    );
                    $account->addLog($log);
                    $log = array(
                        'user_id' => $to_uid,
                        'type' => 'getFromUser',
                        'funds_available' => math($total,$_fRate,'*',2),
                        'remark' => "{$this->username}：转入现金，己扣除{$transferFundsRateText}的手续费，备注：{$remark}"
                    );
                    $account->addLog($log);
                } else {
                    $log = array(
                        'user_id' => $user_id,
                        'type' => 'sendToUser',
                        'integral_available' => '-' . $total,
                        'remark' => "转出现金给：{$to_username}，备注：{$remark}"
                    );
                    $account->addLog($log);
                    $log = array(
                        'user_id' => $to_uid,
                        'type' => 'getFromUser',
                        'integral_available' =>math($total,$_iRate,'*',5),
                        'remark' => "{$this->username}：转入积分，己扣除{$transferIntegralRateText}的手续费，备注：{$remark}"
                    );
                    $account->addLog($log);
                }
                //转帐给合作商，合作商获得奖励
                if((new User())->isPartner($to_uid)){
                    if($type==1){
                        $transferFundsRateReward=(float)Helper::getSystemParam('transferFundsRateReward');
                        $convert_rate=Helper::getSystemParam('convert_rate');
                        $rewardMoney=math($total,$transferFundsRateReward,'*',2);
                        $reward=math($rewardMoney,$convert_rate,'*',5);
                        $remark="{$this->username}：转入现金，合作商获得奖励";
                    }else{
                        $transferIntegralRateReward=(float)Helper::getSystemParam('transferIntegralRateReward');
                        $reward=math($total,$transferIntegralRateReward,'*',5);
                        $remark="{$this->username}：转入积分，合作商获得奖励";
                    }
                    if($reward>0){
                        $log = array(
                            'user_id' => $to_uid,
                            'type' => 'getFromUserReward',
                            'integral_available' =>$reward,
                            'remark' => $remark
                        );
                        $account->addLog($log);
                    }
                }
                DB::commit();
                redirect('account/log')->with('msg', '站内转帐完成！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error = "Failed: " . $e->getMessage();
                redirect()->back()->with('error', $error);
            }
        } else {
            $data['transferFundsRateText']=$transferFundsRateText;
            $data['transferIntegralRateText']=$transferIntegralRateText;
            $data['account'] = $account;
            $data['title_herder'] = '站内转帐';
            $this->view('account_payToUser', $data);
        }
    }
}