<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCreditMaintain */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .ml-20 {
        margin-left: 20px;
    }
    .ml-30 {
        margin-left: 30px;
    }
    .mb-20 {
        margin-bottom: 20px;
    }
    .width-90 {
        width: 90px;
    }
    .width-300 {
        width: 300px;
    }
</style>
<?php $form = ActiveForm::begin([
    'id' => 'add-form',
]); ?>
<div class="ml-30">
    <div class="mb-20">
        <label class="width-90">编&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码</label>
        <label>:</label>
        <span type="text" ><?= $model['code'] ?></span>
    </div>
    <div class="mb-20">
        <label for="" class="width-90"><?= Yii::$app->controller->action->id == 'create' ? '<span class="red">*</span>' : null ?>信用额度类型</label>
        <label>:</label>
        <?php if(\Yii::$app->controller->action->id == "create"){?>
        <input class="width-300 easyui-validatebox" data-options="required:true" name="CrmCreditMaintain[credit_name]" value="<?= $model['credit_name'] ?>" maxlength="15" placeholder="最多输入40个字">
        <?php } else { ?>
            <span><?= $model['credit_name'] ?></span>
        <?php } ?>
    </div>
    <div class="mb-20">
        <label for="" class="width-90">状&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;态</label>
        <label>:</label>
        <select name="CrmCreditMaintain[credit_status]" class="width-300">
            <option value="10" <?= $model['credit_status']=='10'?'selected':null; ?>>启用</option>
            <option value="20" <?= $model['credit_status']=='20'?'selected':null; ?>>禁用</option>
        </select>
    </div>
    <div class="mb-20">
        <label for="" class="width-90 vertical-top">类&nbsp;&nbsp;型&nbsp;&nbsp;描&nbsp;&nbsp;述</label>
        <label class="vertical-top">:</label>
        <textarea name="CrmCreditMaintain[remark]" id="" rows="3" maxlength="200" class="width-300" placeholder="最多输入200个字"><?= $model['remark'] ?></textarea>
    </div>
    <?php if(\Yii::$app->controller->action->id == "update"){?>
        <div class="mb-10">
            <label class="width-90">创建人</label>
            <label>:</label>
            <span type="text" class="width-90"><?= $model['staff_name'] ?></span>
            <label class="width-90">创建时间</label>
            <label>:</label>
            <span type="text"><?= $model['create_at'] ?></span>
        </div>
    <?php } ?>

    <div class="text-center mt-10">
        <button class="button-blue-big" id="confirm">确&nbsp;定</button>
        <button class="button-white-big ml-20" onclick="close_select()">取消</button>
    </div>
</div>

<?php ActiveForm::end(); ?>
<script>
    $(function () {
        ajaxSubmitForm($("#add-form"));
    });
</script>
