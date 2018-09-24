<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/6/7
 * Time: 11:36
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<style>
    .mb-20{
        margin-bottom: 10px;
    }
    .width-80{
        width:80px;
    }
    .width-120{
        width:120px;
    }
    .width-130{
        width:130px;
    }
    .width-200{
        width:200px;
    }
</style>
<h2 class="head-first">请完善会员信息</h2>
    <?php $form = ActiveForm::begin([
            'id' => 'add-form'
    ]); ?>
    <h2 class="head-second">会员信息</h2>
    <div class="mb-20">
        <label class="width-80 label-align"><span class="red">*</span>用户名</label><label>:</label>
        <input name="CrmCustomerInfo[member_name]" class="value-width value-align  easyui-validatebox remove" data-options="required:true,validType:'unique',delay:10000,validateOnBlur:true" data-act="<?=Url::to(['/crm/crm-member/validate-name'])?>" data-attr="member_name" data-id="<?=$model['cust_id'];?>" type="text" value="<?= $model['member_name'] ?>" maxlength="20" placeholder="最多输入20个字" id="member_name">
        <label class="width-120 label-align"><span class="red">*</span>注册网站</label><label>:</label>
        <select class="width-200 value-align easyui-validatebox" data-options="required:'true'" name="CrmCustomerInfo[member_regweb]">
            <option value="">请选择...</option>
            <?php foreach ($downList['regWeb'] as $key => $val) { ?>
                <option value="<?= $val['bsp_id'] ?>"<?= $model['member_regweb']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>

    </div>
    <div class="mb-20">
        <label class="width-80 label-align">会员类别</label><label>:</label>
        <input type="hidden" value="100070" name="CrmCustomerInfo[member_type]">
        <span class="width-200 value-align">普通会员</span>
        <label class="width-120 label-align">注册时间</label><label>:</label>
        <input type="hidden" name="CrmCustomerInfo[member_regtime]" value="<?= date("Y-m-d") ?>">
        <span class="width-200 value-align"><?= date("Y-m-d") ?></span>
    </div>
    <h2 class="head-second">客户信息</h2>
    <div class="mb-20">
        <label class="width-80 label-align">公司名称</label><label>:</label>
        <span class="width-200 value-align vertical-center"><?= $model['cust_sname'] ?></span>
        <label class="width-120 label-align">公司简称</label><label>:</label>
        <span class="width-200 value-align "><?= $model['cust_shortname'] ?></span>
    </div>
    <div class="mb-10">
        <label class="width-80 label-align"><span class="red">*</span>公司电话</label><label>:</label>
        <input class="width-200 value-align easyui-validatebox" data-options="required:'true',validType:'telphone'" type="text" placeholder="请输入:xxxx-xxxxxxx"
               name="CrmCustomerInfo[cust_tel1]" value="<?= $model['cust_tel1'] ?>">
        <label class="width-120 label-align">邮编</label><label>:</label>
        <input class="width-200 value-align easyui-validatebox" data-options="validType:'postcode'" type="text" name="CrmCustomerInfo[member_compzipcode]" value="<?= $model['member_compzipcode'] ?>" maxlength="6" id="member_compzipcode" placeholder="请输入:xxxxxx">
    </div>

    <div class="mb-10">
        <label class="width-80 label-align"><span class="red">*</span>联系人</label><label>:</label>
        <input class="width-200 value-align easyui-validatebox" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_contacts]" value="<?= $model['cust_contacts'] ?>" maxlength="15" id="cust_contacts">
        <label class="width-120 label-align">部门</label><label>:</label>
        <input class="width-200 value-align" type="text" name="CrmCustomerInfo[cust_department]" value="<?= $model['cust_department'] ?>" maxlength="15" id="cust_department">
    </div>
    <div class="mb-10">
        <label class="width-80 label-align">职位</label><label>:</label>
        <input class="width-200 value-align" type="text" name="CrmCustomerInfo[cust_position]" value="<?= $model['cust_position'] ?>" maxlength="15" id="cust_position">
        <label class="width-120 label-align" for="">职位职能</label><label>:</label>
        <select class="width-200 value-align" name="CrmCustomerInfo[cust_function]" class="width-200">
            <option value="">请选择...</option>
            <?php foreach ($downList['custFunction'] as $key => $val) { ?>
                <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['cust_function']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="width-80 label-align"><span class="red">*</span>联系方式</label><label>:</label>
        <input class="width-200 value-align easyui-validatebox" data-options="required:'true',validType:'tel_mobile'"  type="text" name="CrmCustomerInfo[cust_tel2]" value="<?= $model['cust_tel2'] ?>" maxlength="15" id="cust_tel2" placeholder="请输入: 138 xxxx xxxx">
        <label class="width-120 label-align">邮箱</label><label>:</label>
        <input class="width-200 value-align easyui-validatebox" data-options="validType:'email'" type="text" name="CrmCustomerInfo[cust_email]" value="<?= $model['cust_email'] ?>" maxlength="30" id="cust_email" placeholder="请输入:xxxx@xxx.xxx">
    </div>
    <div class="mb-20">
        <label class="width-80"><span class="red">*</span>详细地址</label><label>:</label>
        <select class="width-130 disName easyui-validatebox" data-options="required:'true'" id="disName_1">
            <option value="">国</option>
            <?php foreach ($downList['country'] as $key => $val) { ?>
                <option
                    value="<?= $val['district_id'] ?>" <?=$val['district_id']==$districtAll['oneLevelId']?'selected':null?>><?= $val['district_name'] ?></option>
            <?php } ?>
        </select>
        <select class="width-130 disName easyui-validatebox" data-options="required:'true'" id="disName_2">
            <option value="">省</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['twoLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['twoLevelId']?'selected':null?>><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <select class="width-130 disName easyui-validatebox" data-options="required:'true'" id="disName_3">
            <option value="">市</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['threeLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['threeLevelId']?'selected':null?>><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <select style="width:127px;" class="disName easyui-validatebox" data-options="required:'true'" id="disName_4" name="CrmCustomerInfo[cust_district_2]">
            <option value="">县/区</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['fourLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['fourLevelId']?'selected':null?>><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <input class="easyui-validatebox" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>" style="width: 527px;margin-left: 84px;margin-top: 5px;" maxlength="100">
    </div>
    <div class="text-center mb-20">
    <button type="submit" class="button-blue-big">确定</button>
    <button class="button-white-big" onclick="close_select()" type="button">取消</button>
    </div>
    <?php ActiveForm::end() ?>
<script>
    $(function() {
        ajaxSubmitForm($("#add-form"));

        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select, $url, "select");
        });
    })
</script>
