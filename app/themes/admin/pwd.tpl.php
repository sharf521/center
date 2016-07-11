<?php require 'header.php';
if ($this->func == 'index') {
    ?>
    <div class="main_title">
        <span>修改密码</span>
    </div>
    <form method="post" onsubmit="return setdisabled();">
        <table class="table">
            <tr>
                <td  width="200" class="fr">原密码：</td>
                <td class="fl"><input type="password" name="old_password"/></td>
            </tr>
            <tr>
                <td  class="fr">新密码：</td>
                <td class="fl"><input type="password" name="password"/> 密码长度6位到15位</td>
            </tr>
            <tr>
                <td  class="fr">确认新密码：</td>
                <td class="fl"><input type="password" name="sure_password"/></td>
            </tr>
            <tr>
                <td></td>
                <td class="fl"><input class="but3" value="保存" type="submit"/></td>
            </tr>
        </table>
    </form>
<? } ?>