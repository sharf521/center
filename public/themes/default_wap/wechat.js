function wechat_recharge() {
    var href=window.location.href;
    href=href.replace('&money='+money,'');
    $(function () {
        $('.prices span').on('click',function () {
            var m=Number($(this).html());
            window.location=href+'&money='+m;
        });

        $('.btn_other').on('click',function () {
            $('.weui-mask').show();
            $('.div_bot').show();
            $('.div_bot').slideDown(150);
        });
        $('.div_bot .bot_btn2').on('click',function () {
            $('.weui-mask').hide();
            $('.div_bot').hide();
            $('.div_bot').slideUp(150);
        });
        $('.div_bot .bot_btn1').on('click',function () {
            var inp=$('.div_bot').find("input[name='money']");
            var m=Number(inp.val());
            if(m<0 || parseFloat(m)==0){
                layer.open({
                    content: '请输入正确的金额！'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
                $(inp).focus();
                return ;
            }
            window.location=href+'&money='+m;
        });
    });
}
