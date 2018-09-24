<?php
//厂商搜索功能页
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\Search\FirmQuery */
/* @var $form yii\widgets\ActiveForm */
?>

<div style="height:30px;">

    <?php $form = ActiveForm::begin([
        'action' => ['/ptdt/firm/select-com'],
        'method' => 'get',
    ]); ?>

            <p class="float-left">
                <input type="text" name="PdFirmQuery[firmMessage]" class="width-200"><img id="img" src="<?= Url::to('@web/img/icon/search.png') ?>" title="搜索">
                <input type="submit" id="sub" style="display:none;">
            </p>
    <?= Html::resetButton('重置', ['class' => 'button-blue','style'=>'height:30px;margin-left:10px;' ,'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["select-com"]).'\'']) ?>
            <p class="float-right mt-5"><a href="<?= Url::to(['/ptdt/firm/create']); ?>" target="_blank"><button type="button" class="button-blue text-center" style="width:80px;">新增厂商</button></a></p>
</div>
<div class="space-10"></div>
    <?php ActiveForm::end(); ?>
<script>
    $(function(){
        $("#img").click(function(){
            $("#sub").click();
        })
    })

</script>
