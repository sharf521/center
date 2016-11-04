<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="/themes/platform/css.css" />
    <style type="text/css">
        ul,li{list-style: none;  padding: 0px; margin: 0px;}
        .leftbox{border: 1px solid #b6c0c9; margin: 5px; min-height: 200px;}
        .leftbox_title{ background-image: url("/themes/platform/images/leftMenu.jpg");COLOR: #476064;FONT-SIZE: 14px; FONT-WEIGHT: bold; line-height: 29px; padding-left: 40px}
        .leftbox ul{padding: 5px 0px}
        .leftbox ul li{line-height: 25px; font-size: 14px; padding-left: 30px;}

    </style>
</head>
<body topmargin=0 bgcolor=''>
<div class="leftbox">
    <div class="leftbox_title">会员大厅</div>
    <ul>
        <li><a href="index.php?module=showping" target="middle">注册购物</a></li>
        <li><a href="<?=url('notice')?>" target="middle">最新通知</a></li>
        <li><a href="<?=url('package')?>" target="middle">自购平台</a></li>
        <li><a href="<?=url('account/convertIn')?>" target="middle">兑换电子币</a></li>
        <li><a href="<?=url('account/convertOut')?>" target="middle">兑换现金</a></li>
        <li><a href="<?=url('account/payToUser')?>" target="middle">电子币转帐</a></li>
        <li><a href="<?=url('account/log')?>" target="middle">电子币明细</a></li>
        <li><a target="middle" href="/member/user/userInfo/?from=hide">个人信息</a></li>
        <li><a target="middle" href="/member/user/bank/?from=hide">银行帐号</a></li>
        <li><a target="middle" href="/member/user/realName/?from=hide">实名认证</a></li>
    </ul>
</div>

<div class="leftbox">
    <div class="leftbox_title">会员办公室</div>
    <ul>
        <li><a target="middle" href="/member/account/recharge/?from=hide">我要充值</a></li>
        <li><a target="middle" href="/member/account/cash/?from=hide">我要提现</a></li>
        <li><a target="middle" href="/member/password/changePwd/?from=hide">修改密码</a></li>
        <li><a target="middle" href="/member/password/changePayPwd/?from=hide">修改支付密码</a></li>
    </ul>
</div>
</body>
</html>