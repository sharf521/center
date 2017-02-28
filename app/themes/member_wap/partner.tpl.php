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
    <? if(! $partner->is_exist) : ?>
        <form method="post" onSubmit="return setdisabled();">
            <table class="table_from">
                <tr><td>用户：</td><td><?=$this->username?>（<?=$this->user->name?>）</td></tr>
                <tr><td>可用金额：</td><td><?='￥'.$account->funds_available?></td></tr>
                <tr><td>申请级别：</td><td><?=$type?></td></tr>
                <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
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

<?php require 'footer.php'; ?>