<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <div class="jiben">
            <div class="jbtx">
                <div class="touxiang">
                    <img src="<?=$this->user->headimgurl;?>">                    
                </div>
                <div class="toutext">
                    <h2><?=$this->username?></h2>
                    <p><?=$this->user->name?></p>
                </div>
            </div>

            <div class="zhjin">
                <div class="zhjinle">
                    <p>可用资金：<span><?=(float)$account['funds_available']?></span></p>
                    <p>可用积分：<span><?=(float)$account['integral_available']?></span></p>
                    <p>周转金：<span> <?=(float)$account['turnover_available']?></span></p>
                    <p>保证金：<span> <?=(float)$account['security_deposit']?></span></p>
                    <p>计划奖励：<span> <?=$expectedIntegralReward?> 积分</span></p>
                </div>
                <div class="zhjinle">
                    <p>冻结资金：<span> <?=(float)$account['funds_freeze']?></span></p>
                    <p>冻结积分：<span> <?=(float)$account['integral_freeze']?></span></p>
                    <p>周转金额度：<span> <?=(float)$account['turnover_credit']?></span></p>
                    <p></p>
                    <p>己奖励：<span> <?=$alreadyIntegralReward?> 积分</span></p>
                </div>
                <div class="zhjinri">
                    <p><a href="<?=url('account/recharge')?>" class="chongzhi">充值</a></p>
                    <p><a href="<?=url('account/cash')?>" class="tixian">提现</a></p>
                </div>

            </div>
        </div>
        <div class="layui-clear">
            <a href="<?= url('goApp/10'); ?>" target="_blank" class="layui-btn">进入我的商城</a>
            <!--        <a href="<?/*= url('goApp/5'); */?>" target="_blank" class="layui-btn">进入我的商城（旧）</a>
            <a href="<?= url('goApp/8'); ?>" target="_blank" class="layui-btn">进入我的云购</a>-->

            <br><br>
            <?php   if(! empty($carRents)) : ?>
                <!--<a href="<?/*= url('carRent'); */?>"class="layui-btn">我的购车</a>-->
            <?php endif;?>
        </div>


        <fieldset class="layui-elem-field layui-field-title">
            <legend>计划奖励(最近)</legend>
        </fieldset>
        <? if(empty($rebateList)) : ?>
            <div class="alert-warning" role="alert">无记录！</div>
        <? endif;?>
        <table class="layui-table">
            <thead>
            <tr>
                <th>计划奖励 </th>
                <th>己奖励</th>
                <th>添加时间</th>
                <th>来源</th>
                <th>说明</th>
            </tr>
            </thead>
            <tbody>
            <? foreach ($rebateList as $rebate) : ?>
            <tr>
                <td><?= (float)$rebate->money ?></td>
                <td><?= (float)$rebate->money_rebate ?></td>
                <td><?= $rebate->addtime ?></td>
                <td><? if($rebate->app_id!=0){echo $rebate->App()->name;}else{echo '帐户中心';}?></td>
                <td><?=$rebate->remark?></td>
            </tr>
            <? endforeach;?>
            </tbody>
        </table>

    </div>
</div>

<?php require 'footer.php';?>
