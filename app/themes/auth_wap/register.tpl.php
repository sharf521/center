<?php require 'header.php';?>
<div class="login_contc">
    <div class="m_regtilinde">请认真填写以下信息，完成注册！</div>
    <div class="login_form">
        <form id="add_acon" method="post">
            <ul>

                <li><i class="usename"></i><input name="username" type="text" placeholder="请输入账号"><span></span></li>
                <li><i class="usename"></i><input name="email" type="text" placeholder="请输入邮箱"><span></span></li>
                <li><i class="paswod"></i><input id="field" name="password" type="password" placeholder="请输入密码"><span></span></li>
                <li><i class="paswod"></i><input name="sure_password" type="password" placeholder="请确认密码"><span></span></li>
                <li><i class="tjuser"></i><input type="text" name="invite_user"  placeholder="您的推荐人"></li>
                <li class="lo_subtb"><input type="submit" class="log_sumb" value="注册"></li>
            </ul>
            <input type="hidden" name="_token"  value="<?=_token();?>"/>
        </form>
        <p class="m_alinka"><!--<a href="#">忘记密码？</a>--><a style="float:right;" href="<?=$_url?>">已有帐号，马上登录！</a></p>
    </div>
</div>
<script>
    $(document).ready(function(){

        $('#add_acon').validate({
            onkeyup: false,
            errorPlacement: function(error, element){
                element.nextAll('span').first().after(error);
            },
            submitHandler:function(form){
                ajaxpost('add_acon', '', '', 'onerror');
            },
            rules: {
                username: {
                    required: true
                },
                email: {
                    required: true,
                    email:true,
                },
                password: {
                    required: true,
                    rangelength:[6,12],
                },
                sure_password: {
                    required: true,
                    equalTo:"#field"
                },
            },
            messages: {
                username: {
                    required: '请输入用户名',
                },
                email: {
                    required: '请输入您的常用邮箱',
                    email: '请输入正确格式的邮箱地址',
                },
                password: {
                    required: '请输入密码',
                    rangelength:'密码长度请保持在6-12位之间',
                },
                sure_password: {
                    required: '请确认密码',
                    equalTo:'密码输入不一致'
                },
            }
        });
    });
</script>
<div class="lo_copy">&copy; 远途网 2016</div>
<?php require 'footer.php';?>