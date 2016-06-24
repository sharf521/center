<?php
namespace App\Controller\Admin;

use App\Model\Speak;

class SpeakController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->Speak = new Speak();
    }

    function index()
    {
        $arr=array();
        if($this->permission_id!='ALL'){
            $arr['user_id']=$this->user_id;
        }
        $data['result'] = $this->Speak->getList($arr);
        $this->view('speak', $data);
    }

    function add($data)
    {
        if ($_POST) {
            $data = $_POST;
            $data['user_id'] = $this->user_id;
            $this->Speak->add($data);
            show_msg(array('添加成功', '', $this->base_url('speak')));
            //$this->redirect('usertype');
        } else {
            $this->view('speak', $data);
        }
    }

    function edit()
    {
        $arr=array('id' => $_GET['id']);
        if($this->permission_id!='ALL'){
            $arr['user_id']=$this->user_id;
        }
        $row= $this->Speak->getOne($arr);
        if (!$row) {
            show_msg(array('禁止操作', '', $this->base_url('speak')));
            exit;
        }
        if ($_POST) {
            $this->Speak->edit($_POST);
            show_msg(array('修改成功', '', $this->base_url('speak')));
            //$this->redirect('usertype');
        } else {
            $data['row'] =$row;
            $this->view('speak', $data);
        }
    }

    function delete()
    {
        $arr=array('id' => $_GET['id']);
        if($this->permission_id!='ALL'){
            $arr['user_id']=$this->user_id;
        }
        $this->Speak->del($arr);
        show_msg(array('删除成功', '', $this->base_url('speak')));
    }


    function log()
    {
        $arr=array('speak_id' => $_GET['speak_id']);
        if($this->permission_id!='ALL'){
            $arr['user_id']=$this->user_id;
        }
        $data['result'] = $this->Speak->logList($arr);
        $this->view('speak', $data);
    }
    function log_add($data)
    {
        if ($_POST) {
            $data = $_POST;
            $data['user_id'] = $this->user_id;
            $data['speak_id']=$_GET['speak_id'];
            $this->Speak->logAdd($data);
            show_msg(array('添加成功', '', $this->base_url('speak/log/?speak_id='.$data['speak_id'])));
            //$this->redirect('usertype');
        } else {
            $data['row']['time1']=date('Y-m-d H:i:s');
            $this->view('speak', $data);
        }
    }

    function log_edit()
    {
        $arr=array('id' => $_GET['id']);
        if($this->permission_id!='ALL'){
            $arr['user_id']=$this->user_id;
        }
        $row= $this->Speak->getOne($arr,'speak_log');
        if (!$row) {
            show_msg(array('禁止操作', '', $this->base_url('speak')));
            exit;
        }
        if ($_POST) {
            $this->Speak->logEdit($_POST);
            show_msg(array('修改成功', '', $this->base_url('speak/log/?speak_id='.$row['speak_id'])));
            //$this->redirect('usertype');
        } else {
            $data['row'] =$row;
            $this->view('speak', $data);
        }
    }
    function log_delete()
    {
        $arr=array('id' => $_GET['id']);
        if($this->permission_id!='ALL'){
            $arr['user_id']=$this->user_id;
        }
        $this->Speak->speaklog_del($arr);
        show_msg(array('删除成功', '', $this->base_url('speak/log/?speak_id='.$_GET['speak_id'])));
    }
}