<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/22
 * Time: 18:01
 */

namespace App\Controller\Admin;


use App\Helper;
use App\Model\Account;
use App\Model\FBB;
use App\Model\LinkPage;
use App\Model\Partner;
use App\Model\Rebate;
use App\Model\User;
use System\Lib\DB;
use System\Lib\Request;

class PartnerController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Partner $partner,Request $request,User $user,LinkPage $linkPage)
    {
        $where=" 1=1";
        $page=$request->get('page');
        $type=$request->get('type');
        $status=$request->get('status');
        $username=$request->get('username');
        $starttime=$request->get('starttime');
        $endtime=$request->get('endtime');
        if(!empty($username)){
            $user=$user->where("username='{$username}'")->first();
            $where.=" and user_id='{$user->id}'";
        }
        if(!empty($status)){
            $where.=" and status=".$status;
        }
        if(!empty($type)){
            $where.=" and type=".$type;
        }
        if(!empty($starttime)){
            $where.=" and created_at>=".strtotime($starttime);
        }
        if(!empty($endtime)){
            $where.=" and created_at<".strtotime($endtime);
        }
        $data['result']=$partner->where($where)->orderBy('id desc')->pager($page);
        $data['type']=$linkPage->echoLink('partner_type',$request->get('type'),array('name'=>'type'));
        $this->view('partner',$data);
    }

    public function edit(Partner $partner,Request $request,Account $account)
    {
        $convert_rate=Helper::getSystemParam('convert_rate');
        $id=$request->get('id');
        $row=$partner->findOrFail($id);
        if($_POST){
            $total=$row->apply_money;
            $p_userid=(int)$request->post('p_userid');
            $status=(int)$request->post('status');
            $verify_remark=$request->post('verify_remark');
            if (empty($status)) {
                redirect()->back()->with('error', '审核状态必选');
            }
            if (empty($verify_remark)) {
                redirect()->back()->with('error', '审核备注不能为空');
            }
            if ($row->status == 1) {
                try{
                    DB::beginTransaction();

                    $arr = array();
                    $arr['status'] = $status;
                    $arr['verify_userid'] = $this->user_id;
                    $arr['verify_at'] = time();
                    $arr['verify_remark'] = $verify_remark;
                    if ($status == 2) {
                        $arr['money']=$total;
                    }
                    $row->where("id={$id}")->limit(1)->update($arr);
                    if ($status == 2) {
                        //通过 扣除冻结资金
                        $log = array();
                        $log['user_id'] = $row->user_id;
                        $log['type'] = 'partner_success';
                        $log['funds_freeze']=-$total;
                        $log['label'] = "partner_{$row->user_id}";
                        $log['remark'] = "";
                        $account->addLog($log);

                        //写入FBB
                        $fbb=new FBB();
                        $fbb_data = array(
                            'user_id' => $row->user_id,
                            'p_userid' => $p_userid,
                            'money' => math($total,1.31,'/','2')
                        );
                        $fbb->add($fbb_data);

                        //奖励积分
                        $arr = array(
                            'site_id' => $row->site_id,
                            'typeid' => 1,
                            'user_id' => $row->user_id,
                            'money' => math($total,$convert_rate,'*',2)
                        );
                        (new Rebate())->addRebate($arr);

                        //解冻邀请人的200
                        if($row->invite_uid!=0){
                            $log = array();
                            $log['user_id'] = $row->user_id;
                            $log['type'] = 'partner_invite_success';
                            $log['funds_available'] ='200';
                            $log['funds_freeze']='-200';
                            $log['label'] = "partner_{$row->user_id}";
                            $log['remark'] = "";
                            (new Account())->addLog($log);
                        }
                    }elseif($status== 3){
                        //不通过解冻资金
                        $log = array();
                        $log['user_id'] = $row->user_id;
                        $log['type'] = 'partner_fail';
                        $log['funds_available'] =$total;
                        $log['funds_freeze']=-$total;
                        $log['label'] = "partner_{$row->user_id}";
                        $log['remark'] = "";
                        $account->addLog($log);
                    }

                    DB::commit();
                }catch (\Exception $e){
                    DB::rollBack();
                    redirect()->back()->with('error',"fail:".$e->getMessage());
                }
                redirect('partner/?page='.$request->page)->with('msg', '操作成功！');
            } else {
                redirect()->back()->with('error', '己审核！！');
            }
        }else{
            $data['row']=$row;
            $this->view('partner',$data);
        }
    }
}