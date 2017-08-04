<?php require 'header_v2.php';?>
<?php if($this->func=='changePwd'):?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('')?>"><i class="iconfont">&#xe603;</i>返回</a>
        <a class="m_header_r"></a>
        <h1>密码修改</h1>
    </div>
    <div class="margin_header"></div>
    <div class="m-password">
        <div class="weui-tab m-password__navbar">
            <ul class="weui-navbar">
                <li class="weui-navbar__item <? if($this->func=='changePwd'){echo 'weui-bar__item_on';}?>" data_url='<?=url('password/changePwd')?>'>账户密码</li>
                <li class="weui-navbar__item <? if($this->func=='changePayPwd'){echo 'weui-bar__item_on';}?>" data_url="<?=url('password/changePayPwd')?>">修改支付密码</li>
                <li class="weui-navbar__item <? if($this->func=='getPayPwd'){echo 'weui-bar__item_on';}?>" data_url="<?=url('password/getPayPwd')?>">找回支付密码</li>
            </ul>
        </div>
        <form method="post" name="form2" class="m-password__form" onsubmit="return validateForm()">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">原密码：</label></div>
                    <div class="weui-cell__bd">
                        <input type="password" name="old_password" placeholder="输入原密码"  class="weui-input" id="original_psw" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">新密码：</label></div>
                    <div class="weui-cell__bd">
                        <input type="password" name="password"  placeholder="长度6至15位"  class="weui-input"  id="new_psw" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">确认密码：</label></div>
                    <div class="weui-cell__bd">
                        <input type="password" name="sure_password" class="weui-input" id="confirm_psw" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="weui-btn-area">
                <input class="weui-btn weui-btn_primary" type="submit" name="submit" value="确认修改">
            </div>
        </form>
    </div>
<?php elseif($this->func=='changePayPwd') : ?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('')?>"><i class="iconfont">&#xe603;</i>返回</a>
        <a class="m_header_r"></a>
        <h1>修改支付密码</h1>
    </div>
    <div class="margin_header"></div>
    <div class="m-password">
        <div class="weui-tab m-password__navbar">
            <ul class="weui-navbar">
                <li class="weui-navbar__item <? if($this->func=='changePwd'){echo 'weui-bar__item_on';}?>" data_url='<?=url('password/changePwd')?>'>账户密码</li>
                <li class="weui-navbar__item <? if($this->func=='changePayPwd'){echo 'weui-bar__item_on';}?>" data_url="<?=url('password/changePayPwd')?>">修改支付密码</li>
                <li class="weui-navbar__item <? if($this->func=='getPayPwd'){echo 'weui-bar__item_on';}?>" data_url="<?=url('password/getPayPwd')?>">找回支付密码</li>
            </ul>
        </div>
        <form method="post" name="form2" class="m-password__form" onsubmit="return validateForm()">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">原密码：</label></div>
                    <div class="weui-cell__bd">
                        <input type="password" name="old_password" placeholder="输入原支付密码"  class="weui-input" id="original_psw" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="weui-cells__tips m_tips">初始密码为注册时填写的登陆密码</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">新密码：</label></div>
                    <div class="weui-cell__bd">
                        <input type="password" name="zf_password"  placeholder="长度6至15位"  class="weui-input"  id="new_psw" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">确认密码：</label></div>
                    <div class="weui-cell__bd">
                        <input type="password" name="sure_password" class="weui-input" id="confirm_psw" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="weui-btn-area">
                <input class="weui-btn weui-btn_primary" type="submit" name="submit" value="确认修改">
            </div>
        </form>
    </div>


<?php elseif ($this->func == 'getPayPwd') : ?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('')?>"><i class="iconfont">&#xe603;</i>返回</a>
        <a class="m_header_r"></a>
        <h1>找回支付密码</h1>
    </div>
    <div class="margin_header"></div>
    <div class="m-password">
        <div class="weui-tab m-password__navbar">
            <ul class="weui-navbar">
                <li class="weui-navbar__item <? if($this->func=='changePwd'){echo 'weui-bar__item_on';}?>" data_url='<?=url('password/changePwd')?>'>账户密码</li>
                <li class="weui-navbar__item <? if($this->func=='changePayPwd'){echo 'weui-bar__item_on';}?>" data_url="<?=url('password/changePayPwd')?>">修改支付密码</li>
                <li class="weui-navbar__item <? if($this->func=='getPayPwd'){echo 'weui-bar__item_on';}?>" data_url="<?=url('password/getPayPwd')?>">找回支付密码</li>
            </ul>
        </div>

        <form method="post" name="form2" class="m-password__form" onsubmit="return validateForm3()">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">用户名：</label></div>
                    <div class="weui-cell__bd">
                        <input type="text" name="user_psw"  placeholder="<?=$user->username?>" disabled=""  class="weui-input" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">邮箱地址：</label></div>
                    <div class="weui-cell__bd">
                        <input type="text" name="email_psw" id="emailValidator"  placeholder="<?=$user->email?>" disabled="" class="weui-input" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell weui-cell_vcode">
                    <div class="weui-cell__hd"><label class="weui-label">验证码：</label></div>
                    <div class="weui-cell__bd">
                        <input type="text" name="valicode" id="valicode" placeholder="请输入验证码" class="weui-input" autocomplete="off"/>
                    </div>
                    <div class="weui-cell__ft">
                        <img class="weui-vcode-img" src="/index.php/plugin/code" alt="点击刷新" onClick="this.src='/index.php/plugin/code/?t=' + Math.random();">
                    </div>
                </div>
            </div>
            <div class="weui-btn-area">
                <input class="weui-btn weui-btn_primary" type="submit" value="确认">
            </div>
        </form>
    </div>
