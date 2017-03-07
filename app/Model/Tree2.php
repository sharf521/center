<?php
namespace App\Model;

use System\Lib\DB;

class Tree2 extends Model
{
    protected $table='tree2';
    public function __construct()
    {
        parent::__construct();
    }

    function add($data)
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
            'level'=>1,
            'pids'=>'',
            'money' => (float)($data['money']),
            'income'=>0,
            'position'=>0,
            'childsize'=>0,
            'full_reward_num'=>1,
            'created_at' =>time(),
            'status' => 0
        );
        $row=DB::table('tree2')->where('user_id=?')->bindValues($data['user_id'])->row();
        if($row){
            throw new \Exception('用户己购买！');
            exit;
        }
        $pids='';
        if ($p_userid != 0) {
            $row=DB::table('tree2')->where('user_id=?')->orderBy("id desc")->bindValues($p_userid)->row();
            if (!$row) {
                throw new \Exception('p_userid错误！');
            } else {
                $pid=$row['id'];
                $pids = $row['pids'];
                $count1 = DB::table('tree2')->where("pid={$pid}")->value('count(id) as count1');
                if($count1>=2){
                    throw new \Exception('只能推荐两个人！');
                }
                $arr['pid']=$pid;
                $arr['level']=$row['level']+1;
                $arr['position'] = intval($count1) + 1;
            }
        }
        $id=DB::table('tree2')->insertGetId($arr);
        $pids=$pids.$id.',';
        DB::table('tree2')->where("id={$id}")->limit(1)->update(array('pids'=>$pids));
        return true;
    }

    public function isExist($user_id)
    {
        $tree2=$this->where('user_id=?')->bindValues($user_id)->first();
        return $tree2->is_exist;
    }


    public function calTree2()
    {
        //$where="status=0 and created_at<'".strtotime(date('Y-m-d'))."'";
        $where="status=0";
        $result=(new Tree2())->where($where)->orderBy('id')->get();
        foreach ($result as $tree){
            $tree->status=1;
            $tree->save();
            if($tree->pid!=0){
                $pTree=(new Tree2())->find($tree->pid);
                $pTree->childsize=$pTree->childsize+1;
                $pTree->save();

                $pids = rtrim($tree->pids, ',');//去除最后一个，
                $arr_pid = explode(',', $pids);
                array_pop($arr_pid);//去除自己
                $arr_pid = array_reverse($arr_pid);

                //2层满给1万，3层满见点5000,4层满拐一提车，5层满拐一过户
                //2层满给1万,
                //第4层见点5000（3层满），
                //第5层第一个提车（4层满）
                //第6层第一个过户(5层满)
                $log=new Tree2Log();
                $log->in_tree_id =$tree->id;
                $log->in_user_id=$tree->user_id;
                foreach ($arr_pid as $j=>$pid) {
                    $pTree=(new Tree2())->find($pid);
                    $log->user_id=$pTree->user_id;
                    $log->tree_id= $pTree->id;
                    $log->layer=$j+2;
                   // echo $tree->id.'-'.$pTree->id.'<br>';
                    if($j==0){//第2层
                        if($pTree->childsize==2){
                            $log->money=10000;
                            $log->typeid='layer2full';
                            $log->save();
                            //echo '<br>layer2full<br>';
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                    }elseif ($j==1){//3

                    }elseif ($j==2){//4
                        $lastLevel=$pTree->level + 3;
                        $count=(new Tree2())->where("pids like '{$pTree->pids}%' and level<{$lastLevel} and id<={$tree->id}")->value("count(id)",'int');
                        if($count==7){
                            //第4层见点5000（3层满）
                            $log->money=5000;
                            $log->typeid='layer4dian';
                            $log->save();
                            //echo '<br>'.$pTree->id.'——layer4dian<br>';
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                    }elseif ($j==3){//5
                        //第4层满奖励没有发过
                        if($pTree->full_reward_num<4){
                            $lastLevel=$pTree->level + 4;
                            $count=(new Tree2())->where("pids like '{$pTree->pids}%' and level<{$lastLevel} and id <={$tree->id}")->value("count(id)",'int');
                            if($count==15){
                                $pTree->full_reward_num=4;
                                $pTree->save();
                                $log->money=0;
                                $log->typeid='layer5first';
                                $log->save();
                                //echo '<br>layer5first<br>';
                            }
                        }
                    }elseif ($j==4){//第6层
                        //第5层满奖励没有发过
                        if($pTree->full_reward_num<5){
                            $lastLevel=$pTree->level + 5;
                            $count=(new Tree2())->where("pids like '{$pTree->pids}%' and level<{$lastLevel} and id <={$tree->id}")->value("count(id)",'int');
                            if($count==31){
                                $pTree->full_reward_num=5;
                                $pTree->save();
                                $log->money=0;
                                $log->typeid='layer6first';
                                $log->save();
                                //echo '<br>layer6first<br>';
                            }
                        }
                    }else{
                        break;
                    }
                }
                //echo '<hr>';
            }
        }
    }


    private function isFbb2_1($my_pos,$arr_pos){
        if($my_pos!=1) return false;//当前位置必须是上级的第一个推荐
        array_pop($arr_pos);//删除最后一个元素
        $last1=array_pop($arr_pos);//删除最后一个元素，返回最后一个
        $return=true;
        foreach($arr_pos as $pos){
            if($pos!=1){
                $return=false;
                break;
            }
        }
        if($return && $last1>=2){
            return true;
        }
        return false;
    }
    private function isFbb2_2_1($my_pos,$arr_pos){
        if($my_pos!=1) return false;
        array_pop($arr_pos);//删除最后一个元素
        $last1=array_pop($arr_pos);//删除最后一个元素
        $last2=array_pop($arr_pos);//最后第二个
        $return=true;
        foreach($arr_pos as $pos){
            if($pos!=1){
                $return=false;
                break;
            }
        }
        if($return && $last2==2 && $last1>=2){
            return true;
        }
        return false;
    }

}