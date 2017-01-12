<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/11
 * Time: 16:29
 */

namespace App\Controller\Member;


use App\Model\CarRent;
use App\Model\CarRentRepayment;
use App\Model\System;
use System\Lib\DB;
use System\Lib\Request;

class CarRentController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['carRents']=(new CarRent())->where("user_id=? and status=1")->bindValues($this->user_id)->orderBy('id desc')->get();
        $this->view('carRent',$data);
    }

    public function repayment(CarRent $carRent,Request $request)
    {
        $carRent=$carRent->findOrFail($request->get('id'));
        if($carRent->user_id!=$this->user_id){
            redirect()->back()->with('msg','异常！');
        }
        $repayments=$carRent->Repayments();
        $data['carRent']=$carRent;
        $data['repayments']=$repayments;
        $this->view('carRent',$data);
    }

    public function pay(CarRentRepayment $repayment,Request $request,CarRent $carRent,System $system)
    {
        $repayment=$repayment->findOrFail($request->get('repay_id'));
        if($repayment->user_id!=$this->user_id){
            redirect()->back()->with('error','权限异常！');
        }
        if($repayment->status!=1){
            redirect()->back()->with('error','状态异常！');
        }
        $carRent=$carRent->findOrFail($repayment->car_rent_id);
        $account=$this->user->Account();
        $convert_rate=(float)$system->getCode('convert_rate');
        if(empty($convert_rate)){
            $convert_rate=2.52;
        }
        if($_POST){
            $checkPwd=$this->user->checkPayPwd($request->post('zf_password'),$this->user);
            if($checkPwd!==true){
                redirect()->back()->with('error','支付密码错误！');
            }
            $integral=(float)$request->post('integral');
            if($integral > $account->integral_available){
                redirect()->back()->with('error','可用积分不足！');
            }
            $_money=math($integral,$convert_rate,'/',3);
            $_money=round_money($_money,1,2);
            $money=math($repayment->money,$_money,'-',2);
            if($money > $account->funds_available){
                redirect()->back()->with('error','可用资金不足！');
            }
            try {
                DB::beginTransaction();

                $log = array(
                    'user_id' => $this->user_id,
                    'type' => 'car_repayment',
                    'funds_available' =>'-'.$money,
                    'integral_available' =>'-'.$integral,
                    'funds_available_now'=>$account->funds_available,
                    'integral_available_now'=>$account->integral_available,
                    'label'=>"car_rent:{$carRent->id}",
                    'remark' => "{$carRent->car_name}：{$repayment->title},编号:{$repayment->id}"
                );
                $account->addLog($log);

                $repayment->status=2;
                $repayment->money_yes=$repayment->money;
                $repayment->repayment_yestime=time();
                $repayment->save();

                DB::commit();
                redirect("carRent/repayment/?id={$carRent->id}")->with('msg','付款完成！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        }else{
            $data['convert_rate']=$convert_rate;
            $data['account']=$account;
            $data['carRent']=$carRent;
            $data['repayment']=$repayment;
            $this->view('carRent',$data);
        }
    }

}