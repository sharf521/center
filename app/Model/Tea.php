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
        DB::table('tea')->insert($arr);
    }

    public function call()
    {
        $where = "status=0 and addtime<'" . date('Y-m-d') . "'";
        $where = "status=0 ";
        $result = DB::table('tea')->select('id,user_id')->where($where)->orderBy('id')->all();
        //加入一个新点
        foreach ($result as $row) {
            //获取一个没有两个分支的点
            $_row = DB::table('tea')->select('id,user_id,pids,childsize')->where("childsize!=2")->orderBy('id')->row();
            $arr = array(
                'status' => 1,
                'pid' => $_row['id'],
                'pids' => $_row['pids'] . $row['id'] . ','
            );
            DB::table('tea')->where("id={$row['id']}")->limit(1)->update($arr);


            $_arr = array(
                'childsize' => $_row['childsize'] + 1
            );
            DB::table('tea')->where("id={$_row['id']}")->limit(1)->update($_arr);
        }
    }
}