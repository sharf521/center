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
            $p_tea=$this->where('user_id=? and status=1')->bindValues($p_userid)->first();
            if ($p_tea->is_exist) {
                $invite_id=$p_tea->id;
                $invite_path=$p_tea->invite_path.$p_tea->id.',';
                $p_tea->invite_count=$p_tea->invite_count+1;
                $p_tea->save();
                $group= $this->getLevel1Group($p_tea->group_id);
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
    }

    //分组
    private function splitGroup($group)
    {
        $group->status=2;
        $group->save();
        $this->split_setLeader($group->leader);//升级组长
        //原来设为不可用
        (new Tea())->where("group_id={$group->id}")->update(array('status'=>2));

        $tGroup=new TeaGroup();
        $group1=$tGroup->create(1);
        $group2=$tGroup->create(1);

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
    private function split_setLeader($leader_uid)
    {
        $group=$this->getLevel2Group($leader_uid);
        $arr=array(
            'user_id' => $leader_uid,
            'invite_id'=>(int)$group->tea_invite_id,
            'group_id'=>$group->id,
            'invite_count'=>0,
            'level'=>2
        );
        $tea=$this->create($arr);
        unset($group->tea_invite_id);//去除自定义的属性
        $group=$group->putTea($tea);
        if($group->child_count==15){
            echo '进入第三盘了';
            exit;
        }
    }

    /**
     * 获取一个经营组
     * @param $user_id
     * @return TeaGroup
     */
    private function getLevel2Group($user_id)
    {
        $tGroup=new TeaGroup();
        //第一盘所在的组(多个)的组长
        $leader_ids=$tGroup->where("level=1 and child_ids like '%,{$user_id},%'")->orderBy('id')->lists('leader');
        $tag=false;
        foreach ($leader_ids as $leader_id){
            $tGroup=$tGroup->where("level=2 and status=1 and child_count<15 and child_ids like '%,{$leader_id},%'")->first();
            if($tGroup->is_exist){
                $tea=(new Tea())->where("user_id={$leader_id} and status=1")->first();
                $tGroup->tea_invite_id=$tea->id;//把推荐的点id带出去
                return $tGroup;
                break;
            }
        }
        if(!$tag){
            return $tGroup->create(2);
        }
    }
}