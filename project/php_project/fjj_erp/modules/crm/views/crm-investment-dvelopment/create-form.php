<?php
/**
 * Date: 2017/2/13
 * Time: 上午 09:33
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title = '新增客户';
?>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>

    <h2 class="head-first">
        <?= Html::encode($this->title) ?>
        <?= !empty($model['cust_filernumber'])?'<span class="head-code">编号:'.$model['cust_filernumber'].'</span>':'' ?>
    </h2>
    <div class="ml-20 mb-20">
        <div class="mb-20">
            <label class="width-80">公司名称</label>
            <input class="width-140 easyui-validatebox" data-options="required:true,validType:'unique',delay:500" data-act="<?=Url::to(['validate'])?>" data-attr="cust_sname" data-id="<?=$model['cust_id'];?>" maxlength="50" type="text" name="CrmCustomerInfo[cust_sname]" value="<?= $model['cust_sname'] ?>">
            <label class="width-100">公司简称</label>
            <input class="width-140 easyui-validatebox" data-options="required:'true'" maxlength="15" type="text" name="CrmCustomerInfo[cust_shortname]" value="<?= $model['cust_shortname'] ?>">
            <label class="width-100">公司电话</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[cust_tel1]" value="<?= $model['cust_tel1'] ?>" maxlength="15">
            <!--            <input class="width-200" type="hidden" name="CrmCustomerInfo[codeType]" value="20">-->
        </div>
        <div class="mb-20">
            <label class="width-80">联系人</label>
            <input class="width-140 easyui-validatebox" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_contacts]" value="<?= $model['cust_contacts'] ?>" maxlength="20">
            <label class="width-100">职位</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[cust_position]" value="<?= $model['cust_position'] ?>" maxlength="15">
            <label class="width-100">手机号码</label>
            <input class="width-140 easyui-validatebox add-require" data-options="required:true,validType:'mobile'" type="text" name="CrmCustomerInfo[cust_tel2]" value="<?= $model['cust_tel2'] ?>">
        </div>
        <div class="mb-20">
            <label class="width-80">邮箱</label>
            <input class="width-140 easyui-validatebox add-require" data-options="required:'true',validType:'email'" type="text" name="CrmCustomerInfo[cust_email]" value="<?= $model['cust_email'] ?>" maxlength="50" >
            <label class="width-100">需求类目</label>
            <select class="width-140" name="CrmCustomerInfo[member_reqitemclass]">
                <option value="">请选择...</option>
                <?php foreach ($downList['productType'] as $key => $val) { ?>
                    <option
                        value="<?= $val['category_id'] ?>"<?= $model['member_reqitemclass']==$val['category_id']?"selected":null; ?>><?= $val['category_sname'] ?></option>
                <?php } ?>
            </select>

            <label class="width-100">需求类别</label>
            <input name="CrmCustomerInfo[member_reqdesription]" class="width-140" type="text" value="<?= $model['member_reqdesription'] ?>" maxlength="20">
        </div>
        <div class="mb-20">
            <label class="width-80">用户名</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[member_name]" value="<?= $model['member_name'] ?>" maxlength="20" id="member_name" >
            <label class="width-100">注册网站</label>
            <select class="width-140" name="CrmCustomerInfo[member_regweb]" id="member_regweb">
                <option value="">请选择...</option>
                <?php foreach ($downList['regWeb'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_regweb']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-100">注册时间</label>
            <input class="width-140 select-date" type="text" name="CrmCustomerInfo[member_regtime]" id="member_regtime" value="<?=$model['member_regtime']?$model['member_regtime']:'' ?>"  onfocus="this.blur();">
        </div>
        <div class="mb-20">
            <label class="width-80"><span class="red">*</span>详细地址</label>
            <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_1">
                <option value="">请选择...</option>
                <?php foreach ($downList['country'] as $key => $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_2" >
                <option value="">请选择...</option>
                <?php if (!empty($districtAll2)) { ?>
                    <?php foreach ($districtAll2['twoLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_3">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll2)) { ?>
                    <?php foreach ($districtAll2['threeLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_4"
                    name="CrmCustomerInfo[cust_district_2]">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll2)) { ?>
                    <?php foreach ($districtAll2['fourLevel'] as $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll2['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <input class="easyui-validatebox width-220" type="text" data-options="required:'true'"
                   name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>"
                   maxlength="100">
        </div>
        <div class="mb-20">
            <label class="width-80">客户来源</label>
            <select class="width-140" name="CrmCustomerInfo[member_source]">
                <option value="">请选择...</option>
                <?php foreach ($downList['customerSource'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_source']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-100">经营模式</label>
            <select class="width-140" name="CrmCustomerInfo[cust_businesstype]">
                <option value="">请选择...</option>
                <?php foreach ($downList['businessModel'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['cust_businesstype']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-100">经营范围</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[member_businessarea]" value="<?= $model['member_businessarea'] ?>" maxlength="200">
        </div>
        <div class="mb-20">
            <label class="width-80">法人代表</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[cust_inchargeperson]" value="<?= $model['cust_inchargeperson'] ?>" maxlength="15">
            <label class="width-100">注册时间</label>
            <input class="width-140 select-date" type="text" name="CrmCustomerInfo[cust_regdate]" value="<?= $model['cust_regdate'] ?>"  onfocus="this.blur();">
            <label class="width-100">注册资金</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[cust_regfunds]" value="<?= $model['cust_regfunds'] ?>" maxlength="15">
        </div>
        <div class="mb-20">
            <label class="width-80">公司类型</label>
            <select class="width-140" name="CrmCustomerInfo[cust_compvirtue]">
                <option value="">请选择...</option>
                <?php foreach ($downList['property'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['cust_compvirtue']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-100">员工人数</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[cust_personqty]" value="<?= $model['cust_personqty'] ?>" maxlength="15">
            <label class="width-100">注册货币</label>
            <select class="width-140" name="CrmCustomerInfo[member_regcurr]">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_regcurr']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-20">
            <label class="width-80">年营业额</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[member_compsum]" value="<?= $model['member_compsum'] ?>" maxlength="15">
            <label class="width-100">交易币种</label>
            <select class="width-140" name="CrmCustomerInfo[member_curr]">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>"<?= $model['member_curr']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-100">邮编</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[member_compzipcode]" value="<?= $model['member_compzipcode'] ?>" maxlength="8">
        </div>
        <div class="mb-20">
            <label class="width-80">年采购额</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[cust_pruchaseqty]" value="<?= $model['cust_pruchaseqty'] ?>" maxlength="15">
            <label class="width-100">发票需求</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[member_compreq]" value="<?= $model['member_compreq'] ?>" maxlength="15">
            <label class="width-100">主要市场</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[member_marketing]" value="<?= $model['member_marketing'] ?>" maxlength="50">
        </div>
        <div class="mb-20">
            <label class="width-80">主页</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[member_compwebside]" value="<?= $model['member_compwebside'] ?>" maxlength="50">
            <label class="width-100">主要客户</label>
            <input class="width-140" type="text" name="CrmCustomerInfo[member_compcust]" value="<?= $model['member_compcust'] ?>" maxlength="50">
        </div>
        <div class="mb-20">
            <label class="width-80 vertical-top">备注</label>
            <textarea style="width:635px" name="CrmCustomerInfo[member_remark]" rows="3" maxlength="200"><?= $model['member_remark'] ?></textarea>
        </div>
    </div>
    <div class="mt-20 mb-20">
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
            <?php foreach($model["contacts"] as $k=>$contact){ ?>
                <tr align="center">
                    <td width="150"><?=$k+1?></td>
                    <td width="150"><input name="CrmCustomerPersion[ccper_name][]" class="width-150 text-center easyui-validatebox" data-options="required:true" type="text" value="<?=$contact['ccper_name']?>" maxlength="15"></td>
                    <td width="150"><input name="CrmCustomerPersion[ccper_post][]" class="width-100 text-center easyui-validatebox" data-options="required:true" type="text" value="<?=$contact['ccper_post']?>" maxlength="15"></td>
                    <td width="150"><input name="CrmCustomerPersion[ccper_mail][]" class="width-150 text-center easyui-validatebox" data-options="required:true,validType:'email'" type="text" value="<?=$contact['ccper_mail']?>" maxlength="20"></td>
                    <td width="150"><input name="CrmCustomerPersion[ccper_mobile][]" class="width-150 text-center easyui-validatebox" data-options="required:true,validType:'tel_mobile'" type="text" value="<?=$contact['ccper_mobile']?>" maxlength="20"></td>
                    <td width="150"><input name="CrmCustomerPersion[ccper_remark][]" class="width-150 text-center" type="text" value="<?=$contact['ccper_remark']?>" maxlength="180"></td>
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
        <button type="button" class="button-blue-big but">确定</button>
        <button class="button-white-big" onclick="history.go(-1);" type="button">返回</button>
    </div>
    <?php ActiveForm::end() ?>
</div>
<style>
    td input{
        outline: none;
        border:none;
    }
</style>
<script>
    $(function(){
        $(".but").on('click', function () {
            if (!$('#add-form').form('validate')) {
                return false;
            }
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: $("#add-form").serialize(),
                url: "<?= \yii\helpers\Url::to(['/crm/crm-investment-dvelopment/create', 'ctype' => $ctype]) ?>",
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
        $('.disName').on("change", function () {
            var $select = $(this);
            //console.log($select);
            getNextDistrict($select);

            var distArr=[];
            $(this).prevAll(".disName").andSelf().each(function(){
                distArr.push($(this).children(":selected").html());
            });
            $("#cur-addr-input").val(distArr.join());
            $("#cur-addr-text").text(distArr.join());
        });

        //遞歸清除級聯選項
        function clearOption($select) {
            if ($select == null) {
                $select = $("#disName_1")
            }
            $tagNmae = $select.next().prop("tagName");
            if ($select.next().length != 0 && $tagNmae =='SELECT') {
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


        $("table").click(function(event){
            if($(event.target).hasClass("icon-plus")){
                $(event.target).parents("table").append('<tr align="center">\
                    <td width="150"></td>\
                    <td width="150"><input name="CrmCustomerPersion[ccper_name][]" class="width-150 text-center easyui-validatebox" data-options="required:true" type="text" maxlength="15"></td>\
                    <td width="150"><input name="CrmCustomerPersion[ccper_post][]" class="width-100 text-center easyui-validatebox" data-options="required:true" type="text" maxlength="15"></td>\
                    <td width="150"><input name="CrmCustomerPersion[ccper_mail][]" class="width-150 text-center easyui-validatebox" data-options="required:true,validType:\'email\'" type="text" maxlength="20"></td>\
                    <td width="150"><input name="CrmCustomerPersion[ccper_mobile][]" class="width-150 text-center easyui-validatebox" data-options="required:true,validType:\'tel_mobile\'" type="text" maxlength="20"></td>\
                    <td width="150"><input name="CrmCustomerPersion[ccper_remark][]" class="width-150 text-center" type="text" maxlength="180"></td>\
                    <td width="150">\
                    <a class="icon-trash mr-20"></a>\
                    <a class="icon-refresh"></a></td>\
                </tr>');
                $.parser.parse("tr");
            }
            if($(event.target).hasClass("icon-trash")){
                $(event.target).parents("tr").remove();
            }
            if($(event.target).hasClass("icon-refresh")){
                $(event.target).parents("tr").find(":text").val("");
            }
            for(var x=0;x<$("tbody tr").size();x++){
                $("tbody tr").eq(x).find("td:first").text(x+1);
            }
        });

    });
</script>
