<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--允许全屏-->
    <meta name="apple-touch-fullscreen" content="YES">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="pragram" content="no-cache">
    <meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">
    <title>登录</title>
    <link href="/themes/default_wap/css/web_style.css" type="text/css" rel="stylesheet"/>
    <link href="/themes/default_wap/css/web_login.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="/themes/default_wap/js/jquery-1.11.1.min.js"></script>
</head>
<body>
<div class="login_contc">
    <div class="logoslog"><img src="/themes/default_wap/images/m_logo.png"></div>
    <div class="login_form">
        <form id="login_form" method="post">
        <ul>
            <li><i class="usename"></i><input type="text" name="username" placeholder="帐号/邮箱"></li>
            <li><i class="paswod"></i><input type="password" name="password" placeholder="密码"></li>
            <li class="lo_subtb"><input type="submit" class="log_sumb" value="登录"></li>
        </ul>
        <p class="m_alinka"><!--<a href="#">忘记密码？</a>--><a style="float:right;" href="/register">新用户注册</a></p>
            <input type="hidden" name="_token"  value="<?=_token();?>"/>
        </form>
    </div>
</div>
<div class="lo_copy">&copy; 远途网 2016</div>
<?php require 'footer.php';?>