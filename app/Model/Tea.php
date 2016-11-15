<?php

namespace App\Model;

use System\Lib\DB;

class Tea extends Model
{
    protected $table='tea';
    public function __construct()
    {
        parent::__construct();
    }

    private function create($data=array())
    {
        $arr=array(
            'site_id' => 1,
            'user_id' => $data['user_id'],
            'pid' => 0,
            'pids'=>'',
            'invite_id'=>(int)$data['invite_id'],
            'invite_path'=>$data['invite_path'],
            'invite_count'=>(int)$data['invite_count'],
            'childsize'=>0,
            'group_id'=>$data['group_id'],
            'level'=>$data['level'],
            'created_at' =>time(),
            'status' => 0
        );
        $id=$this->insertGetId($arr);
        return $this->find($id);
    }

    //设置隶属关系
    public function setParentTree()
    {
        //获取一个没有两个分支的点
        $pTea = $this->select('id,user_id,pids,childsize')->where("childsize!=2 and group_id={$this->group_id}")->orderBy('id')->first();
        $pTea->childsize=$pTea->childsize+1;
        $pTea->save();

        $this->status=1;
        $this->pid=$pTea->id;
        $this->pids=$pTea->pids . $this->id . ',';
        $this->save();
    }

    /**
     * 获取一个消费组
     * @param int $group_id
     * @return TeaGroup
     */
    private function getLevel1Group($group_id=0)
    {
        $tGroup=new TeaGroup();
        if($group_id!=0){
            $where="status=1 and level=1 and child_count<15 and id={$group_id}";
        }else{
            $where="status=1 and level=1 and child_count<7";
        }
        $tGroup=$tGroup->where($where)->orderBy('id')->first();
        if(! $tGroup->is_exist){
            $tGroup=$tGroup->create(1);
        }
        return $tGroup;
    }

    public function add($data)
    {
        $user_id=(int)$data['user_id'];
        if(empty($user_id) || empty($data['money'])){
            throw new \Exception('参数错误！');
        }
        $p_userid=(int)$data['p_userid'];
        $teaUser=new TeaUser();
        $user=$teaUser->find($data['user_id']);
        if($user->is_exist) {
            throw new \Exception('用户己购买！');
        }
        $user->id=$user_id;
        $user->site_id=0;
        $user->invite_id=0;
        $user->invite_path='';
        $user->invite_count=0;
        $user->money=(float)($data['money']);
        $user->status=1;
        $user->level=1;
        $user->again=0;

        $invite_id=0;
        $invite_path='';
        if ($p_userid != 0) {
            $p_tea=(new TeaUser())->getMyNowTea($p_userid);
            if ($p_tea->is_exist) {
                if($p_tea->level==1){
                    //同在第一盘时
                    $invite_id=$p_tea->user_id;
                    $invite_path=$p_tea->invite_path.$p_tea->user_id.',';
                    $p_tea->invite_count=$p_tea->invite_count+1;
                    $p_tea->save();
                    $group= $this->getLevel1Group($p_tea->group_id);
                }else{
                    $group= $this->getLevel1Group();
                    //第三盘再推荐一个人进入轮回
                    if($p_tea->level==3){
                        $p_tea->status=2;
                        $p_tea->save();
                        $p_tea_user=(new TeaUser())->find($p_tea->user_id);
                        $p_tea_user->invite_count=0;
                        $p_tea_user->again=$p_tea_user->again+1;
                        $p_tea_user->save();
                        $this->upLeaderLevel($p_tea->user_id,2);//  进入轮回到营经组
                    }
                }
            } else {
                throw new \Exception('p_userid错误！');
            }
            $p_user=(new TeaUser())->find($p_userid);
            $p_user->invite_count=$p_user->invite_count+1;
            $p_user->save();
            if($p_user->invite_count<3){
                $money=800;
            }else{
                $money=1600;
            }
            $arr=array(
                'user_id'=>$p_user->id,
                'money'=>$money,
                'type'=>'invite',
                'remark'=>"邀请用户：{$user_id}，第{$p_user->invite_count}人",
                'label'=>''
            );
            (new TeaMoney())->addLog($arr);
            $user->invite_id=$p_user->id;
            $user->invite_path=$p_user->invite_path.$p_user->id.',';
        }else{
            $group= $this->getLevel1Group();
        }
        $user->save();

        $arr=array(
            'user_id' => (int)$data['user_id'],
            'invite_id'=>$invite_id,
            'invite_path'=>$invite_path,
            'group_id'=>$group->id,
            'level'=>1
        );
        $tea=$this->create($arr);
        $group=$group->putTea($tea);
        if($p_tea->is_exist){
            $group->checkChangeLeader($p_tea);//替换组长
        }
        if($group->child_count==15){
            $this->splitGroup($group);
        }
        if ($user->invite_path!=''){
            $this->managerMoney($user);    //管理奖
        }
    }

