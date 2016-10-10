<?php require 'header.php'; ?>
<? if ($this->func == 'index') : ?>
    <div class="main_title">
        <span>管理</span>列表
        <a href="<?= url('app/add/') ?>" class="but1">添 加</a>
    </div>
    <div class="main_content">
        <form method="post">
            <table class="table">
                <tr class="bt">
                    <th>ID</th>
                    <th>名称</th>
                    <th>appid</th>
                    <th>appsecret</th>
                    <th>分站对应字段</th>
                    <th>添加时间</th>
                    <th>修改时间</th>
                    <th>操作</th>
                </tr>
                <? foreach ($list as $row) { ?>
                    <tr>
                        <td><?= $row->id ?></td>
                        <td><?= $row->name ?></td>
                        <td><?= $row->appid ?></td>
                        <td><?= $row->appsecret ?></td>
                        <td><?= $row->subsite_field ?></td>
                        <td><?= $row->created_at ?></td>
                        <td><?= $row->updated_at ?></td>
                        <td>
                            <a href="<?= url("app/edit/?id={$row->id}&page={$_GET['page']}") ?>">修改</a>
                            <a href="<?= url("app/delete/?id={$row->id}&page={$_GET['page']}") ?>"
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
                    <td>appid：</td>
                    <td><input type="text" name="appid" value="<?= $row->appid ?>"/></td>
                </tr>
                <tr>
                    <td>appsecret：</td>
                    <td><input type="text" name="appsecret" value="<?= $row->appsecret ?>" size="50"/></td>
                </tr>
                <tr>
                    <td>分站对应字段：</td>
                    <td><input type="text" name="subsite_field" value="<?= $row->subsite_field ?>" size="50"/></td>
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