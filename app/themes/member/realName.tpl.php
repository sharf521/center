<?php require 'header.php';

$region=new \App\Model\Region();
$sex_array=array('','男','女');
?>
    <div class="warpcon">
        <?php require 'left.php'; ?>
        <div class="warpright">
            <div class="box">
                <h3>实名认证</h3>
                <? 
                if ($userInfo->card_status == 1) { 
                    echo '<div class="alert-warning" role="alert">您己经上传资料，请等待审核！</div>';
                } 
                if($userInfo->card_status == 3){
                    echo '<div class="alert-warning" role="alert">审核不通过，请查看审核意见后重新提交！</div>';
                }
                //己审核
                if ($userInfo->card_status == 2) : ?>
                    <div class="alert-warning" role="alert">您己经通过实名认证！</div>
                    <table class="table_from">
                        <tr><td>真实姓名：</td><td><?=$userInfo->name?></td></tr>
                        <tr><td>性别：</td><td><?=$sex_array[$userInfo->sex]?></td></tr>
                        <tr><td>身份证号：</td><td><?=$userInfo->card_no?></td></tr>
                        <tr><td>籍贯：</td><td><?=$region->getName($userInfo->province)?> <?=$region->getName($userInfo->city)?> <?=$region->getName($userInfo->county)?></td></tr>
                        <tr>
                            <td align="right">身份证正面：</td>
                            <td><a href="<?= $userInfo->card_pic1 ?>" target="_blank"><img src="<?= $userInfo->card_pic1 ?>" align="absmiddle" width="100"/></a>

                            </td>
                        </tr>
                        <tr>
                            <td align="right">身份证背面：</td>
                            <td>
                                <a href="<?= $userInfo->card_pic2 ?>" target="_blank"><img src="<?= $userInfo->card_pic2 ?>" align="absmiddle" width="100"/></a>

                            </td>
                        </tr>
                    </table>
                <?php else : ?>
                    <script src="/plugin/js/ajaxfileupload.js?111"></script>
                    <form method="post">
                        <table class="table_from">
                            <tr>
                                <td>用户名：</td>
                                <td><?= $this->username ?></td>
                            </tr>
                            <tr>
                                <td>真实姓名：</td>
                                <td><input type="text" name="name" value="<?= $userInfo->name ?>"/></td>
                            </tr>
                            <tr>
                                <td>性别：</td>
                                <td>
                                    <label><input type="radio" name="sex" value="1" checked="checked"/> 男</label>
                                    &nbsp;<label><input type="radio" name="sex" value="2" <? if ($userInfo->sex == 2) {
                                            echo 'checked';
                                        } ?>/> 女</label></td>
                            </tr>
                            <tr>
                                <td>身份证号：</td>
                                <td><input type="text" name="card_no" value="<?= $userInfo->card_no ?>"/></td>
                            </tr>
                            <tr>
                                <td>籍贯：</td>
                                <td><select name="province" id="province" onChange="changeProvince(this.value)">
                                        <option value="0">请选择</option>
                                        <?
                                        foreach ($provinceArray as $region) {
                                            ?>
                                            <option value="<?= $region['id'] ?>"
                                                <? if ($region['id'] == $userInfo->province) {
                                                    echo 'selected';
                                                } ?>><?= $region['name'] ?></option>
                                            <?
                                        }
                                        ?>
                                    </select>
                                    <select name="city" id="city" onChange="changeCity(this.value)"></select>
                                    <select name="county" id="county"></select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">身份证正面：</td>
                                <td>
                                    <input type="hidden" name="card_pic1" id="card1"
                                           value="<?= $userInfo->card_pic1 ?>"/>
						<span id="upload_span_card1">
							<? if ($userInfo->card_pic1 != '') { ?>
                                <a href="<?= $userInfo->card_pic1 ?>" target="_blank"><img
                                        src="<?= $userInfo->card_pic1 ?>" align="absmiddle" width="100"/></a>
                            <? } ?>
                        </span>
                                    <div class="upload-upimg">
                                        <span class="_upload_f">上传文件</span>
                                        <input type="file" id="upload_card1" name="files"
                                               onchange="upload_image('card1','card1')"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">身份证背面：</td>
                                <td>
                                    <input type="hidden" name="card_pic2" id="card2"
                                           value="<?= $userInfo->card_pic2 ?>"/>
						<span id="upload_span_card2">
							<? if ($userInfo->card_pic2 != '') { ?>
                                <a href="<?= $userInfo->card_pic2 ?>" target="_blank"><img
                                        src="<?= $userInfo->card_pic2 ?>" align="absmiddle" width="100"/></a>
                            <? } ?>
                        </span>
                                    <div class="upload-upimg">
                                        <span class="_upload_f">上传文件</span>
                                        <input type="file" id="upload_card2" name="files"
                                               onchange="upload_image('card2','card2')"/>
                                    </div>
                                </td>
                            </tr>
                            <? if ($userInfo->verify_remark != "") { ?>
                                <tr>
                                    <td>审核意见：</td>
                                    <td><?= nl2br($userInfo->verify_remark) ?></td>
                                </tr>
                            <? } ?>
                            <tr>
                                <td>
                                </td>
                                <td><input type="submit" value="保 存"/></td>
                            </tr>
                        </table>
                    </form>
                    <script>
                        $.ajaxSetup({async: false});
                        if ('<?=(int)$userInfo->province?>' != '0') {
                            changeProvince(<?=(int)$userInfo->province?>);
                        }
                        if ('<?=intval($userInfo->city)?>' != '0') {
                            changeCity(<?=intval($userInfo->city)?>);
                        }
                        if ('<?=(int)$userInfo->county?>' != '0') {
                            document.getElementById('county').value = '<?=(int)$userInfo->county?>';
                        }
                    </script>
                <? endif; ?>
            </div>
        </div>
    </div>


<?php require 'footer.php'; ?>