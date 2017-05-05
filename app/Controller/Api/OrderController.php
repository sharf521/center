<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/4
 * Time: 11:56
 */

namespace App\Controller\Api;


use App\Model\Order;
use System\Lib\DB;

class OrderController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function firstOrNew(Order $order)
    {
        $data=$this->data;
        $order_arr=array(
            'order_sn'=>time().rand(10000,90000),
            'app_id'=>$this->app_id,
            'openid'=>$data['openid'],
            'user_id'=>$this->getUserId($data['openid']),
            'title'=>$data['title'],
            'money'=>$data['money'],
            'app_order_sn'=>$data['order_sn'],
            'app_order_pc_url'=>$data['order_pc_url'],
            'app_order_wap_url'=>$data['order_wap_url'],
            'in_out'=>$data['in_out'],//1收，2支
            'other_nickname'=>$data['other_nickname'],
            'other_openid'=>$data['other_openid'],
            'typeid'=>$data['typeid'],
            'label'=>$data['label'],
            'remark'=>$data['remark'],
            'sign'=>$data['sign'],
            'addip'=>ip(),
            'created_at'=>time(),
            'status'=>1 //成功，如果失败会回滚
        );

        try {
            DB::beginTransaction();

            if((int)$order_arr['user_id']==0){
                throw new \Exception("find user error !");
            }
            if($data['other_openid']!=''){
                $order_arr['other_uid']=$this->getUserId($data['other_openid']);
                if((int)$order_arr['other_uid']==0){
                    throw new \Exception("find other_uid error !");
                }
            }
            $order=$order->where('app_order_sn=?')->bindValues($data['order_sn'])->first();
            if($order->is_exist){
                $pay_no=$order->order_sn;
            }else{
                $pay_no=$order_arr['order_sn'];
                DB::table('order')->insert($order_arr);
                DB::commit();
            }
            return $this->returnSuccess(array('pay_no'=>$pay_no));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError("Failed: " .$e->getMessage());
        }
    }
}