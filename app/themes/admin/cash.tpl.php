<?php require 'header.php'; ?>
<? if ($this->func == 'index') : ?>
    <div class="main_title">
        <span>管理</span>列表
    </div>
    <div class="search">
        <form>

            状态：<?=$check_status?>
            用户名：<input type="text" name="username" value="<?=$_GET['username']?>"/>
            充值时间：<input type="text" name="starttime" value="<?=$_GET['starttime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            到<input type="text" name="endtime" value="<?=$_GET['endtime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            <input type="submit" class="but2" value="查询" />
        </form>
    </div>
    <div class="main_content">
        <? if(!empty($result['total'])){?>
            <table class="layui-table">
                <tr>
                    <th>ID</th>
                    <th>用户名</th>
                    <th>申请时间</th>
                    <th>提现金额</th>
                    <th>提现费用</th>
                    <th>姓名</th>
                    <th>提现银行</th>
                    <th>开户支行</th>
                    <th>银行账户</th>
                    <th>审核备注</th>
                    <th>打款备注</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <? foreach($result['list'] as $row){?>
                    <tr>
                        <td><?=$row->id?></td>
                        <td><?=$row->user()->username?></td>
                        <td><?=$row->created_at?></td>
                        <td>￥<?=$row->total?></td>
                        <td>手续费：￥<?=$row->fee?><br>
                            <? if($row->tax_fee>0) : ?>
                                代扣税：￥<?=$row->tax_fee?>
                            <? endif;?>
                        </td>
                        <td><?=$row->name?></td>
                        <td><?=$row->bank?></td>
                        <td ><?=$row->branch?></td>
                        <td ><?=$row->card_no?></td>
                        <td><?=$row->verify_remark?></td>
                        <td><?=$row->remittance_remark?></td>
                        <td><? echo $row->getLinkPageName('check_status',$row->status)?></td>
                        <td>
                            <? if ($row->status == "1") : ?>
                                <a href="<?= url("cash/check/?id={$row->id}&page={$_GET['page']}") ?>">审核</a>
                            <? endif; ?>
                            <? if ($row->status == "2") : ?>
                                <a href="<?= url("cash/checkEnd/?id={$row->id}&page={$_GET['page']}") ?>">打款</a>
                            <? endif; ?>
                        </td>
                    </tr>
                <? }?>

            </table>
        <? }else{?>
            <div class="alert-warning" role="alert">无记录！</div>
        <? }?>
        <?=$result['page'];?>
    </div>
<? elseif ($this->func == 'check') : ?>
    <div class="main_title">
        <span>管理</span>
        <a href="<?= url('cash') ?>" class="but1">返回列表</a>
    </div>
    <div class="main_content">
        <form method="post">
            <input type="hidden" name="id" value="<?=$_GET['id']?>"/>
            <table class="table_from">
                <tr><td>编号：</td><td><?=$row->id?></td></tr>
                <tr><td>用户名：</td><td><?=$row->user()->username?></td></tr>
                <tr><td>提现金额：</td><td>￥<?=$row->total?></td></tr>
                <tr><td>提现费用：</td><td>手续费：￥<?=$row->fee?> &nbsp;
                        <? if($row->tax_fee>0) : ?>
                            代扣税：￥<?=$row->tax_fee?>
                        <? endif;?></td></tr>
                <tr><td>到账金额：</td><td>￥<?=$row->credited?></td></tr>
                <tr><td>申请时间：</td><td><?=$row->created_at?></td></tr>
                <tr><td>姓名：</td><td><?=$row->name?></td></tr>
                <tr><td>提现银行：</td><td><?=$row->bank?></td></tr>
                <tr><td>开户支行：</td><td><?=$row->branch?></td></tr>
                <tr><td>银行账户：</td><td><?=$row->card_no?></td></tr>
                <tr><td>状态：</td><td><? echo $row->getLinkPageName('check_status',$row->status)?></td></tr>
                <tr><td></td><td></td></tr>
                <tr><td>审核：</td><td>
                        <label><input type="radio" name="status" value="2"/>审核通过</label>
                        <label><input type="radio" name="status" value="3"/>审核不通过</label></td></tr>

                <? if($row->verify_remark!='') : ?>
                    <tr><td>原审核备注：</td><td><?=nl2br($row->verify_remark)?></td></tr>
                <? endif ?>
                <? if($row->verify_remark!='') : ?>
                    <tr><td>原打款审核备注：</td><td><?=nl2br($row->remittance_remark)?></td></tr>
                <? endif ?>
                <tr><td>审核备注：</td><td><textarea name="verify_remark" cols="45" rows="5"></textarea></td></tr>
                <tr><td></td><td><input type="submit" value="确认审核" />
                        <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
            </table>
        </form>
    </div>
    <? elseif ($this->func == 'checkEnd') : ?>
    <div class="main_title">
        <span>管理</span>
        <a href="<?= url('cash') ?>" class="but1">返回列表</a>
    </div>
    <div class="main_content">
        <form method="post">
            <input type="hidden" name="id" value="<?=$_GET['id']?>"/>
            <table class="table_from">
                <tr><td>编号：</td><td><?=$row->id?></td></tr>
                <tr><td>用户名：</td><td><?=$row->user()->username?></td></tr>
                <tr><td>提现金额：</td><td>￥<?=$row->total?></td></tr>
                <tr><td>提现费用：</td><td>手续费：￥<?=$row->fee?> &nbsp;
                        <? if($row->tax_fee>0) : ?>
                            代扣税：￥<?=$row->tax_fee?>
                        <? endif;?></td></tr>
                <tr><td>到账金额：</td><td>￥<?=$row->credited?></td></tr>
                <tr><td>申请时间：</td><td><?=$row->created_at?></td></tr>
                <tr><td>姓名：</td><td><?=$row->name?></td></tr>
                <tr><td>提现银行：</td><td><?=$row->bank?></td></tr>
                <tr><td>开户支行：</td><td><?=$row->branch?></td></tr>
                <tr><td>银行账户：</td><td><?=$row->card_no?></td></tr>
                <tr><td>状态：</td><td><? echo $row->getLinkPageName('check_status',$row->status)?></td></tr>
                <tr><td></td><td></td></tr>
                <tr><td>审核：</td><td>
                        <label><input type="radio" name="status" value="4"/>打款成功</label>
                        <label><input type="radio" name="status" value="1"/>返回待审核</label></td></tr>
                <tr><td>审核备注：</td><td><textarea name="verify_remark" cols="45" rows="5"></textarea></td></tr>
                <tr><td></td><td><input type="submit" value="确认操作" />
                        <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
            </table>
        </form>
    </div>
<? endif; ?>
<?php require 'footer.php'; ?>