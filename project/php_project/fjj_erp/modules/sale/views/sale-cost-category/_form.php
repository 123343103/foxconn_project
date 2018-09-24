<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/2/20
 * Time: 上午 10:52
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\common\models\BsTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mb-30">

    <?php $form = ActiveForm::begin(
        ['id' => 'add-form']
    ); ?>
    <div class="mb-10">
        <div class="inline-block field-bstransaction-tac_code required">
            <label class="width-100 " for="bstransaction-tac_code">费用代码</label>
            <input type="text" id="bstransaction-tac_code" class="width-200 easyui-validatebox" data-options="required:'true'" name="SaleCostList[stcl_code]" value="<?=$model->stcl_code?>">
        </div>
    </div>
    <div class="space-20"></div>
    <div class="mb-10">
        <div class="mb-10">
            <div class="inline-block field-bstransaction-tac_code required">
                <label class="width-100 " for="bstransaction-tac_code">费用名称</label>
                <input type="text" id="bstransaction-tac_code" class="width-200 easyui-validatebox" data-options="required:'true'" name="SaleCostList[stcl_sname]" value="<?=$model->stcl_sname?>">
            </div>
        </div>
    </div>
    <div class="space-20"></div>
    <div class="mb-10">
        <div class="inline-block field-bstransaction-tac_code required">
            <label class="width-100 " for="bstransaction-tac_code">费用类型</label>
            <!--<input type="text" id="bstransaction-tac_code" class="width-250 easyui-validatebox" data-options="required:'true'" name="BsTransaction[tac_code]" value="<?/*=$model->tac_code*/?>">-->
            <select name="SaleCostList[scost_id]" class="width-200">
                <option value="">请选择</option>
                <?php foreach ($saleCostTypeValue as $key => $val) { ?>
                    <?php if (isset($model)) { ?>
                        <option
                            value="<?= $key ?>" <?= $model->scost_id == $key ? "selected" : null ?>><?= $val ?> </option>
                    <?php } else { ?>
                        <option value="<?= $key ?>"><?= $val ?> </option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="space-20"></div>
    <div class="mb-10">
        <div class="inline-block field-bstransaction-tac_code required">
            <label class="width-100 " for="bstransaction-tac_code">状态</label>
            <!--<input type="text" id="bstransaction-tac_code" class="width-250 easyui-validatebox" data-options="required:'true'" name="BsTransaction[tac_code]" value="<?/*=$model->tac_code*/?>">-->
            <select  name="SaleCostList[stcl_status]" class="width-200">
                <?php foreach ($stclStatus as $key => $val) { ?>
                    <?php if (isset($model)) { ?>
                        <option
                            value="<?= $key ?>" <?= $model->stcl_status == $key ? "selected" : null ?>><?= $val ?> </option>
                    <?php } else { ?>
                        <option value="<?= $key ?>"><?= $val ?> </option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="space-20"></div>
    <div class="mb-10">
        <div class="mb-10">
            <div class="inline-block field-bstransaction-remarks">
                <label class="width-100 vertical-top" for="bstransaction-remarks">描述</label>
                <textarea id="bstransaction-remarks" class="width-500" name="SaleCostList[stcl_description]" rows="3"><?=$model->stcl_description?></textarea>
                <div class="help-block"></div>
            </div>                </div>
    </div>
    <div class="space-20"></div>
    <div class="mb-10">
        <div class="mb-10">
            <div class="inline-block field-bstransaction-remarks">
                <label class="width-100 vertical-top" for="bstransaction-remarks">备注</label>
                <textarea id="bstransaction-remarks" class="width-500" name="SaleCostList[stcl_remark]" rows="3"><?=$model->stcl_remark?></textarea>
                <div class="help-block"></div>
            </div>
        </div>
    </div>
    <div class="space-20"></div>
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
