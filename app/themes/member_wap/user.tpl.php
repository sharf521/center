<?php require 'header.php';?>


        <?php if($this->func=='userInfo'): ?>
            <div class="box">
                <h3>个人信息：</h3>
                <form method="post">
                    <table class="table_from">
                        <tr><td>用户名：</td><td><?=$user->username?></td></tr>
                        <tr><td>注册邮箱：</td><td><?=$user->email?></td></tr>
                        <tr><td>联系电话：</td><td><input type="text" name="tel" value="<?=$user->tel?>"/></td></tr>
                        <tr><td>联系QQ： </td><td><input type="text" name="qq" class="form-control" value="<?=$user->qq?>" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/></td></tr>
                        <tr><td>联系地址：</td><td><input type="text" name="address" class="form-control" value="<?=$user->address?>"/></td></tr>
                        <tr><td></td><td><input type="submit" value="保 存"/></td></tr>
                    </table>
                </form>
            </div>
        <?php elseif($this->func=='changePwd'):?>
            <nav>
                <span><a href="javascript:history.go(-1)"><img src="/themes/member_wap/images/icon_right_hui.png" /></a></span>
                <p>首页</p>
            </nav>
            <div class="m_regtilinde">密码修改</div>
            <div class="view">
                <ul>
                    <li class="cur"><a  href="<?=url('user/changePwd')?>">账户密码</a></li>
                    <li>支付密码</li>
                    <li>找回密码</li>
                </ul>
            </div>
            <div class="show_box">
                <div class="show_now" style="display: block;">
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
            <?php elseif($this->func=='bank'): ?>
            <div class="box">
                <h3>我的银行卡：</h3>
                <? if($bank->account==""){?>
                    <form method="post">
                        <table class="table_from">
                            <tr><td >用户名：</td><td><?=$this->user->username?></td></tr>
                            <tr><td >真实姓名：</td><td><?=$this->user->name?></td></tr>
                            <tr><td>开户银行：</td><td><?=$bank->selBank?></td></tr>
                            <tr><td >开户支行：</td><td><input  name="branch" type="text" value="<?=$bank->branch?>"/></td></tr>
                            <tr><td >银行账号：</td><td><input  name="account" type="text" value="<?=$bank->account?>"/></td></tr>
                            <tr><td></td><td><input type="submit" value="保 存" /></td></tr>
                        </table>
                    </form>
                <? }else{ ?>
                    <table class="table_from">
                        <tr><td >用户名：</td><td><?=$this->user->username?></td></tr>
                        <tr><td >真实姓名：</td><td><?=$this->user->name?></td></tr>
                        <tr><td align="right">开户银行：</td><td><?=$bank->bank?></td></tr>
                        <tr><td align="right">开户支行：</td><td><?=$bank->branch?></td></tr>
                        <tr><td align="right">银行账号：</td><td><?=$bank->account?></td></tr>
                    </table>
                <? }?>
            </div>
        <?php endif;?>

<?php require 'footer.php';?>