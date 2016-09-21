<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/27
 * Time: 12:54
 */

namespace App\Model;


use System\Lib\DB;

class AccountLog extends Model
{
    protected $table='account_log';
    public function __construct()
    {
        parent::__construct();
    }

    public function getList($data=array())
    {
        $where=" 1=1";
        if(!empty($data['starttime'])){
            $where.=" and created_at>=".strtotime($data['starttime']);
        }
        if(!empty($data['endtime'])){
            $where.=" and created_at<".strtotime($data['endtime']);
        }
        if(!empty($data['label'])){
            $where.=" and label='{$data['label']}'";
        }
        if(!empty($data['label'])){
            $where.=" and label='{$data['label']}'";
        }
        if(!empty($data['type'])){
            $where.=" and type='{$data['type']}'";
        }
        if(!empty($data['user_id'])){
            $where.=" and user_id='{$data['user_id']}'";
        }
        if(!empty($data['pay_no'])){
            $where.=" and pay_no='{$data['pay_no']}'";
        }
        if(!empty($data['app_order_no'])){
            $where.=" and app_order_no='{$data['app_order_no']}'";
        }
        $result=$this->where($where)->orderBy('id desc')->pager(intval($_GET['page']));
        foreach ($result['list'] as $index=>$value){
            $change='';
            $now='';
            if($value->funds_available!=0){
                if($value->funds_available>0){
                    $change.="可用资金：+".(float)$value->funds_available."<br>";
                }else{
                    $change.="可用资金：".(float)$value->funds_available."<br>";
                }
                $now.="当前可用资金：".(float)$value->funds_available_now."<br>";
            }
            if($value->funds_freeze!=0){
                if($value->funds_freeze>0){
                    $change.="冻结资金：+".(float)$value->funds_freeze."<br>";
                }else{
                    $change.="冻结资金：".(float)$value->funds_freeze."<br>";
                }
                $now.="当前冻结资金：".(float)$value->funds_freeze_now."<br>";
            }
            if($value->integral_available!=0){
                if($value->integral_available>0){
                    $change.="可用积分：+".(float)$value->integral_available."<br>";
                }else{
                    $change.="可用积分：".(float)$value->integral_available."<br>";
                }
                $now.="当前可用积分：".(float)$value->integral_available_now."<br>";
            }
            if($value->integral_freeze!=0){
                if($value->integral_freeze>0){
                    $change.="冻结积分：+".(float)$value->integral_freeze."<br>";
                }else{
                    $change.="冻结积分：".(float)$value->integral_freeze."<br>";
                }
                $now.="当前冻结积分：".(float)$value->integral_freeze_now."<br>";
            }
            if($value->security_deposit!=0){
                if($value->security_deposit>0){
                    $change.="保证金：+".(float)$value->security_deposit."<br>";
                }else{
                    $change.="保证金：".(float)$value->security_deposit."<br>";
                }
                $now.="当前保证金：".(float)$value->security_deposit."<br>";
            }
            if($value->turnover_available!=0){
                if($value->turnover_available>0){
                    $change.="可用周转金：+".(float)$value->turnover_available."<br>";
                }else{
                    $change.="可用周转金：".(float)$value->turnover_available."<br>";
                }
                $now.="可用周转金：{$value->turnover_available_now}<br>";
            }
            if($value->turnover_credit!=0){
                if($value->turnover_credit){
                    $change.="周转金额度：+".(float)$value->turnover_credit."<br>";
                }else{
                    $change.="周转金额度：".(float)$value->turnover_credit."<br>";
                }
                $now.="当前周转金额度：".(float)$value->turnover_credit_now."<br>";
            }
            $result['list'][$index]->change=$change;
            $result['list'][$index]->now=$now;
        }
        return $result;
    }

    public function user()
    {
       return $this->hasOne('User','id','user_id');
    }
}