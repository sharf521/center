<?php require 'header.php';?>
<div class="login_contc">
    <div class="logoslog"><img src="/themes/default_wap/images/m_logo.png"></div>
    <div class="login_form">
        <form id="login_form" method="post">
        <ul>
            <li><i class="tjuser"></i><input type="text" name="username" placeholder="帐号"></li>
            <li><i class="paswod"></i><input type="password" name="password" placeholder="密码"></li>
            <li class="lo_subtb"><input type="submit" class="log_sumb" value="登录"></li>
        </ul>
        <p class="m_alinka"><!--<a href="#">忘记密码？</a>--><a style="float:right;" href="<?=$_url?>">新用户注册</a></p>
            <input type="hidden" name="_token"  value="<?=_token();?>"/>
        </form>
    </div>
</div>
<div class="lo_copy">&copy; 远途网 2016</div>
<?php require 'footer.php';?>