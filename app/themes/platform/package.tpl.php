<?php require 'header.php';?>
<fieldset class="layui-elem-field layui-field-title">
    <legend>自购平台</legend>
</fieldset>
<div class="package_box">
    <h3>产品列表</h3>
    <?  foreach($packages as $package) :  ?>
        <div class="package-li">
            <div class="package-name"><label><input type="checkbox" id="chkbox_<?=$package->id?>" value="<?=$package->id?>"><?=$package->name?></label></div>
            <div class="package-content clearFix" style="width: 500px; border: 1px solid #ccc">
                <div style="float: left;">
                    <img src="<?=$package->picture?>" width="150">
                </div>
                <table class="table-package" width="350">
                    <tr><td>名称：</td><td><?=$package->name?></td></tr>
                    <tr><td>价格：</td><td><?=$package->money?></td></tr>
                    <tr><td>折扣：</td><td><?=math($package->discount,100,'*',2)?> %(价格乘以百分比)</td></tr>
                    <tr><td>规格：</td><td><?=$package->title?></td></tr>
                    <tr><td>说明：</td><td><?=nl2br($package->remark)?></td></tr>
                </table>
            </div>
        </div>
    <? endforeach; ?>
</div>

<div class="table_order">
    <h3>订购信息</h3>
    <form method="post">
        <div style="border: 1px solid #ccc;">
            <div style="min-height: 100px;">
                <table id="table_order" width="100%">
                    <tr style="background-color: #efefef; line-height: 25px;"><td align="center">序号</td><td>商品名称</td><td>商品价格</td><td>商品折扣</td><td>折后价</td><td>数量</td><td>操作</td></tr>
                </table>
            </div>
            <div class="table_order_total">
                共(<span id="span_sum">0</span>)种产品,  购物总金额：<span id="span_total">0</span>
            </div>
        </div>
        <br><br>
        支付密码：<input  name="zf_password" type="password"/><br><br>
        <input type="submit" value="确认购买">
    </form>
</div>
<?php require 'footer.php';?>


