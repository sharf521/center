<? if($_GET['from']!='hide') : ?>
<div class="warpleft">
    <h3>我的信息</h3>
    <ul>
        <li><a href="<?=url('user/userInfo')?>"  <? if($this->func=='userInfo'){echo 'class="whover"';}?>>个人信息</a></li>
        <li><a href="<?=url('user/bank')?>" <? if($this->func=='bank'){echo 'class="whover"';}?>>银行帐号</a></li>
        <li><a href="<?=url('user/realName')?>" <? if($this->func=='realName'){echo 'class="whover"';}?>>实名认证</a></li>
        <li><a href="<?=url('partner')?>" <? if($this->control=='partner'){echo 'class="whover"';}?>>申请合伙人</a></li>
    </ul>
    <h3>我的资金</h3>
    <ul>
        <li><a href="<?=url('account/recharge')?>" <? if($this->func=='recharge' || $this->func=='rechargeLog'){echo 'class="whover"';}?>>我要充值</a></li>
        <li><a href="<?=url('account/cash')?>" <? if($this->func=='cash' || $this->func=='cashLog'){echo 'class="whover"';}?>>申请提现</a></li>
        <li><a href="<?=url('account/log')?>" <? if($this->func=='log'){echo 'class="whover"';}?>>资金记录</a></li>
        <li><a href="<?=url('account/convert')?>" <? if($this->func=='convert'){echo 'class="whover"';}?>>积分兑换现金</a></li>
        <li><a href="<?=url('account/playToUser')?>" <? if($this->func=='playToUser'){echo 'class="whover"';}?>>站内转账</a></li>
    </ul>
    <h3>密码管理</h3>
    <ul>
        <li><a href="<?=url('password/changePwd')?>" <? if($this->func=='changePwd'){echo 'class="whover"';}?>>修改密码</a></li>
        <li><a href="<?=url('password/changePayPwd')?>" <? if($this->func=='changePayPwd'){echo 'class="whover"';}?>>修改支付密码</a></li>
        <li class="active"><a href="<?=url('password/getPayPwd')?>" <? if($this->func=='getPayPwd'){echo 'class="whover"';}?>>找回支付密码</a></li>
    </ul>
</div>
<? endif;?>