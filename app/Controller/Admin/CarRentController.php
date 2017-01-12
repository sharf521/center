<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/10
 * Time: 11:45
 */

namespace App\Controller\Admin;


use App\Model\CarRent;
use App\Model\CarRentRepayment;
use System\Lib\Request;

class CarRentController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(CarRent $carRent,Request $request)
    {
        $arr=array(
            'user_id'		=>(int)$_GET['user_id'],
            'contacts'		=>(int)$_GET['contacts'],
            'money'		=>(int)$_GET['money'],
            'group_id'		=>(int)$_GET['group_id'],
        );
        $where = " 1=1";
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if (!empty($arr['contacts'])) {
            $where .= " and contacts='{$arr['contacts']}'";
        }
        $starttime=$request->get('starttime');
        $endtime=$request->get('endtime');
        if(!empty($starttime)){
            $where.=" and created_at>=".strtotime($starttime);
        }
        if(!empty($endtime)){
            $where.=" and created_at<".strtotime($endtime);
        }
        $data['result']=$carRent->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('carRent',$data);
    }

    public function add(CarRent $carRent,Request $request)
    {
        if($_POST){
            $carRent->user_id=(int)$request->post('user_id');
            $carRent->contacts=$request->post('contacts');
            $carRent->tel=$request->post('tel');
            $carRent->area=$request->post('province').'-'.$request->post('city').'-'.$request->post('area');
            $carRent->address=$request->post('address');

            $carRent->car_name=$request->post('car_name');
            $carRent->first_payment_scale=$request->post('first_payment_scale');
            $carRent->first_payment_money=$request->post('first_payment_money');
            $carRent->last_payment_scale=$request->post('last_payment_scale');
            $carRent->last_payment_money=$request->post('last_payment_money');
            $carRent->time_limit=$request->post('time_limit');
            $carRent->month_payment_money=$request->post('month_payment_money');
            $carRent->month_payment_day=$request->post('month_payment_day');
            $carRent->status=0;
            $carRent->save();
            redirect('carRent')->with('msg','添加成功！');
        }else{
            $data=array();
            $this->view('carRent_form',$data);
        }
    }

    public function edit(CarRent $carRent,Request $request)
    {
        $carRent=$carRent->find($request->get('id'));
        if($carRent->status!=0){
            redirect()->back()->with('error','状态异常！');
        }
        if($_POST){
            $carRent->user_id=(int)$request->post('user_id');
            $carRent->contacts=$request->post('contacts');
            $carRent->tel=$request->post('tel');
            $carRent->area=$request->post('province').'-'.$request->post('city').'-'.$request->post('area');
            $carRent->address=$request->post('address');

            $carRent->car_name=$request->post('car_name');
            $carRent->first_payment_scale=$request->post('first_payment_scale');
            $carRent->first_payment_money=$request->post('first_payment_money');
            $carRent->last_payment_scale=$request->post('last_payment_scale');
            $carRent->last_payment_money=$request->post('last_payment_money');
            $carRent->time_limit=$request->post('time_limit');
            $carRent->month_payment_money=$request->post('month_payment_money');
            $carRent->month_payment_day=$request->post('month_payment_day');
            $carRent->save();
            redirect('carRent')->with('msg','保存成功！');
        }else{
            $data['row']=$carRent;
            $this->view('carRent_form',$data);
        }
    }

    public function repayment(CarRent $carRent,Request $request,CarRentRepayment $rentRepayment)
    {
        $carRent=$carRent->findOrFail($request->get('id'));
        $repayments=$carRent->Repayments();
        if($carRent->status==0){
            if(count($repayments)==0){
                $month_payment_day=$carRent->month_payment_day;
                if($month_payment_day<10){
                    $month_payment_day='0'.$month_payment_day;
                }
                for($i=1;$i<=$carRent->time_limit;$i++){
                    $repayment_date=date("Y-m-{$month_payment_day}", strtotime("+{$i} month"));
                    $money=(float)$carRent->month_payment_money;
                    if($money!=0){
                        $rentRepayment->user_id=$carRent->user_id;
                        $rentRepayment->car_rent_id=$carRent->id;
                        $rentRepayment->title="第{$i}期";
                        $rentRepayment->money=$money;
                        $rentRepayment->money_yes=0;
                        $rentRepayment->repayment_time=strtotime($repayment_date);
                        $rentRepayment->repayment_yestime=0;
                        $rentRepayment->last_days=0;
                        $rentRepayment->last_interest=0;
                        $rentRepayment->status=1;
                        $rentRepayment->save();
                    }
                }
                $lastMoney=(float)$carRent->last_payment_money;
                if($lastMoney!=0){
                    $rentRepayment->user_id=$carRent->user_id;
                    $rentRepayment->car_rent_id=$carRent->id;
                    $rentRepayment->title="尾付款";
                    $rentRepayment->money=$lastMoney;
                    $rentRepayment->money_yes=0;
                    $rentRepayment->repayment_time=strtotime($repayment_date);
                    $rentRepayment->repayment_yestime=0;
                    $rentRepayment->last_days=0;
                    $rentRepayment->last_interest=0;
                    $rentRepayment->status=1;
                    $rentRepayment->save();
                }
                $repayments=$carRent->Repayments();
            }
            $carRent->status=1;
            $carRent->save();
        }
        $data['carRent']=$carRent;
        $data['repayments']=$repayments;
        $this->view('carRent',$data);
    }
}