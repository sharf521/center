<?php require 'header.php'; ?>
<? if ($this->func == 'index') : ?>
    <div class="main_title">
        <span>管理</span>列表
        <a href="<?= url('teaPackage/add/') ?>" class="but1">添 加</a>
    </div>
    <div class="main_content">
        <form method="post">
            <table class="table">
                <tr class="bt">
                    <th>ID</th>
                    <th>名称</th>
                    <th>照片</th>
                    <th>价格</th>
                    <th>折扣</th>
                    <th>规格</th>
                    <th>添加时间</th>
                    <th>排序</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <?
                foreach ($result['list'] as $row) { ?>
                    <tr>
                        <td><?= $row->id ?></td>
                        <td><?= $row->name ?></td>
                        <td><img src="<?=$row->picture?>" height="50"></td>
                        <td><?= $row->money ?></td>
                        <td><?= $row->discount ?></td>
                        <td><?= $row->title ?></td>
                        <td><?= $row->created_at ?></td>
                        <td>
                            <input type="hidden" name="id[]" value="<?=$row->id?>">
                            <input type="text" value="<?=$row->showorder?>" name="showorder[]" size="5"></td>
                        <td><?=($row->status == '1')?'显示':'隐藏';?></td>
                        <td>
                            <a href="<?= url("teaPackage/change/?id={$row->id}&page={$_GET['page']}") ?>"><?= ($row->status == '1') ? '隐藏' : '显示' ?></a>
                            <a href="<?= url("teaPackage/edit/?id={$row->id}&page={$_GET['page']}") ?>">修改</a>
                            <a href="<?= url("teaPackage/delete/?id={$row->id}&page={$_GET['page']}") ?>"
                               onclick="return confirm('确定要删除吗？')">删除</a>
                        </td>
                    </tr>
                <? } ?>
            </table>
            <div align="center"><input type="submit" value="修改排序" /></div>
        </form>
    </div>
<? elseif ($this->func == 'add' || $this->func == 'edit') : ?>
    <div class="main_title">
        <span>管理</span><? if ($this->func == 'add') { ?>新增<? } else { ?>编辑<? } ?>
        <a href="<?= url('app') ?>" class="but1">返回列表</a>
    </div>
    <div class="main_content">
        <form method="post">
            <input type="hidden" name="id" value="<?= $row->id ?>"/>
            <table class="table_from">
                <tr>
                    <td>名称：</td>
                    <td><input type="text" name="name" value="<?= $row->name ?>"/></td>
                </tr>

                <tr>
                    <td>图片：</td>
                    <td>
                        <span id="upload_span_picture"><img src="<?=$row->picture?>" height="50"></span>
                        <input type="hidden" name="picture" value="" id="picture">
                        <input type="file" name="file" class="layui-upload-file" upload_id="picture">
                    </td>
                </tr>
                <tr>
                    <td>价格：</td>
                    <td><input type="text" name="money" value="<?= $row->money ?>" size="50"/></td>
                </tr>
                <tr>
                    <td>折扣：</td>
                    <td><input type="text" name="discount" value="<?= $row->discount ?>" size="50"/></td>
                </tr>
                <tr>
                    <td>规格：</td>
                    <td><input type="text" name="title" value="<?= $row->title ?>" size="50"/></td>
                </tr>
                <tr>
                    <td>说明：</td>
                    <td><textarea name="remark" cols="50" rows="4"><?=$row->remark?></textarea></td>
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