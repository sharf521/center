<html>
<head>
    <title>用户信息管理系统</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="/themes/admin/css/base.css">
    <script language="javascript" src="/plugin/js/jquery.js"></script>
    <script charset="utf-8" src="/plugin/layer/layer.js"></script>
    <style type="text/css">
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
            overflow: hidden;
        }
        .STYLE3 {
            font-size: 12px;
            color: #adc9d9;
        }
    </style>
</head>
<body onload="self.status='欢迎您的光临';">
<form name="form1" method="post">
    <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td bgcolor="#1075b1">&nbsp;</td>
        </tr>
        <tr>
            <td height="608" background="/themes/platform/images/login_031.gif">
                <table width="847" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td height="318" background="/themes/platform/images/login_04.jpg">&nbsp;</td>
                    </tr>

                    <tr>
                        <td height="84">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="381" height="84" background="/themes/platform/images/login_061.gif">
                                        &nbsp;</td>
                                    <td width="162" valign="middle" background="/themes/platform/images/login_071.gif">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="60" height="24">
                                                    <div align="right"><span class="STYLE3">会员ID</span></div>
                                                </td>
                                                <td width="10" valign="bottom">&nbsp;</td>
                                                <td height="24" colspan="2" >
                                                    <div align="left">
                                                        <input type="text" name="username" id="username"
                                                               style="width:103px; height:20px; background-color:#BED6E2; border:solid 1px #153966; font-size:12px; color:#283439; ">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="24" >
                                                    <div align="right"><span class="STYLE3">密码</span></div>
                                                </td>
                                                <td width="10" valign="bottom">&nbsp;</td>
                                                <td height="24" colspan="2" valign="bottom"><input type="password"
                                                                                                   name="password"
                                                                                                   style="width:103px; height:20px; background-color:#BED6E2; border:solid 1px #153966; font-size:12px; color:#283439; ">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="24" valign="bottom">
                                                    <div align="right"><span class="STYLE3">验证码</span></div>
                                                </td>
                                                <td width="10" valign="bottom">&nbsp;</td>
                                                <td width="52" height="24" valign="bottom"><input type="text"
                                                                                                  name="valicode"
                                                                                                  style="width:44px; height:20px; background-color:#BED6E2; border:solid 1px #153966; font-size:12px; color:#283439; ">
                                                </td>
                                                <td width="62" valign="bottom">
                                                    <div align="left"><img src="/plugin/code/" alt="点击刷新" onClick="this.src='/plugin/code/?t=' + Math.random();"
                                                                           align="absmiddle" style="cursor:pointer"/></div>
                                                </td>
                                            </tr>
                                            <tr></tr>
                                        </table>
                                    </td>
                                    <td width="26"><img src="/themes/platform/images/login_081.gif" width="26"
                                                        height="84"></td>
                                    <td width="67" background="/themes/platform/images/login_09.gif">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td height="25">
                                                    <div align="center"><input type="image"
                                                                               src="/themes/platform/images/dl.gif"
                                                                               name="submit" onclick="return ok();"
                                                                               width="57" height="20"/></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="25">
                                                    <div align="center"><img src="/themes/platform/images/cz.gif"
                                                                             width="57" height="20"></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="211" background="/themes/platform/images/login_10.gif">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td height="206" background="/themes/platform/images/login_11.gif" align="center"
                            style="color:#fff;font-size:12px;">&nbsp;建议您试用1280*1024及以上的分辨率浏览
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#152753">&nbsp;</td>
        </tr>
    </table>
</form>
<script language="javascript">
    if (self != window.top) {
        window.top.location.href = '<?=url('login')?>';
    }
    document.getElementById('username').focus();
</script>
<?php require 'footer.php'; ?>