<?php
namespace App\Controller\Admin;

use App\Model\Rebate;
use App\Model\RebateList;
use App\Model\RebateLog;
use System\Lib\DB;

class RebateController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->rebate = new Rebate();
    }

    function index(Rebate $rebate)
    {
        $arr = array(
            'typeid' => (int)$_GET['typeid'],
            'startdate' => $_GET['startdate'],
            'enddate' => $_GET['enddate'],
            'status' => $_GET['status'],
            'user_id' => (int)$_GET['user_id']
        );
        $where = " 1=1";
        if (!empty($arr['typeid'])) {
            $where .= " and typeid={$arr['typeid']}";
        }
        if (!empty($arr['startdate'])) {
            $where .= " and addtime>='{$arr['startdate']}'";
        }
        if (!empty($arr['enddate'])) {
            $where .= " and addtime<'{$arr['enddate']}'";
        }
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if ($arr['status'] != '') {
            $where .= " and status={$arr['status']}";
        }
        $data['result'] = $rebate->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $data['result']['moneys']=$rebate->where($where)->value('sum(money)');
        $this->view('rebate', $data);
    }

    function add($data)
    {
        if ($_POST) {
            $post = array(
                'user_id' => $_POST['user_id'],
                'site_id' => $_POST['site_id'],
                'typeid' => $_POST['typeid'],
                'money' => $_POST['money']
            );
            $this->rebate->addRebate($post);
            show_msg(array('添加成功', '', $this->base_url('rebate')));
        } else {
            $this->view('rebate', $data);
        }
    }

    function calRebate()
    {
        $return=$this->rebate->calRebate();
        if ($return === true) {
            show_msg(array('完成', '', $this->base_url('rebate')));
        } else {
            show_msg(array('失败！！'));
        }
    }

    function delete()
    {
        show_msg(array('删除成功', '', $this->base_url('usertype')));
        //$this->redirect('usertype');
    }

    function rebatelist(RebateList $rebateList)
    {
        $arr = array(
            'typeid' => (int)$_GET['typeid'],
            'startdate' => $_GET['startdate'],
            'enddate' => $_GET['enddate'],
            'user_id' => (int)$_GET['user_id']
        );
        $where = " 1=1";
        if (!empty($arr['typeid'])) {
            $where .= " and typeid={$arr['typeid']}";
        }
        if (!empty($arr['startdate'])) {
            $where .= " and addtime>='{$arr['startdate']}'";
        }
        if (!empty($arr['enddate'])) {
            $where .= " and addtime<'{$arr['enddate']}'";
        }
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if ($arr['status'] != '') {
            $where .= " and status={$arr['status']}";
        }
        $data['result'] = $rebateList->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $this->view('rebate', $data);
    }

    function rebatelog(RebateLog $rebateLog)
    {
        $arr = array(
            'typeid' => $_GET['typeid'],
            'startdate' => $_GET['startdate'],
            'enddate' => $_GET['enddate'],
            'user_id' => (int)$_GET['user_id'],
            'rebate_id' => (int)$_GET['rebate_id'],
            'money' => (float)$_GET['money']
        );
        $where = " 1=1";
        if (!empty($arr['typeid'])) {
            $where .= " and typeid like '{$arr['typeid']}%'";
        }
        if (!empty($arr['user_id'])) {
            $where .= " and user_id={$arr['user_id']}";
        }
        if (!empty($arr['money'])) {
            $where .= " and money={$arr['money']}";
        }
        if (!empty($arr['startdate'])) {
            $where .= " and addtime>='{$arr['startdate']}'";
        }
        if (!empty($arr['enddate'])) {
            $where .= " and addtime<'{$arr['enddate']}'";
        }
        if (!empty($arr['rebate_id'])) {
            $where .= " and rebate_id={$arr['rebate_id']}";
        }
        $data['result'] = $rebateLog->where($where)->orderBy('id desc')->pager($_GET['page'],10);
        $data['result']['moneys']=$rebateLog->where($where)->value('sum(money)');
        $this->view('rebate', $data);
    }
}