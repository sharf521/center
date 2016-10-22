<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/22
 * Time: 17:05
 */

namespace App\Controller\Admin;


use App\Model\Tea;
use System\Lib\DB;

class TeaController extends AdminController
{
    public function index(Tea $tea)
    {
        $arr=array(
            'user_id'		=>(int)$_GET['user_id'],
            'id'		=>(int)$_GET['id'],
            'money'		=>(int)$_GET['money'],
            'plate'		=>(int)$_GET['plate'],
        );
        $where = " 1=1";
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if (!empty($arr['money'])) {
            $where .= " and money={$arr['money']}";
        }
        if (!empty($arr['plate'])) {
            $where .= " and plate={$arr['plate']}";
        }
        if (!empty($arr['id'])) {
            $pids = DB::table('zj')->where('id=?')->bindValues($arr['id'])->value('pids');
            $where .= " and  pids like '{$pids}%'";
        }

        $data['result']=$tea->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('tea',$data);
    }

    public function init(Tea $tea)
    {
        $tea->site_id=0;
        $tea->user_id=0;
        $tea->money=5000;
        $tea->income=0;
    }

    public function add(Tea $tea)
    {
        if ($_POST) {
            try {
                DB::beginTransaction();

                $post = array(
                    'user_id' => $_POST['user_id'],
                    'p_userid' => (int)$_POST['p_userid'],
                    'money' => $_POST['money']
                );
                $tea->add($post);

                DB::commit();

                redirect('tea/')->with('msg', '添加成功！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error = "Failed: " . $e->getMessage();
                redirect()->back()->with('error', $error);
            }
        } else {
            $this->view('tea');
        }
    }

    public function call(Tea $tea)
    {
        try {
            DB::beginTransaction();

            $tea->call();
            DB::commit();
            redirect('tea/')->with('msg', '完成！');
        } catch (\Exception $e) {
            DB::rollBack();
            $error= "Failed: " . $e->getMessage();
            redirect()->back()->with('error', $error);
        }
    }
}