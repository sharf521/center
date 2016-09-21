<?php
namespace App\Controller\Admin;

use App\Model\Algorithm;

class AlgorithmController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->Algorithm=new Algorithm();
    }

    public function index(Algorithm $algorithm)
    {
        $arr = array(
            'user_id' => (int)$_GET['user_id'],
            'money' => (int)$_GET['money'],
            'startdate' => $_GET['startdate'],
            'enddate' => $_GET['enddate']
        );
        $where = "1=1";
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
        $data['result'] = $algorithm->where($where)->orderBy("id desc")->pager($_GET['page'], 10);
        $data['result']['money_total']=$algorithm->where($where)->value("sum(money)");
        $this->view('algorithm',$data);
    }

    public function getLog()
    {
        $return = $this->Algorithm->collectLog();
        if ($return === true) {
            show_msg(array('完成', '', $this->base_url('algorithm')));
        } else {
            show_msg(array('失败！！'));
        }
    }

    //按天小计
    public function listByDays(Algorithm $algorithm)
    {
        if(!isset($_GET['startdate'])){
            $_GET['startdate']=date('Y-m-d',strtotime(date('Y-m-d'))-3600*24*2);
        }
        $where = "1=1";
        if (!empty($_GET['startdate'])) {
            $where .= " and addtime>='{$_GET['startdate']}'";
        }
        if (!empty($_GET['enddate'])) {
            $where .= " and addtime<'{$_GET['enddate']}'";
        }
        $data['result']=$algorithm->select("substring(addtime,1,10) as date,sum(money) as money,status")->where($where)->groupBy('date')->get();
        $this->view('algorithm',$data);
    }

}