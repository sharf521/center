<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if(!empty($title_herder)){echo $title_herder.'-';}?><?=$this->site['name'];?></title>
    <link href="/themes/member/user.css" rel="stylesheet" type="text/css" />
    <script language="javascript" src="/plugin/js/jquery.js"></script>
    <link rel="stylesheet" href="/plugin/layui/css/layui.css" />
    <script src="/plugin/layui/layui.js"></script>
    <script src="/themes/member/member.js"></script>
</head>
<body>
<? if($_GET['from']!='hide') : ?>
<div class="usernav">
    <div class="userlogo">
        <div class="logoleft">
            <a href="/"><img src="<?=$this->site['logo']?>" height="60"></a>
        </div>
        <div class="usermenu">
            <ul>
                <li>
                    <a href="<?=url('')?>">个人中心</a>
                </li>
                <li>
                    <a href="<?=url('logout')?>">退出</a>
                </li>
                <div class="clear"></div>
            </ul>
        </div>
    </div>
</div>
<? endif;?>
