$(function () {
    var layer = layui.layer
        ,util = layui.util
        ,laydate = layui.laydate;
    var form = layui.form();
    util.fixbar();
    layui.element().init();
    //上传文件
    if ($('.layui-upload-file').length>0){
        var index;
        $('.layui-upload-file').each(function(index,obj){
            var id=obj.getAttribute('upload_id');
            var type=obj.getAttribute('upload_type');
            layui.upload({
                url: '/index.php/upload/save?type='+type
                ,elem:obj
                ,before: function(input){
                    index=layer.msg('上传中', {icon: 16,time:1000000});
                }
                ,success: function(res){
                    layer.close(index);
                    if(res.code=='0'){
                        var path=res.url+'?'+Math.random();
                        $('#'+id).val(path);
                        var _str="<a href='"+path+"' target='_blank'><img src='"+path+"' height='50'/></a>";
                        $('#upload_span_'+id).html(_str);
                    }else{
                        alert(res.msg);
                    }
                }
            });
        });
    }
});

function changetype(type) {
    if (type == 1) {
        document.getElementById("formpay").action = "/pay/recharge";
        document.getElementById("formpay").target='_blank';
        document.getElementById("xianxia").style.display = "none";
        document.getElementById("xianxiabz").style.display = "none";
        document.getElementById("xianshang").style.display = "";
        document.getElementById("fenqi").style.display = "none";
    }else if (type == 2) {
        document.getElementById("formpay").action = "";
        document.getElementById("formpay").target='';
        document.getElementById("xianshang").style.display = "none";
        document.getElementById("xianxia").style.display = "";
        document.getElementById("xianxiabz").style.display = "";
        document.getElementById("fenqi").style.display = "none";
    }else if (type == 3) {
        document.getElementById("formpay").action = "/tongPay/recharge";
        document.getElementById("formpay").target='_blank';
        document.getElementById("xianshang").style.display = "none";
        document.getElementById("xianxia").style.display = "none";
        document.getElementById("xianxiabz").style.display = "none";
        document.getElementById("fenqi").style.display = "";
    }
}
function card() {
    if (document.getElementById("money").value == "") {
        layer.alert('充值金额不能为空！', {icon: 2});
        return false;
    }
    if (document.getElementById("type1").checked == true) {
        if (document.getElementById("money").value < 50) {
            layer.alert('充值金额不能小于50元', {icon: 2});
            return false;
        }
        if(document.getElementById("money").value >50000){
            layer.alert('单笔充值金额不能大于5万元', {icon: 2});
            return false;
        }
    }
    if (document.getElementById("type2").checked == true) {
        if (document.getElementById("money").value < 1000) {
            alert("充值金额不能小于1000元");
            return false;
        }
        if (document.getElementById("remark").value == "") {
            alert("充值备注不能为空!");
            return false;
        }
    }
    if (document.getElementById("type3").checked == true) {
        if (document.getElementById("money").value < 1000) {
            alert("充值金额不能小于1000元");
            return false;
        }
        if(document.getElementById("money").value >50000){
            layer.alert('单笔金额不能大于5万元', {icon: 2});
            return false;
        }
    }
    if (document.getElementById("valicode").value == "") {
        alert("验证码不能为空!");
        return false;
    }
    return true;
}

//上传图片
function upload_image(id,type)
{
    $('#upload_span_'+id).html('上传中...');
    $.ajaxFileUpload({
        //url:'/index.php/plugin/ajaxFileUpload?type='+type,
        url:'/index.php/upload/save?type='+type,
        fileElementId :'upload_'+id,
        dataType:'json',
        success: function (result,status){
            if(result.code == '0'){
                var path=result.url+'?'+Math.random();
                $('#'+id).val(path);
                var _str="<a href='"+path+"' target='_blank'><img src='"+path+"' height='50'/></a>";
                $('#upload_span_'+id).html(_str);
            }else{
                alert(result.msg);
            }
        },
        error: function (result, status, e){
            alert(e);
        }
    });
    return false;
}

//userInfo   start
function changeProvince(value)
{
    document.getElementById('province').value=value;
    var sel=document.getElementById('city');
    if(value!='0')
    {
        changeSel(sel,value);
    }
    else
    {
        sel.options.length=0;
    }
    document.getElementById('county').options.length=0;
}
function changeCity(value)
{
    document.getElementById('city').value=value;
    var sel=document.getElementById('county');
    changeSel(sel,value);
}
function changeSel(sel,id)
{
    $.post("/index.php/ajax/region_substring/"+id,{},function(str){
        var arr =str.split("[#]");
        sel.options.length=0;
        sel.options.add(new Option('请选择','0'));
        for(v in arr)
        {
            var v=arr[v].split("::");
            if(v[0]!='')
                sel.options.add(new Option(v[1],v[0]));
        }
    });
}
//userInfo   end


function orderPayJs() {
    $(function () {
        $("#integral").bind('input propertychange',function(){
            if(Number($(this).val())>Number($('#span_integral').html())){
                $(this).val($('#span_integral').html());
            }
            var max_jf=Math.mul(price_true,lv);
            if(Number($(this).val())>max_jf){
                $("#integral").val(max_jf);
            }
            var _m=Math.div(Number($("#integral").val()),lv);
            var money=Math.sub(price_true,Math.moneyRound(_m,2));
            $('#money_yes').html(money);
        });
        /*$('.recharge').on('click',function () {
         var center_url=$(this).attr('center_url');
         var money=$('#money_yes').html();
         goCenterRecharge(center_url,money);
         });   */
    });
}


function partner_js() {
    $(function () {
        layui.form('select').render();
        layui.form().on('select(sel1)', function(data){
            price_true=data.value;
            $('#money_yes').html(data.value);
            $('#integral').val(0);
        });
        layui.form().on('submit(*)', function(data){
            var form=data.form;
            var fields=data.field;
            var partner_type=$(form).find('select[name=partner_type]');
            if(partner_type.val()==''){
                layer.tips('不能为空！', $(partner_type).next('.layui-form-select'));
                return false;
            }
            var zf_password=$(form).find('input[name=zf_password]');
            if(zf_password.val()==''){
                layer.tips('不能为空！', zf_password);
                zf_password.focus();
                return false;
            }
        });
        $('#integral').keyup(function () {
            if(Number($(this).val())>Number($('#span_integral').html())){
                $(this).val($('#span_integral').html());
            }
            var max_jf=Math.mul(price_true,lv);
            if(Number($(this).val())>max_jf){
                $("#integral").val(max_jf);
            }
            var _m=Math.div(Number($("#integral").val()),lv);
            var money=Math.sub(price_true,Math.moneyRound(_m,2));
            $('#money_yes').html(money);
        });
    });
}