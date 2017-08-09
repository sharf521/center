<?php
namespace App\Controller\Admin;

use App\Model\FBB;
use App\Model\FbbLog;
use App\Model\User;
use System\Lib\DB;
use System\Lib\Request;

class FbbController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new FBB();
    }

    function index(Request $request, FBB $fbb)
    {
        $user_id = (int)$request->get('user_id');
        $id = (int)$request->get('id');
        $money = (int)$request->get('money');
        $where = "1=1";
        if (!empty($user_id)) {
            $where .= " and user_id={$user_id}";
        }
        if (!empty($money)) {
            $where .= " and money={$money}";
        }
        if (!empty($id)) {
            $pids = DB::table('fbb')->where("id=?")->bindValues($id)->value('pids');
            $where .= " and  pids like '{$pids}%'";
        }
        $data['result'] = $fbb->where($where)->orderBy('id desc')->pager($_GET['page'], 10);
        $this->view('fbb', $data);
    }

    function add($data)
    {
        if ($_POST) {
            try{
                DB::beginTransaction();

                $post = array(
                    'user_id' => $_POST['user_id'],
                    'p_userid' => (int)$_POST['p_userid'],
                    'money' => $_POST['money']
                );
                $this->model->add($post);

                DB::commit();
                redirect('fbb')->with('msg','添加成功！');
            }catch(\Exception $e){
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        } else {
            $this->view('fbb', $data);
        }
    }

    function calFbb()
    {
        $return = $this->model->calFbb();
        if ($return === true) {
            show_msg(array('完成', '', $this->base_url('fbb')));
        } else {
            show_msg(array('失败！！'));
        }
    }

    function fbblog(FbbLog $fbbLog)
    {
        $arr = array(
            'typeid' => $_GET['typeid'],
            'user_id' => (int)$_GET['user_id'],
            'fbb_id' => (int)$_GET['fbb_id'],
            'in_fbb_id' => (int)$_GET['in_fbb_id'],
            'in_user_id' => (int)$_GET['in_user_id'],
            'money' => (float)$_GET['money']
        );
        $where = " 1=1";
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if (!empty($arr['money'])) {
            $where .= " and money={$arr['money']}";
        }
        if (!empty($arr['fbb_id'])) {
            $where .= " and fbb_id={$arr['fbb_id']}";
        }
        if (!empty($arr['in_fbb_id'])) {
            $where .= " and in_fbb_id={$arr['in_fbb_id']}";
        }
        if (!empty($arr['in_user_id'])) {
            $where .= " and in_user_id={$arr['in_user_id']}";
        }
        $data['result'] = $fbbLog->where($where)->orderBy('id desc')->pager($_GET['page'], 20);
        $data['money_total']=(float)$fbbLog->where($where)->value("sum(money)");
        $this->view('fbb', $data);
    }

    function test1000UserReg()
    {
        exit;
        //32767
        for ($i=1;$i<=32767;$i++){
            $data = array(
                'no_login'=>true,
                'reg_type'=>'test',
                'username' => 'service'.$i,
                'email'=>"service{$i}@vcivc.cn",
                'password' =>'psjt_123qwe',
                'sure_password'=>'psjt_123qwe',
                'invite_user'=>''
            );
            $uid = (new User())->register($data);
            echo $uid;
        }
    }

    function test1000FbbReg()
    {
        exit;
        $userList=(new User())->select('id')->where("id>=1500")->limit("30000,5000")->get();
        //$userList=DB::table('user u')->select("u.id")->leftJoin('fbb b','u.id=b.user_id')->where()
        //SELECT * FROM `plf_user` u WHERE 1 and not EXISTS (SELECT 1 FROM plf_fbb b WHERE u.id = b.user_id)
        foreach ($userList as $user){
            /*$pid=0;
            $fbbList=(new FBB())->select("id,user_id")->orderBy('id')->get();
            foreach ($fbbList as $fbb){
                $fbbSubNum=(new FBB())->where("pid=?")->bindValues($fbb->id)->value("count(id)");
                if($fbbSubNum<2){
                    $pid=$fbb->user_id;
                    break;
                }
            }*/
            $pid=DB::table('fbb a')->leftJoin('fbb b','a.id=b.pid')->groupBy('a.id')->having("count(a.id)<2")->orderBy('a.id')->value('a.user_id','int');
            $fbb_data = array(
                'user_id' => $user->id,
                'p_userid' =>$pid,
                'money' => 2000
            );
            try{
                (new FBB())->add($fbb_data); //写入FBB
            }catch (\Exception $e) {
                echo "Failed: " . $e->getMessage();
            }
        }
    }
}