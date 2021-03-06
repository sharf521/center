<?php
require 'header_v2.php';
?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('')?>"><i class="iconfont">&#xe603;</i>返回</a>
        <a class="m_header_r"></a>
        <h1>站内转账</h1>
    </div>
    <div class="margin_header"></div>
    <form method="post" name="form1">
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">对方用户名</label></div>
                <div class="weui-cell__bd">
                    <input type="text" name="to_username" required  placeholder="输入对方手机号"  class="weui-input" autocomplete="off"/>
                </div>
            </div>
        </div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd"><label class="weui-label">类型</label></div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="type">
                        <option value="1" selected>转资金</option>
                        <option value="2">转积分</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">转出数量</label></div>
                <div class="weui-cell__bd">
                    <input type="text" name="total" required placeholder="输入数量" onkeyup="value=value.replace(/[^0-9.]/g,'')"  class="weui-input" autocomplete="off"/>
                </div>
            </div>
        </div>
        <div class="weui-cells__tips">可用积分：<span id="span_integral"><?=$account->integral_available?></span></div>
        <div class="weui-cells__tips">可用金额：¥<span id="span_funds"><?=$account->funds_available?></span></div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">支付密码</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" required type="password" name="zf_password" placeholder="支付密码" />
                </div>
            </div>
        </div>

        <div class="weui-btn-area">
            <input class="weui-btn weui-btn_primary" type="submit" value="立即转账">
        </div>
    </form>
    <ul class="prompt">
        <h4>提示：</h4>
        <li>转资金手续费：<?=$transferFundsRateText?></li>
        <li>转积分手续费：<?=$transferIntegralRateText?></li>
    </ul>
<?php require 'footer_v2.php'; ?>