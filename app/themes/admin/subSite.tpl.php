<?php require 'header.php'; ?>
<? if ($this->func == 'index') : ?>
    <div class="main_title">
        <span>管理</span>列表
        <a href="<?= url('subSite/add/') ?>" class="but1">添 加</a>
    </div>
    <div class="main_content">
        <form method="post">
            <table class="table">
                <tr class="bt">
                    <th>ID</th>
                    <th>分站名称</th>
                    <th>域名</th>
                    <th>LOGO</th>
                    <th>标题</th>
                    <th>关键字</th>
                    <th>描述</th>
                    <th>添加时间</th>
                    <th>操作</th>
                </tr>
                <? foreach ($list as $row) { ?>
                    <tr>
                        <td><?= $row->id ?></td>
                        <td><?= $row->name ?></td>
                        <td><?= $row->domain ?></td>
                        <td><img src="<?=$row->logo?>" height="50"></td>
                        <td><?= $row->title ?></td>
                        <td><?= $row->keywords ?></td>
                        <td><?= $row->description ?></td>
                        <td><?= $row->created_at ?></td>
                        <td>
                            <a href="<?= url("subSite/edit/?id={$row->id}&page={$_GET['page']}") ?>">修改</a>
                            <a href="<?= url("subSite/delete/?id={$row->id}&page={$_GET['page']}") ?>"
                               onclick="return confirm('确定要删除吗？')">删除</a>
                        </td>
                    </tr>
                <? } ?>
            </table>
        </form>
    </div>
<? elseif ($this->func == 'add' || $this->func == 'edit') : ?>
    <div class="main_title">
        <span>管理</span><? if ($this->func == 'add') { ?>新增<? } else { ?>编辑<? } ?>
        <a href="<?= url('subSite') ?>" class="but1">返回列表</a>
    </div>
    <div class="main_content">
        <form method="post">
            <table class="table_from">
                <tr>
                    <td>分站名称：</td>
                    <td><input type="text" name="name" size="50" value="<?= $row->name ?>"/></td>
                </tr>
                <tr>
                    <td>域名：</td>
                    <td><input type="text" name="domain" size="150" value="<?= $row->domain ?>"/> |分隔，以|结尾</td>
                </tr>
                <tr>
                    <td>LOGO：</td>
                    <td>

                        <input type="hidden" name="logo" id="logo" value="<?= $row->logo ?>"/>
						<span id="upload_span_logo">
							<? if ($row->logo != '') { ?>
                                <a href="<?= $row->logo ?>" target="_blank"><img
                                        src="<?= $row->logo ?>" align="absmiddle" width="100"/></a>
                            <? } ?>
                        </span>
                        <input type="file" name="file" class="layui-upload-file" upload_id="logo" upload_type="logo">

                    </td>
                </tr>
                <tr>
                    <td>标题：</td>
                    <td><input type="text" name="title" value="<?= $row->title ?>" size="150"/></td>
                </tr>
                <tr>
                    <td>关键字：</td>
                    <td><textarea name="keywords" cols="50" rows="3"><?=$row->keywords?></textarea></td>
                </tr>
                <tr>
                    <td>描述：</td>
                    <td><textarea name="description" cols="50" rows="3"><?=$row->description?></textarea></td>
                </tr>
                <tr>
                    <td>云购网址：</td>
                    <td><input type="text" name="cloud_url" value="<?= $row->cloud_url ?>" size="150"/>http://开始 结尾不要/</td>
                </tr>
                <tr>
                    <td>云购wap网址：</td>
                    <td><input type="text" name="cloud_url_wap" value="<?= $row->cloud_url_wap ?>" size="150"/></td>
                </tr>
                <tr>
                    <td>POS代理网址：</td>
                    <td><input type="text" name="pos_url" value="<?= $row->pos_url ?>" size="150"/></td>
                </tr>
                <tr>
                    <td>POS代理wap网址：</td>
                    <td><input type="text" name="pos_url_wap" value="<?= $row->pos_url_wap ?>" size="150"/></td>
                </tr>
                <tr>
                    <td>商城网址：</td>
                    <td><input type="text" name="mall_url" value="<?= $row->mall_url ?>" size="150"/></td>
                </tr>
                <tr>
                    <td>商城wap网址：</td>
                    <td><input type="text" name="mall_url_wap" value="<?= $row->mall_url_wap ?>" size="150"/></td>
                </tr>
                <tr>
                    <td>众筹网址：</td>
                    <td><input type="text" name="crowd_url" value="<?= $row->crowd_url ?>" size="150"/></td>
                </tr>
                <tr>
                    <td>众筹wap网址：</td>
                    <td><input type="text" name="crowd_url_wap" value="<?= $row->crowd_url_wap ?>" size="150"/></td>
                </tr>
                <tr>
                    <td>新汽车网址：</td>
                    <td><input type="text" name="shop_url" value="<?= $row->shop_url ?>" size="150"/></td>
                </tr>
                <tr>
                    <td>新汽车wap网址：</td>
                    <td><input type="text" name="shop_url_wap" value="<?= $row->shop_url_wap ?>" size="150"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="保存"/>
                        <input type="button" value="返回" onclick="window.history.go(-1)"/></td>
                </tr>
            </table>
        </form>
    </div>
<? endif; ?>
<?php require 'footer.php'; ?>