<?php require 'header.php'; ?>


<!--<nav>-->
<!--    <span><img src="/themes/member_wap/images/icon_close.png"/>首页</span>-->
<!--</nav>-->
<div class="m_user_title">
    <a href="<?= url('user/userInfo'); ?>">
        <img src="<?= $this->user->headimgurl; ?>">
        <h3><?= $this->username ?>
            <?= $this->user->name ?></h3>
    </a>
</div>
<div class="set" style="margin-bottom: 10rem">
    <ul>
        <li><a href="<?= url('account/index'); ?>"><i><img
                        src="/themes/member_wap/images/icon_right_hui.png"/></i><b><img
                        src="/themes/member_wap/images/pic01.png"></b><span>我的资金</span></a></li>
        </ul>
    <ul>

        <li><a href="<?= url('user/realName'); ?>"><i><img
                        src="/themes/member_wap/images/icon_right_hui.png"/></i><b><img
                        src="/themes/member_wap/images/icon_cash.png"></b><span>实名认证</span></a></li>
</ul><ul>
        <li><a href="<?= url('user/bank'); ?>"><i><img src="/themes/member_wap/images/icon_right_hui.png"/></i><b><img
                        src="/themes/member_wap/images/bank.png"></b><span>我的银行卡</span></a></li>
    </ul>
    <ul>
        <li><a href="<?= url('partner') ?>"><i><img src="/themes/member_wap/images/icon_right_hui.png"/></i><b><img
                        src="/themes/member_wap/images/icon_exchange.png"></b><span>申请合伙人</span></a></li>
    </ul>
    <ul>
        <li><a href="<?= url('password/changePwd'); ?>"><i><img
                        src="/themes/member_wap/images/icon_right_hui.png"/></i><b><img
                        src="/themes/member_wap/images/icon_password.png"></b><span>密码修改</span></a></li>
    </ul>
    <ul>
        <li><a href="<?= url('logout'); ?>"><i><img src="/themes/member_wap/images/icon_right_hui.png"/></i><b><img
                        src="/themes/member_wap/images/icon_bill.png"></b><span>退出</span></a></li>
    </ul>
</div>
<footer>
    <script>
        function mAlert()
        {
            layer.open({
                content: '暂不开放，敬请期待',
                style: '',
                time:10000
            });
        }
    </script>
    <ul>
        <li><a href="<?= url('goApp/10/wap') ?>">
                <b><img src="/themes/member_wap/images/icon_shop.png"/></b>
                <i><img src="/themes/member_wap/images/icon_shop_cor.png"/></i>
                <p>汽车</p>
            </a></li>
        <? if($this->site['id']<3) : ?>
        <li><a href="<?= url('goApp/8/wap') ?>">
                <b><img src="/themes/member_wap/images/icon_shop.png"/></b>
                <i><img src="/themes/member_wap/images/icon_shop_cor.png"/></i>
                <p>云购</p>
            </a></li>
        <li><a href="<?= url('goApp/9/wap'); ?>">
                <b><img src="/themes/member_wap/images/icon_pos.png"/></b>
                <i><img src="/themes/member_wap/images/icon_pos_cor.png"/></i>
                <p>POS机</p>
            </a></li>
            <? else : ?>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
        <? endif;?>
        <li class="cur"><a href="<?=url('')?>">
                <b><img src="/themes/member_wap/images/icon_user.png"/></b>
                <i><img src="/themes/member_wap/images/icon_user_cor.png"/></i>
                <p>账户</p>
            </a></li>
    </ul>
</footer>

<?php require 'footer.php'; ?>
