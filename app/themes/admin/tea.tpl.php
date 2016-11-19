<?php
require 'header.php';
if($this->func=='index')
{
    ?>
    <div class="main_title">
        <span>管理</span>列表
        <?=$this->anchor('tea/add','新增','class="but1"');?>
        <a href="<?=url('tea/userTree')?>" target="_blank" class="but1">查看用户结构图</a>
    </div>

    <form method="get">
        <div class="search">
            用户ID：<input type="text" size="10" name="user_id" value="<?=$_GET['user_id']?>">&nbsp;&nbsp;
            TeaID：<input type="text" size="10" name="id" value="<?=$_GET['id']?>">&nbsp;&nbsp;
            小组ID：<input type="text" size="10" name="group_id" value="<?=$_GET['group_id']?>">&nbsp;&nbsp;
            推荐人uID:<input type="text" size="10" name="invite_uid" value="<?=$_GET['invite_uid']?>">&nbsp;&nbsp;
            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <table class="table">
        <tr class="bt">
            <th>ID</th>
            <th>用户ID</th>
            <th>金额</th>
            <th>上层id</th>
            <th>路径</th>
            <th>组ID号</th>
            <th>推荐人</th>
            <th>推荐PATH</th>
            <th>推荐个数</th>
            <th>状态</th>
            <th>添加时间</th>
        </tr>
        <?
        $arr_status=array('未计算','正常','无效，己分组');
        foreach($result['list'] as $row)
        {
            ?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=$row->user_id?>/<?=$row->User()->username?></td>
                <td><?=(float)$row->money?></td>
                <td><?=$row->pid?></td>
                <td class="l"><?=str_replace(',','->',rtrim($row->pids,','))?></td>
                <td><?=$row->group_id?></td>
                <td><?=$row->invite_id?></td>
                <td><?=$row->invite_path?></td>
                <td><?=$row->invite_count?></td>
                <td><?=$arr_status[$row->status]?></td>
                <td><?=$row->created_at?></td>
            </tr>
        <? }?>
    </table>
    <? if(empty($result['total'])){echo "无记录！";}else{echo $result['page'];}?>
    <script src="/plugin/echarts/echarts-all.js"></script>
    <script src="/themes/admin/js/tea.js"></script>
    <div style="float: left;width: 49%">
        <?php foreach($groups as $group){
            $subtext="组长：{$group->leader}";
            if($group->level==1){
                $chartTitle="消费组：";
                $chartTitle.="{$group->id}";
                if($group->status==2){
                    $chartTitle.='（无效，己重新分组）';
                }
                echo "<div id='div_chart_{$group->id}' style='height:200px; border: 1px solid #ccc;width: 90%;'></div>";
                echo "<script>drawChart('{$chartTitle}','{$subtext}','div_chart_{$group->id}',[{$group->datas}]);</script>";
            }
        }
        ?>
    </div>
    <div style="float: right;width: 49%">
        <?php
        foreach($groups as $group){
            $subtext="组长：{$group->leader}";
            if($group->level==2){
                $chartTitle="经营组：";
                $chartTitle.="{$group->id}";
                if($group->status==2){
                    $chartTitle.='（无效，己重新分组）';
                }
                echo "<div id='div_chart_{$group->id}' style='height:200px; border: 1px solid #ccc;width: 90%;'></div>";
                echo "<script>drawChart('{$chartTitle}','{$subtext}','div_chart_{$group->id}',[{$group->datas}]);</script>";
            }elseif($group->level==3){
                $chartTitle="管理组：";
                $chartTitle.="{$group->id}";
                if($group->status==2){
                    $chartTitle.='（无效，己重新分组）';
                }
                echo "<div id='div_chart_{$group->id}' style='height:200px; border: 1px solid #ccc;width: 90%;'></div>";
                echo "<script>drawChart('{$chartTitle}','{$subtext}','div_chart_{$group->id}',[{$group->datas}]);</script>";
            }
        }
        ?>
    </div>
    <?
}
elseif($this->func=='add'||$this->func=='edit')
{
    ?>
    <div class="main_title">
        <span>管理</span><? if($this->func=='add'){?>新增<? }else{ ?>编辑<? }?>
        <?=$this->anchor('tea','列表','class="but1"');?>
    </div>
    <form method="post">
        <table class="table_from">
            <tr><td>用户id：</td><td><input type="text" name="user_id" value="<?=$row['user_id']?>"/></td></tr>
            <tr><td>金额：</td><td><select name="money">
                        <option value="4980">4980</option>
                    </select></td></tr>
            <tr><td>推荐人用户id：</td><td><input type="text" name="p_userid" value=""/></td></tr>
            <tr><td></td><td><input type="submit" class="but3" value="保存" />
                    <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
        </table>
    </form>
    <?
}elseif($this->func=='log'){
    ?>
    <div class="main_title">
        <span>收益流水</span>列表
    </div>
    <form method="get">
        <div class="search">
            金额：<input type="text" size="10" name="money" value="<?=$_GET['money']?>">&nbsp;&nbsp;
            用户ID：<input type="text" size="10" name="user_id" value="<?=$_GET['user_id']?>">&nbsp;&nbsp;
            类型：<?=$account_type?>&nbsp;&nbsp;
            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <table class="table">
        <tr class="bt">
            <th>ID</th>
            <th>用户ID</th>
            <th>电子币</th>
            <th>冻结</th>
            <th>类型</th>
            <th>添加时间</th>
            <th>当前电子币</th>
            <th>当前冻结</th>
            <th>备注</th>
        </tr>
        <?
        foreach($result['list'] as $row)
        {
            ?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=$row->user_id?>/<?=$row->User()->username?></td>
                <td><?=(float)$row->money?></td>
                <td><?=(float)$row->money_freeze?></td>
                <td><?=$row->getLinkPageName('tea_money_type',$row->type);?></td>
                <td><?=$row->created_at?></td>
                <td><?=(float)$row->money_now?></td>
                <td><?=(float)$row->money_freeze_now?></td>
                <td><?=$row->remark?></td>
            </tr>
        <? }?>
    </table>
    <? if(empty($result['total'])){echo "无记录！";}else{echo $result['page'];}?>
    <?
}elseif($this->func=='user'){
?>
<div class="main_title">
    <span>用户</span>列表
</div>
<form method="get">
    <div class="search">
        金额：<input type="text" size="10" name="money" value="<?=$_GET['money']?>">&nbsp;&nbsp;
        用户ID：<input type="text" size="10" name="id" value="<?=$_GET['id']?>">&nbsp;&nbsp;
        推荐人ID:<input type="text" size="10" name="invite_id" value="<?=$_GET['invite_id']?>">&nbsp;&nbsp;
        <input type="submit" class="but2" value="查询" />
    </div>
</form>
<table class="table">
    <tr class="bt">
        <th>用户ID</th>
        <th>电子币</th>
        <th>冻结</th>
        <th>推荐人</th>
        <th>推荐PATH</th>
        <th>推荐个数</th>
        <th>轮回次数</th>
        <th>添加时间</th>
    </tr>
    <?
    foreach($result['list'] as $row)
    {
        $account=$row->account();
        ?>
        <tr>
            <td><?=$row->id?>/<?=$row->User()->username?></td>
            <td><?=(float)$account->money?></td>
            <td><?=(float)$account->money_freeze?></td>
            <td><?=$row->invite_id?></td>
            <td class="l" style="max-width: 400px; overflow: hidden"><?=$row->invite_path?></td>
            <td><?=$row->invite_count?></td>
            <td><?=$row->again?></td>
            <td><?=$row->created_at?></td>
        </tr>
    <? }?>
    <tr><td></td><td colspan="7" class="l">总计：￥<?=$moneySum?></td></tr>
</table>
<? if(empty($result['total'])){echo "无记录！";}else{echo $result['page'];}?>
    <table class="table">
        <tr class="bt">
            <th>id</th>
            <th>用户数量</th>
            <th>收</th>
            <th>支</th>
            <th>支/收</th>
            <th>添加时间</th>
        </tr>
        <?
        foreach($profit as $row)
        {
            ?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=$row->user_count?></td>
                <td><?=$row->received?></td>
                <td><?=$row->support?></td>
                <td><?=$row->rate?></td>
                <td><?=$row->created_at?></td>
            </tr>
        <? }?>
    </table>
<?php
}elseif($this->func=='userTree'){
    ?>
        <script>
            mxBasePath = '/themes/admin/js/mxgraph/src';
        </script>
        <script src="/themes/admin/js/mxgraph/src/js/mxClient.js"></script>
        <script src="/themes/admin/js/tea.js?1"></script>
        <script>
            $(document).ready(function () {
                main(<?=(int)$_GET['id']?>);
            });
        </script>
    <div><div class="drawContent" id="drawContent"></div></div>
<?
}
require 'footer.php';