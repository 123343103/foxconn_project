<?php
use \yii\widgets\ActiveForm;
use app\assets\JeDateAsset;

JeDateAsset::register($this);
foreach ($model['tradeCurrency'] as $key => $val) {
    if ($val["bsp_svalue"] == "RMB") {
        $rmbId = $val["bsp_id"];
    }
}
?>
<style>
    .label-width {
        width: 90px;
    }

    .value-width {
        width: 200px !important;
    }

    .value-m {
        width: 152px;
    }

    .value-s {
        width: 99px;
    }

    .margin {
        margin-left: 120px;
    }

    .ml-40 {
        margin-left: 40px;
    }

    .margin-s {
        margin-left: 40px;
    }
</style>
<div class="no-padding">
    <div class="pop-head">
        <p>修改店铺信息</p>
    </div>
    <div class="space-30"></div>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-10 ml-40" style="margin-top: 30px">
        <input type="hidden" id="cust_id">
        <label class="label-width qlabel-align">用户名</label><label>：</label>
        <span class="value-width qvalue-align"><?= $model["member_name"] ?></span>
        <input class="value-width qvalue-align hiden" type="text" id="member_name" readonly="readonly"
               value="<?= $model["member_name"] ?>">
        <label class="label-width qlabel-align">注册时间</label><label>：</label>
        <span class="value-width qvalue-align"><?= substr($model["member_regtime"],0,10) ?></span>
        <input class="value-width qvalue-align hiden" type="text" id="member_regtime" readonly="readonly"
               value="<?= $model["member_regtime"] ?>">
    </div>
    <div class="mb-10 ml-40">
        <label class="label-width qlabel-align">公司名称</label><label>：</label>
        <span class="value-width qvalue-align"><?= $model["cust_sname"] ?></span>
        <input class="value-width qvalue-align hiden" type="text" id="cust_sname" readonly="readonly"
               value="<?= $model["cust_sname"] ?>">
        <label class="label-width qlabel-align">公司简称</label><label>：</label>
        <span class="value-width qvalue-align"><?= $model["cust_shortname"] ?></span>
        <input class="value-width qvalue-align hiden" type="text" id="cust_shortname" readonly="readonly"
               value="<?= $model["cust_shortname"] ?>">
    </div>
    <div class="mb-10 ml-40">
        <label class="label-width qlabel-align"><span class="red">*</span>店铺开通日期</label><label>：</label>
        <input class="value-width qvalue-align Wdate required" type="text" name="CrmCustShop[shop_date]" readonly="readonly"
               onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd' })" value="<?= $model['shop_date'] ?>">

        <label class="label-width qlabel-align"><span class="red">*</span>店铺名称</label><label>：</label>
        <input class="value-width qvalue-align required" type="text" name="CrmCustShop[shop_name]" maxlength="60"
               value="<?= $model['shop_name'] ?>">
    </div>
    <div class="mb-10 ml-40">
        <label class="label-width qlabel-align"><span class="red">*</span>发布商品数目</label><label>：</label>
        <input class="value-width qvalue-align required easyui-validatebox Onlynum" type="text"
               name="CrmCustShop[shop_qty]" maxlength="11"
               data-options="validType:'int'" value="<?= empty($model['shop_qty']) ? "" : $model['shop_qty'] ?>">
        <label class="label-width qlabel-align">其它</label><label>：</label>
        <input class="value-width qvalue-align " type="text" name="CrmCustShop[shop_otherpd]" maxlength="255"
               value="<?= $model['shop_otherpd'] ?>">
    </div>
    <div class="mb-10 ml-40">
        <label class="label-width qlabel-align "><span class="red">*</span>是否缴纳保证金</label><label>：</label>
        <?= \yii\helpers\Html::dropDownList("CrmCustShop[shop_isbail]", $model["shop_isbail"], ["1" => "是", "0" => "否"], ["prompt" => "请选择", "id" => "shop_isbail", "class" => "value-width qvalue-align required"]) ?>
        <label class="label-width qlabel-align"><span class="red">*</span>应缴保证金额</label><label>：</label>
        <input class="value-s qvalue-align required easyui-validatebox Onlynum" type="text"
               name="CrmCustShop[shop_bailqty]" <?= ($model['shop_bailqty'] == 0) ? "disabled" : "" ?>
               data-options="validType:'int'" id="shop_bailqty" maxlength="18"
               value="<?= empty($model['shop_bailqty']) ? "" : $model['shop_bailqty'] ?>">
        <!--        <label class="width-50"><span class="red">*</span>币种</label>-->
        <select class="value-s qvalue-align required"
                type="text" <?= ($model['shop_bailqty'] == 0) ? "disabled" : "" ?> name="shop_bailcurr"
                id="shop_bailcurr">
            <?php foreach ($model['tradeCurrency'] as $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>" <?= ((empty($model['shop_bailcurr']) ? $rmbId : $model['shop_bailcurr']) == $val['bsp_id']) ? 'selected' : null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10 ml-40">
        <label class="label-width qlabel-align vertical-top">备注</label><label class="vertical-top">：</label>
        <textarea style="width:510px;" name="CrmCustShop[shop_remark]" rows="3" class="easyui-validatebox"
                  placeholder="最多输入200字"
                  data-options="validType:'maxLength[200]'"
                  maxlength="200"><?= $model["shop_remark"] ?></textarea>
    </div>
    <div class="text-center">
        <button type="submit" class="button-blue">确定</button>
        <button class="button-white close" type="button">取消</button>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $(function () {
        //输入控制
//        $(".IsTel").telphone();
        $(".Onlynum").numbervalid();

        ajaxSubmitForm($("#add-form"), '', function (res) {
            parent.layer.alert(res.msg, {icon: 1});
            parent.location.reload();
            parent.$.fancybox.close();
        });
        $(".required").validatebox({
            required: true
        });
        var shop = $("#shop_bailqty");
        var shop2 = $("#shop_bailcurr");
        var shop_bailcurr = "<?= $model["shop_isbail"]?>";
        if (shop_bailcurr == 0) {
            shop.val('');
            shop.validatebox({
                required: false
            });
            shop2.validatebox({
                required: false
            });
            shop2.css('background-color', '#ebebe6');
        }
        $('#shop_isbail').on("change", function () {
            if ($("#shop_isbail").val() == '1') {
                shop.validatebox({
                    required: true
                });
                shop.removeAttr("disabled");
                shop2.removeAttr("disabled");
                shop2.css('background-color', '#ffffff');
            } else {
                shop.val('');
                shop.validatebox({
                    required: false
                });
                shop.attr("disabled", true);
                shop2.validatebox({
                    required: false
                });
                shop2.attr("disabled", true);
                shop2.css('background-color', '#ebebe6');
            }
        });
        //关闭弹窗
        $(".close").click(function () {
            parent.$.fancybox.close();
        });
//        var data=$(window.parent.$("#data").datagrid("getSelected"));
//        $("#member_name").val(data[0]['member_name']);
//        $("#cust_id").val(data[0]['cust_id']);
//        $("#member_regtime").val(data[0]['member_regtime']);
//        $("#cust_sname").val(data[0]['cust_sname']);
//        $("#cust_shortname").val(data[0]['cust_shortname']);
//        $("#member_type").val(data[0]['member_type']);
//        $("#memberType").val(data[0]['bsPubdata']['member_type']);
    })


</script>
