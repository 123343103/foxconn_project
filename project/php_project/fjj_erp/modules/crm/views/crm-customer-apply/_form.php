<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/7
 * Time: 下午 01:51
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

\app\assets\JeDateAsset::register($this);

$regname = unserialize($model['cust_regname']);
$regnumber = unserialize($model['cust_regnumber']);
?>

<style>
    .label-width {
        width: 140px;
    }

    .value-width {
        width: 200px;
    }

    .comvalue {
        width: 180px;
    }
</style>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<h2 class="head-three">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">客户基本信息</a>
    <!--    --><?php //if(\Yii::$app->controller->action->id != "create"){?>
    <!--<span class="ml-40">
            <a href="<?= Url::to(['/crm/crm-customer-apply/select-com']) ?>" id="select-com" class="fancybox.ajax">
                选择已建立的客户资料
            </a>
        </span>-->
</h2>
<div>
    <input type="hidden" name="CrmCustomerInfo[cust_id]" value="<?= $model['cust_id'] ?>">
    <input type="hidden" name="statusApply" id="statusApply" value="">
    <input type="hidden" name="CrmCustomerApply[status]" value="<?= $caModel['status'] ?>">
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width  label-align"><span class="red">*</span>客户全称：</label>
            <input class="value-width value-align easyui-validatebox" maxlength="50" name="CrmCustomerInfo[cust_sname]"
                   id="cust_sname"
                   value="<?= $model['cust_sname'] ?>" data-options="required:'true'"
                   onchange="document.getElementById('invoicehead').value=this.value;">
        </div>
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>客户简称：</label>
            <input class="value-width value-align  easyui-validatebox" maxlength="60" id="cust_shortname"
                   name="CrmCustomerInfo[cust_shortname]"
                   data-options="required:'true'" value="<?= $model['cust_shortname'] ?>">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align" for="">公司电话：</label>
            <input class="value-width value-align easyui-validatebox" id="cust_tel1" type="text"
                   name="CrmCustomerInfo[cust_tel1]"
                   value="<?= $model['cust_tel1'] ?>" data-options="validType:'telphone'"
                   placeholder="请输入:xxxx-xxxxxxx"
                   maxlength="15">
        </div>
        <div class="inline-block">
            <label class="label-width label-align" for="">传真：</label>
            <input class="value-width value-align easyui-validatebox" data-options="validType:'fax'" id="cust_fax" type="text" name="CrmCustomerInfo[cust_fax]"
                   value="<?= $model['cust_fax'] ?>"
                   placeholder="请输入:xxxx-xxxxxxx"
                   maxlength="15">
        </div>
    </div>
    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>客户类型：</label>
        <select class="value-width  value-align easyui-validatebox" id="cust_type" name="CrmCustomerInfo[cust_type]"
                data-options="required:'true'">
            <option value="">请选择...</option>
            <?php foreach ($downList['customerType'] as $key => $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>" <?= $model['cust_type'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width label-align">客户来源：</label>
        <select class="value-width value-align" id="cust_type" name="CrmCustomerInfo[member_source]">
            <option value="">请选择...</option>
            <?php foreach ($downList['customerSource'] as $key => $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>" <?= $model['member_source'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>所在地区：</label>
        <select class="value-width value-align easyui-validatebox" id="custArea" name="CrmCustomerInfo[cust_area]"
                data-options="required:'true'">
            <option value="">请选择...</option>
            <?php foreach ($district as $key => $val) { ?>
                <option
                    value="<?= $val['district_id'] ?>" <?= $model['cust_area'] == $val['district_id'] ? "selected" : null; ?>><?= $val['district_name'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width label-align">营销区域：</label>
        <select class="value-width value-align" id="custSalearea" name="CrmCustomerInfo[cust_salearea]">
            <option value="">请选择...</option>
            <?php foreach ($salearea as $key => $val) { ?>
                <option
                    value="<?= $val['csarea_id'] ?>" <?= $model['cust_salearea'] == $val['csarea_id'] ? "selected" : null; ?>><?= $val['csarea_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>联系人：</label>
        <input class="value-width value-align easyui-validatebox" id="cust_contacts" maxlength="20"
               name="CrmCustomerInfo[cust_contacts]" data-options="required:'true'"
               value="<?= $model['cust_contacts'] ?>">
        <label class="label-width label-align"><span class="red">*</span>联系电话：</label>
        <input class="value-width value-align easyui-validatebox" type="text" id="cust_tel2"
               placeholder="请输入:138xxxxxxxx"
               onfocus="onfocustishi(this.placeholder,'请输入:138xxxxxxxx',this.id)"
               onblur="blurtishi(this.value,'请输入:138xxxxxxxx',this.id)"
               name="CrmCustomerInfo[cust_tel2]" data-options="required:'true',validType:'mobile'"
               value="<?= $model['cust_tel2'] ?>">
    </div>
    <div class="mb-10">
        <!--        <input type="hidden" name="CrmCustomerInfo[cust_manager]" class="staff_id"-->
        <!--               value="--><? //= $model['personinch']['staffId'] ?><!--">-->
        <!--        <label class="width-80">客户经理人</label>-->
        <!--        <input type="text" onblur="setStaffInfo(this)" placeholder=" 请输入工号" class="width-200" id="pfrc_person"-->
        <!--               value="--><? //= $model['personinch']['code'] ?><!--">-->
        <!--        <span class="width-50 staff_name">--><? //= $model['personinch']['manager'] ?><!--</span>-->
        <label class="label-width label-align" for="">邮箱：</label>
        <input class="value-width value-align easyui-validatebox" id="cust_email" type="text"
               name="CrmCustomerInfo[cust_email]"
               value="<?= $model['cust_email'] ?>" placeholder="请输入:xxx@xx.com"
               data-options="validType:'email'"
               maxlength="30">
        <input type="hidden" name="CrmCustomerInfo[cust_manager]" class="staff_id"
               value="<?= $model['personinch']['staffId'] ?>">
        <label class="label-width label-align"><span class="red">*</span>客户经理人：</label>
        <input type="text" class="value-width value-align staff_code easyui-validatebox" data-options="required:'true'"
               id="cust_manager"
               value="<?= $model['manager_code'] ?>"
               name="code" readonly="readonly">
        <!--        <span class="width-50 staff_name">--><? //= $model['personinch']['manager'] ?><!--</span>-->
        <?php if ($result == true) { ?>
            <a href="<?= Url::to(['/crm/crm-customer-info/select-manage']) ?>" id="select_manage">选择</a>
        <?php } ?>
    </div>

    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>详细地址：</label>
        <select class=" disName easyui-validatebox" data-options="required:'true'" id="disName_1" style="width: 134px;">
            <option value="">请选择...</option>
            <?php foreach ($country as $key => $val) { ?>
                <option
                    value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
            <?php } ?>
        </select>
        <select class=" disName easyui-validatebox" data-options="required:'true'" id="disName_2" style="width: 134px;">
            <option value="">请选择...</option>
            <?php if (!empty($districtAll2)) { ?>
                <?php foreach ($districtAll2['twoLevel'] as $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            <?php } ?>
        </select>
        <select class="width-150 disName easyui-validatebox" data-options="required:'true'" id="disName_3"
                style="width: 134px;">
            <option value="">请选择...</option>
            <?php if (!empty($districtAll2)) { ?>
                <?php foreach ($districtAll2['threeLevel'] as $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            <?php } ?>
        </select>
        <select class="width-150 disName easyui-validatebox" data-options="required:'true'" id="disName_4"
                name="CrmCustomerInfo[cust_district_2]" style="width: 134px;">
            <option value="">请选择...</option>
            <?php if (!empty($districtAll2)) { ?>
                <?php foreach ($districtAll2['fourLevel'] as $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            <?php } ?>
        </select>
        <span class="red">请确保与营业执照上的住所保持一致</span>
    </div>
    <div class="mb-10">
        <input class=" easyui-validatebox value-align" style="width: 547px;margin-left: 143px" id="cust_adress"
               type="text"
               data-options="required:'true'" placeholder="最多输入50个字"
               onfocus="onfocustishi(this.placeholder,'最多输入50个字',this.id)"
               onblur="blurtishi(this.value,'最多输入50个字',this.id)"
               name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>"
               maxlength="50">
    </div>
</div>
<h2 class="head-three">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">客户详情信息</a>
</h2>
<div class="auth">
    <div class="mb-10">
        <label class="label-width label-align" for="">公司属性：</label>
        <select class="value-width value-align" id="cust_compvirtue" name="CrmCustomerInfo[cust_compvirtue]">
            <option value="">请选择...</option>
            <?php foreach ($downList['companyProperty'] as $key => $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>" <?= $model['cust_compvirtue'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width label-align" ">公司规模：</label>
        <select class="value-width value-align" id="cust_compscale" name="CrmCustomerInfo[cust_compscale]">
            <option value="">请选择...</option>
            <?php foreach ($downList['companyScale'] as $key => $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>" <?= $model['cust_compscale'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align" for="">行业类别：</label>
        <select class="value-width value-align" id="cust_industrytype" name="CrmCustomerInfo[cust_industrytype]">
            <option value="">请选择...</option>
            <?php foreach ($industryType as $key => $val) { ?>
                <option
                    value="<?= $val['idt_id'] ?>" <?= $model['cust_industrytype'] == $val['idt_id'] ? "selected" : null; ?>><?= $val['idt_sname'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width label-align">客户等级：</label>
        <select class="value-width value-align" id="cust_level" name="CrmCustomerInfo[cust_level]">
            <option value="">请选择...</option>
            <?php foreach ($downList['custLevel'] as $key => $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>" <?= $model['cust_level'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align" for="">经营类型：</label>
        <select class="value-width value-align" id="cust_businesstype" name="CrmCustomerInfo[cust_businesstype]">
            <option value="">请选择...</option>
            <?php foreach ($downList['managementType'] as $key => $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>" <?= $model['cust_businesstype'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width label-align" for="">员工人数：</label>
        <input class="value-width value-align" id="cust_personqty" type="text" name="CrmCustomerInfo[cust_personqty]"
               value="<?= $model['cust_personqty'] ?>" maxlength="15" onkeyup="value=value.replace(/[^\d]/g,'')">
    </div>

    <div class="mb-10" style="margin-top: 8px;" ;>
        <div class="inline-block">
            <label class="label-width label-align" for="">是否上市公司：</label>
        </div>

        <div class="inline-block value-width value-align">
            <input type="radio" value="1" style="margin-left: 10px;"
                   name="CrmCustomerInfo[cust_islisted]"
                   id="yes" <?= $model['cust_islisted'] == 1 ? "checked=checked" : null; ?>>
            <span class="vertical-middle">是</span>
            <input type="radio" value="0" style="margin-left: 20px;"
                   name="CrmCustomerInfo[cust_islisted]"
                   id="no" <?= $model['cust_islisted'] == 0 ? "checked=checked" : null; ?>>
            <span class="vertical-middle">否</span>
        </div>

        <div class="inline-block">
            <label class="label-width label-align" for="">注册时间：</label>
            <input class="value-width value-align Wdate" id="cust_regdate" type="text"
                   name="CrmCustomerInfo[cust_regdate]"
                   onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate:'%y-%M-%d' })"
                   onfocus="this.blur()"
                   value="<?= empty($model['cust_regdate']) ? '' : date('Y-m-d', strtotime($model['cust_regdate'])) ?>"
            >
        </div>
        <div class="mb-10" style="margin-top: 8px">
            <label class="label-width  label-align"><span class="red">*</span>注册资金：</label>
            <input class="value-width value-align easyui-validatebox" maxlength="15"
                   name="CrmCustomerInfo[cust_regfunds]"
                   onkeyup="value=value.replace(/[^0-9]/g,'')"
                   id="cust_regfunds"
                   value="<?= !empty($model['cust_regfunds'])?$model['cust_regfunds']:'' ?>" data-options="required:'true'">
            <label class="label-width label-align"><span class="red">*</span>注册货币：</label>
            <select class="value-width value-align easyui-validatebox" id="member_curr"
                    data-options="required:'true'"
                    name="CrmCustomerInfo[member_regcurr]">
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_regcurr'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>

        </div>
        <div class="mb-10">
            <label class="label-width label-align" for="">是否公司会员：</label>
            <div class="inline-block value-width value-align">
                <input type="radio" value="1" style="margin-left: 10px;" class="ismember_y"
                       name="CrmCustomerInfo[cust_ismember]"
                       id="yes" <?= $model['cust_ismember'] == 1 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" style="margin-left: 20px;" class="ismember_n"
                       name="CrmCustomerInfo[cust_ismember]"
                       id="no" <?= $model['cust_ismember'] == 0 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">否</span>
            </div>
            <label class="label-width label-align" for="">会员类型：</label>
            <span class="value-width value-align">
                <span class="member-type value-width value-align"><?= $model['memberType'] ?></span>
                <input type="text" class="memberType display-none" name="CrmCustomerInfo[member_type]"
                       value="<?= $model['member_type'] ?>">
            </span>
        </div>

        <div class="mb-10">
            <label class="label-width label-align">潜在需求：</label>
            <select class="value-width value-align" name="CrmCustomerInfo[member_reqflag]">
                <option value="">请选择...</option>
                <?php foreach ($downList['latentDemand'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>" <?= $model['member_reqflag'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="label-width label-align">需求类目：</label>
            <select class="value-width value-align" name="CrmCustomerInfo[member_reqitemclass]">
                <option value="">请选择...</option>
                <?php foreach ($downList['productType'] as $key => $val) { ?>
                    <option
                    <option
                        value="<?= $val['catg_id'] ?>"<?= $model['member_reqitemclass'] == $val['catg_id'] ? "selected" : null; ?>><?= $val['catg_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">旺季分布：</label>
                <input class="value-width value-align" id="cust_bigseason" type="text"
                       name="CrmCustomerInfo[cust_bigseason]"
                       value="<?= $model['cust_bigseason'] ?>" maxlength="15">
            </div>
            <div class="inline-block">
                <label class="label-width label-align" for="">公司主页：</label>
                <input type="text" name="CrmCustomerInfo[member_compwebside]"
                       class="easyui-validatebox value-width value-align"
                       data-options="validType:'www'"
                       value="<?= $model['member_compwebside'] ?>" maxlength="50"
                       placeholder="请输入:www.xxxxxx.com"
                       id="member_compwebside"
                >
            </div>
        </div>
        <div class="mb-10">
            <label class="label-width label-align"><span class="red">*</span>交易币别：</label>
            <select class="value-width value-align easyui-validatebox" id="member_curr"
                    data-options="required:'true'"
                    name="CrmCustomerInfo[member_curr]">
                <!--            <option value="">请选择...</option>-->
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_curr'] == $val['bsp_id'] || $val['bsp_id'] == '100091' ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class=" label-align label-width" for=""> <span class="red">*</span>年营业额：</label>
            <input class="value-align easyui-validatebox" id="member_compsum"
                   name="CrmCustomerInfo[member_compsum]"
                   data-options="required:'true'"
                   value="<?= !empty($model['member_compsum'])?$model['member_compsum']:'' ?>" maxlength="15" onkeyup="value=value.replace(/[^0-9]/g,'')"
                   style="width: 142px;">
            <select class="width-100" id="member_curr"
                    name="CrmCustomerInfo[member_regcurr]">
                <!--            <option value="">请选择...</option>-->
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_regcurr'] == $val['bsp_id'] || $val['bsp_id'] == '100091' ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div id="reg">
            <?php if (!empty($regname) && !empty($regnumber)) { ?>
                <?php foreach ($regname as $key => $val) { ?>
                    <div class="mb-10 reg_sum">
                        <label class="label-width label-align" for="">登记证名称<?php echo $key + 1; ?>：</label>
                        <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_regname][]"
                               value="<?= $val ?>"
                               maxlength="20">
                        <label class="label-width label-align" for="">登记证号码<?php echo $key + 1; ?>：</label>
                        <input class="value-width value-align easyui-validatebox" type="text"
                               name="CrmCustomerInfo[cust_regnumber][]"
                               value="<?= $regnumber[$key] ?>" maxlength="50" data-options="validType:'regCard'">
                        <?php if ($key == 0) { ?>
                            <a class="icon-plus" onclick="add_reg()"> 添加</a>&nbsp;
                            <a class="icon-minus" onclick="del_reg()"> 删除</a>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="mb-10 reg_sum">
                    <label class="label-width label-align" for="">登记证名称：</label>
                    <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_regname][]"
                           maxlength="20">
                    <label class="label-width label-align" for="">登记证号码：</label>
                    <input class="value-width value-align easyui-validatebox" type="text"
                           name="CrmCustomerInfo[cust_regnumber][]"
                           maxlength="50" data-options="validType:'regCard'">
                    <a class="icon-plus" onclick="add_reg()"> 添加</a>&nbsp;
                    <a class="icon-minus" onclick="del_reg()"> 删除</a>
                    <!--               class="icon-plus" class="icon-minus"-->
                </div>
            <?php } ?>
        </div>
        <div class="mb-10">
            <label for="pdvisitplan-note" class="label-width label-align vertical-top">经营范围：</label>
            <textarea rows="3" name="CrmCustomerInfo[member_businessarea]" style="width: 547px;" id="pdvisitplan-note"
                      placeholder="最多输入200个字"
                      maxlength="200"><?= $model['member_businessarea'] ?></textarea>
        </div>
        <div class="mb-10">
            <label class="label-width label-align" for=""><span class="red">*</span>申请的发票类型：</label>
            <select name="CrmCustomerInfo[invoice_type]" id="" class="value-width value-align easyui-validatebox"
                    data-options="required:'true'">
                <!--            <option value="">请选择...</option>-->
                <?php foreach ($downList['invoiceType'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['invoice_type'] == $val['bsp_id'] || $val['bsp_id'] == '100300' ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>


            <label class="label-width label-align" for="">发票抬头：</label>
            <input class="value-width value-align" type="text" id="invoicehead" name="CrmCustomerInfo[invoice_title]"
                   value="<?= $model['cust_sname'] ?>" maxlength="50" readonly="readonly">
        </div>
        <div class="mb-10">
            <label class="label-width label-align">发票抬头地址：</label>
            <select class=" value-align disName1" id="disName_1"
                    style="width: 134px;">
                <option value="">请选择...</option>
                <?php foreach ($country as $key => $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select class=" value-align disName1" id="disName_2" style="width: 134px;">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['twoLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class=" value-align disName1" id="disName_3" style="width: 134px;">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['threeLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class=" value-align disName1" id="disName_4" name="CrmCustomerInfo[invoice_title_district]"
                    style="width: 134px;">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['fourLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <input type="text" style="margin-left: 143px;width: 547px;"
                   name="CrmCustomerInfo[invoice_title_address]"
                   placeholder="最多输入50个字"
                   value="<?= $model['cust_headquarters_address'] ?>" maxlength="50">
        </div>
        <div class="mb-10">
            <label class="label-width label-align">发票寄送地址：</label>
            <select class=" disName1" id="disName_1" style="width: 134px;">
                <option value="">请选择...</option>
                <?php foreach ($country as $key => $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select class="width-120 disName1" id="disName_2" style="width: 134px;">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['twoLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="width-150 disName1" id="disName_3" style="width: 134px;">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['threeLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="width-150 disName1" id="disName_4" name="CrmCustomerInfo[invoice_mail_district]"
                    style="width: 134px;">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['fourLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <input style="margin-left: 143px;width: 547px;" type="text"
                   name="CrmCustomerInfo[invoice_mail_address]"
                   placeholder="最多输入50个字"
                   value="<?= $model['cust_headquarters_address'] ?>" maxlength="50">
        </div>
        <div class="mb-10">
            <label class="label-width label-align" for="">总公司：</label>
            <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_parentcomp]"
                   value="<?= $model['cust_parentcomp'] ?>" maxlength="120">

            <label class="label-width label-align" for="">公司负责人：</label>
            <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_inchargeperson]"
                   value="<?= $model['cust_inchargeperson'] ?>" maxlength="20">
        </div>

        <div class="mb-10">
            <label class="label-width label-align">总公司地址：</label>
            <select class=" disName1" id="disName_1" style="width: 134px;">
                <option value="">请选择...</option>
                <?php foreach ($country as $key => $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select class=" disName1" id="disName_2" style="width: 134px;">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['twoLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class=" disName1" id="disName_3" style="width: 134px;">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['threeLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class=" disName1" id="disName_4" name="CrmCustomerInfo[cust_district_3]" style="width: 134px;">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['fourLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <input style="margin-left: 143px;width:547px;" type="text"
                   name="CrmCustomerInfo[cust_headquarters_address]"
                   placeholder="最多输入50个字"
                   value="<?= $model['cust_headquarters_address'] ?>" maxlength="50">
        </div>
    </div>
</div>

<h2 class="head-three">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">认证信息</a>
</h2>
<div class="auth">
    <div class="mb-10">
        <input type="hidden" id="ynspp" value="<?= $crmcertf['yn_spp'] ?>">
        <label for="" class="comvalue">是否供应商：</label>
        <input type="radio" value="1" id="radyes" name="CrmC[yn_spp]">
        <!--                --><? //= $crmcertf['YN_SPP'] == 1 ? "checked=checked" : null; ?>
        <span class="vertical-middle">是</span>
        <input type="radio" value="0" id="radno" checked=checked name="CrmC[yn_spp]" style="margin-left: 50px;">
        <!--                --><? //= $crmcertf['YN_SPP'] == 0 ? "checked=checked" : null; ?>
        <span class="vertical-middle">否</span>
        <span style="margin-right: 302px;display: none;float:right" id="custcode">
            <label class="lable-width label-align " for="" style="margin-left: 50px;"><span
                    class="red">*</span>供应商代码：</label>
                <input class=" value-align" id="sppno"
                       style="width: 240px;"
                       type="text" name="CrmC[spp_no]"
                       value="<?= $crmcertf['spp_no'] ?>" maxlength="30">
            </span>
    </div>
    <div class="mb-10">
        <input type="hidden" id="crtftype" value="<?= $crmcertf['crtf_type'] ?>">
        <label class="comvalue label-align" for=""><span class="red">*</span>证件类型：</label>
        <input type="radio" value="0" id="old"
               checked="checked" <?= $crmcertf['crtf_type'] == 0 ? "checked=checked" : null; ?>
               name="CrmC[crtf_type]">
        <span class="vertical-middle">旧版三证</span>
        <input type="radio" value="1" id="new"
               style="margin-left: 14px;" <?= $crmcertf['crtf_type'] == 1 ? "checked=checked" : null; ?>
               name="CrmC[crtf_type]">
        <span class="vertical-middle">新版三证合一</span>
        <span style="margin-left: 40px;color: #989898">证件格式：JPG、PNG、TIF，PDF、BMP、压缩档7z、rar、zip等文件小于2M。</span>
    </div>
    <div class="mb-10">
        <label class="comvalue" for=""><span class="red">*</span>税籍编码/统一社会信用代码：</label>
        <span>
<!--            <input class="value-align easyui-validatebox" id="taxcode"-->
<!--                     style="width: 200px;"-->
<!--                     name="CrmCustomerInfo[cust_tax_code]" data-options="required:'true'"-->
<!--                     value="--><?//= $model['cust_tax_code'] ?><!--"-->
<!--                     maxlength="100">-->
            <input name="CrmCustomerInfo[cust_tax_code]" class="value-width value-align  easyui-validatebox remove" data-options="required:true,delay:10000,validateOnBlur:true" data-act="<?=Url::to(['/crm/crm-customer-info/validate-code'])?>"  data-attr="cust_tax_code" data-id="<?=$model['cust_id'];?>" type="text" value="<?= $model['cust_tax_code'] ?>" maxlength="20" placeholder="最多输入20个字" id="taxcode">
        </span>
        <!--        <div class="inline-block" style="vertical-align:middle">-->
        <!--            <label class="label-width label-align" for=""><span class="red">*</span>税籍编码/统一<br>社会信用代码：</label>-->
        <!--        </div>-->
        <!--        <div class="inline-block" id="aaaa">-->
        <!--            <input class=" value-align easyui-validatebox" id="taxcode"-->
        <!--                   style="width: 200px; "-->
        <!--                   name="CrmCustomerInfo[cust_tax_code]" data-options="required:'true'"-->
        <!--                   value="--><? //= $model['cust_tax_code'] ?><!--" maxlength="100">-->
        <!--        </div>-->
    </div>
    <div id="oldthreecer">
        <div class="mb-10">
            <label class="comvalue label-align" id="business"><span class="red">*</span>公司营业执照证：</label>
            <input class="easyui-validatebox" type="text" readonly="readonly"
                   name="CrmC[o_license]" data-options="required:'true'" maxlength="120" id="license_name"
                   value="<?= $crmcertf['o_license'] ?>" style="width: 500px;">
            <!--            onchange="document.getElementById('UPFILES-LIC').value=this.value"-->
            <input type="file" style="width: 70px;" multiple="multiple" name="upfiles-lic" id="upfiles-lic"
                   class="up-btn" onchange="license('upfiles-lic')"/>
            <!--                   onchange="document.getElementById('LICENSE_NAME').value=this.value;"-->
        </div>
        <div class="mb-10" id="tax">
            <label class="comvalue label-align"><span class="red">*</span>税务登记证：</label>
            <input class="easyui-validatebox" type="text" readonly="readonly"
                   name="CrmC[o_reg]" data-options="required:'true'" maxlength="120" id="tax_name"
                   value="<?= $crmcertf['o_reg'] ?>" style="width: 500px;">
            <!--                   onchange="document.getElementById('UPFILES-TAX').value=this.value"-->
            <input type="file" style="width: 70px;" name="upfiles-tax" id="upfiles-tax" class="up-btn"
                   onchange="reg('upfiles-tax')"/>
            <!--            onchange="document.getElementById('TAX_NAME').value=this.value;"-->
        </div>
    </div>
    <div class="mb-10">
        <label class="comvalue label-align">一般纳税人资格证：</label>
        <input style="width: 500px;" type="text" readonly="readonly"
               name="CrmC[o_cerft]" maxlength="120" id="org_name"
               value="<?= $crmcertf['o_cerft'] ?>">
        <!--    <span class="red">*</span>      class="easyui-validatebox"    data-options="required:'true'"   onchange="document.getElementById('UPFILES-ORG').value=this.value"-->
        <input type="file" style="width: 70px;" name="upfiles-org" id="upfiles-org" class="up-btn"
               onchange="cerft('upfiles-org')"/>
        <!--               onchange="document.getElementById('ORGANIZATION_NAME').value=this.value;"-->
    </div>
    <div class="mb-10">
        <label class="comvalue label-align vertical-top">备注：</label>
        <textarea rows="3" name="CrmC[marks]" style="width: 575px;" id="remark"
                  placeholder="最多输入200个字"
                  maxlength="200"><?= $crmcertf['marks'] ?></textarea>
    </div>
</div>

<h2 class="head-three">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">主要联系人</a>
</h2>
<div class="auth">
    <div class="mb-10" style="width: 100%;overflow-x: scroll;">
        <table class="table" style="width: 2000px;">
            <thead>
            <tr>
                <th width="60">序号</th>
                <th><span class="red">*</span>姓名</th>
                <th><span class="red">*</span>性别</th>
                <th><span class="red">*</span>职务</th>
                <th>部门</th>
                <th>座机</th>
                <th><span class="red">*</span>手机</th>
                <th width="180">生日</th>
                <th>传真</th>
                <th>邮箱</th>
                <th>QQ</th>
                <th>微信</th>
                <th>是否主要联系人</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="contacts_table">
            <?php if (!empty($model['contactPersons'])) { ?>
                <?php foreach ($model['contactPersons'] as $key => $val) { ?>
                    <tr>
                        <td>
                            <span style="width: 50px;"><?= $key + 1 ?></span>
                        </td>
                        <td>
                            <input class="width-90 no-border text-center easyui-validatebox " type="text"
                                   data-options="required:'true',validType:['tdSame','length[0,20]']"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_name]"
                                   value="<?= $val['ccper_name'] ?>" maxlength="20">
                        </td>
                        <td>
                            <select name="CrmCustomerPersion[<?= $key ?>][ccper_sex]" id="">
                                <option value="1" <?= $val['ccper_sex'] == '1' ? "selected" : null; ?>>--男--</option>
                                <option value="0" <?= $val['ccper_sex'] == '0' ? "selected" : null; ?>>--女--</option>
                            </select>
                        </td>
                        <td><input class="width-70 no-border text-center  easyui-validatebox" type="text"
                                   data-options="required:'true',validType:'length[0,20]'"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_post]"
                                   value="<?= $val['ccper_post']?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:'length[0,50]'" type="text"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_deparment]"
                                   value="<?= $val['ccper_deparment'] ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" type="text" id="ccper_tel"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_tel]" data-options="validType:'telphone'"
                                   placeholder="请输入:xxxx-xxxxxxx"
                                   value="<?= $val['ccper_tel'] ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" type="text"
                                   data-options="required:'true',validType:'mobile'" id="ccper_mobile"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_mobile]" placeholder="请输入:138xxxxxxxx"
                                   value="<?= $val['ccper_mobile'] ?>" maxlength="20"></td>
                        <td><input class=" no-border text-center Wdate"
                                   type="text" id="ccper_birthday" style="width: 150px;"
                                   onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate:'%y-%M-%d' })"
                                   onfocus="this.blur()"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_birthday]" onclick="birDate(this)"
                                   value="<?= empty($val['ccper_birthday']) ? '' : date('Y-m-d', strtotime($val['ccper_birthday'])) ?>"
                                   readonly="readonly"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" type="text" id="ccper_fax"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_fax]" placeholder="请输入:xxxx-xxxxxxx"
                                   data-options="validType:'telphone'"
                                   value="<?= $val['ccper_fax'] ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" type="text" id="ccper_mail"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_mail]" data-options="validType:'email'"
                                   placeholder="请输入:xxx@xx.com"
                                   value="<?= $val['ccper_mail'] ?>" data-options="validType:'email'"
                                   maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:'qq'" type="text"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_qq]"
                                   value="<?= $val['ccper_qq'] ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:'length[0,50]'" type="text"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_wechat]"
                                   value="<?= $val['ccper_wechat'] ?>" maxlength="20"></td>
                        <td>
                            <select name="CrmCustomerPersion[<?= $key ?>][ccper_ismain]" id="">
                                <option value="1" <?= $val['ccper_ismain'] == '1' ? "selected" : null; ?>>--是--</option>
                                <option value="0" <?= $val['ccper_ismain'] == '0' ? "selected" : null; ?>>--否--</option>
                            </select>
                        </td>
                        <td><span style="width: 100px;"><a onclick="reset(this)">重置</a> <a
                                    onclick="vacc_del(this,'contacts_table')" style="margin-left: 20px">删除</a></span>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div>
        <p class="text-right mt-10">
            <a class="icon-plus" onclick="add_contacts()"> 添加联系人</a>
        </p>
    </div>
</div>
<h2 class="head-three">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">设备信息</a>
</h2>
<div class="auth">
    <div class="mb-10">
        <table class="table">
            <thead>
            <tr>
                <th width="60">序号</th>
                <th>设备类型</th>
                <th>设备品牌</th>
                <th class="width-80">操作</th>
            </tr>
            </thead>
            <tbody id="equipment_table">
            <?php if (!empty($model['custDevice'])) { ?>
                <?php foreach ($model['custDevice'] as $key => $val) { ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><input class="width-200 no-border text-center" type="text"
                                   name="CrmCustDevice[<?= $key ?>][type]" maxlength="20"
                                   value="<?= $val['type'] ?>"></td>
                        <td><input class="width-200 no-border text-center" type="text"
                                   name="CrmCustDevice[<?= $key ?>][brand]" maxlength="20"
                                   value="<?= $val['brand'] ?>"></td>
                        <td><a onclick="reset(this)">重置</a> <a onclick="vacc_del(this,'equipment_table')">删除</a></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
        <p class="text-right mt-10">
            <a class="icon-plus" onclick="add_equipment()">添加设备</a>
        </p>
    </div>
</div>
<h2 class="head-three">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">主营产品</a>
</h2>
<div class="auth">
    <div class="mb-10">
        <table class="table">
            <thead>
            <tr>
                <th width="60">序号</th>
                <th><span class="red">*</span>主要产品</th>
                <th>规格/型号</th>
                <th>年产量</th>
                <th>品牌</th>
                <th>备注</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="product_table">
            <?php if (!empty($model['custProduct'])) { ?>
                <?php foreach ($model['custProduct'] as $key => $val) { ?>
                    <tr>
                        <td style="width: 50px;"><?= $key + 1 ?></td>
                        <td><input class="width-100 no-border text-center easyui-validatebox" type="text"
                                   data-options="required:'true'"
                                   name="CrmCustProduct[<?= $key ?>][ccp_sname]" maxlength="20"
                                   value="<?= $val['ccp_sname'] ?>"></td>
                        <td><input class="width-100 no-border text-center" type="text"
                                   name="CrmCustProduct[<?= $key ?>][ccp_model]" maxlength="20"
                                   value="<?= $val['ccp_model'] ?>"></td>
                        <td><input class="width-100 no-border text-center" type="text"
                                   name="CrmCustProduct[<?= $key ?>][ccp_annual]" maxlength="20"
                                   value="<?= $val['ccp_annual'] ?>"></td>
                        <td><input class="width-100 no-border text-center" type="text"
                                   name="CrmCustProduct[<?= $key ?>][ccp_brand]" maxlength="20"
                                   value="<?= $val['ccp_brand'] ?>"></td>
                        <td><input class="width-100 no-border text-center" type="text"
                                   name="CrmCustProduct[<?= $key ?>][ccp_remark]" maxlength="200" style="width: 200px;"
                                   value="<?= $val['ccp_remark'] ?>"></td>
                        <td><a onclick="reset(this)">重置</a> <a onclick="vacc_del(this,'product_table')">删除</a></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
        <p class="text-right mt-10">
            <a class="icon-plus" onclick="add_product()">添加产品</a>
        </p>
    </div>
</div>
<h2 class="head-three">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">主要客户</a>
</h2>
<div class="auth">
    <div class="mb-10" style="width: 100%;overflow-x: scroll;">
        <table class="table" style="width:2000px">
            <thead>
            <tr>
                <th width="60">序号</th>
                <th><span class="red">*</span>公司名称</th>
                <th width="180">经营类型</th>
                <th width="100">公司负责人</th>
                <th width="100">电话（手机）</th>
                <th>公司电话</th>
                <th class="width-50">占营收比率（%）</th>
                <th>备注</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody id="customer_table">
            <?php if (!empty($model['custCustomer'])) { ?>
                <?php foreach ($model['custCustomer'] as $key => $val) { ?>
                    <tr>
                        <td><span ></span><?= $key + 1 ?></td>
                        <td><input class=" no-border text-center easyui-validatebox" type="text" style="width: 60px"
                                   data-options="required:'true'"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_name]"
                                   maxlength="20" value="<?= $val['cc_customer_name']?>"></td>
                        <td><select name="CrmCustCustomer[<?= $key ?>][cc_customer_type]" style="width: 180px;">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['managementType'] as $k => $v) { ?>
                                    <option value="<?= $v['bsp_id'] ?>" <?= $val['cc_customer_type'] == $v['bsp_id'] ? "selected" : null; ?>><?= $v['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select></td>
                        <td><input class="width-80 no-border text-center" type="text"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_person]"
                                   maxlength="20" value="<?= $val['cc_customer_person'] ?>"></td>
                        <td><input class="width-80 no-border text-center easyui-validatebox" type="text"
                                   id="cc_customer_mobile"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_mobile]"
                                   placeholder="请输入:138 xxxx xxxx" data-options="validType:'mobile'"
                                   maxlength="20" value="<?= $val['cc_customer_mobile'] ?>"></td>
                        <td><input class="width-100 no-border text-center easyui-validatebox" type="text"
                                   id="cc_customer_tel"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_tel]"
                                   placeholder="请输入:xxxx-xxxxxxxx" data-options="validType:'telphone'"
                                   maxlength="20" value="<?= $val['cc_customer_tel'] ?>"></td>
                        <td><input class="width-50 no-border text-center" type="text"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_ratio]"
                                   value="<?= $val['cc_customer_ratio'] ?>" maxlength="20"></td>
                        <td><input class="width-100 no-border text-center" type="text"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_remark]"
                                   maxlength="200" value="<?= $val['cc_customer_remark'] ?>"></td>
                        <td><span style="width: 100px;"><a onclick="reset(this)">重置</a> <a
                                    onclick="vacc_del(this,'customer_table')" style="margin-left: 20px;">删除</a></span>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="mb-10">
        <p class="text-right mt-10">
            <a class="icon-plus" onclick="add_customer()">添加客户</a>
        </p>
    </div>
</div>
<div class="text-center" style="color:">
    <!--<button type="submit" class="button-blue-big save-form">保存</button>
    <button type="submit" class="button-white-big apply-form">申请</button>
    <button type="button" class="button-white-big" onclick="history.go(-1)">取消</button>-->
    <?php if (isset($status)) { ?>
        <button id="apply-form" class="button-blue-big"
                type="submit" <?= $caModel['status'] == 30 ? 'disabled' : '' ?>>提交
        </button>
        <button onclick="history.go(-1);" type="button" class="button-white-big">取消</button>
    <?php } else { ?>
        <button id="save-form" class="button-blue-big" <?= $caModel['status'] == 30 ? 'disabled' : '' ?>>保存</button>
        <button id="apply-form" class="button-blue-big" type="submit" <?= $caModel['status'] == 30 ? 'disabled' : '' ?>>提交</button>
        <button onclick="history.go(-1);" type="button" class="button-white-big" style="margin-left: 0px;">取消</button>
    <?php } ?>
</div>
<?php ActiveForm::end(); ?>
<style type="text/css">
    .head-three + div {
        display: none;
    }
</style>
<script>
    function onfocustishi(val, title, id) {
        if (val == title) {
            $("#" + id).attr("placeholder", "");
        }
    }
    function blurtishi(val, title, id) {
        if (val == "") {
            $("#" + id).attr("placeholder", title);
        }
    }
    function change(obj) {
        var dom = document.getElementById(obj);
        var mList = dom.value;
        str = mList.substring(mList.lastIndexOf("\\") + 1);
        return str;
//       var length = obj.files.length;
//        var temp = "";
//        console.log(obj.files.length);
//        for (var i = 0; i < obj.files.length; i++) {
//            if (i == 0) {
//                temp = obj.files[i].name;
//            } else {
//                temp = temp + "&nbsp,&nbsp" + obj.files[i].name;
//            }
//            return temp;
//        }
    }
    //三证合一和营业执照文件名
    function license(obj) {
        $("#license_name").val(change(obj)).validatebox("enableValidation");
//        $("#upfiles-lic").val(change(obj));
    }
    //税务登记证文件名
    function reg(obj) {
        $("#tax_name").val(change(obj)).validatebox("enableValidation");
//        $("#upfiles-tax").val(change(obj));
    }
    //一般纳税人资格证文件名
    function cerft(obj) {
        $("#org_name").val(change(obj)).validatebox("enableValidation");
//        $("#upfiles-org").val(change(obj));
    }

    //    $("#taxcode").blur(function () {
    //        if (document.getElementById("old").checked) {
    //            var oldlen = $("#taxcode").val().length;
    //            if (oldlen >= 9 && oldlen <= 20) {
    //                alert("税籍编码只能输入9-20个字,且只能是数字和字母");
    //            }
    //        }
    //        if (document.getElementById("new").checked) {
    //            var newlen = $("#taxcode").val().length;
    //            if (newlen >= 17 && newlen <= 20) {
    //                alert("税籍编码只能输入17-20字,且只能是数字和字母");
    //            }
    //        }
    //        //$('input:radio:checked')
    //    });
    //当是供应商时显示供应商代码
    $("#radyes").click(function () {
        $("#radyes").attr('checked', 'checked');
        $("#radno").attr('checked', false);
        $("#custcode").show();
        $('#sppno').validatebox({required: true, validType: 'regCard'});
    });
    //    $("#sppno").mouseover(function () {
    //        $("#sppno").attr('class', 'easyui-validatebox validatebox-text validatebox-invalid tooltip-f');
    //    });
    //    $("#sppno").mouseout(function () {
    //        $("#sppno").attr('class', 'easyui-validatebox validatebox-text  validatebox-invalid');
    //    });
    //    $("#sppno").focus(function () {
    //        $("#sppno").attr('class', 'easyui-validatebox validatebox-text validatebox-invalid tooltip-f');
    //    });
    //    $("#sppno").blur(function () {
    //        $("#sppno").attr('class', 'easyui-validatebox validatebox-text  validatebox-invalid');
    //    });
    //当不是供应商时隐藏供应商代码
    $("#radno").click(function () {
        $("#radno").attr('checked', 'checked');
        $("#radyes").attr('checked', false);
        $("#custcode").css('display', 'none');
        $('#sppno').validatebox({required: false});
    });

    //当选择旧版三证时，隐藏公司三证合一证显示公司营业执照证和税务登记证
    //    $("#old").click(function () {
    //        $("#old").attr('checked', 'checked');
    //        $("#new").attr('checked', false);
    //        $("#business").html("<span class='red'>*</span>公司营业执照证：");
    //        $("#tax").css('display', 'block');
    //        $("#tax_name").attr('class', 'easyui-validatebox width-550');
    //        $("#tax_name").attr('data-options', "required:'true'");
    //        var len = $("#taxcode").val().length;
    //        if (len < 9) {
    //            alert("税籍编码不能小于9位");
    //        }
    //        $("#newthreeinone").css('display', 'none');
    //    });
    //当选择新版三证合一时，显示公司三证合一证隐藏公司营业执照证和税务登记证
    $("input[name='CrmC[crtf_type]']").click(function () {
        if ($(this).val() == 0) {
            $("#old").attr('checked', 'checked');
            $("#new").attr('checked', false);
            $("#business").html("<span class='red'>*</span>公司营业执照证：");
            $("#tax").css('display', 'block');
            $("#tax_name").attr('class', 'easyui-validatebox width-550');
            $("#tax_name").attr('data-options', "required:'true'");
            $("#taxcode").validatebox({
                validType: ['taxCodeOld','unique']
            });
        }
        if ($(this).val() == 1) {
            $("#new").attr('checked', 'checked');
            $("#old").attr('checked', false);
            $("#business").html("<span class='red'>*</span>公司三证合一证：");
            $("#tax").css('display', 'none');
            $("#tax_name").removeAttr('class');
            $("#tax_name").removeAttr('data-options');
            $("#taxcode").validatebox({
                validType: ['taxCodeNew','unique']
            });
        }
    });
    //    $("#new").click(function () {
    //        $("#new").attr('checked', 'checked');
    //        $("#old").attr('checked', false);
    //        $("#business").html("<span class='red'>*</span>公司三证合一证：");
    //        $("#tax").css('display', 'none');
    //        $("#tax_name").removeAttr('class');
    //        $("#tax_name").removeAttr('data-options');
    //        $("#taxcode").attr('data-options',"required:'true',validType:'taxCodeNew'");
    //        $.parser.parse($('#aaaa'));
    ////        var len = $("#taxcode").val().length;
    ////        if (len < 17) {
    ////            alert("税籍编码不能小于17位");
    ////        }
    ////        $("#newthreeinone").css('display', 'block');
    //    });
    function setStaffInfo(obj) {
        var url = "<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>";
        staffInfo(obj, url);
    }
    $(function () {
        $('.ismember_y').click(function () {
            $('.memberType').val('100070');
            $('.memberType').val() && $('.member-type').text('普通会员');
        })
        $('.ismember_n').click(function () {
            $('.memberType').val('');
            $('.member-type').text("");
        })
        ajaxSubmitForm($("#add-form"));
        if (document.getElementById("old").checked) {
            $("#taxcode").validatebox(
                {
                    validType: ['taxCodeOld','unique']
                }
            )
        }
        else {
            $("#taxcode").validatebox(
                {
                    validType: ['taxCodeNew','unique']
                }
            )
        }
        $.extend($.fn.validatebox.defaults.rules, {
            taxCodeOld: {
                validator: function (value, param) {
                    return /^[0-9a-zA-Z]{9,20}$/.test(value);
                },
                message: '必须为9-20位的数字,字母组合'
            },
            taxCodeNew: {
                validator: function (value, param) {
                    return /^[0-9a-zA-Z]{17,20}$/.test(value);
                },
                message: '必须为17-20位的数字,字母组合'
            },
            regCard: {
                validator: function (value, param) {
                    return /^[0-9a-zA-Z]*$/.test(value);
                },
                message: '只能输入字母或数字'
            }
        });
        //敲击按键时触发
        $("#cust_regfunds,#member_compsum").bind("keypress", function (event) {
            var event = event || window.event;
            var getValue = $(this).val();
            //控制第一个不能输入小数点"."
            if (getValue.length == 0 && event.which == 46) {
                //alert(1)
                event.preventDefault();
                return;
            }
            //控制只能输入一个小数点"."
            if (getValue.indexOf('.') != -1 && event.which == 46) {
                event.preventDefault();
                //alert(1)
                return;
            }
            var reg = /^(\-)*(\d+)\.(\d\d\d).*$/;
            if (reg.test($(this).val())) {
                event.preventDefault();
                return;
            }
        });

        <?php if(\Yii::$app->controller->action->id == "create"){?>
        <?php }else{ ?>
        var ynspp = $("#ynspp").val();//获取是否供应商标记
        if (ynspp == '0') {
            $("#radyes").attr('checked', false);
            $("#radno").attr('checked', 'checked');
            $("#custcode").css('display', 'none');
            $("#sppno").removeAttr('class');
            $("#sppno").removeAttr('data-options');
        }
        if (ynspp == '1') {
            $("#radyes").attr('checked', 'checked');
            $("#radno").attr('checked', false);
            $("#custcode").css('display', 'block');
            $("#sppno").attr('class', 'easyui-validatebox');
            $("#sppno").attr('data-options', "required:'true'");
        }
        var types = $("#crtftype").val();//获取证件类型
        if (types == '1') {
            $("#new").attr('checked', 'checked');
            $("#old").attr('checked', false);
            $("#tax").css('display', 'none');
            $("#tax_name").removeAttr('class');
            $("#tax_name").removeAttr('data-options');
            $("#business").html("<span class='red'>*</span>公司三证合一证：");
        }
        if (types == '0') {
            $("#new").attr('checked', false);
            $("#old").attr('checked', 'checked');
            $("#tax").css('display', 'block');
            $("#business").html("<span class='red'>*</span>公司营业执照证：");
            $("#tax_name").attr('class', 'easyui-validatebox width-550');
            $("#tax_name").attr('data-options', "required:'true'");
        }
//        ajaxSubmitForm($("#add-form"));//crmtomer-info
        <?php }?>
        //修改申请代码保存按钮
        $("#save-form").click(function () {
            $("#statusApply").val('10');
            $("#add-form").attr('action', '<?= \yii\helpers\Url::to(['/crm/crm-customer-apply/update-customer']) ?>');
//            ajaxSubmitForm($("#add-form"), function () {
//                if (!$("#add-form").form('validate')) {
//                    var item = $('.validatebox-invalid:first') || null;
//                    var item0=$('.validatebox-invalid');
//                    if (item != null) {
//                        item.parents('.auth').show();
//                        item.focus(); // 展开后获取焦点
//                        $("button[type='submit']").prop("disabled", false);
//                    }
//                    return false;
//                }
//                else {
//                    $("#statusApply").val('10');
//                    $("#add-form").attr('action', '<?//= \yii\helpers\Url::to(['/crm/crm-customer-apply/update-customer']) ?>//');
//                    $("#add-form").submit();
//                }
//            });
        });
//        ajaxSubmitForm($("#add-form"));//crmtomer-info
        <?php if(isset($status)){ ?>
        $("#apply-form").click(function () {
            $("#statusApply").val('20');
            $("#add-form").attr('action', '<?= \yii\helpers\Url::to(['/crm/crm-customer-apply/update-customer?is_apply=1&status=1']) ?>');
        })
        <?php }else{ ?>
        $("#apply-form").click(function () {
            $("#statusApply").val('20');
            $("#add-form").attr('action', '<?= \yii\helpers\Url::to(['/crm/crm-customer-apply/update-customer?is_apply=1&status=0']) ?>');
//            ajaxSubmitForm($("#add-form"), function () {
//                if (!$("#add-form").form('validate')) {
//                    var item = $('.validatebox-invalid:first') || null;
//                    var item0=$('.validatebox-invalid');
//                    if (item != null) {
//                        item.parents('.auth').show();
//                        item.focus(); // 展开后获取焦点
//                        $("button[type='submit']").prop("disabled", false);
//                    }
//                    return false;
//                }
//                else {
//                    $("#statusApply").val('20');
//                    $("#add-form").attr('action', '<?//= \yii\helpers\Url::to(['/crm/crm-customer-apply/update-customer?is_apply=1']) ?>//');
//                    $("#add-form").submit();
//                }
//            });
        })
        <?php } ?>
        $('#select-com').fancybox(      //选择厂商弹框
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

        $(".save-form").on('click', function () {
            $("#custSalearea").removeAttr('disabled');
        })
        $(".head-three").next("div:eq(0)").css("display", "block");
        $(".head-three>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-right");
            $(this).prev().toggleClass("icon-caret-down");
        });

        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select, $url, "select");
        });
        $('.disName1').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select, $url, "select");
        });
    });
    function add_contacts() {
        var a = $("#contacts_table tr").length;
        var b = a;
        b += 1;
        var obj = $("#contacts_table").append(
            '<tr">' +
            '<td>'+ b +'</td>'+
            '<td><input class="width-90 no-border text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustomerPersion[' + a + '][ccper_name]" placeholder="" maxlength="20"></td>' +
            '<td><select name="CrmCustomerPersion[' + a + '][ccper_sex]" class="easyui-validatebox"  data-options="required:true">' +
            '<option value="1">--男--</option>' +
            '<option value="0">--女--</option>' +
            '</select></td>' +
            '<td><input class="width-70 no-border text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustomerPersion[' + a + '][ccper_post]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-70 no-border text-center" type="text"  name="CrmCustomerPersion[' + a + '][ccper_deparment]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:\'telphone\'" type="text"  name="CrmCustomerPersion[' + a + '][ccper_tel]" placeholder="请输入:xxxx-xxxxxxxx" maxlength="20"></td>' +
            '<td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:\'mobile\',required:true" type="text"  name="CrmCustomerPersion[' + a + '][ccper_mobile]" placeholder="请输入:138 xxxx xxxx" maxlength="20"></td>' +
            '<td><input class="width-70 no-border text-center Wdate" type="text"  name="CrmCustomerPersion[' + a + '][ccper_birthday]" placeholder="" maxlength="20" onclick="WdatePicker({ skin: \'whyGreen\', dateFmt: \'yyyy-MM-dd\',maxDate:\'%y-%M-%d\'})" readonly="readonly"></td>' +
            '<td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:\'fax\'"  type="text"  name="CrmCustomerPersion[' + a + '][ccper_fax]" placeholder="请输入:xxxx-xxxxxxxx" maxlength="20"></td>' +
            '<td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:\'email\'" type="text"  name="CrmCustomerPersion[' + a + '][ccper_mail]" placeholder="请输入:xxxx@xxx.xxx" maxlength="20"></td>' +
            '<td><input class="width-70 no-border text-center" type="text"  name="CrmCustomerPersion[' + a + '][ccper_qq]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-70 no-border text-center" type="text"  name="CrmCustomerPersion[' + a + '][ccper_wechat]" placeholder="" maxlength="20"></td>' +
            '<td><select name="CrmCustomerPersion[' + a + '][ccper_ismain]" id="">' +
            '<option value="1">--是--</option>' +
            '<option value="0">--否--</option>' +
            '</select></td>' +
            '<td><a onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,'+ "'contacts_table'" +')">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }
    function add_equipment() {
        var a = $("#equipment_table tr").length;
        var b = a;
        b += 1;
        $("#equipment_table").append(
            '<tr>' +
            '<td>' + b + '</td>' +
            '<td><input class="width-200 no-border text-center easyui-validatebox"  data-options="validType:\'length[0,20]\'"  type="text"  name="CrmCustDevice[' + a + '][type]"></td>' +
            '<td><input class="width-200 no-border text-center easyui-validatebox" data-options="validType:\'length[0,20]\'" type="text"  name="CrmCustDevice[' + a + '][brand]"></td>' +
            '<td><a onclick="reset(this)">重置</a>  <a style="margin-left: 15px" onclick="vacc_del(this,' + "'equipment_table'" + ')">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse($("#equipment_table").find("tr:last"));
        a++;
    }
    function add_product() {
        var a = $("#product_table tr").length;
        var b = a;
        b += 1;
        $("#product_table").append(
            '<tr>' +
            '<td>' + b + '</td>' +
            '<td><input class="width-100 no-border text-center easyui-validatebox" type="text"  data-options="validType:\'length[0,20]\'" name="CrmCustProduct[' + a + '][ccp_sname]"  ></td>' +
            '<td><input class="width-100 no-border text-center" type="text" data-options="validType:\'length[0,20]\'"   name="CrmCustProduct[' + a + '][ccp_model]" ></td>' +
            '<td><input class="width-100 no-border text-center" type="text" data-options="validType:\'length[0,20]\'"   name="CrmCustProduct[' + a + '][ccp_annual]"  ></td>' +
            '<td><input class="width-100 no-border text-center" type="text" data-options="validType:\'length[0,20]\'"   name="CrmCustProduct[' + a + '][ccp_brand]"   ></td>' +
            '<td><input class="width-100 no-border text-center" type="text" data-options="validType:\'length[0,200]\'"  name="CrmCustProduct[' + a + '][ccp_remark]"   ></td>' +
            '<td><a onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'product_table'" + ')">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse($("#product_table").find("tr:last"));
        a++;
    }
    function add_customer() {
        var a = $("#customer_table tr").length;
        var b = a;
        b += 1; //var obj =
        $("#customer_table").append(
            '<tr>' +
            '<td>' + b + '</td>' +
            '<td><input class="width-170 no-border text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_name]" placeholder="" maxlength="20"></td>' +
            '<td>' +
            '<select class="value-width value-align" name="CrmCustCustomer['+ a +'][cc_customer_type]">'+
            '<option value="">请选择...</option>'+
            <?php foreach ($downList['managementType'] as $k => $v) { ?>
            '<option value="<?= $v['bsp_id'] ?>"><?= $v['bsp_svalue'] ?></option>'+
            <?php } ?>
            '</select>' +
            '</td>' +
            '<td><input class="width-80 no-border text-center" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_person]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-80 no-border text-center easyui-validatebox" data-options="validType:\'mobile\'" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_mobile]" id="cc_customer_mobile_' + b + '" placeholder="请输入:138 xxxx xxxx" maxlength="20" onfocus="onfocustishi(this.placeholder,\'请输入:138 xxxx xxxx\',\'cc_customer_mobile_' + b + '\')" onblur="blurtishi(this.placeholder,\'请输入:138 xxxx xxxx\',\'cc_customer_mobile_' + b + '\')"></td>' +
            '<td><input class="width-100 no-border text-center easyui-validatebox" data-options="validType:\'telphone\'" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_tel]" id="cc_customer_tel_' + b + '" placeholder="请输入:xxxx-xxxxxxxx" maxlength="20" onfocus="onfocustishi(this.placeholder,\'请输入:xxxx-xxxxxxxx\',\'cc_customer_tel_' + b + '\')" onblur="blurtishi(this.placeholder,\'请输入:xxxx-xxxxxxxx\',\'cc_customer_tel_' + b + '\')"></td>' +
            '<td><input class="width-50 no-border text-center" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_ratio]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_remark]" placeholder="" maxlength="50"></td>' +
            '<td><a onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'customer_table'" + ')" style="margin-left: 20px; ;">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse($("#customer_table"));
        a++;
    }
    function add_reg() {
        var a = $(".reg_sum").length;
        ++a;
        $("#reg").append(
            '<div class="mb-10 reg_sum">' +
            '<label class="label-width label-align" for="">登记证名称' + a + '：</label>' +
            ' <input class="value-width value-align" type="text"  name="CrmCustomerInfo[cust_regname][]" maxlength="20">' +
            ' <label class="label-width label-align" for="">登记证号码' + a + '：</label>' +
            ' <input class="value-width value-align easyui-validatebox" data-options="validType:\'regCard\'" type="text"  name="CrmCustomerInfo[cust_regnumber][]" maxlength="50">' +
            '&nbsp;<a class="icon-minus" onclick="del_reg()"> 删除</a>' +
            '</div>'
        );
        if ($("#del").length == 0) {
            $("#add").after('<a id="del" onclick="del_reg()"> 删除登记证</a>')
        }
    }
    function del_reg() {
        var a = $(".reg_sum").length;
        if (a > 1) {
            $("#reg .reg_sum:last").remove();
        }
        if (a == 2) {
            $("#del").remove();
        }
    }

    function reset(obj) {
        var td = $(obj).parents("tr").find("td");
        $(obj).parents("tr").find("input").val("");
    }
    function vacc_del(obj, id) {
        $(obj).parents("tr").remove();
//        console.log($(obj).parents("tr").find('td').eq(0).text());
//        alert($("#contacts_table tr").length);
        var a = $("#" + id + " tr").length;
        for (var i = 0; i < a; i++) {
            $('#' + id).find('tr').eq(i).find('td:first').text(i + 1);
        }
    }
    $(function () {
        $("#custArea").on("change", function () {
            var id = $(this).val();
            $('#custSalearea').html('<option value="">请选择...</option>');
            $.ajax({
                type: "get",
                dataType: "json",
                data: {"id": id},
                url: "<?= Url::to(['/crm/crm-customer-info/get-district-salearea'])?>",
                success: function (msg) {
//                    $("#custSalearea").val(msg.csarea_id);
                    for (var $i = 0; $i < msg.length; $i++) {
//                        console.log(msg.length);
//                        return false;
                        $("#custSalearea").append('<option value="' + msg[$i].csarea_id + '">' + msg[$i].csarea_name + '</option>')
                    }
                },
            })
        })
        /**
         * 验证客户名称唯一性
         */
        <?php if (!\Yii::$app->controller->action->id == "create") { ?>
        $("#cust_sname").blur(function () {
            $("#cust_sname").validatebox({
                required: true,
                delay: 700,
                validType: "remote['<?=\yii\helpers\Url::to(['/crm/crm-member/cust-validation'])?>?name=" + $("#cust_sname").val() + "','code']",
                invalidMessage: '客户已存在',
                missingMessage: '客户不能为空'
            })
        })
        $("#cust_shortname").blur(function () {
            $("#cust_shortname").validatebox({
                required: true,
                delay: 700,
                validType: "remote['<?=\yii\helpers\Url::to(['/crm/crm-member/cust-validation'])?>?name=" + $("#cust_shortname").val() + "','code']",
                invalidMessage: '客户简称已存在',
                missingMessage: '客户简称不能为空'
            })
        })
        <?php } ?>
    })
    //    $(document).on('click', '.select_date', function () {
    //        jeDate({
    //            dateCell: this,
    //            zIndex: 8831,
    //            format: "YYYY-MM-DD",
    //            skinCell: "jedatedeep",
    //            isTime: false,
    //            okfun: function (elem) {
    //                $(elem).change();
    //            },
    //            //点击日期后的回调, elem当前输入框ID, val当前选择的值
    //            choosefun: function (elem) {
    //                $(elem).change();
    //            }
    //        })
    //    })
    $(function () {
        $('#cust_ismember').change(function () {
            var n = $('#cust_ismember').val();
            if (n == 1) {
                $('#member_type').attr('disabled', false);
            } else {
                $('#member_type').attr('disabled', true);
            }
        })
        $("#select_manage").fancybox({
            padding: [],
            fitToView: false,
            width: 800,
            height: 350,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
    })
</script>
