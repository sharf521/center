<?php require 'header.php';?>
    <nav>
        <span><a href="javascript:history.go(-1)"><img src="../images/icon_right_hui.png" /></a></span>
        <p>首页</p>
    </nav>
    <div class="m_regtilinde">积分兑换</div>
    <div class="ca_d_table">
        <form id="exchange">
            <table width="100%">
                <tbody>
                <tr>
                    <td align="right" style="width:12rem;">兑换类型：</td>
                    <td><label><input type="radio" name="exchange" checked /><span>兑换现金</span></label></td>
                    <td></td>
                </tr>
                <tr>
                    <td align="right" style="width:12rem;">剩余积分：</td>
                    <td colspan="2">20000</td>
                </tr>
                <tr>
                    <td align="right" style="width:12rem;">兑换积分：</td>
                    <td colspan="2"><input type="text" class="nam_inpou" name="integral" /><b></b></td>
                </tr>
                <tr>
                    <td colspan="3" align="center"><input class="cada_tba" type="submit" value="立即兑换"></td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
    <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="../js/jquery.validation.min.js"></script>
    <script>
        $(document).ready(function(){

            $('#exchange').validate({
                onkeyup: false,
                errorPlacement: function(error, element){
                    element.nextAll('b').first().after(error);
                },
                submitHandler:function(form){
                    ajaxpost('add_acon', '', '', 'onerror');
                },
                rules: {
                    integral: {
                        required: true,
                        number: true,
                        min: 0,
                        max:1000
                    },
                },
                messages: {
                    integral: {
                        required: '请输入要兑换的积分',
                        number:'请输入大于0的数字',
                        min:'请输入大于0的数字',
                        max:'您的积分不足'
                    },
                }
            });
        });
    </script>
<?php require 'footer.php';?>