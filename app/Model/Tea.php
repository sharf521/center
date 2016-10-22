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

    public function add($data)
    {
        if(empty($data['user_id']) || empty($data['money'])){
            throw new \Exception('参数错误！');
            exit;
        }
        $p_userid=(int)$data['p_userid'];
        $arr=array(
            'site_id' => 1,
            'user_id' => (int)$data['user_id'],
            'pid' => 0,
            'pids'=>'',
            'invite_id'=>0,
            'invite_path'=>'',
            'money' => (float)($data['money']),
            'childsize'=>0,
            'index'=>0,
            'plate'=>0,
            'income'=>0,
            'level'=>1,
            'addtime' => date('Y-m-d H:i:s'),
            'status' => 0
        );
        $row=DB::table('tea')->where('user_id=?')->bindValues($data['user_id'])->row();
        if($row){
            throw new \Exception('用户己购买！');
            exit;
        }
        if ($p_userid != 0) {
            $invite_arr=DB::table('tea')->where('user_id=?')->orderBy("id desc")->bindValues($p_userid)->row();
            if (!$invite_arr) {
                throw new \Exception('p_userid错误！');
                exit;
            } else {
                $arr['invite_id']=$invite_arr['id'];
                $arr['invite_path']=$invite_arr['invite_path'].$invite_arr['id'].',';
            }
        }
        $id=DB::table('tea')->insertGetId($arr);

        //第一个设为正常
        $count= DB::table('tea')->value('count(*)');
        if($count==1){
            $arr=array(
                'status'=>1,
                'pids' => "{$id},"
            );
            DB::table('tea')->where("id={$id}")->limit(1)->update($arr);
        }
    }

    public function call()
    {
        $where = "status=0 and addtime<'" . date('Y-m-d') . "'";
        $where = " status=0 ";
        $result = $this->where($where)->orderBy('id')->get();
        //加入一个新点
        foreach ($result as $newTea) {
            //获取一个没有两个分支的点
            $pTea = $this->select('id,user_id,pids,childsize')->where("childsize!=2")->orderBy('id')->first();

            $newTea->status=1;
            $newTea->pid=$pTea->id;
            $newTea->pids=$pTea->pids . $newTea->id . ',';
            $newTea->save();

            $pTea->childsize=$pTea->childsize+1;
            $pTea->save();
        }
    }
}