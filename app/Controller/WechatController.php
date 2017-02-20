<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/18
 * Time: 18:37
 */

namespace App\Controller;


use App\Model\Account;
use App\Model\AccountRecharge;
use App\Model\User;
use App\WeChat;
use EasyWeChat\Payment\Order;
use System\Lib\DB;
use System\Lib\Request;
use System\Lib\Session;

class WechatController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }
    
    public function recharge(Request $request,User $user)
    {
        $money=abs((float)$request->get('money'));
        $url=$request->get('url');
        if($money>5000){
            $money=5000;
            (new Session())->flash('msg','单次最多充值5000');
        }
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
            'total_fee'        => math($money,100,'*',2),
            'attach'=>'',
            'openid'=>$request->get('wechat_openid'),
            'notify_url'       => "http://centerwap.yuantuwang.com/index.php/wechat/payNotify/"
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
        $data['trade_no']=$attributes['out_trade_no'];
        $this->title='我要冲值';
        $data['money']=$money;
        $data['url']=$url;
        $this->view('wechat_recharge',$data);
    }
    
    public function payPre(Request $request,AccountRecharge $recharge)
    {
        $user_id=(int)$request->post('user_id');
        $money=(float)$request->post('money');
        $recharge->user_id=$user_id;
        $recharge->trade_no=$request->post('trade_no');
        $recharge->status=0;
        $recharge->money=$money;
        $recharge->type=1;
        $recharge->remark='微信充值';
        $recharge->fee=0;
        $recharge->addip=ip();
        $recharge->save();
        echo  'true';
        return 'true';
    }

    public function payNotify()
    {
        $weChat=new WeChat();
        $app=$weChat->app;
        $response = $app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            //$id=(int)$notify->attach;
            $out_trade_no=$notify->out_trade_no;
            $recharge=(new AccountRecharge())->where('trade_no=?')->bindValues($out_trade_no)->first();
            if(! $recharge->is_exist){
                // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }else{
                // 检查订单是否已经更新过支付状态
                if ($recharge->status!=0 || $recharge->trade_no !=$out_trade_no) {
                    return true; // 已经支付成功了就不再更新了
                }
                // 用户是否支付成功
                if ($successful) {
                    $recharge->money=(float)math($notify->total_fee,100,'/',2);
                    $recharge->verify_userid=0;
                    $recharge->verify_time=time();
                    $recharge->verify_remark='';
                    $recharge->status = 1;
                    $inser_id=$recharge->save(true);

                    $log = array();
                    $log['user_id'] = $recharge->user_id;
                    $log['type'] = 1;
                    $log['funds_available'] = $recharge->money;
                    $log['remark'] = "充值ID：" . $inser_id;
                    (new Account())->addLog($log);
                } else {
                    // 用户支付失败
                }
            }
            return true; // 返回处理完成
        });
        $response->send();
    }
}