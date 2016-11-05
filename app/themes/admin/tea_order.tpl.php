<?php require 'header.php'; ?>
<? if ($this->func == 'order') : ?>
    <div class="main_title">
        <span>管理</span>列表
    </div>
    <div class="main_content">
        <? foreach($result['list'] as $row) : ?>
            <div class="clearFix" style="border-bottom: 2px solid #ccc; margin-bottom: 25px;">
                订单号：<?= $row->order_sn ?>  用户Id：<?= $row->user_id ?> 用户名：<?= $row->username ?> 价格：<?= $row->order_money ?> 下单时间：<?= $row->created_at ?><br>
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
                物流公司：<?=$row->shipping_name?>  物流单号：<?=$row->shipping_no?> 物流费用：<?=$row->shipping_fee?>

                <a href="<?=url('tea/order_shipping/?id='.$row->id) ?>" class="but1">编辑物流</a><br><br>
            </div>
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