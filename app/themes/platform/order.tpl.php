<?php require 'header.php';?>
<fieldset class="layui-elem-field layui-field-title">
    <legend>我的订单</legend>
</fieldset>
<? foreach($result['list'] as $row) : ?>
    <div class="clearFix" style="border-bottom: 2px solid #ccc; margin-bottom: 25px;">
        订单号：<?= $row->order_sn ?>  价格：<?= $row->order_money ?> 下单时间：<?= $row->created_at ?><br>
        <table class="table">
            <tr>
                <td>商品</td>
                <td>照片</td>
                <td>价格</td>
                <td>数量</td>
                <td>折扣</td>
            </tr>
            <?
            $oGoods=$row->OrderGoods();
            foreach ($oGoods as $og) : ?>
                <tr>
                    <td>（<?= $og->goods_id ?>）<?=$og->goods_name?></td>
                    <td><img src="<?=$og->goods_image?>" width="100"></td>
                    <td><?= $og->price ?></td>
                    <td><?= $og->quantity ?></td>
                    <td><?=$og->discount*100?> %</td>
                </tr>
            <? endforeach;?>
        </table>
        <table width="100%"><tr><td>收件人：<?=$row->contacts?></td><td>电话：<?=$row->phone?></td><td>地址：<?=$row->province?>-<?=$row->city?>-<?=$row->area?>:<?=$row->address?></td><td>邮编：<?=$row->zipcode?></td></tr></table>
        <div style="line-height: 30px; margin-bottom: 20px;">
            <? if($row->status==1) : ?>
                未发货
            <? else : ?>
                物流公司：<?=$row->shipping_name?>  物流单号：<?=$row->shipping_no?> 物流费用：<?=$row->shipping_fee?>
            <? endif;?>
        </div>
    </div>
<? endforeach;?>
<? if(empty($result['total'])){echo "无记录！";}else{echo $result['page'];}?>
<?php require 'footer.php';?>