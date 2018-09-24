<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\common\models\BsTransaction */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .width-50{
        width: 50px;
    }
    .width-100{
        width: 100px;
    }
    .width-250{
        width: 250px;
    }
    .width-619{
        width: 619px;
    }
    .ml-300{
        margin-left: 300px;
    }
</style>
<div class="mb-30">

    <?php $form = ActiveForm::begin(
        ['id' => 'add-form']
    ); ?>
    <div class="mb-10">
        <div class="inline-block field-bsdevcon-dec_code">
            <?php if (Yii::$app->controller->action->id == 'update'){?>
                <label class="width-50 " for="bsdevcon-dec_code"><span class="red">*</span>代码</label><label>：</label>
                <input type="text" disabled id="bsdevcon-dec_code" class="width-250 easyui-validatebox" data-options="required:'true',validType:'length[0,20]'" name="BsDevcon[dec_code]" value="<?=$model->dec_code?>">
            <?php }else{?>
                <label class="width-50 " for="bsdevcon-dec_code"><span class="red">*</span>代码</label><label>：</label>
                <input type="text" id="bsdevcon-dec_code" class="width-250 easyui-validatebox" data-options="required:'true',validType:'length[0,20]'" name="BsDevcon[dec_code]" value="<?=$model->dec_code?>">
            <?php }?>
        </div>

        <div class="inline-block field-bsdevcon-dec_sname ">
            <label class="width-100 ml-50" for="bsdevcon-dec_sname"><span class="red">*</span>交货条件</label><label>：</label>
            <input type="text" id="bsdevcon-dec_sname" class="width-250 easyui-validatebox" data-options="required:'true',validType:'length[0,20]'" name="BsDevcon[dec_sname]" value="<?=$model->dec_sname?>">
        </div>
    </div>
    <div style="margin-top: 20px"></div>
    <div class="mb-10">

        <div class="mb-10">
            <div class="inline-block field-bsdevcon-remarks">
                <label class="vertical-top" for="bsdevcon-remarks" style="width: 62px">备注<label>：</label></label>
                <textarea id="bsdevcon-remarks" class="width-619" name="BsDevcon[remarks]" rows="4" maxlength="255"><?=$model->remarks?></textarea>
                <div class="help-block"></div>
            </div>                </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('确&nbsp认' ,['class' =>'button-blue-big ml-300','id'=>'submit']) ?>&nbsp;
        <?= Html::resetButton('取&nbsp消', ['class' => 'button-white-big ml-20','id'=>'goback' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(function () {
        ajaxSubmitForm($("#add-form"));//ajax提交
        $('#goback').click(function () {
            history.back(-1);
        });
    })
</script>