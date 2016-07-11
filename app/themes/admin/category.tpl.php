<?php require 'header.php';?>
<?
echo url('category/?pid=1');
if($this->func=='index')
{
?>
<div class="main_title">
    <span>分类管理</span>列表<?=$this->anchor("category/add/?pid=".intval($_GET['pid']),'新增','class="but1"');?>
    <?php if($level>0){echo $this->anchor("category/?pid={$pid}",'返回上一级','class="but1"');}?>
</div>
<form method="post">	
<table class="table">
    <tr class="bt">
        <th>ID</th>
        <th>PID</th>
        <th>名称</th>
        <th>路径</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>操作</th>                                
    </tr>
    <? foreach($list as $row){?>
    <tr>
        <td><?=$row['id']?></td>
        <td><?=$row['pid']?></td>        
        <td><?=$row['name']?></td>
        <td><?=$row['path']?></td>
        <td>
        <input type="text" value="<?=$row['showorder']?>" name="showorder[]" size="5">
        <input type="hidden" name="id[]" value="<?=$row['id']?>">
        </td>
        <td><?=$row['addtime']?></td>
        <td>
            <a href="<?=url("category/?pid={$row['id']}")?>">管理子项</a>
        <? 
        //echo $this->anchor("category/?pid={$row['id']}","管理子项")." | ";
		//echo $this->anchor("category/add/?pid={$row['id']}","添加子项")." | ";
        echo $this->anchor('category/edit/?id='.$row['id'],'编辑')." | ";
        $arr=array(
            'onclick'=>"return confirm('确定要删除吗？')"
        );
        echo $this->anchor('category/delete/?pid='.$row['pid'].'&id='.$row['id'],'删除',$arr);
        ?>

        </td>                                
    </tr> 
    <? }?>
</table>
<div align="center"><input type="submit" value="更新排序" class="but3" /></div>
</form>
<?
}
elseif($this->func =='add' || $this->func =='edit')
{
	?>
    <div class="main_title">
        <span>分类管理</span><? if($this->func=='add'){?>新增<? }else{ ?>编辑<? }?>
		<?=$this->anchor('category','列表','class="but1"');?>
    </div>
    <form method="post">	
	    <input type="hidden" name="id" value="<?=$row['id']?>" />
    	<div class="form1">
            <ul>
                <? if($this->func =='add'){?>
                <li><label>PID：</label><?=$_GET['pid']?><input type="hidden" name="pid" value="<?=$_GET['pid']?>"/></li><? }else{?>
                <li><label>PID：</label><?=$row['pid']?><input type="hidden" name="pid" value="<?=$row['pid']?>"/></li><? }?>
                <li><label>名称：</label><input type="text" name="name" value="<?=$row['name']?>"/></li>
                <li><label>aside1：</label><input type="text" name="aside1" value="<?=$row['aside1']?>"/></li>
                <li><label>aside2：</label><input type="text" name="aside2" value="<?=$row['aside2']?>"/></li>
                <li><label>aside3：</label><input type="text" name="aside3" value="<?=$row['aside3']?>"/></li>
                <li><label>排序：</label><input type="text" name="showorder" value="<?=$row['showorder']?>"/></li>
            </ul>
            <input type="submit" class="but3" value="保存" />
            <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/>
        </div>
    </form>
    
    <?
}
?>
<?php require 'footer.php';?>