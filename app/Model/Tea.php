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
            'money' =>(float)$data['money'],
            'childsize'=>0,
            'group_id'=>$data['group_id'],
            'income'=>0,
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
        if(empty($data['user_id']) || empty($data['money'])){
            throw new \Exception('参数错误！');
        }
        $p_userid=(int)$data['p_userid'];
        $tea=(new Tea())->where('user_id=?')->bindValues($data['user_id'])->first();
        if($tea->is_exist){
            throw new \Exception('用户己购买！');
        }
        $invite_id=0;
        $invite_path='';
        if ($p_userid != 0) {
            $p_tea=$this->where('user_id=? and level=1')->bindValues($p_userid)->first();
            if ($p_tea->is_exist) {
                $invite_id=$p_tea->user_id;
                $invite_path=$p_tea->invite_path.$p_tea->user_id.',';
                $p_tea->invite_count=$p_tea->invite_count+1;
                $p_tea->save();
                $group= $this->getLevel1Group($p_tea->group_id);

                if($p_tea->invite_count==1){
                    $money=800;
                }else{
                    $money=1600;
                }
                $arr=array(
                    'user_id'=>$p_tea->user_id,
                    'money'=>$money,
                    'type'=>'invite',
                    'remark'=>"邀请用户：{$data['user_id']}，第{$p_tea->invite_count}人",
                    'label'=>''
                );
                (new TeaUser())->addLog($arr);
            } else {
                throw new \Exception('p_userid错误！');
            }
        }else{
            $group= $this->getLevel1Group();
        }

        $arr=array(
            'user_id' => (int)$data['user_id'],
            'invite_id'=>$invite_id,
            'invite_path'=>$invite_path,
            'money' => (float)($data['money']),
            'group_id'=>$group->id,
            'level'=>1
        );
        $tea=$this->create($arr);
        $group=$group->putTea($tea);
        if($group->child_count==15){
            $this->splitGroup($group);
        }


        //管理奖 按原始推荐人给
        if($invite_path!=''){
            $uids=explode(',',trim($invite_path,','));
            $uids=array_reverse($uids);
            $i=0;
            $weight=array();//加权
            $teaUser=new TeaUser();
            foreach($uids as $user_id){
                $tea=$tea->where("level=3 and status=1 and invite_count>1 and user_id={$user_id}")->first();
                if($tea->is_exist){
                    $i++;
                    if($i<6){
                        if($i==1 || $i==5){
                            $money=math(5000,0.02);
                        }elseif($i==2){
                            $money=math(5000,0.03);
                        }elseif($i==3){
                            $money=math(5000,0.04);
                        }elseif($i==4){
                            $money=math(5000,0.05);
                        }
                        $money_arr=array(
                            'user_id'=>$user_id,
                            'money'=>$money,
                            'type'=>'manage',
                            'remark'=>"管理奖"
                        );
                        $teaUser->addLog($money_arr);
                    }else{
                        array_push($weight,$user_id);
                    }
                }
            }
            if(count($weight)>1){
                $_money=math(4980,count($weight),2);
                foreach ($weight as $u){
                    $money_arr=array(
                        'user_id'=>$u,
                        'money'=>$_money,
                        'type'=>'weight',
                        'remark'=>"加权奖",
                        'label'=>''
                    );
                    $teaUser->addLog($money_arr);
                }
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

        //原来设为不可用
        (new Tea())->where("group_id={$group->id}")->update(array('status'=>2));

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
                        'group_id'=>$group1->id,
                        'level'=>$group1->level
                    );
                    $_tea=$this->create($arr);
                    $group1->putTea($_tea);
                }else{
                    $arr=array(
                        'user_id' => $tea->user_id,
                        'invite_count'=>$tea->invite_count,
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
        $leaderTea=(new Tea())->where("level=1 and user_id={$leader_uid}")->first();
        if($leaderTea->invite_count>=2){
            if($toLevel==2){
                $money_arr=array(
                    'user_id'=>$leader_uid,
                    'money'=>5000,
                    'type'=>'count15',
                    'remark'=>"1盘满员奖励",
                    'label'=>''
                );
                (new TeaUser())->addLog($money_arr);
            }
            if($toLevel==3){
                $money_arr=array(
                    'user_id'=>$leader_uid,
                    'money'=>55000,
                    'type'=>'count15',
                    'remark'=>"2盘满员奖励",
                    'label'=>''
                );
                (new TeaUser())->addLog($money_arr);
            }
        }
        $group=$this->getLevelGroup($leader_uid,$toLevel);
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
     * @param $user_id
     * @return TeaGroup
     */
    private function getLevelGroup($user_id,$level=2)
    {
        $tGroup=new TeaGroup();
        //如果他的推荐人的组没满时进入。
        $tea=new Tea();
        $invite_path=$tea->where("user_id={$user_id} and level=1")->value('invite_path');
        $uids=explode(',',trim($invite_path,','));
        $uids=array_reverse($uids);
        foreach($uids as $user_id){
            if($level==2){
                $tGroup=$tGroup->where("level=2 and status=1 and child_count<15 and child_ids like '%,{$user_id},%'")->first();
            }elseif($level==3){
                $tGroup=$tGroup->where("level=3 and status=1 and child_ids like '%,{$user_id},%'")->first();
            }
            if($tGroup->is_exist){
                //把推荐的点id带出去,推荐点的推荐总数加1
                $tea=$tea->where("level={$level} and status=1 and user_id={$user_id}")->first();
                $tea->invite_count=$tea->invite_count+1;
                $tea->save();
                $tGroup->tea_invite_id=$tea->user_id;
                $tGroup->tea_invite_path=$tea->invite_path.$tea->user_id.',';
                return $tGroup;
                break;
            }
        }
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


//        if($invite_uid==0){
//            //第一盘所在的组(多个)的组长
//            $leader_ids=$tGroup->where("level=1 and child_ids like '%,{$user_id},%'")->orderBy('id')->lists('leader');
//            foreach ($leader_ids as $user_id){
//                $tGroup=$tGroup->where("level=2 and status=1 and child_count<15 and child_ids like '%,{$user_id},%'")->first();
//                if($tGroup->is_exist){
//                    $invite_uid=$user_id;
//                    break;
//                }
//            }
//        }
    }
}