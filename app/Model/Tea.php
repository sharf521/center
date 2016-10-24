<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/22
 * Time: 17:06
 */

namespace App\Model;


use System\Lib\DB;

class Tea extends Model
{
    protected $table='tea';
    public function __construct()
    {
        parent::__construct();
    }

    private function add_getOneGroup($group_id=0)
    {
        $tGroup=new TeaGroup();
        if($group_id!=0){
            $where="status=1 and level=1 and child_count<15 and id={$group_id}";
        }else{
            $where="status=1 and level=1 and child_count<7";
        }
        $tGroup=$tGroup->where($where)->orderBy('id')->first();
        if(! $tGroup->is_exist){
            $tGroup->level=1;
            $tGroup->leader=0;
            $tGroup->child_count=0;
            $tGroup->child_ids='';
            $tGroup->status=1;
            $groupId=$tGroup->save(true);
            $tGroup=$tGroup->findOrFail($groupId);
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
            $p_tea=$this->where('user_id=?')->bindValues($p_userid)->first();
            if ($p_tea->is_exist) {
                $invite_id=$p_tea->id;
                $invite_path=$p_tea->invite_path.$p_tea->id.',';
                $p_tea->invite_count=$p_tea->invite_count+1;
                $p_tea->save();
                $group= $this->add_getOneGroup($p_tea->group_id);
            } else {
                throw new \Exception('p_userid错误！');
            }
        }else{
            $group= $this->add_getOneGroup();
        }


        $arr=array(
            'site_id' => 1,
            'user_id' => (int)$data['user_id'],
            'pid' => 0,
            'pids'=>'',
            'invite_id'=>$invite_id,
            'invite_path'=>$invite_path,
            'money' => (float)($data['money']),
            'childsize'=>0,
            'group_id'=>$group->id,
            'income'=>0,
            'level'=>1,
            'created_at' =>time(),
            'status' => 0
        );
        $id=$this->insertGetId($arr);
        $tea=$this->find($id);
        //第一个设为正常
        if($group->child_count==0){
            $tea->status=1;
            $tea->pids = "{$id},";
            $tea->save();
            $group->leader=$id;
        }else{
            $this->add_setParentTree($tea);
        }
        $group->child_count=$group->child_count+1;
        $group->child_ids=$group->child_ids.$id.',';
        $group->save();
    }


    //设置隶属关系
    private function add_setParentTree($tea)
    {
        //获取一个没有两个分支的点
        $pTea = $this->select('id,user_id,pids,childsize')->where("childsize!=2 and level=1")->orderBy('id')->first();

        $tea->status=1;
        $tea->pid=$pTea->id;
        $tea->pids=$pTea->pids . $tea->id . ',';
        $tea->save();

        $pTea->childsize=$pTea->childsize+1;
        $pTea->save();
    }
}