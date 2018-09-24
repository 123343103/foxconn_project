<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/9/1
 * Time: 上午 11:06
 */
use app\assets\JqueryUIAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

JqueryUIAsset::register($this);
?>
<h1 class="head-first">系统提示</h1>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div style="margin-left: 15px;">
    请填写取消原因
</div>
<div style="margin:2px 15px 20px;">
    <input type="hidden" id="capply_id" value="<?= $capply_id ?>" name="capply_id">
    <textarea class="easyui-validatebox" data-options="required:'true'" style='width:100%;height:100px;' id="remark" placeholder="请输入取消原因(必填)" maxlength="100" name="remark"></textarea>
</div>

<div style='text-align: center;'>
    <button type="submit" class='button-blue' id="submit">确定</button>
    <button type="button" class='button-white' id="close" style="margin-left: 10px;">取消</button>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        $("#close").on("click", function () {
            parent.$.fancybox.close();
        });
        var capply_id = $("#capply_id").val();
        ajaxSubmitForm($("#add-form"));

    })
</script>