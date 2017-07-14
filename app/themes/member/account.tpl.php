<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <?php if ($this->func=='log') : ?>
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>资金记录</legend>
                </fieldset>
                <div class="search" style="padding-top: 0px;">
                    <form  method="get">
                        记录时间：
                        <input autocomplete="off" class="layui-input" name="starttime" type="text" lay-verify="date" value="<?=$_GET['starttime']?>" placeholder="开始日期" onclick="laydate({elem: this});" style="width: 100px; display: inline-block">
                        到
                            <input autocomplete="off" class="layui-input" name="endtime" type="text" lay-verify="date" value="<?=$_GET['endtime']?>" placeholder="结束日期" onclick="laydate({elem: this})" style="width: 100px; display: inline-block">
                        <input  type="submit" value="查询" />
                    </form>
                </div>
                <? if(!empty($result['total'])){?>
                    <table class="layui-table">
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
        <? elseif($this->func=='convert') : ?>
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>积分兑换现金</legend>
                </fieldset>

                <blockquote class="layui-elem-quote layui-quote-nm">
                    用户名：<?=$this->username?><br>
                    可用资金：<?='￥'.$account->funds_available?><br>
                    可用积分：<?=$account->integral_available?><br>
                </blockquote>
                <form method="post" class="layui-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">兑换积分</label>
                        <div class="layui-input-inline">
                            <input  name="total" type="text" class="layui-input" lay-verify="required" autocomplete="off" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">支付密码</label>
                        <div class="layui-input-inline">
                            <input  name="zf_password" type="password" class="layui-input" lay-verify="required" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">确认修改</button>
                        </div>
                    </div>
                </form>
                <ul class="prompt">
                    <h4>温馨提示：</h4>
                    <li>1、50积分起兑。</li>
                    <li>2、兑换比例：2.52积分=1元。</li>
                    <li>3、扣除31%用于长成积分。</li>
                </ul>
            </div>
        <? elseif($this->func=='playToUser') : ?>
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>站内转账</legend>
                </fieldset>
                <form method="post" onSubmit="return setdisabled();">
                    <table class="table_from">
                        <tr><td>用户名：</td><td><?=$this->username?></td></tr>
                        <tr><td>可用资金：</td><td><?='￥'.$account->funds_available?></td></tr>
                        <tr><td>可用积分：</td><td><?=$account->integral_available?></td></tr>
                        <tr><td>类型：</td><td>
                                <label><input type="radio" name="type" value="1" checked>转资金</label>
                                <label><input type="radio" name="type" value="2">转积分</label></td></tr>
                        <tr><td>对方用户名：</td><td><input  name="to_username" type="text"/></td></tr>
                        <tr><td>转出金额：</td><td><input  name="total" type="text" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/></td></tr>
                        <tr><td>备注：</td><td><textarea cols="50" rows="2" name="remark"></textarea></td></tr>
                        <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
                        <tr><td></td><td><input type="submit" value="立即转账"/></td></tr>
                    </table>
                </form>
                <ul class="prompt">
                    <h4>温馨提示：</h4>
                    <li>转资金手续费：<?=$transferFundsRateText?></li>
                    <li>转积分手续费：<?=$transferIntegralRateText?></li>
                </ul>
            </div>
        <?php endif;?>
    </div>
</div>
<?php require 'footer.php';?>
