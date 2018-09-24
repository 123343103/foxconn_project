<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
use app\assets\MultiSelectAsset;

JqueryUIAsset::register($this);
MultiSelectAsset::register($this);
?>
<style>
    .multi-select {
        position: relative;
        width: 200px;
        height: 25px;
        margin-left: 100px;
        margin-top: -25px;
    }

    .multi-select-title {
        height: 25px;
        line-height: 25px;
        position: absolute;
        margin-left: 4px;
        display: block;
    }

    .multi-select ul {
        position: absolute;
        width: 180px;
        padding: 0 10px;
        height: 250px;
        top: 28px;
        border: #ccc 1px solid;
        overflow-x: hidden;
        overflow-y: auto;
        display: none;
        background: #f0f1f4;
    }

    .multi-select label {
        line-height: 25px;
    }

    .multi-select label:after, .multi-select label:before {
        content: ""
    }

    .multi-select label span {
        vertical-align: top;
    }
    .ml-10{
        margin-left: 10px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
    .width-100{
        width: 100px;
    }
    .width-200{
        width: 200px;
    }
    .mt-10{
        margin-top: 10px;
    }
    .width-400{
        width: 400px;
    }
    .mb-20{
        margin-bottom: 20px;
    }
    .width-120{
        width: 120px;
    }
    .mt-40{
        margin-top: 40px;
    }
    .ml-150{
        margin-left: 150px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .space-20{
        width: 100%;
        height: 20px;
    }
    .fancybox-wrap {
        top: 0px !important;
        left: 0px !important;
    }
</style>
<div class="no-padding " >
<div class="pop-head">
    <p>新增储位信息</p>
</div>
    <div class="ml-10">
        <?php $form = ActiveForm::begin(
            [
                'id' => "form",
            ]
        ); ?>
        <div class="space-20"></div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-wh_name"><span class="red" title="*">*</span>仓库名称:</label>
                <input class="value-align" type="hidden" value="<?= Yii::$app->user->identity->staff_id ?>" name="HrStaff[staff_code]">
                <select id="wh_name" class="width-200 value-align  easyui-validatebox"  data-options="required:'true'"  name="BsSt[wh_id]">
                    <option value="">请选择...</option>
                    <?php foreach ($whname as $key => $val) { ?>
                        <option
                            value="<?= $val['wh_id'] ?>" <?= isset($get['PartSearch']['wh_name']) && $get['PartSearch']['wh_name'] == $val['wh_name'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-part_code"><span class="red" title="*">*</span>区位名称:</label>
                <select id="partName"  class="easyui-validatebox value-align width-200" name="BsPart[part_name]"  data-options="required:'true'">
                    <option value="">请选择</option>
                </select>
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-part_name">分区码:</label>
                <input  readonly="readonly" type="text" maxlength="30" id="partCode" name="BsSt[part_code]"
                       class=" easyui-validatebox value-align width-200" value="" style="display: none">
                <label  id="partCodelb" name="BsSt[part_code]"
                        class=" easyui-validatebox value-align width-200" value=""></label>
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-rack_code"><span class="red" title="*">*</span>货架码:</label>
                <input type="text" maxlength="10" id="rackCode" name="BsSt[rack_code]"  style="ime-mode:disabled" onchange="this.value=this.value.substring(0, 20)"
                       class="easyui-validatebox width-200 value-align" value="" data-options="required:true,validType:'numoren'" />
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-st_code"><span class="red" title="*">*</span>储位码:</label>
                <input type="text" maxlength="10" id="stCode" name="BsSt[st_code]"  style="ime-mode:disabled" onchange="this.value=this.value.substring(0, 20)"
                       class="easyui-validatebox width-200 value-align" value="" data-options="required:true,validType:'numoren'" />
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-yn">状态:</label>
                <select id="partsearch-yn" class="width-200 value-align" name="BsSt[YN]">
                    <option value="启用">启用</option>
                    <option value="禁用">禁用</option>
                </select>
            </div>
        </div>
        <div class="mt-10">
            <div class="inline-block field-partsearch-pm_desc">
                <label class="vertical-top width-100 label-align" for="partsearch-remarks">备注:</label>
                <textarea id="partsearch-remarks" class="width-400 value-align" maxlength="200" placeholder="最多输入200个字" name="BsSt[remarks]" rows="4"></textarea>
            </div>
        </div>
        <div class="mt-10">
            <div class="mb-20">
                <div class="inline-block">
                    <label class="width-100 label-align" for="partsearch-st_code">操作人:</label>
                    <span class="width-140"><?=$name?></span>
                </div>
            <div class="inline-block">
                <label class="width-120 label-align">
                    操作时间:
                </label>
                <?php echo $showtime=date("Y/m/d H:i:s"); ?>
            </div>
            </div>
        </div>
        <div class="mt-40">
            <button id="submit" type="submit" class="button-blue-big ml-150">保存</button>
            <button  type="button" class="button-white-big ml-20" onclick="parent.$.fancybox.close()">取消</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $(document).ready(function () {
        ajaxSubmitForm($("#form")
//            $("#form"), function () {
//            if ($("#wh_name").val() == null || $("#wh_name").val() == "") {
//                layer.alert("请选择仓库名称!", {icon: 2});
//                return false;
//            }
//            if ($("#partCode").val() == null || $("#partCode").val() == "") {
//                layer.alert("请输入分区码!", {icon: 2});
//                return false;
//            }
//            if ($("#partName").val() == null || $("#partName").val() == "") {
//                layer.alert("请输入区位名称!", {icon: 2});
//                return false;
//            }
//            if ($("#rackCode").val() == null || $("#rackCode").val() == "") {
//                layer.alert("请输入货架码!", {icon: 2});
//                return false;
//            }
//            if ($("#stCode").val() == null || $("#stCode").val() == "") {
//                layer.alert("请输入儲位码!", {icon: 2});
//                return false;
//            }
//            else if ($("#stCode").val().length < 6 || $("#stCode").val().length > 20) {
//                layer.alert("儲位码请输入6-20位的字符!", {icon: 2});
//                return false;
//            }
//            else if (checkNumber($("#stCode").val())) {
//                layer.alert("不建议儲位码使用纯数字或者纯字母，可使用字母、數字、符號的自由組合方式!", {icon: 2});
//                return false;
//            }
//            if ($("#partsearch-yn").val() == null || $("#partsearch-yn").val() == "") {
//                layer.alert("请选择状态!", {icon: 2});
//                return false;
//            }
//            if ($("#partsearch-remarks").val().length >60) {
//                layer.alert("备注最多能输入60个字！", {icon: 2});
//                return false;
//            }
//            return true;
//        }
        );
        $("#wh_name").change(function () {
            var code = $(this).val();
            if (code == '') {
                $("#partName").html("<option value=''>请选择</option>");
                return false;
            }
            $.ajax({
                url: "<?=Url::to(['get-warehouse-info'])?>",
                data: {"id": code},
                dataType: "json",
                success: function (data) {
                    if (data.locationInfo.length > 0) {
                        var $lorId = $("#partName");
                        if ($lorId.length > 0) {
                            var str = "<option value=''>请选择</option>";
                            $.each(data.locationInfo, function (i, n) {
                                str += "<option data-code='" + n.part_code + "' value='" + n.part_code + "'>" + n.part_name + "</option>";
                            });
                            $lorId.html(str);
                        }
                    } else {
                        $("#partName").html("<option value=''>请选择</option>");
                    }
                }
            });
        });
        $("#partName").change(function () {
            $("#partCodelb").text($(this).val());
            $("#partCode").val($(this).val());
        });
        //判断是否为IE浏览器
        if(!!window.ActiveXObject || "ActiveXObject" in window){
        }
        else {
            $("#rackCode").bind("keyup",function(){
                $("#rackCode").val($("#rackCode").val().replace(/[\u4e00-\u9fa5]/g,''));
            });
            $("#stCode").bind("keyup",function(){
                $("#stCode").val($("#stCode").val().replace(/[\u4e00-\u9fa5]/g,''));
            });
        }
    });
//    //判断儲位码是否是纯数字或纯字母
//    function checkNumber(obj) {
//        var value=obj.value;
//        //var reg=/^(?![0-9]+$)(?![a-zA-Z]+$)(?!([^(0-9a-zA-Z)]|[\(\)])+$)([^(0-9a-zA-Z)]|[\(\)]|[a-zA-Z]|[0-9])$/;
//        var reg=/^(?![\p{P}]+$)$/;
//        if(!reg.test(value)){
//            layer.alert("不建议储位码或货架码使用纯数字或者纯字母，可使用字母、数字、符号的自由組合方式!", {icon: 2});
//            $(obj).addClass("validatebox-invalid");
//            return false;
//        }
//    }
</script>