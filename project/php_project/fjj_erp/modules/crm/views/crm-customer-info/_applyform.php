<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$regname = unserialize(Html::decode($model['cust_regname']));
$regnumber = unserialize(Html::decode($model['cust_regnumber']));
\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\CrmCustomerInfo */
$this->title = '修改客户信息';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '客户列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '修改客户信息'];

$action=\Yii::$app->controller->action->id;
?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
        <span class="head-code">客户编号：<?= $model['cust_filernumber'] ?></span>
    </h1>
<style>
    .label-width{
        width:140px;
    }
    .value-width{
        width:200px;
    }
</style>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<h2 class="head-second color-1f7ed0">
    <i class="icon-caret-down"></i>
    <a href="javascript:void(0)">客户基本信息</a>
</h2>

<div class="ml-10 mb-10">
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>客户全称：</label>
            <span class="value-width value-align"><?= $model['cust_sname'] ?></span>
        </div>
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>客户简称：</label>
            <span class="value-width value-align"><?= $model['cust_shortname'] ?></span>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align" for="">公司电话：</label>
            <input class="value-width value-align easyui-validatebox" data-options="validType:'telphone'" type="text" name="CrmCustomerInfo[cust_tel1]" value="<?= $model['cust_tel1'] ?>" maxlength="15" placeholder="请输入：xxxx-xxxxxx">
        </div>
        <div class="inline-block">
            <label class="label-width label-align" for="">传真：</label>
            <input class="value-width value-align easyui-validatebox" data-options="validType:'fax'" type="text" name="CrmCustomerInfo[cust_fax]" value="<?= $model['cust_fax'] ?>" maxlength="15" placeholder="请输入：xxxx-xxxxxx">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>客户类型：</label>
            <span class="value-width value-align">
                <?php foreach ($downList["customerType"] as $key => $val) { ?>
                    <?=$val["bsp_id"]==$model["cust_type"]?$val["bsp_svalue"]:""?>
                <?php } ?>
            </span>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">客户来源：</label>
            <span class="value-width value-align">
                <?php foreach ($downList["customerSource"] as $key => $val) { ?>
                    <?=$val["bsp_id"]==$model["member_source"]?$val["bsp_svalue"]:""?>
                <?php } ?>
            </span>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>所在地区：</label>
            <span class="value-width value-align">
                <?php foreach ($district as $key => $val) { ?>
                    <?=$val["district_id"]==$model["cust_area"]?$val["district_name"]:""?>
                <?php } ?>
            </span>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">营销区域：</label>
            <span class="value-width value-align">
                <?php foreach ($downList["salearea"] as $key => $val) { ?>
                    <?=$val["csarea_id"]==$model["cust_salearea"]?$val["csarea_name"]:""?>
                <?php } ?>
            </span>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>联系人：</label>
            <input class="value-width value-align easyui-validatebox" maxlength="20" name="CrmCustomerInfo[cust_contacts]" data-options="required:'true'" value="<?= $model['cust_contacts'] ?>" >
        </div>
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>联系电话：</label>
            <input class="value-width value-align easyui-validatebox" type="text"
                   name="CrmCustomerInfo[cust_tel2]" data-options="required:'true',validType:'tel_mobile'"
                   value="<?= $model['cust_tel2'] ?>" placeholder="请输入:138xxxxxxxx">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align">邮箱：</label>
            <input class="value-width value-align easyui-validatebox" data-options="validType:'email'" type="text" name="CrmCustomerInfo[cust_email]" value="<?= $model['cust_email'] ?>" maxlength="30" id="cust_email" placeholder="请输入:xxxx@xx.com">
        </div>
        <div class="inline-block">
            <input type="hidden" name="CrmCustomerInfo[cust_manager]" class="staff_id"
                   value="<?= $model['manager_cid'] ?>">
            <label class="label-width label-align">客户经理人：</label>
            <span class="value-width value-align"><?= $model['manager_code']?$model['manager_code']:""; ?></span>
        </div>
    </div>
    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>详细地址：</label>
        <span class="value-align"><?= $model['district'][0]['district_name'] . $model['district'][1]['district_name'] . $model['district'][2]['district_name'] . $model['district'][3]['district_name'] . $model['cust_adress'] ?></span>
    </div>
</div>

