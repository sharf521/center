<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>资金记录11</legend>
                </fieldset>


                <form method="post" class="layui-form">
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">扣除积分</label>
                            <div class="layui-input-inline">
                                <input type="text" id="integral" name="integral" value="0" required placeholder="" onkeyup="value=value.replace(/[^0-9.]/g,'')"  class="layui-input" autocomplete="off"/>
                            </div>
                            <div class="layui-form-mid layui-word-aux">可用积分：<span id="span_integral"><?=(float)$account->integral_available?></span></div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">支付密码</label>
                            <div class="layui-input-inline">
                                <input class="layui-input" required type="password" name="zf_password" placeholder="支付密码" />
                            </div>
                            <div class="layui-form-mid layui-word-aux">可用金额：￥<span id="span_funds"><?=$account->funds_available?></span></div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">实际支付：￥<span id="money_yes"><?=$order->money?></span> &nbsp;
                            <a class="layui-btn layui-btn-mini layui-btn-normal recharge" target="_blank"
                               href="<?=url("account/recharge/?money={$order->money}")?>">我要充值</a>
                            <br><br>
                            <button class="layui-btn" lay-submit="" lay-filter="*">立即支付</button>
                            <button class="layui-btn" onclick="history.go(-1)">返回</button>
                        </div>
                    </div>
                </form>
                <script src="/plugin/js/math.js"></script>
                <script>
                    var lv='<?=$convert_rate?>';
                    var price_true='<?=$order->money?>';
                    orderPayJs();
                </script>
                
            </div>
    </div>
</div>
<?php require 'footer.php';?>
