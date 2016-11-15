<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <?php if($this->func=='cash') : ?>
            <div class="box">
                <ul class="nav-tabs">
                    <li class="active"><a href="<?=url('account/cash/?from='.$_GET['from'])?>">我要提现</a></li>
                    <li><a href="<?=url('account/cashLog/?from='.$_GET['from'])?>">提现记录</a></li>
                </ul>
                <? if($bank->card_no ==""){?>
                    <div class="alert-warning" role="alert">您还没有填写银行账户，请先填写<?=$this->anchor('user/bank/?from='.$_GET['from'],'>>银行账户>>');?></div>
                <? }else{?>
                    <form method="post" onSubmit="return setdisabled();">
                        <table class="table_from">
                            <tr><td>用户名：</td><td><?=$this->username?></td></tr>
                            <tr><td>可提现金额：</td><td><?='￥'.$account->funds_available?></td></tr>
                            <tr><td>姓名：</td><td><?=$this->user->name?></td></tr>
                            <tr><td>提现银行：</td><td><?=$bank->bank?></td></tr>
                            <tr><td>开户支行：</td><td><?=$bank->branch?></td></tr>
                            <tr><td>银行账号：</td><td><?=$bank->card_no?></td></tr>
                            <tr><td>提现金额：</td><td><input  name="total" type="text" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/>&nbsp;&nbsp;元</td></tr>
                            <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
                            <tr><td></td><td><input type="submit" value="提交" /></td></tr>
                        </table>
                    </form>
                    <ul class="prompt">
                        <h4>温馨提示：</h4>
                        <li>1. 单笔提现金额最低50.00元，最高50000.00元。</li>
                        <li>2. 单笔提现费率<?=math($cash_rate,100,'*',2)?>%，单笔提现手续费最低5.00元。</li>
                        <li>3. 每个工作日的下午5点之前提交的提现申请，T+1个工作日到账，每个工作日的下午5点之后提交的提现申请，T+2个工作日到账。</li>
                    </ul>
                <? }?>
            </div>
        <?php elseif ($this->func=='cashLog'): ?>
            <div class="box">
                <ul class="nav-tabs">
                    <li><a href="<?=url('account/cash/?from='.$_GET['from'])?>">我要提现</a></li>
                    <li class="active"><a href="<?=url('account/cashLog/?from='.$_GET['from'])?>">提现记录</a></li>
                </ul>
                <div class="search">
                    <form  method="get">
                        <input autocomplete="off" class="layui-input" name="starttime" type="text" lay-verify="date" value="<?=$_GET['starttime']?>" placeholder="开始日期" onclick="layui.laydate({elem: this})" style="width: 200px; display: inline-block">
                        到
                        <input autocomplete="off" class="layui-input" name="endtime" type="text" lay-verify="date" value="<?=$_GET['endtime']?>" placeholder="结束日期" onclick="layui.laydate({elem: this})" style="width: 200px; display: inline-block">
                        <input  type="submit" value="查询" />
                    </form>
                </div>
                <? if(!empty($result['total'])){?>
                    <table class="table">
                        <tr>
                            <th>申请时间</th>
                            <th>提现金额</th>
                            <th>手续费</th>
                            <th>提现银行</th>
                            <th>开户支行</th>
                            <th>银行账户</th>
                            <th>审核备注</th>
                            <th>打款备注</th>
                            <th>状态</th>
                        </tr>
                        <? foreach($result['list'] as $row){?>
                            <tr>
                                <td><?=$row->created_at?></td>
                                <td>￥<?=$row->total?></td>
                                <td>￥<?=$row->fee?></td>
                                <td><?=$row->bank?></td>
                                <td ><?=$row->branch?></td>
                                <td ><?=$row->card_no?></td>
                                <td><?=$row->verify_remark?></td>
                                <td><?=$row->remittance_remark?></td>
                                <td><? echo $row->getLinkPageName('check_status',$row->status)?></td>
                            </tr>
                        <? }?>
                    </table>
                <? }else{ ?>
                    <div class="alert-warning" role="alert">无记录！</div>
                <? }?>
                <?=$result['page'];?>
            </div>
        <?php endif;?>
    </div>
</div>
<?php require 'footer.php';?>
