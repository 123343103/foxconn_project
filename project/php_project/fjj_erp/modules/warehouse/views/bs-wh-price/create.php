<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/12
 * Time: 下午 03:38
 */
use app\assets\JqueryUIAsset;
use yii\widgets\ActiveForm;

JqueryUIAsset::register($this);
?>
<style>
    .ml-10 {
        margin-bottom: 10px;
    }

    .mb-30 {
        margin-bottom: 30px;
    }

    .width-100 {
        width: 100px;
    }

    .width-200 {
        width: 200px;
    }

    .width-400 {
        width: 400px;
    }

    .label-right {
        text-align: right;
    }

    .label-left {
        text-align: left;
    }

    .value-left {
        text-align: left;
    }
</style>
<h1 class="head-first"> 新增费用信息</h1>
<div class="content">

    <?php $from = ActiveForm::begin(['id' => 'from']); ?>

    <div class="ml-10">
        <div class="inline-block">
            <label class="width-100 label-right">费用名称：</label>
            <input class="width-200 label-left easyui-validatebox" data-options="required:true" type="text" maxlength="20" name="BsWhPrice[whpb_sname]">
        </div>
    </div>
    <div class="ml-10">
        <div class="inline-block">
            <label class="width-100 label-right">状态：</label>
            <select name="BsWhPrice[stcl_status]" class="width-200 value-left">
                <option value="1" selected="selected">启用</option>
                <option value="0">禁用</option>
            </select>
        </div>
    </div>
    <div class="ml-10">
        <div class="inline-block">
            <label class="vertical-top width-100 label-right"">描述：</label>
            <textarea id="remarks" maxlength="100" class="width-400 value-left" name="BsWhPrice[stcl_description]"
                      placeholder="字数不能超过100 " rows="4"></textarea>
        </div>
    </div>
    <div class="mb-20 text-center" id="buttons">
        <button class="button-blue-big" type="submit" id="submit">保存</button>
        <button class="button-white-big ml-20" type="button" id="back">取消</button>
    </div>
    <?php $from->end(); ?>
</div>
<script>
    $(function () {
        ajaxSubmitForm($('form'));
    })
    $("#back").click(function () {
        parent.$.fancybox.close();
    });
</script>
