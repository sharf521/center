<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <?php if($this->func=='index') : ?>
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>申请合伙人</legend>
                </fieldset>
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
            </div>
        <?php endif;?>
    </div>
</div>
<?php require 'footer.php';?>
