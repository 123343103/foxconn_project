<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/11/1
 * Time: 上午 09:20
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\search\PdNegotiationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pd-negotiation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => false,
        'fieldConfig' => [
            'options'=>['class'=>'search-row'],
            'template' => "{label}\n{input}<div class=\"space-10\"></div>",
            'labelOptions' => ['class' => 'width-110  text-right'],
            'inputOptions' => ['class' => 'width-150'],
        ],
    ]); ?>

    <?= $form->field($model, 'material_code') ?>

    <?= $form->field($model, 'pro_name')/*->dropDownList(ArrayHelper::map($firmType,'bsp_id','bsp_svalue'),['prompt' => '請選擇...'])*/ ?>

    <?= $form->field($model, 'pro_size')/*->dropDownList(['1'=>'是','0'=>'否'],['prompt' => '請選擇...'])*/ ?>

    <?= $form->field($model, 'status') ?>
    <?= $form->field($model, 'create_time') ?>

    <?/*= $form->field($model, 'pdn_status')->dropDownList(['10'=>'待談判','20'=>'談判中','30'=>'談判完成','40'=>'駁回'],['prompt' => '所有廠商']) */?>

    <?= Html::submitButton('查询', ['class' => 'button-blue ml-50']) ?>
    <?= Html::resetButton('重置', ['class' => 'button-blue']) ?>

    <?php ActiveForm::end(); ?>

</div>
<div class="space-20"></div>