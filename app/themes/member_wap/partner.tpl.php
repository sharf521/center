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
    <? if($partner->status==0) : ?>
        <? if($_GET['invite_code']!=$invite_code): ?>
            <div class="alert-warning" role="alert">您己经使用过邀请码，己更换为原来的邀请码！</div>
        <? endif;?>
        <form method="post" onSubmit="return setdisabled();">
            <table class="table_from">
                <tr><td>用户：</td><td><?=$this->username?>（<?=$this->user->name?>）</td></tr>
                <tr><td>可用金额：</td><td><?='￥'.$account->funds_available?></td></tr>
                <tr><td>邀请码：</td><td><input type="text" name="invite_code" value="<?=$invite_code?>" width="40"></td></tr>
                <tr><td>申请级别：</td><td><?=$type?></td></tr>
                <tr><td>支付密码：</td><td><input  name="zf_password" type="password" width="40"/></td></tr>
                <tr><td></td><td><input type="submit" value="提交申请" /></td></tr>
            </table>
        </form>
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
<? endif; ?>
<?php
//己审枋
if($partner->status==2){ ?>
    <? if($account->funds_available>='262') : ?>
        <div class="alert-warning" role="alert">将下面邀请码或二维码复制并发送给好友，该好友申请后您即成为好友的邀请人</div>

        <table class="table_from">
            <tr><td>我的邀请码：</td><td><?=$invite_code?></td></tr>
            <tr><td>邀请二维码：</td><td><img src="<?=$invite_img?>"></td></tr>
        </table>
    <? else : ?>
        <div class="alert-warning" role="alert">您的余额不足262元，暂时无法邀请其它会员！<br> 您的余额：<?=$account->funds_available?>元</div>
    <? endif;?>
    <fieldset class="layui-elem-field layui-field-title">
        <legend>我的邀请列表</legend>
    </fieldset>
    <?
    if(count($invite_list)==0) {
        echo '<blockquote class="layui-elem-quote">暂无邀请</blockquote>';
    }else{ ?>
        <table class="table" width="100%" cellpadding="10">
            <thead>
            <tr>
                <th>用户名</th>
                <th>邀请时间</th>
                <th>状态</th>
            </tr>
            </thead>
            <tbody>
            <? foreach ($invite_list as $part) : ?>
                <tr>
                    <td><?=(new \App\Model\User())->find($part->user_id)->username ?></td><td><?= $part->created_at ?></td>
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
<?php require 'footer.php'; ?>