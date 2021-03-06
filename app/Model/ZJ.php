<?php
namespace App\Model;

use System\Lib\DB;

/*
 * bcadd — 加法
bccomp — 比较
bcdiv — 相除
bcmod — 求余数
bcmul — 乘法
bcpow — 次方
bcpowmod — 先次方然后求余数
bcscale — 给所有函数设置小数位精度
bcsqrt — 求平方根
bcsub — 减法
 * */

class ZJ extends Model
{
    protected $table='zj';
    public function __construct()
    {
        parent::__construct();
        $this->fields = array('id', 'site_id', 'user_id', 'money', 'income', 'pid', 'pids', 'position', 'addtime', 'status');
    }

    private function add_pre($plate)
    {
        $plate = (int)$plate;
        if ($plate == 0) {
            $plate = 1;
        }
        for ($i = 0; $i < 5; $i++) {
            //$row = $this->mysql->get_one("select `index` as nums from {$this->table} where plate={$plate} order by id desc limit 1");
            $nums = DB::table('zj')->where("plate={$plate}")->orderBy('id desc')->value('`index`');
            $index = intval($nums) + 1;
            if ($this->checkSild($index)) {
                $arr = array(
                    'site_id' => 0,
                    'user_id' => 0,
                    'pid' => 0,
                    'pids' => '',
                    'money' => bcmul(800, pow(2, $plate - 1)),
                    'income' => 0,
                    'plate' => $plate,
                    'index' => $index,
                    'addtime' => date('Y-m-d H:i:s'),
                    'dayplan' => 0,
                    'dayplan_income' => 0,
                    'dayplan_last' => 0,
                    'status' => 0
                );
                $id = DB::table('zj')->insertGetId($arr);
                //每一盘的第一个
                if ($index == 1) {
                    $arr=array(
                        'status'=>1,
                        'pids' => "{$id},"
                    );
                    DB::table('zj')->where("id={$id}")->limit(1)->update($arr);
                }
            } else {
                break;
            }
        }
        return true;
    }

    function add($data)
    {
        $plate = (int)$data['plate'];
        $user_id=(int)$data['user_id'];
        if ($plate == 0) {
            $plate = 1;
        }
        if ($user_id==0 && $plate==1) {
                throw new \Exception('参数错误！');
        } else {
            /*
            $row=$this->mysql->one('zj',array('user_id'=>$data['user_id']));
            if($row){
                $return=array('code'=>1,'msg'=>'用户己购买！');
                return json_encode($return);
            }*/
            $this->add_pre($plate);
            $nums = DB::table('zj')->where("plate={$plate}")->orderBy('id desc')->value('`index`');
            $arr = array(
                'site_id' => 1,
                'user_id' =>$user_id,
                'pid' => 0,
                'pids' => '',
                'index' => intval($nums) + 1,
                'money' => bcmul(800, pow(2, $plate - 1)),
                'income' => 0,
                'plate' => $plate,
                'addtime' => date('Y-m-d H:i:s'),
                'dayplan' => 0,
                'dayplan_income' => 0,
                'dayplan_last' => 0,
                'status' => 0
            );
            $result = DB::table('zj')->insert($arr);
            return $result;
        }
    }

    function calAdd1000()
    {
        DB::query('TRUNCATE TABLE  `plf_zj`');
        DB::query('TRUNCATE TABLE  `plf_zj_log`');
        for ($i = 1; $i <= 100; $i++) {
            $this->add(array('user_id' => $i, 'plate' => 1));
        }
        return true;
    }

    function calZj()
    {
        try {
            DB::beginTransaction();
            for ($i = 0; $i <= 10; $i++) {
                $this->calPlate($i);
            }
            $this->cal25DaysPlan();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Failed: " . $e->getMessage();
            return false;
        }
        return true;
    }

    //25天计划
    private function cal25DaysPlan()
    {
        $today = date('Y-m-d');
        $result = DB::table('zj')->select('id,user_id,income,addtime,plate,dayplan')->where('dayplan!=25')->orderBy('id')->all();
        foreach ($result as $row) {
            $date = substr($row['addtime'], 0, 10);
            $child_count = DB::table('zj')->where("pid={$row['id']}")->orderBy('id')->value('count(id)');
            if ($child_count > 0 || $today > date('Y-m-d', strtotime($date) + 3600 * 24 * 25)) {
                DB::table('zj')->where("id={$row['id']}")->limit(1)->update(array('dayplan' => 25));
            } else {
                $arr_days = array(
                    3 => 150,
                    5 => 50,
                    10 => 50,
                    15 => 50,
                    20 => 50,
                    25 => 150,
                );
                foreach ($arr_days as $k => $v) {
                    if ($k > $row['dayplan']) {
                        $day = date('Y-m-d', strtotime($date) + 3600 * 24 * $k);
                        if ($day < $today) {
                            $money_log = array(
                                'user_id' => $row['user_id'],
                                'zj_id' => $row['id'],
                                'in_user_id' => 0,
                                'in_zj_id' => 0,
                                'plate' => $row['plate'],
                                'money' => bcmul($v, pow(2, $row['plate'] - 1)),
                                'typeid' => '3,4,',
                                'addtime' => date('Y-m-d H:i:s')
                            );
                            DB::table('zj_log')->insert($money_log);
                            DB::query("update {$this->dbfix}zj set income=income+{$money_log['money']},dayplan={$k},dayplan_income=dayplan_income+{$money_log['money']},dayplan_last=dayplan_last+{$money_log['money']} where id={$row['id']} limit 1");
                        }
                    }
                }
            }
        }
        return true;
    }

