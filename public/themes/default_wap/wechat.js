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
            var m=Number($(this).prev("input[name='money']").val());
            window.location=href+'&money='+m;
        });
    });
}
