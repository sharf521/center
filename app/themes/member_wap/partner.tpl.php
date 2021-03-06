<?php
require 'header.php';
?>
<div class="header">
    <a class="header_left" href="<?=url('')?>" ><i class="iconfont">&#xe603;</i>返回</a>
    <span class="header_right">&nbsp;</span>
    <h1>申请合伙人</h1>
</div>


<div class="margin_header"></div>
<? if($userInfo->card_status!=2) : ?>
    <div class="alert-warning" role="alert">您还没有完成实名认证，请先完成<?=$this->anchor('user/realName','>>实名认证>>');?></div>
<? else : ?>
        <? if($partner->status==0 || $partner->status==3) : ?>
        <? if(isset($_GET['invite_code']) && $_GET['invite_code']!=$invite_code){ ?>
            <div class="alert-warning" role="alert">您己经使用过邀请码，己更换为原来的邀请码！</div>
        <? }?>
        <? if(trim($partner->verify_remark)!=''){?>
            <div class="alert-warning" role="alert">审核备注：<?=nl2br($partner->verify_remark)?></div>
        <? }?>
        <form method="post" onSubmit="return setdisabled();" style="display: none">
            <table class="table_from">
                <tr><td style="width: 28%">用户：</td><td style="width: 72%"><?=$this->username?></td></tr>
                <tr><td>可用金额：</td><td><?='￥'.$account->funds_available?></td></tr>
                <tr><td>邀请码：</td><td><input type="text" name="invite_code" value="<?=$invite_code?>"></td></tr>
                <tr><td>申请级别：</td><td><?=$type?></td></tr>
                <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
                <tr><td colspan="2"><input type="submit" class="button1" value="提交申请" /></td></tr>
            </table>
        </form>
        <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.0/weui.css"/>
        <form method="post" name="form1">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">邀请码</label></div>
                    <div class="weui-cell__bd">
                        <input type="text" name="invite_code" value="<?=$invite_code?>"  placeholder=""  class="weui-input" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">申请级别</label></div>
                    <div class="weui-cell__bd">
                        <?=$type?>
                    </div>
                </div>
            </div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">扣除积分</label></div>
                    <div class="weui-cell__bd">
                        <input type="text" id="integral" name="integral" value="0" required placeholder="" onkeyup="value=value.replace(/[^0-9.]/g,'')"  class="weui-input" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="weui-cells__tips">可用积分：<span id="span_integral"><?=$account->integral_available?></span></div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">支付密码</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" required type="password" name="zf_password" placeholder="支付密码" />
                    </div>
                </div>
            </div>
            <div class="weui-cells__tips">可用金额：¥<span id="span_funds"><?=$account->funds_available?></span></div>
            <div style="text-align: right; padding: 10px 20px 0px 0px; font-size: 16px; font-weight: 600; color: #c00;">支付金额：¥<span id="money_yes"><?=$order->order_money?></span></div>
            <div class="weui-btn-area">
                <input class="weui-btn weui-btn_primary" type="submit" value="提交申请">
                <a class="recharge weui-btn weui-btn_plain-primary" href="<?=url('account/recharge')?>">我要充值</a>
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
            <tr><td>用户：</td><td><?=$this->username?></td></tr>
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
<? endif; ?>
<?php
//己审枋
if($partner->status==2){ ?>
    <? if($account->funds_available>='0') :
        //余额小于262也可以邀请
        ?>
        <div class="alert-warning" role="alert">
            将下面邀请码或二维码复制并发送给好友，该好友申请后您即成为好友的邀请人<!--，邀请好友将冻结262元,余额不足262元，将暂时无法邀请其它会员-->！
        </div>
        <table class="table_from">
            <tr><td>我的邀请码：</td><td><?=$invite_code?></td></tr>
            <tr><td>邀请二维码：</td><td><img src="<?=$invite_img?>" width="70%"></td></tr>
        </table>
     <? else : ?>
        <div class="alert-warning" role="alert">邀请好友将冻结262元,您的帐户余额不足262元，暂时无法邀请！</div>
    <? endif;?>
    <div style="line-height: 70px; font-size: 22px; font-weight: 400">我的邀请列表</div>
    <?
    if(count($invite_list)==0) {
        echo '<blockquote class="layui-elem-quote">暂无邀请</blockquote>';
    }else{ ?>
        <table class="table" width="100%" cellpadding="10" cellspacing="1">
            <thead>
            <tr>
                <th align="left">真实姓名</th>
                <th align="left">帐户</th>
                <th align="left">邀请时间</th>
                <th align="left">状态</th>
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
        </table><br>
        <?php
    }
} ?>
<?php require 'footer.php'; ?>