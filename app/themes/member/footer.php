<? if($_GET['from']!='hide') : ?>
<div class="footer">Copyright 2012-2020 Inc.,All rights reserved.<br></div>
<? endif;?>

<script>
    window.onload=function(){
        <?php if (session('msg')){?>
        layer.msg('<?= addslashes(session('msg')) ?>', {
            offset: '200px',
            icon: 1,
            time: 1000
        });
        <? }
        if (session('error')){?>
        layer.msg('<?= addslashes(session('error')) ?>', {
            offset: '200px',
            icon: 2,
            time: 2000
        });
        <?php } ?>
    }
</script>
</body>
</html>