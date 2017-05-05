<?php
namespace App\Controller\Member;

use App\Model\Account;
use App\Model\AccountCash;
use App\Model\AccountLog;
use App\Model\AccountRecharge;
use App\Model\Order;
use App\Model\Rebate;
use App\Model\System;
use App\Model\User;
use System\Lib\DB;
use System\Lib\Request;

class OrderController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Account $account)
    {
        $data['account'] = $account->find($this->user_id);
        $this->view('account', $data);
    }
    
    public function pay(Request $request, Order $order,System $system,Account $account)
    {
        $order=$order->where("order_sn=?")->bindValues($request->get('sn'))->first();
        $account=$account->find($this->user_id);
        $convert_rate=(float)$system->getCode('convert_rate');
        if(empty($convert_rate)){
            $convert_rate=2.52;
        }
        $data['order']=$order;
        $data['account']=$account;
        $data['convert_rate']=$convert_rate;
        $data['title_herder'] = '资金流水';
        $this->view('order_pay', $data);
    }

    //流水
    public function log(Request $request, Order $order)
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
        $data['result'] = $order->where($where)->orderBy('id desc')->pager($page);
        $data['title_herder'] = '资金流水';
        $this->view('order_log', $data);
    }
}