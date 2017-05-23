<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/22
 * Time: 16:19
 */

namespace App\Controller\Member;


use App\Helper;
use App\Model\Account;
use App\Model\LinkPage;
use App\Model\Partner;
use App\Model\Rebate;
use App\Model\User;
use App\WeChat;
use System\Lib\Request;

class PartnerController extends MemberController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(LinkPage $linkPage,Request $request,Partner $partner)
    {
        $convert_rate=Helper::getSystemParam('convert_rate');
        $invite_code=$request->invite_code;
        $invite_uid=0;
        if(!empty($invite_code)){
            $invite_uid=(int)Helper::encrypt($invite_code,'D');
            if($invite_uid==0){
                redirect()->back()->with('error','邀请码无效。');
                $pPartner=(new Partner())->where('status=2 and user_id=?')->bindValues($invite_uid)->first();
                if(!$pPartner->is_exist){
                    redirect()->back()->with('error','邀请码无效！');
                }

            }
        }

        $partner=$partner->where("user_id=?")->bindValues($this->user_id)->first();
        if($partner->is_exist){
            $invite_code=Helper::encrypt($partner->invite_uid);
        }elseif($invite_uid!=0){
/*
            //冻结邀请人资金
            $inviteAccount=(new Account())->find($invite_uid);
            if($inviteAccount->funds_available<262){
                redirect()->back()->with('error','您的邀请人余额不足262元，邀请码暂不可用！');
            }
            $rebate_money=math(262,$convert_rate,'*',2);
            $log = array();
            $log['user_id'] = $invite_uid;
            $log['type'] = 'partner_invite';
            $log['funds_available'] ='-262';
            $log['funds_freeze']=200;
            $log['funds_available_now']=$inviteAccount->funds_available;
            $log['label'] = "partner_{$this->user_id}";
            $log['remark'] = "邀请合伙人{$this->user->username}，触发积分奖励计划：{$rebate_money}积分。";
            $inviteAccount->addLog($log);

            //奖励积分
            $arr = array(
                'site_id' => $this->site->id,
                'typeid' => 1,
                'user_id' => $invite_uid,
                'money' => $rebate_money
            );
            (new Rebate())->addRebate($arr);
            */
            //写入申请表
            $partner->site_id=$this->site->id;
            $partner->user_id=$this->user_id;
            $partner->invite_uid=$invite_uid;
            $partner->status=0;
            $partner->type=0;
            $partner->apply_money=0;
            $partner->save();
            $partner=$partner->where("user_id=?")->bindValues($this->user_id)->first();
        }
        $account=$this->user->Account();
        if($_POST){
            if($partner->status!=0){
                redirect()->back()->with('error','己审请，勿重复提交！');
            }
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
            $partner->site_id=$this->site->id;
            $partner->user_id=$this->user_id;
            $partner->invite_uid=$invite_uid;
            $partner->status=1;
            $partner->type=1;
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
            if($invite_uid!=0){
                $invite_uname=(new User())->find($invite_uid)->username;
                $log['remark'] = "邀请人：{$invite_uname}";
            }
            $account->addLog($log);

            redirect()->back()->with('msg','申请己上报，等待管理员审核！');
        }else{
            $data['invite_code']=$invite_code;
            $data['type']=$linkPage->echoLink('partner_type',$partner->type,array('type'=>'radio'));
            $data['userInfo']=$this->user->UserInfo();
            $data['account']=$account;
            $data['partner']=$partner;
            if($partner->status==2){
                $data['invite_code']=Helper::encrypt($this->user_id);
                $data['invite_url']=$this->site->wap_url."/member/partner/?invite_code=".urlencode($data['invite_code']);
                if($this->is_inWeChat){
                    $data['invite_url']=(new WeChat())->shorten($data['invite_url']);
                }
                $data['invite_img']=Helper::QRcode($data['invite_url'],'fbb',($data['invite_code']));
                //邀请列表
                $invite_list=(new Partner())->where("invite_uid=?")->bindValues($this->user_id)->get();
                $data['invite_list']=$invite_list;
            }
            $this->view('partner',$data);
        }
    }
}