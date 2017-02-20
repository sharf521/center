<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>我要充值</title>
    <script src="/plugin/js/jquery.js"></script>
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.0/weui.css"/>
    <link rel="stylesheet" href="/themes/base_wap.css?7887"/>
    <style type="text/css">
        *{max-height: 9999999px;}
        /*定义滚动条高宽及背景 高宽分别对应横竖滚动条的尺寸*/
        ::-webkit-scrollbar
        {
            width: 0;
            height: 0;
            background-color: #fff;
            display: none;
        }
        @media only screen and (min-width:800px){
            body{ width:760px; margin:0 auto; }
        }
        ul,li{list-style: none}
        em, i {
            font-style: normal;
        }
        .hide{display: none}
        .clear{ clear:both}
        .clearFix:after{
            clear:both;
            display:block;
            visibility:hidden;
            height:0;
            line-height:0;
            content:'';
        }

        .m_header{ line-height:28px;height:28px; padding:8px 0;background: #eaeaea;color:#333;text-align: center; width:100%; display:block; position:fixed; top:0; left:0; z-index:999;}
        .m_header a{color: #333;}
        .m_header h1{ height:28px; padding:0 60px; width:100%; box-sizing:border-box; font-size:20px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; font-weight:normal;}
        .m_header .m_header_r{ position:absolute;height: 28px; display:block;right:0; top:0; padding:8px 10px;}
        .m_header .m_header_l{ position:absolute;height: 28px; display:block;left:0; top:0;padding:8px 10px;}
        .m_header .m_header_l i{ font-size: 20px; color: #333;}
        .margin_header{margin-top: 50px;}
        .div_bot {
            z-index: 9999;
            position: fixed;
            border-top: 1px solid #efefef;
            bottom: -1px;
            width: 100%;
            background-color: #F7F7FA;
            left: 0;
            padding: 20px 10px;
            display: none;
        }
    </style>
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
    充值金额：
    <input style="line-height: 28px; border: 1px solid #ccc;" size="10" onkeyup="value=value.replace(/[^0-9.]/g,'')" type="tel" name="money" placeholder="￥" value=""/>
    <input type="button" class="bot_btn1 weui-btn weui-btn_mini weui-btn_primary" value="确定">
    <input type="button" class="bot_btn2 weui-btn weui-btn_mini weui-btn_plain-primary" value="取消">
</div>


<script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    var href=window.location.href;
    href=href.replace('&money=<?=$money?>','');
    $(function () {
        $('.prices span').on('click',function () {
           var m=Number($(this).html());
            window.location=href+'&money='+m;
        });

        $('.btn_other').on('click',function () {
            $('.weui-mask').show();
            $('.div_bot').show();
            $('.div_bot').slideDown(150);
        });
        $('.div_bot .bot_btn2').on('click',function () {
            $('.weui-mask').hide();
            $('.div_bot').hide();
            $('.div_bot').slideUp(150);
        });
        $('.div_bot .bot_btn1').on('click',function () {
            var m=Number($(this).prev("input[name='money']").val());
            window.location=href+'&money='+m;
        });
    });
    wx.config(<?=$config?>);
    wx.ready(function () {
        $(".btn_recharge").click(function () {
            wx.chooseWXPay({
                timestamp: '<?=$pay['timestamp']?>',
                nonceStr: '<?=$pay['nonceStr']?>',
                package: '<?=$pay['package']?>',
                signType: 'MD5',
                paySign: '<?=$pay['paySign']?>',
                success: function (res) {
                    alert('支付成功！');
                    //window.location = "/index.php/weixin/orderShow/?task_id=<?=$task->id?>";
                }
            });
        });
    });
</script>
<?php require 'footer.php';?>