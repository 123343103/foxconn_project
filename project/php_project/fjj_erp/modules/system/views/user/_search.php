<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\modules\system\models\search\SearchUser */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="user-search">
    <style>
        .width-70 {
            width: 70px;
        }
        .width-135 {
            width: 135px;
        }
    </style>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get'
    ]); ?>

    <div class="mb-20">
        <label  class="label-width qlabel-align width-70">账号</label><label>：</label>
        <input type="text" name="UserSearch[user_account]" class="width-135"  value="<?= $search['user_account'] ?>">
        <label  class="label-width qlabel-align width-70">工号/姓名</label><label>：</label>
        <input type="text" name="UserSearch[staff_code]" class="width-135" value="<?= $search['staff_code'] ?>">
<!--        <label  class="label-width qlabel-align width-70">姓名</label><label>：</label>-->
<!--        <input type="text" name="UserSearch[staff_name]" class="width-120"  value="--><?//= $search['staff_name'] ?><!--">-->
        <label  class="label-width qlabel-align width-70">公司</label><label>：</label>
        <input type="text" name="UserSearch[company_name]" class="width-135"  value="<?= $search['company_name'] ?>">

        <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue','style'=>'margin-left:100px']) ?>
        <?= Html::resetButton('重置', ['class' =>  'button-blue reset-btn-yellow', 'type' => 'reset','onclick'=>'window.location.href=\''.\yii\helpers\Url::to(["index"]).'\'']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="space-10"></div>
</div>
