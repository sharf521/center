<script charset="utf-8" src="/plugin/layer.mobile/layer.js"></script>
<?php
if(session('msg'))
{
    ?>
    <script>
        layer.open({
            content: '<?=session('msg')?>',
            style: 'background-color:#ffffff;  border:none;font-size:1.4rem;padding: 2rem 1.5rem;line-height: 2.2rem;',
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
            style: 'background-color:#ffffff;  border:none;font-size:1.4rem;padding: 2rem 1.5rem;line-height: 2.2rem;',
            time: 20
        });
    </script>
    <?
}
?>
</body>
</html>