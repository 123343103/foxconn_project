<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<style>
    .width-100{
        width: 100px;
    }
    .width-200{
        width: 200px;
    }
    .width-80{
        width: 80px;
    }
    .width-127{
        width: 127px;
    }
</style>
<div style="margin-top: 30px"></div>
<div class="bs-company-form">
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-10">
            <div class="mb-10">
                <label class="label-width qlabel-align width-100"><span class="red">*</span>上级公司</label><label>：</label>
                <select  name="BsCompany[parent_id]" class="form-control width-200 easyui-validatebox" data-options="required:'true'"  id="hrstaff-position">
                    <option value="">请选择</option>
                    <?php foreach ($downList as $val) {?>
                        <option value="<?=$val['company_id'] ?>" <?= $model['parent_id']==$val['company_id']?"selected":null ?>><?= $val['company_name'] ?></option>
                    <?php } ?>
                </select>
                <label class="width-100">公司代码</label><label>：</label>
                <input type="text"  name="BsCompany[company_code]" class="width-200"  value="<?= $model['company_code'] ?>">
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width-100"><span class="red">*</span>公司名称(法人)</label><label>：</label>
                <input type="text"   name="BsCompany[company_name]" data-options="required:'true'" class="width-200 easyui-validatebox"  value="<?= $model['company_name'] ?>">
                <label class=" width-100">简称</label><label>：</label>
                <input type="text"  name="BsCompany[comp_shortname]" class="width-200"  value="<?= $model['comp_shortname'] ?>">
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width-100"><span class="red">*</span>成立日期</label><label>：</label>
                <input type="text" name="BsCompany[comp_createdate]" class="width-200 select-date easyui-validatebox" id="comp_createdate" data-options='required:"true"' readonly="readonly" value="<?= $model['comp_createdate']; ?>">
                <label class=" width-100">总部地点</label><label>：</label>
                <input type="text" maxlength="50" name="BsCompany[comp_mainaddress]" class="width-200 easyui-validatebox" data-options="required:'true'" value="<?= $model['comp_mainaddress'] ?>">
            </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100"><span class="red">*</span>法定代表人</label><label>：</label>
            <input type="text"  name="BsCompany[comp_cman]" class="width-200 easyui-validatebox" data-options="required:'true'"  value="<?= $model['comp_cman'] ?>">
            <label class=" width-100"><span class="red">*</span>经营状态</label><label>：</label>
<!--            <input type="text"  name="BsCompany[company_status]" class="width-200 easyui-validatebox" data-options="required:'true'"  value="--><?//= $model['company_status'] ?><!--">-->
            <select class="width-200 easyui-validatebox" name="BsCompany[company_status]"
                    data-options="required:'true'">
                <?php foreach ($companyStatus as $key => $val) { ?>
                    <?php if (isset($model)) { ?>
                        <option
                            value="<?= $key ?>" <?= $model['company_status'] == $key ? "selected" : null ?>><?= $val ?> </option>
                    <?php } else { ?>
                        <option value="<?= $key ?>"><?= $val ?> </option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100"><span class="red">*</span>负责人</label><label>：</label>
<!--            <input type="text"  name="BsCompany[comp_cman2]" class="width-200 easyui-validatebox" data-options="required:'true'"  value="--><?//= $model['comp_cman2'] ?><!--">-->
            <input type="hidden" value="<?=$model['comp_cman2']?>" name="BsCompany[comp_cman2]" class="staff_id">
            <input type="text" class="width-200 easyui-validatebox staff_code" data-options="required:true" value="<?=$staffInfo['staff_code']?>" onblur="setStaffInfo(this)">
            <span class="width-80 staff_name" style="position: absolute;margin-top: 3px;margin-left:-100px"><?=$staffInfo['staff_name']?></span>
            <label class="width-100"><span class="red">*</span>联系方式</label><label>：</label>
            <input type="text"  name="BsCompany[comp_tel]" class="width-200 easyui-validatebox" data-options="required:'true'"  value="<?= $model['comp_tel'] ?>">
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100 "><span class="red">*</span>组织机构代码</label><label>：</label>
            <input type="text"  name="BsCompany[comp_orgcode]" class="width-200 easyui-validatebox" data-options="required:'true'"  value="<?= $model['comp_orgcode'] ?>">
            <label class="width-100"><span class="red">*</span>注册号</label><label>：</label>
            <input type="text"  name="BsCompany[comp_regcode]" class="width-200 easyui-validatebox" data-options="required:'true'"  value="<?= $model['comp_regcode'] ?>">
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100">公式性质</label><label>：</label>
<!--            <input type="text"  name="BsCompany[comp_bustype]" class="width-200"  value="--><?//= $model['comp_bustype'] ?><!--">-->
            <select class="width-200 easyui-validatebox" name="BsCompany[comp_bustype]"
                    data-options="required:'true'">
                <option value>请选择...</option>
                <?php foreach ($downListCop['companyProperty'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>" <?= $model['comp_bustype'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class=" width-100">公司类型</label><label>：</label>
            <input type="text"  name="BsCompany[comp_type]" class="width-200"  value="<?= $model['comp_type'] ?>">
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100"><span class="red">*</span>营业额</label><label>：</label>
            <input type="text"  name="BsCompany[comp_count]" class="width-200 easyui-validatebox" data-options="required:'true'"  value="<?= $model['comp_count'] ?>">
            <label class=" width-100">注册资本</label><label>：</label>
            <input type="text"  name="BsCompany[comp_regcount]" class="width-200"  value="<?= $model['comp_regcount'] ?>">
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100"><span class="red">*</span>企业地址</label><label>：</label>
            <select class="width-127 disName easyui-validatebox" data-options="required:'true'" id="disName_1">
                <option value="">请选择...</option>
                <?php foreach ($downListCop['country'] as $key => $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select class="width-127 disName easyui-validatebox" data-options="required:'true'" id="disName_2">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll2)) { ?>
                    <?php foreach ($districtAll2['twoLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="width-127 disName easyui-validatebox" data-options="required:'true'" id="disName_3">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll2)) { ?>
                    <?php foreach ($districtAll2['threeLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="width-127 disName easyui-validatebox" data-options="required:'true'" id="disName_4"
                    name="BsCompany[comp_disid]">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll2)) { ?>
                    <?php foreach ($districtAll2['fourLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <input type="text"  name="BsCompany[comp_addrdss]" style="width:518px;margin-left: 116px;margin-top: 5px"  value="<?= $model['comp_addrdss'] ?>">
        </div>
   <div class="space-10"></div>
        <div class="text-center">
            <button class="button-blue-big" type="submit">确认</button>&nbsp;
            <button class="button-white-big ml-20 close">取消</button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function setStaffInfo(obj) {
        var url = "<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>";
        staffInfo(obj, url);
    }
    $(function () {
        $(".close").click(function(){
            parent.$.fancybox.close();
        });

        ajaxSubmitForm($("#add-form"),'',function(data){
            parent.parent.layer.alert(data.msg, {
                icon: 1,
                end: function () {
                    window.top.location.href="<?=Url::to(['index'])?>";
                },
                success: function(){
                    window.top.$('body .fancybox-overlay').hide();
                }
            });
        });
        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select,$url,"select");
        });
    })

</script>
