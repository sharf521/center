<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <div class="box">
            <br>
            <fieldset class="layui-elem-field layui-field-title">
                <legend>申请合伙人</legend>
            </fieldset>
            <? if($userInfo->card_status!=2){ ?>
                <div class="alert-warning" role="alert">您还没有完成实名认证，请先完成<?=$this->anchor('user/realName','>>实名认证>>');?></div>
            <? }else{ ?>
            <? if($partner->status==0 || $partner->status==3) : ?>
            <? if(isset($_GET['invite_code']) && $_GET['invite_code']!=$invite_code){?>
                <div class="alert-warning" role="alert">您己经使用过邀请码，己更换为原来的邀请码！</div>
            <? }?>
                <? if(trim($partner->verify_remark)!=''){?>
                <div class="alert-warning" role="alert">审核备注：<?=nl2br($partner->verify_remark)?></div>
                <? }?>
                <form method="post" onSubmit="return setdisabled();" style="display: none">
                    <table class="table_from">
                        <tr><td>用户：</td><td><?=$this->username?>（<?=$this->user->name?>）</td></tr>
                        <tr><td>可用金额：</td><td><?='￥'.$account->funds_available?></td></tr>
                        <tr><td>邀请码：</td><td><input type="text" name="invite_code" value="<?=$invite_code?>"></td></tr>
                        <tr><td>申请级别：</td><td><?=$type?></td></tr>
                        <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
                        <tr><td></td><td><input type="submit" value="提交申请" /></td></tr>
                    </table>
                </form>
                <blockquote class="layui-elem-quote layui-quote-nm">
                    用户名：<?=$this->username?><br>
                    可用资金：<?='￥'.$account->funds_available?><br>
                    可用积分：<?=$account->integral_available?><br>
                </blockquote>
                <form method="post" class="layui-form">
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">申请级别</label>
                            <div class="layui-input-inline">
                                <?=$type?>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">邀请码</label>
                            <div class="layui-input-inline">
                                <input type="text" name="invite_code" value="<?=$invite_code?>"  placeholder="" class="layui-input" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">扣除积分</label>
                            <div class="layui-input-inline">
                                <input type="text" id="integral" name="integral" value="0" required placeholder="" onkeyup="value=value.replace(/[^0-9.]/g,'')"  class="layui-input" autocomplete="off"/>
                            </div>
                            <div class="layui-form-mid layui-word-aux">可用积分：<span id="span_integral"><?=(float)$account->integral_available?></span></div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">支付密码</label>
                            <div class="layui-input-inline">
                                <input class="layui-input" required type="password" name="zf_password" placeholder="支付密码" />
                            </div>
                            <div class="layui-form-mid layui-word-aux">可用金额：￥<span id="span_funds"><?=(float)$account->funds_available?></span></div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">实际支付：￥<span id="money_yes"><?=$order->order_money?></span> &nbsp;
                            <a class="layui-btn layui-btn-mini layui-btn-normal recharge" target="_blank"
                               href="/member/account/recharge/">我要充值</a>
                            <br><br>
                            <button class="layui-btn" lay-submit="" lay-filter="*">提交申请</button>
                            <button class="layui-btn" onclick="history.go(-1)">返回</button>
                        </div>
                    </div>
                </form>
                <script src="/plugin/js/math.js"></script>
                <script>
                    var lv='<?=$convert_rate?>';
                    var price_true='0';
                    partner_js();
                </script>
            <? else : ?>
                <table class="table_from">
                    <tr><td>用户：</td><td><?=$this->username?>（<?=$this->user->name?>）</td></tr>
                    <tr><td>级别：</td><td><? echo $partner->getLinkPageName('partner_type',$partner->type)?></td></tr>
                    <tr><td>状态：</td><td><? echo $partner->getLinkPageName('check_status',$partner->status)?></td></tr>
                    <?
                    if($partner->status==3){
                        ?>
                        <tr><td>审核备注：</td><td><?=nl2br($partner->verify_remark)?></td></tr>
                        <?
                    }
                    ?>
                </table>
            <? endif;?>
            <? } ?>
            <?php
            //己审
            if($partner->status==2){ ?>
                <? if($account->funds_available>='0') :
                    //余额小于262也可以邀请
                    ?>
                    <blockquote class="layui-elem-quote">
                        将下面邀请码或二维码复制并发送给好友，该好友申请后您即成为好友的邀请人
                        <!--<br>邀请好友将冻结262元,如果余额不足262元，将暂时无法邀请！--></blockquote>
                    <form class="layui-form" method="post">
                        <div class="layui-form-item">
                            <label class="layui-form-label">我的邀请码</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" value="<?=$invite_code?>" readonly>
                            </div>
                            <div class="layui-form-mid layui-word-aux"></div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">邀请二维码</label>
                            <div class="layui-input-inline">
                                <img src="<?=$invite_img?>">
                            </div>
                        </div>
                    </form>
                <? else : ?>
                    <blockquote class="layui-elem-quote">邀请好友将冻结262元,您的帐户余额不足262元，暂时无法邀请！</blockquote>
                <? endif;?>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>我的邀请列表</legend>
                </fieldset>
                <?
                if(count($invite_list)==0) {
                    echo '<blockquote class="layui-elem-quote">暂无邀请</blockquote>';
                }else{ ?>
                    <table class="layui-table" lay-skin="line">
                        <thead>
                        <tr>
                            <th>真实姓名</th>
                            <th>帐号</th>
                            <th>邀请时间</th>
                            <th>状态</th>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach ($invite_list as $part) :
                            $user=(new \App\Model\User())->find($part->user_id);
                            ?>
                            <tr>
                                <td><?=$user->name?></td>
                                <td><?=$user->username ?></td><td><?= $part->created_at ?></td>
                                <td><?
                                    if($part->status==0){
                                        echo '待支付';
                                    }else{
                                        echo $partner->getLinkPageName('check_status',$part->status);
                                    }
                                    ?></td>
                            </tr>
                        <? endforeach; ?>
                        </tbody>
                    </table>
                    <?php
                }
            } ?>
        </div>
    </div>
</div>
<?php require 'footer.php';?>
