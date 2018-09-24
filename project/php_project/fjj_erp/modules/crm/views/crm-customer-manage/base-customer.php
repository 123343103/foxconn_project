<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/22
 * Time: 17:01
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<!--修改客户基本信息-->

<style>
    .label-width{
        width:140px;
    }
    .value-width{
        width: 200px;
    }
</style>
<div>
    <?php $form = ActiveForm::begin([
        'id' => 'add-form',
        'action' => Url::to(['/crm/crm-customer-manage/update-customer', 'id' => $id]),
        'method' => 'post'
    ]) ?>
    <div>
        <h2 class="head-first">修改客户基本信息</h2>

        <div class="mb-10">
            <div class="inline-block">
                <label for="" class="label-width label-align">客户全称：</label>
                <input type="text" class="value-width value-align" name="CrmCustomerInfo[cust_sname]"
                       value="<?= $model['cust_sname'] ?>" maxlength="50">
            </div>
            <div class="inline-block">
                <label for="" class="label-width label-align">客户简称：</label>
                <input type="text" class="value-width value-align" name="CrmCustomerInfo[cust_shortname]"
                       value="<?= $model['cust_shortname'] ?>" maxlength="50">
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
                <?php if($isSuper){ ?>
                    <input type="text" class="value-width value-align staff_code" value="<?= $model['manager_code']?$model['manager_code']:""; ?>" readonly="readonly">
                <?php }else{ ?>
                    <input type="text" class="value-width value-align staff_code" value="<?= $model['manager_code']?$model['manager_code']:""; ?>" name="code" readonly="readonly">
                <?php } ?>
                <?php if($isSuper || !$model){ ?>
                    <a id="select_manage">选择</a>
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
            <div class="mb-10"></div>
            <div><input style="width:548px;margin-left: 144px;" class="easyui-validatebox" data-options="required:true" type="text" name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>" maxlength="50" placeholder="最多输入50个字" id="cust_adress"></div>
            <div style="width:690px;text-align: right;"><span class="red" style="font-size: 10px;">请确保与营业执照上的住所保持一致</span></div>
        </div>
        <div class="text-center mt-40">
            <button type="submit" class="button-blue-big sub">确认</button>
            <button type="button" class="button-white-big" onclick="close_select()">取消</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $(".sub").click(function () {
        return ajaxSubmitForm($("#add-form"));
    });
    $(function(){
        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select,$url,"select");
        });

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

        //选择客户弹出框
        $('#select_manage').click(function(){
            var url;
            var staff_code = $('.staff_code').val();
            var staff_id = $('.staff_id').val();
            url = '<?= Url::to(['/crm/crm-customer-info/select-manage']) ?>?id='+ staff_id + '&code='+ staff_code;
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
    })
</script>
