<script charset="utf-8" src="/plugin/layer.mobile/layer.js"></script>
<?php
if(session('msg'))
{
    ?>
    <script>
        layer.open({
            content: '<?=session('msg')?>',
            style: 'background-color:#ffffff; border:none;',
            time: 1
        });
    </script>
    <?
}
if(session('error'))
{
    ?>
    <script>
        layer.open({
            content: '<?=session('error')?>',
            style: 'background-color:#ffffff;  border:none;',
            time: 2
        });
    </script>
    <?
}
?>
</body>
</html>