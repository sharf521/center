<?php
namespace App\Controller\Api;

use App\Model\AccountLog;
use System\Lib\DB;

class UserController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取用户信息
    public function info()
    {
        $data=$this->data;
        $row = DB::table('app_user au')->select('u.*')
            ->leftJoin('user u', 'au.user_id=u.id')
            ->where('au.app_id=? and au.openid=?')
            ->bindValues(array($this->app_id, $data['openid']))
            ->row();
        if($row){
            $user=array(
                'openid' => $data['openid'],
                'username' => $row['username'],
                'headimgurl' => 'http://'.$_SERVER['HTTP_HOST'].$row['headimgurl'],
                'nickname' => $row['nickname'],
                'qq' => $row['qq'],
                'tel' => $row['tel'],
                'address' => $row['address'],
                'invite_openid' => '',
                'email' =>$row['email']
            );
            if($row['invite_userid']!=0){
                $user['invite_openid']=DB::table('app_user')->where('app_id=? and user_id=?')
                    ->bindValues(array($this->app_id, $row['invite_userid']))->value('openid');
            }
            return $this->returnSuccess($user);
        }else{
            return $this->returnError('not find openid：'.$data['openid']);
        }
    }
    //验证支付密码
    public function checkPayPwd()
    {
        $data=$this->data;
        $row = DB::table('app_user au')->select('u.*')
            ->leftJoin('user u', 'au.user_id=u.id')
            ->where('au.app_id=? and au.openid=?')
            ->bindValues(array($this->app_id, $data['openid']))
            ->row();
        if($row){
            if ($row['zf_password'] == md5(md5($data['pay_password']) . $row['salt'])) {
                return $this->returnSuccess();
            }
            return $this->returnError(' check failed ');
        }else{
            return $this->returnError('not find openid：'.$data['openid']);
        }
    }

    //获取用户资金
    public function fund()
    {
        $data=$this->data;
        $row = DB::table('app_user au')->select('a.*')
            ->leftJoin('account a', 'au.user_id=a.user_id')
            ->where('au.app_id=? and au.openid=?')
            ->bindValues(array($this->app_id, $data['openid']))
            ->row();
        if($row){
            unset($row['user_id']);
            unset($row['signature']);
            unset($row['created_at']);
            $row['openid']=$data['openid'];
            return $this->returnSuccess($row);
        }else{
            return $this->returnError('not find openid：'.$data['openid']);
        }
    }

    //支出、收入 改变用户资金
    public function receivables(AccountLog $accountLog)
    {
        $data=$this->data;
        $pay_arr=array(
            'pay_no'=>time().rand(10000,90000),
            'app_id'=>$this->app_id,
            'openid'=>$data['openid'],
            'user_id'=>(int)$data['user_id'],
            'body'=>$data['body'],
            'app_order_no'=>$data['order_no'],
            'type'=>$data['type'],
            'status'=>0,
            'remark'=>$data['remark'],
            'label'=>$data['label'],
            'data'=>json_encode($data['data']),
            'signature'=>$data['sign'],
            'addip'=>ip(),
            'created_at'=>time()
        );
        try {
            DB::beginTransaction();

            $pay_order_id=DB::table('pay_order')->insertGetId($pay_arr);
            if(!is_array($data['data'])){
                throw new \Exception("data is not a array!");
            }
            foreach ($data['data'] as $item){
                if(!is_array($item)){
                    throw new \Exception("data item is not a array!");
                }
                $item['pay_no']=$pay_arr['pay_no'];
                $item['app_order_no']=$pay_arr['app_order_no'];
                $item['label']=$data['label'];
                $accountLog->addLog($item);
            }
            DB::table('pay_order')->where("id={$pay_order_id}")->limit(1)->update(array('status'=>1));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError("Failed: " .$e->getMessage());
        }
        return $this->returnSuccess(array('pay_no'=>$pay_arr['pay_no']));
    }

    // 退款
    public function refund(AccountLog $accountLog)
    {
        $data=$this->data;
        $pay_arr=array(
            'pay_no'=>time().rand(10000,90000),
            'app_id'=>$this->app_id,
            'openid'=>$data['openid'],
            'user_id'=>(int)$data['user_id'],
            'body'=>$data['body'].'[退款]',
            'app_order_no'=>$data['order_no'],
            'type'=>$data['type'],
            'status'=>0,
            'remark'=>$data['remark'],
            'label'=>$data['label'],
            'data'=>json_encode($data['data']),
            'signature'=>$data['sign'],
            'addip'=>ip(),
            'created_at'=>time()
        );
        try {
            DB::beginTransaction();

            $pay_order_id=DB::table('pay_order')->insertGetId($pay_arr);

            if(!is_array($data['data'])){
                throw new \Exception("data is not a array!");
            }
            //多个订单
            foreach ($data['data'] as $pay_no_old){
                //获取单个旧订单
                $pay_order=DB::table('pay_order')->where('pay_no=?')->bindValues($pay_no_old)->row();
                if($pay_order['status']==1){
                    $pay_order_data=json_decode($pay_order['data'],true);
                    if(is_array($pay_order_data)){
                        $arr_col=array('funds_available','funds_freeze','integral_available','integral_freeze','security_deposit','turnover_available','turnover_credit');
                        //多个人的资金变化
                        foreach ($pay_order_data as $item){
                            foreach ($item as $i=>$v){
                                if(in_array($i,$arr_col)){
                                    $item[$i]='-'.$v;
                                }
                            }
                            $item['pay_no']=$pay_arr['pay_no'];
                            $item['app_order_no']=$pay_arr['app_order_no'];
                            $item['label']=$pay_order['label'];
                            $accountLog->addLog($item);
                        }
                    }
                    //更改旧订单为2
                    DB::table('pay_order')->where('pay_no=?')->bindValues($pay_no_old)->limit(1)->update(array('status'=>2));
                }
            }
            DB::table('pay_order')->where("id={$pay_order_id}")->limit(1)->update(array('status'=>1));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError("Failed: " .$e->getMessage());
        }
        return $this->returnSuccess(array('pay_no'=>$pay_arr['pay_no']));
    }
}


/*
$params=array(
    'appid'=>'shop',
    'time'=>time(),
    'order_no'=>time().rand(10000,99999),
    'openid'=>'',
    'user_id'=>'',
    'body'=>'test body',
    'type'=>1,
    'remark'=>'test remark',
    'label'=>'label',
    'data'=>array(
        array(
            'openid'=>'3321411135799d72d66280403804743',
            'type'=>1,
            'remark'=>'收入',
            'funds_available'=>10,
            'integral_available'=>100
        ),
        array(
            'openid'=>'5910888675799dc46de8a2857962257',
            'type'=>2,
            'remark'=>'消费了',
            'funds_available'=>-10,
            'integral_available'=>-100
        )
    )
);*/