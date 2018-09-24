<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * F3858995
 * 2016/10/20
 */
?>

<style type="text/css">
    .mb-20 {
        margin-bottom: 20px;
    }

    .mt-20 {
        margin-top: 20px;
    }

    .width-100 {
        width: 100px !important;
    }

    .mb-50 {
        margin-bottom: 50px;
    }
</style>
<?= $first ? '<div class="head-first">系统提示</div>' : null ?>
<ul class="breadcrumb">
    <?= $first ? '<div class="index-head mt-20"><p>初次使用请修改密码</p></div>' : '<div class="index-head"><p><span>|</span>修改密码</p></div>' ?>
    <!--    <div class="index-head"><p><span>|</span>修改密码</p></div>-->
</ul>
<div class="content">
    <div class="<?= $first ? 'mt-40' : 'mt-20' ?> text-center">
        <?php $form = ActiveForm::begin([
                'id' => 'form-id',
                'enableAjaxValidation' => true,
                'validationUrl' => Url::to(['/index/validate-form']),]
        ) ?>
        <input type="hidden" name="EditPwdForm[username]" value="<?= \Yii::$app->user->identity->user_account; ?>">
        <div class="mb-20">
            <?= $form->field($model, "oldPwd", ['labelOptions' => ['class' => 'width-100 text-left'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:105px"]])
                ->passwordInput() ?>
        </div>
        <div class="mb-20">
            <?= $form->field($model, "newPwd", ['labelOptions' => ['class' => 'width-100 text-left'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:105px"]])
                ->passwordInput() ?>
        </div>
        <div class="mb-50">
            <?= $form->field($model, "newPwdV", ['labelOptions' => ['class' => 'width-100 text-left'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:105px"]])
                ->passwordInput() ?>
        </div>
        <div>
            <?= Html::submitButton('提交', ['class' => 'button-blue-big', 'type' => 'submit']) ?>
            <?= $first ? null : (Html::resetButton('返回', ['class' => 'button-white-big ml-20', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["/index/index"]) . '\''])) ?>
        </div>
        <?php $form::end() ?>
    </div>
</div>
<script>
    $(function () {
        var first = "<?= $first ?>";
        if (first == 1) {
            ajaxSubmitForm($("#form-id"), '', function (data) {
                window.top.location.href = "<?=Url::to(['index/index'])?>";
            });
        } else {
            ajaxSubmitForm($("#form-id"))
        }
        $('button[type="submit"]').click(function () {
            setTimeout('$(\'button[type="submit"]\').attr("disabled",false)', 100)
        })
    })
</script>
