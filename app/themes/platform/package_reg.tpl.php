<?php require 'header.php';?>
<fieldset class="layui-elem-field layui-field-title">
    <legend>注册购物</legend>
</fieldset>
<div class="package_box">
    <h3>产品列表</h3>
    <?  foreach($packages as $package) :  ?>
        <div class="package-li">
            <div class="package-name"><label><input type="checkbox" id="chkbox_<?=$package->id?>" value="<?=$package->id?>"><?=$package->name?></label></div>
            <div class="package-content clearFix" style="width: 500px; border: 1px solid #ccc">
                <div style="float: left;">
                    <img src="<?=$package->picture?>" width="150">
                </div>
                <table class="table-package" width="350">
                    <tr><td>名称：</td><td><?=$package->name?></td></tr>
                    <tr><td>价格：</td><td><?=$package->money?></td></tr>
                    <tr><td>折扣：</td><td><?=math($package->discount,100,'*',2)?> %(价格乘以百分比)</td></tr>
                    <tr><td>规格：</td><td><?=$package->title?></td></tr>
                    <tr><td>说明：</td><td><?=nl2br($package->remark)?></td></tr>
                </table>
            </div>
        </div>
    <? endforeach; ?>
</div>

<div class="table_order">
    <div style="line-height: 30px;"><?=$this->username?> 当前电子币：<?=$teaMoney->money?></div>
    <form method="post" class="layui-form">
        <input type="hidden" id="regType" value="1" name="regType">
        <div class="layui-tab layui-tab-card" lay-filter="tab1">
            <ul class="layui-tab-title">
                <li class="layui-this">注册新用户</li>
                <li>平台己注册用户</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">会员ID号</label>
                            <div class="layui-input-inline">
                                <input type="text" name="username" id="username" placeholder="请填写用户名" class="layui-input" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">email</label>
                            <div class="layui-input-inline">
                                <input type="text" name="email" id="email" placeholder="请输入邮箱" class="layui-input" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">设置密码</label>
                            <div class="layui-input-inline">
                                <input type="password" id="password" name="password" placeholder="请输入密码" class="layui-input"  autocomplete="off"/>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">确认密码</label>
                            <div class="layui-input-inline">
                                <input type="password" id="sure_password" name="sure_password" placeholder="确认密码" class="layui-input"  autocomplete="off"/>
                            </div>
                        </div>
                        <div style="padding-left: 100px;">登陆密码和支付密码初始值一样</div>
                    </div>
                </div>
                <div class="layui-tab-item">
                    <div class="layui-form-item">
                        <label class="layui-form-label">会员ID号</label>
                        <div class="layui-input-inline">
                            <input type="text" name="username2" id="username2" placeholder="请填写用户名" class="layui-input" autocomplete="off"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <fieldset class="layui-elem-field layui-field-title">
            <legend>订购信息</legend>
        </fieldset>
        <div style="border: 1px solid #ccc;">
            <div style="min-height: 100px;">
                <table id="table_order" width="100%">
                    <tr style="background-color: #efefef; line-height: 25px;"><td align="center">序号</td><td>商品名称</td><td>商品价格</td><td>商品折扣</td><td>折后价</td><td>数量</td><td>操作</td></tr>
                </table>
            </div>
            <div class="table_order_total">
                共(<span id="span_sum">0</span>)种产品,  购物总金额：<span id="span_total">0</span>
            </div>
        </div>


        <br><br>
        <fieldset class="layui-elem-field">
            <legend>填写收件人</legend>
            <div class="layui-field-box">
                <div class="layui-form-item">
                    <label class="layui-form-label">姓名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="contacts" placeholder="请填写收件人姓名" class="layui-input" lay-verify="required" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">电话</label>
                    <div class="layui-input-inline">
                        <input type="text" name="phone" placeholder="请填写收件人电话" class="layui-input" lay-verify="required" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">收件地址</label>
                    <div class="layui-input-inline"><select id="province" name="province" lay-filter="province" lay-verify="required"></select></div>
                    <div class="layui-input-inline"><select id="city" name="city" lay-filter="city"  lay-verify="required"></select></div>
                    <div class="layui-input-inline"> <select id="area" name="area" lay-filter="area" lay-verify="required"></select></div>

                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <input type="text" name="address" placeholder="请填写详细地址" class="layui-input" lay-verify="required" autocomplete="off"/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">邮编</label>
                    <div class="layui-input-inline">
                        <input type="text" name="zipcode" placeholder="请填写邮编" class="layui-input" lay-verify="required" autocomplete="off"/>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">支付密码</label>
            <div class="layui-input-inline">
                <input type="password" name="zf_password" placeholder="请输入支付密码" class="layui-input" lay-verify="required" autocomplete="off"/>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="*">确认购买</button>
            </div>
        </div>
    </form>
