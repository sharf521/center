function changetype(type) {
    if (type == 1) {
        document.getElementById("formpay").action = "/pay/recharge";
        document.getElementById("formpay").target='_blank';
        document.getElementById("xianxia").style.display = "none";
        document.getElementById("xianxiabz").style.display = "none";
        document.getElementById("xianshang").style.display = "";
    }
    if (type == 2) {
        document.getElementById("formpay").action = "";
        document.getElementById("formpay").target='';
        document.getElementById("xianshang").style.display = "none";
        document.getElementById("xianxia").style.display = "";
        document.getElementById("xianxiabz").style.display = "";
    }
}
function card() {
    if (document.getElementById("money").value == "") {
        alert("充值金额不能为空!");
        return false;
    }
    if (document.getElementById("type1").checked == true) {
        if (document.getElementById("money").value < 50) {
            alert("充值金额不能小于50元");
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
        url:'/index.php/plugin/ajaxFileUpload?type='+type,
        fileElementId :'upload_'+id,
        dataType:'json',
        success: function (result,status){
            if(result.status == 'success'){
                var path=result.data+'?'+Math.random();
                $('#'+id).val(path);
                var _str="<a href='"+path+"' target='_blank'><img src='"+path+"' height='50'/></a>";
                $('#upload_span_'+id).html(_str);
            }else{
                alert(result.data);
            }
        },
        error: function (result, status, e){
            alert(e);
        }
    });
    return false;
}
