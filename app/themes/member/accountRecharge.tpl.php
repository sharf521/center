<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <?php if($this->func=='recharge') : ?>
            <div class="box">
                <ul class="nav-tabs">
                    <li class="active"><a href="<?=url('account/recharge/?from='.$_GET['from'])?>">我要充值</a></li>
                    <li><a href="<?=url('account/rechargeLog/?from='.$_GET['from'])?>">充值记录</a></li>
                </ul>
                <form id="formpay" method="post" onSubmit="return card();" action="/pay/recharge" target="_blank">
                    <table class="table_from">
                        <tr>
                            <td align="right">用户名：</td><td><?=$user->username?></td>
                        </tr>
                        <tr>
                            <td align="right">充值金额：</td><td><input id="money" value="<?=$money?>" name="money" type="text" size="8" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/>&nbsp;&nbsp;元</td>
                        </tr>
                        <tr>
                            <td align="right">充值方式：</td>
                            <td><label><input id="type1" name="type" type="radio" value="1" onClick="changetype(1)" checked="checked"/> 在线充值</label>
                                <label><input id="type2" name="type" type="radio" value="2" onClick="changetype(2)"/> 线下充值</label>
                                <!--<label><input id="type3" name="type" type="radio" value="3" onClick="changetype(3)"/> 分期充值</label>-->
                            </td>
                        </tr>
                        <tr id="xianshang">
                            <td align="right">充值银行：</td>
                            <td>
                                <table>
                                    <tr>
                                        <td ><label><input type="radio" name="GateId" value="25" checked="checked"/>
                                                <img align="absmiddle" src="/themes/images/bank/ICBC_OUT.gif" border="0"/></label></td>
                                        <td><label><input type="radio" name="GateId" value="29"/>
                                                <img align="absmiddle" src="/themes/images/bank/ABC_OUT.gif" border="0"/></label></td>
                                        <td ><label><input type="radio" name="GateId" value="27"/>
                                                <img align="absmiddle" src="/themes/images/bank/CCB_OUT.gif" border="0"/></label></td>
                                    </tr>
                                    <tr>
                                        <td><label><input type="radio" name="GateId" value="28"/>
                                                <img align="absmiddle" src="/themes/images/bank/CMB_OUT.gif" border="0"/></label></td>
                                        <td><label><input type="radio" name="GateId" value="12"/>
                                                <img align="absmiddle" src="/themes/images/bank/CMBC_OUT.gif" border="0"/></label></td>
                                        <td><label><input type="radio" name="GateId" value="13"/>
                                                <img align="absmiddle" src="/themes/images/bank/hx.jpg" border="0"/></label></td>
                                    </tr>
                                    <tr>
                                        <td><label><input type="radio" name="GateId" value="33"/>
                                                <img align="absmiddle" src="/themes/images/bank/CITIC_OUT.gif" border="0"/></label></td>
                                        <td><label><input type="radio" name="GateId" value="36"/>
                                                <img align="absmiddle" src="/themes/images/bank/CEB_OUT.gif" border="0"/></label></td>
                                        <td><label><input type="radio" name="GateId" value="09"/>
                                                <img align="absmiddle" src="/themes/images/bank/CIB_OUT.gif" border="0"/></label></td>
                                    </tr>
                                    <tr>
                                        <td><label><input type="radio" name="GateId" value="PSBC"/>
                                                <img align="absmiddle" src="/themes/images/bank/yz.jpg" border="0"/></label></td>
                                        <td><label><input type="radio" name="GateId" value="45">
                                                <img align="absmiddle" src="/themes/images/bank/BOC_OUT.gif" border="0"/></label></td>
                                        <td><label><input type="radio" name="GateId" value="21"/>
                                                <img align="absmiddle" src="/themes/images/bank/COMM_OUT.gif" border="0"/></label></td>
                                    </tr>
                                    <tr>
                                        <td><label><input type="radio" name="GateId" value="GDB" />
                                                <img align="absmiddle" src="/themes/images/bank/GDB_OUT.gif" border="0"/></label></td>
                                        <td><label><input type="radio" name="GateId" value="16">
                                                <img align="absmiddle" src="/themes/images/bank/pf.jpg" border="0"/></label></td>
                                        <td></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr id="xianxia" style="display:none">
                            <td align="right">线下充值：</td>
                            <td>
                                <table cellpadding="4" cellspacing="1">
                                    <tr><?
                                        $system=new \App\Model\System();
                                        $accounts=$system->getCode('pay_accounts');
                                        $accounts=explode("\r\n",$accounts);
                                        foreach($accounts as $account){
                                            $val=explode('[#]',$account);
                                            if(!empty($val[0])) :
                                            ?>
                                            <tr><td><input type="radio" name="payment" value="<?=$val[0]?>" checked="checked"/></td><td><?=$val[1]?></td></tr>
                                            <?
                                            endif;
                                        }
                                        ?>

                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr id="xianxiabz" style="display:none">
                            <td align="right">备注：</td><td><textarea  id="remark" name="remark" cols="60" rows="5"></textarea>*必填</td>
                        </tr>
                        <tr id="fenqi" style="display:none">
                            <td align="right"></td>
                            <td>
                                金额2千至5万、12期、费用7%
                            </td>
                        </tr>
                        <tr>
                            <td></td><td><input type="submit" value="确认提交"/></td>
                        </tr>
                    </table>
                </form>
                <ul class="prompt">
                    <h4>温馨提示：</h4>
                    <li>1.	线下充值  单笔金额不低于1000元，有效充值登记时间为:周一至周五的9:30到17:00，充值成功请跟我们的客服联系；</li>
                    <li>2.	线下充值备注  请注明您的用户名，转账银行卡号和转账流水号，以及转账时间。</li>
                </ul>
            </div>
        <?php elseif ($this->func=='rechargeLog'): ?>
            <div class="box">
                <ul class="nav-tabs">
                    <li ><a href="<?=url('account/recharge/?from='.$_GET['from'])?>">我要充值</a></li>
                    <li class="active"><a href="<?=url('account/rechargeLog/?from='.$_GET['from'])?>">充值记录</a></li>
                </ul>
                <div class="search">
                    <form  method="get">
                        记录时间：
                        <input autocomplete="off" class="layui-input" name="starttime" type="text" lay-verify="date" value="<?=$_GET['starttime']?>" placeholder="开始日期" onclick="layui.laydate({elem: this})" style="width: 100px; display: inline-block">
                        到
                        <input autocomplete="off" class="layui-input" name="endtime" type="text" lay-verify="date" value="<?=$_GET['endtime']?>" placeholder="结束日期" onclick="layui.laydate({elem: this})" style="width: 100px; display: inline-block">
                        <input  type="submit" value="查询" />
                    </form>
                </div>
                <?
                if(!empty($result['total'])){?>
                    <table class="table">
                        <tr>
                            <th>充值时间</th>
                            <th>充值类型</th>
                            <th>充值金额</th>
                            <th>手续费</th>
                            <th>到账金额</th>
                            <th>充值备注</th>
                            <th>审核备注</th>
                            <th>状态</th>
                        </tr>
                        <? foreach($result['list'] as $row){?>
                            <tr>
                                <td align="center"><?=$row->created_at?></td>
                                <td align="center">
                                    <?php
                                    switch ($row->type){
                                        case 1:
                                            echo '在线';
                                            break;
                                        case 2:
                                            echo '线下';
                                            break;
                                        case 3:
                                            echo '分期';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td>￥<?=(float)$row->money?></td>
                                <td>￥<?=(float)$row->fee?></td>
                                <td style="color:#F00;"><? if($row->status==1){?>￥<?=$row->money-$row->fee?><? }?></td>
                                <td><?=nl2br($row->remark)?></td>
                                <td><?=nl2br($row->verify_remark)?></td>
                                <td align="center"><? if ($row->status == 0) {
                                        if($row->type==2){
                                            echo "待审核";
                                        }else{
                                            echo '未成功';
                                        }
                                    } elseif ($row->status == 1) {
                                        echo "充值成功";
                                    } elseif ($row->status == 2) {
                                        echo "审核未通过";
                                    } ?></td>
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