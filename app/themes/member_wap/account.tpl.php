<?php require 'header.php';?>
<?php if($this->func=='index') : ?>
    <div class="header">
        <a class="header_left" href="<?=url('')?>" ><i class="iconfont">&#xe603;</i>返回</a>
        <span class="header_right">&nbsp;</span>
        <h1>我的资金</h1>
    </div>
    <div class="mode" style="display: none">
        <p>可用资金</p>
        <h3><?=(float)$account->funds_available?></h3>
        <a href="<?= url('account/log'); ?>" class="link_bill">查看记录</a>
    </div>
    <div class="lump" style="margin-top: 10rem">
        <ul>
            <li>可用资金<br/><span><?=(float)$account->funds_available?></span></li>
            <li>冻结资金<br/><span><?=(float)$account->funds_freeze?></span></li>


            <li>可用积分<br/><span><?=(float)$account->integral_available?></span></li>
            <li>冻结积分<br/><span><?=(float)$account->integral_freeze?></span></li>

            <li>周转金<br/><span><?=(float)$account->turnover_available?></span></li>
            <li>周转金额度<br/><span><?=(float)$account->turnover_credit?></span></li>

            <li>保证金<br/><span><?=(float)$account->security_deposit?></span></li>
            <li></li>
        </ul>
        <p class="clear"></p>
    </div>
    <br><br><br><br><br><br><br><br>
    <div class="mode_foot">
        <ul>
            <li><a href="<?=url('account/log')?>">查看记录</a></li>
            <li><a href="<?=url('account/cash')?>">提现</a></li>
        </ul>
    </div>
<? elseif ($this->func=='log') : ?>
    <div class="header">
        <a class="header_left" href="<?=url('account')?>" ><i class="iconfont">&#xe603;</i>返回</a>
        <span class="header_right">&nbsp;</span>
        <h1>资金明细</h1>
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
                    <span class="date"><?=str_replace(' ','<br>',$row->created_at);?></span>
                    <b class="down"></b>
                    <div class="still"><p><?=$row->change?></p></div>
                </div>
                <div class="bill_show">

                    <p><?=$row->getLinkPageName('account_type',$row->type);?></p>
                    <p><span>备注：</span><i><?=nl2br($row->remark)?></i></p>
                </div>
            </div>
        <? endforeach;?>
    </div>
    <!--pagination-->
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
<?php endif;?>
<?php require 'footer.php';?>
