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
                    <? if($partner->status==0) : ?>
                        <? if($_GET['invite_code']!=$invite_code): ?>
                            <div class="alert-warning" role="alert">您己经使用过邀请码，己更换为原来的邀请码！</div>
                        <? endif;?>
                        <form method="post" onSubmit="return setdisabled();">
                            <table class="table_from">
                                <tr><td>用户：</td><td><?=$this->username?>（<?=$this->user->name?>）</td></tr>
                                <tr><td>可用金额：</td><td><?='￥'.$account->funds_available?></td></tr>
                                <tr><td>邀请码：</td><td><input type="text" name="invite_code" value="<?=$invite_code?>"></td></tr>
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
                <?php
                //己审枋
                if($partner->status==2){ ?>
                    <? if($account->funds_available>='262') : ?>
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
                        <blockquote class="layui-elem-quote">邀请好友将冻结262元,您的帐户余额不足262元，将暂时无法邀请！</blockquote>
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
        <?php endif;?>
    </div>
</div>
<?php require 'footer.php';?>
