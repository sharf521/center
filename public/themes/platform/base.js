/**
 * Created by Administrator on 2016/11/2.
 */

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
