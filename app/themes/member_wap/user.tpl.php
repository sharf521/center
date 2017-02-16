<?php require 'header.php';?>
        <?php if($this->func=='userInfo'): ?>
            <div class="header">
                <a class="header_left" href="<?=url('')?>" ><i class="iconfont">&#xe603;</i>返回</a>
                <span class="header_right">&nbsp;</span>
                <h1>编辑个人资料</h1>
            </div>
            <script src="/plugin/js/ajaxfileupload.js"></script>
            <div class="ca_d_table input_right margin_header">
                <form method="post">
                    <table width="100%">
                        <tbody>
                        <tr>
                            <td align="right" style="width:12rem;">头像：</td>
                            <td colspan="3">
                            <span class="photo">
                                <span id="upload_span_headimgurl"><img src="<?=$user->headimgurl?>"></span>
                                <input type="hidden" name="headimgurl" value="<?=$user->headimgurl?>" id="headimgurl">
                                <input type="file" id="upload_headimgurl" accept="image/*" name="file" onchange="upload_image('headimgurl','headimgurl')">
                            </span></td>
                        </tr>
                        <tr><td align="right" style="width:12rem;">联系电话：</td><td><input type="text" name="tel" value="<?=$user->tel?>" class="nam_inpou" /></td></tr>
                        <tr><td align="right" style="width:12rem;">联系QQ： </td><td><input type="text" name="qq" class="nam_inpou" value="<?=$user->qq?>" onKeyUp="value=value.replace(/[^0-9.]/g,'')"/></td></tr>
                        <tr><td align="right" style="width:12rem;">联系地址：</td><td><input type="text" name="address" class="nam_inpou" value="<?=$user->address?>"/></td></tr>
                        <tr>
                            <td colspan="4" align="center"><input class="cada_tba" type="submit" value="保存"></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <?php elseif($this->func=='bank'): ?>
            <div class="header">
                <a class="header_left" href="javascript:history.go(-1)" ><i class="iconfont">&#xe603;</i>返回</a>
                <span class="header_right">&nbsp;</span>
                <h1>我的银行卡</h1>
            </div>

            <div class="ca_d_table margin_header">
                <? if($userInfo->card_status!=2) : ?>
                    <div class="alert-warning" role="alert">您还没有完成实名认证，请先完成<?=$this->anchor('user/realName','>>实名认证>>');?></div>
                <?php else : ?>
                    <? if($bank->account==""){  ?>
                        <form method="post">
                            <table class="table_from">
                                <tr><td >用户名：</td><td><?=$this->user->username?></td></tr>
                                <tr><td >真实姓名：</td><td><?=$this->user->name?></td></tr>
                                <tr><td>开户银行：</td><td><?=$bank->selBank?></td></tr>
                                <tr><td >开户支行：</td><td><input  name="branch" type="text" value="<?=$bank->branch?>"/></td></tr>
                                <tr><td >银行账号：</td><td><input  name="card_no" type="text" value="<?=$bank->card_no?>"/></td></tr>
                            </table>
                            <input type="submit" value="保 存" class="submit" />
                        </form>
                    <? }else{ ?>
                        <table class="table_from">
                            <tr><td >用户名：</td><td><?=$this->user->username?></td></tr>
                            <tr><td >真实姓名：</td><td><?=$this->user->name?></td></tr>
                            <tr><td align="right">开户银行：</td><td><?=$bank->bank?></td></tr>
                            <tr><td align="right">开户支行：</td><td><?=$bank->branch?></td></tr>
                            <tr><td align="right">银行账号：</td><td><?=$bank->card_no?></td></tr>
                        </table>
                    <? }?>
                <? endif ?>
            </div>
        <?php endif;?>
<?php require 'footer.php';?>
