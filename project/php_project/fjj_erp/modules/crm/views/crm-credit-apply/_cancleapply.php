<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/11
 * Time: 10:53
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use \app\assets\MultiSelectAsset;


MultiSelectAsset::register($this);
?>
<style>
    .width-400 {
        width: 400px;
    }
</style>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div>
    <div style="width:435px;height: 25px;font-size:16px;background-color: #099bff;color: #ffffff;line-height: 25px;padding-left:10px;">
        系统提示
    </div>
    <div style="margin-top: 30px;font-size:16px;font-weight: bolder;margin-left: 20px;"><span class="red">*</span> 请输入取消原因</div>
    <div style="margin-top: 15px;margin-left: 20px;">
        <textarea rows="5" class="width-400 easyui-validatebox" data-options="required:true,validType:'maxLength[200]',tipPosition:'top'" placeholder="最多输入200个字" name="LCrmCreditApply[can_reason]"  maxlength="200"></textarea>
    </div>
    <div style="margin-left: 135px;margin-top: 10px;">
        <button class="button-blue-big" style="width: 80px;" type="submit" id="sure">确定</button>
        <button class="button-white-big" style="width: 80px;" type="button" onclick="close_select();">取消</button>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(document).ready(function() {
        ajaxSubmitForm($("#add-form"));
    });
</script>