<div class="space-10"></div>

    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">客户详细信息</a>
    </h2>
    <div>
        <table width="90%" class="no-border vertical-center mb-10" style="border-collapse:separate; border-spacing:5px;">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">公司属性<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['comPvirtue'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">公司规模<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['compscale'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">行业类别<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['industryType'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户等级<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['custLevel'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">经营类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['businessType'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">员工人数<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_personqty'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">注册时间<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_regdate'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">注册资金<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= empty($model['cust_regfunds']) ? '' : $model['cust_regfunds'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">注册货币<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['dealCurrency'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">是否上市公司<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_islisted']==1?"是":"否"; ?></td>
                <td class="no-border vertical-center label-align" width="10%">是否公司会员<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_ismember'] == 1?"是":"否" ?></td>
                <td class="no-border vertical-center label-align" width="10%">会员类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['memberType'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">潜在需求<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['latDemand'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">需求类目<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['category_name'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">旺季分布<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_bigseason'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">交易币别<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['bsPubdata']['memberCurr'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">年营业额<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= empty($model['member_compsum'])?'':$model['member_compsum'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">公司主页<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['member_compwebside'] ?></td>
            </tr>
            <?php foreach ($regname as $key => $val) { ?>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="10%">登记证名称<?php echo $key + 1; ?><label>：</label></td>
                    <td class="no-border vertical-center value-align" width="20%"><?= $val ?></td>
                    <td class="no-border vertical-center label-align" width="10%">登记证号码<?php echo $key + 1; ?><label>：</label></td>
                    <td class="no-border vertical-center value-align" colspan="3" width="20%"><?= $regnumber[$key] ?></td>
                </tr>
            <?php } ?>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">申请的发票类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['invoiceType'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">具备开票类型<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3" width="20%"><?= $model['invoType'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">发票抬头<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5" width="20%"><?= $model['invoice_title'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">发票抬头地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5" width="20%"><?= $model['invoiceTitleDistrict'][0]['district_name'] . $model['invoiceTitleDistrict'][1]['district_name'] . $model['invoiceTitleDistrict'][2]['district_name'] . $model['invoiceTitleDistrict'][3]['district_name'] . $model['invoice_title_address'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">发票寄送地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5" width="20%"><?= $model['invoiceMailDistrict'][0]['district_name'] . $model['invoiceMailDistrict'][1]['district_name'] . $model['invoiceMailDistrict'][2]['district_name'] . $model['invoiceMailDistrict'][3]['district_name'] . $model['invoice_mail_address'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">经营范围<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5" width="20%"><?= $model['member_businessarea'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">总公司<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_parentcomp'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">公司负责人<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3" width="20%"><?= $model['cust_inchargeperson'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">总公司地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5" width="20%"><?= $model['districtCompany'][0]['district_name'] . $model['districtCompany'][1]['district_name'] . $model['districtCompany'][2]['district_name'] . $model['districtCompany'][3]['district_name'] . $model['cust_headquarters_address'] ?></td>
            </tr>
        </table>
    </div>

    <div class="space-10"></div>

    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">认证信息</a>
    </h2>
    <div class="display-none">
        <div class="mb-10">
            <div class="inline-block">
                <input type="hidden" value="<?= $crmcertf['yn_spp'] ?>" id="ynspp">
                <label class="label-width label-align vertical-middle" for="">是否供应商：</label>
                <span class="value-width"><?= $crmcertf['yn_spp']==1?"是":"否" ?></span>
            </div>
            <div id="custcode" class="inline-block">
                <label class="width-120 vertical-middle" for="">供应商代码：</label>
                <span class="value-width value-align"><?= $crmcertf['spp_no'] ?></span>
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">证件类型：</label>
                <span class="value-width value-align"><?= $crmcertf['crtf_type']==1?"新版三证合一":"旧版三证" ?></span>
                <span style="color: #989898">证件格式：JPG、PNG、TIF，PDF、BMP、压缩档7z、rar、zip等文件小于2M。</span>
            </div>
        </div>
        <div class="inline-block mb-10">
            <label class="label-align" for="">税籍编码/统一社会信用代码：</label>
            <span class="value-align"><?= $model['cust_tax_code'] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width label-align vertical-middle" id="business">公司营业执照证：</label>
            <input type="hidden" id="httpIp" value="<?= Yii::$app->ftpPath['httpIP'] ?>">
            <input type="hidden" id="cmpname" value="<?= Yii::$app->ftpPath['CMP']['father'] ?>">
            <input type="hidden" id="bslcn" value="<?= Yii::$app->ftpPath['CMP']['BsLcn'] ?>">
            <input type="hidden" id="bslic" value="<?= $crmcertf['bs_license'] ?>">
            <span class="value-width value-align"><a id="license" target="_blank"><?= $crmcertf['o_license'] ?></a></span>
        </div>
        <div class="mb-10" id="tax">
            <label class="label-width label-align vertical-middle">税务登记证：</label>
            <input type="hidden" id="reg" value="<?= Yii::$app->ftpPath['CMP']['TaxReg'] ?>">
            <input type="hidden" id="oregname" value="<?= $crmcertf['tx_reg'] ?>">
            <span class="value-width value-align"><a id="oreg" target="_blank"><?= $crmcertf['o_reg'] ?></a></span>
        </div>
        <div class="mb-20">
            <label class="label-width label-align vertical-middle">一般纳税人资格证：</label>
            <input type="hidden" id="qlf" value="<?= Yii::$app->ftpPath['CMP']['TaxQlf'] ?>">
            <input type="hidden" id="ocerftname" value="<?= $crmcertf['qlf_certf'] ?>">
            <span class="value-width value-align"><a id="cerft" target="_blank"><?= $crmcertf['o_cerft'] ?></a></span>
        </div>
        <div class="mb-20">
            <label class="label-width label-align vertical-top">备注：</label>
            <span class="value-width value-align" style="width: 700px;"><?= $crmcertf['marks'] ?></span>
        </div>
    </div>


    <div class="space-10"></div>

<h2 class="head-second color-1f7ed0">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">主要联系人</a>
</h2>
<div class="mb-10">
    <div style="width: 100%;overflow: auto;">
        <table class="table" style="width: 2000px;">
            <thead>
            <tr>
                <th width="100">序号</th>
                <th width="100"><span class="red">*</span>姓名</th>
                <th width="100"><span class="red">*</span>性别</th>
                <th width="100"><span class="red">*</span>职务</th>
                <th width="100">部门</th>
                <th width="100">座机</th>
                <th width="100"><span class="red">*</span>手机</th>
                <th width="100">生日</th>
                <th width="100">传真</th>
                <th width="100">邮箱</th>
                <th width="100">QQ</th>
                <th width="100">微信</th>
                <th width="100">是否主要联系人</th>
                <th width="100" class="width-60">操作</th>
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
                                   data-options="required:'true'"
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
                                   data-options="required:'true'"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_post]"
                                   value="<?= $val['ccper_post'] ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center" type="text"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_deparment]"
                                   value="<?= $val['ccper_deparment'] ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:'telphone'"  type="text" id="ccper_tel"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_tel]"
                                   placeholder="请输入:xxxx-xxxxxxx"
                                   value="<?= $val['ccper_tel'] ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" type="text"
                                   data-options="required:'true',validType:'mobile'" id="ccper_mobile"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_mobile]" placeholder="请输入:138xxxxxxxx"
                                   value="<?= $val['ccper_mobile'] ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center Wdate"
                                   type="text"
                                   onclick="WdatePicker({ onpicked: function () { }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd'})"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_birthday]"
                                   value="<?= empty($val['ccper_birthday']) ? '' : date('Y-m-d', strtotime($val['ccper_birthday'])) ?>"
                                   readonly="readonly"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" data-options="validType:'fax'"  type="text" id="ccper_fax"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_fax]" placeholder="请输入:xxxx-xxxxxxx"
                                   value="<?= $val['ccper_fax'] ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center" type="text" id="ccper_mail"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_mail]"
                                   placeholder="请输入:xxx@xx.com"
                                   value="<?= $val['ccper_mail'] ?>" data-options="validType:'email'"
                                   maxlength="20"></td>
                        <td><input class="width-70 no-border text-center" type="text"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_qq]"
                                   value="<?= $val['ccper_qq'] ?>" maxlength="20"></td>
                        <td><input class="width-70 no-border text-center" type="text"
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

    <div class="space-10"></div>

    <p class="text-right mt-10">
        <a class="icon-plus" onclick="add_contacts()"> 添加联系人</a>
    </p>
