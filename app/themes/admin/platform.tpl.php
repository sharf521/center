<?php require 'header.php'; ?>
<?
if ($this->func == 'index') {
    ?>
    <div class="main_title">
        <span>联动管理</span>列表<?= $this->anchor('platform/add', '添加', 'class="but1"'); ?>
    </div>
    <? if (!empty($result['list'])) { ?>
        <table border="0" class="flexme" width="100%">
            <form method="post">
                <thead class="theadtd">
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>备注</th>
                    <th>登陆地址</th>
                    <th>登陆有效时间</th>
                    <th>排序</th>
                    <th>添加时间</th>
                    <th>最后登陆时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($result['list'] as $row): ?>
                    <tr onmouseover="this.bgColor='#ECFBFF'" onmouseout="this.bgColor='#ffffff'" align="center">
                        <td><?= $row['id'] ?><input type="hidden" name="id[]" value="<?= $row['id'] ?>"/></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= nl2br($row['content']) ?></td>
                        <td><?= $row['login_url'] ?></td>
                        <td><?= $row['login_minutes'] ?></td>
                        <td><input type="text" name="showorder[]" value="<?= $row['showorder'] ?>" size="6"/></td>
                        <td><?= $row['addtime'] ?></td>
                        <td><?= $row['login_last'] ?></td>
                        <td>
                            <?
                            echo $this->anchor("platform/linklist/?id={$row['id']}", "管理") . " | ";
                            echo $this->anchor("platform/edit/?id={$row['id']}", '编辑') . " | ";
                            $arr = array(
                                'onclick' => "return confirm('确定要删除吗？')"
                            );
                            echo $this->anchor("platform/drop/?id={$row['id']}", '删除', $arr);
                            ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <td colspan="6" class="submit" align="center">
                        <input type="submit" class="but3" value="修改资料"/>
                    </td>
                </tr>
                </tbody>
            </form>
        </table>
        <? echo $result['page'];
    } else {
        echo '没有符合条件的记录';
    }
} elseif ($this->func == 'edit' || $this->func == 'add') {
    ?>
    <div class="main_title">
        <span>联动管理</span><? if ($this->func == 'add') { ?>添加类型<? } else { ?>编辑<? } ?>
        <?= $this->anchor('platform', '列表', 'class="but1"'); ?>
    </div>
    <form method="post">
        <table cellpadding="4">
            <in
            <tr>
                <td>名称：</td>
                <td><input class="bkboxinput" type="text" name="name" value="<?= $data['name'] ?>" size="40">
                </td>
            </tr>
            <tr>
                <td>登陆地址：</td>
                <td><input class="bkboxinput" type="text" name="login_url" value="<?= $data['login_url'] ?>" size="40">
                </td>
            </tr>
            <tr>
                <td>验证码地址：</td>
                <td><input class="bkboxinput" type="text" name="randcode_url" value="<?= $data['randcode_url'] ?>" size="40">
                </td>
            </tr>
            <tr>
                <td>登陆有效时间：</td>
                <td><input class="bkboxinput" type="text" name="login_minutes" value="<?= $data['login_minutes'] ?>">分钟
                </td>
            </tr>
            <tr>
                <td>备注：</td>
                <td>
                    <textarea cols="40" rows="4" name="content"><?= $data['content'] ?></textarea>
                </td>
            </tr>
            <tr>
                <td>参数：</td>
                <td>
                    <table id='yltable'>
                        <tbody>
                        <tr><td>name</td><td>value</td></tr>
                        <?
                        $award=explode(';',$row['award']);
                        foreach($award as $i=>$v)
                        {
                            $m=explode(',',$v);
                            ?>
                            <tr><td><input type="text" name="award[]" size="15" value="<?=$m[0]?>"></td><td><input type="text" name="money[]" size="5" value="<?=$m[1]?>"></td></tr>
                            <?
                        }
                        ?>
                        <tr><td><input  type="text" name="award[]" size="15"></td><td><input type="text" name="money[]" size="5"></td></tr>

                        </tbody>
                    </table>
                    <a href="javascript:addRow()">添加一行</a>
                    <script language="javascript">
                        function addRow() {
                            var tbl = document.getElementById("yltable");
                            var newTR = tbl.insertRow(tbl.rows.length);
                            var newNameTD = newTR.insertCell(0);
                            newNameTD.innerHTML = "<input  type='text' name='award[]' size='20'>";
                            var newNameTD = newTR.insertCell(1);
                            newNameTD.innerHTML = '<input type="text" name="money[]" size="20">';
                            if (tbl.rows.length > 2) {
                                var newNameTD = newTR.insertCell(2);
                                newNameTD.innerHTML = "<a href='javascript:deleteRow(" + tbl.rows.length + ")'>删除</a>";
                                if (tbl.rows[tbl.rows.length - 2].cells[2] != null) {
                                    tbl.rows[tbl.rows.length - 2].deleteCell(2);
                                }
                            }
                        }
                        function deleteRow(ids) {
                            var tbl = document.getElementById("yltable");
                            tbl.deleteRow(ids - 1);
                            if (tbl.rows.length > 2) {
                                var newNameTD = tbl.rows[ids - 2].insertCell(2);
                                newNameTD.innerHTML = "<a href='javascript:deleteRow(" + tbl.rows.length + ")'>删除</a>";
                            }
                        }
                    </script>
                </td>
            </tr>
            <tr>
                <td>排序：</td>
                <td>
                    <input class="bkboxinput" type="text" name="showorder" value="<?= $data['showorder'] ?>">
                </td>
            </tr>

            <tr>
                <td></td>
                <td><input type="submit" class="but3" value="保存">
                    <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td>
            </tr>
        </table>
    </form>
    <?
} elseif ($this->func == 'linklist') {
    ?>
    <div class="main_title">
        <span>联动管理</span>管理
        <?= $this->anchor('platform', '列表', 'class="but1"'); ?>
    </div>
    <table border="0" class="flexme" width="100%">
        <form method="post">
            <thead class="theadtd">
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>备注</th>
                <th>是否需要登陆</th>
                <th>是否需要登陆</th>
                <th>是否需要登陆</th>
                <th>排序</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <? foreach ($list as $row): ?>
                <tr onmouseover="this.bgColor='#ECFBFF'" onmouseout="this.bgColor='#ffffff'" align="center">
                    <td><?= $row['id'] ?><input type="hidden" value="<?= $row['id'] ?>" name="id[]"/></td>
                    <td><?= $typename ?></td>
                    <td><input type="text" value="<?= $row['name'] ?>" name="name[]" size="40"/></td>
                    <td><input type="text" value="<?= $row['value'] ?>" name="value[]" size="40"/></td>
                    <td><input type="text" value="<?= $row['showorder'] ?>" name="showorder[]" size="6"/></td>
                    <td><?= $row['createdate'] ?></td>
                    <td>
                        <?
                        $arr = array(
                            'onclick' => "return confirm('确定要删除吗？')"
                        );
                        echo $this->anchor("platform/link_drop/?id={$row['id']}&typeid={$_GET['id']}", '删除', $arr);
                        ?>
                    </td>
                </tr>
            <? endforeach ?>
            <tr>
                <td colspan="7" class="submit" align="center">
                    <input type="hidden" value="{$_G.row.id}" name="type"/>
                    <input type="submit" class="but3" value="修改"/>
                </td>
            </tr>
            </tbody>
        </form>
    </table>
    <? echo $page; ?>
    <div><font color="#990000"><b>添加(<?= $typename ?>)分类下的联动</b></font></div>
    <form method="post" action="<?= $this->base_url('platform/link_add') ?>">

        <table cellpadding="4">
            <tr>
                <td>所属类别：</td>
                <td><?= $typename ?>
                    <input class="bkboxinput" type="hidden" name="typeid" value="<?= $_GET['id'] ?>" size="30">
                </td>
            </tr>
            <tr>
                <td>联动名称：</td>
                <td><input class="bkboxinput" type="text" name="name">
                </td>
            </tr>
            <tr>
                <td>联动值：</td>
                <td><input class="bkboxinput" type="text" name="value" size="30">
                </td>
            </tr>
            <tr>
                <td>排序：</td>
                <td>
                    <input class="bkboxinput" type="text" name="showorder" value="10">
                </td>
            </tr>

            <tr>
                <td></td>
                <td><input type="submit" class="but3" value="保存">
                    <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td>
            </tr>
        </table>
    </form>


    <table border="0" cellspacing="1" width="100%">
        <form method="post" action="<?= $this->base_url('platform/link_action') ?>">
            <tr>
                <td class="main_td" colspan="6" align="left">&nbsp;<font color="#990000"><b>批量添加</b></font></td>
            </tr>
            <tr class="tr2">
                <td class="main_td1" align="center">联动名称</td>
                <td class="main_td1" align="center">联动值</td>
                <td class="main_td1" align="center">排序</td>
            </tr>
            <tr>
                <td class="main_td1" align="center"><input type="text" name="name[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="value[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="showorder[]" value="10" size="5"/></td>
            </tr>
            <tr>
                <td class="main_td1" align="center"><input type="text" name="name[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="value[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="showorder[]" value="10" size="5"/></td>
            </tr>
            <tr>
                <td class="main_td1" align="center"><input type="text" name="name[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="value[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="showorder[]" value="10" size="5"/></td>
            </tr>
            <tr>
                <td class="main_td1" align="center"><input type="text" name="name[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="value[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="showorder[]" value="10" size="5"/></td>
            </tr>
            <tr>
                <td class="main_td1" align="center"><input type="text" name="name[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="value[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="showorder[]" value="10" size="5"/></td>
            </tr>
            <tr>
                <td class="main_td1" align="center"><input type="text" name="name[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="value[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="showorder[]" value="10" size="5"/></td>
            </tr>
            <tr>
                <td class="main_td1" align="center"><input type="text" name="name[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="value[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="showorder[]" value="10" size="5"/></td>
            </tr>
            <tr>
                <td class="main_td1" align="center"><input type="text" name="name[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="value[]" size="40"/></td>
                <td class="main_td1" align="center"><input type="text" name="showorder[]" value="10" size="5"/></td>
            </tr>
            <input type="hidden" name="typeid" value="<?= $_GET['id'] ?>"/>
            <tr>
                <td colspan="6" class="submit" align="center">
                    <input type="submit" class="but3" value="添加">
                </td>
            </tr>
        </form>
    </table>
<? } ?>
<?php require 'footer.php'; ?>