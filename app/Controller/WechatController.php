<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/18
 * Time: 18:37
 */

namespace App\Controller;


use App\Model\User;
use App\WeChat;
use EasyWeChat\Payment\Order;
use System\Lib\DB;
use System\Lib\Request;

class WechatController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function recharge(Request $request,User $user)
    {
        $id=$request->get('id');
        $openid=$request->get('openid');
        $appid=$request->get('appid');
        if(empty($id)){
            $app_id = DB::table('app')->where('appid=?')->bindValues($appid)->value('id');
            $id=DB::table('app_user')->where('app_id=? and openid=?')->bindValues(array($app_id, $openid))->value('user_id','int');
            $user=$user->findOrFail($id);
        }else{
            $user=$user->findOrFail($id);
        }
        $data['user']=$user;


        $weChat=new WeChat();
        $app=$weChat->app;
        $payment = $app->payment;
        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => '支付订单',
            'out_trade_no'     => time().rand(10000,99999),
            'total_fee'        => math(88,100,'*',2),
            'attach'=>'',
            'openid'=>$request->get('wechat_openid'),
            'notify_url'       => "http://{$_SERVER['HTTP_HOST']}/index.php/wxapi/payNotify/"
        ];
        $order=new Order($attributes);
        $result = $payment->prepare($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $js = $app->js;
            $data['config']=$js->config(array('chooseWXPay','openAddress','checkJsApi'), false);
            $pay=$weChat->getPayParams($result->prepay_id);
            $data['pay']=$pay;
            //$task->out_trade_no=$attributes['out_trade_no'];
            //$task->save();
        }


        $this->title='我要冲值';
        $this->view('wechat_recharge',$data);
    }
}