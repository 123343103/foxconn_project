<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/10/10
 * Time: 下午 02:58
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->params['homeLike'] = ['label'=>'人事资料','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'岗位信息'];
$this->params['breadcrumbs'][] = ['label'=>'新增岗位'];
$this->title = '新增岗位';
?>
<style>
    .value-width{

        width:150px;
    }
    .label-width{

        width: 80px;
    }
</style>
<!--<div class="content">
    <h1 class="head-first">
        新增信息
        </h1>
    <?/*=$this->render('_form')*/?>
    </div>-->
<div class="content">
<h1 class="head-first">
    新增信息
</h1>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div class="mb-10">
    <div class="inline-block field-hrstafftitle-title_name required">
        <label for="hrstafftitle-title_name" class="label-width label-align">岗位名称：</label>
        <input type="text" maxlength="50" name="HrStaffTitle[title_name]" class="value-align value-width  easyui-validatebox " data-options="required:'true'" id="hrstafftitle-title_name" value="<?= $model['title_name'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstafftitle-title_code required">
        <label for="hrstafftitle-title_code" class=label-width label-align">岗位编号：</label>
        <input type="text" maxlength="50" name="HrStaffTitle[title_code]" class=" value-width value-align easyui-validatebox" id="hrstafftitle-title_code" data-options="required:'true'" value="<?= $model['title_code'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstafftitle-title_description required">
        <p class="gang-shu">
        <label for="content" class="label-width label-align height-54" style="line-height:21px; float:left; margin-right:3px;">岗位描述：</label>
        <!--<input type="text" maxlength="50" name="HrStaffTitle[title_description]" class="width-390 height-54" id="hrstafftitle-title_description" value="<?/*= $model['title_description'] */?>">-->
        <textarea name="HrStaffTitle[title_description]" class="value-align  height-54 easyui-validatebox" id="" data-options="required:'true'" value="<?= $model['title_description'] ?>" style="width: 600px;height: 100px;"></textarea>
        </p>
    </div>
</div>
    <div class="space-10"></div>
<div>
    <?=Html::submitButton('确认',['class'=>'button-blue-big','type'=>'submit','id'=>'sub-button','style'=>'margin-left:240px'])?>
    <?= Html::resetButton('重置', ['class' => 'button-blue-big ml-10', 'type' => 'reset','onclick'=>'window.location.href=\''.\yii\helpers\Url::to(["create"]).'\'']) ?>
    <?=Html::Button('返回',['class'=>'button-white-big ml-10','type'=>'button','style'=>'margin-left:0px','onclick'=>'history.go(-1)'])?>
</div>
<?php ActiveForm::end(); ?>
    </div>
<script>
    $(document).ready(function() {
        ajaxSubmitForm($("#add-form"));

       /* var $titleCode = $('#hrstafftitle-title_code');
        $("#sub-button").on("click", function () {
            if ($titleCode.val() == "") {
                layer.alert("请填写岗位编号", {icon: 2, time: 5000});
            }
        });*/

    })
</script>
