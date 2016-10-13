<?php

namespace App\Model;

use System\Lib\DB;

class Account extends Model
{
    protected  $table='account';
    protected  $primaryKey='user_id';
    public function __construct()
    {
        parent::__construct();
    }
    function addLog($data)
    {
        $insert=false;
        if(isset($data['user_id'])){
            $user_id=(int)$data['user_id'];
        }elseif (isset($data['openid'])){
            $user_id=DB::table('app_user')->where("openid=?")->bindValues($data['openid'])->value('user_id','int');
        }
        if($user_id>0){
            $fp = fopen(ROOT."/public/data/money.txt" ,'w+');
            if(flock($fp , LOCK_EX))
            {
                $account=DB::table('account')->where("user_id={$user_id}")->row();
                if(empty($account)){
                    $insert=true;
                    $account=array(
                        'user_id'=>$user_id,
                        'funds_available'=>0,
                        'funds_freeze'=>0,
                        'integral_available'=>0,
                        'integral_freeze'=>0,
                        'security_deposit'=>0,
                        'turnover_available'=>0,
                        'turnover_credit'=>0,
                        'created_at'=>time()
                    );
                }
                $log=array(
                    'user_id'=>$user_id,
                    'pay_no'=>$data['pay_no'],
                    'app_id'=>(int)$data['app_id'],
                    'app_order_no'=>$data['app_order_no'],
                    'type'=>$data['type'],
                    'remark'=>$data['remark'],
                    'label'=>$data['label'],
                    'created_at'=>time(),
                    'addip'=>ip()
                );
                $arr_col=array('funds_available','funds_freeze','integral_available','integral_freeze','security_deposit','turnover_available','turnover_credit');
                $_turnover_available=0;//额外变动的周转金
                foreach ($arr_col as $col){
                    if(isset($data[$col])){
                        if($col=='funds_available'){//入帐
                            if($data['funds_available']>0){
                                //是否欠周转金
                                $owe=(float)math($account['turnover_credit'],$account['turnover_available'],'-',2);
                                if($owe>0){
                                    /*
                                     * 充值金额可以还清欠款
                                     * 周转金:增加所欠的欠款
                                     * 可用资金:增加 还清欠款后 剩余的金额
                                     * */
                                    if($data['funds_available']>=$owe){
                                        $data['funds_available']=math($data['funds_available'],$owe,'-',2);
                                        $_turnover_available=$owe;
                                    }else{
                                        $data['funds_available']=0;
                                        $_turnover_available=$data['funds_available'];
                                    }
                                }
                            }else{ //出帐
                                if(in_array($data['type'],array(14,15))){
                                    //买pos,买车 可以使用周转金
                                    $owe=(float)math($account['funds_available'],$data['funds_available'],'+',2);
                                    if($owe<0){
                                        //出现欠款 把可用资金减为0
                                        $data['funds_available']='-'.$account['funds_available'];
                                        $_turnover_available=$owe;
                                    }
                                }
                            }
                        }
                        $log[$col]=$data[$col];
                        $account[$col]=math($account[$col],$data[$col],'+',5);
                        $log[$col.'_now']=$account[$col];
                    }else{
                        $log[$col]=0;
                        $log[$col.'_now']=$account[$col];
                    }
                }
                if($_turnover_available!=0){
                    $log['turnover_available']=math($log['turnover_available'],$_turnover_available,'+',2);
                    $account['turnover_available']=math($account['turnover_available'],$_turnover_available,'+',2);
                    $log['turnover_available_now']=$account['turnover_available'];
                }
                $account['signature']=$this->sign($account);
                if($insert){
                    DB::table('account')->insert($account);
                }else{
                    DB::table('account')->where("user_id={$user_id}")->limit(1)->update($account);
                }
                $log['signature']=$this->sign($log);
                $return= DB::table('account_log')->insert($log);
                flock($fp,LOCK_UN);
            }
            fclose($fp);
            return $return;
        }else{
            return 'no param user_id';
        }
    }
    private function sign($signature)
    {
        $md5key=app('\App\Model\System')->getCode('md5key');
        if (isset($signature['id'])) {
            unset($signature['id']);
        }
        if (isset($signature['signature'])) {
            unset($signature['signature']);
        }
        if (isset($signature['created_at'])) {
            unset($signature['created_at']);
        }
        ksort($signature);
        $jsonStr = json_encode($signature);
        $str = md5($jsonStr.$md5key);
        return strtoupper($str);
    }
}