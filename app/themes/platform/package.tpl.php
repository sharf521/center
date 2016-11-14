<?php require 'header.php';?>
<fieldset class="layui-elem-field layui-field-title">
    <legend>自购平台</legend>
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
    <h3>订购信息</h3>
    <form method="post" class="layui-form">
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
    window.onload=function(){
        layui.use(['form'], function () {
            var form = layui.form();
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
            form.on('submit(*)', function(data){
                var total=Number($('#span_total').html());
                if(total<=0){
                    layer.msg('请选择套餐', { icon: 2,   time: 5000      });
                    return false;
                }
                if(total>myMoney){
                    layer.msg('您的电子币不足', { icon: 2,   time: 5000      });
                    return false;
                }
            });
        });
    }
</script>
<?php require 'footer.php';?>


