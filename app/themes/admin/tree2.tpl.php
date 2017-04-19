<?php
require 'header.php';
$arr_typeid=array(
    'layer2first'=>'推荐1个',
    'layer2full'=>'二层满',
    'layer3full'=>'三层满',
    'layer4dian'=>'第4层见点',
    'car_money'=>'提车',
    'transfer_money'=>'过户',
);
if($this->func=='index')
{
    ?>
    <div class="main_title">
        <span>Tree2管理</span>列表<?=$this->anchor('tree2/add','新增','class="but1"');?>
        <?=$this->anchor('tree2/calTree2','计算','class="but1"')?>
    </div>

    <form method="get">
        <div class="search">
            金额：<input type="text" size="10" name="money" value="<?=$_GET['money']?>">&nbsp;&nbsp;
            用户ID：<input type="text" size="10" name="user_id" value="<?=$_GET['user_id']?>">&nbsp;&nbsp;
            Tree2_ID：<input type="text" size="10" name="id" value="<?=$_GET['id']?>">&nbsp;&nbsp;
            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <table class="table">
        <tr class="bt">
            <th>ID</th>
            <th>用户ID</th>
            <th>金额</th>
            <th>收入</th>
            <th>上层id</th>
            <th>上层id推荐个数</th>
            <th>推荐关系</th>
            <th>状态</th>
            <th>添加时间</th>
        </tr>
        <?
        $arr_status=array('未计算','己计算');
        foreach($result['list'] as $row)
        {
            ?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=$row->user_id?></td>
                <td><?=(float)$row->money?></td>
                <td><?=(float)$row->income?></td>
                <td><?=$row->pid?></td>
                <td><?=$row->position?></td>
                <td class="l"><?=str_replace(',','->',rtrim($row->pids,','))?></td>
                <td><?=$arr_status[$row->status]?></td>
                <td><?=$row->created_at?></td>
            </tr>
        <? }?>
    </table>
    <? if(empty($result['total'])){echo "无记录！";}else{echo $result['page'];}?>

    <?
    if ((int)$_GET['id']>0) {
        ?>
        <script>
            mxBasePath = '/themes/admin/js/mxgraph/src';
        </script>
        <script src="/themes/admin/js/mxgraph/src/js/mxClient.js"></script>
        <script src="/themes/admin/js/tree2.js"></script>
        <script>
            $(document).ready(function () {
                main(<?=(int)$_GET['user_id']?>, <?=(int)$_GET['id']?>,<?=(float)$_GET['money']?>);
            });
        </script>
    <?
    }
    ?>
    <div><div class="drawContent" id="drawContent" style="margin: 20px 0px;"></div></div>

<?
}
elseif($this->func=='add')
{
    ?>
    <div class="main_title">
        <span>Tree2管理</span><? if($this->func=='add'){?>新增<? }else{ ?>编辑<? }?>
        <?=$this->anchor('tree2','列表','class="but1"');?>
    </div>
    <div class="main_content">
        <form method="post">
            <table class="table_from">
                <tr><td>用户id：</td><td><input type="text" name="user_id" value="<?=$row['user_id']?>"/></td></tr>
                <tr><td>金额：</td><td><select name="money">
                            <option value="27500">27500</option>
                            <option value="18500">18500</option>
                        </select></td></tr>
                <tr><td>推荐人id：</td><td><input type="text" name="p_userid" value=""/></td></tr>
                <tr><td></td><td><input type="submit" class="but3" value="保存" />
                        <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
            </table>
        </form>
    </div>
<?
}elseif($this->func=='tree2log'){
    ?>
    <div class="main_title">
        <span>对列收益流水</span>列表
    </div>
    <form method="get">
        <div class="search">
            金额：<input type="text" size="10" name="money" value="<?=$_GET['money']?>">&nbsp;&nbsp;
            用户ID：<input type="text" size="10" name="user_id" value="<?=$_GET['user_id']?>">&nbsp;&nbsp;
            tree_id：<input type="text" size="10" name="tree_id" value="<?=$_GET['tree_id']?>">&nbsp;&nbsp;
            进入tree_id：<input type="text" size="10" name="in_tree_id" value="<?=$_GET['in_tree_id']?>">&nbsp;&nbsp;
            进入用户ID：<input type="text" size="10" name="in_user_id" value="<?=$_GET['in_user_id']?>">&nbsp;&nbsp;
            类型：
            <select name="typeid">
                <option value="0">请选择</option>
                <?  foreach ($arr_typeid as $key=>$typeid) : ?>
                <option value="<?=$key?>" <? if($key==$_GET['typeid']){echo 'selected';}?>><?=$typeid?></option>
                <? endforeach;?>
            </select>

            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <table class="table">
        <tr class="bt">
            <th>ID</th>
            <th>tree_id/用户ID</th>
            <th>进入tree_id/进入用户ID</th>
            <th>类型</th>
            <th>金额</th>
            <th>层数</th>
            <th>添加时间</th>
        </tr>
        <?
        foreach($result['list'] as $row)
        {
            ?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=$row->tree_id?>/<?=$row->user_id?></td>
                <td><?=$row->in_user_id?>/<?=$row->in_tree_id?></td>
                <td><?=$arr_typeid[$row->typeid]?></td>
                <td><?=(float)$row->money?></td>
                <td><?=$row->layer?></td>
                <td><?=$row->created_at?></td>
            </tr>
        <? }?>
    </table>
    <? if(empty($result['total'])){echo "无记录！";}else{echo $result['page'];}?>
    <div id="div_echarts" style="height:500px;"></div>
    <script src="/plugin/echarts/echarts-all.js"></script>
    <script>
        option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross',
                    crossStyle: {
                        color: '#999'
                    }
                }
            },
            toolbox: {
                feature: {
                    dataView: {show: true, readOnly: false},
                    magicType: {show: true, type: ['line', 'bar']},
                    restore: {show: true},
                    saveAsImage: {show: true}
                }
            },
            legend: {
                data:['收入','支出','支出比']
            },
            xAxis: [
                {
                    type: 'category',
                    data: <?php echo json_encode($user_count) ?>,
                    axisPointer: {
                        type: 'shadow'
                    }
                }
            ],
            yAxis: [
                {
                    type: 'value',
                    name: '收支',
                    axisLabel: {
                        formatter: '{value} '
                    }
                },
                {
                    type: 'value',
                    name: '收支比',
                    axisLabel: {
                        formatter: '{value} '
                    }
                }
            ],
            series: [
                {
                    name:'收入',
                    type:'bar',
                    data:<?php echo json_encode($received) ?>
                },
                {
                    name:'支出',
                    type:'bar',
                    data:<?php echo json_encode($support) ?>
                },
                {
                    name:'支出比',
                    type:'line',
                    yAxisIndex: 1,
                    data:<?php echo json_encode($rate) ?>
                }
            ]
        };
        var myChart = echarts.init(document.getElementById('div_echarts'));
        myChart.setOption(option);
    </script>
<?
}
require 'footer.php';