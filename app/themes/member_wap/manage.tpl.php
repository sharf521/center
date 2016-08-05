<?php require 'header.php';?>


<nav>
    <span><img src="/themes/member_wap/images/icon_close.png" /></span>
    <p>首页</p>
</nav>
<div class="m_user_title">
    <a href="person_msg.html">
        <img src="/themes/member_wap/images/userpic.jpg">
        <h3>木鱼与鱼</h3>
    </a>
</div>
<div class="set">
    <ul>
        <li><a href="balance.html"><i><img src="/themes/member_wap/images/icon_right_hui.png" /></i><b><img src="/themes/member_wap/images/pic01.png"></b><span>可用资金</span></a></li>
    </ul>
    <ul>
        <li><a href="recharge.html"><i><img src="/themes/member_wap/images/icon_right_hui.png" /></i><b><img src="/themes/member_wap/images/icon_recharge.png"></b><span>账户充值</span></a></li>
        <li><a href="cash.html"><i><img src="/themes/member_wap/images/icon_right_hui.png" /></i><b><img src="/themes/member_wap/images/icon_cash.png"></b><span>账户提现</span></a></li>
    </ul>
    <ul>
        <li><a href="integral_eschange.html"><i><img src="/themes/member_wap/images/icon_right_hui.png" /></i><b><img src="/themes/member_wap/images/icon_exchange.png"></b><span>积分兑换</span></a></li>
    </ul>
    <ul>
        <li><a href="bank_list.html"><i><img src="/themes/member_wap/images/icon_right_hui.png" /></i><b><img src="/themes/member_wap/images/bank.png"></b><span>绑定银行卡</span></a></li>
    </ul>
    <ul>
        <li><a href="<?=url('user/changePwd');?>"><i><img src="/themes/member_wap/images/icon_right_hui.png" /></i><b><img src="/themes/member_wap/images/icon_password.png"></b><span>密码修改</span></a></li>
    </ul>
    <ul>
        <li><a href="bill_list.html"><i><img src="/themes/member_wap/images/icon_right_hui.png" /></i><b><img src="/themes/member_wap/images/icon_bill.png"></b><span>账单记录</span></a></li>
    </ul>
</div>
<footer>
    <ul>
        <li class="cur"><a href="<?=url('goApp/5')?>">
                <b><img src="/themes/member_wap/images/icon_shop.png"/></b>
                <i><img src="/themes/member_wap/images/icon_shop_cor.png"/></i>
                <p>商城</p>
            </a></li>
        <li><a href="#">
                <b><img src="/themes/member_wap/images/icon_pos.png"/></b>
                <i><img src="/themes/member_wap/images/icon_pos_cor.png"/></i>

                <p>pos机</p>
            </a></li>
        <li><a href="#">
                <b><img src="/themes/member_wap/images/icon_user.png"/></b>
                <i><img src="/themes/member_wap/images/icon_user_cor.png"/></i>
                <p>账户</p>
            </a></li>
    </ul>
</footer>

<?php require 'footer.php';?>
