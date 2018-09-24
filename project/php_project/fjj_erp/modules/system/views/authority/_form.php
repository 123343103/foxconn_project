<?php
/**
 * F3858995
 * 2016/10/10
 */
use app\assets\MultiSelectAsset;
use yii\widgets\ActiveForm;
MultiSelectAsset::register($this);
?>
<style>
    .ml-300 {
        margin-left: 300px;
    }
    .ml-20 {
        margin-left: 20px;
    }
    .mb-20 {
        margin-bottom: 20px;
    }
    .width-100 {
        width: 100px;
    }
    .width-200 {
        width: 200px;
    }
    .width-607 {
        width: 607px;
    }
    label:after{
        content: "：";
    }
</style>
<?php $form = ActiveForm::begin([
    'id'=>"add-form",
    'enableAjaxValidation'=>$model->isNewRecord ? true : false, //开启Ajax验证
    'validationUrl' => \yii\helpers\Url::to(['/system/authority/ajax-validation']),
]) ?>
<div class="mb-20">
    <?= $form->field($model, 'code', ['labelOptions' => ['class' => 'width-100'], 'errorOptions' => ['class' => 'error-notice easyui-validatebox', 'style' => "margin-left:105px"]])
        ->textInput(["class" => "width-200"]); ?>
    <?= $form->field($model, "title", ['labelOptions' => ['class' => 'width-200'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:205px"]])
        ->textInput(["class" => "width-200"]); ?>
</div>
<div class="mb-20">
    <?= $form->field($model, "description", ['labelOptions' => ['class' => 'width-100'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:105px"]])
        ->textInput(["class" => "width-607"]); ?>
</div>
<div class="mb-20">
    <select multiple="multiple" id="my-select" name="AuthItem[AuthItemChildren][]" >
        <?php foreach ($authority as $k => $v) { ?>
            <optgroup label="<?= $k ?>">
                <?php foreach ($v as $key => $val) { ?>
                    <option value="<?=$val['action_url'] ?>"><?= $val['action_title'] ?></option>
                <?php } ?>
            </optgroup>
        <?php } ?>
    </select>
</div>

<div class="ml-300 mb-20">
    <?php if(Yii::$app->controller->action->id != "view" ) { ?>
    <button class="button-blue-big" type="submit" >提交</button>
    <?php   } ?>
    <button class="button-white-big ml-20" type="button" onclick="location.href='<?=\yii\helpers\Url::to(["role-index"]) ?>'">返回</button>

</div>
<?php ActiveForm::end() ?>
<script>
    $(function(){
        ajaxSubmitForm($("#add-form"));
        $("button[type='submit']").click(function () {
            setTimeout("$(\"button[type=\'submit\']\").attr(\'disabled\',false)",100);
        });
        $("#my-select").multiSelect({
            'selectableOptgroup': true
        });
        <?php if(isset($permissions)){ ?>
            <?php foreach ( $permissions as $key => $val ){ ?>
                $("#my-select").multiSelect("select","<?= $val->name ?>");
            <?php } ?>
        <?php } ?>
        if(<?= Yii::$app->controller->action->id== "view"?1:0 ?> ){
            $("input").attr("readOnly","readOnly");
        }

    });

</script>