    private function calPlate($plate)
    {
        //每一盘的第一个跳过
        $where = "status=0 and plate={$plate} and addtime<'" . date('Y-m-d') . "'";
        $result = DB::table('zj')->select('id,user_id')->where($where)->orderBy('id')->all();
        foreach ($result as $row) {
            $_row = DB::table('zj')->select('id,user_id,pids,childsize,income,dayplan_last')->where("plate={$plate} and childsize!=3")->orderBy('id')->row();
            $arr = array(
                'status' => 1,
                'pid' => $_row['id'],
                'pids' => $_row['pids'] . $row['id'] . ','
            );
            DB::table('zj')->where("id={$row['id']}")->limit(1)->update($arr);

            $dayplan_last = $_row['dayplan_last'];
            $t1_money = bcmul(300, pow(2, $plate - 1));
            if ($dayplan_last > $t1_money) {
                $_arr = array(
                    'childsize' => $_row['childsize'] + 1,
                    'dayplan_last' => $dayplan_last - $t1_money
                );
                DB::table('zj')->where("id={$_row['id']}")->limit(1)->update($_arr);
            } else {
                $money = $t1_money - $dayplan_last;
                //第一层奖励
                $money_log = array(
                    'user_id' => $_row['user_id'],
                    'zj_id' => $_row['id'],
                    'in_user_id' => $row['user_id'],
                    'in_zj_id' => $row['id'],
                    'plate' => $plate,
                    'money' => $money,
                    'typeid' => '3,1,',
                    'addtime' => date('Y-m-d H:i:s')
                );
                DB::table('zj_log')->insert($money_log);
                $_arr = array(
                    'childsize' => $_row['childsize'] + 1,
                    'income' => bcadd($_row['income'], $money_log['money'], 5),
                    'dayplan_last' => 0
                );
                DB::table('zj')->where("id={$_row['id']}")->limit(1)->update($_arr);
            }

            //滑落 上层已经够3个了 再 判断上层的上层是不是可以滑落
            if ($plate < 10 && $_arr['childsize'] == 3) {
                $arr = explode(',', trim($arr['pids'], ','));
                if (count($arr) > 2) {
                    array_pop($arr);
                    array_pop($arr);
                    $pp_id = intval(array_pop($arr));
                    $pp_row = DB::table('zj')->select('user_id,income')->where("id={$pp_id} and childsize=3")->row();
                    if ($pp_row) {
                        $p_row_counts = DB::table('zj')->where("pid={$pp_id} and childsize=3")->value('count(id)');
                        if ($p_row_counts == 3) {
                            $money_log = array(
                                'user_id' => $pp_row['user_id'],
                                'zj_id' => $pp_id,
                                'in_user_id' => $row['user_id'],
                                'in_zj_id' => $row['id'],
                                'plate' => $plate,
                                'money' => bcmul(3600, pow(2, $plate - 1)),//T2 每个400 一共9个
                                'typeid' => '3,2,',
                                'addtime' => date('Y-m-d H:i:s')
                            );
                            DB::table('zj_log')->insert($money_log);

                            //进入下一盘
                            $money_log2 = array(
                                'user_id' => $pp_row['user_id'],
                                'zj_id' => $pp_id,
                                'in_user_id' => $row['user_id'],
                                'in_zj_id' => $row['id'],
                                'plate' => $plate + 1,
                                'money' => '-' . bcmul(800, pow(2, $plate + 1 - 1)),
                                'typeid' => '3,3,',
                                'addtime' => date('Y-m-d H:i:s')
                            );
                            DB::table('zj_log')->insert($money_log2);
                            $arr = array(
                                'status' => 2,
                                'income' => bcadd($pp_row['income'], bcadd($money_log['money'], $money_log2['money']), 5)
                            );
                            DB::table('zj')->where("id={$pp_id}")->limit(1)->update($arr);
                            $this->add(array('user_id' => $pp_row['user_id'], 'plate' => $plate + 1));
                        }
                    }
                }
            }
        }
        return true;
    }

    //判断边缘
    private function checkSild($x)
    {
        $a0 = 0;
        if ($x == $a0 || $x == $a0 + 1) return true;
        $flag = false;
        $a0 = 1;
        while (true) {
            $an = $a0 * 3 - 1;
            if ($x == $an || $x == $an - 1) {
                $flag = true;
                break;
            }
            if ($an > $x) break;
            $a0 = $an;
        }
        return $flag;
    }
}