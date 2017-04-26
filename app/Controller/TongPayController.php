<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/27
 * Time: 11:05
 */

namespace App\Controller;

use App\Model\Account;
use App\Model\System;
use System\Lib\DB;
use System\Lib\Request;

class TongPayController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $url='http://gateway.ulinkpay.com:8002/asaop/rest/api/';
        $para=array(
            'app_key'=>'testhn',
            'method'=>'allinpay.order.orderstage.add.test',
            'format'=>'json',
            'mer_id'=>'999290053990002',
            'pdno'=>'0200',
            'v'=>'1.0',
            'sign_v'=>'1',
            'amount'=>'2000.00',
            'channel'=>'1',//支付渠道：0：pc   1：wap
            'comment'=>'',
            'description'=>'',
            'notify_url'=>'http://center.yuantuwang.com/tongPay/result',
            'return_url'=>'http://www.yuantuwang.com',
            'nper'=>'12',//分期数
            'order_id'=>time() . rand(10000, 99999),
            'timestamp'=>date('YmdHis'),
            'trade_date'=>date('Ymd'),
            'trade_time'=>date('His'), 
            'unalter'=>'nper'
        );

        $data = array(
            'trade_no' => $para['order_id'],
            'user_id' => 2,
            'status' => 0,
            'money' => $para['amount'],
            'fee' => 0,
            'payment' => 'tonglian',
            'type' => 1,
            'remark' => '',
            'created_at' => time(),
            'addip' => ip()
        );
        DB::table('account_recharge')->insert($data);

        echo "\r\n\r\n 参数==================================\r\n\r\n";
        print_r($para);
        $sign_str=$this->getsignstr($para);
        echo "\r\n\r\n 签名前字符==================================\r\n\r\n".$sign_str;

        echo "\r\n\r\n 签名==================================\r\n\r\n";

        $sign_str=$this->sign($sign_str);
        echo $sign_str;
        $para['sign']=$sign_str;
        echo "\r\n\r\n 所有数值==================================\r\n\r\n";
        print_r($para);

        $sHtml = "<form id='fupaysubmit' name='fupaysubmit' action='{$url}' method='post' style='display:'>";
        while (list ($key, $val) = each($para)) {
            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml . "<input type='submit'></form>";
        //$sHtml = $sHtml . "<script>document.forms['fupaysubmit'].submit();</script>";
        echo $sHtml;
    }

    private function sign($data)
    {
        $privkeypass = '123456'; //私钥密码
        $pfxpath = ROOT.'/pay_file/private.pfx'; //密钥文件路径
        $priv_key = file_get_contents($pfxpath); //获取密钥文件内容
        //$data = "test"; //加密数据测试test
        //私钥加密
        openssl_pkcs12_read($priv_key, $certs, $privkeypass); //读取公钥、私钥
        $prikeyid = $certs['pkey']; //私钥
        openssl_sign($data, $signMsg, $prikeyid,OPENSSL_ALGO_SHA1); //注册生成加密信息
        //$signMsg = base64_encode($signMsg); //base64转码加密信息
        $signMsg=bin2hex($signMsg);//转16进制
        return $signMsg;
    }

    /**
    msg=订单提交成功，待支付
    orderId=201608301002296995
    result=0
    sign=06a85083a70fed3c90d2
     */
    public function result(Request $request, Account $account)
    {
        $myfile = fopen(ROOT."/public/data/tonglian".date('YmdHis').".txt", "w");
        $txt = json_encode($_REQUEST);
        fwrite($myfile, $txt);
        $txt = json_encode("\r\n POST：".$_POST);
        fwrite($myfile, $txt);
        fclose($myfile);

        $UsrSn=$request->post('orderId');
        $result=$request->post('result');
        $sign=$request->post('sign');
        if($result!=1){
            exit;
        }
        //判断缓存中是否有 创建交易cache缓存文件
        $path = ROOT . "/public/data/pay_cache/" . date("Y-m") . "/";
        if (!file_exists($path)) {
            mkdir($path,0777,true);
        }
        $file = $path . $UsrSn;
        $fp = fopen($file, 'w+');
        chmod($file, 0777);
        if (flock($fp, LOCK_EX | LOCK_NB)) //设定模式独占锁定和不堵塞锁定
        {
            ////////////start处理////////////////////////////
            $row = DB::table('account_recharge')->where("trade_no={$UsrSn}")->row();
            if ($row) {
                if ($row['status'] == 0) {
                    DB::table('account_recharge')->where("trade_no={$UsrSn}")->update(array('status' => 1));
                    $log = array();
                    $log['user_id'] = $row['user_id'];
                    $log['type'] = 1;
                    $log['funds_available'] = $row['money'] - $row['fee'];
                    $log['remark'] = "在线分期：" . $row['id'];
                    $account->addLog($log);
                }
            }
            echo 'success';
            ///////////结束处理/////////////////////////////
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

    //参数排序
    private function argSort($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }

    private function getsignstr($para)
    {
        $para = $this->argSort($para);
        $arg = "";
        while (list ($key, $val) = each($para)) {
            if($val!=''){
                $arg .= $key . $val;
            }
        }
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }
        return $arg;
    }


}