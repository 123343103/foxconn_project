<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\Search\PdFirmReportSearch */
/* @var $form yii\widgets\ActiveForm */
//dumpE($firmCategoryToValue);
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>

<div class="mb-10">
    <?= $form->field($model, 'supplier_sname',['labelOptions' => ['class' => 'width-110']])->label('供应商名称')->input('text',['class' => 'width-150']) ?>
    <?= $form->field($model, 'supplier_brand',['labelOptions' => ['class' => 'width-100']])->label('品牌')->input('text',['class' => 'width-150']) ?>
    <?= $form->field($model, 'supplier_type', ['labelOptions' => ['class' => 'width-100']])->label('供应商分类')->dropDownList(ArrayHelper::map($downList['firmType'],'bsp_id','bsp_svalue'), ['class' => 'width-150', 'prompt' => "请选择"]) ?>
</div>
<div class="mb-10">
    <?= $form->field($model, 'supplier_issupplier', ['labelOptions' => ['class' => 'width-110']])->label('是否为集团供应商')->dropDownList(['1'=>'是','0'=>'否'], ['class' => 'width-150', 'prompt' => "请选择"]) ?>
    <?= $form->field($model, 'supplier_status', ['labelOptions' => ['class' => 'width-100']])->dropDownList(['10'=>'待评鉴', '20'=>'待申请', '30'=>'审核中','40'=>'已驳回','50'=>'已完成'], ['class' => 'width-150', 'prompt' => "请选择"]) ?>
    <?= $form->field($model, 'supplier_category_id', ['labelOptions' => ['class' => 'width-100']])->label('供应商来源')->dropDownList(ArrayHelper::map($downList['productType'],'type_id','type_name'), ['class' => 'width-150', 'prompt' => "请选择"]) ?>
    <?= Html::submitButton('查询', ['class' => 'button-blue ml-40']) ?>
    <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["index"]).'\'']) ?>
</div>
<div class="mb-10">

</div>

<?php ActiveForm::end(); ?>
<div class="space-30"></div>