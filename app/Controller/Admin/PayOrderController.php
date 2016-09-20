<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/20
 * Time: 11:07
 */

namespace App\Controller\Admin;


use App\Model\PayOrder;
use System\Lib\Request;

class PayOrderController extends  AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function  index(Request $request,PayOrder $payOrder)
    {
        $where = " 1=1";
        if (!empty($_GET['type_id'])) {
            $where .= " and type_id={$_GET['type_id']}";
        }

        if(!empty($_GET['pay_no'])){
            $where.=" and pay_no='{$_GET['pay_no']}'";
        }
        if(!empty($_GET['app_order_no'])){
            $where.=" and app_order_no='{$_GET['app_order_no']}'";
        }
        if(!empty($_GET['label'])){
            $where.=" and label='{$_GET['label']}'";
        }

        $starttime=$request->get('starttime');
        $endtime=$request->get('endtime');
        if(!empty($starttime)){
            $where.=" and created_at>=".strtotime($starttime);
        }
        if(!empty($endtime)){
            $where.=" and created_at<".strtotime($endtime);
        }
        $data['result'] =$payOrder->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('payOrder', $data);
    }
}