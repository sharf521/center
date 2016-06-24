<?php
namespace App\Controller\Api;

use App\Model\Algorithm;
use App\Model\FBB;
use App\Model\Rebate;
use App\Model\ZJ;
use System\Lib\DB;

class AlgorithmController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    function index()
    {

    }

    function fbb_do()
    {
        $fbb = new FBB();
        $return = $fbb->calFbb();
        echo $return;
    }

    function fbb_add()
    {
        $fbb = new FBB();
        $post = array(
            'user_id' => $_POST['user_id'],
            'pid' => (int)$_POST['pid'],
            'money' => $_POST['money']
        );
        $return = $fbb->add($post);
        echo $return;
    }

//    function fbb_log_get()
//    {
//        $id = (int)$_POST['id'];
//        $size = (int)$_POST['size'];
//        if ($size == 0) {
//            $size = 100;
//        }
//        $result = DB::table('fbb_log')->select('id,user_id,money*2.52 as money,addtime')->where('id>?')->orderBy('id')->bindValues($id)->limit("0,{$size}")->all();
//        $result = json_encode($result);
//        echo $result;
//    }

    function zj_do()
    {
        $zj = new ZJ();
        $return =$zj->calZj();
        echo $return;
    }

    function zj_add()
    {
        $zj = new ZJ();
        $return = $zj->add(array('user_id' => $_POST['user_id'], 'plate' => 1));
        echo $return;
    }

//    function zj_log_get()
//    {
//        $id = (int)$_POST['id'];
//        $size = (int)$_POST['size'];
//        if ($size == 0) {
//            $size = 100;
//        }
//        $result = DB::table('zj_log')->select('id,user_id,money*2.52 as money,addtime')->where('id>?')->orderBy('id')->bindValues($id)->limit("0,{$size}")->all();
//        $result = json_encode($result);
//        echo $result;
//    }


    function rebate_do()
    {
        $reate = new Rebate();
        $return =$reate->calRebate();
        echo $return;
    }
    /**
     *
     * typeid:1,2,3
     */
    function rebate_add()
    {
        $reate = new Rebate();
        $post = array(
            'user_id' => $_POST['user_id'],
            'site_id' => $_POST['site_id'],
            'typeid' => $_POST['typeid'],
            'money' => $_POST['money']
        );
        $result = $reate->addRebate($post);
        echo $result;
    }

    public function rebate_list()
    {
        $id = (int)$_POST['id'];
        $size = (int)$_POST['size'];
        $site_id = (int)$_POST['site_id'];
        if ($size == 0) {
            $size = 100;
        }
        $result = DB::table('rebate')->select('*')->where('site_id=? and id>?')->orderBy('id')->bindValues(array($site_id,$id))->limit("0,{$size}")->all();
        $result = json_encode($result);
        echo $result;
    }

//    function rebate_log_get()
//    {
//        $id = (int)$_POST['id'];
//        $size = (int)$_POST['size'];
//        if ($size == 0) {
//            $size = 100;
//        }
//        $result = DB::table('rebate_log')->select('id,user_id,money,addtime')->where('id>?')->orderBy('id')->bindValues($id)->limit("0,{$size}")->all();
//        $result = json_encode($result);
//        echo $result;
//    }

    function log_collect()
    {
        $algorithm=new Algorithm();
        $result = $algorithm->collectLog();
        $result = json_encode($result);
        echo $result;
    }

    function log_get()
    {
        $id = (int)$_POST['id'];
        $size = (int)$_POST['size'];
        if ($size == 0) {
            $size = 100;
        }
        $result = DB::table('algorithm_log')->select('id,user_id,money,addtime')->where('id>?')->orderBy('id')->bindValues($id)->limit("0,{$size}")->all();
        $result = json_encode($result);
        echo $result;
    }



}
