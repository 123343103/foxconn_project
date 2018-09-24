<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/16
 * Time: 上午 10:24
 */
?>
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
        padding: 0px 10px;
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
    .ml-50{
        margin-left: 50px;
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
    .width-350{
        width:350px;
    }
    .mt-30{
        margin-top: 30px;
    }
    .ml-120{
        margin-left: 120px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .space-20{
        width: 100%;
        height: 20px;
    }
</style>
<div class="no-padding " >
<div class="pop-head">
    <p>修改储位信息</p>
</div>
    <div class="ml-50">
        <?php $form = ActiveForm::begin(
            [
                'id' => "form",
                'enableAjaxValidation' => true,
            ]
        ); ?>
        <div class="space-20"></div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-wh_name"><span class="red" title="*">*</span>仓库名称:</label>
                <input type="hidden" value="<?= Yii::$app->user->identity->staff_id ?>" name="HrStaff[staff_code]">
                <input id="wh_name" class="width-200 value-align" name="BsWh[wh_name]" value="<?= $model['wh_name'] ?>"
                       readonly="readonly">
                <input class="value-align" type="hidden" id="wh_id" name="BsWh[wh_id]" value="<?= $model['wh_id'] ?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="part_name"><span class="red" title="*">*</span>区位名称:</label>
                <!--                <input type="text" maxlength="30" id="partName" name="BsPart[part_name]"-->
                <!--                       class="width-200 easyui-validatebox" value="-->
                <? //= $model['part_name'] ?><!--">-->
                <input type="hidden" id="name" value="<?= $model['part_name'] ?>">
                <select id="partName" class=" easyui-validatebox width-200 value-align" data-options="required:'true'"
                        name="BsPart[part_name]">
                    <option value="">请选择</option>
                </select>
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="part_code">分区码:</label>
                <label class="label-width text-left width-200" id="partCodelb"><?= $model['part_code'] ?></label>
                <input type="text"  maxlength="30" id="partCode" name="BsSt[part_code]"
                       class="easyui-validatebox width-100 value-align" style="display: none"  value="<?= $model['part_code'] ?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-rack_code"><span class="red" title="*">*</span>货架码:</label>
                <input type="text" maxlength="10" id="rackCode" name="BsSt[rack_code]" style="ime-mode:disabled" placeholder="请输入6-10位字符" style="ime-mode:disabled"
                       class="easyui-validatebox width-200 value-align" value="<?= $model['rack_code'] ?>" data-options="required:'true'">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-st_code"><span class="red" title="*">*</span>储位码:</label>
                <input type="text" maxlength="10" id="stCode" name="BsSt[st_code]"  placeholder="请输入6-10位字符" style="ime-mode:disabled"
                     class="easyui-validatebox width-200 value-align" value="<?= $model['st_code'] ?>" data-options="required:'true'">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-yn">状态:</label>
                <select id="partsearch-yn" class="width-200 value-align" name="BsSt[YN]">
                    <option value="启用" <?= $model['YN'] == "启用" ? "selected" : null ?>>启用</option>
                    <option value="禁用" <?= $model['YN'] == "禁用" ? "selected" : null ?>>禁用</option>
                </select>
            </div>
        </div>
        <div class="mt-10">
            <div class="inline-block field-partsearch-pm_desc">
                <label class=" vertical-top width-100 label-align" for="partsearch-remarks">备注:</label>
                <textarea class="width-350 value-align" maxlength="200" id="partsearch-remarks" name="BsSt[remarks]"
                          rows="4"><?= $model['remarks'] ?></textarea>
            </div>
        </div>
        <div class="mt-10">
            <div class="inline-block field-partsearch-pm_desc">
                <label class=" vertical-top width-100 label-align" for="partsearch-remarks">操作人:</label>
                <span class="label-width text-left width-140" style="line-height: 25px"><?=$model['OPPER']?></span>
                <label class="vertical-top width-100 label-align" for="partsearch-remarks">操作时间:</label>
                <span class="label-width text-left width-140" style="line-height: 25px"><?=$model['OPP_DATE']?></span>
            </div>
        </div>
        <div class="mt-30">
            <button id="submit" type="submit" class=" button-blue-big ml-120">保存</button>
            <button onclick="parent.$.fancybox.close()" type="button" class="button-white-big ml-20">取消</button>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        ajaxSubmitForm($("#form")
//            , function () {
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
//                layer.alert("请输入储位码!", {icon: 2});
//                return false;
//            }
//            else if ($("#stCode").val().length < 6 || $("#stCode").val().length > 20) {
//                layer.alert("储位码请输入6-20位的字符!", {icon: 2});
//                return false;
//            }
//            else if (checkNumber($("#stCode").val())) {
//                layer.alert("不建议储位码使用纯数字或者纯字母，可使用字母、数字、符号的自由組合方式！", {icon: 2});
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
        var code = $("#wh_id").val();
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
                            str += "<option";
                            if ($("#name").val() == n.part_name) {
                                str += " selected data-code='" + n.part_code + "' value='" + n.part_code + "'>" + n.part_name + "</option>";
                            }
                            else {
                                str += " data-code='" + n.part_code + "' value='" + n.part_code + "'>" + n.part_name + "</option>";
                            }
                        });
                        $lorId.html(str);
                    }
                } else {
                    $("#partName").html("<option value=''>请选择</option>");
                }
            }
        });
//        console.log(locationInfo);
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
        //判断儲位码是否是纯数字或纯字母
//        function checkNumber(theObj) {
//            var reg = /^[0-9]*$/;
//            var reg1 = /^[A-Za-z]*$/;
//            if (reg.test(theObj) || reg1.test(theObj)) {
//                return true;
//            }
//            return false;
//        }
    });
</script>

