<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/22
 * Time: 17:18
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
\app\assets\JeDateAsset::register($this);
$regname = unserialize($model['cust_regname']);
$regnumber = unserialize($model['cust_regnumber']);
?>
<style>
    .label-width{
        width: 140px;
    }
    .value-width{
        width: 200px;
    }
</style>
<!--修改客户的公司信息-->
<div>
    <?php $form = ActiveForm::begin([
        'id'=>'customer-company',
        'action' => Url::to(['/crm/crm-customer-manage/customer-company', 'id' => $id]),
        'method' => 'post'
    ]) ?>
    <h2 class="head-first">修改客户的公司信息</h2>
    <div class="ml-10">
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
                    <input class="value-width value-align easyui-validatebox" data-options="validType:'int'" type="text" name="CrmCustomerInfo[cust_personqty]" value="<?= $model['cust_personqty'] ?>" maxlength="15" id="cust_personqty">
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
                    <input onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate:'%y-%M-%d' })" class="value-width value-align select-date" type="text" name="CrmCustomerInfo[cust_regdate]"
                           value="<?= $model['cust_regdate'] ?>" readonly="readonly">
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width label-align">注册资金：</label>
                    <input  class="value-width value-align easyui-validatebox Onlynum" data-options="validType:'int'" type="text" name="CrmCustomerInfo[cust_regfunds]" value="<?=$model['cust_regfunds']?(int)$model['cust_regfunds']:"" ?>" maxlength="15">
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
                    <span class="member-type value-width value-align"></span>
                    <select style="display: none;" class="value-width value-align memberType" name="CrmCustomerInfo[member_type]">
                        <option value="">请选择...</option>
                        <?php foreach ($downList['customerClass'] as $key => $val) { ?>
                            <option value="<?= $val['bsp_id'] ?>" <?= $model['member_type'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                        <?php } ?>
                    </select>
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
                    <input  class="value-align value-width easyui-validatebox" data-options="validType:'www'" type="text" name="CrmCustomerInfo[member_compwebside]" value="<?= $model['member_compwebside'] ?>" maxlength="50" id="member_compwebside" placeholder="请输入:www.xxxxxxxx.com">
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
                <input style="width: 120px;" class="value-align easyui-validatebox Onlynum" data-options="validType:'int'" type="text" name="CrmCustomerInfo[member_compsum]" value="<?=$model['member_compsum']?(int)$model['member_compsum']:"" ?>" maxlength="15">
                        <?=Html::dropDownList("CrmCustomerInfo[compsum_cur]",$model['compsum_cur']?$model['compsum_cur']:100091,array_combine(array_column($downList['tradeCurrency'],"bsp_id"),array_column($downList['tradeCurrency'],"bsp_svalue")),["style"=>"width:75px"])?>
            </span>
                </div>
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
                        <label class="label-width label-align" for="">登记证名称：</label>
                        <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_regname][]" maxlength="20">
                    </div>
                    <div class="inline-block">
                        <label class="label-width label-align" for="">登记证号码：</label>
                        <input class="value-width value-align easyui-validatebox" data-options="validType:'regCard'"  type="text" name="CrmCustomerInfo[cust_regnumber][]" maxlength="50">
                    </div>
                    <a class="icon-plus" onclick="add_reg()"> 添加</a>
                    <a class="icon-minus" onclick="del_reg()"> 删除</a>
                </div>
            <?php } ?>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align vertical-top">经营范围说明：</label>
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
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align" for="">发票抬头：</label>
                <input style="width:548px;" class="value-width value-align invoice_title" type="text" name="CrmCustomerInfo[invoice_title]"
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

                <input style="width:548px;margin-left: 144px;" class="mt-5" type="text" name="CrmCustomerInfo[invoice_title_address]"
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

                <input style="width:548px;margin-left: 144px;" class="mt-5" type="text" name="CrmCustomerInfo[invoice_mail_address]"
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

                <input style="width:548px;margin-left: 144px;" class="mt-5" type="text" name="CrmCustomerInfo[cust_headquarters_address]"
                       value="<?= $model['cust_headquarters_address'] ?>" maxlength="50"  placeholder="最多输入50个字">
            </div>
        </div>
        <div class="text-center mb-10">
            <button type="submit" class="button-blue-big companySub">确认</button>
            <button type="button" class="button-white-big" onclick="close_select()">取消</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $(".companySub").click(function () {
        return ajaxSubmitForm($("#customer-company"));
    });
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
    $(function(){
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
        $('.disName,.disName1,.disName2,.disName3').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select,$url,"select");
        });

        $('.ismember_y').click(function(){
//            console.log($('.ismember').val());
            $('.memberType').val('100070');
            $('.memberType').val() && $('.member-type').text($('.memberType :selected').text());

        })
        $('.ismember_n').click(function(){
//            console.log($('.ismember').val());
            $('.memberType').val('');
            $('.member-type').text("");
        })
    })

</script>
