<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\system\models\search\SearchUser */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .width-150{
        width: 150px;
    }
</style>
<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['role-index'],
        'method' => 'get'
    ]); ?>

    <div class="mb-20">
        <label class="width-70">角色编码/名称<label>：</label></label>
        <input type="text" name="AuthoritySearch[title]" class="width-150"  value="<?= $search['title'] ?>">
<!--        <label class="width-70">状态<label>：</label></label>-->
<!--        <select name="AuthoritySearch['status']" id="" class="width-150">-->
<!--            <option>请选择...</option>-->
<!--            <option value="10">启用</option>-->
<!--            <option value="20">未启用</option>-->
<!--        </select>-->
        <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue ml-20']) ?>
        <?= Html::resetButton('重置', ['class' => 'button-blue reset-btn-yellow', 'type' => 'reset','onclick'=>'window.location.href=\''.\yii\helpers\Url::to(["role-index"]).'\'']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="space-10"></div>
</div>
