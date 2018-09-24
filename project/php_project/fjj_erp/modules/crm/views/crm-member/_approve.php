<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div class="mb-20">
    <label class="width-80"><span class="red">*</span>用户名</label>
    <input name="CrmCustomerInfo[member_name]" class="width-200  easyui-validatebox" data-options="required:true,validType:'unique',delay:10000" data-act="<?=Url::to(['validate'])?>" data-attr="member_name" data-id="<?=$model['cust_id'];?>" type="text" value="<?= $model['member_name'] ?>" maxlength="20">
    <label class="width-120">注册时间</label>
    <input class="width-200 select-date easyui-validatebox" data-options="required:true" type="text" name="CrmCustomerInfo[member_regtime]" value="<?=$model['member_regtime']?$model['member_regtime']:date("Y-m-d") ?>" readonly="readonly">
    <label class="width-120"><span class="red">*</span>会员类别</label>
    <select class="width-200 easyui-validatebox add-require" type="text" name="CrmCustomerInfo[member_type]"
            data-options="required:'true'">
        <option value="">请选择...</option>
        <?php foreach ($downList['memberType'] as $key => $val) { ?>
            <option
                value="<?= $val['bsp_id'] ?>"<?= ($model['member_type']==$val['bsp_id'])?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
</div>
<div class="mb-20">
    <label class="width-80">公司名称</label>
    <input name="CrmCustomerInfo[cust_sname]" class="width-200  add-require easyui-validatebox" data-options="required:true,validType:'unique',delay:10000" data-act="<?=Url::to(['validate'])?>" data-attr="cust_sname" data-id="<?=$model['cust_id'];?>"  type="text" value="<?= $model['cust_sname'] ?>" maxlength="50">
    <label class="width-120">公司简称</label>
    <input class="width-200 easyui-validatebox add-require" id="cust_shortname" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_shortname]" value="<?= $model['cust_shortname'] ?>" maxlength="15">
    <label class="width-120">公司电话</label>
    <input class="width-200 easyui-validatebox add-require" type="text" name="CrmCustomerInfo[cust_tel1]" data-options="validType:'telphone'" value="<?= $model['cust_tel1'] ?>" maxlength="20">
