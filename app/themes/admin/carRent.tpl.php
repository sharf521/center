<?php
require 'header.php';
if($this->func=='index') :    ?>

    <div class="main_title">
        <span>租车</span>列表 <a href="<?= url('carRent/add') ?>" class="but1">添加</a>
    </div>
    <form method="get">
        <div class="search">
            用户ID：<input type="text" name="user_id" value="<?=$_GET['user_id']?>"/>
            联系人：<input type="text" name="contacts" value="<?=$_GET['contacts']?>"/>
            申请时间：<input type="text" name="starttime" value="<?=$_GET['starttime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            到<input type="text" name="endtime" value="<?=$_GET['endtime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <table class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th>USER_ID</th>
            <th>联系人</th>
            <th>联系电话</th>
            <th>联系地址</th>
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
        foreach($result['list'] as $row)
        {
            ?>
            <tr>
                <td><?=$row->user_id?></td>
                <td><?=$row->contacts?></td>
                <td><?=$row->tel?></td>
                <td><?=$row->area?>-<?=$row->address?></td>
                <td><?=$row->car_name?></td>
                <td><?=$row->first_payment_scale*100?>%（￥<?=$row->first_payment_money?>）</td>
                <td><?=$row->last_payment_scale*100?>%（￥<?=$row->last_payment_money?>）</td>
                <td><?=$row->time_limit?>月</td>
                <td>￥<?=$row->month_payment_money?></td>
                <td>每月<?=$row->month_payment_day?>号</td>
                <td><?=$row->created_at?></td>
                <td>
                    <?
                    if ($row->status == 0) {
                        ?>
                        <a href="<?= url("carRent/edit/?id={$row->id}") ?>" class="layui-btn layui-btn-mini">编辑</a> |

                        <?
                        $txt='生成还款列表，将不可编辑';
                    }else{
                        $txt='还款列表';
                    }
                    ?>
                    <a href="<?= url("carRent/repayment/?id={$row->id}") ?>" class="layui-btn layui-btn-mini"><?=$txt?></a>
                </td>
            </tr>
        <? }?>
    </table>
    <? if (empty($result['total'])) {
    echo "无记录！";
} else {
    echo $result['page'];
} ?>
    <?
elseif($this->func=='repayment') :
    $arr_status=array('','待付款','己还款');
    ?>
    <div class="main_title">
        <span>租车</span> <a href="<?= url('carRent') ?>" class="but1">列表</a>
    </div>
    <blockquote class="layui-elem-quote">联系人：<?=$carRent->contacts?><br>车款：<?=$carRent->car_name?></blockquote>
    <table class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th>用户ID</th>
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
        <? foreach ($repayments as $repayment) : ?>
            <tr>
                <td><?=$repayment->user_id?></td>
                <td><?=$repayment->title?></td>
                <td>￥<?=$repayment->money?></td>
                <td>￥<?=$repayment->money_yes?></td>
                <td><?=substr($repayment->	repayment_time,0,10)?></td>
                <td><?=$repayment->	repayment_yestime?></td>
                <td><?=$repayment->last_days?></td>
                <td>￥<?=$repayment->last_interest?></td>
                <td><?=$arr_status[$repayment->status]?></td>
                <td>
                    <? if($repayment->status==1)?>
                </td>
            </tr>
        <? endforeach;?>
        </tbody>
    </table>
<?
endif;
require 'footer.php';?>