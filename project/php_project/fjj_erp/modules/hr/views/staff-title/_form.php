<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2017/3/10
 * Time: 下午 03:30
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<style>
    .label-width{
        width: 80px;
    }
    .value-width{

        width: 150px;
    }
</style>
<h2 class="head-second">
    基本信息
</h2>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div class="mb-10">
    <div class="inline-block field-hrstafftitle-title_name required">
        <label for="hrstafftitle-title_name" class="label-width label-align">岗位名称：</label>
        <input type="text" maxlength="50" name="HrStaffTitle[title_name]" class="value-width value-align easyui-validatebox " data-options="required:'true'" id="hrstafftitle-title_name" value="<?= $model['title_name'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstafftitle-title_code required">
        <label for="hrstafftitle-title_code" class="label-width label-align">岗位编号：</label>
        <input type="text" maxlength="50" name="HrStaffTitle[title_code]" class="value-width value-align easyui-validatebox" data-options="required:'true'" id="hrstafftitle-title_code" value="<?= $model['title_code'] ?>">
    </div>
</div>
<div class="mb-20">
    <div class="inline-block field-hrstafftitle-title_description required">
        <!--<label for="hrstafftitle-title_description" class="width-100">岗位描述</label>
        <input type="text" maxlength="50" name="HrStaffTitle[title_description]" class="width-200" id="hrstafftitle-title_description" value="<?/*= $model['title_description'] */?>">-->
      <p class="gang-shu">
            <label for="hrstafftitle-title_description" class="label-width label-align height-54" style="line-height:21px; float:left; margin-right:3px;">岗位描述：</label>
            <textarea name="HrStaffTitle[title_description]" class=" height-54 easyui-validatebox value-align" data-options="required:'true'" id="hrstafftitle-title_description" value="<?= $model['title_description'] ?>" style="width: 600px;height: 100px;"><?= $model['title_description'] ?></textarea>
        </p>
    </div>
</div>
<div class="space-10"></div>
<div class="ml-200 mt-125">
    <?=Html::submitButton('确认',['class'=>'button-blue-big','type'=>'submit','style'=>'margin-left:280px'])?>
    <?=Html::Button('返回',['class'=>'button-white-big ml-10','type'=>'button','onclick'=>'history.go(-1)'])?>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(document).ready(function() {
        ajaxSubmitForm($("#add-form"));
    })
</script>