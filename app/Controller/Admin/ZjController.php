<?php
namespace App\Controller\Admin;

use App\Model\ZjLog;
use System\Lib\DB;
use App\Model\ZJ;

class ZjController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ZJ();
    }

    function index(ZJ $ZJ)
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

        $data['result']=$ZJ->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('zj',$data);
    }
    function add($data)
    {
        if($_POST)
        {
            $post=array(
                'user_id'=>$_POST['user_id']
            );


            try {
                DB::beginTransaction();

                $this->model->add($post);

                DB::commit();

                redirect('Zj/')->with('msg','添加成功！');
            } catch (\Exception $e) {
                DB::rollBack();
                $error= "Failed: " . $e->getMessage();
                redirect()->back()->with('error',$error);
            }
        }
        else
        {
            $this->view('zj',$data);
        }
    }
    function calAdd1000()
    {
        $return=$this->model->calAdd1000();
        if($return===true){
            show_msg(array('完成','',$this->base_url('zj')));
        }else{
            show_msg(array('失败！！'));
        }
    }
    function calZj(){
        $return=$this->model->calZj();
        if($return===true){
            show_msg(array('完成','',$this->base_url('zj')));
        }else{
            show_msg(array('失败！！'));
        }
    }
    function  zjlog(ZjLog $zjLog){
        $arr=array(
            'typeid'		=>$_GET['typeid'],
            'user_id'		=>(int)$_GET['user_id'],
            'zj_id'		=>(int)$_GET['zj_id'],
            'in_zj_id'		=>(int)$_GET['in_zj_id'],
            'money'		=>(int)$_GET['money'],
            'plate'		=>(int)$_GET['plate']
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
        if (!empty($arr['zj_id'])) {
            $where .= " and zj_id={$arr['zj_id']}";
        }
        if (!empty($arr['in_zj_id'])) {
            $where .= " and in_zj_id={$arr['in_zj_id']}";
        }
        if (!empty($arr['typeid'])) {
            $where .= " and typeid='{$arr['typeid']}'";
        }

        $data['result']=$zjLog->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('zj',$data);
    }
}