<?php require 'header.php';?>
<div class="warpcon">
    <?php require 'left.php';?>
    <div class="warpright">
        <?php if($this->func=='changePwd'):?>
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>修改密码</legend>
                </fieldset>

                <form class="layui-form layui-form-pane" method="post">
                    <div class="layui-form-item">
                        <label class="layui-form-label">原密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="old_password" placeholder="请输入原密码" class="layui-input" lay-verify="required" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">新密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="password" placeholder="密码长度6位到15位" class="layui-input" lay-verify="required" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">确认新密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="sure_password" placeholder="确认新密码" class="layui-input" lay-verify="required" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">确认修改</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php elseif($this->func=='changePayPwd') : ?>
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>修改支付密码</legend>
                </fieldset>
                <form class="layui-form" method="post">
                    <div class="layui-form-item">
                        <label class="layui-form-label">原密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="old_password" placeholder="请输入原密码" class="layui-input" lay-verify="required" autocomplete="off"/>
                        </div>
                        <div class="layui-form-mid layui-word-aux">初始密码为注册时填写的登陆密码</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">新支付密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="zf_password" placeholder="密码长度6位到15位" class="layui-input" lay-verify="required" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">确认新密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="sure_password" placeholder="确认新密码" class="layui-input" lay-verify="required" autocomplete="off"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">确认修改</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php elseif ($this->func == 'getPayPwd') : ?>
            <div class="box">
                <br>
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>找回支付密码</legend>
                </fieldset>
                <form method="post">
                    <table class="table_from">
                        <tr><td >用户名：</td><td><?=$user->username?></td></tr>
                        <tr><td >邮箱地址：</td><td><?=$user->email?></td></tr>
                        <tr><td >验证码：</td><td><input type="text" name="valicode" size="11" maxlength="4" value=""/><img src="/index.php/plugin/code/?<?=rand(1000,9999)?>" alt="点击刷新" onClick="this.src='/index.php/plugin/code/?t=' + Math.random();" align="absmiddle" style="cursor:pointer" /></td></tr>
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