</div>
<div class="mb-20">
    <label class="width-80">联系人</label>
    <input class="width-70 easyui-validatebox add-require" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_contacts]" value="<?= $model['cust_contacts'] ?>" maxlength="15">
    <label class="width-50">职位</label>
    <input style="width:72px;" type="text" name="CrmCustomerInfo[cust_position]" value="<?= $model['cust_position'] ?>" maxlength="15">
    <label class="width-120">手机号码</label>
    <input class="width-200 easyui-validatebox add-require" data-options="required:'true',validType:'mobile'"  type="text" name="CrmCustomerInfo[cust_tel2]" value="<?= $model['cust_tel2'] ?>" maxlength="15">
    <label class="width-120">注册网站</label>
    <select class="width-200 easyui-validatebox add-require" data-options="required:'true'" name="CrmCustomerInfo[member_regweb]">
        <option value="">请选择...</option>
        <?php foreach ($downList['regWeb'] as $key => $val) { ?>
            <option
                value="<?= $val['bsp_id'] ?>"<?= $model['member_regweb']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
</div>
<div class="mb-20">
    <label class="width-80">邮箱</label>
    <input class="width-200 easyui-validatebox add-require" data-options="required:'true',validType:'email'" type="text" name="CrmCustomerInfo[cust_email]" value="<?= $model['cust_email'] ?>" maxlength="30">
    <label class="width-120">是否回访</label>
    <select name="CrmCustomerInfo[member_visitflag]" class="width-200 visit_flag" disabled="disabled">
        <option value="0" <?= $model['member_visitflag']=='0'?"selected":null; ?>>否</option>
        <option value="1" <?= $model['member_visitflag']=='1'?"selected":null; ?>>是</option>
    </select>
    <label class="width-120">需求类别</label>
    <input class="width-200" type="text" name="CrmCustomerInfo[member_reqdesription]" value="<?= $model['member_reqdesription'] ?>">
</div>
<div class="mb-20">
    <label class="width-80">详细地址</label>
    <select class="width-120 disName easyui-validatebox add-require" data-options="required:'true'" id="disName_1">
        <option value="">请选择...</option>
        <?php foreach ($downList['country'] as $key => $val) { ?>
            <option
                value="<?= $val['district_id'] ?>" <?=$val['district_id']==$districtAll['oneLevelId']?'selected':null?>><?= $val['district_name'] ?></option>
        <?php } ?>
    </select>
    <select class="width-120 disName easyui-validatebox add-require" data-options="required:'true'" id="disName_2">
        <option value="">请选择...</option>
        <?php if(!empty($districtAll)){?>
            <?php foreach($districtAll['twoLevel'] as $val){?>
                <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['twoLevelId']?'selected':null?>><?=$val['district_name']?></option>
            <?php }?>
        <?php }?>
    </select>
    <select class="width-120 disName easyui-validatebox add-require" data-options="required:'true'" id="disName_3">
        <option value="">请选择...</option>
        <?php if(!empty($districtAll)){?>
            <?php foreach($districtAll['threeLevel'] as $val){?>
                <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['threeLevelId']?'selected':null?>><?=$val['district_name']?></option>
            <?php }?>
        <?php }?>
    </select>
    <select class="width-120 disName easyui-validatebox add-require" data-options="required:'true'" id="disName_4" name="CrmCustomerInfo[cust_district_2]">
        <option value="">请选择...</option>
        <?php if(!empty($districtAll)){?>
            <?php foreach($districtAll['fourLevel'] as $val){?>
                <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['fourLevelId']?'selected':null?>><?=$val['district_name']?></option>
            <?php }?>
        <?php }?>
    </select>
    <input class="easyui-validatebox add-require" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>" style="width:360px;" maxlength="100">
</div>
<div class="mb-20">
    <label class="width-80">客户来源</label>
    <select class="width-200" name="CrmCustomerInfo[member_source]">
        <option value="">请选择...</option>
        <?php foreach ($downList['customerSource'] as $key => $val) { ?>
            <option
                value="<?= $val['bsp_id'] ?>"<?= $model['member_source']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
    <label class="width-120">经营模式</label>
    <select class="width-200" name="CrmCustomerInfo[cust_businesstype]">
        <option value="">请选择...</option>
        <?php foreach ($downList['managementType'] as $key => $val) { ?>
            <option
                value="<?= $val['bsp_id'] ?>"<?= $model['cust_businesstype']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
    <label class="width-120">经营范围</label>
    <input class="width-200" type="text" name="CrmCustomerInfo[member_businessarea]" value="<?= $model['member_businessarea'] ?>" maxlength="200">
</div>
<div class="mb-20">
    <label class="width-80">法人代表</label>
    <input class="width-200" type="text" name="CrmCustomerInfo[cust_inchargeperson]" value="<?= $model['cust_inchargeperson'] ?>" maxlength="15">
    <label class="width-120">注册时间</label>
    <input class="width-200 select-date" type="text" name="CrmCustomerInfo[cust_regdate]" value="<?= $model['cust_regdate'] ?>" readonly="readonly">
    <label class="width-120">注册资金</label>
    <input  class="width-200" type="text" name="CrmCustomerInfo[cust_regfunds]" value="<?= $model['cust_regfunds'] ?>" maxlength="15">
</div>
<div class="mb-20">
    <label class="width-80">公司类型</label>
    <select class="width-200" name="CrmCustomerInfo[cust_compvirtue]">
        <option value="">请选择...</option>
        <?php foreach ($downList['property'] as $key => $val) { ?>
            <option
                value="<?= $val['bsp_id'] ?>"<?= $model['cust_compvirtue']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
    <label class="width-120">员工人数</label>
    <input class="width-200" type="text" name="CrmCustomerInfo[cust_personqty]" value="<?= $model['cust_personqty'] ?>" maxlength="15">
    <label class="width-120">注册货币</label>
    <select class="width-200" name="CrmCustomerInfo[member_regcurr]">
        <option value="">请选择...</option>
        <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
            <option
                value="<?= $val['bsp_id'] ?>"<?= $model['member_regcurr']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
</div>
<div class="mb-20">
    <label class="width-80">年营业额</label>
    <input class="width-200" type="text" name="CrmCustomerInfo[member_compsum]" value="<?= $model['member_compsum'] ?>" maxlength="15">
    <label class="width-120">交易币种</label>
    <select class="width-200" name="CrmCustomerInfo[member_curr]">
        <option value="">请选择...</option>
        <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
            <option
                value="<?= $val['bsp_id'] ?>"<?= $model['member_curr']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
    <label class="width-120">邮编</label>
    <input class="width-200" type="text" name="CrmCustomerInfo[member_compzipcode]" value="<?= $model['member_compzipcode'] ?>" maxlength="15">
</div>
<div class="mb-20">
    <label class="width-80">年采购额</label>
    <input class="width-200" type="text" name="CrmCustomerInfo[cust_pruchaseqty]" value="<?= $model['cust_pruchaseqty'] ?>" maxlength="15">
    <label class="width-120">发票需求</label>
    <input class="width-200" type="text" name="CrmCustomerInfo[member_compreq]" value="<?= $model['member_compreq'] ?>" maxlength="15">
    <label class="width-120">主要市场</label>
    <input class="width-200" type="text" name="CrmCustomerInfo[member_marketing]" value="<?= $model['member_marketing'] ?>" maxlength="50">
</div>
<div class="mb-20">
    <label class="width-80">需求类目</label>
    <select class="width-200" name="CrmCustomerInfo[member_reqitemclass]">
        <option value="">请选择...</option>
        <?php foreach ($downList['productType'] as $key => $val) { ?>
            <option
                value="<?= $val['category_id'] ?>"<?= $model['member_reqitemclass']==$val['category_id']?"selected":null; ?>><?= $val['category_sname'] ?></option>
        <?php } ?>
    </select>
    <label class="width-120">主页</label>
    <input class="width-530" type="text" name="CrmCustomerInfo[member_compwebside]" value="<?= $model['member_compwebside'] ?>" maxlength="50">
</div>
<div class="mb-20">
    <label class="width-80">潜在需求</label>
    <select class="width-200" name="CrmCustomerInfo[member_reqflag]">
        <option value="">请选择...</option>
        <?php foreach ($downList['latentDemand'] as $key => $val) { ?>
            <option
                value="<?= $val['bsp_id'] ?>"<?= $model['member_reqflag']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
    <label class="width-120">主要客户</label>
    <input class="width-530" type="text" name="CrmCustomerInfo[member_compcust]" value="<?= $model['member_compcust'] ?>" maxlength="50">
</div>
<div class="mb-20">
    <label class="width-80 vertical-top">备注</label>
    <textarea class="width-860 easyui-validatebox" data-options="validType:'maxLength[200]'" placeholder="最多输入200个字"  name="CrmCustomerInfo[member_remark]" rows="3" maxlength="200"><?= $model['member_remark'] ?></textarea>
</div>
<div class="text-center">
    <button type="submit" class="button-blue-big sub">确定</button>
    <button class="button-white-big" onclick="window.location.href='<?= Url::to(["index"]) ?>'" type="button">返回</button>
</div>

<?php ActiveForm::end() ?>
<script>
    $(function(){
        $('.sub').one('click',function(){
            $('.visit_flag').attr('disabled',false);
            ajaxSubmitForm($("#add-form"));
        })
        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select,$url,"select");
        });

    });
</script>
