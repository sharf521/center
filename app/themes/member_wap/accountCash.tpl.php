<?php require 'header.php';?>
<?php if($this->func=='cash') : ?>
    <div class="m_regtilinde">我要提现  <a href="<?=url('account/cashLog')?>">提现记录</a></div>
    <div class="show_box">
        <? if($bank->card_no ==""){?>
            <div class="alert-warning" role="alert">您还没有填写银行账户，请先填写<?=$this->anchor('user/bank','>>银行账户>>');?></div>
        <? }else{?>
            <form method="post" onSubmit="return setdisabled();">
                <table class="table_from">
                    <tr><td>用户名：</td><td><?=$this->username?></td></tr>
                    <tr><td>可提现金额：</td><td><?='￥'.$account->funds_available?></td></tr>
                    <tr><td>姓名：</td><td><?=$this->user->name?></td></tr>
                    <tr><td>提现银行：</td><td><?=$bank->bank?></td></tr>
                    <tr><td>开户支行：</td><td><?=$bank->branch?></td></tr>
                    <tr><td>银行账号：</td><td><?=$bank->card_no?></td></tr>
                    <tr><td>提现金额：</td><td><input  name="total" style="width: 90%" type="text" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/> 元</td></tr>
                    <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
                    <tr><td colspan="2"><input type="submit" class="submit" value="提交" /></td></tr>
                </table>
            </form>
            <ul class="cash_prompt">
                <h4>温馨提示：</h4>
                <li>1. 单笔提现金额最低50.00元，最高50000.00元。</li>
                <li>2. 单笔提现费率<?=math($cash_rate,100,'*',2)?>%，单笔提现手续费最低5.00元。</li>
                <li>3. 每个工作日的下午5点之前提交的提现申请，T+1个工作日到账，每个工作日的下午5点之后提交的提现申请，T+2个工作日到账。</li>
            </ul>
        <? }?>
    </div>
<? endif;?>
<?php require 'footer.php';?>