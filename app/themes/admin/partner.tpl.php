<?php require 'header.php'; ?>
<? if ($this->func == 'index') : ?>
    <div class="main_title">
        <span>管理</span>列表
    </div>
    <div class="search">
        <form>
            类型：<?=$type?>
            状态：<select name="status">
                <option value=""<? if($_GET['status']==""){?> selected="selected"<? }?>>请选择</option>
                <option value="1"<? if($_GET['status']=="1"){?> selected="selected"<? }?>>待审核</option>
                <option value="2"<? if($_GET['status']=="2"){?> selected="selected"<? }?>>己审核</option>
            </select>
            用户名：<input type="text" name="username" value="<?=$_GET['username']?>"/>
            时间：<input type="text" name="starttime" value="<?=$_GET['starttime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            到<input type="text" name="endtime" value="<?=$_GET['endtime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            <input type="submit" class="but2" value="查询" />
        </form>
    </div>
    <div class="main_content">
        <? if(!empty($result['total'])){?>
            <table class="layui-table">
                <tr>
                    <th>ID</th>
                    <th>用户ID</th>
                    <th>用户名</th>
                    <th>真实姓名</th>
                    <th>类型</th>
                    <th>冻结金额</th>
                    <th>冻结积分</th>
                    <th>己收金额</th>
                    <th>审请时间</th>
                    <th>审核时间</th>
                    <th>审核备注</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <? foreach($result['list'] as $row){?>
                    <tr>
                        <td><?=$row->id?></td>
                        <td><?=$row->user_id?></td>
                        <td><?=$row->user()->username?></td>
                        <td><?=$row->user()->name?></td>
                        <td><?=$row->getLinkPageName('partner_type',$row->type)?></td>
                        <td>￥<?=(float)$row->payed_funds?></td>
                        <td><?=(float)$row->payed_integral?></td>
                        <td>￥<?=(float)$row->money?></td>
                        <td><?=$row->created_at?></td>
                        <td><? if($row->verify_at!=0){echo $row->verify_at;}?></td>
                        <td class="fl"><?=nl2br($row->verify_remark)?></td>
                        <td><?=$row->getLinkPageName('check_status',$row->status)?></td>
                        <td>
                            <?
                            if($row->status=="1")
                            {
                                ?>
                                <a href="<?=url("partner/edit/?id={$row->id}&page={$_GET['page']}")?>">审核</a>
                                <?
                            }
                            ?>
                        </td>
                    </tr>
                <? }?>
            </table>
        <? }else{?>
            <div class="alert-warning" role="alert">无记录！</div>
        <? }?>
        <?=$result['page'];?>
    </div>
<? elseif ($this->func == 'edit') : ?>
    <div class="main_title">
        <span>管理</span><? if ($this->func == 'add') { ?>新增<? } else { ?>编辑<? } ?>
        <a href="<?= url('recharge') ?>" class="but1">返回列表</a>
    </div>
    <div class="main_content">
        <form method="post">
            <table class="table_from">
                <tr><td>编号：</td><td><?=$row->id?></td></tr>
                <tr><td>用户名：</td><td><?=$row->user()->username?>(<?=$row->user()->name?>)</td></tr>
                <tr><td>申请级别：</td><td><? echo $row->getLinkPageName('partner_type',$row->type)?></td></tr>
                <tr><td>己冻结：</td><td><?=(float)$row->payed_funds?>元 和 <?=(float)$row->payed_integral?> 积分</td></tr>
                <tr><td>申请时间：</td><td><?=$row->created_at?></td></tr>
                <tr><td>状态：</td><td><? echo $row->getLinkPageName('check_status',$row->status)?></td></tr>
                <tr><td>上层用户Id：</td><td><input type="text" name="p_userid" value="<?=$row->invite_uid?>"></td></tr>
                <tr><td>审核：</td><td>
                        <label><input type="radio" name="status" value="2"/>审核通过</label>
                        <label><input type="radio" name="status" value="3"/>审核不通过</label></td></tr>
                <tr><td>审核备注：</td><td><textarea name="verify_remark" cols="45" rows="5"></textarea></td></tr>
                <tr><td></td><td><input type="submit" class="but3" value="确认审核" />
                        <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
            </table>
        </form>
    </div>
<? endif; ?>
<?php require 'footer.php'; ?>