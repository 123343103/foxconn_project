<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


?>
<h1 class="head-first">
    <?= $this->title; ?>
</h1>
<style>
    .width100{
        width: 100px;
    }
    .width120{
        width: 120px;
    }
    .width300{
        width: 300px;
    }
    .mb-15{
        margin-bottom:15px;
    }
    .mb-20{
        margin-bottom: 20px;
    }
</style>
<div class="space-40"></div>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div class="mb-10">
    <label class="label-width qlabel-align width100 ml-20"><span class="red">*</span>按钮名称<label>：</label></label>
    <input class="width300 easyui-validatebox btn_no" type="text"  name="BsBtn[btn_no]" style="ime-mode:disabled"
           value="<?= $model['btn_no'] ?>"  data-options="required:'true',validType:'unique',delay:10000,validateOnBlur:'true'" data-act="<?=Url::to(['validate'])?>"
           data-id="<?=$model['btn_pkid'];?>" data-attr="btn_no" <?php if(!empty($model)){ ?> readonly="readonly" <?php } ?>  maxlength="20">
    <label class="width300 text-left no" ><?= $model['btn_no'] ?></label>
</div>
<div class="mb-20">
    <label class="label-width qlabel-align width100 ml-20"><span class="red">*</span>按钮<label>：</label></label>
    <input class="width300 easyui-validatebox" type="text" name="BsBtn[btn_name]"
           value="<?= $model['btn_name'] ?>"  data-options="required:'true'" maxlength="200">
</div>
<div class="mb-20">
    <label class="label-width qlabel-align width100 ml-20">有效否<label>：</label></label>
    <select class="width300 easyui-validatebox" type="text" name="BsBtn[btn_yn]"
            data-options="required:'true'">
        <option value="1" <?= isset($model['btn_yn']) && $model['btn_yn'] == 1 ? "selected" : null ?>>是</option>
        <option value="0" <?= isset($model['btn_yn']) && $model['btn_yn'] == 0 ? "selected" : null ?>>否</option>
    </select>
</div>
<div class="mb-15 text-center" style="margin-top: 50px">
    <button class="button-blue-big" type="submit">保存</button>
    <button  onclick="close_select()" class="button-white-big ml-20" type="button">取消</button>
</div>
<?php ActiveForm::end() ?>
<script>
    $(function () {
        ajaxSubmitForm($("#add-form"));

        var btn_no=$(".btn_no").val();
        if(btn_no!=""&&btn_no!=null){
            $(".btn_no").css("display","none");
            // $(".code").removeAttr("display");
        }else {
            $(".no").removeClass("width200");
        };
        //判断是否为IE浏览器
        if(!!window.ActiveXObject || "ActiveXObject" in window){
        }
        else {
            $(".btn_no").bind("keyup",function(){
                $(".btn_no").val($(".btn_no").val().replace(/[\u4e00-\u9fa5]/g,''));
            });
        }
    })
</script>

