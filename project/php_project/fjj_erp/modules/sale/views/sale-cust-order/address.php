<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;

JqueryUIAsset::register($this);
$this->title = "新增收货地址";
if (!empty($model)) {
    $this->title = "修改收货地址";
}
?>
<style>
    .width-130 {
        width: 130px;
    }

    .label-width {
        width: 80px;
    }

    .mb-20 {
        margin-bottom: 20px;
    }
</style>
<div class="head-first"><?= $this->title ?></div>
<?php $form = ActiveForm::begin(
    ['id' => 'add-form', 'options' => ['enctype' => 'multipart/form-data']]
); ?>
<div style="margin: 40px 20px 20px;">
    <div class="mb-10">
        <div class="mb-20 overflow-auto  ml-60">
            <div class=" float-left"><label class="label-width "><span
                        class="red ">*</span>收货地址</label><label>：</label></div>
            <div class="float-left" style="margin-left: 4px;width: 550px">
                <select class="width-130 disName easyui-validatebox mb-10" data-options="required:'true'"
                        id="disName_1">
                    <option value="">请选择...</option>
                    <?php foreach ($countrys as $key => $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $district['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 disName easyui-validatebox" data-options="required:'true'" id="disName_2"
                        id="title_address_province">
                    <option value="">请选择...</option>
                    <?php if (!empty($district)) { ?>
                        <?php foreach ($district['twoLevel'] as $val) { ?>
                            <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $district['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <select class="width-130 disName easyui-validatebox" data-options="required:'true'" id="disName_3"
                        id="title_address_city">
                    <option value="">请选择...</option>
                    <?php if (!empty($district)) { ?>
                        <?php foreach ($district['threeLevel'] as $val) { ?>
                            <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $district['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <select class="width-130 disName easyui-validatebox" data-options="required:'true'" id="disName_4"
                        id="title_address_town"
                        name="BsAddress[district]">
                    <option value="">请选择...</option>
                    <?php if (!empty($district)) { ?>
                        <?php foreach ($district['fourLevel'] as $val) { ?>
                            <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $district['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <input class="remove-require width-552 easyui-validatebox" data-options="required:true" type="text"
                       name="BsAddress[address]"
                       style="width: 531px" id="address"
                       value="<?= $model['address'] ?>" maxlength="100">
                <input class="hiden" type="text" name="BsAddress[ba_address]" id="ba_address"
                       value="<?= $model['ba_address'] ?>">
                <input class="hiden" type="text" name="BsAddress[cust_id]" id="ba_address"
                       value="<?= $params['custId'] ?>" maxlength="100">
            </div>
        </div>
        <div class="mb-10">
            <label class="label-width"><span class="red">*</span>收 货 人</label><label>：</label>
            <input type="text" id="cust_contacts" class="value-width easyui-validatebox"
                   data-options="required:'true',validType:'maxLength[20]'"
                   name="BsAddress[contact_name]" maxlength="20"
                   value="<?= $model['contact_name'] ?>">
        </div>
        <div class="mb-10">
            <label class="label-width ml-140"><span class="red">*</span>联系手机</label><label>：</label>
            <input type="text" id="cust_tel2" class="value-width easyui-validatebox Onlynum"
                   data-options="required:true,validType:'mobile'" placeholder="请输入：138xxxxxxxx"
                   name="BsAddress[contact_tel]"
                   value="<?= $model['contact_tel'] ?>">
        </div>
        <div class="mb-10">
            <label class="label-width ml-140">固定电话</label><label>：</label>
            <input type="text" id="cust_tel2" class="value-width easyui-validatebox IsTel"
                   data-options="validType:'telphone'" placeholder="请输入：xxxx-xxxxxxx"
                   name="BsAddress[tel]"
                   value="<?= $model['tel'] ?>">
        </div>
        <div class="mb-10">
            <label class="label-width ml-140">邮政编码</label><label>：</label>
            <input type="text" id="cust_tel2" class="value-width easyui-validatebox" data-options="validType:'postcode'"
                   name="BsAddress[code]" maxlength="6"
                   value="<?= $model['code'] ?>">
            <input type="text" class="hiden"
                   name="BsAddress[ba_type]"
                   value="12">
        </div>
        <div class="mb-10">
            <label class="label-width ml-140"><span class="red">*</span>设为默认地址</label><label>：</label>
            <input type="radio" name="BsAddress[ba_status]" class="value-width easyui-validatebox ba_status" value="11">是
            <input type="radio" name="BsAddress[ba_status]" checked class="value-width easyui-validatebox ba_status"
                   value="10">否
        </div>
    </div>
</div>
<div class="text-center mb-20">
    <button type="submit" class="button-blue mr-20" id="confirm_product">确认</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        //输入控制
        $(".IsTel").telphone();
        $(".Onlynum").numbervalid();
        var id = "<?= $params["id"]?>";
        ajaxSubmitForm($("#add-form"), null, function (data) {
            if (data.flag == 1) {
                if (($(".ba_status:checked").val() == 11)) {
                    parent.$(".address-select").css("border-color", "#cccccc");
                    parent.$(".grey").addClass("defaultAddress");
                    parent.$(".grey").removeClass("grey");
                    parent.$("#myAddress").val(data.data);
                }
                var address = '<div class="mb-10 address-select" ' + (($(".ba_status:checked").val() == 11) ? 'style="border: 1px solid #1f7ed0;"' : '') + '>' +
                    '<input type="text" class="radioGroup hiden" value="' + data.data + '">' +
                    '<span class="mb-10">收 货  人</span><span>：</span><a class="icon-remove float-right deleteAddress"></a>' +
                    '<span class="inline-block">' + htmlEncode($("#cust_contacts").val()) + '</span><br/>' +
                    '<span class="mb-10">联系电话</span><span>：</span>' +
                    '<span class="inline-block">' + $("#cust_tel2").val() + '</span>' +
                    '<div>' +
                    '<span class="vertical-top wd-60">收货地址：</span>' +
                    '<p class="text-no-next wd-265" title="' + htmlEncode($("#ba_address").val()) + '">' + htmlEncode($("#ba_address").val()) + '</p>' +
                    '</div>' +
                    '<a class="float-right mt-5 editAddress">修改</a><a class="float-right mt-5 mr-10 ' + (($(".ba_status:checked").val() == 11) ? 'grey' : 'defaultAddress') + '">设为默认地址</a>' +
                    '</div>';
                if (id == "") {
                    parent.$("#selectAddrr").append(address
                    );
                } else {
                    $(address).insertBefore(parent.$(".edit_address"));
                    parent.$(".edit_address").remove();
                }
                layer.alert(data.msg, {
                    icon: 1,
                    end: function () {
                        if (parent.$("#selectAddrr").children().length >= 8) {
                            parent.$("#editAdrress").parent().next().show();
                            parent.$("#editAdrress").parent().hide();
                        }
                        parent.$.fancybox.close()
                    }
                });
            }
            if (data.flag == 0) {
                if ((typeof data.msg) == 'object') {
                    layer.alert(JSON.stringify(data.msg), {icon: 2});
                } else {
                    layer.alert(data.msg, {icon: 2});
                }
                $("button[type='submit']").prop("disabled", false);
            }
        });

        var type = "<?=$params["type"]?>";
        $('.disName,.disName1,.disName2,.disName3').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select, $url, "select");
        });
        $('#disName_1,#disName_2,#disName_3,#disName_4,#address').on("change", function () {
            var disName_1 = $("#disName_1").find("option:selected").text();
            var disName_2 = $("#disName_2").find("option:selected").text();
            var disName_3 = $("#disName_3").find("option:selected").text();
            var disName_4 = $("#disName_4").find("option:selected").text();
            var ba_address = ((disName_1 != "请选择...") ? disName_1 : "") + ((disName_2 != "请选择...") ? disName_2 : "") + ((disName_3 != "请选择...") ? disName_3 : "") + ((disName_4 != "请选择...") ? disName_4 : "") + $("#address").val();
            $("#ba_address").val(ba_address);
        });
    });
    //获取Html转义字符
    function htmlEncode(html) {
        return document.createElement('div').appendChild(
            document.createTextNode(html)).parentNode.innerHTML;
    }
    ;
    //获取Html
    function htmlDecode(html) {
        var a = document.createElement('div');
        a.innerHTML = html;
        return a.textContent;
    }
    ;
</script>