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
        $this->payUrl='https://www.allinpaycard.com/asaop/rest/api/';
        $this->mer_id='999491055110002';
        $this->app_key='hnszdyzc';
        $this->pfxpath = ROOT.'/ulinkpay_file/99491055110002.pfx'; //密钥文件路径
        $this->privkeypass = 'FXxGdukKraMFGVqcUmJRDVABfucnFibJ'; //私钥密码
    }

    public function index(Request $request)
    {
        if($request->get('t')==1){
            $channel='1';
        }else{
            $channel='0';
        }
        $para=array(
            'app_key'=>$this->app_key,
            'method'=>'allinpay.order.orderinstall.add',//'allinpay.order.orderstage.add.test',
            'format'=>'json',
            'mer_id'=>$this->mer_id,
            'pdno'=>'0200',
            'v'=>'1.0',
            'sign_v'=>'1',
            'amount'=>sprintf("%.2f", 600),
            'channel'=>$channel,//支付渠道：0：pc   1：wap
            'comment'=>'',
            'description'=>'',
            'notify_url'=>'http://center.yuantuwang.com/tongPay/result',
            'return_url'=>'http://wechat.yuantuwang.com',
            'nper'=>'12',//分期数
            'order_id'=>'TL'.time() . rand(10000, 99999),
            'timestamp'=>date('YmdHis'),
            'trade_date'=>date('Ymd'),
            'trade_time'=>date('His'),
            'unalter'=>'nper',
            'cetitype'=>'01'
        );
        /*$para['creditName']='李红';
        $para['idno']=$this->des_encrypt('340603199402064797');
        $para['phoneNo']=$this->des_encrypt('13937127756');
        $para['creditNo']=$this->des_encrypt('6259986239282080');
        $para['validty_period']=$this->des_encrypt('0620');
        $para['cvv']=$this->des_encrypt('490');*/

        $data = array(
            'trade_no' => $para['order_id'],
            'user_id' => 2,
            'status' => 0,
            'money' => $para['amount'],
            'fee' => 0,
            'payment' => 'tonglian',
            'type' => 3,
            'remark' => "信用卡分{$para['nper']}期,{$para['channel']}|{$para['trade_date']}|{$para['trade_time']}",
            'created_at' => time(),
            'addip' => $this->ip()
        );
        DB::table('account_recharge')->insert($data);
        $para['sign']=$this->sign($para);
        print_r($para);
        $sHtml = "<form id='fupaysubmit' name='fupaysubmit' action='{$this->payUrl}' method='post' style='display:'>";
        while (list ($key, $val) = each($para)) {
            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml . "<input type='submit'></form>";
        //$sHtml = $sHtml . "<script>document.forms['fupaysubmit'].submit();</script>";
        echo $sHtml;
    }


    /*        $arr = array(
            'msg' => '消费成功',
            'nper' => '12',
            'orderId' => '149320225680298',
            'result' => '1',
            'totalAmt' => '2127.80',
            'sign' => '0720794c53a495dbb3438cb381c96534fcb12ed0df3c2fef719917335d85c647f2c81ae7ebebc63bd7ed48994a7458aaee1c8c2273f6ddeeceb02b13a0b189b79615f4b837a313948971e4960241491e0a81f70a1a7d1182dd1c4d5c5aafaf40b95b9dac2588bbfc093861f289c02a85d4a24de0708874c1ef60f7dba998dd2d'
        );*/

    /*
     * 【2017-05-03 11:59:19】{"msg":"消费成功","nper":"12","orderId":"TL149378374976329","result":"1","sign":"78ee1761f94da22fe9bbf220070caa9e138b3b24f8106911552322843258644d0989fe03f72ed953f83b33eaddffc20d4380c332b0019499e0678452e8b35264c85b1a98d556aefb6f0355eefdfb7e7f24c7e670b024f26ed73b7b9ad2d30eef76de70086b284635826784bf59df109d535f98b8ff9fbc0cf8a259715a382073e9782d578a6138719dbbc9946f9bf0c879c483d02741ad16a2640d3e324fc2d08a0b6bf5bd0efca5e7b43f9176f7e77cc14b2f33bce100e6e9cd3773f040770b3f3bef1f7154fc1eac3b03290531e23cb6efac5f6f7e17723de534ea700989b5ae9dcec4ff6318d9eb0434cfee83af9497566328d29d40ab5d088053cfdd26b6","totalAmt":"600.00"}
     * */
    public function result(Request $request, Account $account)
    {
        $_POST=array(
            'msg'=>'消费成功',
            'nper'=>'12',
            'orderId'=>'TL149378374976329',
            'result'=>'1',
            'sign'=>'78ee1761f94da22fe9bbf220070caa9e138b3b24f8106911552322843258644d0989fe03f72ed953f83b33eaddffc20d4380c332b0019499e0678452e8b35264c85b1a98d556aefb6f0355eefdfb7e7f24c7e670b024f26ed73b7b9ad2d30eef76de70086b284635826784bf59df109d535f98b8ff9fbc0cf8a259715a382073e9782d578a6138719dbbc9946f9bf0c879c483d02741ad16a2640d3e324fc2d08a0b6bf5bd0efca5e7b43f9176f7e77cc14b2f33bce100e6e9cd3773f040770b3f3bef1f7154fc1eac3b03290531e23cb6efac5f6f7e17723de534ea700989b5ae9dcec4ff6318d9eb0434cfee83af9497566328d29d40ab5d088053cfdd26b6',
            'totalAmt'=>'600.00'
        );
        $this->log($_POST);
        $checkSign = $this->checkSign($_POST);
        if(!$checkSign){
            $this->log('checkSign error');
            exit;
        }

        echo 'ok';
        exit;
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
            $row = DB::table('account_recharge')->where("trade_no='{$UsrSn}'")->row();
            if ($row) {
                if ($row['status'] == 0) {
                    DB::table('account_recharge')->where("trade_no='{$UsrSn}'")->update(array('status' => 1));
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

    //查询
    public function getOrder(Request $request)
    {
        $order_id=$request->get('order_id');
        $para=array(
            'app_key'=>$this->app_key,
            'method'=>'allinpay.order.orderinstall.query',
            'format'=>'json',
            'mer_id'=>$this->mer_id,
            'pdno'=>'0200',
            'v'=>'1.0',
            'sign_v'=>'1',
            'order_id'=>$order_id,
            'timestamp'=>date('YmdHis')
        );
        $para['sign']=$this->sign($para);
        $sHtml = "<form id='fupaysubmit' name='fupaysubmit' action='{$this->payUrl}' method='post' style='display:'>";
        while (list ($key, $val) = each($para)) {
            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml . "<input type='submit'></form>";
        $sHtml = $sHtml . "<script>document.forms['fupaysubmit'].submit();</script>";
        //$html=$this->curl_url($this->payUrl,$para);
        echo $sHtml;
    }

    //退款
    public function refund()
    {
        $para=array(
            'app_key'=>$this->app_key,
            'method'=>'allinpay.order.orderinstall.refund',
            'format'=>'json',
            'mer_id'=>$this->mer_id,
            'pdno'=>'0200',
            'v'=>'1.0',
            'sign_v'=>'1',
            'order_id'=>'TL149328338469412',
            'amount'=>sprintf("%.2f", 3000),
            'channel'=>'0',//下单时的支付渠道：0：pc   1：wap
            'timestamp'=>date('YmdHis'),
            'trade_date'=>'20170427',
            'trade_time'=>'165624',
        );
        $para['sign']=$this->sign($para);
        $sHtml = "<form id='fupaysubmit' name='fupaysubmit' action='{$this->payUrl}' method='post' style='display:'>";
        while (list ($key, $val) = each($para)) {
            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml . "<input type='submit'></form>";
        //$sHtml = $sHtml . "<script>document.forms['fupaysubmit'].submit();</script>";
        echo $sHtml;
        exit;
    }

    private function curl_url($url, $data = array())
    {
        echo $url;
        $ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if ($data) {
            if (is_array($data)) {
                curl_setopt($ch, CURLOPT_POST, 1);
            } else {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data))
                );
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        $data = curl_exec($ch);
        var_dump($data);
        curl_close($ch);
        return $data;
    }

    //关键数据DES加密
    private function des_encrypt ($encrypt,$key='06l3d3zZ')
    {
        // 根據 PKCS#7 RFC 5652 Cryptographic Message Syntax (CMS) 修正 Message 加入 Padding
        $block = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_ECB);
        $pad = $block - (strlen($encrypt) % $block);
        $encrypt .= str_repeat(chr($pad), $pad);

        // 不需要設定 IV 進行加密
        $passcrypt = mcrypt_encrypt(MCRYPT_DES, $key, $encrypt, MCRYPT_MODE_ECB);
        return base64_encode($passcrypt);
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
        echo $this->privkeypass;
        openssl_pkcs12_read($priv_key, $certs, $this->privkeypass); //读取公钥、私钥
        $pubkeyid = $certs['cert']; //公钥
        $res = openssl_verify($data, $signStr, $pubkeyid); //验证
        var_dump($res);
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

    private  function ip()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip_address = $_SERVER["HTTP_CLIENT_IP"];
        } else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        } else if (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip_address = $_SERVER["REMOTE_ADDR"];
        } else {
            $ip_address = '';
        }
        return $ip_address;
    }
}