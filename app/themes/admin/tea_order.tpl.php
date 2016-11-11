<?php require 'header.php'; ?>
<? if ($this->func == 'order') : ?>
    <div class="main_title">
        <span>管理</span>列表
    </div>
    <style type="text/css">
        .orderbox{border: 1px solid #dddddd; margin-bottom: 25px;}
        .orderbox:hover{border: 1px solid #999;}
        .orderbox dt{ line-height: 40px; background-color: #efefef; padding-left: 8px;}
        .orderbox dt b{margin-right: 30px;}
        .orderbox dd td{padding: 10px;border-collapse: collapse; border: 1px solid #efefef; line-height: 22px;}
        .orderbox .goodsImg{ margin-right: 10px;width: 100px; float: left}
        .orderbox .goodsDetail{float: left;}
        .orderbox .goodsDetail .name{float: left; width: 300px;}
        .orderbox .goodsDetail .price{float: left; width: 100px; text-align: center}
        .orderbox .goodsDetail .discount{float: left; width: 100px; text-align: center}
        .orderbox .goodsDetail .quantity{float: left; width: 50px; text-align: center}
        .orderbox .shipping{padding-left: 10px; line-height: 40px;}
    </style>
    <div class="main_content">
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
                                        用户Id：<?= $row->user_id ?><br>
                                        用户名：<?= $row->username ?></td>
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
                    <a href="<?=url('tea/order_shipping/?id='.$row->id) ?>" class="but1">编辑物流</a>
                </dd>
            </dl>
        <? endforeach;?>
        <? if(empty($result['total'])){echo "无记录！";}else{echo $result['page'];}?>
    </div>
<? elseif ($this->func == 'order_shipping') : ?>
    <div class="main_title">
        <span>管理</span> 编辑物流
        <a href="<?= url('tea/order') ?>" class="but1">返回列表</a>
    </div>
    <div class="main_content">
        <form method="post">
            <input type="hidden" name="id" value="<?= $row->id ?>"/>
            <table class="table_from">
                <tr>
                    <td>物流名称：</td>
                    <td><input type="text" name="shipping_name" value="<?=$order->shipping_name ?>"/></td>
                </tr>
                <tr>
                    <td>物流单号：</td>
                    <td><input type="text" name="shipping_no" value="<?= $order->shipping_no ?>" /></td>
                </tr>
                <tr>
                    <td>费用：</td>
                    <td><input type="text" name="shipping_fee" value="<?= $order->shipping_fee ?>"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="保存"/>
                        <input type="button" value="返回" onclick="window.history.go(-1)"/></td>
                </tr>
            </table>
        </form>
    </div>
<? endif; ?>
<?php require 'footer.php'; ?>