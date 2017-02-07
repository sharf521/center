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
</head>
<body>
<div class="layui-layout layui-layout-admin">
    <div class="layui-header " style="height: 55px;    border-bottom: 5px solid #5FB878;    background-color: #393D49;    color: #fff;">
        <div class="layui-main">
aassds

        </div>
    </div>
    <div class="layui-side layui-bg-black x-side" style="    top: 55px;    width: 200px;    overflow-x: hidden;">
       <ul>
           <li class="layui-nav-item">
               <a href="javascript:;" data-url="http://www.layui.com/">
                   <i class="iconfont icon-youqinglianjie" data-icon="icon-youqinglianjie"></i>
                   <span>友情链接</span>
               </a>
           </li>
           <li class="layui-nav-item">
               <a href="javascript:;" data-url="http://www.layui.com/">
                   <i class="iconfont icon-youqinglianjie" data-icon="icon-youqinglianjie"></i>
                   <span>友情链接</span>
               </a>
           </li>
           <li class="layui-nav-item">
               <a href="javascript:;" data-url="http://www.layui.com/">
                   <i class="iconfont icon-youqinglianjie" data-icon="icon-youqinglianjie"></i>
                   <span>友情链接</span>
               </a>
           </li>
       </ul>

    </div>
    <div class="layui-body">
            <div class="layui-tab layui-tab-card larry-tab-box" id="larry-tab" lay-filter="demo" lay-allowclose="true">

            <ul class="layui-tab-title">
                <li class="layui-this">
                    我的桌面
                    <i class="layui-icon layui-unselect layui-tab-close"></i>
                </li>
            </ul>
            <div class="layui-tab-content" >
                <div class="layui-tab-item layui-show">
                    <iframe frameborder="0" src="/psadmin/account/log" class="x-iframe" width="100%" height="100%"></iframe>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    $(window).on("resize", function() {
        var $content = $("#larry-tab .layui-tab-content");
        $content.height($(this).height() - 163);
        $content.find("iframe").each(function() {
            $(this).height($content.height())
        });
    }).resize();
</script>


</body>
</html>