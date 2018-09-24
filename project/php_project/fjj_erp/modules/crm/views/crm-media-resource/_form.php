<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/13
 * Time: 上午 10:07
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<h3 class="head-second">媒体基础信息</h3>
<?php ActiveForm::begin() ?>
<div class="mb-20">
    <label for="" class="width-100">媒体类型</label>
    <?=Html::dropDownList("CrmMediaCount[cmt_type]",$model['cmt_type'],$options["mediaType"],["prompt"=>"请选择","class"=>"width-250 easyui-validatebox","data-options"=>"required:true"])?>
    <label for="" class="width-100">公司名称</label>
    <?=\app\widgets\RealTimeSearch::widget([
        "name"=>"CrmMediaCount[medic_compname]",
        "value"=>$model["medic_compname"],
        "url"=>\yii\helpers\Url::to(['crm-potential-customer/search','attr'=>'cust_sname']),
        "options"=>[
            "class"=>"width-250 easyui-validatebox",
            "data-options"=>"required:true",
            "maxlength"=>"60"
        ]
    ])?>
</div>

<div class="mb-20">
    <label for="" class="width-100">合作联系人</label>
    <input name="CrmMediaCount[medic_parner]" value="<?=$model['medic_parner']?>" type="text" class="width-250 easyui-validatebox" data-options="required:true" maxlength="60">
    <label for="" class="width-100">职务</label>
    <input name="CrmMediaCount[medic_position]" value="<?=$model['medic_position']?>" type="text" class="width-250" maxlength="60">
</div>

<div class="mb-20">
    <label for="" class="width-100">手机号码</label>
    <input name="CrmMediaCount[medic_phone]" value="<?=$model['medic_phone']?>" type="text" class="width-250 easyui-validatebox" data-options="required:true,validType:'mobile'">
    <label for="" class="width-100">电话号码</label>
    <input name="CrmMediaCount[medic_tel]" value="<?=$model['medic_tel']?>" type="text" class="width-250 easyui-validatebox" data-options="validType:'telphone'">
</div>

<div class="mb-20">
    <label for="" class="width-100">邮箱</label>
    <input name="CrmMediaCount[medic_emails]" value="<?=$model['medic_emails']?>" type="text" class="width-250 easyui-validatebox" data-options="required:true,validType:'email'">
    <label for="" class="width-100">是否供应商</label>
    <?=Html::dropDownList("CrmMediaCount[medic_issupilse]",$model["medic_issupilse"],$options["isSupplier"],["prompt"=>"请选择","class"=>"width-250"])?>
</div>

<div class="mb-20">
    <label for="" class="width-100">合作次数</label>
    <input name="CrmMediaCount[medic_times]" value="<?=$model['medic_times']?>" type="text" class="width-250 easyui-validatebox" data-options="validType:'int'">
    <label for="" class="width-100">服务评级</label>
    <?=Html::dropDownList("CrmMediaCount[medic_level]",$model["medic_level"],$options["serviceLevel"],["prompt"=>"请选择","class"=>"width-250"])?>
</div>



<div class="mb-20">
    <label for="" class="width-100">联系地址</label>
    <?=\app\widgets\district\District::widget([
        "district_id"=>$model["cust_cmp_district"],
        "name"=>"CrmCommunity[cust_cmp_district]",
        "options"=>[
            'country'=>["prompt"=>"请选择","class"=>"district-level easyui-validatebox","style"=>"width:149px;"],
            'province'=>["prompt"=>"请选择","class"=>"district-level easyui-validatebox","style"=>"width:149px;"],
            'city'=>["prompt"=>"请选择","class"=>"district-level easyui-validatebox","style"=>"width:149px;"],
            'district'=>["prompt"=>"请选择","class"=>"district-level easyui-validatebox","style"=>"width:149px;"]
        ]
    ])?>
    <div>
        <input name="CrmMediaCount[medic_adds]" type="text" style="margin-left: 105px;margin-top:20px;width:610px;" value="<?=$model['medic_adds']?>" maxlength="200">
    </div>
</div>
<div class="mb-20">
    <label style="vertical-align: top" for="" class="width-100">媒体简介</label>
    <textarea name="CrmMediaCount[medic_profile]" style="width:610px;height:100px;" maxlength="200"><?=$model["medic_profile"]?></textarea>
</div>
<div class="mb-20">
    <label style="vertical-align: top" for="" class="width-100">行业资质</label>
    <textarea name="CrmMediaCount[medic_qual]" style="width:610px;height:100px;" maxlength="60"><?=$model['medic_qual']?></textarea>
</div>
<div class="mb-20 text-center">
    <button type="submit" class="button-blue">确定</button>
    <button type="button" class="button-white" onclick="window.location.href='<?=\yii\helpers\Url::to(['index'])?>'">取消</button>
</div>
<?php ActiveForm::end() ?>


<script>
    $(function(){
        ajaxSubmitForm($("form"));
    });
</script>
