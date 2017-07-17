<script>
    window.onload=function(){
        <?php if (session('msg')){?>
        layer.open({
            content: '<?=session('msg')?>'
            ,skin: 'msg'
            ,time: 2
        });
        <? }
        if (session('error')){?>
        layer.open({
            content: '<?=session('error')?>'
            ,skin: 'msg'
            ,time: 10 //200秒后自动关闭
        });
        <?php } ?>
    }
</script>
</body>
</html>