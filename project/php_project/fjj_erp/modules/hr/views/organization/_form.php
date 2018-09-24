<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<style>
    .label-width{

        width: 80px;
    }
    .value-width
    {

        width: 150px;
    }
</style>
<div class="space-10"></div>
<div class="space-10"></div>
<div class="organization-form">
    <?php $form=ActiveForm::begin([ 'id'=>'orgForm', ]); ?>
    <div class="mb-10">
        <div class="inline-block field-hrorganization-organization_code required">
            <div class='width-120 display-style'>
                <label class="label-width  label-align" for="hrorganization-organization_code">部门代码：</label></div>
            <div class='display-style'>
                <input type="text" id="hrorganization-organization_code" class="value-width value-align easyui-validatebox" name="HrOrganization[organization_code]" maxlength="50"  data-options="required:'true'" value="<?= isset($model['organization_code'])?$model['organization_code']:''?>"></div>
        </div>
        <div class="inline-block field-hrorganization-organization_pid">
            <div class='width-120 display-style'>
                <label class="label-width label-align" for="hrorganization-organization_pid">上级部门：</label></div>
            <div class='display-style'>
                <select id="hrorganization-organization_pid" class="form-control input-xlarge value-width value-align" name="HrOrganization[organization_pid]">
                    <?php  foreach($downList['orgAll'] as $list){ ?>
                        <option value="<?= $list['organization_id']?>" <?= $list['organization_id']==$model['organization_pid'] ?'selected':''?>><?= $list['organization_name']?></option>
                    <?php }?>
                </select>
            </div>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block field-hrorganization-organization_name required">
            <div class='width-120 display-style'>
                <label class="label-width label-align" for="hrorganization-organization_name">部门名称：</label></div>
            <div class='display-style'>
                <input type="text" id="hrorganization-organization_name" class="value-width value-align easyui-validatebox" name="HrOrganization[organization_name]"  data-options="required:'true'" maxlength="255" value="<?= isset($model['organization_name'])?$model['organization_name']:''?>"></div>
        </div>
        <div class="inline-block field-hrorganization-is_abandoned">
            <div class='width-120 display-style'>
                <label class="label-width label-align" for="hrorganization-is_abandoned">是否弃用：</label></div>
            <div class='display-style'>
                <input type="hidden" name="HrOrganization[is_abandoned]" value="">
                <div id="hrorganization-is_abandoned">
                        <input type="radio" name="HrOrganization[is_abandoned]" value="0" <?= $model['is_abandoned']==0?'checked':''?> style="margin-left: 10px;">否&nbsp;
                        <input type="radio" name="HrOrganization[is_abandoned]" value="1" <?= $model['is_abandoned']==1?'checked':''?> style="margin-left: 20px;">是
                </div>
            </div>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block field-hrorganization-organization_leader">
            <div class='width-120 display-style '>
                <label class="label-width label-align" for="hrorganization-organization_leader">部门主管：</label></div>
            <div class='display-style'>
                <input type="text" id="hrorganization-organization_leader" class="value-width value-align" name="HrOrganization[organization_leader]" maxlength="20" value="<?= isset($model['organization_leader'])?$model['organization_leader']:''?>"></div>
        </div>
        <div class="inline-block field-hrorganization-organization_level">
            <div class='width-120 display-style'>
                <label class="label-width label-align" for="hrorganization-organization_level">部门级别：</label></div>
            <div class='display-style'>
                <select id="hrorganization-organization_level" class="value-width value-align" name="HrOrganization[organization_level]">
                    <?php foreach($downList['level'] as $key=>$val){?>
                        <option value="<?= $key ?>" <?= $key==$model['organization_level'] ?'selected':''?>><?= $val ?></option>
                    <?php  }?>
                </select>
            </div>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-flex field-hrorganization-organization_description">
            <div class='width-120 display-style'>
                <label class="label-width label-align" for="hrorganization-organization_description">部门描述：</label></div>
                <textarea id="hrorganization-organization_description" class=" value-align" style="width: 500px;margin-left: 4px;" name="HrOrganization[organization_description]" rows="4"><?= isset($model['organization_description'])?$model['organization_description']:''?></textarea>
        </div>
    </div>
    <div class="space-10"></div>
    <div>
        <button type="submit" class="button-blue-big ml-50" style="margin-left: 235px;">确认</button>&nbsp;
        <button type="reset" class="button-blue-big">重置</button></div>
    <?php ActiveForm::end(); ?></div>
<script>
    $("body").on("submit", $("#orgForm"),
        function() {
            var id = $("#orgForm").attr('id');
            $("button[type='submit']").prop("disabled", true);
            var options = {
                dataType: 'json',
                success: function(data) {
                    if (data.flag == 1) {
                        layer.alert(data.msg, {
                            icon: 1,
                            end: function() {
                                window.parent.location.reload();
                            }
                        });
                    }
                    if (data.flag == 0) {
                        layer.alert(data.msg, {
                            icon: 2
                        });
                        $("button[type='submit']").prop("disabled", false);
                    }
                },
                error: function(data) {
                    layer.alert(data.responseText, {
                        icon: 2
                    });
                }
            };
            $("#" + id).ajaxSubmit(options);
            return false;
        })
</script>
