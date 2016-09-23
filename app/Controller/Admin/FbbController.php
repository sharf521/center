<?php
namespace App\Controller\Admin;

use App\Model\FBB;
use App\Model\FbbLog;
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
        $data['result'] = $fbbLog->where($where)->orderBy('id desc')->pager($_GET['page'], 10);
        $this->view('fbb', $data);
    }
}