<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2017/3/3
 * Time: 上午 09:08
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<style>
    .label-width{

        width: 80px;;
    }
    .value-width
    {
        width: 120px;;
    }
</style>
<div class="search-div">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get'
    ]); ?>
    <div class="mb-20">
        <label for="hr_staff_title_name" class=" qlabel-align">岗位名称：</label>
        <input type="text" name="HrStaffTitleSearch[title_name]" class="value-width value-align" id="hr_staff_title_name" value="<?= $search['title_name'] ?>"/>
        <label for="hr_staff_title_code" class="qlabel-width label-align">岗位编号：</label>
        <input type="text" name="HrStaffTitleSearch[title_code]" class="value-width value-align" id="hr_staff_title_code" value="<?= $search['title_code'] ?>"/>
        <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-395','style'=>'margin-left:20px']) ?>
        <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow', 'type' => 'reset','onclick'=>'window.location.href=\''.\yii\helpers\Url::to(["index"]).'\'']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="space-10"></div>
<div class="space-10"></div>
<div class="space-10"></div>
