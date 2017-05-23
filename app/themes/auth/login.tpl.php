<?php require 'header.php';?>
<body style="background:url(/themes/default/images/qpbj.jpg) no-repeat center; background-size:cover;">
<div class="mainbox">
    <div class="left_reason fl">
        <div class="link_logo"><img src="<?=$this->site->logo?>" /></div>
    </div>
    <div class="mill loginbox fl">
        <form id="login_form" method="post">
            <h3>会员登录</h3>
            <div class="from_cont">
                <p><input type="text" name="username"  placeholder="手机号/账号"/><b></b></p>
                <p><input type="password" name="password"  placeholder="请输入密码" /><b></b></p>
            </div>
            <p class="tip_most"><span>没有账号？<a href="<?=$regUrl?>">去注册</a></span>  <a href="<?=$getPwdUrl?>" style="float: right; margin-right: 10px;">忘记密码</a></p>
            <p class="smit_btn"><input type="submit" value="登&nbsp;&nbsp;录" /></p>
            <input type="hidden" name="_token"  value="<?=_token();?>"/>
        </form>
    </div>
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
                password: {
                    required: true
                },
            },
            messages: {
                username: {
                    required: '<i class="fa fa-exclamation-circle"></i>手机号或账号',
                },
                password: {
                    required: '<i class="fa fa-exclamation-circle"></i>请填写密码',
                },
            }
        });
    });
</script>
<?php require 'footer.php';?>