    private function managerMoney($user)
    {
        $uids=explode(',',trim($user->invite_path,','));
        $i=0;
        $weight=array();//加权
        $teaMoney=new TeaMoney();
        $teas=(new Tea())->where("level=3 and status=1 and invite_count>1")->orderBy('id desc')->get();
        foreach($teas as $tea){
            $user_id=$tea->user_id;
            if(in_array($user_id,$uids)){
                $i++;
                if($i<6){
                    if($i==1){
                        $money=math($user->money,0.02,'*',2);
                    }else{
                        $money=math($user->money,0.01,'*',2);
                    }
                    $money_arr=array(
                        'user_id'=>$user_id,
                        'money'=>$money,
                        'type'=>'manage',
                        'remark'=>"管理奖"
                    );
                    $teaMoney->addLog($money_arr);
                }else{
                    array_push($weight,$user_id);
                }
            }
        }
/*
        $uids=array_reverse($uids);
        foreach($uids as $user_id){
            $tea=$tea->where("level=3 and status=1 and invite_count>1 and user_id={$user_id}")->first();
            if($tea->is_exist){

            }
        }*/
        if(count($weight)>0){
            $money=math($user->money,0.01,'*',2);
            $_money=math($money,count($weight),'/',2);
            foreach ($weight as $u){
                $money_arr=array(
                    'user_id'=>$u,
                    'money'=>$_money,
                    'type'=>'weight',
                    'remark'=>"加权奖"
                );
                $teaMoney->addLog($money_arr);
            }
        }
    }

    //分组
    private function splitGroup($group)
    {
        $group->status=2;
        $group->save();
        if($group->level==1){
            $this->upLeaderLevel($group->leader,2);//升级组长到营经组
        }elseif($group->level==2){
            $this->upLeaderLevel($group->leader,3);//升级组长到level3
        }
        (new Tea())->where("group_id={$group->id}")->update(array('status'=>2));//原来设为不可用
        $tGroup=new TeaGroup();
        $group1=$tGroup->create($group->level);
        $group2=$tGroup->create($group->level);

        $teas=$group->Teas();
        $i=1;
        foreach ($teas as $tea){
            if($tea->user_id!=$group->leader){
                $i++;
                if($i % 2==0){
                    $arr=array(
                        'user_id' => $tea->user_id,
                        'invite_count'=>$tea->invite_count,
                        'invite_path'=>$tea->invite_path,
                        'group_id'=>$group1->id,
                        'level'=>$group1->level
                    );
                    $_tea=$this->create($arr);
                    $group1->putTea($_tea);
                }else{
                    $arr=array(
                        'user_id' => $tea->user_id,
                        'invite_count'=>$tea->invite_count,
                        'invite_path'=>$tea->invite_path,
                        'group_id'=>$group2->id,
                        'level'=>$group2->level
                    );
                    $_tea=$this->create($arr);
                    $group2->putTea($_tea);
                }
            }
        }
    }

