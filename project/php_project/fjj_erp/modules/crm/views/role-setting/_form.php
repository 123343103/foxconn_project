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
    .width200{
        width: 200px;
    }
    .mb-15{
        margin-bottom:15px;
    }
</style>
<div class="space-40"></div>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div class="mb-10">
    <label class="label-width qlabel-align width100 ml-20"><span class="red">*</span>销售角色编码<label>：</label></label>
    <input class="width200 easyui-validatebox sarole_code" type="text" name="CrmSaleRoles[sarole_code]" style="ime-mode:disabled"
           value="<?= $model['sarole_code'] ?>"  data-options="required:'true',validType:'unique',delay:10000,validateOnBlur:'true'" maxlength="20"  data-act="<?=Url::to(['validate'])?>" data-id="<?=$model['sarole_id'];?>" data-attr="sarole_code" <?php if(!empty($model)){ ?> readonly="readonly" <?php } ?>>
    <label class="width200 text-left code" name="CrmSaleRoles[sarole_code]" display="none"><?= $model['sarole_code'] ?></label>
    <label class="width120"><span class="red">*</span>销售角色名称<label>：</label></label>
    <input class="width200 easyui-validatebox sarole_sname" type="text" name="CrmSaleRoles[sarole_sname]"
           data-options="required:'true'" value="<?= $model['sarole_sname'] ?>" maxlength="20" placeholder="最多输入20个字">
</div>
<div class="mb-10">
    <label class="label-width qlabel-align width100"><span class="red">*</span>销售人力类型<label>：</label></label>
    <select class="width200 easyui-validatebox" type="text" name="CrmSaleRoles[sarole_type]"
            data-options="required:'true'">
        <option value="">--请选择--</option>
        <?php foreach ($employeeType as $key => $val) { ?>
            <option
                value="<?= $val['bsp_id'] ?>" <?= isset($model['sarole_type']) && $model['sarole_type'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
    <label style="width: 123px"><span class="red">*</span>角色提成系数<label>：</label></label>
    <input class="width200 easyui-validatebox sarole_qty" type="text" name="CrmSaleRoles[sarole_qty]"
           data-options="required:'true',validType:'two_percent'" value="<?= $model['sarole_qty'] ?>"  maxlength="9"><span class="pl-10">%</span>
</div>
<div class="mb-10">
    <label class="label-width qlabel-align float-left width100"><span class="red">*</span>是否销售主管<label>：</label></label>
    <div class="width200 float-left pl-8">
        <input type="radio" value="1" class="easyui-validatebox" data-options="required:'true'"
               name="CrmSaleRoles[vdef1]" <?= $model['vdef1'] == 1 ? "checked=checked" : null; ?>>
        <span class="vertical-middle">是</span>
        <input type="radio" value="0" class="easyui-validatebox" data-options="required:'true'"
               name="CrmSaleRoles[vdef1]" <?= $model['vdef1'] == 0 ? "checked=checked" : null; ?>>
        <span class="vertical-middle">否</span>
    </div>
    <label style="width: 130px"><span class="red">*</span>状态<label>：</label></label>
    <select class="width200 easyui-validatebox" type="text" name="CrmSaleRoles[sarole_status]"
            data-options="required:'true'">
        <?php if (!empty($roleStatus)) { ?>
            <?php foreach ($roleStatus as $key => $val) { ?>
                <option
                        value="<?= $key ?>" <?= isset($model['sarole_status']) && $model['sarole_status'] == $key ? "selected" : null ?>><?= $val ?></option>
            <?php } ?>
        <?php } ?>
    </select>
</div>
<div class="mb-10">
    <label class="label-width qlabel-align width100">描述<label>：</label></label>
    <textarea type="text" rows="3" class="text-top easyui-validatebox" style="width: 530px"
              name="CrmSaleRoles[sarole_desription]" maxlength="120" placeholder="最多输入120个字"><?= $model['sarole_desription'] ?></textarea>
</div>
<div class="mb-10">
    <label class="label-width qlabel-align width100">备注<label>：</label></label>
    <textarea type="text" rows="3" class="width-530 text-top easyui-validatebox" style="width: 530px"
              name="CrmSaleRoles[sarole_remark]" maxlength="120"  placeholder="最多输入120个字"><?= $model['sarole_remark'] ?></textarea>
</div>
<div class="mb-15 text-center" style="margin-top: 50px">
    <button class="button-blue-big" type="submit">保存</button>
    <button  onclick="close_select()" class="button-white-big ml-20" type="button">取消</button>
</div>
<?php ActiveForm::end() ?>
<script>
    $(function () {
        ajaxSubmitForm($("#add-form"));
        var sarole_code=$(".sarole_code").val();
        if(sarole_code!=""&&sarole_code!=null){
            $(".sarole_code").css("display","none");
           // $(".code").removeAttr("display");
        }else {
            $(".code").removeClass("width200");
        }
        $(".sarole_qty").numbervalid(7);
//        $(".sarole_code").CodeId();
//        $(".sarole_qty").bind("keyup",function(){
//            $(".sarole_qty").val($(".sarole_qty").val().replace(/[^\-?\d.]/g,''));
//        });
        //判断是否为IE浏览器
        if(!!window.ActiveXObject || "ActiveXObject" in window){
        }
        else {
            $(".sarole_code").bind("keyup",function(){
                $(".sarole_code").val($(".sarole_code").val().replace(/[\u4e00-\u9fa5]/g,''));
            });
        }
    });
</script>