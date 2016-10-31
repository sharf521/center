<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/30
 * Time: 13:59
 */

namespace App\Model;


use System\Lib\DB;

class TeaMoney extends Model
{
    protected $table='tea_money';
    protected $primaryKey='user_id';
    public function __construct()
    {
        parent::__construct();
    }
    function addLog($data)
    {
        $insert=false;
        if(isset($data['user_id'])){
            $user_id=(int)$data['user_id'];
        }
        if($user_id>0){
            $fp = fopen(ROOT."/public/data/tMoney.txt" ,'w+');
            if(flock($fp , LOCK_EX))
            {
                $account=DB::table('tea_money')->where("user_id={$user_id}")->row();
                if(empty($account)){
                    $insert=true;
                    $account=array(
                        'user_id'=>$user_id,
                        'money'=>0,
                        'money_freeze'=>0,
                        'created_at'=>time()
                    );
                }
                $log=array(
                    'user_id'=>$user_id,
                    'type'=>$data['type'],
                    'remark'=>$data['remark'],
                    'label'=>$data['label'],
                    'created_at'=>time(),
                    'addip'=>ip()
                );
                $arr_col=array('money','money_freeze');
                foreach ($arr_col as $col){
                    if(isset($data[$col])){
                        $log[$col]=$data[$col];
                        $account[$col]=math($account[$col],$data[$col],'+',5);
                        $log[$col.'_now']=$account[$col];
                    }else{
                        $log[$col]=0;
                        $log[$col.'_now']=$account[$col];
                    }
                }
                $account['signature']=$this->sign($account);
                if($insert){
                    $this->insert($account);
                }else{
                    $this->where("user_id={$user_id}")->update($account);
                }
                $log['signature']=$this->sign($log);
                $return= DB::table('tea_log')->insert($log);
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