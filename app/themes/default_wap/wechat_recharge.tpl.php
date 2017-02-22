<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>我要充值</title>
    <script src="/plugin/js/jquery.js"></script>
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.0/weui.css"/>
    <link rel="stylesheet" href="/themes/default_wap/wechat.css?7887"/>
    <script src="/themes/default_wap/wechat.js"></script>
</head>
<body ontouchstart>

<div class="m_header">
    <a class="m_header_l" href="<?=$url?>">返回</a>
    <a class="m_header_r"></a>
    <h1><?=$this->title?></h1>
</div>

<div class="margin_header"></div>

<h2><?=$user->username?></h2>
<form method="post" id="form1">
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">金额</label></div>
            <div class="weui-cell__bd prices">
                <?
                $array_ms=array(50,100,500,1000);
                if($money!=0 && ! in_array($money,$array_ms)){
                    array_unshift($array_ms,$money);
                }
                foreach ($array_ms as $m):
                    if($m==$money){
                        echo "\r\n<span class='weui-btn weui-btn_mini weui-btn_primary'>{$m}</span>";
                    }
                    else{
                        echo "\r\n<span class='weui-btn weui-btn_mini weui-btn_plain-primary'>{$m}</span>";
                    }
                endforeach;
                ?>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label"></label></div>
            <div class="weui-cell__bd">
                <input type="button" class="btn_other weui-btn weui-btn_mini weui-btn_plain-primary" value="其它金额">
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary btn_recharge">立即充值</a>
    </div>
</form>

<div class="weui-mask hide"></div>
<div class="div_bot">
    <div class="weui-form-preview">
        <div style=" line-height: 30px; padding: 10px 20px;">微信充值</div>
        <div class="weui-form-preview__bd">
            <div class="weui-form-preview__item">
                <label class="weui-form-preview__label">充值金额</label>
                <span class="weui-form-preview__value"><input class="weui-input" onkeyup="value=value.replace(/[^0-9.]/g,'')" type="tel" name="money" placeholder="￥" value=""/></span>
            </div>
        </div>
        <div class="weui-form-preview__ft">
            <span class="bot_btn1 weui-form-preview__btn weui-form-preview__btn_primary">确定</span>
            <span class="bot_btn2 weui-form-preview__btn weui-form-preview__btn_default">取消</span>
        </div>
    </div>
</div>

<script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    var money='<?=$money?>';
    wechat_recharge();
    if(money>0){
        var layer1=layer.open({
            type: 2
            ,content: '加载中'
        });
        wx.config(<?=$config?>);
        wx.ready(function () {
            layer.close(layer1);
            $(".btn_recharge").click(function () {
                $.post("<?=url('wechat/payPre/')?>", { user_id: "<?=$user->id?>", trade_no: "<?=$trade_no?>",money:money }, function(data){
                    if(data=='true'){
                        wx.chooseWXPay({
                            timestamp: '<?=$pay['timestamp']?>',
                            nonceStr: '<?=$pay['nonceStr']?>',
                            package: '<?=$pay['package']?>',
                            signType: 'MD5',
                            paySign: '<?=$pay['paySign']?>',
                            success: function (res) {
                                //alert('支付成功！');
                                window.location = "<?=$url?>";
                            }
                        });
                    }
                });
            });
            <? if($_GET['t']=='1') : ?>
            $(".btn_recharge").click();
            <? endif;?>
        });
    }else{
        $(function () {
            $(".btn_recharge").click(function () {
                layer.open({
                    content: '请选择金额！'
                    ,skin: 'msg'
                    ,time: 2
                });
            });
        });
    }
</script>
<?php require 'footer_v2.php';?>