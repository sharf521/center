<?php require 'header.php';?>
<fieldset class="layui-elem-field layui-field-title">
    <legend>我的订单</legend>
</fieldset>
<? foreach($result['list'] as $row) : ?>
    <dl class="orderbox">
        <dt>
            <b><?= $row->created_at ?></b>
            订单号：<?= $row->order_sn ?>
        </dt>
        <dd>
            <table width="100%">
                <?
                $oGoods=$row->OrderGoods();
                foreach ($oGoods as $i=>$og) : ?>
                    <tr>
                        <td>
                            <img class="goodsImg" src="<?=$og->goods_image?>" width="100">
                            <div class="goodsDetail">
                                <div class="name">
                                    <?=$og->goods_name?>&nbsp;编号：<?= $og->goods_id ?><br>
                                    <?=$og->goods_title?>
                                </div>
                                <div class="price">￥<?= $og->price ?></div>
                                <div class="discount">折扣：<?=$og->discount*100?> %</div>
                                <div class="quantity"><?= $og->quantity ?></div>
                            </div>
                        </td>
                        <? if($i==0) : ?>
                            <td rowspan="<?=count($oGoods)?>" valign="top">
                                收件人：<?=$row->contacts?><br>
                                电话：<?=$row->phone?><br>
                                地址：<?=$row->province?>-<?=$row->city?>-<?=$row->area?>:<?=$row->address?><br>
                                邮编：<?=$row->zipcode?>
                            </td>
                            <td rowspan="<?=count($oGoods)?>" valign="top" align="center">￥<?= $row->order_money ?></td>
                        <? endif;?>
                    </tr>
                <? endforeach;?>
            </table>
        </dd>
        <dd class="shipping">
            物流公司：<?=$row->shipping_name?>
            物流单号：<?=$row->shipping_no?>
            物流费用：<?=$row->shipping_fee?>
        </dd>
    </dl>
<? endforeach;?>
<? if(empty($result['total'])){echo "无记录！";}else{echo $result['page'];}?>
<?php require 'footer.php';?>