<?php
use yii\widgets\ActiveForm;
use app\assets\JeDateAsset;
use \yii\helpers\Url;
JeDateAsset::register($this);
?>
<style>
    .width-80{
        width:81px;
    }
    .width-115{
        width:115px;
    }
    .label-width{
        width:80px;
    }
    .width-175{
        width:175px;
    }
    .value-width{
        width:200px !important;
    }
    .ml-220{
        margin-left: 220px;
    }
    .mt-30{
        margin-top: 30px;
    }

</style>

    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>

    <h2 class="head-second">
        <a href="javascript:void(0)" class="ml-10">
            <i class="icon-caret-down"></i>
            客户基本信息
        </a>
    </h2>
<div>
    <div class="mb-10">
        <?php if (\Yii::$app->controller->action->id == "create") { ?>
            <label class="label-width label-align"><span class="red">*</span>公司名称</label><label>:</label>
            <input name="CrmCustomerInfo[cust_sname]" class="value-width  value-align add-require easyui-validatebox"
                   data-options="required:true,validType:'unique',delay:10000"
                   data-act="<?= Url::to(['/crm/crm-member/validate']) ?>" data-attr="cust_sname"
                   data-id="<?= $model['cust_id']; ?>" type="text" value="<?= $model['cust_sname'] ?>" maxlength="50">
            <label class="label-width ml-220 label-align"><span class="red">*</span>公司简称</label><label>:</label>
            <input class="value-width value-align easyui-validatebox" data-options="required:true" type="text" name="CrmCustomerInfo[cust_shortname]" placeholder="最多可输入10个字"
                   value="<?= $model['cust_shortname'] ?>" maxlength="10">
        <?php }else if(\Yii::$app->controller->action->id == "update"){ ?>
            <label class="label-width label-align vertical-top">公司名称</label><label class="vertical-top">:</label>
            <label class="value-width value-align vertical-top"><?= $model['cust_sname'] ?></label>
            <label class="label-width ml-220 label-align vertical-top">公司简称</label><label class="vertical-top">:</label>
            <label class="value-width value-align vertical-top"><?= $model['cust_shortname'] ?></label>
        <?php } ?>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">公司电话</label><label>:</label>
        <input class="value-width value-align easyui-validatebox IsTel" data-options="validType:'telphone'" type="text" placeholder="请输入:xxxx-xxxxxxx"
               name="CrmCustomerInfo[cust_tel1]" value="<?= $model['cust_tel1'] ?>">
        <label class="label-width ml-220 label-align">邮编</label><label>:</label>
        <input class="value-width value-align easyui-validatebox Onlynum" data-options="validType:'postcode'" type="text" name="CrmCustomerInfo[member_compzipcode]" value="<?= $model['member_compzipcode'] ?>" maxlength="6" id="member_compzipcode" placeholder="请输入:xxxxxx">
    </div>

    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>联系人</label><label>:</label>
        <input class="value-width value-align easyui-validatebox" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_contacts]" value="<?= $model['cust_contacts'] ?>" maxlength="15" id="cust_contacts">
        <label class="label-width ml-220 label-align">部门</label><label>:</label>
        <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_department]" value="<?= $model['cust_department'] ?>" maxlength="15" id="cust_department">
    </div>
    <div class="mb-10">
        <label class="label-width label-align">职位</label><label>:</label>
        <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_position]" value="<?= $model['cust_position'] ?>" maxlength="15" id="cust_position">
        <label class="label-width ml-220 label-align" for="">职位职能</label><label>:</label>
        <select class="value-width value-align" name="CrmCustomerInfo[cust_function]" class="value-width">
            <option value="">请选择...</option>
            <?php foreach ($downList['custFunction'] as $key => $val) { ?>
                <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['cust_function']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>联系方式</label><label>:</label>
        <input class="value-width value-align easyui-validatebox" data-options="required:'true',validType:'tel_mobile'"  type="text" name="CrmCustomerInfo[cust_tel2]" value="<?= $model['cust_tel2'] ?>" maxlength="15" id="cust_tel2" placeholder="请输入: 138 xxxx xxxx">
        <label class="label-width ml-220 label-align"><span class="red">*</span>邮箱</label><label>:</label>
        <input class="value-width value-align easyui-validatebox" data-options="required:'true',validType:'email'" type="text" name="CrmCustomerInfo[cust_email]" value="<?= $model['cust_email'] ?>" maxlength="30" id="cust_email" placeholder="请输入:xxxx@xxx.xxx">
    </div>
    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>详细地址</label><label>:</label>
        <select class="width-175 value-align disName easyui-validatebox" data-options="required:'true'" id="disName_1">
            <option value="">国</option>
            <?php foreach ($downList['country'] as $key => $val) { ?>
                <option
                        value="<?= $val['district_id'] ?>" <?=$val['district_id']==$districtAll['oneLevelId']?'selected':null?>><?= $val['district_name'] ?></option>
            <?php } ?>
        </select>
        <select class="width-175 value-align disName easyui-validatebox" data-options="required:'true'" id="disName_2">
            <option value="">省</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['twoLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['twoLevelId']?'selected':null?>><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <select class="width-175  value-align disName easyui-validatebox" data-options="required:'true'" id="disName_3">
            <option value="">市</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['threeLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['threeLevelId']?'selected':null?>><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <select style="width:174px;" class="value-align disName easyui-validatebox" data-options="required:'true'" id="disName_4" name="CrmCustomerInfo[cust_district_2]">
            <option value="">县/区</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['fourLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['fourLevelId']?'selected':null?>><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <input style="margin-left:85px;margin-top: 5px; width:710px;" class="value-align easyui-validatebox" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>" maxlength="100" placeholder="请填写公司详细地址,例如街道名称、门牌号码、楼层等信息" id="cust_adress">
    </div>
</div>
    <h2 class="head-second">
        <a href="javascript:void(0)" class="ml-10">
            <i class="icon-caret-right"></i>
            客户详细信息
        </a>
    </h2>
<div class="display-none">
    <div class="mb-10">
        <label class="label-width label-align">法人代表</label><label>:</label>
        <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_inchargeperson]" value="<?= $model['cust_inchargeperson'] ?>" maxlength="15" id="cust_inchargeperson">
        <label class="label-width ml-220 label-align">注册时间</label><label>:</label>
        <input class="value-width value-align Wdate" type="text" name="CrmCustomerInfo[cust_regdate]" value="<?= $model['cust_regdate'] ?>" id="cust_regdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd' })" onfocus="this.blur()">
    </div>
    <div class="mb-10">
        <label class="label-width label-align">注册货币</label><label>:</label>
        <select class="value-width value-align" name="CrmCustomerInfo[member_regcurr]" id="member_regcurr">
            <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                <option
                        value="<?= $val['bsp_id'] ?>"<?= (!empty($model)?$model['member_regcurr']:'100091') ==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width ml-220 label-align">注册资金</label><label>:</label>
        <input  class="value-width value-align easyui-validatebox Onlynum" data-options="validType:'positive'" type="text" name="CrmCustomerInfo[cust_regfunds]" value="<?= !empty($model['cust_regfunds'])?floor($model['cust_regfunds']):'' ?>" maxlength="15" id="cust_regfunds">

    </div>
    <div class="mb-10">
        <label class="label-width label-align">公司类型</label><label>:</label>
        <select class="value-width value-align" name="CrmCustomerInfo[cust_compvirtue]" id="cust_compvirtue">
            <option value="">请选择...</option>
            <?php foreach ($downList['property'] as $key => $val) { ?>
                <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['cust_compvirtue']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width ml-220 label-align">客户来源</label><label>:</label>
        <select class="value-width value-align" name="CrmCustomerInfo[member_source]" id="member_source">
            <option value="">请选择...</option>
            <?php foreach ($downList['customerSource'] as $key => $val) { ?>
                <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_source']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">经营范围</label><label>:</label>
        <select class="value-width value-align" name="CrmCustomerInfo[cust_businesstype]" id="cust_businesstype">
            <option value="">请选择...</option>
            <?php foreach ($downList['managementType'] as $key => $val) { ?>
                <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['cust_businesstype']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width ml-220 label-align">交易币种</label><label>:</label>
        <select class="value-width value-align" name="CrmCustomerInfo[member_curr]" id="member_curr">
            <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                <option
                        value="<?= $val['bsp_id'] ?>"<?= (!empty($model)?$model['member_curr']:'100091') ==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">年营业额</label><label>:</label>
        <input class="width-115 value-align easyui-validatebox Onlynum" data-options="validType:'positive'" type="text" name="CrmCustomerInfo[member_compsum]" value="<?= !empty($model['member_compsum'])?floor($model['member_compsum']):'' ?>" maxlength="15" id="member_compsum">
        <select name="CrmCustomerInfo[compsum_cur]" class="width-80 value-align" id="compsum_cur">
            <?php foreach ($downList['tradeCurrency'] as $key => $val){ ?>
                <option value="<?= $val['bsp_id'] ?>" <?= (!empty($model)?$model['compsum_cur']:'100091') ==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width ml-220 label-align">年采购额</label><label>:</label>
        <input class="width-115 easyui-validatebox value-align Onlynum" data-options="validType:'positive'" type="text" name="CrmCustomerInfo[cust_pruchaseqty]" value="<?= !empty($model['cust_pruchaseqty'])?floor($model['cust_pruchaseqty']):'' ?>" maxlength="15" id="cust_pruchaseqty">
        <select name="CrmCustomerInfo[pruchaseqty_cur]" class="width-80 value-align" id="cust_pruchaseqty_currency">
            <?php foreach ($downList['tradeCurrency'] as $key => $val){ ?>
                <option value="<?= $val['bsp_id'] ?>" <?= (!empty($model)?$model['pruchaseqty_cur']:'100091') == $val['bsp_id']?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">员工人数</label><label>:</label>
        <input class="value-width value-align easyui-validatebox" data-options="validType:'number'" type="text" name="CrmCustomerInfo[cust_personqty]" value="<?= $model['cust_personqty'] ?>" maxlength="15" id="cust_personqty">
        <label class="label-width ml-220 label-align">发票需求</label><label>:</label>
        <select class="value-width value-align" name="CrmCustomerInfo[member_compreq]" id="member_compreq">
            <option value="">请选择...</option>
            <?php foreach ($downList['InvoiceNeeds'] as $key => $val) { ?>
                <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_compreq']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">潜在需求</label><label>:</label>
        <select class="value-width value-align" name="CrmCustomerInfo[member_reqflag]" id="member_reqflag">
            <option value="">请选择...</option>
            <?php foreach ($downList['latentDemand'] as $key => $val) { ?>
                <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_reqflag']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width ml-220 label-align">需求类目</label><label>:</label>
        <select class="value-width value-align" name="CrmCustomerInfo[member_reqitemclass]" id="member_reqitemclass">
            <option value="">请选择...</option>
            <?php foreach ($downList['productType'] as $key => $val) { ?>
                <option
                        value="<?= $val['catg_id'] ?>"<?= $model['member_reqitemclass']==$val['catg_id']?"selected":null; ?>><?= $val['catg_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">需求类别</label><label>:</label>
        <input class="value-width value-align" type="text" name="CrmCustomerInfo[member_reqdesription]" value="<?= $model['member_reqdesription'] ?>" id="member_reqdesription">
        <label class="label-width ml-220 label-align">主要市场</label><label>:</label>
        <input class="value-width" type="text" name="CrmCustomerInfo[member_marketing]" value="<?= $model['member_marketing'] ?>" maxlength="50" id="member_marketing">
    </div>
    <div class="mb-10">
        <label class="label-width label-align">主要客户</label><label>:</label>
        <input class="value-width value-align" type="text" name="CrmCustomerInfo[member_compcust]" value="<?= $model['member_compcust'] ?>" maxlength="50" id="member_compcust">
        <label class="label-width ml-220 label-align">主页</label><label>:</label>
        <input class="value-width easyui-validatebox value-align" data-options="validType:'www'" type="text" name="CrmCustomerInfo[member_compwebside]" value="<?= $model['member_compwebside'] ?>" maxlength="50" id="member_compwebside" placeholder="请输入:www.xxxx.xxx">
    </div>
    <div class="mb-10">
        <label class="label-width vertical-top label-align">范围说明</label><label class="vertical-top">:</label>
        <textarea style="width:710px;" class="value-align easyui-validatebox" data-options="validType:'maxLength[200]'" name="CrmCustomerInfo[member_businessarea]" id="member_businessarea" cols="5" rows="3" maxlength="200" placeholder="最多输入200个字"><?= $model['member_businessarea'] ?></textarea>
    </div>
    <div class="mb-10">
        <label class="label-width vertical-top label-align">备注</label><label class="vertical-top">:</label>
        <textarea style="width:710px;" class="value-align easyui-validatebox" data-options="validType:'maxLength[200]'"  name="CrmCustomerInfo[member_remark]" id="member_remark" rows="3" maxlength="200" placeholder="最多输入200个字"><?= $model['member_remark'] ?></textarea>
    </div>
</div>
<div class="text-center mt-30">
    <button type="submit" class="button-blue-big">确定</button>
    <button class="button-white-big" onclick="history.go(-1);" type="button">返回</button>
</div>
    <?php ActiveForm::end() ?>
<script>
    $(function () {
        $(".IsTel").telphone();
        $(".Onlynum").numbervalid();

        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).find('i').toggleClass("icon-caret-right");
            $(this).find('i').toggleClass("icon-caret-down");
        });
        ajaxSubmitForm($("#add-form"));
        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select, $url, "select");
        });

        $('.select-date').click(function(){
            $(".select-date").jeDate({
                format:"YYYY-MM-DD",
                isTime:false,
                minDate:"2014-09-19 00:00:00"
            })
        })
    });
</script>
