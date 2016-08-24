<?php require 'header.php';?>
<body style="background:url(/themes/default/images/qpbj.jpg) no-repeat center; background-size:cover;">
<div class="mainbox">
    <div class="left_reason fl">
        <div class="link_logo"><img src="/themes/default/images/link.png" /></div>
    </div>
    <div class="mill loginbox fl">
        <form id="login_form" method="post">
            <h3>会员登录</h3>
            <div class="from_cont">
                <p><input type="text" name="username"  placeholder="请输入账号"/><b></b></p>
                <p><input type="password" name="password"  placeholder="请输入密码" /><b></b></p>
            </div>
            <p class="tip_most"><span>没有账号？<a href="<?=$_url?>">去注册</a></span></p>
            <p class="smit_btn"><input type="submit" value="登&nbsp;&nbsp;录" /></p>
            <input type="hidden" name="_token"  value="<?=_token();?>"/>
        </form>
    </div>
    <a class="back_link" href="<?=$_url?>">返回注册页</a>
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
                    required: true,
                    minlength: 6,
                    maxlength: 15
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 15
                },
            },
            messages: {
                username: {
                    required: '<i class="fa fa-exclamation-circle"></i>请填写账号',
                    minlength: '<i class="fa fa-exclamation-circle"></i>长度6至15位',
                    maxlength: '<i class="fa fa-exclamation-circle"></i>长度6至15位'
                },
                password: {
                    required: '<i class="fa fa-exclamation-circle"></i>请填写密码',
                    minlength: '<i class="fa fa-exclamation-circle"></i>长度6至15位',
                    maxlength: '<i class="fa fa-exclamation-circle"></i>长度6至15位'
                },
            }
        });
    });
</script>
<?php require 'footer.php';?>