</div>

    <div class="space-10"></div>

<h2 class="head-second color-1f7ed0">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">设备信息</a>
</h2>
<div class="mb-10">
    <table class="table">
        <thead>
        <tr>
            <th>序号</th>
            <th><span class="red">*</span>设备类型</th>
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

    <div class="space-10"></div>

    <p class="text-right mt-10">
        <a class="icon-plus" onclick="add_equipment()">添加设备</a>
    </p>
</div>

    <div class="space-10"></div>

<h2 class="head-second color-1f7ed0">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">主营产品</a>
</h2>
<div class="mb-10">
    <table class="table">
        <thead>
        <tr>
            <th>序号</th>
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
                               value="<?=$val['ccp_sname'] ?>"></td>
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
                               name="CrmCustProduct[<?= $key ?>][ccp_remark]" maxlength="200"
                               value="<?= $val['ccp_remark'] ?>"></td>
                    <td><a onclick="reset(this)">重置</a> <a onclick="vacc_del(this,'product_table')">删除</a></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>

    <div class="space-10"></div>

    <p class="text-right mt-10">
        <a class="icon-plus" onclick="add_product()">添加产品</a>
    </p>
</div>

    <div class="space-10"></div>


<h2 class="head-second color-1f7ed0">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">主要客户</a>
</h2>
<div class="mb-10">
    <div style="width:100%;overflow: auto;">
        <table class="table" style="width: 1400px;">
            <thead>
            <tr>
                <th width="100">序号</th>
                <th width="100"><span class="red">*</span>公司名称</th>
                <th width="100">经营类型</th>
                <th width="100">公司电话</th>
                <th width="100">占营收比率（%）</th>
                <th width="100">公司负责人</th>
                <th width="100">联系方式</th>
                <th width="100">备注</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody id="customer_table">
            <?php if (!empty($model['custCustomer'])) { ?>
                <?php foreach ($model['custCustomer'] as $key => $val) { ?>
                    <tr>
                        <td><span style="width:50px;"><?= $key + 1 ?></span></td>
                        <td><input class=" no-border text-center easyui-validatebox" type="text" style="width: 60px"
                                   data-options="required:'true'"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_name]"
                                   maxlength="20" value="<?=$val['cc_customer_name'] ?>"></td>
                        <td>
                            <select class="value-width value-align" name="CrmCustCustomer[<?= $key ?>][cc_customer_type]">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['managementType'] as $k => $v) { ?>
                                    <option value="<?= $v['bsp_id'] ?>" <?= $val['cc_customer_type'] == $v['bsp_id'] ? "selected" : null; ?>><?= $v['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td><input class="width-100 no-border text-center easyui-validatebox" data-options="validType:'telphone'" type="text" id="cc_customer_tel"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_tel]"
                                   placeholder="请输入:xxxx-xxxxxxx"
                                   maxlength="20" value="<?= $val['cc_customer_tel']?>"></td>
                        <td><input class="width-50 no-border text-center easyui-validatebox" data-options="validType:'two_percent'"  type="text"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_ratio]"
                                   value="<?= $val['cc_customer_ratio'] ?>" maxlength="20"></td>
                        <td><input class="width-80 no-border text-center" type="text"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_person]"
                                   maxlength="20" value="<?= $val['cc_customer_person'] ?>"></td>
                        <td><input class="width-80 no-border text-center easyui-validatebox" data-options="validType:'mobile'" type="text" id="cc_customer_mobile"
                                   name="CrmCustCustomer[<?= $key ?>][cc_customer_mobile]"
                                   placeholder="请输入:138xxxxxxxx"
                                   maxlength="20" value="<?= $val['cc_customer_mobile'] ?>"></td>

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
    <div class="space-10"></div>
    <p class="text-right mt-10">
        <a class="icon-plus" onclick="add_customer()">添加客户</a>
    </p>
