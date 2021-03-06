<?php require 'header.php';?>

<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <?php if($this->func=='userInfo'): ?>
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>个人信息</legend>
                </fieldset>
                <form method="post" >
                    <table class="table_from">
                        <tr><td>用户名：</td><td><?=$user->username?></td></tr>
                        <tr><td>注册邮箱：</td><td><?=$user->email?></td></tr>
                        <tr><td>头像：</td><td>
                                <span id="upload_span_headimgurl"><img src="<?=$user->headimgurl?>" height="50"></span>
                                <input type="hidden" name="headimgurl" value="<?=$user->headimgurl?>" id="headimgurl">
                                <input type="file" name="file" class="layui-upload-file" upload_id="headimgurl" upload_type="headimgurl">
                            </td></tr>
                        <tr><td>联系电话：</td><td><input type="text" name="tel" value="<?=$user->tel?>"/></td></tr>
                        <tr><td>联系QQ： </td><td><input type="text" name="qq" class="form-control" value="<?=$user->qq?>" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/></td></tr>
                        <tr><td>联系地址：</td><td><input type="text" name="address" class="form-control" value="<?=$user->address?>"/></td></tr>
                        <tr><td></td><td><input type="submit" value="保 存"/></td></tr>
                    </table>
                </form>
            </div>
            <?php elseif($this->func=='bank'): ?>
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>我的银行卡</legend>
                </fieldset>
                <? if($userInfo->card_status!=2) : ?>
                    <div class="alert-warning" role="alert">您还没有完成实名认证，请先完成<?=$this->anchor('user/realName/?from='.$_GET['from'],'>>实名认证>>');?></div>
                <?php else : ?>
                    <? if($bank->account==""){  ?>
                        <form method="post">
                            <table class="table_from">
                                <tr><td >用户名：</td><td><?=$this->user->username?></td></tr>
                                <tr><td >真实姓名：</td><td><?=$this->user->name?></td></tr>
                                <tr><td>开户银行：</td><td><?=$bank->selBank?></td></tr>
                                <tr><td >开户支行：</td><td><input  name="branch" type="text" value="<?=$bank->branch?>"/></td></tr>
                                <tr><td >银行账号：</td><td><input  name="card_no" type="text" value="<?=$bank->card_no?>"/></td></tr>
                                <tr><td></td><td><input type="submit" value="保 存" /></td></tr>
                            </table>
                        </form>
                    <? }else{ ?>
                        <table class="table_from">
                            <tr><td >用户名：</td><td><?=$this->user->username?></td></tr>
                            <tr><td >真实姓名：</td><td><?=$this->user->name?></td></tr>
                            <tr><td align="right">开户银行：</td><td><?=$bank->bank?></td></tr>
                            <tr><td align="right">开户支行：</td><td><?=$bank->branch?></td></tr>
                            <tr><td align="right">银行账号：</td><td><?=$bank->card_no?></td></tr>
                        </table>
                    <? }?>
                <? endif?>
            </div>
        <?php endif;?>
    </div>
</div>
<?php require 'footer.php';?>
