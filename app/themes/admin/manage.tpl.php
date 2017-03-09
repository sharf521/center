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
        .topbox{height: 55px;}
        .topbox h3{ float:left;font-weight:normal; font-size:22px; color:#2baab1; line-height:55px; margin:0 40px;}
        .nav{ float:left;}
        .nav li{float:left; margin-left:20px;  cursor:pointer;color:#018dba; font-size:14px; padding:0 21px; line-height:55px;}
        .nav li.checkit{ color:#FFF; background-color:#4cd1fc;}
        .nav li:hover{background-color:#4cd1fc; color:#FFF;}

        .topbox .layui-nav{position: absolute; right: 0; top: 0; padding: 0; background: none;}
        .topbox .layui-nav .layui-nav-item{margin: 0 15px; line-height: 60px;}
        .topbox .layui-nav .layui-nav-item a{color: #999;}
        .topbox .layui-nav .layui-this{background-color: #f2f2f2;}
        .topbox .layui-nav .layui-nav-item a:hover{color: #1AA094;}
        .topbox .layui-nav .layui-this:after,.topbox  .layui-nav-bar{background-color: #5FB878;}

        .leftpanel{ background-color:#393D49;overflow-y: auto}
        .leftpanel h4{ font-size: 16px;  line-height: 50px;    background: #2B2E37;color: #ccc;      text-indent: 35px;        }

        .layui-layout-admin .layui-header { background-color: #fff;border-bottom: 5px solid #1AA094; }
        .layui-tab{ margin: 0px; }
        .layui-tab-content {padding:0px;margin:0px;}
        .larry-tab-box>.layui-tab-title {border-bottom:0px solid #1AA094;}
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
            <h3>管理后台</h3>
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
            <ul class="layui-nav" lay-filter="">
                <li class="layui-nav-item"><a href="<?=url('/member')?>" target="_blank">用户中心</a></li>
                <li class="layui-nav-item">
                    <a href="javascript:;"><?= $this->username ?></a>
                    <dl class="layui-nav-child">
                        <dd class="li_item"><a href="javascript:;" url="<?=url('changepwd')?>" data_id="000" target="iframe_main">修改密码</a></dd>
                        <dd><?= $this->anchor('logout', '[退出]') ?></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
    <div class="layui-side leftpanel">
        <?
        $num = 0;
        foreach ($menu as $i => $m)  :
            $num++;
            ?>
            <div class="menu <? if ($num > 1) {echo 'hide'; } ?>">
                <h4><i class="layui-icon">&#xe63c;</i>
                    <?= $menu[$i]['name'] ?></h4>
                <ul class="layui-nav layui-nav-tree layui-inline">
                    <?php
                    //显示左侧二级菜单
                    if (isset($m['son']) && is_array($m['son'])) {
                        foreach ($m['son'] as $li) {
                            ?>
                            <li class="layui-nav-item li_item" style="cursor:pointer"><a url="<?= url($li['url']) ?>" data_id="<?=$li['id']?>" target="iframe_main"><?= $li['name'] ?></a></li>
                            <?
                        }
                    }
                    ?>
                </ul>
            </div>
        <?  endforeach;?>
    </div>
    <div class="layui-body" style="bottom: 0px; padding-left: 8px;">
        <div class="layui-tab layui-tab-card larry-tab-box" id="main-tab" lay-filter="x-tab" lay-allowclose="true">
            <ul class="layui-tab-title">
                <li class="layui-this" lay-id="0">
                    管理首页
                    <i class="layui-icon layui-unselect layui-tab-close"></i>
                </li>
            </ul>
            <div class="layui-tab-content" >
                <div class="layui-tab-item layui-show">
                    <iframe frameborder="0" class="x-iframe" width="100%" src="<?=url('main')?>"></iframe>
                </div>
            </div>
        </div>
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