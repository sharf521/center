<?php
namespace App\Model;

use System\Lib\DB;

class Algorithm extends Model
{
    protected $table='algorithm_log';
    public function __construct()
    {
        parent::__construct();
        $this->config = DB::table('rebate_config')->lists('v', 'k');
    }

    public function collectLog()
    {
        try {
            DB::beginTransaction();

            $lastDate=DB::table('algorithm_log')->orderBy('addtime desc')->value('addtime');
            $date = date('Y-m-d', strtotime($lastDate) + 3600 * 24);
            $tables=array('fbb_log','zj_log','rebate_log');
            foreach($tables as $table){
                $result=DB::table($table)->select('user_id,sum(money) as money,substring(addtime,1,10) as addtime')->where("addtime>='{$date}'")->orderBy("id")->groupBy('substring(addtime,1,10),user_id')->all();
                foreach($result as $row){
                    if ($table != 'rebate_log') {
                        $row['money'] = bcmul($row['money'], 2.52, 5);
                    }
                    if($table=='fbb_log'){
                        $fbb_rate=(float)$this->config['fbb_rate'];
                        if(empty($fbb_rate)){
                            $fbb_rate=1;
                        }
                        $row['money'] = bcmul($row['money'], $fbb_rate, 5);
                    }
                    $arr=array(
                        'user_id'=>$row['user_id'],
                        'addtime'=>$row['addtime']
                    );
                    $_one=DB::table('algorithm_log')->where($arr)->row();
                    if($_one){
                        $_update=array(
                            'money'=>bcadd($row['money'],$_one['money'],5)
                        );
                        DB::table('algorithm_log')->where("id={$_one['id']}")->limit(1)->update($_update);
                    }else{
                        $arr['money']=$row['money'];
                        $arr['status']=0;
                        DB::table('algorithm_log')->insert($arr);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Failed: " . $e->getMessage();
            return false;
        }
        return true;
    }

    public function send($date)
    {
        if(empty($date)){
            throw new \Exception('结算日期不能为空！');
            exit;
        }
        $enddate=date('Y-m-d',strtotime($date)+3600*24);
        $result = DB::table('algorithm_log')->select('id,user_id,money')->where("status=0 and addtime>=? and addtime<?")->bindValues(array($date,$enddate))->all();
        $account=new Account();
        foreach($result as $row){
            $_arr=array(
                'user_id' => $row['user_id'],
                'integral_available' => $row['money'],
                'type'=>'webservice',
                'remark'=>$date,
                'label'=>''
            );
            $account->addLog($_arr);
            //更新状态
            $_arr=array(
                'send_money'=>$row['money'],
                'send_date'=>date('Y-m-d H:i:s'),
                'status'=>1
            );
            DB::table('algorithm_log')->where("id={$row['id']}")->limit(1)->update($_arr);
        }
        return true;
    }
    public function User()
    {
        return $this->hasOne('App\Model\User', 'id','user_id');
    }
}