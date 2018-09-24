<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<style>
    .width-120{
        width: 120px;
    }
    .width-200{
        width:200px;
    }
    .width-145{
        width: 145px;
    }
    .width-130{
        width: 130px;
    }
    .mb-15{
        margin-bottom: 15px;
    }
    .mt-5{
        margin-top: 5px;
    }
</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
    <div class="space-40"></div>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-10">
        <label class="label-width qlabel-align  width-120"><span class="red dispaly">*</span>销售点代码<label>：</label></label>
        <input class="width-200 easyui-validatebox sts_code" type="text" name="CrmStoresinfo[sts_code]" style="ime-mode:disabled"
               value="<?= $model['sts_code'] ?>" data-options="required:'true',validType:'unique',delay:10000,validateOnBlur:'true'"  data-act="<?=Url::to(['validate'])?>" data-id="<?=$model['sts_id'];?>" maxlength="20" data-attr="sts_code">
        <label class="width-200 text-left code" name="CrmStoresinfo[sts_code]" display="none"><?= $model['sts_code'] ?></label>
        <label class="cname" style="width: 141px"><span class="red">*</span>销售点名称<label>：</label></label>
        <input class="width-200 easyui-validatebox" type="text" name="CrmStoresinfo[sts_sname]"
               data-options="required:'true'" value="<?= $model['sts_sname'] ?>" maxlength="20" placeholder="最多输入20个字">
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-120"><span class="red">*</span>营销区域<label>：</label></label>
        <select class="width-200 easyui-validatebox" type="text" name="CrmStoresinfo[csarea_id]"
                data-options="required:'true'">
            <option value="">--请选择--</option>
            <?php foreach ($saleArea as $key => $val) { ?>
                <option
                    value="<?= $val['csarea_id'] ?>" <?= isset($model['csarea_id']) && $model['csarea_id'] == $val['csarea_id'] ? "selected" : null ?>><?= $val['csarea_name'] ?></option>
            <?php } ?>
        </select>
        <label class="width-145">KPI<label>：</label></label>
        <input class="width-200 easyui-validatebox KPI"   maxlength="9" data-options="validType:'two_decimal'"  type="text" name="CrmStoresinfo[kpi]" value="<?= $model['kpi'] ?>" >
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-120">省长<label>：</label></label>
        <select class="width-200 easyui-validatebox" type="text" name="CrmStoresinfo[sz_staff_id]">
            <option value="">--请选择--</option>
            <?php foreach ($seller as $key => $val) { ?>
                <option value="<?= $val['staff_id'] ?>" <?= isset($model['sz_staff_id']) && $model['sz_staff_id'] == $val['staff_id'] ? "selected" : null ?>><?= $val['staff_code'] ?>--<?= $val['staff_name'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width qlabel-align width-145">店长<label>：</label></label>
        <select class="width-200 easyui-validatebox" type="text"
                name="CrmStoresinfo[dz_staff_id]">
            <option value="">--请选择--</option>
            <?php foreach ($seller as $key => $val) { ?>
                <option value="<?= $val['staff_id'] ?>" <?= isset($model['dz_staff_id']) && $model['dz_staff_id'] == $val['staff_id'] ? "selected" : null ?>><?= $val['staff_code'] ?>--<?= $val['staff_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-120">店员数量<label>：</label></label>
        <input class="width-200 easyui-validatebox sts_count" data-options="validType:'number'" style="ime-mode:disabled" maxlength="9" type="text" name="CrmStoresinfo[sts_count]" value="<?= $model['sts_count'] ?>">
        <label class="width-145">店员数量上限<label>：</label></label>
        <input class="width-200 easyui-validatebox sts_count_limit" style="ime-mode:disabled" data-options="validType:'number'" type="text" name="CrmStoresinfo[sts_count_limit]" value="<?= $model['sts_count_limit'] ?>" maxlength="9">
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-120"><span class="red">*</span>状态<label>：</label></label>
        <select class="width-200 easyui-validatebox" type="text" name="CrmStoresinfo[sts_status]" data-options="required:'true'">
            <option value="">--请选择--</option>
            <option value="10" <?= isset($model['sts_status'])&& $model['sts_status'] == '10' ? "selected":null; ?>>营业中</option>
            <option value="11" <?= isset($model['sts_status'])&& $model['sts_status']  == '11' ? "selected":null; ?>>筹备中</option>
            <option value="14" <?= isset($model['sts_status'])&& $model['sts_status']  == '14' ? "selected":null; ?>>已暂停</option>
            <option value="13" <?= isset($model['sts_status'])&& $model['sts_status']  == '13' ? "selected":null; ?>>已歇业</option>
            <option value="15" <?= isset($model['sts_status'])&&$model['sts_status']  == '15' ? "selected":null; ?>>已关闭</option>
        </select>
    </div>
    <div class="mb-10 overflow-auto">
        <label class="label-width qlabel-align width-120 float-left"><span class="red">*</span>详细地址<label>：</label></label>
        <div class="float-left" style="width:560px;margin-left: 4px">
            <select class="width-130 disName easyui-validatebox" id="disName_1" data-options="required:'true'">
                <option value="">--请选择--</option>
                <?php foreach ($country as $key => $val) { ?>
                    <option
                    <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['oneLevelId'] ? 'selected' : '' ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select class="width-130 disName easyui-validatebox" id="disName_2" data-options="required:'true'">
                <option value="">--请选择--</option>
                <?php if (!empty($districtAll)) { ?>
                    <?php foreach ($districtAll['twoLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="width-130 disName easyui-validatebox" id="disName_3" data-options="required:'true'">
                <option value="">--请选择--</option>
                <?php if (!empty($districtAll)) { ?>
                    <?php foreach ($districtAll['threeLevel'] as $val) { ?>
                        <option value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="disName easyui-validatebox" id="disName_4" data-options="required:'true'"
                    name="CrmStoresinfo[district_id]" style="width:152px;">
                <option value="">--请选择--</option>
                <?php if (!empty($districtAll)) { ?>
                    <?php foreach ($districtAll['fourLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <input class="remove-require mt-5 easyui-validatebox" data-options="required:'true'" type="text" name="CrmStoresinfo[sts_address]" value="<?= $model['sts_address'] ?>" maxlength="120" placeholder="最多输入120个字" id="cust_adress" style="width:552px;">
        </div>
    </div>
    <div class="space-10"></div>
    <div class="text-center">
        <button type="submit" class="button-blue-big">保存</button>
        <button class="button-white-big" onclick="history.go(-1);" type="button">返回</button>
    </div>
    <?php ActiveForm::end() ?>
</div>

<script>
    $(function () {
        ajaxSubmitForm($("#add-form"));
        $('#select-seller').fancybox(      //选择销售人员（店长、省长）弹窗
            {
                padding: [],
                fitToView: false,
                width: 800,
                height: 530,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe'
            }
        );
        $('.disName,.disName1,.disName2,.disName3').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select, $url, "select");
        });
        $(".sts_count_limit").numbervalid();
        $(".sts_count").numbervalid();
        if(!!window.ActiveXObject || "ActiveXObject" in window){
        }
        else {
            $(".sts_code").bind("keyup",function(){
                $(".sts_code").val($(".sts_code").val().replace(/[\u4e00-\u9fa5]/g,''));
            });
            $(".sts_count").bind("keyup",function(){
                $(".sts_count").val($(".sts_count").val().replace(/[^\d]/g,''));
            });
            $(".sts_count_limit").bind("keyup",function(){
            $(".sts_count_limit").val($(".sts_count_limit").val().replace(/[^\d]/g,'',''));
        });
        }
        $(".KPI").numbervalid(2);
        var sts_code=$(".sts_code").val();
        if(sts_code!=""&&sts_code!=null){
            $(".sts_code").css("display","none");
            $(".dispaly").css("display","none");
            $(".cname").css("width",'144px')
            // $(".code").removeAttr("display");
        }else {
            $(".code").removeClass("width-200");
        }
    });

</script>