<?php require 'header.php';?>
<div class="main_title">
    <span>Tree2管理</span>
    <?=$this->anchor('tree2','列表','class="but1"');?>
</div>
<div class="main_content">
    <fieldset class="layui-elem-field">
        <legend>默认---旧</legend>
        <div class="layui-field-box">
            <blockquote class="layui-elem-quote">
                //2层满给1万，3层满见点5000,4层满拐一提车，5层满拐一过户<br>
                //2层满给1万,<br>
                //第4层见点5000（3层满），<br>
                //第5层第一个提车（4层满）<br>
                //第6层第一个过户(5层满)</blockquote>
            <form method="post">
                <input type="hidden" name="typeid" value="default">
                <table class="table_from">
                    <tr><td>套餐金额：</td><td><input type="text" name="money" value="27500"/>元</td></tr>
                    <tr><td>提车支出：</td><td><input type="text" name="car_money" value=""/>元</td></tr>
                    <tr><td>过户支出：</td><td><input type="text" name="transfer_money" value=""/>元</td></tr>
                    <tr><td></td><td><input type="submit" class="but3" value="重新开始计算" />
                            <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
                </table>
            </form>
        </div>
    </fieldset>


    <fieldset class="layui-elem-field">
        <legend>一、套餐：12000元，推荐两个提车，三层满给一万</legend>
        <div class="layui-field-box">
            <form method="post">
                <input type="hidden" name="typeid" value="type1">
                <table class="table_from">
                    <tr><td>套餐金额：</td><td><input type="text" name="money" value="12000"/>元</td></tr>
                    <tr><td>提车支出：</td><td><input type="text" name="car_money" value=""/>元</td></tr>
                    <tr><td></td><td><input type="submit" class="but3" value="重新开始计算" />
                            <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
                </table>
            </form>
        </div>
    </fieldset>

    <fieldset class="layui-elem-field">
        <legend>二、套餐：22000元,推荐一个提车，两个给1万，三层满给1万，四层见点5000</legend>
        <div class="layui-field-box">
            <form method="post">
                <input type="hidden" name="typeid" value="type2">
                <table class="table_from">
                    <tr><td>套餐金额：</td><td><input type="text" name="money" value="22000"/>元</td></tr>
                    <tr><td>提车支出：</td><td><input type="text" name="car_money" value=""/>元</td></tr>
                    <tr><td></td><td><input type="submit" class="but3" value="重新开始计算" />
                            <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
                </table>
            </form>
        </div>
    </fieldset>

    <fieldset class="layui-elem-field">
        <legend>三、套餐：17500元，推荐1个提车，两个给1万，三层满给1万</legend>
        <div class="layui-field-box">
            <form method="post">
                <input type="hidden" name="typeid" value="type3">
                <table class="table_from">
                    <tr><td>套餐金额：</td><td><input type="text" name="money" value="17500"/>元</td></tr>
                    <tr><td>提车支出：</td><td><input type="text" name="car_money" value=""/>元</td></tr>
                    <tr><td></td><td><input type="submit" class="but3" value="重新开始计算" />
                            <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
                </table>
            </form>
        </div>
    </fieldset>


    <fieldset class="layui-elem-field">
        <legend>四、套餐:27500元，自提一台车，推荐两个给1万，三层满给一万</legend>
        <div class="layui-field-box">
            <form method="post">
                <input type="hidden" name="typeid" value="type4">
                <table class="table_from">
                    <tr><td>套餐金额：</td><td><input type="text" name="money" value="27500"/>元</td></tr>
                    <tr><td>提车支出：</td><td><input type="text" name="car_money" value=""/>元</td></tr>
                    <tr><td></td><td><input type="submit" class="but3" value="重新开始计算" />
                            <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
                </table>
            </form>
        </div>
    </fieldset>

    <fieldset class="layui-elem-field">
        <legend>五、套餐、36000 自提车，推荐2个给1万，三层满给30000，四层满见点5000</legend>
        <div class="layui-field-box">
            <form method="post">
                <input type="hidden" name="typeid" value="type5">
                <table class="table_from">
                    <tr><td>套餐金额：</td><td><input type="text" name="money" value="27500"/>元</td></tr>
                    <tr><td>提车支出：</td><td><input type="text" name="car_money" value=""/>元</td></tr>
                    <tr><td>二层满支出：</td><td><input type="text" name="layer2full_money" value="10000"/>元</td></tr>
                    <tr><td>二层满支出：</td><td><input type="text" name="layer3full_money" value="30000"/>元</td></tr>
                    <tr><td></td><td><input type="submit" class="but3" value="重新开始计算" />
                            <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
                </table>
            </form>
        </div>
    </fieldset>

    <fieldset class="layui-elem-field">
        <legend>六、套餐：12000元，推荐两个提车，三层满给1万，四层见点5000</legend>
        <div class="layui-field-box">
            <form method="post">
                <input type="hidden" name="typeid" value="type6">
                <table class="table_from">
                    <tr><td>套餐金额：</td><td><input type="text" name="money" value="12000"/>元</td></tr>
                    <tr><td>提车支出：</td><td><input type="text" name="car_money" value=""/>元</td></tr>
                    <tr><td></td><td><input type="submit" class="but3" value="重新开始计算" />
                            <input type="button" class="but3" value="返回" onclick="window.history.go(-1)"/></td></tr>
                </table>
            </form>
        </div>
    </fieldset>
</div>
<?php
require 'footer.php';