    //升级组长
    private function upLeaderLevel($leader_uid,$toLevel=2)
    {
        $leaderUser=(new TeaUser())->find($leader_uid);
        $leaderTea=(new Tea())->where("status=1 and user_id={$leader_uid}")->first();
        if($leaderUser->invite_count >=2 && $leaderTea->invite_count>=2){
            if($toLevel==2){
                $money_arr=array(
                    'user_id'=>$leader_uid,
                    'money'=>5000,
                    'type'=>'count15',
                    'remark'=>"1盘满员奖励",
                    'label'=>''
                );
                (new TeaMoney())->addLog($money_arr);
            }
            if($toLevel==3){
                $money_arr=array(
                    'user_id'=>$leader_uid,
                    'money'=>55000,
                    'type'=>'count15',
                    'remark'=>"2盘满员奖励",
                    'label'=>''
                );
                (new TeaMoney())->addLog($money_arr);
                $money_arr=array(
                    'user_id'=>$leader_uid,
                    'money'=>-5000,
                    'type'=>'taxFee',
                    'remark'=>"扣税",
                    'label'=>''
                );
                (new TeaMoney())->addLog($money_arr);
            }
        }
        $group=$this->getLevelGroup($leaderUser,$leaderTea,$toLevel);
        $invite_id=(int)$group->tea_invite_id;
        $invite_path=$group->tea_invite_path;
        unset($group->tea_invite_id);//去除自定义的属性
        unset($group->tea_invite_path);
        $arr=array(
            'user_id' => $leader_uid,
            'invite_id'=>$invite_id,
            'invite_path'=>$invite_path,
            'group_id'=>$group->id,
            'invite_count'=>0,
            'level'=>$toLevel
        );
        $tea=$this->create($arr);
        $group=$group->putTea($tea);
        if($toLevel==2 && $group->child_count==15){
            $this->splitGroup($group);
        }
    }

    /**
     * 获取一个组
     * @param $leaderUser
     * @return TeaGroup
     */
    private function getLevelGroup($leaderUser,$leaderTea,$level=2)
    {
        //如果他的推荐人的组没满时进入。
        $uids=explode(',',trim($leaderUser->invite_path,','));
        $uids=array_reverse($uids);
        $tea=new Tea();
        $tGroup=new TeaGroup();
        foreach($uids as $user_id){
            if($level==2){
                $tGroup=$tGroup->where("level=2 and status=1 and child_count<15 and child_ids like '%,{$user_id},%'")->first();
            }elseif($level==3){
                //轮回status=2 资格保留
                $tGroup=$tGroup->where("level=3 and child_ids like '%,{$user_id},%'")->first();
            }
            if($tGroup->is_exist){
                //把推荐的点id带出去,推荐点的推荐总数加1
                if($level==2){
                    $tea=$tea->where("level={$level} and status=1 and user_id={$user_id}")->first();
                }elseif($level==3){
                    $tea=$tea->where("level={$level} and user_id={$user_id}")->first();
                }
                $tea->invite_count=$tea->invite_count+1;
                $tea->save();


                $tGroup->checkChangeLeader($tea);//替换组长

                $tGroup->tea_invite_id=$tea->user_id;
                $tGroup->tea_invite_path=$tea->invite_path.$tea->user_id.',';
                return $tGroup;
                break;
            }
        }
        //没有找到推荐人所在的组
        if($level==2){
            $tGroup=$tGroup->where("level=2 and status=1 and child_count<7")->first();
            if($tGroup->is_exist){
                return $tGroup;
            }
            return $tGroup->create(2);
        }elseif($level==3){
            //创建管理组，管理组里放入公司为第一个元素
            $tGroup=$tGroup->create(3);
            $arr=array(
                'user_id' => 0,
                'invite_id'=>0,
                'group_id'=>$tGroup->id,
                'invite_count'=>0,
                'level'=>3
            );
            $tea=$this->create($arr);
            $tGroup=$tGroup->putTea($tea);
            $tGroup->tea_invite_id=0;
            return$tGroup;
        }
    }

    public function User()
    {
        return $this->hasOne('\App\Model\User','id','user_id');
    }

    public function TeaUser()
    {
        return $this->hasOne('\App\Model\TeaUser','id','user_id');
    }

    /**
     * @return \App\Model\TeaGroup
     */
    public function getMyGroup()
    {
        return $this->hasOne('\App\Model\TeaGroup','id','group_id');
    }

    public function showTeaUserName($user_id)
    {
        $User=(new User())->find($user_id);
        $TeaUser=(new TeaUser())->find($user_id);
        $again='';
        if($TeaUser->again!=0){
            $again='*';
        }
        return $User->username.$again.'&nbsp;　&nbsp;';
    }
}