<?php require 'header.php';?>
<?php if($this->func=='index') : ?>
    <div class="login_contc">
        <div class="m_regtilinde">找回密码</div>
        <br><br><br>
        <div class="login_form">
            <form id="login_form" method="post">
                <ul>
                    <li><i class="tjuser"></i><input type="text" name="username" placeholder="用户名"></li>
                    <li><i class="usename"></i><input type="text" name="email" placeholder="邮箱"></li>
                    <li class="lo_subtb"><input type="submit" class="log_sumb" value="立即找回"></li>
                </ul>
                <p class="m_alinka"><a style="float:right;" href="<?=$loginUrl?>">去登陆</a></p>
                <input type="hidden" name="_token"  value="<?=_token();?>"/>
            </form>
        </div>
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

    <div class="login_contc">
        <div class="m_regtilinde"><?=$user->username?>：重置密码</div>
        <br><br><br>
        <div class="login_form">
            <form id="login_form" method="post">
                <ul>
                    <li><i class="paswod"></i><input type="password" name="password" id="password" placeholder="新密码"></li>
                    <li><i class="paswod"></i><input type="password" name="sure_password" placeholder="确认新密码"></li>
                    <li class="lo_subtb"><input type="submit" class="log_sumb" value="保存"></li>
                </ul>
                <p class="m_alinka"><a style="float:right;" href="/login">去登陆</a></p>
                <input type="hidden" name="_token"  value="<?=_token();?>"/>
            </form>
        </div>
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
    <div class="lo_copy">&copy; <?=$this->site->name?> 2016</div>
<?php require 'footer.php';?>