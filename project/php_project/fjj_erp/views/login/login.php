<?php
/**
 * Created by PhpStorm.
 * User: F3858995
 * Date: 2016/9/20
 * Time: 上午 10:50
 */
use yii\helpers\Url;
use app\assets\AppAsset;
use yii\captcha\Captcha;
use yii\widgets\ActiveForm;
use app\assets\IeBlockerAsset;
use app\assets\JqueryUIAsset;

IeBlockerAsset::register($this);
AppAsset::register($this);
JqueryUIAsset::register($this);
$this->title = '登录';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>

<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge;10;9">
    <title><?= $this->title; ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="udp_pwd"></div>
<div class="login-head">
    <div class="login-brand">
        <img class='float-left' src="<?php echo Url::to('@web/img/login/brand.png'); ?>">
        <div class="float-left">
            <p>系统登录</p>
        </div>
    </div>
</div>
<div class="login-main">
    <div class="login-body">
        <div class="back-img">
            <img src="<?php echo Url::to('@web/img/login/background.png'); ?>">
        </div>
        <div class="login-form">
            <?php $form = ActiveForm::begin(); ?>
            <div class="input">
                <?= $form->field($model, 'username', ['template' => '<img src="' . Url::to('@web/img/login/person.png') . '">{input}{error}',
                    'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:40px"]
                ])->textInput(['style' => "width: 300px;"])
                ?>
            </div>
            <div class="input">
                <?= $form->field($model, 'password', ['template' => '<img src="' . Url::to('@web/img/login/lock.png') . '">{input}{error}',
                    'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:40px"]
                ])->passwordInput(['style' => "width: 300px;"])
                ?>
            </div>
            <div class="input">
                <!--                <img src="-->
                <?php //echo Url::to('@web/img/login/code.png'); ?><!--"><input class="width-100">-->
                <?= $form->field($model, 'verifyCode', ['template' => "{input}{error}",
                    'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:40px"]])
                    ->widget(Captcha::className(), ['captchaAction' => 'login/captcha',
                        'template' => '<img src="' . Url::to('@web/img/login/lock.png') . '">{input}{image}',
                        'options' => ['style' => "width: 100px;"],
                        'imageOptions' => ["style" => "height:40px;cursor: pointer;", 'title' => "点击刷新"],

                    ]) ?>
            </div>

            <div style="margin-top: 30px; text-align: center;">
                <button type="submit" class="login-button">登入</button>
                <button class="cancel-button ml-10">取消</button>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
<foot>

</foot>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<script type="text/javascript">
    var fan = "<?= $fancybox ?>";
    $(function () {
        if (fan == 1) {
            $.fancybox.open({
                href: "<?=Url::to(['/index/edit-pwd?first=1'])?>",
                type: 'iframe',
                padding: [],
                fitToView: false,
                width: 500,
                height: 350,
                autoSize: false,
                openEffect: 'none',
                closeEffect: 'none',
                leftRatio: 1,
                topRatio: 0.9,
                closeClick: true,
                beforeClose: function () {
                    window.location.href = "<?=Url::to(['login-out'])?>";
                },
                afterLoad: function () {
                    $('button').attr('disabled', true);
                }
            });
        }
        $('.login-button').click(function () {
            $('button').attr('disabled', false);
        })
    })

</script>
