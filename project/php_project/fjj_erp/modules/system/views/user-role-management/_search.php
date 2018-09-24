<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\system\models\search\SearchUser */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .width-70 {
        width: 70px;
    }

    .width-100 {
        width: 100px;
    }

    .width-150 {
        width: 150px;
    }
</style>
<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['user-index'],
        'method' => 'get'
    ]); ?>

    <div class="mb-20">
        <label class="width-100">角色编号/名称<label>：</label></label>
        <input type="text" name="BsRole[role_name]" class="width-150" value="<?= $search['BsRole']['role_name'] ?>">
        <div class="inline-block">
            <label class="width-100" for="role_state">状态:</label>
            <select name="BsRole[role_state]" class="width-150" id="role_state" >
                <option value=""
                         <?= (empty($search)) ? 'selected="selected"' : '' ?>>全部
                </option>
                <option value="0"
                        <?= ($search['BsRole']['role_state'] == '0' && !empty($search)) ? 'selected="selected"' : '' ?>>
                    禁用
                </option>
                <option value="1"
                         <?= ($search['BsRole']['role_state'] == '1' && !empty($search)) ? 'selected="selected"' : '' ?>>
                    启用
                </option>


            </select>
            <div class="help-block"></div>
        </div>
        <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue ml-20']) ?>
        <?= Html::resetButton('重置', ['class' => 'button-blue reset-btn-yellow', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . \yii\helpers\Url::to(["user-index"]) . '\'']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="space-10"></div>
</div>
