<!DOCTYPE html>
<html>
<head>
    <link href="/plugin/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script language="javascript" src="/plugin/js/jquery.js"></script>
    <script charset="utf-8" src="/plugin/js/My97DatePicker/WdatePicker.js"></script>
    <link rel="stylesheet" href="/plugin/layui/css/layui.css" />
    <script src="/plugin/layui/lay/dest/layui.all.js"></script>
    <link href="/themes/admin/css/admin.css" rel="stylesheet">
    <script src="/themes/admin/js/base.js"></script>
    <title>管理中心</title>
    <style>
        /*.topbox{width:100%; background:#fff; height:54px; overflow: hidden; border-top:2px solid #2baab1;  box-shadow: 0 1px 1px rgba(0,0,0,.15);  -moz-box-shadow: 0 1px 1px rgba(0,0,0,.15);  -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.15); }*/
        .topbox h3{ float:left;font-weight:normal; font-size:18px; color:#2baab1; line-height:70px; padding:0 0 0 20px;}
        .topbox h3 i{ margin-right:6px; font-size:24px;}

        .nav{ float:left; margin-left:50px;}
        .nav li{float:left; margin-left:20px;  cursor:pointer;color:#018dba; font-size:14px; padding:0 21px; line-height:65px;}
        .nav li.checkit{ color:#FFF; background-color:#4cd1fc;}
        .nav li:hover{background-color:#4cd1fc; color:#FFF;}

        .topnav{ top:10px; position:absolute; right:30px;}
        .topnav li{ float:left;}

        .leftpanel{ background-color:#1c2b36;overflow-y: auto}
        .leftpanel h1{ font-size:16px; color:#f9691a; margin-left:36px; margin-top:30px; display: none}
        /*二级菜单*/
        .menu a{display: block; text-decoration: none; font-size:14px}
        .menu li{line-height:42px;font-family: "微软雅黑";font-size: 14px;  border-bottom: 1px solid #17232c;}
        .menu li a{ color: #7ca0bb; padding-left: 20px;}
        .menu li a:hover{color: #fff; border-left: 5px #1AA094 solid; padding-left: 25px;background-color: #17232c;}
        li.menuSelectd a{ color: #fff; border-left: 5px #1AA094 solid; padding-left: 25px;background-color: #17232c;}

        .layui-tab{ margin: 0px; }
        .layui-tab-content {padding:0px;margin:0px;}
        .larry-tab-box>.layui-tab-title {border-bottom:1px solid #efefef;}
        .larry-tab-box>.layui-tab-title li{ padding-left: 20px;}
        .larry-tab-box>.layui-tab-title .layui-this {color:white;background-color:#1AA094;}
        .larry-tab-box>.layui-tab-title .layui-this:after {border-bottom:0;}
        .layui-tab-card {border: 0px;}
    </style>
</head>
<body>
<div class="layui-layout layui-layout-admin">
    <div class="layui-header topbox">
        <div class="layui-main">
            <h3><i class="fa fa-home"></i>管理后台</h3>
            <ul class="nav">
                <?
                //输出一级菜单
                $num = 1;
                foreach ($menu as $i => $m) {
                    if ($num == 1) {
                        echo "<li class='checkit'>{$m['name']}</li>";
                    } else {
                        echo "<li>{$m['name']}</li>";
                    }
                    $num++;
                }
                ?>
            </ul>
            <ul class="topnav">
                <li class="nihao">您好，<?= $this->username ?>！</li>
                <li class="tuichu"><a href="<?=url('/member')?>" target="_blank">用户中心</a> </li>
                <li class="tuichu"><?= $this->anchor('changepwd', '[修改密码]', 'target="iframe_main"') ?></li>
                <li class="tuichu"><?= $this->anchor('logout', '[退出]') ?></li>
            </ul>
        </div>

        </div>
    <div class="layui-side leftpanel">
            <?
            $num = 0;
            foreach ($menu as $i => $m) {
                $num++;
                //每个一级菜单输出一个div
                ?>
                <div class="menu <? if ($num > 1) {
                    echo 'hide';
                } ?>">
                    <h1><?= $menu[$i]['name'] ?></h1>
                    <ul>
                        <?php
                        //显示左侧二级菜单
                        if (isset($m['son']) && is_array($m['son'])) {
                            foreach ($m['son'] as $li) {
                                ?>
                                <li class="li_item" style="cursor:pointer"><a url="<?= url($li['url']) ?>" target="iframe_main"><?= $li['name'] ?></a></li>
                                <?
                            }
                        }
                        ?>
                    </ul>
                </div>
                <?
            }
            ?>
        </div>
    <div class="layui-body" style="bottom: 0px; padding-left: 8px;">

            <div class="layui-tab layui-tab-card larry-tab-box" id="main-tab" lay-filter="x-tab" lay-allowclose="true">

                <ul class="layui-tab-title">
                    <li class="layui-this">
                        默认
                        <i class="layui-icon layui-unselect layui-tab-close"></i>
                    </li>
                </ul>
                <div class="layui-tab-content" >
                    <div class="layui-tab-item layui-show">
                        <iframe frameborder="0" class="x-iframe" width="100%"></iframe>
                    </div>
                </div>
            </div>
            <!-- <iframe marginheight="0" width="100%" marginwidth="0" frameborder="0" id="iframe_main" name="iframe_main" src=""></iframe>-->
        </div>
</div>
<script>
    $(window).on("resize", function() {
        init_menu();
        _initWH();
    }).resize();
</script>
</body>
</html>
