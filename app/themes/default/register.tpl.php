<?php require 'header.php';?>
<body style="background:url(/themes/default/images/qpbj.jpg) no-repeat center; background-size:cover;">
<div class="mainbox">
    <div class="left_reason fl">
        <div class="link_logo"><img src="<?=$this->site->logo?>"/></div>
    </div>
    <div class="mill loginbox fl">
        <form id="login_form" method="post">
            <h3>会员注册</h3>
            <div class="from_cont">
                <p><input type="text" name="username"  placeholder="请输入手机号"/><b></b></p>
                <p><input type="text" name="email"  placeholder="请输入邮箱"/><b></b></p>
                <p><input type="text" name="invite_user"  placeholder="推荐人(可不填)"/><b></b></p>
                <p><input type="password" name="password"  id="field" placeholder="请输入密码" /><b></b></p>
                <p><input type="password" name="sure_password"  placeholder="确认密码"/><b></b></p>
            </div>
            <p class="tip_most"><span>没有账号？<a href="<?=$loginUrl?>">去登陆</a></span></p>
            <p class="smit_btn"><input type="submit" value="登&nbsp;&nbsp;录" /></p>
            <input type="hidden" name="_token"  value="<?=_token();?>"/>
        </form>
    </div>
    <a class="back_link" href="/login">返回登陆页</a>
    <p class="clear"></p>
</div>
<script src="/plugin/js/jquery.js"></script>
<script src="/plugin/js/jquery.validation.min.js"></script>
<script>
    jQuery.validator.addMethod("isPhone", function(value, element) {
        var length = value.length;
        var mobile = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/;
        return this.optional(element) || (length == 11 && mobile.test(value));
    }, "请填写正确的手机号码");//可以自定义默认提示信息
    $(document).ready(function(){
        $('#login_form').validate({
            onkeyup: false,
            errorPlacement: function(error, element){
                element.nextAll('b').first().after(error);
            },
            submitHandler:function(form){
                ajaxpost('login_form', '', '', 'onerror');
            },
            rules: {
                username: {
                    required: true,
                    isPhone:true,
                    remote:"/index.php/register/checkUserName/"
                },
                email: {
                    required: true,
                    email:true,
                    remote:"/index.php/register/checkEmail/"
                },
                invite_user:{
                    remote:{
                        url:"/index.php/register/checkInviteUser/",
                        data: {
                            'invite_user': function(){
                                return $('input[name="invite_user"]').val();
                            },
                            'appid':'<?=$_GET['appid']?>'
                        }
                    }
                },
                password: {
                    required: true,
                    rangelength:[6,15]
                },
                sure_password:{
                    required: true,
                    equalTo: "#field"
                }
            },
            messages: {
                username: {
                    required: '<i class="fa fa-exclamation-circle"></i>不能为空',
                    isPhone:'<i class="fa fa-exclamation-circle"></i>请填写正确的手机号码',
                    remote:'<i class="fa fa-exclamation-circle"></i>己存在'
                },
                email:{
                    required: '<i class="fa fa-exclamation-circle"></i>邮箱不能为空',
                    email: '<i class="fa fa-exclamation-circle"></i>请填写正确的邮箱',
                    remote:'<i class="fa fa-exclamation-circle"></i>该邮箱不可用'
                },
                invite_user:{
                    remote:'<i class="fa fa-exclamation-circle"></i>推荐人不存在'
                },
                password: {
                    required: '<i class="fa fa-exclamation-circle"></i>请填写密码',
                    rangelength: '<i class="fa fa-exclamation-circle"></i>长度6至15位',
                },
                sure_password:{
                    required: '<i class="fa fa-exclamation-circle"></i>请确认密码',
                    equalTo: '<i class="fa fa-exclamation-circle"></i>两次输入密码不一致',
                }
            }
        });
    });
</script>
<?php require 'footer.php';?>