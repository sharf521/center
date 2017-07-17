<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title><?php if(!empty($title_herder)){echo $title_herder.'-';}?><?=$this->site->name?></title>
    <script charset="utf-8" src="/plugin/layer.mobile.v2/layer.js"></script>
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.0/weui.css"/>
    <link rel="stylesheet" href="/plugin/iconfont/iconfont.css?<?=rand(10000,99999)?>" />
    <link rel="stylesheet" href="/themes/member_wap/member.css"/>
    <script type="text/javascript" src="/themes/member_wap/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/themes/member_wap/member.js?<?=rand(10000,99999)?>"></script>
</head>
<body ontouchstart>