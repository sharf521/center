<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>站内转账</legend>
                </fieldset>
                <form method="post" onSubmit="return setdisabled();">
                    <table class="table_from">
                        <tr><td>用户名：</td><td><?=$this->username?></td></tr>
                        <tr><td>可用资金：</td><td><?='￥'.$account->funds_available?></td></tr>
                        <tr><td>可用积分：</td><td><?=$account->integral_available?></td></tr>
                        <tr><td>类型：</td><td>
                                <label><input type="radio" name="type" value="1" checked>转资金</label>
                                <label><input type="radio" name="type" value="2">转积分</label></td></tr>
                        <tr><td>对方用户名：</td><td><input  name="to_username" type="text"/></td></tr>
                        <tr><td>转出数量：</td><td><input  name="total" type="text" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/></td></tr>
                        <tr><td>备注：</td><td><textarea cols="50" rows="2" name="remark"></textarea></td></tr>
                        <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
                        <tr><td></td><td><input type="submit" value="立即转账"/></td></tr>
                    </table>
                </form>
                <ul class="prompt">
                    <h4>提示：</h4>
                    <li>转资金手续费：<?=$transferFundsRateText?></li>
                    <li>转积分手续费：<?=$transferIntegralRateText?></li>
                </ul>
            </div>
    </div>
</div>
<?php require 'footer.php';?>
