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
        $this->pfxpath = ROOT.'/pay_file/private.pfx'; //密钥文件路径
        $this->privkeypass = '123456'; //私钥密码
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
            'channel'=>'0',//支付渠道：0：pc   1：wap
            'comment'=>'',
            'description'=>'',
            'notify_url'=>'http://center.yuantuwang.com/tongPay/result',
            'return_url'=>'http://www.yuantuwang.com',
            'nper'=>'12',//分期数
            'order_id'=>'TL'.time() . rand(10000, 99999),
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
            'remark' => "信用卡分{$para['nper']}期",
            'created_at' => time(),
            'addip' => ip()
        );
        DB::table('account_recharge')->insert($data);
        $para['sign']=$this->sign($para);
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
        $sign_str=$this->getsignstr($data);
        //echo "\r\n\r\n 签名前字符==================================\r\n\r\n".$sign_str;
        $priv_key = file_get_contents($this->pfxpath); //获取密钥文件内容
        //私钥加密
        openssl_pkcs12_read($priv_key, $certs, $this->privkeypass); //读取公钥、私钥
        $prikeyid = $certs['pkey']; //私钥
        openssl_sign($sign_str, $signMsg, $prikeyid,OPENSSL_ALGO_SHA1); //注册生成加密信息
        //$signMsg = base64_encode($signMsg); //base64转码加密信息
        $signMsg=bin2hex($signMsg);//转16进制
        return $signMsg;
    }

    private function checkSign($data)
    {
        $signStr=$data['sign'];
        $signStr=hex2bin($signStr);
        unset($data['sign']);
        $data=$this->getsignstr($data);
        $priv_key = file_get_contents($this->pfxpath); //获取密钥文件内容
        openssl_pkcs12_read($priv_key, $certs, $this->privkeypass); //读取公钥、私钥
        $pubkeyid = $certs['cert']; //公钥

        $res = openssl_verify($data, $signStr, $pubkeyid); //验证
        //echo $res; //输出验证结果，1：验证成功，0：验证失败
        if($res=='1'){
            return true;
        }else{
            return false;
        }
    }

    private function log($data)
    {
        $myfile = fopen(ROOT."/public/data/tonglian".date('YmdHi').".txt", "a+");
        if(is_array($data)){
            $data=json_encode($data);
        }
        fwrite($myfile, '【'.date('Y-m-d H:i:s').'】'.$data."\r\n");
        fclose($myfile);
    }

    public function result(Request $request, Account $account)
    {
        $this->log($_POST);
/*        $arr = array(
            'msg' => '消费成功',
            'nper' => '12',
            'orderId' => '149320225680298',
            'result' => '1',
            'totalAmt' => '2127.80',
            'sign' => '0720794c53a495dbb3438cb381c96534fcb12ed0df3c2fef719917335d85c647f2c81ae7ebebc63bd7ed48994a7458aaee1c8c2273f6ddeeceb02b13a0b189b79615f4b837a313948971e4960241491e0a81f70a1a7d1182dd1c4d5c5aafaf40b95b9dac2588bbfc093861f289c02a85d4a24de0708874c1ef60f7dba998dd2d'
        );*/
        $checkSign = $this->checkSign($_POST);
        if(!$checkSign){
            $this->log('checkSign error');
            exit;
        }
        $msg=$request->post('msg');
        $nper=$request->post('nper');//12
        $totalAmt=$request->post('totalAmt');//2127.80
        $UsrSn=$request->post('orderId');
        $result=$request->post('result');
        if($result!=1){
            echo 'success';
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