<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <?php if ($this->func=='log'): ?>
            <div class="box">
                <h3>资金记录：</h3>
                <div class="search">
                    <form  method="get">
                        记录时间：
                        <input  name="starttime" type="text" value="<?=$_GET['starttime']?>" onClick="javascript:WdatePicker();" class="Wdate">
                        到
                        <input  name="endtime" type="text" value="<?=$_GET['endtime']?>" onClick="javascript:WdatePicker();" class="Wdate">
                        <input  type="submit" value="查询" />
                    </form>
                </div>
                <? if(!empty($result['total'])){?>
                    <table class="table">
                        <tr>
                            <th>时间</th>
                            <th>类型</th>
                            <th>变动</th>
                            <th>当前</th>
                            <th>备注</th>
                        </tr>
                        <? foreach($result['list'] as $row){
                            ?>
                            <tr>
                                <td><?=$row->created_at?></td>
                                <td><?=$row->getLinkPageName('account_type',$row->type);?></td>
                                <td class="fl"><?=$row->change?></td>
                                <td class="fl"><?=$row->now?></td>
                                <td class="fl"><?=nl2br($row->remark)?></td>
                            </tr>
                        <? }?>
                    </table>
                <? }else{?>
                    <div class="alert-warning" role="alert">无记录！</div>
                <? }?>
                <?=$result['page'];?>
            </div>
        <?php endif;?>
    </div>
</div>
<?php require 'footer.php';?>
