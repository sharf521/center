<?php require 'header.php';?>
<?php if($this->func=='index'): ?>
    <div class="mode">
        <p>可用资金</p>
        <h3><?=(float)$account->funds_available?></h3>
        <a href="bill_list.html" class="link_bill">查看记录</a>
    </div>
    <div class="lump">
        <ul>
            <li>可用资金<br/><span><?=(float)$account->funds_available?></span></li>
            <li>冻结资金<br/><span><?=(float)$account->funds_freeze?></span></li>


            <li>可用积分<br/><span><?=(float)$account->integral_available?></span></li>
            <li>冻结积分<br/><span><?=(float)$account->integral_freeze?></span></li>

            <li>周转金<br/><span><?=(float)$account->turnover_available?></span></li>
            <li>周转金额度<br/><span><?=(float)$account->turnover_credit?></span></li>

            <li>保证金<br/><span><?=(float)$account->security_deposit?></span></li>
            <li></li>
        </ul>
        <p class="clear"></p>
    </div>
    <br><br><br><br><br><br><br><br>
    <div class="mode_foot">
        <ul>
            <li><a href="<?=url('account/recharge')?>">充值</a></li>
            <li><a href="<?=url('account/cash')?>">提现</a></li>
        </ul>
    </div>
<?php endif;?>
<?php require 'footer.php';?>
