<?php require 'header.php';?>
<?php if($this->func=='changePwd'):?>
    <nav>
        <span><a href="/"><img src="/themes/member_wap/images/icon_right_hui.png" />返回</a></span>
    </nav>
    <div class="m_regtilinde">密码修改</div>
    <div class="view">
        <ul>
            <li <? if($this->func=='changePwd'){echo 'class="cur"';}?>><a  href="<?=url('password/changePwd')?>">账户密码</a></li>
            <li <? if($this->func=='changePayPwd'){echo 'class="cur"';}?>><a href="<?=url('password/changePayPwd')?>">修改支付密码</a></li>
            <li <? if($this->func=='getPayPwd'){echo 'class="cur"';}?>><a href="<?=url('password/getPayPwd')?>">找回支付密码</a></li>
        </ul>
    </div>
    <div class="show_box">
        <form method="post" onsubmit="return setdisabled();">
            <div class="ca_d_table">
                <table width="100%">
                    <tbody>
                    <tr>
                        <td align="right" style="width:12rem;">原始密码：</td>
                        <td colspan="2"><input type="password" name="old_password" class="nam_inpou" /><b></b></td>
                    </tr>
                    <tr>
                        <td align="right" style="width:12rem;">新密码：</td>
                        <td colspan="2"><input type="password" name="password"  class="nam_inpou" /><b></b></td>
                    </tr>
                    <tr>
                        <td align="right" style="width:12rem;">确认密码：</td>
                        <td colspan="2"><input type="password" name="sure_password" class="nam_inpou" /><b></b></td>
                    </tr>
                    <tr>
                        <td colspan="3" align="center"><input class="cada_tba" type="submit" value="确认修改"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
<?php elseif($this->func=='changePayPwd') : ?>
    <nav>
        <span><a href="/"><img src="/themes/member_wap/images/icon_right_hui.png" />首页</a></span>
    </nav>
    <div class="m_regtilinde">修改支付密码</div>
    <div class="view">
        <ul>
            <li <? if($this->func=='changePwd'){echo 'class="cur"';}?>><a  href="<?=url('password/changePwd')?>">账户密码</a></li>
            <li <? if($this->func=='changePayPwd'){echo 'class="cur"';}?>><a href="<?=url('password/changePayPwd')?>">修改支付密码</a></li>
            <li <? if($this->func=='getPayPwd'){echo 'class="cur"';}?>><a href="<?=url('password/getPayPwd')?>">找回支付密码</a></li>
        </ul>
    </div>
    <div class="show_box">
        <form method="post" onsubmit="return setdisabled();">
            <div class="ca_d_table">
                <table width="100%">
                    <tr>
                        <td align="right" style="width:12rem;">原密码：</td>
                        <td><input type="password" name="old_password" class="nam_inpou"/></td>
                    </tr>
                    <tr>
                        <td align="right" style="width:12rem;">新密码：</td>
                        <td><input type="password" name="zf_password" class="nam_inpou"/></td>
                    </tr>
                    <tr>
                        <td align="right" style="width:12rem;">确认密码：</td>
                        <td><input type="password" name="sure_password" class="nam_inpou"/></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><input class="cada_tba" type="submit" value="确认修改"></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
<?php elseif ($this->func == 'getPayPwd') : ?>
    <div class="box">
        <nav>
            <span><a href="/"><img src="/themes/member_wap/images/icon_right_hui.png" />首页</a></span>
        </nav>
        <div class="m_regtilinde">找回支付密码</div>
        <div class="view">
            <ul>
                <li <? if($this->func=='changePwd'){echo 'class="cur"';}?>><a  href="<?=url('password/changePwd')?>">账户密码</a></li>
                <li <? if($this->func=='changePayPwd'){echo 'class="cur"';}?>><a href="<?=url('password/changePayPwd')?>">修改支付密码</a></li>
                <li <? if($this->func=='getPayPwd'){echo 'class="cur"';}?>><a href="<?=url('password/getPayPwd')?>">找回支付密码</a></li>
            </ul>
        </div>
        <div class="show_box">
            <form method="post" onsubmit="return setdisabled();">
                <div class="ca_d_table">
                    <table width="100%">
                        <tr><td align="right" style="width:12rem;">用户名：</td><td><?=$user->username?></td></tr>
                        <tr><td align="right" style="width:12rem;">邮箱地址：</td><td><?=$user->email?></td></tr>
                        <tr>
                            <td align="right" style="width:12rem;">验证码：</td>
                            <td><input type="text" class="nam_inpou" style="width: 15rem" name="valicode" size="11" maxlength="4" value=""/><img src="/index.php/plugin/code" alt="点击刷新" onClick="this.src='/index.php/plugin/code/?t=' + Math.random();" align="absmiddle" style="cursor:pointer; height: 3rem"/></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><input class="cada_tba" type="submit" value="确认"></td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
<?php elseif ($this->func == 'resetPayPwd') : ?>
    <div class="box">
        <div class="m_regtilinde">重置支付密码：</div>
        <? if ($error != '') {
            echo '<div class="alert-warning">' . $error . '</div>';
        } else {
            ?>
            <form method="post">
                <div class="ca_d_table">
                    <table width="100%">
                        <tr>
                            <td align="right" style="width:12rem;">新密码：</td>
                            <td><input type="password" name="zf_password" class="nam_inpou"/></td>
                        </tr>
                        <tr>
                            <td align="right" style="width:12rem;">确认密码：</td>
                            <td><input type="password" name="sure_password" class="nam_inpou"/></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><input class="cada_tba" type="submit" value="确认修改"></td>
                        </tr>
                    </table>
                </div>
            </form>
        <? } ?>
    </div>
<?php endif;?>

<?php require 'footer.php';?>