</div>

<script src="/plugin/js/PCASClass.js"></script>
<script type="text/javascript">
    var myMoney=<?=$teaMoney->money?>;
    var mypcas=new PCAS("province,请选择省份","city,请选择城市","area,请选择地区");
    $(document).ready(function(){
        layui.use(['form','element'], function () {
            var form = layui.form();
            var element = layui.element();

            form.render('select');
            form.on('select(province)', function (data) {
                province = data.value;
                mypcas.SetValue(data.value, "", "");
                form.render('select');
            });
            form.on('select(city)', function (data) {
                city = data.value;
                mypcas.SetValue(province, data.value, "");
                form.render('select');
            });
            form.on('select(area)', function (data) {
                mypcas.SetValue(province, city, data.value);
                form.render('select');
            });

            element.on('tab(tab1)', function(data){
                if(data.index==0){
                    $('#regType').val(1);
                }else{
                    $('#regType').val(2);
                }
            });

            form.on('submit(*)', function(data){
                var fields=data.field;
                var total=Number($('#span_total').html());
                if(total<=0){
                    layer.msg('请选择套餐', { icon: 2,   time: 5000      });
                    return false;
                }
                if(total>myMoney){
                    layer.msg('您的电子币不足', { icon: 2,   time: 5000      });
                    return false;
                }

                $.ajaxSetup({async: false});
                if($('#regType').val()==1){
                    if(fields.password=='' || fields.sure_password==''){
                        layer.msg('密码不能为空', { icon: 2,   time: 5000      });
                        $('#password').focus();
                        return false;
                    }
                    if(fields.sure_password != fields.password){
                        layer.msg('两次密码不一致', { icon: 2,   time: 5000      });
                        $('#password').focus();
                        return false;
                    }
                    var t_user=false;
                    $.get('/index.php/register/checkUserName/?username='+fields.username,function(result){
                        if(result=='true'){
                            t_user=true;
                        }else{
                            layer.msg('该用户名不可用', { icon: 2,   time: 5000      });
                            $('#username').focus();
                        }
                    });
                    var t_email=false;
                    $.get('/index.php/register/checkEmail/?email='+fields.email,function(result){
                        if(result=='true'){
                            t_email=true;
                        }else{
                            layer.msg('该邮箱不可用', { icon: 2,   time: 5000      });
                            $('#email').focus();
                        }
                    });
                    if(t_user && t_email){
                        return true;
                    }
                }else{
                    if(fields.username2==''){
                        layer.msg('会员ID号不能为空', { icon: 2,   time: 5000      });
                        $('#username2').focus();
                        return false;
                    }
                    var t_user=false;
                    $.get('/index.php/register/checkInviteUser/?invite_user='+fields.username2,function(result){
                        if(result=='true'){
                            t_user=true;
                        }else{
                            layer.msg('该用户名不存在', { icon: 2,   time: 5000      });
                            $('#username2').focus();
                            return false;
                        }
                    });
                    if(t_user){
                        return true;
                    }
                }
                return false;
            });
        });
    });
</script>
<?php require 'footer.php';?>