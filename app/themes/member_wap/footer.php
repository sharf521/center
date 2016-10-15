<?php
if(session('msg'))
{
    ?>
    <script>
        layer.open({
            content: '<?=session('msg')?>'
            ,skin: 'msg'
            //,time: 5
        });
    </script>
    <?
}
if(session('error'))
{
    ?>
    <script>
        layer.open({
            content: '<?=session('error')?>'
            ,skin: 'msg'
            ,time: 200 //200秒后自动关闭
        });
    </script>
    <?
}
?>
</body>
</html>