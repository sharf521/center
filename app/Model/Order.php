<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/20
 * Time: 11:15
 * 总订单表
 */

namespace App\Model;


use System\Lib\DB;

class Order extends  Model
{
    protected $table='order';
    protected $dates=array('created_at','payed_at');
    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        $order_arr=array(
            'order_sn'=>time().rand(10000,90000),
            'app_id'=>$data['app_id'],
            'openid'=>$data['openid'],
            'user_id'=>$data['user_id'],
            'title'=>$data['title'],
            'money'=>$data['money'],
            'app_order_sn'=>$data['order_sn'],
            'app_order_pc_url'=>$data['order_pc_url'],
            'app_order_wap_url'=>$data['order_wap_url'],
            'in_out'=>$data['in_out'],//1收，2支
            'other_nickname'=>$data['other_nickname'],
            'other_openid'=>$data['other_openid'],
            'typeid'=>$data['typeid'],
            'mode'=>$data['mode'],
            'label'=>$data['label'],
            'remark'=>$data['remark'],
            'sign'=>$data['sign'],
            'addip'=>ip(),
            'created_at'=>time(),
            'status'=>1 //成功，如果失败会回滚
        );
        if((int)$order_arr['user_id']==0){
            throw new \Exception("find user error !");
        }
        $pay_no='';
        if($data['order_sn']!=''){
            $order=(new Order())->where('app_order_sn=?')->bindValues($data['order_sn'])->first();
            if($order->is_exist){
                $pay_no=$order->order_sn;
            }
        }
        if($pay_no==''){
            $pay_no=$order_arr['order_sn'];
            DB::table('order')->insert($order_arr);
        }
        return $pay_no;
    }
}