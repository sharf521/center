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
            'p_userid' => (int)$_POST['p_userid'],
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
        $return = $zj->calZj();
        echo $return;
    }

    function zj_add()
    {
        $zj = new ZJ();
        $return = $zj->add(array('user_id' => $_POST['user_id'], 'plate' => 1));
        echo $return;
    }

    function rebate_do()
    {
        $reate = new Rebate();
        $return = $reate->calRebate();
        echo $return;
    }

    /**
     *
     * typeid:1,2,3
     */
    public function rebate_add()
    {
        $data=$this->data;
        $user_id=$this->getUserId($data['openid']);
        if($user_id==0){
            return $this->returnError('not find openid：'.$data['openid']);
        }
        if(!in_array($data['typeid'],array(1,2,3))){
            return $this->returnError('parameter typeid error!');
        }
        $reate = new Rebate();
        $post = array(
            'app_id'=>$this->app_id,
            'user_id' => $user_id,
            'site_id' => (int)$data['site_id'],
            'typeid' => $data['typeid'],
            'money' => $data['money'],
            'remark'=>$data['remark']
        );
        $result = $reate->addRebate($post);
        if($result===true){
            return $this->returnSuccess();
        }else{
            return $this->returnError($result);
        }
    }

    public function rebate_list()
    {
        $data=$this->data;
        $site_id=$this->app_id;
        $id = (int)$data['id'];
        $size = (int)$data['size'];
        if ($size == 0) {
            $size = 100;
        }
        $result = DB::table('rebate')->select('*')->where('site_id=? and id>?')->orderBy('id')->bindValues(array($site_id, $id))->limit("0,{$size}")->all();
        return $this->returnSuccess($result);
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
        $algorithm = new Algorithm();
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
