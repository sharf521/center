<?php require 'header.php';?>
<?php if($this->func=='index') : ?>
    <body style="background:url(/themes/default/images/qpbj.jpg) no-repeat center; background-size:cover;">
    <div class="mainbox">
        <div class="left_reason fl">
            <div class="link_logo"><img src="<?=$this->site->logo?>" /></div>
        </div>
        <div class="mill loginbox fl">
            <form id="login_form" method="post">
                <h3>找回密码</h3>
                <div class="from_cont">
                    <p><input type="text" name="username"  placeholder="请输入账号"/><b></b></p>
                    <p><input type="text" name="email"  placeholder="请输入邮箱" /><b></b></p>
                </div>
                <p class="tip_most"><span>想起密码？<a href="/login">去登陆</a></span></p>
                <p class="smit_btn"><input type="submit" value="立即找回" /></p>
                <input type="hidden" name="_token"  value="<?=_token();?>"/>
            </form>
        </div>
        <a class="back_link" href="/login">返回登陆</a>
        <p class="clear"></p>
    </div>
    <script src="/plugin/js/jquery.js"></script>
    <script src="/plugin/js/jquery.validation.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#login_form').validate({
                onkeyup: false,
                errorPlacement: function (error, element) {
                    element.nextAll('b').first().after(error);
                },
                submitHandler: function (form) {
                    ajaxpost('login_form', '', '', 'onerror');
                },
                rules: {
                    username: {
                        required: true
                    },
                    email: {
                        required: true,
                        email:true
                    },
                },
                messages: {
                    username: {
                        required: '<i class="fa fa-exclamation-circle"></i>请填写账号'
                    },
                    email:{
                        required: '<i class="fa fa-exclamation-circle"></i>邮箱不能为空',
                        email: '<i class="fa fa-exclamation-circle"></i>请填写正确的邮箱'
                    },
                }
            });
        });
    </script>
<? elseif($this->func=='updatePwd') : ?>
    <body style="background:url(/themes/default/images/qpbj.jpg) no-repeat center; background-size:cover;">
    <div class="mainbox">
        <div class="left_reason fl">
            <div class="link_logo"><img src="<?=$this->site->logo?>" /></div>
        </div>
        <div class="mill loginbox fl">
            <form id="login_form" method="post">
                <h3>重置密码</h3>
                <div class="from_cont">
                    <p><?=$user->username?></p>
                    <p><input type="password" name="password" id="password"  placeholder="请输入新密码"/><b></b></p>
                    <p><input type="password" name="sure_password"  placeholder="请再次输入新密码" /><b></b></p>
                </div>
                <p class="tip_most"><span>想起密码？<a href="/login">去登陆</a></span></p>
                <p class="smit_btn"><input type="submit" value="保存" /></p>
                <input type="hidden" name="_token"  value="<?=_token();?>"/>
            </form>
        </div>
        <a class="back_link" href="/login">返回登陆</a>
        <p class="clear"></p>
    </div>
    <script src="/plugin/js/jquery.js"></script>
    <script src="/plugin/js/jquery.validation.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#login_form').validate({
                onkeyup: false,
                errorPlacement: function (error, element) {
                    element.nextAll('b').first().after(error);
                },
                submitHandler: function (form) {
                    ajaxpost('login_form', '', '', 'onerror');
                },
                rules: {
                    password: {
                        required: true
                    },
                    sure_password: {
                        required: true,
                        equalTo:"#password"
                    },
                },
                messages: {
                    password: {
                        required: '<i class="fa fa-exclamation-circle"></i>请输入新密码'
                    },
                    sure_password:{
                        required: '<i class="fa fa-exclamation-circle"></i>请再次输入新密码',
                        equalTo: '<i class="fa fa-exclamation-circle"></i>两次密码不一致'
                    },
                }
            });
        });
    </script>
    <? endif;?>
<?php require 'footer.php';?>