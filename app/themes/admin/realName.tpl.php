<?php
require 'header.php';
$status_array=array('','待审核','认证成功','审核不通过');
$sex_array=array('','男','女');
$region=new \App\Model\Region();
if($this->func=='index')
{
    ?>
    <div class="main_title">
        <span>实名认证</span>列表
    </div>
    <form method="get">
        <div class="search">
            用户名：<input type="text" name="username" value="<?=$_GET['username']?>"/>
            姓名：<input type="text" name="name" value="<?=$_GET['name']?>"/>
            申请时间：<input type="text" name="starttime" value="<?=$_GET['starttime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            到<input type="text" name="endtime" value="<?=$_GET['endtime']?>" class="Wdate" onclick="javascript:WdatePicker();" size="10"/>
            <input type="submit" class="but2" value="查询" />
        </div>
    </form>
    <table class="table">
        <tr class="bt">
            <th>USER_ID</th>
            <th>用户名</th>
            <th>真实姓名</th>
            <th>性别</th>
            <th>籍贯</th>
            <th>身份证号</th>
            <th>正面</th>
            <th>背面</th>
            <th>认证时间</th>
            <th>审核备注</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?
        foreach($result['list'] as $row)
        {
            ?>
            <tr>
                <td><?=$row->user_id?></td>
                <td><?=$row->User()->username?></td>
                <td><?=$row->name?></td>
                <td><?=$sex_array[$row->sex]?></td>
                <td><?=$region->getName($row->province)?> <?=$region->getName($row->city)?> <?=$region->getName($row->county)?></td>
                <td><?=$row->card_no?></td>
                <td><? if($row->card_pic1){?><a href="<?=$row->card_pic1?>" target="_blank">正面照片</a><? }?></td>
                <td><? if($row->card_pic2){?><a href="<?=$row->card_pic2?>" target="_blank">背面照片</a><? }?></td>
                <td><?=$row->created_at?></td>
                <td><?=$row->verify_remark?></td>
                <td><?=$status_array[$row->card_status]?></td>
                <td>
                    <?
                    if ($row->card_status == 1) {
                        ?>
                        <a href="<?= url("realName/edit/?id={$row->id}") ?>">审核</a>
                        <?
                    } else {
                        echo '完成';
                    }
                    ?>
                </td>
            </tr>
        <? }?>
    </table>
    <? if (empty($result['total'])) {
        echo "无记录！";
    } else {
        echo $result['page'];
    } ?>
    <?
}
elseif($this->func=='edit')
{
    ?>
    <div class="main_title">
        <span>实名认证</span>审核
        <?=$this->anchor('realname','列表','class="but1"');?>
    </div>
    <form method="post">
        <div class="main_content">
            <table class="table_from">
                <tr><td>用户名：</td><td><?=$userInfo->User()->username?></td></tr>
                <tr><td>真实姓名：</td><td><?=$userInfo->name?></td></tr>
                <tr><td>性别：</td><td><?=$sex_array[$userInfo->sex]?></td></tr>
                <tr><td>身份证号：</td><td><?=$userInfo->card_no?></td></tr>
                <tr><td>籍贯：</td><td><?=$region->getName($userInfo->province)?> <?=$region->getName($userInfo->city)?> <?=$region->getName($userInfo->county)?></td></tr>
                <tr><td>身份证正面：</td><td><a href="<?=$userInfo->card_pic1?>" target="_blank"><img src="<?=$userInfo->card_pic1?>" height="200"></a></td></tr>
                <tr><td>身份证背面：</td><td><a href="<?=$userInfo->card_pic2?>" target="_blank"><img src="<?=$userInfo->card_pic2?>" height="200"></a></td></tr>
                <tr><td>状态：</td><td><input type="radio" name="card_status" value="2"/>审核通过<input type="radio" name="card_status" value="3"/>审核不通过</td></tr>
                <tr><td>审核备注：</td><td><textarea name="verify_remark" cols="45" rows="3"></textarea></td></tr>
                <tr><td></td><td><input type="submit" class="but3" value="保存" />
                        <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
            </table>
        </div>
    </form>
    <?
}
require 'footer.php';?>