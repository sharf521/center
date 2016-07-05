<?php
if(session('msg'))
{
    ?>
    <script>
        layer.msg('<?=session('msg')?>', {
            offset: '200px',
            icon: 1,
            time: 1000
        });
    </script>
    <?
}
?>
</body>
</html>