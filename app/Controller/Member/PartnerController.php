<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/22
 * Time: 16:19
 */

namespace App\Controller\Member;


use App\Model\LinkPage;
use App\Model\Partner;
use System\Lib\Request;

class PartnerController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(LinkPage $linkPage,Request $request,Partner $partner)
    {
        $partner=$partner->where("user_id=?")->bindValues($this->user_id)->first();
        $account=$this->user->Account();
        if($_POST){
            $total=$request->post('partner_type');
            $type_arr=$linkPage->getLink('partner_type');
            if(!array_key_exists($total,$type_arr)){
                redirect()->back()->with('error','数据异常！');
            }
            if($total > $account->funds_available){
                redirect()->back()->with('error','您的可用余额不足！');
            }
            $checkPwd=$this->user->checkPayPwd($request->post('zf_password'),$this->user);
            if($checkPwd!==true){
                redirect()->back()->with('error','支付密码错误！');
            }
            //写入申请表
            $partner->user_id=$this->user_id;
            $partner->status=1;
            $partner->type=$total;
            $partner->apply_money=$total;
            $partner->save();

            //冻结资金
            $log = array();
            $log['user_id'] = $this->user_id;
            $log['type'] = 'partner_apply';
            $log['funds_available'] ='-'.$total;
            $log['funds_freeze']=$total;
            $log['label'] = "partner_{$this->user_id}";
            $log['remark'] = "";
            $account->addLog($log);

            redirect()->back()->with('msg','申请己上报，等待管理员审核！');
        }else{
            $data['type']=$linkPage->echoLink('partner_type',$partner->type,array('type'=>'radio'));
            $data['userInfo']=$this->user->UserInfo();
            $data['account']=$account;
            $data['partner']=$partner;
            $this->view('partner',$data);
        }
    }
}