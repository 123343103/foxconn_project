<?php
/**
 * Date: 2017/2/13
 * Time: 上午 09:33
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JeDateAsset;

JeDateAsset::register($this);
foreach ($downList['tradeCurrency'] as $key => $val) {
    if ($val["bsp_svalue"] == "RMB") {
        $rmbId = $val["bsp_id"];
    }
}
?>
<style>
    .label-width {
        width: 80px;
    }

    .value-width {
        width: 200px !important;
    }

    .value-m {
        width: 152px;
    }

    .value-s {
        width: 120px;
    }

    .margin {
        margin-left: 120px;
    }

    .ml-20 {
        margin-left: 20px;
    }
</style>
<div class="content">
    <h2 class="head-first">
        <?= Html::encode($this->title) ?>
        <?= !empty($model['cust_filernumber']) ? '<span class="head-code">编号:' . $model['cust_filernumber'] . '</span>' : '' ?>
    </h2>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <h2 class="head-second">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">客户基本信息</a>
    </h2>
    <div class="ml-20 mb-10">
        <div class="mb-10">
            <label class="label-width qlabel-align vertical-top">公司名称</label><label>：</label>
            <label
                class="value-width qvalue-align vertical-top"><?= $model['cust_sname'] ?></label>
            <label class="label-width qlabel-align vertical-top margin">公司简称</label><label>：</label>
            <label class="value-width qvalue-align vertical-top"><?= $model['cust_shortname'] ?></label><br/>

        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align">公司电话</label><label>：</label>
            <input class="value-width qvalue-align easyui-validatebox IsTel" type="text"
                   name="CrmCustomerInfo[cust_tel1]" data-options="validType:'telphone'" placeholder="请输入 xxx-xxxxxxxx"
                   value="<?= $model['cust_tel1'] ?>"
                   maxlength="15">
            <label class="label-width qlabel-align margin">邮编</label><label>：</label>
            <input class="value-width qvalue-align easyui-validatebox Onlynum" data-options="validType:'postcode'"
                   type="text"
                   name="CrmCustomerInfo[member_compzipcode]" value="<?= $model['member_compzipcode'] ?>" maxlength="6"
                   id="member_compzipcode" placeholder="请输入 xxxxxx">


        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align"><span class="red">*</span>联系人</label><label>：</label>
            <input class="value-width qvalue-align easyui-validatebox" data-options="required:'true'" type="text"
                   name="CrmCustomerInfo[cust_contacts]" value="<?= $model['cust_contacts'] ?>" maxlength="20">
            <label class="label-width qlabel-align margin">部门</label><label>：</label>
            <input class="value-width qvalue-align" type="text" name="CrmCustomerInfo[cust_department]"
                   value="<?= $model['cust_department'] ?>" maxlength="15" id="cust_department">
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align">职位</label><label>：</label>
            <input class="value-width qvalue-align" type="text" name="CrmCustomerInfo[cust_position]"
                   value="<?= $model['cust_position'] ?>" maxlength="15">
            <label class="label-width qlabel-align margin" for="">职位职能</label><label>：</label>
            <select class="value-width qvalue-align" name="CrmCustomerInfo[cust_function]"
                    class="value-width qvalue-align">
                <option value="">请选择...</option>
                <?php foreach ($downList['custFunction'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['cust_function'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align"><span class="red">*</span>联系方式</label><label>：</label>
            <input class="value-width qvalue-align easyui-validatebox add-require"
                   data-options="required:true,validType:'mobile'" placeholder="请输入 136xxxxxxxx"
                   type="text" name="CrmCustomerInfo[cust_tel2]" value="<?= $model['cust_tel2'] ?>">
            <label class="label-width qlabel-align margin"><span class="red">*</span>邮箱</label><label>：</label>
            <input class="value-width qvalue-align easyui-validatebox add-require"
                   data-options="required:'true',validType:'email'" placeholder="请输入 xxx@xxx.com"
                   type="text" name="CrmCustomerInfo[cust_email]" value="<?= $model['cust_email'] ?>" maxlength="50">
        </div>
        <?php if (isset($model['member_regweb'])) { ?>
            <div class="mb-10">
                <label class="label-width qlabel-align vertical-top">注册网站</label><label>：</label>
                <label class="value-width qvalue-align vertical-top add-require" id="member_regweb">
                    <?php foreach ($downList['regWeb'] as $key => $val) { ?>
                        <?= $model['member_regweb'] == $val['bsp_id'] ? $val['bsp_svalue'] : null; ?>
                    <?php } ?></label>
                <label class="label-width qlabel-align vertical-top margin member_name">用户名</label><label>：</label>
                <label class="value-width qvalue-align vertical-top  add-require"
                       id="member_name"><?= $model['member_name'] ?></label>

            </div>
            <div class="mb-10 member_regtime">
                <label class="label-width qlabel-align">注册时间</label><label>：</label>
                <span class="value-width qvalue-align  add-require"
                      id="member_regtime"><?= $model['member_regtime'] ? $model['member_regtime'] : '' ?></span>
            </div>
        <?php } else { ?>
            <div class="mb-10 ">
                <label class="label-width qlabel-align">注册网站</label><label>：</label>
                <select class="value-width qvalue-align" name="CrmCustomerInfo[member_regweb]" id="member_regweb">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['regWeb'] as $key => $val) { ?>
                        <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
                <label class="label-width qlabel-align margin hiden member_name"><span
                        class="red">*</span>用户名</label><label class="hiden member_name">：</label>
                <input class="value-width qvalue-align easyui-validatebox add-require hiden member_name"
                       data-options="required:false" type="text" name="CrmCustomerInfo[member_name]"
                       value="<?= $model['member_name'] ?>" maxlength="20" id="member_name">
            </div>
            <div class="mb-10 hiden member_regtime ">
                <label class="label-width qlabel-align"><span class="red">*</span>注册时间</label><label>：</label>
<!--                <input class="value-width qvalue-align select-date easyui-validatebox" type="text"-->
<!--                       data-options="required:false" name="CrmCustomerInfo[member_regtime]" id="member_regtime"-->
<!--                       value="--><?//= $model['member_regtime'] ? $model['member_regtime'] : '' ?><!--" onfocus="this.blur();">-->
                <input class="value-width qvalue-align Wdate" type="text" name="CrmCustomerInfo[member_regtime]"
                       value="<?= $model['member_regtime'] ? $model['member_regtime'] : '' ?>" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd' })"  onfocus="this.blur();">
            </div>
        <?php } ?>
        <div class="mb-10">
            <label class="label-width qlabel-align"><span class="red">*</span>详细地址</label><label>：</label>
            <select class="value-m qvalue-align disName easyui-validatebox" data-options="required:'true'"
                    id="disName_1">
                <option value="">国</option>
                <?php foreach ($downList['country'] as $key => $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select class="value-m qvalue-align disName easyui-validatebox" data-options="required:'true'"
                    id="disName_2">
                <option value="">省</option>
                <?php if (!empty($districtAll2)) { ?>
                    <?php foreach ($districtAll2['twoLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="value-m qvalue-align disName easyui-validatebox" data-options="required:'true'"
                    id="disName_3">
                <option value="">市</option>
                <?php if (!empty($districtAll2)) { ?>
                    <?php foreach ($districtAll2['threeLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="value-m qvalue-align disName easyui-validatebox" id="disName_4"
                    data-options="required:'true'"
                    name="CrmCustomerInfo[cust_district_2]">
                <option value="">县/区</option>
                <?php if (!empty($districtAll2)) { ?>
                    <?php foreach ($districtAll2['fourLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select><br/>
            <input style="width:620px;margin-left: 94px;margin-top: 5px;" type="text"
                   name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>"
                   maxlength="100" placeholder="公司详细地址">
        </div>
    </div>
    <h2 class="head-second">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">客户详细信息</a>
    </h2>
    <div class="ml-20 mb-10">

        <div class="mb-10">
            <label class="label-width qlabel-align">法人代表</label><label>：</label>
            <input class="value-width qvalue-align" type="text" name="CrmCustomerInfo[cust_inchargeperson]"
                   value="<?= $model['cust_inchargeperson'] ?>" maxlength="15">
            <label class="label-width qlabel-align margin">注册时间</label><label>：</label>
            <input class="value-width qvalue-align Wdate" type="text" name="CrmCustomerInfo[cust_regdate]" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd' })"
                   value="<?= $model['cust_regdate'] ?>"  onfocus="this.blur();">
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align">注册货币</label><label>：</label>
            <select class="value-width qvalue-align" name="CrmCustomerInfo[member_regcurr]">
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= ((empty($model['member_regcurr']) ? $rmbId : $model['member_regcurr']) == $val['bsp_id']) ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="label-width qlabel-align margin">注册资金</label><label>：</label>
            <input class="value-width qvalue-align Onlynum easyui-validatebox" type="text"
                   name="CrmCustomerInfo[cust_regfunds]" data-options="validType:'int'"
                   value="<?= empty($model['cust_regfunds']) ? "" : $model['cust_regfunds'] ?>"
                   maxlength="15">
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align">公司类型</label><label>：</label>
            <select class="value-width qvalue-align" name="CrmCustomerInfo[cust_compvirtue]">
                <option value="">请选择...</option>
                <?php foreach ($downList['property'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['cust_compvirtue'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="label-width qlabel-align margin">客户来源</label><label>：</label>
            <select class="value-width qvalue-align" name="CrmCustomerInfo[member_source]">
                <option value="">请选择...</option>
                <?php foreach ($downList['customerSource'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_source'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align">经营范围</label><label>：</label>
            <select class="value-width qvalue-align" name="CrmCustomerInfo[cust_businesstype]">
                <option value="">请选择...</option>
                <?php foreach ($downList['businessModel'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['cust_businesstype'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="label-width qlabel-align margin">交易币种</label><label>：</label>
            <select class="value-width qvalue-align" name="CrmCustomerInfo[member_curr]">
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= ((empty($model['member_curr']) ? $rmbId : $model['member_curr']) == $val['bsp_id']) ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align">年营业额</label><label>：</label>
            <input class="value-s qvalue-align Onlynum easyui-validatebox" type="text"
                   name="CrmCustomerInfo[member_compsum]" data-options="validType:'int'"
                   value="<?= empty($model['member_compsum']) ? "" : $model['member_compsum'] ?>"
                   maxlength="15">
            <select style="width: 76px" name="CrmCustomerInfo[compsum_cur]">
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= ((empty($model['compsum_cur']) ? $rmbId : $model['compsum_cur']) == $val['bsp_id']) ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="label-width qlabel-align margin">年采购额</label><label>：</label>
            <input class="value-s qvalue-align Onlynum easyui-validatebox" type="text"
                   name="CrmCustomerInfo[cust_pruchaseqty]" data-options="validType:'int'"
                   value="<?= empty($model['cust_pruchaseqty']) ? "" : $model['cust_pruchaseqty'] ?>"
                   maxlength="15">
            <select style="width: 76px" name="CrmCustomerInfo[pruchaseqty_cur]">
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= ((empty($model['pruchaseqty_cur']) ? $rmbId : $model['pruchaseqty_cur']) == $val['bsp_id']) ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align">员工人数</label><label>：</label>
            <input class="value-width qvalue-align Onlynum easyui-validatebox" type="text"
                   name="CrmCustomerInfo[cust_personqty]" data-options="validType:'int'"
                   value="<?= empty($model['cust_personqty']) ? "" : $model['cust_personqty'] ?>"
                   maxlength="15">
            <label class="label-width qlabel-align margin">发票需求</label><label>：</label>
            <select class="value-width qvalue-align" name="CrmCustomerInfo[member_compreq]">
                <option value="">请选择...</option>
                <?php foreach ($downList['invoice'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_compreq'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <!--            <input class="value-width qvalue-align" type="text" name="CrmCustomerInfo[member_compreq]" value="-->
            <? //= $model['member_compreq'] ?><!--" maxlength="15">-->
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align">潜在需求</label><label>：</label>
            <select class="value-width qvalue-align" name="CrmCustomerInfo[member_reqflag]">
                <option value="">请选择...</option>
                <?php foreach ($downList['latentDemand'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_reqflag'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="label-width qlabel-align margin">需求类目</label><label>：</label>
            <select class="value-width qvalue-align" name="CrmCustomerInfo[member_reqitemclass]">
                <option value="">请选择...</option>
                <?php foreach ($downList['productType'] as $key => $val) { ?>
                    <option
                        value="<?= $val['catg_id'] ?>"<?= $model['member_reqitemclass'] == $val['catg_id'] ? "selected" : null; ?>><?= $val['catg_name'] ?></option>
                <?php } ?>
            </select>

        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align">需求类别</label><label>：</label>
            <input name="CrmCustomerInfo[member_reqdesription]" class="value-width qvalue-align" type="text"
                   value="<?= $model['member_reqdesription'] ?>" maxlength="20">
            <label class="label-width qlabel-align margin">主要市场</label><label>：</label>
            <input class="value-width qvalue-align" type="text" name="CrmCustomerInfo[member_marketing]"
                   value="<?= $model['member_marketing'] ?>" maxlength="50">
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align">主要客户</label><label>：</label>
            <input class="value-width qvalue-align" type="text" name="CrmCustomerInfo[member_compcust]"
                   value="<?= $model['member_compcust'] ?>" maxlength="50">
            <label class="label-width qlabel-align margin">主页</label><label>：</label>
            <input class="value-width qvalue-align easyui-validatebox" type="text"
                   name="CrmCustomerInfo[member_compwebside]"
                   placeholder="请输入www.xxx.xxx" data-options="validType:'www'"
                   value="<?= $model['member_compwebside'] ?>" maxlength="50">
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align vertical-top">范围说明</label><label class="vertical-top">：</label>
            <textarea class="width-800 easyui-validatebox" style="width: 620px"
                      name="CrmCustomerInfo[member_businessarea]" rows="3" data-options="validType:'maxLength[200]'"
                      maxlength="200" placeholder="最多输入200个字"><?= $model['member_businessarea'] ?></textarea>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align vertical-top">备注</label><label class="vertical-top">：</label>
            <textarea class="width-800 easyui-validatebox" style="width: 620px" name="CrmCustomerInfo[member_remark]"
                      rows="3" data-options="validType:'maxLength[200]'"
                      maxlength="200" placeholder="最多输入200个字"><?= $model['member_remark'] ?></textarea>
        </div>
    </div>
    <h2 class="head-second">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">其他联系人信息</a>
    </h2>
    <div class="mt-20 mb-10">
        <p class="mb-10 ml-20">客户其他联系人:</p>
        <table class="ml-20 table" style="width: 98%">
            <thead>
            <th>序号</th>
            <th>姓名</th>
            <th>职位</th>
            <th>电子邮箱</th>
            <th>电话(手机)</th>
            <th>备注</th>
            <th>操作</th>
            </thead>
            <tbody>
            <?php foreach ($model["contacts"] as $k => $contact) { ?>
                <tr align="center">
                    <td width="150"><?= $k + 1 ?></td>
                    <td width="150"><input name="CrmCustomerPersion[ccper_name][]"
                                           class="width-150 text-center easyui-validatebox" data-options="required:true"
                                           type="text" value="<?= $contact['ccper_name'] ?>" maxlength="15"></td>
                    <td width="150"><input name="CrmCustomerPersion[ccper_post][]"
                                           class="width-100 text-center easyui-validatebox"
                                           type="text" value="<?= $contact['ccper_post'] ?>" maxlength="15"></td>
                    <td width="150"><input name="CrmCustomerPersion[ccper_mail][]"
                                           class="width-150 text-center easyui-validatebox"
                                           data-options="required:true,validType:'email'" type="text"
                                           value="<?= $contact['ccper_mail'] ?>" maxlength="20"></td>
                    <td width="150"><input name="CrmCustomerPersion[ccper_mobile][]"
                                           class="width-150 text-center easyui-validatebox"
                                           data-options="required:true,validType:'tel_mobile'" type="text"
                                           value="<?= $contact['ccper_mobile'] ?>" maxlength="20"></td>
                    <td width="150"><input name="CrmCustomerPersion[ccper_remark][]" class="width-150 text-center"
                                           type="text" value="<?= $contact['ccper_remark'] ?>" maxlength="180"></td>
                    <td width="150">
                        <a class="icon-trash mr-20"></a>
                        <a class="icon-refresh"></a></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7" class="text-left">
                    <a class="ml-20 icon-plus"> 添加行</a>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="text-center">
        <button type="submit" class="button-blue-big">确定</button>
        <button class="button-white-big" onclick="history.go(-1);" type="button">返回</button>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $(function () {
        ajaxSubmitForm($("#add-form"));
        //输入控制
        $(".IsTel").telphone();
        $(".Onlynum").numbervalid();

        $('.disName').on("change", function () {
            var $select = $(this);
            //console.log($select);
            getNextDistrict($select);

            var distArr = [];
            $(this).prevAll(".disName").andSelf().each(function () {
                distArr.push($(this).children(":selected").html());
            });
            $("#cur-addr-input").val(distArr.join());
            $("#cur-addr-text").text(distArr.join());
        });

        $(".head-second").next("div:eq(0)").css("display", "block");
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-right");
            $(this).prev().toggleClass("icon-caret-down");
        });

        //遞歸清除級聯選項
        function clearOption($select) {
            if ($select == null) {
                $select = $("#disName_1")
            }
            $tagNmae = $select.next().prop("tagName");
            if ($select.next().length != 0 && $tagNmae == 'SELECT') {
                $select.next().html('<option value=>请选择</option>');
                clearOption($select.next());
            }
        }

        function getNextDistrict($select) {
            var id = $select.val();
            if (id == "") {
                clearOption($select);
                return;
            }
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"id": id},
                url: "<?=Url::to(['/ptdt/firm/get-district']) ?>",
                success: function (data) {
//                        console.log(data);
                    var $nextSelect = $select.next("select");
//                        console.log();
                    clearOption($nextSelect);
                    $nextSelect.html('<option value>请选择</option>')
                    if ($nextSelect.length != 0)
                        for (var x in data) {
                            $nextSelect.append('<option value="' + data[x].district_id + '" >' + data[x].district_name + '</option>')
                        }
                }

            })
        }


        $("table").click(function (event) {
            if ($(event.target).hasClass("icon-plus")) {
                $(event.target).parents("table").append('<tr align="center">\
                    <td width="150"></td>\
                    <td width="150"><input name="CrmCustomerPersion[ccper_name][]" class="width-150 text-center easyui-validatebox" data-options="required:true" type="text" maxlength="15"></td>\
                    <td width="150"><input name="CrmCustomerPersion[ccper_post][]" class="width-100 text-center easyui-validatebox"  type="text" maxlength="15"></td>\
                    <td width="150"><input name="CrmCustomerPersion[ccper_mail][]" class="width-150 text-center easyui-validatebox" data-options="required:true,validType:\'email\'" type="text" maxlength="20"></td>\
                    <td width="150"><input name="CrmCustomerPersion[ccper_mobile][]" class="width-150 text-center easyui-validatebox IsTel" data-options="required:true,validType:\'tel_mobile\'" type="text" maxlength="20"></td>\
                    <td width="150"><input name="CrmCustomerPersion[ccper_remark][]" class="width-150 text-center" type="text" maxlength="180"></td>\
                    <td width="150">\
                    <a class="icon-trash mr-20"></a>\
                    <a class="icon-refresh"></a></td>\
                </tr>');
                $.parser.parse("tr");
            }
            if ($(event.target).hasClass("icon-trash")) {
                $(event.target).parents("tr").remove();
            }
            if ($(event.target).hasClass("icon-refresh")) {
                $(event.target).parents("tr").find(":text").val("");
            }
            for (var x = 0; x < $("tbody tr").size(); x++) {
                $("tbody tr").eq(x).find("td:first").text(x + 1);
            }
            //输入控制
            $(".IsTel").telphone();
            $(".Onlynum").numbervalid();
        });
        $("#member_regweb").on("change", function () {
            if ($(this).val() == "") {
                $(".member_name").hide();
                $(".member_regtime").hide();
                $("#member_name").val("");
                $("#member_regtime").val("");
                $("#member_name").validatebox({
                    required: false
                });
                $("#member_regtime").validatebox({
                    required: false
                });
            } else {
                $(".member_name").show();
                $(".member_regtime").show();
                $("#member_name").validatebox({
                    required: true
                });
                $("#member_regtime").validatebox({
                    required: true
                });
            }
        })
    });
</script>
