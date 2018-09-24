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
$regname = unserialize(Html::decode($model['cust_regname']));
$regnumber = unserialize(Html::decode($model['cust_regnumber']));
\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);
$action=\Yii::$app->controller->action->id;
?>
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
            <input name="CrmCustomerInfo[cust_sname]" class="value-width value-align  add-require easyui-validatebox" data-options="required:true,validType:'unique',delay:200" data-act="<?=Url::to(['validate'])?>" data-attr="cust_sname" data-id="<?=$model['cust_id'];?>"  type="text" value="<?= $model['cust_sname'] ?>" maxlength="50" onkeyup="$('.invoice_title').val(this.value);">
        </div>
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>客户简称：</label>
            <input class="value-width value-align easyui-validatebox" maxlength="60" id="cust_shortname"
                   name="CrmCustomerInfo[cust_shortname]"
                   data-options="required:'true'" value="<?= $model['cust_shortname'] ?>">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align" for="">公司电话：</label>
            <input class="value-width value-align easyui-validatebox IsTel" data-options="validType:'telphone'" type="text" name="CrmCustomerInfo[cust_tel1]" value="<?= $model['cust_tel1'] ?>" maxlength="15" placeholder="请输入：xxxx-xxxxxx">
        </div>
        <div class="inline-block">
            <label class="label-width label-align" for="">传真：</label>
            <input class="value-width value-align easyui-validatebox IsTel" data-options="validType:'fax'" type="text" name="CrmCustomerInfo[cust_fax]" value="<?= $model['cust_fax'] ?>" maxlength="15" placeholder="请输入：xxxx-xxxxxx">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>客户类型：</label>
            <select class="value-width value-align easyui-validatebox" name="CrmCustomerInfo[cust_type]"
                    data-options="required:'true'">
                <option value>请选择...</option>
                <?php foreach ($downList['customerType'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>" <?= $model['cust_type'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">客户来源：</label>
            <select class="value-width value-align" name="CrmCustomerInfo[member_source]">
                <option value="">请选择...</option>
                <?php foreach ($downList['customerSource'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['member_source'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>所在地区：</label>
            <select class="value-width value-align easyui-validatebox" id="custArea" name="CrmCustomerInfo[cust_area]" data-options="required:'true'">
                <option value="">请选择...</option>
                <?php foreach ($district as $key => $val) { ?>
                    <option
                            value="<?= $val['district_id'] ?>" <?= $model['cust_area'] == $val['district_id'] ? "selected" : null; ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">营销区域：</label>
            <select class="value-width value-align custSalearea" name="CrmCustomerInfo[cust_salearea]">
                <option value="">请选择...</option>
                <?php foreach ($downList['salearea'] as $key => $val) { ?>
                    <option
                            value="<?= $val['csarea_id'] ?>" <?= $model['cust_salearea'] == $val['csarea_id'] ? "selected" : null; ?>><?= $val['csarea_name'] ?></option>
                <?php } ?>
            </select>
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
                   name="CrmCustomerInfo[cust_tel2]" data-options="required:'true',validType:'mobile'"
                   value="<?= $model['cust_tel2'] ?>" placeholder="请输入:138xxxxxxxx">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align">邮箱：</label>
            <input class="value-width value-align easyui-validatebox" data-options="validType:'email'" type="text" name="CrmCustomerInfo[cust_email]" value="<?= $model['cust_email'] ?>" maxlength="30" id="cust_email" placeholder="请输入:xxxx@xx.com">
        </div>
        <div class="inline-block">
            <?php if(Yii::$app->controller->action->id==='create'){ ?>
            <input type="hidden" name="CrmCustomerInfo[cust_manager]" class="staff_id"
                   value="<?= $u['staff']['id'] ?>">
            <label class="label-width label-align">客户经理人：</label>
            <span style="width: 280px;">
                <?php if($isSuper && !$u){ ?>
                    <input type="text" class="value-width value-align staff_code" value="" name="code" readonly="readonly">
                <?php }else if($isSuper && $u){ ?>
                    <input type="text" class="value-width value-align staff_code" value="<?= !empty($u)?$u['staff_code']."-".$u['staff']['name']:'' ?>" name="code" readonly="readonly">
                <?php }else{ ?>
                    <input type="text" class="value-width value-align staff_code" value="<?= !empty($u)?$u['staff_code']."-".$u['staff']['name']:'' ?>" name="code" readonly="readonly">
                <?php } ?>
                <?php if($isSuper || !$u){ ?>
                    <a id="select_manage">选择</a>
                <?php } ?>
            </span>
            <?php }else if(Yii::$app->controller->action->id==='update'){ ?>
                <input type="hidden" name="CrmCustomerInfo[cust_manager]" class="staff_id"
                       value="<?= $model['manager_cid'] ?>">
                <label class="label-width label-align">客户经理人：</label>
                <?php if($isSuper){ ?>
                    <input type="text" class="value-width value-align staff_code" value="<?= $model['manager_code']?$model['manager_code']:""; ?>" readonly="readonly">
                <?php }else{ ?>
<!--                    <input type="hidden" name="CrmCustomerInfo[cust_manager]" class="staff_id"-->
<!--                           value="--><?//= $model['manager_cid'] ?><!--">-->
<!--                    <span class="value-width value-align">--><?//= $model['manager_code']?$model['manager_code']:""; ?><!--</span>-->
                    <input type="text" class="value-width value-align staff_code" value="<?= $model['manager_code']?$model['manager_code']:""; ?>" name="code" readonly="readonly">
                <?php } ?>
                <?php if($isSuper || !$model){ ?>
                    <a id="select_manage">选择</a>
                <?php } ?>

            <?php } ?>
        </div>
    </div>
    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>详细地址：</label>
            <select style="width: 134px;" class="disName easyui-validatebox" data-options="required:'true'">
                <option value="">请选择...</option>
                <?php foreach ($downList['country'] as $key => $val) { ?>
                    <option value="<?= $val['district_id'] ?>" <?=$val['district_id']==$districtAll2['oneLevelId']?'selected':null?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select style="width: 134px;" class="disName easyui-validatebox" data-options="required:'true'">
                <option value="">请选择...</option>
                <?php if(!empty($districtAll2)){?>
                    <?php foreach($districtAll2['twoLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll2['twoLevelId']?'selected':null?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <select  style="width: 134px;" class="disName easyui-validatebox" data-options="required:'true'">
                <option value="">请选择...</option>
                <?php if(!empty($districtAll2)){?>
                    <?php foreach($districtAll2['threeLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll2['threeLevelId']?'selected':null?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <select style="width:134px;" class="disName easyui-validatebox" data-options="required:'true'" name="CrmCustomerInfo[cust_district_2]">
                <option value="">请选择...</option>
                <?php if(!empty($districtAll2)){?>
                    <?php foreach($districtAll2['fourLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll2['fourLevelId']?'selected':null?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <span class="red" style="font-size: 10px;">请确保与营业执照上的住所保持一致</span>
            <div class="mb-10"></div>
            <input style="width:548px;margin-left: 143px;" class="mt-5 easyui-validatebox" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>" maxlength="50" placeholder="最多输入50个字" id="cust_adress">
    </div>
</div>

<div class="space-10"></div>

<h2 class="head-second color-1f7ed0">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">客户详细信息</a>
</h2>
<div class="ml-10">
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align" for="">公司属性：</label>
            <select class="value-width value-align" name="CrmCustomerInfo[cust_compvirtue]">
                <option value="">请选择...</option>
                <?php foreach ($downList['companyProperty'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>" <?= $model['cust_compvirtue'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">公司规模：</label>
            <select class="value-width value-align" name="CrmCustomerInfo[cust_compscale]">
                <option value>请选择...</option>
                <?php foreach ($downList['companyScale'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>" <?= $model['cust_compscale'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>

    </div>

    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align" for="">行业类别：</label>
            <select class="value-width value-align" name="CrmCustomerInfo[cust_industrytype]">
                <option value="">请选择...</option>
                <?php foreach ($downList['industryType'] as $key => $val) { ?>
                    <option value="<?= $val['idt_id'] ?>" <?= $model['cust_industrytype'] == $val['idt_id'] ? "selected" : null; ?>><?= $val['idt_sname'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">客户等级：</label>
            <select class="value-width value-align" name="CrmCustomerInfo[cust_level]">
                <option value="">请选择...</option>
                <?php foreach ($downList['custLevel'] as $key => $val) { ?>
                    <option value="<?= $val['bsp_id'] ?>" <?= $model['cust_level'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align" for="">经营类型：</label>
            <select class="value-width value-align" name="CrmCustomerInfo[cust_businesstype]">
                <option value="">请选择...</option>
                <?php foreach ($downList['managementType'] as $key => $val) { ?>
                    <option value="<?= $val['bsp_id'] ?>" <?= $model['cust_businesstype'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">员工人数：</label>
            <input class="value-width value-align easyui-validatebox Onlynum" data-options="validType:'number'" type="text" name="CrmCustomerInfo[cust_personqty]" value="<?= $model['cust_personqty'] ?>" maxlength="15" id="cust_personqty">
        </div>
    </div>
    <div class="mb-10 overflow-auto">
        <div class="inline-block">
            <label class="label-width label-align">是否上市公司：</label>
            <span class="value-width">
                <input type="radio" value="1"
                       name="CrmCustomerInfo[cust_islisted]" <?= $model['cust_islisted'] == 1 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0"
                       name="CrmCustomerInfo[cust_islisted]" <?= $model['cust_islisted'] == 0 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">否</span>
            </span>
        </div>
        <div class="inline-block">
            <label class="label-width label-align" for="">注册时间：</label>
            <input class="value-width value-align Wdate" type="text" name="CrmCustomerInfo[cust_regdate]" value="<?= $model['cust_regdate'] ?>" id="cust_regdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate:'%y-%M-%d' })" onfocus="this.blur()">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align">注册资金：</label>
            <input  class="value-width value-align easyui-validatebox Onlynum" data-options="validType:'number'" type="text" name="CrmCustomerInfo[cust_regfunds]" value="<?= !empty($model['cust_regfunds'])?$model['cust_regfunds']:"" ?>" maxlength="15">
        </div>
        <div class="inline-block">
            <label class="label-width label-align">注册货币：</label>
            <?=Html::dropDownList("CrmCustomerInfo[member_regcurr]",$model['member_regcurr']?$model['member_regcurr']:100091,array_combine(array_column($downList['tradeCurrency'],"bsp_id"),array_column($downList['tradeCurrency'],"bsp_svalue")),["class"=>"value-width value-align"])?>
        </div>
    </div>
    <div class="mb-10 overflow-auto">
        <div class="inline-block">
            <label class="label-width label-align">是否公司会员：</label>
            <span class="value-width">
                <input type="radio" value="1" class="ismember_y"
                       name="CrmCustomerInfo[cust_ismember]" <?= $model['cust_ismember'] == 1 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="ismember_n"
                       name="CrmCustomerInfo[cust_ismember]" <?= $model['cust_ismember'] == 0 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">否</span>
            </span>
        </div>
        <div class="inline-block">
            <label class="label-width label-align" for="">会员类型：</label>
            <span class="member-type value-width value-align"><?= $model['memberType'] ?></span>
            <input type="text" class="memberType display-none"  name="CrmCustomerInfo[member_type]" value="<?= $model['member_type'] ?>">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align">潜在需求：</label>
            <select class="value-width value-align" name="CrmCustomerInfo[member_reqflag]">
                <option value="">请选择...</option>
                <?php foreach ($downList['latentDemand'] as $key => $val) { ?>
                    <option value="<?= $val['bsp_id'] ?>" <?= $model['member_reqflag'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">需求类目：</label>
            <select class="value-width value-align" name="CrmCustomerInfo[member_reqitemclass]">
                <option value>请选择...</option>
                <?php foreach ($downList['productType'] as $key => $val) { ?>
                    <option value="<?= $val['catg_id'] ?>"<?= $model['member_reqitemclass'] == $val['catg_id'] ? "selected" : null; ?>><?= $val['catg_name'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align">旺季分布：</label>
            <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_bigseason]"
                   value="<?= $model['cust_bigseason'] ?>" maxlength="15">
        </div>
        <div class="inline-block">
            <label class="label-width label-align">公司主页：</label>
            <input class="value-width value-align easyui-validatebox" data-options="validType:'www'" type="text" name="CrmCustomerInfo[member_compwebside]" value="<?= $model['member_compwebside'] ?>" maxlength="50" id="member_compwebside" placeholder="请输入:www.xxxxxxxx.com">
        </div>
    </div>

    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align">交易币别：</label>
            <?=Html::dropDownList("CrmCustomerInfo[member_curr]",$model['member_curr']?$model['member_curr']:100091,array_combine(array_column($downList['tradeCurrency'],"bsp_id"),array_column($downList['tradeCurrency'],"bsp_svalue")),["class"=>"value-width value-align"])?>
        </div>
        <div class="inline-block">
            <label class="label-width label-align">年营业额：</label>
            <span class="value-width">
                <input style="width: 120px;" class="value-align easyui-validatebox Onlynum" data-options="validType:'number'" type="text" name="CrmCustomerInfo[member_compsum]" value="<?= !empty($model['member_compsum'])?$model['member_compsum']:"" ?>" maxlength="15">
                <?=Html::dropDownList("CrmCustomerInfo[compsum_cur]",$model['compsum_cur']?$model['compsum_cur']:100091,array_combine(array_column($downList['tradeCurrency'],"bsp_id"),array_column($downList['tradeCurrency'],"bsp_svalue")),["style"=>"width:75px"])?>
            </span>
        </div>
    </div>
    <div id="reg">
        <?php if (!empty($regname) && !empty($regnumber)) { ?>
            <?php foreach ($regname as $key => $val) { ?>
                <div class="mb-10 reg_sum">
                    <div class="inline-block">
                        <label class="label-width label-align" for="">登记证名称<?php echo $key + 1; ?>：</label>
                        <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_regname][]" value="<?= $val ?>" maxlength="20">
                    </div>
                    <div class="inline-block">
                        <label class="label-width label-align" for="">登记证号码<?php echo $key + 1; ?>：</label>
                        <input class="value-width value-align easyui-validatebox" data-options="validType:'regCard'" type="text" name="CrmCustomerInfo[cust_regnumber][]"
                               value="<?= $regnumber[$key] ?>" maxlength="50">
                    </div>
                    <?php if ($key == 0) { ?>
                        <a class="icon-plus" onclick="add_reg()"> 添加</a>&nbsp;
                    <?php } ?>
                    <a class="icon-minus" onclick="del_reg()"> 删除</a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="mb-10 reg_sum">
                <div class="inline-block">
                    <label class="label-width label-align" for="">登记证名称1：</label>
                    <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_regname][]" maxlength="20">
                </div>
                <div class="inline-block">
                    <label class="label-width label-align" for="">登记证号码1：</label>
                    <input class="value-width value-align easyui-validatebox" data-options="validType:'regCard'"  type="text" name="CrmCustomerInfo[cust_regnumber][]" maxlength="50">
                </div>
                <a class="icon-plus" onclick="add_reg()"> 添加</a>
                <a class="icon-minus" onclick="del_reg()"> 删除</a>
            </div>
        <?php } ?>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align vertical-top">经营范围：</label>
            <textarea  style="width:548px;" name="CrmCustomerInfo[member_businessarea]" id="member_businessarea" onkeyup="surplus(this,200);" cols="5" rows="3" maxlength="200" placeholder="最多输入200个字"><?= $model['member_businessarea'] ?></textarea>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align" for="">申请发票类型：</label>
            <select name="CrmCustomerInfo[invoice_type]" id="" class="value-width">
                <option value="">请选择...</option>
                <?php foreach ($downList['invoiceType'] as $key => $val) { ?>
                    <option value="<?= $val['bsp_id'] ?>"<?= $model['invoice_type'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-width label-align" for="">发票抬头：</label>
            <input class="value-width value-align invoice_title" type="text" name="CrmCustomerInfo[invoice_title]"
                   value="<?= $model['invoice_title'] ?>" maxlength="50" readonly="readonly">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align">发票抬头地址：</label>
            <select style="width:134px;" class="disName2">
                <option value="">请选择...</option>
                <?php foreach ($downList['country'] as $key => $val) { ?>
                    <option value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll4['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select style="width:134px;" class="disName2">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll4)) { ?>
                    <?php foreach ($districtAll4['twoLevel'] as $val) { ?>
                        <option value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll4['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select style="width:134px;" class="disName2">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll4)) { ?>
                    <?php foreach ($districtAll4['threeLevel'] as $val) { ?>
                        <option value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll4['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select style="width:134px;" class="disName2" name="CrmCustomerInfo[invoice_title_district]">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll4)) { ?>
                    <?php foreach ($districtAll4['fourLevel'] as $val) { ?>
                        <option value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll4['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>

            <div class="mb-10"></div>

            <input style="width:548px;margin-left: 143px;" class="mt-5" type="text" name="CrmCustomerInfo[invoice_title_address]"
                   value="<?= $model['invoice_title_address'] ?>" maxlength="50" placeholder="最多输入50个字">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align">发票寄送地址：</label>
            <select style="width: 134px;" class="disName3">
                <option value="">请选择...</option>
                <?php foreach ($downList['country'] as $key => $val) { ?>
                    <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll5['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select style="width: 134px;" class="disName3">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll5)) { ?>
                    <?php foreach ($districtAll5['twoLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll5['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select style="width: 134px;" class="disName3">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll5)) { ?>
                    <?php foreach ($districtAll5['threeLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll5['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select style="width: 134px;" class="disName3" name="CrmCustomerInfo[invoice_mail_district]">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll5)) { ?>
                    <?php foreach ($districtAll5['fourLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll5['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>

            <div class="mb-10"></div>

            <input style="width:548px;margin-left: 143px;" class="mt-5" type="text" name="CrmCustomerInfo[invoice_mail_address]"
                   value="<?= $model['invoice_mail_address'] ?>" maxlength="50"  placeholder="最多输入50个字">
        </div>
    </div>

    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align" for="">总公司：</label>
            <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_parentcomp]"
                   value="<?= $model['cust_parentcomp'] ?>" maxlength="120">
        </div>
        <div class="inline-block">
            <label class="label-width label-align" for="">公司负责人：</label>
            <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_inchargeperson]"
                   value="<?= $model['cust_inchargeperson'] ?>" maxlength="20">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align">总公司地址：</label>
            <select style="width: 134px;" class="disName1">
                <option value="">请选择...</option>
                <?php foreach ($downList['country'] as $key => $val) { ?>
                    <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select  style="width: 134px;" class="disName1">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['twoLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select  style="width: 134px;" class="disName1">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['threeLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select  style="width: 134px;" class="disName1" name="CrmCustomerInfo[cust_district_3]">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll3)) { ?>
                    <?php foreach ($districtAll3['fourLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll3['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>

            <div class="mb-10"></div>

            <input style="width:548px;margin-left: 143px;" class="mt-5" type="text" name="CrmCustomerInfo[cust_headquarters_address]"
                   value="<?= $model['cust_headquarters_address'] ?>" maxlength="50"  placeholder="最多输入50个字">
        </div>
    </div>
</div>

<div class="space-10"></div>

<h2 class="head-second color-1f7ed0">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">认证信息</a>
</h2>
<div class="auth">
    <div class="mb-10">
        <input type="hidden" id="ynspp" value="<?= $crmcertf['yn_spp'] ?>">
        <label class="label-width " for="">是否供应商：</label>
        <input type="radio" value="1" id="radyes" name="CrmC[yn_spp]" <?= $crmcertf['yn_spp'] == 1 ? "checked=checked" : null; ?>>

        <span class="vertical-middle">是</span>
        <input type="radio" value="0" id="radno" name="CrmC[yn_spp]" style="margin-left: 50px;" <?= $crmcertf['yn_spp'] == 0 ? "checked=checked" : null; ?>>

        <span class="vertical-middle">否</span>
        <span style="margin-left: 140px; <?= $crmcertf['yn_spp'] == '0' || empty($crmcertf) ?'display:none':'' ?>"  id="custcode">
                <label class="lable-width label-align " for=""><span class="red">*</span>供应商代码：</label>
                <input class=" value-align" id="sppno"
                       style="width: 240px;"
                       type="text" name="CrmC[spp_no]"
                       value="<?= $crmcertf['spp_no'] ?>" maxlength="30">
            </span>
    </div>
    <div class="mb-10">
        <input type="hidden" id="crtftype" value="<?= $crmcertf['crtf_type'] ?>">
        <label class="label-width label-align" for="">证件类型：</label>
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
    <div class="inline-block mb-10">
        <label class="label-align" for="">税籍编码/统一社会信用代码：</label>
<!--        <input class="value-width value-align easyui-validatebox"  id="taxcode" type="text" name="CrmCustomerInfo[cust_tax_code]"-->
<!--               value="--><?//= $model['cust_tax_code'] ?><!--" maxlength="20" >-->
        <input name="CrmCustomerInfo[cust_tax_code]" class="value-width value-align  easyui-validatebox remove" data-options="delay:10000,validateOnBlur:true" data-act="<?=Url::to(['/crm/crm-customer-info/validate-code'])?>"  data-attr="cust_tax_code" data-id="<?=$model['cust_id'];?>" type="text" value="<?= $model['cust_tax_code'] ?>" maxlength="20" placeholder="最多输入20个字" id="taxcode">
    </div>
    <div id="oldthreecer" <?=$crmcertf['crtf_type']?"class='display-none'":""?> >
        <div class="mb-10">
            <label class="label-width label-align" id="business">公司营业执照：</label>
            <input type="text" readonly="readonly" class="easyui-validatebox"  data-options="validType:'regFile'"
                   name="CrmC[o_license]" maxlength="120" id="license_name"
                   value="<?= $crmcertf['crtf_type'] == 0 ? $crmcertf['o_license'] :'' ?>" style="width: 500px;">
            <input type="file" style="width: 70px;display: none;" name="upfiles-lic" id="upfiles-lic"
                   class="up-btn" onchange="license(this)"/>
            <button type="button" style="outline: none;width:80px;height: 25px;" onclick="$(this).prev('input').click()">选择文件</button>
        </div>
        <div class="mb-10" id="tax">
            <label class="label-width label-align">税务登记证：</label>
            <input type="text" readonly="readonly"
                   name="CrmC[o_reg]" maxlength="120" id="tax_name" class="easyui-validatebox" data-options="validType:'regFile'"
                   value="<?= $crmcertf['o_reg'] ?>" style="width: 500px;">
            <input type="file" style="width: 70px;display: none;" name="upfiles-tax" id="upfiles-tax" class="up-btn"
                   onchange="reg(this)"/>
            <button type="button" style="outline: none;width:80px;height: 25px;" onclick="$(this).prev('input').click()">选择文件</button>
        </div>
    </div>
    <div id="newthreecer" <?=$crmcertf['crtf_type']?"":"class='display-none'"?> >
        <div class="mb-10">
            <label class="label-width label-align" id="business">公司三证合一：</label>
            <input type="text" readonly="readonly"
                   name="CrmC[o_license_new]" maxlength="120" id="license_name_new" class="easyui-validatebox" data-options="validType:'regFile'"
                   value="<?= $crmcertf['crtf_type'] == 1 ? $crmcertf['o_license'] :'' ?>" style="width: 500px;">
            <input type="file" style="width: 70px;display: none;" name="upfiles-lic" id="upfiles-lic"
                   class="up-btn" onchange="licenseNew(this)"/>
            <button type="button" style="outline: none;width:80px;height: 25px;" onclick="$(this).prev('input').click()">选择文件</button>
        </div>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">一般纳税人资格证：</label>
        <input style="width: 500px;" type="text" readonly="readonly"
               name="CrmC[o_cerft]" maxlength="120" id="org_name" data-options="validType:'regFile'" class="easyui-validatebox"
               value="<?= $crmcertf['o_cerft'] ?>">
        <input type="file" style="width: 70px;display: none;" name="upfiles-org" id="upfiles-org" class="up-btn"
               onchange="cerft(this)"/>
        <button type="button" style="outline: none;width:80px;height: 25px;" onclick="$(this).prev('input').click()">选择文件</button>
    </div>
    <div class="mb-10">
        <label class="label-width label-align vertical-top">备注：</label>
        <textarea rows="3" name="CrmC[marks]" style="width: 575px;" id="remark"
                  placeholder="最多输入200个字"
                  maxlength="200"><?= $crmcertf['marks'] ?></textarea>
    </div>
</div>
<div class="space-10"></div>

<h2 class="head-second color-1f7ed0">
    <i class="icon-caret-right"></i>
    <a href="javascript:void(0)">主要联系人</a>
</h2>
<div class="mb-10">
    <div style="width: 100%;overflow-x: scroll;">
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
                                   onclick="WdatePicker({ onpicked: function () { }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate:'%y-%M-%d'})"
                                   name="CrmCustomerPersion[<?= $key ?>][ccper_birthday]"
                                   value="<?= empty($val['ccper_birthday']) ? '' : date('Y-m-d', strtotime($val['ccper_birthday'])) ?>"
                                   readonly="readonly"></td>
                        <td><input class="width-70 no-border text-center easyui-validatebox" type="text"
                                   data-options="validType:'fax'" id="ccper_fax"
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
        <div style="width:100%;overflow-x: scroll;">
            <table class="table" style="width: 1400px;">
                <thead>
                <tr>
                    <th width="100">序号</th>
                    <th width="100"><span class="red">*</span>公司名称</th>
                    <th width="100">经营类型</th>
                    <th width="100">公司电话</th>
                    <th width="100">占营收比率（%）</th>
                    <th width="100">公司负责人</th>
                    <th width="100">电话（手机）</th>
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
                            <td><input class="width-50 no-border text-center easyui-validatebox" data-options="validType:'two_percent'" type="text"
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
    <button class="button-white-big" onclick="window.history.go(-1)" type="button">返回</button>
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
        $(".IsTel").telphone();
        $(".Onlynum").numbervalid();
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
            },
            regFile: {
                validator: function(value,param){
                    return /.(jpg|png|tif|pdf|bmp|7z|rar|zip)$/.test(value);
                },
                message: '文件格式不正确'
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
    /*新增设备信息*/
    function add_equipment() {
        var a = $("#equipment_table tr").length;
        var b = a;
        b += 1;
        $("#equipment_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><input class="width-200 no-border text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustDevice[' + a + '][type]" placeholder="" maxlength="20"></td>' +
            '<td><input class="width-200 no-border text-center" type="text"  name="CrmCustDevice[' + a + '][brand]" placeholder="" maxlength="20"></td>' +
            '<td><a onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,'+ "'equipment_table'" +')">删除</a></td>' +
            '</tr>'
        );
        a++;
    }
    /*新增主营产品*/
    function add_product() {
        var a = $("#product_table tr").length;
        var b=a;
        b += 1;
        $("#product_table").append(
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
//            '<td><input class="width-100 no-border text-center" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_type]" placeholder="" maxlength="20"></td>' +
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
            '&nbsp;<a class="icon-minus" onclick="del_reg()"> 删除</a>'+
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
    $(function(){
        $('.ismember_y').click(function(){
            $('.memberType').val('100070');
            $('.memberType').val() && $('.member-type').text('普通会员');

        })
        $('.ismember_n').click(function(){
//            console.log($('.ismember').val());
            $('.memberType').val('');
            $('.member-type').text("");
        });

        if(document.getElementById("old").checked)
        {
            $("#taxcode").validatebox(

                {
                    validType:['taxCodeOld','unique']
                }
            )
        }
        else {
            $("#taxcode").validatebox(
                {
                    validType:['taxCodeNew','unique']
                }
            )
        }
        //选择客户弹出框
        $('#select_manage').click(function(){
            var url;
            <?php if(Yii::$app->controller->action->id==='update'){ ?>
            var staff_code = $('.staff_code').val();
            var staff_id = $('.staff_id').val();
            url = '<?= Url::to(['select-manage']) ?>?id='+ staff_id + '&code='+ staff_code
            <?php }else{ ?>
            url = '<?= Url::to(['select-manage']) ?>'
            <?php } ?>
            $("#select_manage").fancybox({
                padding: [],
                fitToView: false,
                width: 800,
                height: 570,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: url
            });
        })

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
        $.extend($.fn.validatebox.defaults.rules, {
            taxCodeOld: {
                validator: function (value, param) {
                    return /^[0-9a-zA-Z]{9,20}$/.test(value);
                },
                message: '必须为9-20位的数字,字母组合'
            },
            taxCodeNew:{
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
    })
    function uploadCallback(t,res){
        t.prev(".remove").css("visibility","visible");
        t.css("display","none");
        t.next("input").css("display","inline-block")
    }

    function change(obj) {
        var p=obj.value;
        var i=p.lastIndexOf("\\")+1;
        return p.substring(i);
//        var length = obj.files.length;
//        //var span = obj.parentNode.previousSibling.previousSibling;
//        var temp = "";
//        for (var i = 0; i < obj.files.length; i++) {
//            if (i == 0) {
////                console.log(obj);
////                console.log(obj.files[i].name);
//                temp = obj.files[i].name;
//            } else {
//                temp = temp + "&nbsp,&nbsp" + obj.files[i].name;
//            }
////            console.log(temp);
//            return temp;
////            $("#LICENSE_NAME").val(temp);
//        }
    }
    //三证合一和营业执照文件名
    function license(obj) {
        $("#license_name").val(change(obj)).validatebox("enableValidation");
//        var name=change(obj);
//        alert(name);
//        $("#upfiles-lic").val(change(obj));
    }
    function licenseNew(obj) {
//        $("#license_name_new").val(change(obj)).validatebox("enableValidation");

        $("#license_name_new").val(change(obj)).validatebox("enableValidation");
//        $("#upfiles-lic").val(change(obj));
    }
    //税务登记证文件名
    function reg(obj) {

        $("#tax_name").val(change(obj)).validatebox("enableValidation");
//        $("#tax_name").val(change(obj)).validatebox("enableValidation");
//        $("#upfiles-tax").val(change(obj));
    }
    //一般纳税人资格证文件名
    function cerft(obj) {

        $("#org_name").val(change(obj)).validatebox("enableValidation");
//        $("#org_name").val(change(obj)).validatebox("enableValidation");
//        $("#upfiles-org").val(change(obj));
    }

    //当是供应商时显示供应商代码
    $("#radyes").click(function () {
        $("#radyes").attr('checked', 'checked');
        $("#radno").attr('checked', false);
        $("#custcode").show();
        $('#sppno').validatebox({required:true,validType:'regCard'});
    });

    //当不是供应商时隐藏供应商代码
    $("#radno").click(function () {
        $("#radno").attr('checked', 'checked');
        $("#radyes").attr('checked', false);
        $("#custcode").css('display', 'none');
        $('#sppno').validatebox({required:false});
    });
//    $("input[name='CrmC[crtf_type]']").click(function(){
//        if($(this).val() == 0){
//            $("#old").attr('checked', 'checked');
//            $("#new").attr('checked', false);
//            $("#business").html("公司营业执照证：");
//            $("#tax").css('display', 'block');
//            $("#tax_name").attr('class', 'easyui-validatebox width-550');
//            $("#tax_name").attr('data-options', "required:'true'");
//            $("#taxcode").validatebox({
//                validType:'taxCodeOld'
//            });
//        }
//        if($(this).val() == 1){
//            $("#new").attr('checked', 'checked');
//            $("#old").attr('checked', false);
//            $("#business").html("公司三证合一证：");
//            $("#tax").css('display', 'none');
//            $("#tax_name").removeAttr('class');
//            $("#tax_name").removeAttr('data-options');
//            $("#taxcode").validatebox({
//                validType:'taxCodeNew'
//            });
//        }
//    });
    //当选择旧版三证时，隐藏公司三证合一证显示公司营业执照证和税务登记证
    $("#old").click(function () {
        $("#old").attr('checked', 'checked');
        $("#new").attr('checked', false);
//        $("#business").html("<span class='red'>*</span>公司营业执照证：");
        $("#tax").css('display', 'block');
        $("#tax_name").attr('class', 'easyui-validatebox width-550');
        $("#tax_name").attr('data-options', "required:'true'");
        $('#oldthreecer').removeClass('display-none');
        $('#newthreecer').addClass('display-none');
        $("#taxcode").validatebox({
            validType: ['taxCodeOld','unique']
        });
    });
    //当选择新版三证合一时，显示公司三证合一证隐藏公司营业执照证和税务登记证
    $("#new").click(function () {
        $("#new").attr('checked', 'checked');
        $("#old").attr('checked', false);
//        $("#business").html("<span class='red'>*</span>公司三证合一证：");
        $("#tax").css('display', 'none');
        $("#tax_name").removeAttr('class');
        $("#tax_name").removeAttr('data-options');
        $('#oldthreecer').addClass('display-none');
        $('#newthreecer').removeClass('display-none');
        $("#taxcode").validatebox({
            validType: ['taxCodeNew','unique']
        });
//        var len = $("#taxcode").val().length;
//        if (len < 17) {
//            alert("税籍编码不能小于17位");
//        }
//        $("#newthreeinone").css('display', 'block');
    });
    //当选择新版三证合一时，显示公司三证合一证隐藏公司营业执照证和税务登记证

</script>
