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

    public function calTree2_default($money,$car_money,$transfer_money)
    {
        $this->reset(array('money'=>$money));//重置
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
                                $log->money=$car_money;
                                $log->typeid='car_money';//提车
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
                                $log->money=$transfer_money;
                                $log->typeid='transfer_money';//过户
                                $log->save();
                                //echo '<br>layer6first<br>';
                            }
                        }
                    }else{
                        break;
                    }
                }
            }
            $this->profit();
        }
    }

    public function calTree2_type1($money,$car_money)
    {
        $this->reset(array('money'=>$money));//重置
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

                //二层满提车，三层满给一万
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
                            $log->money=$car_money;
                            $log->typeid='layer2full';
                            $log->save();
                            //echo '<br>layer2full<br>';
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                    }elseif ($j==1){//3
                        $lastLevel=$pTree->level + 3;
                        $count=(new Tree2())->where("pids like '{$pTree->pids}%' and level<{$lastLevel} and id<={$tree->id}")->value("count(id)",'int');
                        if($count==7){
                            $log->money=10000;
                            $log->typeid='layer3full';
                            $log->save();
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                    }else{
                        break;
                    }
                }
            }
            $this->profit();
        }
    }

    public function calTree2_type2($money,$car_money)
    {
        $this->reset(array('money'=>$money));//重置
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

                //推荐一个提车，两个给1万，三层满给1万，四层见点5000
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
                            $log->money=$car_money;
                            $log->typeid='layer2full';
                            $log->save();
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                    }elseif ($j==1){//3
                        $lastLevel=$pTree->level + 3;
                        $count=(new Tree2())->where("pids like '{$pTree->pids}%' and level<{$lastLevel} and id<={$tree->id}")->value("count(id)",'int');
                        if($count==7){
                            $log->money=10000;
                            $log->typeid='layer3full';
                            $log->save();
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
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
                    }else{
                        break;
                    }
                }
            }
            $this->profit();
        }
    }

    public function calTree2_type3($money,$car_money)
    {
        $this->reset(array('money'=>$money));//重置
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

                //推荐1个提车，两个给1万，三层满给1万
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
                        if($pTree->childsize==1){
                            $log->money=$car_money;
                            $log->typeid='layer2first';
                            $log->save();
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                        if($pTree->childsize==2){
                            $log->money=10000;
                            $log->typeid='layer2full';
                            $log->save();
                            //echo '<br>layer2full<br>';
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                    }elseif ($j==1){//3
                        $lastLevel=$pTree->level + 3;
                        $count=(new Tree2())->where("pids like '{$pTree->pids}%' and level<{$lastLevel} and id<={$tree->id}")->value("count(id)",'int');
                        if($count==7){
                            $log->money=10000;
                            $log->typeid='layer3full';
                            $log->save();
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                    }else{
                        break;
                    }
                }
            }
            $this->profit();
        }
    }

    public function calTree2_type4($money,$car_money)
    {
        $money=math($money,$car_money,'-',2);
        $this->reset(array('money'=>$money));//重置
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

                //四、工具A、17500+10000元，自提一台车，推荐两个给1万，三层满给一万
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
                        $lastLevel=$pTree->level + 3;
                        $count=(new Tree2())->where("pids like '{$pTree->pids}%' and level<{$lastLevel} and id<={$tree->id}")->value("count(id)",'int');
                        if($count==7){
                            $log->money=10000;
                            $log->typeid='layer3full';
                            $log->save();
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                    }else{
                        break;
                    }
                }
            }
            $this->profit();
        }
    }

    public function calTree2_type5($money,$car_money,$layer3full_money)
    {
        $this->reset(array('money'=>$money));//重置
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

                //五、工具A、36000 自提车，推荐2个给1万，三层满给30000，四层满见点5000
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
                        if($pTree->childsize==1){
                            $log->money=$car_money;
                            $log->typeid='layer2first';
                            $log->save();
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                        if($pTree->childsize==2){
                            $log->money=10000;
                            $log->typeid='layer2full';
                            $log->save();
                            //echo '<br>layer2full<br>';
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                    }elseif ($j==1){//3
                        $lastLevel=$pTree->level + 3;
                        $count=(new Tree2())->where("pids like '{$pTree->pids}%' and level<{$lastLevel} and id<={$tree->id}")->value("count(id)",'int');
                        if($count==7){
                            $log->money=$layer3full_money;
                            $log->typeid='layer3full';
                            $log->save();
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
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
                    }else{
                        break;
                    }
                }
            }
            $this->profit();
        }
    }



    public function calTree2_zong($money,$layer2first_money,$layer2full_money,$layer3full_money,$layer4dian_money,$layer5fist_money,$layer6first_money)
    {
        $this->reset(array('money'=>$money));//重置
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

                //推荐一个提车，两个给1万，三层满给1万，四层见点5000
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
                        if($pTree->childsize==1 && $layer2first_money!=0){
                            $log->money=$layer2first_money;
                            $log->typeid='layer2first';
                            $log->save();
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                        if($pTree->childsize==2 && $layer2full_money!=0){
                            $log->money=$layer2full_money;
                            $log->typeid='layer2full';
                            $log->save();
                            //echo '<br>layer2full<br>';
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                    }elseif ($j==1){//3
                        $lastLevel=$pTree->level + 3;
                        $count=(new Tree2())->where("pids like '{$pTree->pids}%' and level<{$lastLevel} and id<={$tree->id}")->value("count(id)",'int');
                        if($count==7 && $layer3full_money!=0){
                            $log->money=$layer3full_money;
                            $log->typeid='layer3full';
                            $log->save();
                            $pTree->income=math($pTree->income,$log->money,'+',2);
                            $pTree->save();
                        }
                    }elseif ($j==2){//4
                        $lastLevel=$pTree->level + 3;
                        $count=(new Tree2())->where("pids like '{$pTree->pids}%' and level<{$lastLevel} and id<={$tree->id}")->value("count(id)",'int');
                        if($count==7 && $layer4dian_money!=0){
                            //第4层见点5000（3层满）
                            $log->money=$layer4dian_money;
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
                            if($count==15 && $layer5fist_money!=0){
                                $pTree->full_reward_num=4;
                                $pTree->save();
                                $log->money=$layer5fist_money;
                                $log->typeid='layer5first_money';//五层第一个
                                $log->save();
                                //echo '<br>layer5first<br>';
                            }
                        }
                    }elseif ($j==4){//第6层
                        //第5层满奖励没有发过
                        if($pTree->full_reward_num<5){
                            $lastLevel=$pTree->level + 5;
                            $count=(new Tree2())->where("pids like '{$pTree->pids}%' and level<{$lastLevel} and id <={$tree->id}")->value("count(id)",'int');
                            if($count==31 && $layer6first_money!=0){
                                $pTree->full_reward_num=5;
                                $pTree->save();
                                $log->money=$layer6first_money;
                                $log->typeid='layer6first_money';//六层第一个
                                $log->save();
                                //echo '<br>layer6first<br>';
                            }
                        }
                    }else{
                        break;
                    }
                }
            }
            $this->profit();
        }
    }

    //计算拨出比
    private function profit()
    {
        $profit=new Tree2Profit();
        $profit->user_count=(new Tree2())->where('status=1')->value('count(*)');
        $profit->received=(new Tree2())->where('status=1')->value('sum(money)');
        //$profit->support=(float)(new TeaMoney())->value('sum(money)');
        $profit->support=(new Tree2Log())->value('sum(money)','float');
        $profit->rate=math($profit->support,$profit->received,'/',5);
        $profit->save();
    }

    /**
     * 重置
     */
    private function reset($data=array())
    {
        $arr=array(
            'income'=>0,
            'childsize'=>0,
            'full_reward_num'=>1,
            'status'=>0
        );
        if(isset($data['money']) && (float)$data['money']!=0){
            $arr['money']=(float)$data['money'];
        }
        DB::table('tree2')->update($arr);
        DB::table('tree2_log')->delete();
        DB::table('tree2_profit')->delete();
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