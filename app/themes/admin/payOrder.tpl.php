<?php require 'header.php'; ?>
<? if ($this->func == 'index') : ?>
    <div class="main_title">
        <span>管理</span>列表
    </div>
    <div class="search">
        <form  method="get">
            应用：<select name="app_id">
                <option value="">请选择</option>
                <? foreach($apps as $app) :?>
                <option value="<?=$app->id?>" <? if($_GET['app_id']==$app->id){echo 'selected';}?>><?=$app->name?></option>
                <? endforeach;?>
            </select>
            支付单号：<input type="text" size="15" name="pay_no" value="<?=$_GET['pay_no']?>">
            商户订单号：<input type="text" size="15" name="app_order_no" value="<?=$_GET['app_order_no']?>">
            Label：<input type="text" size="8" name="label" value="<?=$_GET['label']?>">
            时间：
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
                    <th>ID</th>
                    <th>支付单号</th>
                    <th>商户</th>
                    <th>商户订单号</th>
                    <th>支付说明</th>
                    <th>类型</th>
                    <th>label</th>
                    <th>变动</th>
                    <th >备注</th>
                    <th>时间</th>
                    <th>状态</th>
                </tr>
                <?
                $status_arr=array('','己支付','己退款');
                foreach($result['list'] as $row){
                    ?>
                    <tr>
                        <td><?=$row->id?></td>
                        <td><?=$row->pay_no?></td>
                        <td><?=$row->App()->name?></td>
                        <td><?=$row->app_order_no?></td>
                        <td class="fl"><?=$row->body?></td>
                        <td><?=$row->getLinkPageName('account_type',$row->type);?></td>
                        <td class="fl"><?=$row->label?></td>
                        <td class="fl" style="max-width: 300px; overflow: hidden"><?=$row->data?></td>
                        <td class="fl"><?=nl2br($row->remark)?></td>
                        <td><?=$row->created_at?></td>
                        <td><?=$status_arr[$row->status]?></td>
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