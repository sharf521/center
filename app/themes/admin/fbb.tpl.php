<?php
require 'header.php';
if($this->func=='index')
{
    ?>
    <div class="main_title">
        <span>FBB管理</span>列表<?=$this->anchor('fbb/add','新增','class="but1"');?>
        <?=$this->anchor('fbb/calFbb','计算','class="but1"')?>
    </div>

    <form method="get">
        <div class="search">
            金额：<input type="text" size="10" name="money" value="<?=$_GET['money']?>">&nbsp;&nbsp;
            用户ID：<input type="text" size="10" name="user_id" value="<?=$_GET['user_id']?>">&nbsp;&nbsp;
            Fbb_ID：<input type="text" size="10" name="id" value="<?=$_GET['id']?>">&nbsp;&nbsp;
            第:<input type="text" size="10" name="level" value="<?=$_GET['level']?>">层&nbsp;&nbsp;
            结构显示层数:<input type="text" size="10" name="showLevel" value="<?=$_GET['showLevel']?$_GET['showLevel']:'5'?>">&nbsp;&nbsp;

            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <div id="div_table">
        <table class="layui-table">
            <tr class="bt">
                <th>ID</th>
                <th>用户ID</th>
                <th>用户名</th>
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
                $user=$row->User();
                ?>
                <tr>
                    <td><?=$row->id?></td>
                    <td><?=$row->user_id?></td>
                    <td><?=$user->username?></td>
                    <td><?=(float)$row->money?></td>
                    <td><?=(float)$row->income?></td>
                    <td><?=$row->pid?></td>
                    <td><?=$row->position?></td>
                    <td class="l"><?=str_replace(',','->',rtrim($row->pids,','))?></td>
                    <td><?=$arr_status[$row->status]?></td>
                    <td><?=$row->addtime?></td>
                </tr>
            <? }?>
        </table>
        <? if(empty($result['total'])){echo "无记录！";}else{echo $result['page'];}?>
    </div>
    <script>
        $(function () {
            $('#div_table_btn').on('click',function () {
                if($('#div_table').is(':hidden')){
                    $('#div_table').show();
                }else{
                    $('#div_table').hide();
                }
            })
        });
    </script>

    <?
    if ((int)$_GET['id']>0) {
        ?>
        <input type="button" id="div_table_btn" class="but2" value="隐藏/显示表格" />
        <script>
            mxBasePath = '/themes/admin/js/mxgraph/src';
        </script>
        <script src="/themes/admin/js/mxgraph/src/js/mxClient.js"></script>
        <script src="/themes/admin/js/fbb.js"></script>
        <script>
            $(document).ready(function () {
                main(<?=(int)$_GET['user_id']?>, <?=(int)$_GET['id']?>,<?=(float)$_GET['money']?>,<?=(int)$_GET['showLevel']?>);
            });
        </script>
    <?
    }
    ?>
    <div><div class="drawContent" id="drawContent"></div></div>

<?
}
elseif($this->func=='add')
{
    ?>
    <div class="main_title">
        <span>FBB管理</span><? if($this->func=='add'){?>新增<? }else{ ?>编辑<? }?>
        <?=$this->anchor('fbb','列表','class="but1"');?>
    </div>
    <div class="main_content">
        <form method="post">
            <table class="table_from">
                <tr><td>用户id：</td><td><input type="text" name="user_id" value="<?=$row['user_id']?>"/></td></tr>
                <tr><td>金额：</td><td><select name="money">
                            <option value="200">200</option>
                            <option value="2000">2000</option>
                            <option value="20000">20000</option>
                            <option value="200000">200000</option>
                        </select></td></tr>
                <tr><td>推荐人id：</td><td><input type="text" name="p_userid" value=""/></td></tr>
                <tr><td></td><td><input type="submit" class="but3" value="保存" />
                        <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
            </table>
        </form>
    </div>
<?
}elseif($this->func=='fbblog'){
    ?>
    <div class="main_title">
        <span>对列收益流水</span>列表
    </div>
    <form method="get">
        <div class="search">
            金额：<input type="text" size="10" name="money" value="<?=$_GET['money']?>">&nbsp;&nbsp;
            用户ID：<input type="text" size="10" name="user_id" value="<?=$_GET['user_id']?>">&nbsp;&nbsp;
            fbb_id：<input type="text" size="10" name="fbb_id" value="<?=$_GET['fbb_id']?>">&nbsp;&nbsp;
            进入的fbb_id：<input type="text" size="10" name="in_fbb_id" value="<?=$_GET['in_fbb_id']?>">&nbsp;&nbsp;
            进入的用户id：<input type="text" size="10" name="in_user_id" value="<?=$_GET['in_user_id']?>">&nbsp;&nbsp;
            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <table class="layui-table">
        <tr class="bt">
            <th>ID</th>
            <th>fbb_id</th>
            <th>用户ID</th>
            <th>用户名</th>
            <th>进入fbb_id/进入用户ID</th>
            <th>金额</th>
            <th>layer</th>
            <th>添加时间</th>
        </tr>
        <?
        foreach($result['list'] as $row)
        {
            $user=$row->User();
            ?>
            <tr>
                <td><?=$row->id?></td>
                <td><?=$row->fbb_id?></td>
                <td><?=$row->user_id?></td>
                <td><?=$user->username?></td>
                <td><?=$row->in_fbb_id?>/<?=$row->in_user_id?></td>
                <td><?=(float)$row->money?></td>
                <td><?=$row->layer?></td>
                <td><?=$row->addtime?></td>
            </tr>
        <? }?>
        <?
        if($money_total>0){
            echo "<tr><td colspan='5'></td><td colspan='3'>总计：{$money_total}</td></tr>";
        }
        ?>
    </table>

    <? if(empty($result['total'])){echo "无记录！";}else{echo $result['page'];}?>
<?
}
require 'footer.php';