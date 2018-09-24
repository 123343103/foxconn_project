<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/11/21
 * Time: 上午 10:46
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<style>
    .label-width {

        width: 120px;
    }

    .value-align {

        width: 150px;
    }

</style>
<h2 class="head-first">菜单列表</h2>
<div class="space-10"></div>
<div class="space-10"></div>
<div class="menu-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get'
    ]); ?>
    <div class="mb-10">
        <label class="lable-align label-width">菜单名称：</label>
        <input type="text" name="MenuSearch[menu_name]" class="value-widh value-align"
               value="<?= $search['menu_name'] ?>">
        <label class="label-align label-width">有效否：</label>
        <select name="MenuSearch[yn]" class="vlaue-width value-align">
            <option value="" <?php if ($search['yn'] == '') echo 'selected'; ?>>全部</option>
            <option value="1" <?php if ($search['yn'] == '1') echo 'selected'; ?>>是</option>
            <option value="0" <?php if ($search['yn'] == '0') echo 'selected'; ?>>否</option>
        </select>
        <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue', 'style' => 'margin-left:60px']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>
