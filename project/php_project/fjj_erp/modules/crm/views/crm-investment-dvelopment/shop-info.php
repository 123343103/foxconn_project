<?php
use \yii\widgets\ActiveForm;
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
        <p>开店信息录入</p>
    </div>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-10 ml-40" style="margin-top: 30px">
        <input type="hidden" id="cust_id" name="cust_id">
        <label class="label-width qlabel-align vertical-top">用户名</label><label>：</label>
        <input class="value-width qvalue-align hiden" type="text" id="member_name" readonly="readonly">
        <label class="value-width qvalue-align vertical-top" id="member_name2"></label>
        <label class="label-width qlabel-align vertical-top">注册时间</label><label>：</label>
        <input class="value-width qvalue-align hiden" type="text" id="member_regtime" readonly="readonly">
        <label class="value-width qvalue-align vertical-top" type="text" id="member_regtime2"></label>
    </div>
    <div class="mb-10 ml-40">
        <label class="label-width qlabel-align vertical-top">公司名称</label><label>：</label>
        <input class="value-width qvalue-align hiden" type="text" id="cust_sname" name="cust_sname" readonly="readonly">
        <label class="value-width qvalue-align vertical-top" id="cust_sname2"></label>
        <label class="label-width qlabel-align">公司简称</label><label>：</label>
        <input class="value-width qvalue-align hiden" type="text" id="cust_shortname" readonly="readonly">
        <label class="value-width qvalue-align vertical-top" id="cust_shortname2"></label>
    </div>
    <div class="mb-10 ml-40">
        <label class="label-width qlabel-align"><span class="red">*</span>店铺开通日期</label><label>：</label>
        <input class="value-width qvalue-align Wdate required" type="text" name="shop_date" readonly="readonly"
               onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd' })">
        <label class="label-width qlabel-align"><span class="red">*</span>店铺名称</label><label>：</label>
        <input class="value-width qvalue-align required" type="text" name="shop_name" maxlength="60">
    </div>
    <div class="mb-10 ml-40">
        <label class="label-width qlabel-align"><span class="red">*</span>发布商品数目</label><label>：</label>
        <input class="value-width qvalue-align required Onlynum easyui-validatebox" type="text" name="shop_qty"
               maxlength="11"
               data-options="validType:'int'">
        <label class="label-width qlabel-align">其它</label><label>：</label>
        <input class="value-width qvalue-align " type="text" name="shop_otherpd" maxlength="255">
    </div>
    <div class="mb-10 ml-40">
        <label class="label-width qlabel-align "><span class="red">*</span>是否缴纳保证金</label><label>：</label>
        <select class="value-width qvalue-align required" type="text" name="shop_isbail" id="shop_isbail">
            <option value="">请选择...</option>
            <option value="1">是</option>
            <option value="0">否</option>
        </select>
        <label class="label-width qlabel-align"><span class="red">*</span>应缴保证金额</label><label>：</label>
        <input class="value-s required Onlynum easyui-validatebox" type="text" name="shop_bailqty"
               data-options="validType:'int'"
               id="shop_bailqty" maxlength="18">
        <!--        <label class="width-50"><span class="red">*</span>币种</label>-->
        <select class="value-s required" type="text" name="shop_bailcurr" id="shop_bailcurr">
            <?php foreach ($downList['tradeCurrency'] as $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>" <?= ($rmbId == $val['bsp_id']) ? 'selected' : null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10 ml-40">
        <label class="label-width qlabel-align vertical-top">备注</label><label class="vertical-top">：</label>
        <textarea style="width:510px;" name="shop_remark" placeholder="最多输入200字" rows="3" class="easyui-validatebox"
                  data-options="validType:'maxLength[200]'"
                  maxlength="200"></textarea>
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
            parent.$("#data").datagrid("reload");
            parent.$.fancybox.close();
        });
        $(".required").validatebox({
            required: true
        });

        $('#shop_isbail').on("change", function () {
            var shop = $("#shop_bailqty");
            var shop2 = $("#shop_bailcurr");
            if ($("#shop_isbail").val() == '1') {
                shop.validatebox({
                    required: true
                });
                shop.removeAttr("disabled");
                shop2.removeAttr("disabled");
                shop2.css('background-color', '#ffffff');
            } else {
                shop.val("");
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
        var data = $(window.parent.$("#data").datagrid("getSelected"));
        $("#member_name").val(data[0]['member_name']);
        $("#cust_id").val(data[0]['cust_id']);
        $("#member_regtime").val(data[0]['member_regtime']);
        $("#cust_sname").val(data[0]['cust_sname']);
        $("#cust_shortname").val(data[0]['cust_shortname']);
        $("#member_type").val(data[0]['member_type']);
//        $("#memberType").val(data[0]['bsPubdata']['member_type']);
        $("#member_name2").html(data[0]['member_name']);
        $("#member_regtime2").html(data[0]['member_regtime']);
        $("#cust_sname2").html(data[0]['cust_sname']);
        $("#cust_shortname2").html(data[0]['cust_shortname']);
    })


</script>
