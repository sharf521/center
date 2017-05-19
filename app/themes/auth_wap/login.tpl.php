<?php require 'header.php';?>
<div class="login_contc">
    <div class="logoslog"><img src="<?=$this->site->logo?>"></div>
    <div class="login_form">
        <form id="login_form" method="post">
        <ul>
            <li><i class="tjuser"></i><input type="text" name="username" placeholder="帐号"></li>
            <li><i class="paswod"></i><input type="password" name="password" placeholder="密码"></li>
            <li class="lo_subtb"><input type="submit" class="log_sumb" value="登录"></li>
        </ul>
        <p class="m_alinka"><a href="<?=$getPwdUrl?>">忘记密码？</a><a style="float:right;" href="<?=$regUrl?>">新用户注册</a></p>
            <input type="hidden" name="_token"  value="<?=_token();?>"/>
        </form>
    </div>
</div>
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
                        required: '请填写账号'
                    },
                    password: {
                        required: '请填写密码'
                    },
                }
            });
        });
    </script>
    <div class="lo_copy">&copy; <?=$this->site->name?> 2016</div>
<?php require 'footer.php';?>