<?php elseif ($this->func == 'resetPayPwd') : ?>
    <div class="m_header">
        <a class="m_header_l" href="<?=url('')?>"></a>
        <a class="m_header_r"></a>
        <h1>重置支付密码</h1>
    </div>
    <div class="margin_header"></div>
    <div class="box">
        <div class="m_regtilinde">重置支付密码：</div>
        <? if ($error != '') {
            echo '<div class="alert-warning">' . $error . '</div>';
        } else {
            ?>
            <div class="m-password">
                <form method="post" class="m-password__form">
                    <div class="weui-cells weui-cells_form">
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">新密码：</label></div>
                            <div class="weui-cell__bd">
                                <input type="password" name="zf_password"  placeholder="长度6至15位"  class="weui-input"  autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="weui-cells weui-cells_form">
                        <div class="weui-cell">
                            <div class="weui-cell__hd"><label class="weui-label">确认密码：</label></div>
                            <div class="weui-cell__bd">
                                <input type="password" name="sure_password" class="weui-input" id="confirm_psw" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="weui-btn-area">
                        <input class="weui-btn weui-btn_primary" type="submit" name="submit" value="确认保存">
                    </div>
                </form>
            </div>
        <? } ?>
    </div>
<?php endif;?>
<script>
    $(function(){
        /*navbar切换*/
        $('.weui-navbar__item ').on('click', function () {
            $(this).addClass('weui-bar__item_on').siblings('.weui-bar__item_on').removeClass('weui-bar__item_on');
            var a = $(this).attr("data_url");
            window.location.href=a;
        });
    });
    /*账户密码验证，修改支付密码验证*/
    function validateForm(){
        if(!pwdOriginalValidator()||!pwdValidator()||!pwdRepeatValidator()){
            return false;
        }
        return true;
    }
    //定义验证原始密码的函数
    function pwdOriginalValidator(){
        var value=$("#original_psw").val();
        if(value==""||value==null){
            layer.open({
                content: '原密码不能为空'
                ,skin: 'msg'
                ,time: 2
            });
            return false;
        }else if(value.length<6||value.length>15){
            layer.open({
                content: '密码长度不能小于6大于15'
                ,skin: 'msg'
                ,time: 2
            });
            return false;
        }else{
            return true;
        }
    }
    //定义验证密码的函数
    function pwdValidator(){
        var value=$("#new_psw").val();
        if(value==""||value==null){
            layer.open({
                content: '新密码不能为空'
                ,skin: 'msg'
                ,time: 2
            });
            $("#new_psw").focus();
            return false;
        }else if(value.length<6||value.length>15){
            layer.open({
                content: '密码长度不能小于6大于15'
                ,skin: 'msg'
                ,time: 2
            });
            return false;
        }else{
            return true;
        }
    }
    //定义确认密码验证的函数
    function pwdRepeatValidator(){
        var value=$("#confirm_psw").val();
        var pwd=$("#new_psw").val();
        if(value==""||value==null){
            layer.open({
                content: '确认密码不能为空'
                ,skin: 'msg'
                ,time: 2
            });
            return false;
        }else if(value.length<6||value.length>15){
            layer.open({
                content: '密码长度不能小于6大于15'
                ,skin: 'msg'
                ,time: 2
            });
            return false;
        }else if(value!=pwd){
            layer.open({
                content: '两次密码输入不一致'
                ,skin: 'msg'
                ,time: 2
            });
            return false;
        }else{
            return true;
        }
    }

    /*找回支付密码页面提交验证*/
    function validateForm3(){
        var value=$("#valicode").val();
        if(value==""||value==null){
            layer.open({
                content: '验证码不能为空'
                ,skin: 'msg'
                ,time: 2
            });
            return false;
        }else{
            return true;
        }
    }
</script>
<?php require 'footer_v2.php';?>