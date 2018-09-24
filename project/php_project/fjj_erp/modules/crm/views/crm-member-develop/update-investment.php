<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

?>
<h1 class="head-first">
    修改客户信息
</h1>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <h2 class="head-second">客户基本信息</h2>
    <div class="mb-10">
        <label class="width-80"><span class="red">*</span>公司名称</label>
        <input name="CrmCustomerInfo[cust_sname]" class="width-200  add-require easyui-validatebox"
               readonly="readonly"
               data-options="required:true,validType:'unique',delay:10000"
               data-act="<?= Url::to(['/crm/crm-member/validate']) ?>" data-attr="cust_sname"
               data-id="<?= $model['cust_id']; ?>" type="text" value="<?= $model['cust_sname'] ?>" maxlength="50">
        <label class="width-140">公司简称</label>
        <input class="width-200 easyui-validatebox" type="text" name="CrmCustomerInfo[cust_shortname]"
               value="<?= $model['cust_shortname'] ?>" maxlength="50">
    </div>
    <div class="mb-10">
        <label class="width-80">公司电话</label>
        <input class="width-200 easyui-validatebox" data-options="validType:'int'" type="text"
               name="CrmCustomerInfo[cust_tel1]" value="<?= $model['cust_tel1'] ?>">
        <label class="width-140">邮编</label>
        <input class="width-200 easyui-validatetbox" data-options="valid Type:'int'" type="text"
               name="CrmCustomerInfo[member_compzipcode]" value="<?= $model['member_compzipcode'] ?>">
    </div>
    <div class="mb-10  overflow-auto">
        <label class="width-80 float-left"><span class="red">*</span>详细地址</label>
        <div class="width-530 float-left">
        <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_1">
            <option value="">请选择...</option>
            <?php foreach ($downList['country'] as $key => $val) { ?>
                <option
                    value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
            <?php } ?>
        </select>
        <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_2">
            <option value="">请选择...</option>
            <?php if (!empty($districtAll)) { ?>
                <?php foreach ($districtAll['twoLevel'] as $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            <?php } ?>
        </select>
        <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_3">
            <option value="">请选择...</option>
            <?php if (!empty($districtAll)) { ?>
                <?php foreach ($districtAll['threeLevel'] as $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            <?php } ?>
        </select>
        <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_4"
                name="CrmCustomerInfo[cust_district_2]">
            <option value="">请选择...</option>
            <?php if (!empty($districtAll)) { ?>
                <?php foreach ($districtAll['fourLevel'] as $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            <?php } ?>
        </select>
            </div>
        <p style="padding-left: 80px;">
            <input class="width-410 easyui-validatebox" data-options="required:'true'" type="text" placeholder="公司详细地址"
                   name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>" maxlength="100">
        </p>
    </div>
    <div class="mb-10">
        <label class="width-80"><span class="red">*</span>联系人</label>
        <input class="width-200 easyui-validatebox" data-options="required:'true'" type="text"
               name="CrmCustomerInfo[cust_contacts]" value="<?= $model['cust_contacts'] ?>" maxlength="20">
        <label class="width-140">部门</label>
        <input class="width-200 easyui-validatebox" type="text" value="<?= $model['cust_department'] ?>"
               name="CrmCustomerInfo[cust_department]" maxlength="20">
    </div>
    <div class="mb-10">
        <label class="width-80">职位</label>
        <input class="width-200" type="text" name="CrmCustomerInfo[cust_position]"
               value="<?= $model['cust_position'] ?>">
        <label class="width-140">职位职能</label>
        <select class="width-200" name="CrmCustomerInfo[cust_function]">
            <option value="">请选择...</option>
            <?php foreach ($downList['custFunction'] as $key => $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>"<?= $model['cust_function'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="width-80"><span class="red">*</span>联系方式</label>
        <input class="width-200 easyui-validatebox" data-options="required:'true',validType:'mobile'" type="text"
               name="CrmCustomerInfo[cust_tel2]" value="<?= $model['cust_tel2'] ?>">
        <label class="width-140"><span class="red">*</span>邮箱</label>
        <input class="width-200 easyui-validatebox" data-options="required:'true',validType:'email'" type="text"
               name="CrmCustomerInfo[cust_email]" value="<?= $model['cust_email'] ?>">
    </div>
    <div class="mb-10">
        <label class="width-80"><span class="red">*</span>用户名</label>
        <input class="width-200 easyui-validatebox" type="text" name="CrmCustomerInfo[member_name]"
               value="<?= $model['member_name'] ?>"
               maxlength="20" id="member_name" data-options="required:'true'">
        <label class="width-140"><span class="red">*</span>注册网站</label>
        <select class="width-200 easyui-validatebox" name="CrmCustomerInfo[member_regweb]" id="member_regweb"
                data-options="required:'true'">
            <option value="">请选择...</option>
            <?php foreach ($downList['regWeb'] as $key => $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>"<?= $model['member_regweb'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="width-80"><span class="red">*</span>注册时间</label>
        <input class="width-200 select-date  easyui-validatebox" name="CrmCustomerInfo[member_regtime]"
               readonly="readonly"
               id="member_regtime" data-options="required:true"
               value="<?= $member['member_regtime'] ?>">
    </div>
    <h2 class="head-three">
        <a href="javascript:void(0)" class="ml-10">
            客户详细信息
            <i class="icon-caret-down float-right font-arrows"></i>
        </a>
    </h2>
    <div class="ml-10">
        <div class="space-20"></div>
        <div class="mb-10">
            <label class="width-80">法人代表</label>
            <input class="width-200" type="text" name="CrmCustomerInfo[cust_inchargeperson]"
                   value="<?= $model['cust_inchargeperson'] ?>" maxlength="20">
            <label class="width-140">注册时间</label>
            <input class="width-200 select-date" type="text" name="CrmCustomerInfo[cust_regdate]"
                   value="<?= $model['cust_regdate'] ?>" readonly="readonly">
        </div>
        <div class="mb-10">
            <label class="width-80">注册货币</label>
            <select class="width-200" name="CrmCustomerInfo[member_regcurr]">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_regcurr'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-140">注册资金</label>
            <input class="width-200" type="text" name="CrmCustomerInfo[cust_regfunds]"
                   value="<?= $model['cust_regfunds'] ?>" maxlength="20">
        </div>
        <div class="mb-10">
            <label class="width-80">公司类型</label>
            <?= Html::dropDownList("CrmCustomerInfo[cust_compvirtue]", $model['cust_compvirtue'], array_combine(array_column($downList['property'], 'bsp_id'), array_column($downList['property'], 'bsp_svalue')), ["prompt" => "请选择", "class" => "width-200", "maxlength" => 200]) ?>
            <label class="width-140">客户来源</label>
            <?= Html::dropDownList("CrmCustomerInfo[member_source]", $model['member_source'], array_combine(array_column($downList['customerSource'], 'bsp_id'), array_column($downList['customerSource'], 'bsp_svalue')), ["prompt" => "请选择", "class" => "width-200", "maxlength" => 200]) ?>
        </div>
        <div class="mb-10">
            <label class="width-80">经营范围</label>
            <select class="width-200 cust_businesstype" name="CrmCustomerInfo[cust_businesstype]">
                <option value="">请选择...</option>
                <?php foreach ($downList['managementType'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $member['cust_businesstype'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-140">交易币种</label>
            <?= Html::dropDownList("CrmCustomerInfo[member_curr]", $model['member_curr'], array_combine(array_column($downList['tradeCurrency'], 'bsp_id'), array_column($downList['tradeCurrency'], 'bsp_svalue')), ["prompt" => "请选择", "class" => "width-200", "maxlength" => 200]) ?>
        </div>
        <div class="mb-10">
            <label class="width-80">年营业额</label>
            <input class="width-140" type="number" name="CrmCustomerInfo[member_compsum]"
                   value="<?= $model['member_compsum'] ?>" maxlength="20">
            <?= Html::dropDownList("CrmCustomerInfo[compsum_cur]", $model['compsum_cur'], array_combine(array_column($downList['tradeCurrency'], 'bsp_id'), array_column($downList['tradeCurrency'], 'bsp_svalue')), ["class" => "width-59", "maxlength" => 200]) ?>
            <label class="width-140">年采购额</label>
            <input class="width-140" type="number" name="CrmCustomerInfo[cust_pruchaseqty]"
                   value="<?= $model['cust_pruchaseqty'] ?>" maxlength="20">
            <?= Html::dropDownList("CrmCustomerInfo[pruchaseqty_cur]", $model['pruchaseqty_cur'], array_combine(array_column($downList['tradeCurrency'], 'bsp_id'), array_column($downList['tradeCurrency'], 'bsp_svalue')), ["class" => "width-59", "maxlength" => 200]) ?>
        </div>
        <div class="mb-10">
            <label class="width-80">员工人数</label>
            <input class="width-200" type="text" name="CrmCustomerInfo[cust_personqty]"
                   value="<?= $model['cust_personqty'] ?>">
            <label class="width-140">发票需求</label>
            <select class="width-200" name="CrmCustomerInfo[member_compreq]" id="member_compreq">
                <option value="">请选择...</option>
                <option
                <?php foreach ($downList['InvoiceNeeds'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_compreq'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="width-80">潜在需求</label>
            <select class="width-200" name="CrmCustomerInfo[member_reqflag]">
                <option value="">请选择...</option>
                <?php foreach ($downList['latentDemand'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $member['member_reqflag'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-140">需求类目</label>
            <?= Html::dropDownList("CrmCustomerInfo[member_reqitemclass]", $model['member_reqitemclass'], array_combine(array_column($downList['productType'], 'category_id'), array_column($downList['productType'], 'category_sname')), ["prompt" => "请选择", "class" => "width-200", "maxlength" => 200]) ?>
        </div>
        <div class="mb-10">
            <label class="width-80">需求类别</label>
            <input class="width-200 easyui-validatebox" name="CrmCustomerInfo[member_reqdesription]"
                   value="<?= $model['member_reqdesription'] ?>" type="text" maxlength="20">
            <label class="width-140">主要市场</label>
            <input class="width-200" type="text" name="CrmCustomerInfo[member_marketing]"
                   value="<?= $model['member_marketing'] ?>" maxlength="200">
        </div>
        <div class="mb-10">
            <label class="width-80">主要客户</label>
            <input style="width:200px;" type="text" name="CrmCustomerInfo[member_compcust]"
                   value="<?= $model['member_compcust'] ?>" maxlength="200">
            <label class="width-140">主页</label>
            <input class="width-200 count easyui-validatebox" type="text" name="CrmCustomerInfo[member_compwebside]" maxlength="200"
                   id="compweb" data-options="validType:'www'"
                   value="<?= $model['member_compwebside'] ?>">
            <span class="red" id="compweb1"></span>
        </div>
        <div class="mb-10">
            <label class="width-80 vertical-top">经营范围说明</label>
            <textarea style="width:549px;height: 50px;" name="CrmCustomerInfo[member_businessarea]"
                      maxlength="200" id="memare"><?= $model['member_businessarea'] ?></textarea>
            <span class="red" id="memare1"></span>
        </div>
        <div class="mb-10">
            <label class="width-80  vertical-top">备注</label>
            <textarea style="width:549px;height: 50px;" name="CrmCustomerInfo[member_remark]"
                      maxlength="200" id="memark"><?= $model['member_remark'] ?></textarea>
            <div style="vertical-align:top;display:inline;line-height: 50px">
                <span class="red" id="memark1"></span>
            </div>

        </div>
    </div>
    <h2 class="head-three">
        <a href="javascript:void(0)" class="ml-10">
            其他联系人信息
            <i class="icon-caret-down float-right font-arrows"></i>
        </a>
    </h2>
    <div class="ml-10">
        <div class="mt-20 mb-10">
            <p class="mb-10">客户其他联系人</p>
            <table class="table">
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
                <?php foreach ($model["contactPersons"] as $k => $contact) { ?>
                    <tr align="center">
                        <td width="50"><?= $k + 1 ?></td>
                        <td width="100"><input name="CrmCustomerPersion[ccper_name][]"
                                               class="width-100 text-center easyui-validatebox"
                                               data-options="required:true"
                                               type="text" value="<?= $contact['ccper_name'] ?>" maxlength="15"></td>
                        <td width="100"><input name="CrmCustomerPersion[ccper_post][]"
                                               class="width-100 text-center easyui-validatebox"
                                               data-options="required:true"
                                               type="text" value="<?= $contact['ccper_post'] ?>" maxlength="15"></td>
                        <td width="100"><input name="CrmCustomerPersion[ccper_mail][]"
                                               class="width-100 text-center easyui-validatebox"
                                               data-options="required:true,validType:'email'" type="text"
                                               value="<?= $contact['ccper_mail'] ?>" maxlength="20"></td>
                        <td width="100"><input name="CrmCustomerPersion[ccper_mobile][]"
                                               class="width-100 text-center easyui-validatebox"
                                               data-options="required:true,validType:'mobile'" type="text"
                                               value="<?= $contact['ccper_mobile'] ?>" maxlength="20"></td>
                        <td width="100"><input name="CrmCustomerPersion[ccper_remark][]" class="width-100 text-center"
                                               type="text" value="<?= $contact['ccper_remark'] ?>" maxlength="180"></td>
                        <td width="100">
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
    </div>
    <?php ActiveForm::end() ?>
</div>
<div class="space-20"></div>
<div class="text-center">
    <?php if (!empty($type)) { ?>
        <button type="button" class="button-blue-big but">确定</button>
    <?php } else { ?>
        <button type="submit" class="button-blue-big">确定</button>
    <?php } ?>
    <button class="button-white-big" onclick="close_select()" type="button">取消</button>
</div>
<div class="space-20"></div>
<script>
    $(function () {
//        isMember();
//        $('#ismember').on("change", function(){
//            isMember();
//        });
        /*div 收缩展开*/
        $(".head-three>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).find('i').toggleClass("icon-caret-left");
            $(this).find('i').toggleClass("icon-caret-down");
        });
        <?php if($type == 1){ ?>
        $(".but").on('click', function () {
            if (!$('#add-form').form('validate')) {
                return false;
            }
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: $("#add-form").serialize(),
                url: "<?= \yii\helpers\Url::to(['update', 'id' => $model['cust_id'], 'type' => $type, 'ctype' => $ctype]) ?>",
                success: function (data) {
                    if (data.flag == 1) {
                        layer.alert("修改成功!", {
                            icon: 1, end: function () {
                                parent.window.location.reload();
                            }
                        });
                    }
                }
            })
        })
        <?php }else if($type == 2){ ?>
        $(".but").on('click', function () {
            if (!$('#add-form').form('validate')) {
                return false;
            }
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: $("#add-form").serialize(),
                url: "<?= \yii\helpers\Url::to(['create', 'type' => $type]) ?>",
                success: function (data) {
                    if (data.flag == 1) {
                        layer.alert("添加成功!", {
                            icon: 1, end: function () {
                                parent.window.location.reload();
                            }
                        });
                    }
                }
            })
        })
        <?php }else{ ?>
        ajaxSubmitForm($("#add-form"));
        <?php } ?>
        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select, $url, "select");
        });

        $("table").click(function (event) {
            if ($(event.target).hasClass("icon-plus")) {
                $(event.target).parents("table").append('<tr align="center">\
                    <td width="50"></td>\
                    <td width="100"><input name="CrmCustomerPersion[ccper_name][]" class="width-100 text-center easyui-validatebox" data-options="required:true" type="text" maxlength="15"></td>\
                    <td width="100"><input name="CrmCustomerPersion[ccper_post][]" class="width-100 text-center easyui-validatebox" data-options="required:true" type="text" maxlength="15"></td>\
                    <td width="100"><input name="CrmCustomerPersion[ccper_mail][]" class="width-100 text-center easyui-validatebox" data-options="required:true,validType:\'email\'" type="text" maxlength="20"></td>\
                    <td width="100"><input name="CrmCustomerPersion[ccper_mobile][]" class="width-100 text-center easyui-validatebox" data-options="required:true,validType:\'mobile\'" type="text" maxlength="20"></td>\
                    <td width="100"><input name="CrmCustomerPersion[ccper_remark][]" class="width-100 text-center" type="text" maxlength="180"></td>\
                    <td width="100">\
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
        });
        $.fn.extend({
            wordCount: function (maxLength, wordWrapper) {
                var self = this;
                $(self).attr("maxlength", maxLength);
                showWordCount();
                $(this).on("input propertychange", showWordCount);
                function showWordCount() {
                    curLength = $(self).val().length;
                    wordWrapper.text(curLength + "/" + maxLength);
                }
            }
        })
        $(function () {
            $("#compweb").wordCount(200, $("#compweb1"));
            $("#memare").wordCount(200, $("#memare1"));
            $("#memark").wordCount(200, $("#memark1"));
        })
    });
    //    //是否会员
    //    function isMember(){
    //        var member = $("#member_type,#member_regtime,#member_name,#member_regweb");
    //        if($("#ismember").val()=='1'){
    //            member.validatebox({
    //                required:true
    //            });
    //            member.removeAttr("disabled");
    //        }else {
    //            member.val('');
    //            member.validatebox({
    //                required:false
    //            });
    //            member.attr("disabled",true);
    //        }
    //    }

</script>
