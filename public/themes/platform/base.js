
layui.use(['layer', 'util', 'laydate','form'], function(){
    var layer = layui.layer
        ,util = layui.util
        ,laydate = layui.laydate;
    var form = layui.form();
    util.fixbar();

    //上传文件
    if ($('.layui-upload-file').length>0){
        layui.use(['upload'], function(){
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
        });
    }
});

$(document).ready(function(){
    $('.package-name').each(function(i){
        $(this).mouseenter(function(){
            $(this).next('.package-content').show();
        });
        $(this).mouseleave(function(){
            $(this).next('.package-content').hide();
        });

        var chk=$(this).find('input');
        chk.click(function(){
                if(chk.is(':checked')){
                    $.getJSON("/platform/package/get/?id="+chk.val(),function(data,status){
                        var tem="<tr class='table_order_tr'><td align='center'></td>" +
                            "<td><input type='hidden' name='id[]' value='"+data.id+"'>"+data.name+"</td>" +
                            "<td>"+data.money+"</td>" +
                            "<td>"+data.discount_show+"</td>" +
                            "<td>"+data.money_dis+"</td>" +
                            "<td><input type='text' name='num[]' size='4' value='1' class='txt_num' oninput='packageChange()' onkeyup=\"value=value.replace(/[^0-9]/g,'')\"></td>" +
                            "<td><span class='hand' onclick='delPack("+data.id+",this)'>删除</span></td></tr>";
                        $('#table_order tr:last').after(tem);
                        packageChange();
                    });
                    chk.attr('disabled',true);
                    chk.parent().css('color','rgb(241, 105, 17)');
                }
            }
        );
    });
});
function delPack(id,o)
{
    $('#chkbox_'+id).attr('disabled',false);
    $('#chkbox_'+id).attr('checked',false);
    $('#chkbox_'+id).parent().css('color','');
    $(o).parent().parent().remove();
    packageChange();
}

function packageChange()
{
    var total=0;
    $('.table_order_tr').each(function(i){
        $(this).find('td:first').html(i+1);
        var money=$(this).find('td:eq(4)').html();
        var num=$(this).find('.txt_num').val();
        total=total + Number(money) * Number(num);
    });
    $('#span_sum').html($('.table_order_tr').length);
    $('#span_total').html(total);
}
