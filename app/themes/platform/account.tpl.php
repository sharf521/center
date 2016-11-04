<?php require 'header.php';?>
<?php if ($this->func=='log') : ?>
    <div class="box">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>电子币明细</legend>
        </fieldset>
        <div class="search" style="display: none">
            <form  method="get">
                记录时间：
                <input autocomplete="off" class="layui-input" name="starttime" type="text" lay-verify="date" value="<?=$_GET['starttime']?>" placeholder="开始日期" onclick="laydate({elem: this});" style="width: 100px; display: inline-block">
                到
                <input autocomplete="off" class="layui-input" name="endtime" type="text" lay-verify="date" value="<?=$_GET['endtime']?>" placeholder="结束日期" onclick="laydate({elem: this})" style="width: 100px; display: inline-block">
                <input  type="submit" value="查询" />
            </form>
        </div>
        <? if(!empty($result['total'])){?>
            <table class="table">
                <tr class="bt">
                    <th>电子币</th>
                    <th>冻结</th>
                    <th>类型</th>
                    <th>添加时间</th>
                    <th>当前电子币</th>
                    <th>当前冻结</th>
                    <th>备注</th>
                </tr>
                <?
                foreach($result['list'] as $row)
                {
                    ?>
                    <tr>
                        <td><?=(float)$row->money?></td>
                        <td><?=(float)$row->money_freeze?></td>
                        <td><?=$row->getLinkPageName('tea_money_type',$row->type);?></td>
                        <td><?=$row->created_at?></td>
                        <td><?=(float)$row->money_now?></td>
                        <td><?=(float)$row->money_freeze_now?></td>
                        <td><?=$row->remark?></td>
                    </tr>
                <? }?>
            </table>
        <? }else{?>
            <div class="alert-warning" role="alert">无记录！</div>
        <? }?>
        <?=$result['page'];?>
    </div>
<? elseif($this->func=='convertIn') : ?>
    <div class="box">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>兑换电子币</legend>
        </fieldset>
        <form method="post" onSubmit="return setdisabled();">
            <table class="table_from">
                <tr><td>用户名：</td><td><?=$this->username?></td></tr>
                <tr><td>可用资金：</td><td><?='￥'.$account->funds_available?></td></tr>
                <tr><td>兑换数量：</td><td><input  name="total" type="text" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/></td></tr>
                <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
                <tr><td></td><td><input type="submit" value="立即兑换"/></td></tr>
            </table>
        </form>
        <ul class="prompt">
            <h4>温馨提示：50起兑</h4>
        </ul>
    </div>
<? elseif($this->func=='convertOut') : ?>
    <div class="box">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>兑换现金</legend>
        </fieldset>
        <form method="post" onSubmit="return setdisabled();">
            <table class="table_from">
                <tr><td>用户名：</td><td><?=$this->username?></td></tr>
                <tr><td>可用电子币：</td><td><?='￥'.$teaMoney->money?></td></tr>
                <tr><td>兑换数量：</td><td><input  name="total" type="text" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/></td></tr>
                <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
                <tr><td></td><td><input type="submit" value="立即兑换"/></td></tr>
            </table>
        </form>
        <ul class="prompt">
            <h4>温馨提示：50起兑</h4>
        </ul>
    </div>
<? elseif($this->func=='payToUser') : ?>
    <div class="box">
        <ul class="nav-tabs">
            <li class="active"><span>站内转账</span></li>
        </ul>
        <form method="post" onSubmit="return setdisabled();">
            <table class="table_from">
                <tr><td>用户名：</td><td><?=$this->username?></td></tr>
                <tr><td>可用电子币：</td><td><?='￥'.$teaMoney->money?></td></tr>
                <tr><td>对方用户名：</td><td><input  name="to_username" type="text"/></td></tr>
                <tr><td>转出数量：</td><td><input  name="total" type="text" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/></td></tr>
                <tr><td>备注：</td><td><textarea cols="50" rows="2" name="remark"></textarea></td></tr>
                <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
                <tr><td></td><td><input type="submit" value="立即转账"/></td></tr>
            </table>
        </form>
    </div>
<?php endif;?>
<?php require 'footer.php';?>
