<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/17
 * Time: 16:33
 */

namespace App\Controller\Admin;

use App\Model\UserInfo;
use System\Lib\DB;
use System\Lib\Request;

class RealNameController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(UserInfo $userInfo,Request $request)
    {
        $page=$request->get('page');
        $where = " 1=1";
        if (!empty($_GET['username'])) {
            $user_id=DB::table('user')->where('username=?')->bindValues($_GET['username'])->value('id','int');
            $where .= " and user_id='{$user_id}'";
        }
        if (!empty($_GET['name'])) {
            $where .= " and name='{$_GET['name']}'";
        }
        $starttime=$request->get('starttime');
        $endtime=$request->get('endtime');
        if(!empty($starttime)){
            $where.=" and created_at>=".strtotime($starttime);
        }
        if(!empty($endtime)){
            $where.=" and created_at<".strtotime($endtime);
        }
        $data['result']=$userInfo->where($where)->pager($page,10);
        $this->view('realName',$data);
    }

    public function edit(Request $request,UserInfo $userInfo)
    {
        $id=$request->get('id');
        $page=$request->get('page');
        $userInfo=$userInfo->findOrFail($id);
        if($_POST){
            $remark=$request->post(verify_remark);
            $status=(int)$request->post(card_status);
            if($userInfo->card_status!=1){
                redirect()->back()->with('error','状态异常，请勿重复审核！');
            }

            if($status==0){
                redirect()->back()->with('error','请选择审核状态！');
            }
            if(empty($remark)){
                redirect()->back()->with('error','审核备注不能为空！');
            }
            $userInfo->verify_remark=$remark;
            $userInfo->verify_userid=$this->user_id;
            $userInfo->verify_time=time();
            $userInfo->card_status=$status;
            $userInfo->save();
            if($status==2){
                $user=$userInfo->User();
                $user->name=$userInfo->name;
                $user->save();
            }
            redirect("realName/index/?page={page}")->with('msg','操作完成');
        }else{
            $data['userInfo']=$userInfo;
            $this->view('realName',$data);
        }
    }
}