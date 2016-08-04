//头部个人中心
$(".user_cont").mousemove(function(){
	$(this).addClass("open");
});
$(".user_cont").mouseout(function(){
	$(this).removeClass("open");
});

//左侧菜单
$(".clkopen").click(function(){
	if($(this).parent("li").hasClass("open"))
	    {
			$(this).parent("li").removeClass("open").children(".submenu").slideUp(500);
		}
		else{
			$(".menus_left li").removeClass("open").children(".submenu").slideUp(500);
			$(this).parent("li").addClass("open").children(".submenu").slideDown(500);
		}
});

//会员详情
	$(".contab_tit ul li").click(function(){
	   var n=$(this).index();
       $(this).addClass("currs").siblings().removeClass("currs");
	   $(".congt_main .content_tab").eq(n).fadeIn(800).siblings().hide();
	})
	

//企业认证
   function check(){
		var fruit = document.getElementById("RadioGroup1_2");
		if(fruit.checked ==true){
			$(".nocheckd").show(500)
		}else{
			$(".nocheckd").hide()
		}
   };