</div>
<div class="text-center">
    <button type="submit" class="button-blue-big save-form">保存</button>
    <button class="button-white-big" onclick="window.location.href='<?= Url::to(["index"]) ?>'" type="button">返回</button>
</div>
</div>

<?php ActiveForm::end(); ?>
<style type="text/css">
    .head-second + div {
        display: none;
    }
    .cert-item{
        position: relative;
    }
    .cert-item .remove{
        position: absolute;
        left:500px;
        height: 25px;
        line-height: 25px;
        color:darkred;
        cursor: pointer;
    }
</style>
<script>
    $(function () {

        var bslicname = $("#bslic").val();//公司营业执照/三证合一证文件名
        var bslicna = bslicname.substring(2, 8);//从第二位到第八位
        //var reg = new RegExp("-", "g");
        // bslicna = bslicna.replace(reg, '');//公司营业执照/三证合一证时间文件名

        var oregname = $("#oregname").val();//税务登记证文件名
        var oregna = oregname.substring(2, 8);//从第二位到第八位
        //oregna = oregna.replace(reg, '');//税务登记证时间文件名

        var ocerftname = $("#ocerftname").val();//一般纳税人资格证文件名
        var ocerftna = ocerftname.substring(2, 8);//从第二位到第八位
        //ocerftna = ocerftna.replace(reg, '');//一般纳税人资格证时间文件名

        var httpip = $("#httpIp").val();//服务器地址
        var cmp = $("#cmpname").val();//cmp文件夹
        var bslcn = $("#bslcn").val();//公司营业执照/三证合一文件夹
        var taxreg = $("#reg").val();//税务登记证文件夹
        var taxqlf = $("#qlf").val();//一般纳税人资格证书文件夹
        $("#license").attr('href', httpip + cmp + bslcn + '/' + bslicna + '/' + bslicname);//公司营业执照/三证合一证书的图片路径
        $("#oreg").attr('href', httpip + cmp + taxreg + '/' + oregna + '/' + oregname);//税务登记证书的图片路径
        $("#cerft").attr('href', httpip + cmp + taxqlf + '/' + ocerftna + '/' + ocerftname);//一般纳税人资格证书的图片路径

        $.extend($.fn.validatebox.defaults.rules, {
            taxCode: {
                validator: function(value,param){
                    return /^[0-9a-zA-Z]{15,20}$/.test(value);
                },
                message: '必须为15-20位的数字,字母组合'
            },
            regCard: {
                validator: function(value,param){
                    return /^[0-9a-zA-Z]*$/.test(value);
                },
                message: '只能输入字母或数字'
            }
        });

        $('#contacts_table input,#equipment_table input,#product_table input,#customer_table input').focus(function(){
            $(this).addClass('no-border');
        });
        ajaxSubmitForm($("#add-form"));
//        $(".save-form").on('click', function () {
//            $('.no-border').removeClass('no-border');
//            $('.memberType').attr('disabled',false);
//
////            $("#custSalearea").removeAttr('disabled');
//        });
        $(".head-second").next("div:eq(0)").css("display", "block");
        $(".head-second").next("div:eq(1)").css("display", "block");
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-right");
            $(this).prev().toggleClass("icon-caret-down");
        });
        /*地址联动*/
        $('.disName,.disName1,.disName2,.disName3').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select, $url, "select");
        });
    });

    /*新增联系人*/
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
            '<td><input class="width-70 no-border text-center Wdate" type="text"  name="CrmCustomerPersion[' + a + '][ccper_birthday]" placeholder="" maxlength="20" onclick="WdatePicker({ onpicked: function () { }, skin: \'whyGreen\', dateFmt: \'yyyy-MM-dd\'})" readonly="readonly"></td>' +
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
    /*新增设备信息*/
    function add_equipment() {
        var a = $("#equipment_table tr").length;
        var b = a;
        b += 1;
        var obj = $("#equipment_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><input class="width-200 no-border text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustDevice[' + a + '][type]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-200 no-border text-center" type="text"  name="CrmCustDevice[' + a + '][brand]" placeholder="" maxlength="20"></td>' +
            '<td><a onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,'+ "'equipment_table'" +')">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }
    /*新增主营产品*/
    function add_product() {
        var a = $("#product_table tr").length;
        var b=a;
        b += 1;
        var obj = $("#product_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><input class="width-100 no-border text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustProduct[' + a + '][ccp_sname]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustProduct[' + a + '][ccp_model]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustProduct[' + a + '][ccp_annual]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustProduct[' + a + '][ccp_brand]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustProduct[' + a + '][ccp_remark]" placeholder="" maxlength="50"></td>' +
            '<td><a onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,'+ "'product_table'" +')">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }
    /*新增客户*/
    function add_customer() {
        var a = $("#customer_table tr").length;
        var b = a;
        b += 1;
        var obj = $("#customer_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><input class="width-170 no-border text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_name]" placeholder="" maxlength="20"></td>' +
            '<td>' +
            '<select class="value-width value-align" name="CrmCustCustomer['+ a +'][cc_customer_type]">'+
            '<option value="">请选择...</option>'+
            <?php foreach ($downList['managementType'] as $k => $v) { ?>
            '<option value="<?= $v['bsp_id'] ?>"><?= $v['bsp_svalue'] ?></option>'+
            <?php } ?>
            '</select>' +
            '</td>' +
            '<td><input class="width-100 no-border text-center easyui-validatebox" data-options="validType:\'telphone\'" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_tel]" placeholder="请输入:xxxx-xxxxxxxx" maxlength="20"></td>' +
            '<td><input class="width-50 no-border text-center easyui-validatebox" data-options="validType:\'two_percent\'" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_ratio]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-80 no-border text-center" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_person]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-80 no-border text-center easyui-validatebox" data-options="validType:\'mobile\'" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_mobile]" placeholder="请输入:138 xxxx xxxx" maxlength="20"></td>' +

            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_remark]" placeholder="" maxlength="50"></td>' +
            '<td><a onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,'+ "'customer_table'" +')">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }
    /*登记证新增*/
    function add_reg() {
        var a = $(".reg_sum").length;
        ++a;
        $("#reg").append(
            '<div class="mb-10 reg_sum">' +
            '<label class="label-width label-align" for="">登记证名称' + a + '：</label>' +
            ' <input class="value-width value-align" type="text"  name="CrmCustomerInfo[cust_regname][]" maxlength="20">' +
            ' <label class="label-width label-align" for="">登记证号码' + a + '：</label>' +
            ' <input class="value-width value-align easyui-validatebox" data-options="validType:\'regCard\'" type="text"  name="CrmCustomerInfo[cust_regnumber][]" maxlength="50">' +
            '</div>'
        );
        $.parser.parse("#reg");
    }
    /*删除登记证*/
    function del_reg() {
        var a = $(".reg_sum").length;
        if (a > 1) {
            $("#reg .reg_sum:last").remove();
        }
    }
    /*重置表格信息*/
    function reset(obj) {
        var td = $(obj).parents("tr").find("td");
        $(obj).parents("tr").find("input").val("");
    }
    /*删除表格行*/
    function vacc_del(obj,id) {
        $(obj).parents("tr").remove();
//        console.log($(obj).parents("tr").find('td').eq(0).text());
//        alert($("#contacts_table tr").length);
        var a = $("#"+ id +" tr").length;
        for(var i=0;i<a;i++){
            $('#'+id).find('tr').eq(i).find('td:first').text(i+1);
        }
    }
    /*所在地区及营销区域联动*/
    $(function () {
        $("#custArea").on("change", function () {
            var id = $(this).val();
            $('.custSalearea').html('<option value="">请选择...</option>');
            $.ajax({
                type: "get",
                dataType: "json",
                data: {"id": id},
                url: "<?= Url::to(['/crm/crm-customer-info/get-district-salearea'])?>",
                success: function (msg) {
//                    $("#custSalearea").val(msg.csarea_id);
                    for(var $i=0;$i < msg.length;$i++){
//                        console.log(msg.length);
//                        return false;
                        $(".custSalearea").append('<option value="'+ msg[$i].csarea_id +'">'+ msg[$i].csarea_name +'</option>')
                    }
                    if (msg.length == 1) {
                        $(".custSalearea").val(msg[0].csarea_id)
                    }
                },
            })
        })
    })
    $(document).on('click','.select_date',function () {
        jeDate({
            dateCell: this,
            zIndex:8831,
            format: "YYYY-MM-DD",
            skinCell: "jedatedeep",
            isTime: false,
            okfun:function(elem) {
                $(elem).change();
            },
            //点击日期后的回调, elem当前输入框ID, val当前选择的值
            choosefun:function(elem) {
                $(elem).change();
            }
        })
    })
    $(function(){
        $('.ismember_y').click(function(){
//            console.log($('.ismember').val());
            $('.memberType').val('100070');
            $('.memberType').attr('disabled',true);
        })
        $('.ismember_n').click(function(){
//            console.log($('.ismember').val());
            $('.memberType').val('');
            $('.memberType').attr('disabled',true);
        })
        //选择客户弹出框
        $("#select_manage").fancybox({
            padding: [],
            fitToView: false,
            width: 800,
            height: 570,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });

        $(".cert-item .remove").click(function(){
            $(this).prev("input").val("").change();
            $(this).next("input").css("display","inline-block");
            $(this).next("input").next("input").css("display","none");
            $(this).css("visibility","hidden");
        });
        $(".cert-item .preview").click(function(){
            var src="<?=\Yii::$app->params['FtpConfig']['httpIP']?>"+$(this).parent().find(":text").val();
            window.location.href=src;
        });

    })

    function uploadCallback(t,res){
        t.prev(".remove").css("visibility","visible");
        t.css("display","none");
        t.next("input").css("display","inline-block")
    }
</script>

