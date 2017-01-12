<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <?php if($this->func=='index') : ?>
            <br>
            <span class="layui-breadcrumb">
              <a href="/">个人中心</a>
              <a><cite>我的购车</cite></a>
            </span>
            <hr>
            <table class="layui-table" lay-skin="line">
                <thead>
                <tr>
                    <th>所选车款</th>
                    <th>首付</th>
                    <th>尾付</th>
                    <th>租期</th>
                    <th>月付</th>
                    <th>付款时间</th>
                    <th>添加时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <?
                foreach($carRents as $row)
                {
                    ?>
                    <tr>
                        <td><?=$row->car_name?></td>
                        <td>￥<?=$row->first_payment_money?></td>
                        <td>￥<?=$row->last_payment_money?></td>
                        <td><?=$row->time_limit?>月</td>
                        <td>￥<?=$row->month_payment_money?></td>
                        <td>每月<?=$row->month_payment_day?>号</td>
                        <td><?=$row->created_at?></td>
                        <td>
                            <?
                            if ($row->status == 1) {
                                ?>
                                <a href="<?= url("carRent/repayment/?id={$row->id}") ?>" class="layui-btn layui-btn-mini">还款</a>
                                <?
                            } else {
                                ;
                            }
                            ?>
                        </td>
                    </tr>
                <? }?>
            </table>
        <? elseif ($this->func=='repayment') : ?>
            <div class="box">
                <br>
            <span class="layui-breadcrumb">
              <a href="/">个人中心</a>
                <a href="<?=url('carRent')?>">我的购车</a>
              <a><cite>还款列表</cite></a>
            </span><hr>
                <blockquote class="layui-elem-quote">联系人：<?=$carRent->contacts?><br>车款：<?=$carRent->car_name?></blockquote>
                <table class="layui-table" lay-skin="line">
                    <thead>
                    <tr>
                        <th>说明</th>
                        <th>应还</th>
                        <th>己还</th>
                        <th>应还日期</th>
                        <th>还款时间</th>
                        <th>逾期天数</th>
                        <th>逾期利息</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?
                    $arr_status=array('','待付款','己还款');
                    foreach ($repayments as $repayment) : ?>
                        <tr>
                        <td><?=$repayment->title?></td>
                        <td>￥<?=$repayment->money?></td>
                        <td>￥<?=$repayment->money_yes?></td>
                        <td><?=substr($repayment->	repayment_time,0,10)?></td>
                        <td><?=$repayment->	repayment_yestime?></td>
                        <td><?=$repayment->last_days?></td>
                        <td>￥<?=$repayment->last_interest?></td>
                        <td><?=$arr_status[$repayment->status]?></td>
                        <td>
                            <? if($repayment->status==1){
                                ?>
                                <a href="<?=url("carRent/pay/?repay_id={$repayment->id}")?>" class="layui-btn layui-btn-mini">还款</a>
                            <?php
                            }?>
                            </td>
                            </tr>
                        <? endforeach;?>
                    </tbody>
                </table>
            </div>
        <? elseif ($this->func=='pay') : ?>
        <br>
            <span class="layui-breadcrumb">
              <a href="/">个人中心</a>
                <a href="<?=url('carRent')?>">我的购车</a>
                <a href="<?=url("carRent/repayment/?id={$carRent->id}")?>">还款列表</a>
              <a><cite>还款</cite></a>
            </span><hr>
            <blockquote class="layui-elem-quote">联系人：<?=$carRent->contacts?><br>
                车款：<?=$carRent->car_name?><br>
                说明：<?=$repayment->title?><br>
                应付日期：<?=substr($repayment->repayment_time,0,10)?><br>
                应付金额：<?='￥'.$repayment->money?>
            </blockquote>

            <br><br>
            <form method="post" class="layui-form">
                <div class="layui-field-box">
                    <div class="layui-form-item">
                        <label class="layui-form-label">使用积分</label>
                        <div class="layui-input-inline">
                            <input type="text" id="integral" name="integral" value="0" required placeholder="" onkeyup="value=value.replace(/[^0-9.]/g,'')"  class="layui-input" autocomplete="off"/>
                        </div>
                        <div class="layui-form-mid layui-word-aux">可用积分：<span id="span_integral"><?=$account->integral_available?></span></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">支付密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="zf_password" required placeholder="请填写支付密码" class="layui-input" autocomplete="off"/>
                        </div>
                        <div class="layui-form-mid layui-word-aux">可用金额：￥<span id="span_funds"><?=$account->funds_available?></span></div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">实际支付：￥<span id="money_yes"><?=$repayment->money?></span>
                        <button class="layui-btn" lay-submit="" lay-filter="*">立即支付</button>
                    </div>
                </div>
            </form>
            <script src="/plugin/js/math.js"></script>
            <script>
                $(function () {
                    var lv='<?=$convert_rate?>';
                    var price_true = '<?=$repayment->money?>';
                    $("#integral").bind('input propertychange',function(){
                        if(Number($(this).val())>Number($('#span_integral').html())){
                            $(this).val($('#span_integral').html());
                        }
                        var _m=Math.div(Number($("#integral").val()),lv);
                        var money=Math.sub(price_true,Math.moneyRound(_m,2));
                        $('#money_yes').html(money);
                    });
                });
            </script>
        <?php endif;?>
    </div>
</div>
<?php require 'footer.php';?>
