<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/10/20
 * Time: 上午 11:39
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
?>
<div class="content">
<h2 class="head-second">
料号基本信息
</h2>
<?php $form = ActiveForm::begin(['id' => "add-form"]) ?>
<div class="mb-20">
    <div class="mb-20">

        <?= $form->field($model, 'material_code', ['labelOptions' => ['class' => 'width-80'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])
            ->dropDownList($model, ['class' => 'width-200', 'prompt' => "请选择"]); ?>

    </div>

</div>


</div>

