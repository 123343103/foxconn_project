<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\search\CrmCreditMaintainSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .ml-40 {
        margin-left: 40px;
    }
</style>

<div class="crm-credit-maintain-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="mb-10">
        <label class="qlabel-align">查&nbsp;&nbsp;询</label>
        <label>:</label>
        <input type="text" name="keyWords" class="width-200 qvalue-align" value="<?= $queryParam['keyWords'] ?>">
        <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-40', 'type' => 'submit']) ?>
        <?= Html::button('重置', ['class' => 'reset-btn-yellow', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
