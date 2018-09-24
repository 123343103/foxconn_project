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
        width: 120px;
    }

    .value-width {
        width: 200px;
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
    <input type="hidden" name="CrmCustomerInfo[cust_id]" value="<?=$model['cust_id']?>">
    <input type="hidden" name="statusApply" value="" id="statusApply">
    <input type="hidden" name="CrmCustomerApply[status]" value="<?= $caModel['status'] ?>">
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width  label-align">客户全称：</label>
            <span class="value-width value-align" name="CrmCustomerInfo[cust_sname]"
                  id="cust_sname"><?= $model['cust_sname'] ?></span>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">客户简称：</label>
            <span class="value-width value-align" id="cust_shortname"
                  name="CrmCustomerInfo[cust_shortname]"><?= $model['cust_shortname'] ?></span>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>公司电话：</label>
            <input class="value-width value-align easyui-validatebox" id="cust_tel1" maxlength="20"
                   name="CrmCustomerInfo[cust_tel1]" data-options="required:'true',validType:'telphone'"
                   placeholder="请输入:xxxx-xxxxxxx"
                   onfocus="onfocustishi(this.placeholder,'请输入:xxxx-xxxxxxx',this.id)"
                   onblur="blurtishi(this.value,'请输入:xxxx-xxxxxxx',this.id)"
                   value="<?= $model['cust_tel1'] ?>">
        </div>
        <div class="inline-block">
            <label class="label-width label-align" for="">传真：</label>
            <input class="value-width value-align easyui-validatebox" id="cust_fax" type="text" name="CrmCustomerInfo[cust_fax]"
                   value="<?= $model['cust_fax'] ?>" data-options="validType:'telphone'"
                   placeholder="请输入:xxxx-xxxxxxx"
                   onfocus="onfocustishi(this.placeholder,'请输入:xxxx-xxxxxxx',this.id)"
                   onblur="blurtishi(this.value,'请输入:xxxx-xxxxxxx',this.id)"
                   maxlength="20">
        </div>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">客户类型：</label>
        <span class="value-width value-align" id="cust_type"
              name="CrmCustomerInfo[cust_type]"><?= $model["custType"] ?></span>
        <label class="label-width label-align">客户来源：</label>
        <span class="value-width value-align" id="cust_type"
              name="CrmCustomerInfo[member_source]"><?= $model["custSource"] ?></span>
    </div>

    <div class="mb-10">
        <label class="label-width label-align">所在地区：</label>
        <span class="value-width value-align" id="custArea"
              name="CrmCustomerInfo[cust_area]"><?= $model["area"] ?></span>
        <label class="label-width label-align">营销区域：</label>
        <span class="value-width value-align" id="custSalearea"
              name="CrmCustomerInfo[cust_salearea]"><?= $model["saleArea"] ?></span>
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
        <input class="value-width value-align easyui-validatebox" id="cust_email" type="text" name="CrmCustomerInfo[cust_email]"
               value="<?= $model['cust_email'] ?>" placeholder="请输入:xxx@xx.com" data-options="validType:'email'"
               onfocus="onfocustishi(this.placeholder,'请输入:xxx@xx.com',this.id)"
               onblur="blurtishi(this.value,'请输入:xxx@xx.com',this.id)"
               maxlength="100">
        <input type="hidden" name="CrmCustomerInfo[cust_manager]" class="staff_id"
               value="<?= $model['personinch']['staffId'] ?>">
        <label class="label-width label-align">客户经理人：</label>
        <span class="value-width value-align"
              name="code"><?=$model['manager_code']?>
        </span>
        <!--        <span class="width-50 staff_name">--><? //= $model['personinch']['manager'] ?><!--</span>-->
    </div>

    <div class="mb-10">
        <label class="label-width label-align">详细地址：</label>
        <span class="value-width value-align"  style="vertical-align: middle"><?= $model["districts"] ?></span>
    </div>
</div>
<h2 class="head-three">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">客户详情信息</a>
</h2>
<div>
    <div class="mb-10">
        <label class="label-width label-align" for="">公司属性：</label>
        <span class="value-width value-align" id="cust_compvirtue"
              name="CrmCustomerInfo[cust_compvirtue]"><?= $model["compvirtue"] ?></span>
        <label class="label-width label-align" ">公司规模：</label>
        <span class="value-width value-align" id="cust_compscale"
              name="CrmCustomerInfo[cust_compscale]"><?= $model["bsPubdata"]["compscale"] ?></span>
        <label class="label-width label-align" for="">行业类别：</label>
        <span class="value-width value-align" id="cust_industrytype"
              name="CrmCustomerInfo[cust_industrytype]"><?= $model["industryType"] ?></span>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">客户等级：</label>
        <span class="value-width value-align" id="cust_level"
              name="CrmCustomerInfo[cust_level]"><?= $model["custLevel"] ?></span>
        <label class="label-width label-align" for="">经营类型：</label>
        <span class="value-width value-align" id="cust_businesstype"
              name="CrmCustomerInfo[cust_businesstype]"><?= $model["businessType"] ?></span>
        <label class="label-width label-align" for="">员工人数：</label>
        <span class="value-width value-align" id="cust_personqty"
              name="CrmCustomerInfo[cust_personqty]"><?= $model["cust_personqty"] ?></span>
    </div>
    <div class="mb-10">
        <label class="label-width label-align" for="">注册时间：</label>
        <span class="value-width value-align" id="cust_regdate"
              name="CrmCustomerInfo[cust_regdate]"><?= $model["cust_regdate"] ?></span>
        <label class="label-width label-align" for="">注册资金：</label>
        <span class="value-width value-align" id="cust_regfunds"
              name="CrmCustomerInfo[cust_regfunds]"><?= $model["cust_regfunds"] ?></span>
        <label class="label-width label-align" for="">注册货币：</label>
        <span class="value-width value-align" id="member_curr"
              name="CrmCustomerInfo[member_regcurr]"><?= $model["regCurrency"] ?></span>
        <div class="mb-10">
            <label class="label-width label-align" for="">是否上市公司：</label>
            <span class="value-width value-align"
                  name="CrmCustomerInfo[cust_islisted]"><?php if ($model['cust_islisted'] == 1) echo "是"; else echo "否" ?></span>
            <label class="label-width label-align" for="">是否公司会员：</label>
            <span class="value-width value-align"
                  name="CrmCustomerInfo[cust_ismember]"><?php if ($model['cust_ismember'] == 1) echo "是"; else echo "否" ?></span>
            <label class="label-width label-align" for="">会员类型：</label>
            <span class="value-width value-align"><?= $model['memberType'] ?></span>
        </div>

        <div class="mb-10">
            <label class="label-width label-align">潜在需求：</label>
            <span class="value-width value-align" id="member_reqflag"
                  name="CrmCustomerInfo[member_reqflag]"><?= $model["latDemand"] ?></span>
            <label class="label-width label-align">需求类目：</label>
            <span class="value-width value-align" id="member_reqitemclass"
                  name="CrmCustomerInfo[member_reqitemclass]"><?= $model["productType"] ?></span>
            <label class="label-width label-align" for="">旺季分布：</label>
            <span class="value-width value-align" id="cust_bigseason"
                  name="CrmCustomerInfo[cust_bigseason]"><?= $model["cust_bigseason"] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">交易币别：</label>
            <span class="value-width value-align" id="member_curr"
                  name="CrmCustomerInfo[member_curr]"><?= $model['bsPubdata']["memberCurr"] ?></span>
            <label class=" label-align label-width" for="">年营业额：</label>
            <span class="value-width value-align" id="member_compsum"
                  name="CrmCustomerInfo[member_compsum]"><?= $model['member_compsum'] ?></span>
            <label class="label-width label-align" for="">公司主页：</label>
            <span class="value-width value-align" id="member_compwebside"
                  name="CrmCustomerInfo[member_compwebside]"><?= $model['member_compwebside'] ?></span>
        </div>
        <div id="reg">
            <?php if (!empty($regname) && !empty($regnumber)) { ?>
                <?php foreach ($regname as $key => $val) { ?>
                    <div class="mb-10 reg_sum">
                        <label class="label-width label-align" for="">登记证名称<?php echo $key + 1; ?>：</label>
                        <span class="value-width value-align"
                              name="CrmCustomerInfo[cust_regname][]"><?= $val ?></span>
                        <label class="label-width label-align" for="">登记证号码<?php echo $key + 1; ?>：</label>
                        <span class="value-width value-align"
                              name="CrmCustomerInfo[cust_regnumber][]"><?= $regnumber[$key] ?></span>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="mb-10 reg_sum">
                    <label class="label-width label-align" for="">登记证名称：</label>
                    <span class="value-width value-align"
                          name="CrmCustomerInfo[cust_regname][]"></span>
                    <label class="label-width label-align" for="">登记证号码：</label>
                    <span class="value-width value-align"
                          name="CrmCustomerInfo[cust_regnumber][]"></span>
                    <!--               class="icon-plus" class="icon-minus"-->
                </div>
            <?php } ?>
        </div>
        <div class="mb-10">
            <label class="label-width label-align" for="">申请的发票类型：</label>
            <span class="value-width value-align" id=""
                  name="CrmCustomerInfo[invoice_type]"><?= $model['invoiceType'] ?></span>
            <label class="label-width label-align" for="">具备开票类型：</label>
            <span class="value-width value-align" id=""
                  name="CrmCustomerInfo[invo_type]"><?= $model['invoType'] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width label-align" for="">发票抬头：</label>
            <span class=" value-align" id="invoicehead" style="width: 500px;vertical-align: middle;"
                  name="CrmCustomerInfo[invoice_title]"><?= $model['cust_sname'] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">发票抬头地址：</label>
            <span class=" value-align" id="invoicehead" style="width: 600px;vertical-align: middle;"
                  name="CrmCustomerInfo[invoice-titleDistrict]"><?= $model['invoiceTitleDistrict'][0]["district_name"] ?>
                <?= $model['invoiceTitleDistrict'][1]["district_name"] ?><?= $model['invoiceTitleDistrict'][2]["district_name"] ?><?= $model['invoiceTitleDistrict'][3]["district_name"] ?><?= $model['cust_headquarters_address'] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">发票寄送地址：</label>
            <span class=" value-align" id="" style="width: 600px;vertical-align:middle;"
                  name="CrmCustomerInfo[invoice-mailDistrict]"><?= $model['invoiceMailDistrict'][0]["district_name"] ?>
                <?= $model['invoiceMailDistrict'][1]["district_name"] ?><?= $model['invoiceMailDistrict'][2]["district_name"] ?><?= $model['invoiceMailDistrict'][3]["district_name"] ?><?= $model['cust_headquarters_address'] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">经营范围说明：</label>
            <span class=" value-align" id="member_compsum" style="width: 600px;vertical-align: middle;"
                  name="CrmCustomerInfo[member_businessarea]"><?= $model['member_businessarea'] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">总公司：</label>
            <span class=" value-align value-width"
                  name="CrmCustomerInfo[cust_parentcomp]"><?= $model['cust_parentcomp'] ?></span>
            <label class="label-width label-align">公司负责人：</label>
            <span class=" value-align value-width"
                  name="CrmCustomerInfo[cust_inchargeperson]"><?= $model['cust_inchargeperson'] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">总公司地址：</label>
            <span class=" value-align" id="" style="width: 600px;vertical-align: middle"
                  name="CrmCustomerInfo[distric_company]"><?= $model['districtCompany'][0]["district_name"] ?>
                <?= $model['districtCompany'][1]["district_name"] ?><?= $model['districtCompany'][2]["district_name"] ?><?= $model['districtCompany'][3]["district_name"] ?><?= $model['cust_headquarters_address'] ?></span>
        </div>
    </div>
</div>

<h2 class="head-three">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">认证信息</a>
</h2>
<div>
    <div class="mb-10">
        <input type="hidden" id="ynspp" value="<?= $crmcertf['YN_SPP'] ?>">
        <label class=" label-align" for="" style="width: 161px;">是否供应商：</label>
        <span class="value-width value-align"><?php if ($crmcertf['YN_SPP'] == 1) echo "是"; else echo "否"; ?></span>
        <span style="display: none;" id="custcode">
            <label class="lable-width label-align " for=""><span class="red">*</span>供应商代码：</label>
                <input class=" value-align" id="sppno"
                       style="width: 240px;"
                       data-options="required:'true'"
                       type="text" name="CrmC[SPP_NO]"
                       value="<?= $crmcertf['SPP_NO'] ?>" maxlength="120">
        </span>
    </div>
    <div class="mb-10">
        <input type="hidden" id="crtftype" value="<?= $crmcertf['crtf_type'] ?>">
        <label class=" label-align" for="" style="width: 161px;">证件类型：</label>
        <span class=" value-align"
              style="width: 100px;"><?php if ($crmcertf['crtf_type'] == 1) echo "新版三证合一"; else echo "旧版三证"; ?></span>
        <span style="color: #989898">证件格式：JPG、PNG、TIF，PDF、BMP、压缩档7z、rar、zip等文件小于2M。</span>
    </div>
    <div class="mb-10">
        <label >税籍编码/统一社会信用代码：</label>
        <span class="value-width value-align" id="taxcode"
              name="CrmCustomerInfo[cust_tax_code]"><?= $model['cust_tax_code'] ?></span>
    </div>
    <div id="oldthreecer">
        <div class="mb-10">
            <label
                class=" label-align" style="width: 161px;"
                id="business">公司营业执照证：</label>
            <span class=" value-align" style="width: 500px;"><a href="<?=Yii::$app->ftpPath['httpIP']?>/cmp/bslcns/<?=$newnName1?>/<?=$crmcertf['bs_license']?>"><?= $crmcertf['o_license'] ?></a></span>
        </div>
        <div class="mb-10" id="tax">
            <label class=" label-align" style="width: 161px;">税务登记证：</label>
            <span class=" value-align" style="width: 500px;"><a href="<?=Yii::$app->ftpPath['httpIP']?>./cmp/txrg/<?=$newnName2?>/<?=$crmcertf['tx_reg']?>"><?= $crmcertf['o_reg'] ?></a></span>
        </div>
    </div>
    <div class="mb-10">
        <label class=" label-align" style="width: 161px;">一般纳税人资格证：</label>
        <span class=" value-align" style="width: 500px;"><a href="<?=Yii::$app->ftpPath['httpIP']?>./cmp/txqlf/<?=$newnName3?>/<?=$crmcertf['qlf_certf']?>"><?= $crmcertf['o_cerft'] ?></a></span>
    </div>
    <div class="mb-10">
        <label class=" label-align vertical-top" style="width: 161px;">备注：</label>
        <span class="value-align" style="width: 600px;vertical-align: middle" name="CrmC[marks]" id="remark"><?= $crmcertf['marks'] ?></span>
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
                                   value="<?= Html::encode($val['ccper_name']) ?>" maxlength="20">
                        </td>
                        <td>
                            <select name="CrmCustomerPersion[<?= $key ?>][ccper_sex]" id="">
                                <option value="1" <?= $val['ccper_sex'] == '1' ? "selected" : null; ?>>--男--</option>
                                <option value="0" <?= $val['ccper_sex'] == '0' ? "selected" : null; ?>>--女--</option>
                            </select>
                        </td>
                        <td>
                            <input class="width-70 no-border text-center  easyui-validatebox"
                                   data-options="required:'true',validType:'length[0,20]'"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_post]"
                                   value="<?= Html::encode($val['ccper_post']) ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:'length[0,50]'" type="text"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_deparment]"
                                   value="<?= Html::encode($val['ccper_deparment']) ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" type="text" id="ccper_tel"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_tel]" data-options="validType:'telphone'"
                                   placeholder="请输入:xxxx-xxxxxxx"
                                   onfocus="onfocustishi(this.placeholder,'请输入:xxxx-xxxxxxx',this.id)"
                                   onblur="blurtishi(this.value,'请输入:xxxx-xxxxxxx',this.id)"
                                   value="<?= Html::encode($val['ccper_tel']) ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" type="text"
                                   data-options="required:'true'" id="ccper_mobile"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_mobile]" placeholder="请输入:138xxxxxxxx"
                                   onfocus="onfocustishi(this.placeholder,'请输入:138xxxxxxxx',this.id)"
                                   onblur="blurtishi(this.value,'请输入:138xxxxxxxx',this.id)"
                                   value="<?= Html::encode($val['ccper_mobile']) ?>" maxlength="20"></td>
                        <td><input class=" no-border text-center Wdate " type="text" style="width: 150px;"
                                   onclick="WdatePicker({ onpicked: function () { }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd HH:mm' })"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_birthday]"
                                   id="ccper_birthday"
                                   value="<?= empty($val['ccper_birthday']) ? '' : date('Y-m-d', strtotime($val['ccper_birthday'])) ?>"
                                   readonly="readonly"></td>
                        <td><input class="width-70 no-border text-center  easyui-validatebox" type="text" id="ccper_fax"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_fax]" placeholder="请输入:xxxx-xxxxxxx" data-options="validType:'telphone'"
                                   onfocus="onfocustishi(this.placeholder,'请输入:xxxx-xxxxxxx',this.id)"
                                   onblur="blurtishi(this.value,'请输入:xxxx-xxxxxxx',this.id)"
                                   value="<?= Html::encode($val['ccper_fax']) ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" type="text" id="ccper_mail"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_mail]" data-options="validType:'email'"
                                   placeholder="请输入:xxx@xx.com"
                                   onfocus="onfocustishi(this.placeholder,'请输入:xxx@xx.com',this.id)"
                                   onblur="blurtishi(this.value,'请输入:xxx@xx.com',this.id)"
                                   value="<?= Html::encode($val['ccper_mail']) ?>"
                                   maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:'qq'" type="text"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_qq]"
                                   value="<?= Html::encode($val['ccper_qq']) ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:'length[0,50]'" type="text"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_wechat]"
                                   value="<?= Html::encode($val['ccper_wechat']) ?>" maxlength="20"></td>
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
<div>
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
                                   value="<?= Html::encode($val['type']) ?>"></td>
                        <td><input class="width-200 no-border text-center" type="text"
                                   name="CrmCustDevice[<?= $key ?>][brand]" maxlength="20"
                                   value="<?= Html::encode($val['brand']) ?>"></td>
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
                        <td style="width:50px;"><?= $key + 1 ?></td>
                        <td><input class="width-100 no-border text-center easyui-validatebox" type="text"
                                   data-options="required:'true'"
                                   name="CrmCustProduct[<?= $key ?>][ccp_sname]" maxlength="20"
                                   value="<?= Html::encode($val['ccp_sname']) ?>"></td>
                        <td><input class="width-100 no-border text-center" type="text"
                                   name="CrmCustProduct[<?= $key ?>][ccp_model]" maxlength="20"
                                   value="<?= Html::encode($val['ccp_model']) ?>"></td>
                        <td><input class="width-100 no-border text-center" type="text"
                                   name="CrmCustProduct[<?= $key ?>][ccp_annual]" maxlength="20"
                                   value="<?= Html::encode($val['ccp_annual']) ?>"></td>
                        <td><input class="width-100 no-border text-center" type="text"
                                   name="CrmCustProduct[<?= $key ?>][ccp_brand]" maxlength="20"
                                   value="<?= Html::encode($val['ccp_brand']) ?>"></td>
                        <td><input class="width-100 no-border text-center" type="text"
                                   name="CrmCustProduct[<?= $key ?>][ccp_remark]" maxlength="200"
                                   value="<?= Html::encode($val['ccp_remark']) ?>"></td>
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
        <table class="table" style="width:2000px;">
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
                        <td><?= $key + 1 ?></td>
                        <td><input class=" no-border text-center easyui-validatebox" type="text" style="width: 60px"
                                   data-options="required:'true'"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_name]"
                                   maxlength="20" value="<?= Html::encode($val['cc_customer_name']) ?>"></td>
                        <td><select name="CrmCustCustomer[<?= $key ?>][cc_customer_type]" style="width: 180px;">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['managementType'] as $k => $v) { ?>
                                    <option value="<?= $v['bsp_id'] ?>" <?= $val['cc_customer_type'] == $v['bsp_id'] ? "selected" : null; ?>><?= $v['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                        <td><input class="width-80 no-border text-center" type="text"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_person]"
                                   maxlength="20" value="<?= Html::encode($val['cc_customer_person']) ?>"></td>
                        <td><input class="width-80 no-border text-center easyui-validatebox" type="text"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_mobile]" data-options="validType:'mobile'"
                                   placeholder="请输入:138 xxxx xxxx"
                                   onfocus="onfocustishi(this.placeholder,'请输入:138 xxxx xxxx',this.id)"
                                   onblur="blurtishi(this.value,'请输入:138 xxxx xxxx',this.id)"
                                   maxlength="20" value="<?= Html::encode($val['cc_customer_mobile']) ?>"></td>
                        <td><input class="width-100 no-border text-center easyui-validatebox" type="text"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_tel]" data-options="validType:'telphone'"
                                   placeholder="请输入:xxxx-xxxxxxxx"
                                   onfocus="onfocustishi(this.placeholder,'请输入:xxxx-xxxxxxxx',this.id)"
                                   onblur="blurtishi(this.value,'请输入:xxxx-xxxxxxxx',this.id)"
                                   maxlength="20" value="<?= Html::encode($val['cc_customer_tel']) ?>"></td>
                        <td><input class="width-50 no-border text-center" type="text"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_ratio]"
                                   value="<?= Html::encode($val['cc_customer_ratio']) ?>" maxlength="20"></td>
                        <td><input class="width-100 no-border text-center" type="text"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_remark]"
                                   maxlength="200" value="<?= Html::encode($val['cc_customer_remark']) ?>"></td>
                        <td><span style="width: 100px;"><a onclick="reset(this)">重置</a> <a
                                    onclick="vacc_del(this,'customer_table')" style="margin-left: 20px;">删除</a></span>
                        </td>
                    </tr>
                <?php } ?>
            <?php }?>
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
    <button id="save-form" class="button-blue-big" type="submit">保存</button>
    <button onclick="history.go(-1);" type="button" class="button-white-big" >取消</button>
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
            $(id).attr("placeholder", "");
        }
    }
    function blurtishi(val, title, id) {
        if (val == "") {
            $(id).attr("placeholder", title);
        }
    }
    function change(obj) {
        var length = obj.files.length;
        //var span = obj.parentNode.previousSibling.previousSibling;
        var temp = "";
        for (var i = 0; i < obj.files.length; i++) {
            if (i == 0) {
                temp = obj.files[i].name;
            } else {
                temp = temp + "&nbsp,&nbsp" + obj.files[i].name;
            }
            return temp;
//            $("#LICENSE_NAME").val(temp);
        }
    }
    //三证合一和营业执照文件名
    function license(obj) {
        $("#license_name").val(change(obj)).validatebox("enableValidation");
        $("#upfiles-lic").val(change(obj)).validatebox("enableValidation");
    }
    //税务登记证文件名
    function reg(obj) {
        $("#tax_name").val(change(obj)).validatebox("enableValidation");
        $("#upfiles-tax").val(change(obj));
    }
    //一般纳税人资格证文件名
    function cerft(obj) {
        $("#org_name").val(change(obj)).validatebox("enableValidation");
        $("#upfiles-org").val(change(obj));
    }
    var radyes = $("#ynspp").val();
    if (radyes == 1) {
        $("#custcode").show();
    }
    $("#sppno").mouseover(function () {
        $("#sppno").attr('class', 'easyui-validatebox validatebox-text validatebox-invalid tooltip-f');
    });
    $("#sppno").mouseout(function () {
        $("#sppno").attr('class', 'easyui-validatebox validatebox-text validatebox-invalid');
    });
    $("#sppno").focus(function () {
        $("#sppno").attr('class', 'easyui-validatebox validatebox-text validatebox-invalid tooltip-f');
    });
    $("#sppno").blur(function () {
        $("#sppno").attr('class', 'easyui-validatebox validatebox-text validatebox-invalid');
    });
    var crtftype = $("#crtftype").val();
    if (crtftype == 1) {
        $("#business").text("公司三证合一证：");
        $("#tax").hide();
    }
    else {
        $("#business").text("公司营业执照证：");
    }
    function setStaffInfo(obj) {
        var url = "<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>";
        staffInfo(obj, url);
    }
    $(function () {
        ajaxSubmitForm($("#add-form"));
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
        //保存
        $("#save-form").click(function () {
            $("#statusApply").val('10');
            $("#add-form").attr('action', '<?= \yii\helpers\Url::to(['/crm/crm-customer-apply/save-cust-info']) ?>');
        });
//        ajaxSubmitForm($("#add-form"));//crmtomer-info
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
            '<td><input class="width-200 no-border text-center" type="text"  name="CrmCustDevice[' + a + '][type]"></td>' +
            '<td><input class="width-200 no-border text-center" type="text"  name="CrmCustDevice[' + a + '][brand]"></td>' +
            '<td><a onclick="reset(this)">重置</a>  <a style="margin-left: 15px" onclick="vacc_del(this,' + "'equipment_table'" + ')">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse($("#equipment_table"));
        a++;
    }
    function add_product() {
        var a = $("#product_table tr").length;
        var b = a;
        b += 1;
        $("#product_table").append(
            '<tr>' +
            '<td>' + b + '</td>' +
            '<td><input class="width-100 no-border text-center easyui-validatebox" data-options="required:\'true\'" type="text"  name="CrmCustProduct[' + a + '][ccp_sname]"></td>' +
            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustProduct[' + a + '][ccp_model]"></td>' +
            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustProduct[' + a + '][ccp_annual]"></td>' +
            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustProduct[' + a + '][ccp_brand]"></td>' +
            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustProduct[' + a + '][ccp_remark]"></td>' +
            '<td><a onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'product_table'" + ')">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse($("#product_table"));
        a++;
    }
    function add_customer() {
        var a = $("#customer_table tr").length;
        var b = a;
        b += 1;
        $("#customer_table").append(
            '<tr>' +
            '<td>' + b + '</td>' +
            '<td><input class="width-170 no-border text-center easyui-validatebox" type="text" data-options="required:\'true\'" name="CrmCustCustomer[' + a + '][cc_customer_name]" ></td>' +
            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_type]" ></td>' +
            '<td><input class="width-80 no-border text-center" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_person]" ></td>' +
            '<td><input class="width-80 no-border text-center easyui-validatebox" type="text"  data-options="validType:\'mobile\'" name="CrmCustCustomer[' + a + '][cc_customer_mobile]" id="cc_customer_mobile_' + b + '" placeholder="请输入:138 xxxx xxxx" onfocus="onfocustishi(this.placeholder,\'请输入:138 xxxx xxxx\',\'#cc_customer_mobile_' + b + '\')" onblur="blurtishi(this.placeholder,\'请输入:138 xxxx xxxx\',\'#cc_customer_mobile_' + b + '\')"></td>' +
            '<td><input class="width-100 no-border text-center easyui-validatebox" type="text"  data-options="validType:\'telphone\'" name="CrmCustCustomer[' + a + '][cc_customer_tel]" id="cc_customer_tel_' + b + '" placeholder="请输入:xxxx-xxxxxxxx" onfocus="onfocustishi(this.placeholder,\'请输入:xxxx-xxxxxxxx\',\'#cc_customer_tel_' + b + '\')" onblur="blurtishi(this.value,\'请输入:xxxx-xxxxxxxx\',\'#cc_customer_tel_' + b + '\')"></td>' +
            '<td><input class="width-50 no-border text-center" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_ratio]" ></td>' +
            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_remark]"></td>' +
            '<td><a onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'customer_table'" + ')" style="margin-left: 20px;">删除</a></td>' +
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
            '<label class="label-width label-align" for="">登记证名称' + a + '</label>' +
            '<input class="value-width value-align" style="margin-left: 4px;" type="text"  name="CrmCustomerInfo[cust_regname][]">' +
            '<label class="label-width label-align" style="margin-left: 4px;" for="">登记证号码' + a + '</label>' +
            '<input class="value-width value-align" style="margin-left: 4px;" type="text"  name="CrmCustomerInfo[cust_regnumber][]">' +
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
