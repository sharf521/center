<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/24
 * Time: 12:05
 */

namespace App\Model;


use System\Lib\DB;

class TeaGroup extends Model
{
    protected $table='tea_group';
    public function __construct()
    {
        parent::__construct();
    }

    public function create($level=1)
    {
        $arr=array();
        $arr['level']=$level;
        $arr['leader']=0;
        $arr['child_count']=0;
        $arr['child_ids']=',';
        $arr['status']=1;
        $arr['created_at']=time();
        $id=$this->insertGetId($arr);
        return $this->find($id);
    }

    //加入一个点
    public function putTea($tea)
    {
        $tea_id=$tea->id;
        //第一个设为正常
        if($this->child_count==0){
            $tea->status=1;
            $tea->pids = "{$tea_id},";
            $tea->save();
            $this->leader=$tea->user_id;
        }else{
            $tea->setParentTree();
        }
        $this->child_count=$this->child_count+1;
        $this->child_ids=$this->child_ids.$tea->user_id.',';
        $this->save();
        if($this->child_count==1) {
            $this->leaderReward(); //组长奖励
        }
        $this->pointReward();//点奖励
        return $this;
    }

    //组长奖励
    public function leaderReward()
    {
        $leaderUser=(new TeaUser())->find($this->leader);
        $leaderTea=$leaderUser->getMyNowTea();
        if($leaderTea->invite_count>=2 && $leaderUser->invite_count>=2){
            $teaMoney=new TeaMoney();
            if($this->level==1){
                $money_arr=array(
                    'user_id'=>$this->leader,
                    'money'=>800,
                    'type'=>'leader',
                    'remark'=>"组长奖",
                    'label'=>''
                );
                $teaMoney->addLog($money_arr);
            }
            if($this->level==2){
                $money_arr=array(
                    'user_id'=>$this->leader,
                    'money'=>10000,
                    'type'=>'leader',
                    'remark'=>"组长奖",
                    'label'=>''
                );
                $teaMoney->addLog($money_arr);
            }
        }
    }

    //组长点奖励
    public function pointReward()
    {
        if($this->level==2 && $this->child_count>=8){
            $leaderUser=(new TeaUser())->find($this->leader);
            $leaderTea=$leaderUser->getMyNowTea();
            if($leaderTea->invite_count>=2 && $leaderUser->invite_count>=2) {
                $money_arr=array(
                    'user_id'=>$this->leader,
                    'money'=>5000,
                    'type'=>'dianjiang',
                    'remark'=>"组长点奖",
                    'label'=>''
                );
                (new TeaMoney())->addLog($money_arr);
            }
        }
    }

    //验证Tea是否可以替换所在组的组长
    public function checkChangeLeader(Tea $tea)
    {
        $group=(new TeaGroup())->find($tea->group_id);
        if($group->level==1 || $group->leavel==2){
            if($tea->invite_count>=2 && $tea->TeaUser()->invite_count>=2){
                $leaderUser=(new TeaUser())->find($group->leader);
                $leaderTea=$leaderUser->getMyNowTea();
                if($leaderUser->invite_count<2 || $leaderTea->invite_count<2){
                    $group->leader=$tea->user_id;
                    $group->save();
                    $group->leaderReward();
                }
            }
        }
    }


    public function Teas()
    {
        $result=DB::table('tea t')->select("t.id")->leftJoin('tea_user u',"t.user_id=u.id")->where("t.group_id={$this->id}")->orderBy("t.invite_count desc,u.invite_count desc,t.id")->all(\PDO::FETCH_OBJ);
        $objs=array();
        foreach($result as $row){
            $tea=(new Tea())->find($row->id);
            array_push($objs,$tea);
        }
        return $objs;
        //return $this->hasMany('\App\Model\Tea','group_id','id','',"invite_count desc");
    }
}