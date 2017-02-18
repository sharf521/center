<script charset="utf-8" src="/plugin/layer.mobile/layer.js"></script>
<script>
    window.onload=function(){
        <?php if (session('msg')){?>
        layer.open({
            content: '<?=session('msg')?>'
            ,skin: 'msg'
            //,time: 5
        });
        <? }
        if (session('error')){?>
        layer.open({
            content: '<?=session('error')?>'
            ,skin: 'msg'
            ,time: 200 //200秒后自动关闭
        });
        <?php } ?>
    }
</script>
</body>
</html>