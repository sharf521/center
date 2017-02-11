<!DOCTYPE html>
<html>
<head>
    <link href="/plugin/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script language="javascript" src="/plugin/js/jquery.js"></script>
    <script charset="utf-8" src="/plugin/js/My97DatePicker/WdatePicker.js"></script>
    <link rel="stylesheet" href="/plugin/layui/css/layui.css" />
    <!--     <script src="/plugin/layui/layui.js"></script>-->
    <script src="/plugin/layui/lay/dest/layui.all.js"></script>
    <link href="/themes/admin/css/admin.css" rel="stylesheet">
    <script src="/themes/admin/js/base.js"></script>
    <title>管理中心</title>
    <style>
        .layui-tab-content {padding:0px;margin:0px;}
        .larry-tab-box>.layui-tab-title {border-bottom:1px solid #1AA094;}
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
        $('.leftpanel').css('height', ($(window).height() - 56 ));
        //$('#iframe_main').css('width', ($(window).width() - 190));
        //$('#iframe_main').css('height', ($(window).height() - 56 ));

        _initWH();
    }).resize();
</script>



</body>
</html>
