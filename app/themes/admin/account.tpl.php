<?php require 'header.php'; ?>
<? if ($this->func == 'log') : ?>
    <div class="main_title">
        <span>管理</span>列表
    </div>
    <div class="search">
        <form  method="get">
            用户ID：<input type="text" size="5" name="user_id" value="<?=$_GET['user_id']?>">
            Label：<input type="text" size="8" name="label" value="<?=$_GET['label']?>">
            记录时间：
            <input  name="starttime" type="text" value="<?=$_GET['starttime']?>" onClick="javascript:WdatePicker();" class="Wdate">
            到
            <input  name="endtime" type="text" value="<?=$_GET['endtime']?>" onClick="javascript:WdatePicker();" class="Wdate">
            <input  type="submit" value="查询" />
        </form>
    </div>
    <div class="main_content">
        <? if(!empty($result['total'])){?>
            <table class="table">
                <tr>
                    <th>用户/ID</th>
                    <th>时间</th>
                    <th>类型</th>
                    <th>label</th>
                    <th>变动</th>
                    <th>备注</th>
                    <th>可用资金</th>
                    <th>冻结资金</th>
                    <th>可用积分</th>
                    <th>冻结积分</th>
                    <th>保证金</th>
                    <th>可用周转金</th>
                    <th>周转金额度</th>

                </tr>
                <? foreach($result['list'] as $row){
                    ?>
                    <tr>
                        <td><?=$row->user()->username?>/<?=$row->user()->id?></td>
                        <td><?=$row->created_at?></td>
                        <td><?=$row->getLinkPageName('account_type',$row->type);?></td>
                        <td class="fl"><?=$row->label?></td>
                        <td class="fl"><?=$row->change?></td>
                        <td class="fl"><?=nl2br($row->remark)?></td>
                        <td class="fl">￥<?=(float)$row->funds_available_now?></td>
                        <td class="fl">￥<?=(float)$row->funds_freeze_now?></td>
                        <td class="fl">￥<?=(float)$row->integral_available_now?></td>
                        <td class="fl">￥<?=(float)$row->integral_freeze_now?></td>
                        <td class="fl">￥<?=(float)$row->turnover_available?></td>
                        <td class="fl">￥<?=(float)$row->security_deposit_now?></td>
                        <td class="fl">￥<?=(float)$row->turnover_credit_now?></td>
                    </tr>
                <? }?>
            </table>
        <? }else{?>
            <div class="alert-warning" role="alert">无记录！</div>
        <? }?>
        <?=$result['page'];?>
    </div>
<? endif; ?>
<?php require 'footer.php'; ?>