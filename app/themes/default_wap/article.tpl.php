<?php require 'header_v2.php';?>

<div class="m_header">
    <a class="m_header_l" href="javascript:history.go(-1)">返回</a>
    <a class="m_header_r"></a>
    <h1><?=$this->title?></h1>
</div>

<div class="margin_header"></div>
<div class=" main_content articleBox">
    <h2><?=$article->title?></h2>
    <div class="articleContent">
        <?=$article->content?>
    </div>
</div>



<?php require 'footer_v2.php';?>