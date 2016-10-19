<div class="footer">Copyright 2012-2020 Inc.,All rights reserved. 豫ICP备11027012号<br></div>
<span class="layui-breadcrumb" lay-separator="-">
  <a href="">首页</a>
  <a href="">国际新闻</a>
  <a href="">亚太地区</a>
  <a><cite>正文</cite></a>
</span>
<script>
    <?php if (session('msg')){?>
    layer.msg('<?= session('msg') ?>', {
        offset: '200px',
        icon: 1,
        time: 1000
    });
    <? }
    if (session('error')){?>
    layer.msg('<?= session('error') ?>', {
        offset: '200px',
        icon: 2,
        time: 20000
    });
    <?php } ?>
//    $(document).ready(function () {
//        $("form").submit(function (e) {
//            $(":submit", this).css("background-color", "#cccccc");
//            $("input[type='submit']", this).attr("disabled", true);
//            return true;
//        });
//    });
    layui.util.fixbar();
</script>
</body>
</html>