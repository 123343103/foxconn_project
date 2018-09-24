<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCreditApply */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crm-credit-apply-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cust_id')->textInput() ?>

    <?= $form->field($model, 'credit_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'accessory1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'accessory2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apply_remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit_date')->textInput() ?>

    <?= $form->field($model, 'credit_people')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'credit_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_id')->textInput() ?>

    <?= $form->field($model, 'create_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'update_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <?= $form->field($model, 'standby1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'standby2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'standby3')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
