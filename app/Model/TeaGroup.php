<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/24
 * Time: 12:05
 */

namespace App\Model;


class TeaGroup extends Model
{
    protected $table='tea_group';
    public function __construct()
    {
        parent::__construct();
    }

    public function create($level=1)
    {
        $arr['level']=$level;
        $arr['leader']=0;
        $arr['child_count']=0;
        $arr['child_ids']=',';
        $arr['status']=1;
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
            $this->leader=$tea_id;
        }else{
            $tea->setParentTree();
        }
        $this->child_count=$this->child_count+1;
        $this->child_ids=$this->child_ids.$tea_id.',';
        $this->save();
        return $this;
    }

    public function Teas()
    {
        return $this->hasMany('\App\Model\Tea','group_id','id','',"invite_count desc");
    }
}