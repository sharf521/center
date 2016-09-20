<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <?php if ($this->func=='log') : ?>
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
        <? elseif($this->func=='convert') : ?>
            <div class="box">
                <ul class="nav-tabs">
                    <li class="active"><span>积分兑换现金</span></li>
                </ul>
                <form method="post" onSubmit="return setdisabled();">
                    <table class="table_from">
                        <tr><td>用户名：</td><td><?=$this->username?></td></tr>
                        <tr><td>可用资金：</td><td><?='￥'.$account->funds_available?></td></tr>
                        <tr><td>可用积分：</td><td><?=$account->integral_available?></td></tr>
                        <tr><td>兑换积分：</td><td><input  name="total" type="text" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/></td></tr>
                        <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
                        <tr><td></td><td><input type="submit" value="立即兑换"/></td></tr>
                    </table>
                </form>
                <ul class="prompt">
                    <h4>温馨提示：</h4>
                    <li>1、50积分起兑。</li>
                    <li>2、兑换比例：2.52积分=1元。</li>
                    <li>3、扣除31%用于长成积分。</li>
                </ul>
            </div>
        <?php endif;?>
    </div>
</div>
<?php require 'footer.php';?>
