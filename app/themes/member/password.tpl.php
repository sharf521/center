<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <?php if($this->func=='changePwd'):?>
            <div class="box">
                <h3>修改密码：</h3>
                <form method="post" onsubmit="return setdisabled();">
                    <table class="table_from">
                        <tr>
                            <td>原密码：</td>
                            <td><input type="password" name="old_password"/></td>
                        </tr>
                        <tr>
                            <td>新密码：</td>
                            <td><input type="password" name="password"/> 密码长度6位到15位</td>
                        </tr>
                        <tr>
                            <td>确认新密码：</td>
                            <td><input type="password" name="sure_password"/></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input class="but3" value="保存" type="submit"/></td>
                        </tr>
                    </table>
                </form>
            </div>
        <?php elseif($this->func=='changePayPwd') : ?>
            <div class="box">
                <h3>找回支付密码：</h3>
                <form method="post">
                    <table class="table_from">
                        <tr>
                            <td>原密码：</td>
                            <td><input type="password" name="old_password"/></td>
                        </tr>
                        <tr>
                            <td>新支付密码：</td>
                            <td><input type="password" name="zf_password"/> 密码长度6位到15位</td>
                        </tr>
                        <tr>
                            <td>确认新密码：</td>
                            <td><input type="password" name="sure_password"/></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input class="but3" value="保存" type="submit"/></td>
                        </tr>
                    </table>
                </form>
            </div>
        <?php elseif ($this->func == 'getPayPwd') : ?>
            <div class="box">
                <h3>找回支付密码：</h3>
                <form method="post">
                    <table class="table_from">
                        <tr><td >用户名：</td><td><?=$user->username?></td></tr>
                        <tr><td >邮箱地址：</td><td><?=$user->email?></td></tr>
                        <tr><td >验证码：</td><td><input type="text" name="valicode" size="11" maxlength="4" value=""/><img src="/index.php/plugin/code" alt="点击刷新" onClick="this.src='/index.php/plugin/code/?t=' + Math.random();" align="absmiddle" style="cursor:pointer" /></td></tr>
                        <tr><td></td><td><input  value="保 存" type="submit"/></td></tr>
                    </table>
                </form>
            </div>
        <?php elseif ($this->func == 'resetPayPwd') : ?>
            <div class="box">
                <h3>重置支付密码：</h3>
                <? if ($error != '') {
                    echo '<div class="alert-warning">' . $error . '</div>';
                } else {
                    ?>
                    <form method="post">
                        <table class="table_from">
                            <tr>
                                <td>新支付密码：</td>
                                <td><input type="password" name="zf_password"/> 密码长度6位到15位</td>
                            </tr>
                            <tr>
                                <td>确认新密码：</td>
                                <td><input type="password" name="sure_password"/></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input class="but3" value="保存" type="submit"/></td>
                            </tr>
                        </table>
                    </form>
                <? } ?>
            </div>
        <?php endif;?>
    </div>
</div>
<?php require 'footer.php';?>
