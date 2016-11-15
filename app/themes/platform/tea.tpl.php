<?php require 'header.php';?>
    <fieldset class="layui-elem-field layui-field-title">
        <legend>销售状况</legend>
    </fieldset>

<style type="text/css">
    .item{float: left; width: 300px;line-height: 22px;}
    .item b{color: #f00; font-size: 16px; line-height: 30px;}
</style>
<?php
foreach($teas as $i=>$tea) :
    echo '<div class="item">';
    $myColor='';
    if($tea->user_id==$this->user_id){
        $myColor='style="color: green"';
    }
    echo "<b {$myColor}>".$tea->user_id.':'.$tea->showTeaUserName($tea->user_id).'</b><br>';
    $invite_path=trim($tea->invite_path,',');
    if(!empty($invite_path)){
        $ids=explode(',',$invite_path);
        foreach($ids as $id){
            echo $id.':'.$tea->showTeaUserName($id).'&nbsp;　&nbsp;';
        }
    }
    echo '</div>';
    if(in_array($i,array(0,2,6))){
        echo '<div class="clearFix"></div>';
    }
endforeach;

?>
<? if(empty($teas)){echo "无记录！";}?>
<?php require 'footer.php';?>