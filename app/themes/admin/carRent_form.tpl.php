<?php
require 'header.php';
if($this->func=='add' || $this->func=='edit') :    ?>
    <div class="main_title">
        <span>租车</span>
        <? if ($this->func == 'add') { ?>新增<? } else { ?>编辑<? } ?>
        <?= $this->anchor('carRent', '列表', 'class="but1"'); ?>
    </div>
    <form method="post" class="layui-form">
        <div class="layui-field-box">
            <div class="layui-form-item">
                <label class="layui-form-label">用户ID</label>
                <div class="layui-input-inline">
                    <input type="text" name="user_id" required placeholder="请填写用户ID" class="layui-input" value="<?=$row->user_id?>" autocomplete="off"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">联系人</label>
                <div class="layui-input-inline">
                    <input type="text" name="contacts" required placeholder="请填写联系人" class="layui-input" value="<?=$row->contacts?>" autocomplete="off"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">联系电话</label>
                <div class="layui-input-inline">
                    <input type="text" name="tel" required placeholder="请填写联系电话" class="layui-input" value="<?=$row->tel?>" autocomplete="off"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">联系地址</label>
                <div class="layui-input-inline">
                    <select name="province" lay-filter="province" required>
                        <option></option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <select name="city" lay-filter="city" required>
                        <option></option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <select name="area" lay-filter="area" required>
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <div class="layui-input-block">
                    <input type="text" name="address" placeholder="请填写详细地址" required class="layui-input" value="<?=$row->address?>" autocomplete="off"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">所选车款</label>
                <div class="layui-input-block">
                    <input type="text" name="car_name" placeholder="请填写车款" required class="layui-input" value="<?=$row->car_name?>" autocomplete="off"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">首付比例</label>
                <div class="layui-input-inline">
                    <select name="first_payment_scale" required>
                        <option value="0" <? if($row->first_payment_scale==0){echo 'selected';}?>>无</option>
                        <option value="0.1" <? if($row->first_payment_scale==0.1){echo 'selected';}?>>10%</option>
                        <option value="0.2" <? if($row->first_payment_scale==0.2){echo 'selected';}?>>20%</option>
                        <option value="0.3" <? if($row->first_payment_scale==0.3){echo 'selected';}?>>30%</option>
                        <option value="0.5" <? if($row->first_payment_scale==0.5){echo 'selected';}?>>50%</option>
                    </select>
                </div>
                <label class="layui-form-label">首付金额</label>
                <div class="layui-input-inline">
                    <input type="text" name="first_payment_money" required placeholder="￥" value="<?=(float)$row->first_payment_money?>" class="layui-input" autocomplete="off"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">尾付比例</label>
                <div class="layui-input-inline">
                    <select name="last_payment_scale" required>
                        <option value="0" <? if($row->last_payment_scale==0){echo 'selected';}?>>无</option>
                        <option value="0.5" <? if($row->last_payment_scale==0.5){echo 'selected';}?>>50%</option>
                    </select>
                </div>
                <label class="layui-form-label">尾付金额</label>
                <div class="layui-input-inline">
                    <input type="text" name="last_payment_money" required placeholder="￥" value="<?=(float)$row->last_payment_money?>" class="layui-input" autocomplete="off"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">租期</label>
                <div class="layui-input-inline">
                    <select name="time_limit" required>
                        <option value="12" <? if($row->time_limit==12){echo 'selected';}?>>12月</option>
                        <option value="24" <? if($row->time_limit==24){echo 'selected';}?>>24月</option>
                        <option value="36" <? if($row->time_limit==36){echo 'selected';}?>>36月</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">月付</label>
                <div class="layui-input-inline">
                    <input type="text" name="month_payment_money" required placeholder="￥" class="layui-input" value="<?=$row->month_payment_money?>" autocomplete="off"/>
                </div>
                <label class="layui-form-label">付款时间</label>
                <div class="layui-input-inline">
                    <select name="month_payment_day" required>
                        <option value="1" <? if($row->month_payment_day==1){echo 'selected';}?>>每月1号</option>
                        <option value="5" <? if($row->month_payment_day==5){echo 'selected';}?>>每月5号</option>
                        <option value="10" <? if($row->month_payment_day==10){echo 'selected';}?>>每月10号</option>
                        <option value="15" <? if($row->month_payment_day==15){echo 'selected';}?>>每月15号</option>
                        <option value="20" <? if($row->month_payment_day==20){echo 'selected';}?>>每月20号</option>
                        <option value="25" <? if($row->month_payment_day==25){echo 'selected';}?>>每月25号</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="*">确认提交</button>
            </div>
        </div>
    </form>
    <script src="/plugin/js/layui_citys.js"></script>
    <script>
        <?php
            $arr=explode('-',$row->area);
        ?>
        $(function () {
            pca.init('select[name=province]', 'select[name=city]', 'select[name=area]', '<?=$arr[0]?>', '<?=$arr[1]?>', '<?=$arr[2]?>');
            layui.form('select').render();
            layui.form().on('submit(*)', function(data){
                var form=data.form;
                var fields=data.field;
                var contacts=$(form).find('input[name=contacts]');
                if(contacts.val()==''){
                    layer.tips('不能为空！', contacts);
                    contacts.focus();
                    return false;
                }
            });
        });
    </script>
    <?
endif;
require 'footer.php';?>