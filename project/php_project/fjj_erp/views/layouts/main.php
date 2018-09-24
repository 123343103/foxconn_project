<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
use yii\widgets\Breadcrumbs;

JqueryUIAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge;10;9">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="warp">
    <nav class="nav">
        <div class="nav-box">

            <div class="nav-brand">
                <div class="nav-brand-img">
                    <img src="<?php echo Url::to('@web/img/layout/brand.png'); ?>">
                </div>
                <div class="nav-brand-font">
                    <p>富金机企业资源管理系统</p>
                </div>
            </div>

            <a href="javascript:void(0)" id="nav-info">
                <div class="nav-info">
                    <img src="<?php echo Url::to('@web/img/layout/avatar.png') ?>" class="float-left">
                    <p class="float-left"><?= isset(yii::$app->user->identity->staff->staff_name) ? yii::$app->user->identity->staff->staff_name : '欢迎登陆' ?></p>
                    <img src="<?php echo Url::to('@web/img/layout/arrow-down.png') ?>" class="float-right">
                </div>
            </a>
            <ul class='nav-action' >
                <li><a href="<?=Url::to(['/hr/staff/my-info']) ?>">个人资料</a></li>
                <li><a href="<?=Url::to(['/index/edit-pwd']) ?>">修改密码</a></li>
                <li><a href="<?=Url::to(['/login/login-out']) ?>">登出</a></li>
            </ul>
        </div>
    </nav>
    <div class="main-container">
        <div class="my-menu">
            <div class="quick-menu">
                <a href="<?= Url::to(['/index/index']) ?>"> <img
                        src="<?php echo Url::to("@web/img/layout/quick-menu-1.png") ?>" id="homeImg"></a>
                <img src="<?php echo Url::to("@web/img/layout/quick-menu-2.png") ?>">
                <img src="<?php echo Url::to("@web/img/layout/quick-menu-3.png") ?>">
            </div>
            <div class="menu-list">
                <div class="space-10"></div>
                <?php
                    $cache_id="menu_".\Yii::$app->user->identity->user_id;
                    if($this->beginCache($cache_id,"",120)) {
                        echo \app\classes\Menu::generateMenu();
                        $this->endCache();
                    }
                ?>
            </div>
        </div>
        <div class="main-content">
            <div style="height: 70px"></div>
            <?= Breadcrumbs::widget([
                'homeLink' =>isset($this->params['homeLike']) ? $this->params['homeLike'] : [],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>

            <?= $content ?>
        </div>

    </div>
</div>
<div class="foot">
    <p class="text-center pt-30">推荐网站: 富贸商城 | 富金机网 | 工业学院 | 版权保护声明</p>
    <p class="text-center mt-10">Copyright@2016 富金机网络科技有限公司 All rights reserved</p>
</div>

<?php $this->endBody() ?>
</body>
<script>
    $(function () {
        var height = $(document).scrollTop();
        $(".menu-list").height($('.main-content').height()-102);
        $(document).scroll(function () {
            setMenuHeight();
        });

        $("#nav-info").on('click',function(){
            $(".nav-action").toggle();
        });

        $(".second-menu>li>span").bind('click',function () {
            $(this).next('i').next(".three-menu").slideToggle();
            $(this).next().toggleClass("icon-caret-left");
        });

//        $(".easyui-validatebox").change(function(){
//            $(this).validatebox();
//        });
    });
</script>
</html>
<?php $this->endPage() ?>

