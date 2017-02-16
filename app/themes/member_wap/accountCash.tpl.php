<?php require 'header.php';?>
<?php if($this->func=='cash') :  ?>
    <div class="header">
        <a class="header_left" href="<?=url('account')?>" ><i class="iconfont">&#xe603;</i>返回</a>
        <span class="header_right"><a href="<?= url('account/cashLog'); ?>">提现记录</a></span>
        <h1>我要提现</h1>
    </div>
    <div class="show_box" style="margin-top: 10rem">
        <? if($bank->card_no ==""){?>
            <div class="alert-warning" role="alert">您还没有填写银行账户，请先填写<?=$this->anchor('user/bank','>>银行账户>>');?></div>
        <? }else{?>
            <form method="post" onSubmit="return setdisabled();">
                <table class="table_from">
                    <tr><td>用户名：</td><td><?=$this->username?></td></tr>
                    <tr><td>可提现金额：</td><td><?='￥'.$account->funds_available?></td></tr>
                    <tr><td>姓名：</td><td><?=$this->user->name?></td></tr>
                    <tr><td>提现银行：</td><td><?=$bank->bank?></td></tr>
                    <tr><td>开户支行：</td><td><?=$bank->branch?></td></tr>
                    <tr><td>银行账号：</td><td><?=$bank->card_no?></td></tr>
                    <tr><td>提现金额：</td><td><input  name="total" style="width: 80%" type="text" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/> 元</td></tr>
                    <tr><td>支付密码：</td><td><input  name="zf_password" type="password"/></td></tr>
                    <tr><td colspan="2"><input type="submit" class="submit" value="提交" /></td></tr>
                </table>
            </form>
            <ul class="cash_prompt">
                <h4>温馨提示：</h4>
                <li>1. 单笔提现金额最低50.00元，最高50000.00元。</li>
                <li>2. 单笔提现费率<?=math($cash_rate,100,'*',2)?>%，单笔提现手续费最低5.00元。</li>
                <li>3. 每个工作日的下午5点之前提交的提现申请，T+1个工作日到账，每个工作日的下午5点之后提交的提现申请，T+2个工作日到账。</li>
            </ul>
        <? }?>
    </div>
<? elseif ($this->func=='cashLog') :  ?>
    <div class="header">
        <a class="header_left" href="<?=url('account/cash')?>" ><i class="iconfont">&#xe603;</i>返回</a>
        <span class="header_right">&nbsp;</span>
        <h1>提现记录</h1>
    </div>
    <div class="date_box" style="margin-top: 10rem">
        <form method="get">
            时间： <input type="text" readonly="readonly" name="starttime" id="beginDate" value="<?=$_GET['starttime']?>"/> -
            <input type="text" readonly="readonly" name="endtime" id="endDate" value="<?=$_GET['endtime']?>"/>
            <input type="submit" value="查询"/>
        </form>
        </div>
    <div class="bill_box">
        <? if(empty($result['total'])){
            echo ' <div class="alert-warning" role="alert">无记录！</div>';
        }?>
        <? foreach($result['list'] as $row) : ?>
        <div class="bill_list">
            <div class="bill_top">
                <span class="date"><?=$row->created_at?></span>
                <b class="down"></b>
                <div class="still"><p class="add">￥<?=$row->total?></p><p><?=$row->getLinkPageName('check_status',$row->status)?></p></div>
            </div>
            <div class="bill_show">
                <p><span>手续费：</span><i>￥<?=$row->fee?></i></p>
                <p><span>提现银行：</span><i><?=$row->bank?></i></p>
                <p><span>开户支行：</span><i><?=$row->branch?></i></p>
                <p><span>银行账户：</span><i><?=$row->card_no?></i></p>
                <p><span>审核备注：</span><i><?=$row->verify_remark?></i></p>
                <p><span>打款备注：</span><i><?=$row->remittance_remark?></i></p>
            </div>
        </div>
        <? endforeach;?>
    </div>
    <div class="pag_main">
        <?=$result['page'];?>
    </div>
    <link href="/plugin/date/css/mobiscroll_002.css" rel="stylesheet" type="text/css">
    <link href="/plugin/date/css/mobiscroll.css" rel="stylesheet" type="text/css">
    <link href="/plugin/date/css/mobiscroll_003.css" rel="stylesheet" type="text/css">
    <script src="/plugin/date/js/mobiscroll_002.js" type="text/javascript"></script>
    <script src="/plugin/date/js/mobiscroll_004.js" type="text/javascript"></script>
    <script src="/plugin/date/js/mobiscroll.js" type="text/javascript"></script>
    <script src="/plugin/date/js/mobiscroll_003.js" type="text/javascript"></script>
    <script src="/plugin/date/js/mobiscroll_005.js" type="text/javascript"></script>
    <!--pagination end-->
    <script type="text/javascript">
        $(".bill_list").click(function(){
            $(this).toggleClass("show");
            $(this).siblings(".bill_list").removeClass("show");
        })
    </script>
    <!--date-js-->
    <script type="text/javascript">
        $(function () {
            var currYear = (new Date()).getFullYear();
            var opt={};
            opt.date = {preset : 'date'};
            opt.datetime = {preset : 'datetime'};
            opt.time = {preset : 'time'};
            opt.default = {
                theme: 'android-ics light', //皮肤样式
                display: 'modal', //显示方式
                mode: 'scroller', //日期选择模式
                dateFormat: 'yyyy-mm-dd',
                lang: 'zh',
                showNow: true,
                nowText: "今天",
                startYear: currYear - 10, //开始年份
                endYear: currYear + 10 //结束年份
            };
            $("#beginDate").mobiscroll($.extend(opt['date'], opt['default']));
            $("#endDate").mobiscroll($.extend(opt['date'], opt['default']));
        });
    </script>
<? endif;?>
<?php require 'footer.php';?>