<?php
if(session('msg'))
{
    ?>
    <script>
        layer.msg('<?=addslashes(session('msg'))?>', {
            offset: '200px',
            icon: 1,
            time: 1000
        });
    </script>
    <?
}
if(session('error'))
{
    ?>
    <script>
        layer.msg('<?=addslashes(session('error'))?>', {
            offset: '200px',
            icon: 2,
            time: 10000
        });
    </script>
    <?
}
?>
</body>
</html>