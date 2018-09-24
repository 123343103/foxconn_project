<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/8/7
 * Time: 下午 06:28
 */


?>
<?php
use app\assets\JqueryUIAsset;
use yii\widgets\ActiveForm;

JqueryUIAsset::register($this);
?>
<style>
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
    .width-150{
        width: 150px;
    }
    .mt-10{
        margin-top: 10px;
    }
    .width-400{
        width: 400px;
    }
    .block{
        font-size: 10px;
        color: red;
        margin-top: -15px;
        margin-left: 508px;
    }
    .mt-30{
        margin-top: 30px;
    }
    .ml-100{
        margin-left: 100px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .space-20{
        width: 100%;
        height: 20px;
    }
    .content{padding: 0px;}
</style>
<div class="content">
<div class="pop-head">
    <?php if (empty($model)) { ?>
        <p>新增区位</p>
    <?php } else { ?>
        <p>修改区位</p>
    <?php } ?>
</div>
    <div class="space-20"></div>
    <div class="ml-10">
        <?php $form = ActiveForm::begin(
            [
                'id' => "form",
                'enableAjaxValidation' => true,
            ]
        ); ?>
        <div class="mb-10">
            <div class="inline-block">
                <?php if(!empty($model)){?>
                    <label class="no-border vertical-center width-100 label-align" for="partsearch-wh_name">仓库名称：</label>
                    <input style="width: 300px" class="no-border vertical-center value_align" type="hidden" name="BsPart[wh_name]" value="<?= $model['wh_code'] ?>">
                    <span style="width: 300px" class="no-border vertical-center value_align"><?= $ret['wh_name'] ?></span>
                <?php }else{ ?>
                    <label class="width-100 label-align" for="partsearch-wh_name"><span class="red">*</span>仓库名称：</label>
                    <select name="BsPart[wh_name]" class="width-200 value-align" id="wh_id">
                        <option value="">请选择--</option>
                        <?php foreach ($downList['whname'] as $val) { ?>
                                <option value="<?= $val['wh_code'] ?>" name="wh_code"><?= $val['wh_name'] ?></option>
                        <?php } ?>
                    </select>
                <?php }?>
            </div>
        </div>
        <div class="mb-10">
            <?php if(!empty($model)){?>
                <div class="inline-block">
                    <label class="width-100 label-align" for="partsearch-part_code">仓库代码：</label>
                    <input type="hidden" maxlength="30" id="whCode" name="BsPart[wh_code]"
                           class=" easyui-validatebox width-200 value-align" value="<?= $model[0]['wh_code'] ?>">
                    <span><?= $model[0]['wh_code'] ?></span>
                </div>
            <?php }else{?>
                <div class="inline-block">
                    <label class="width-100 label-align" for="partsearch-part_code"><span class="red">*</span>仓库代码：</label>
                    <input type="text" maxlength="30" id="whCode" name="BsPart[wh_code]"
                           class=" easyui-validatebox width-200 value-align" value="">
                </div>
            <?php }?>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-part_code"><span class="red">*</span>区位码：</label>
                <input type="text" maxlength="20" onchange="this.value=this.value.substring(0, 20)" id="partCode" name="BsPart[part_code]" data-options="required:true,validType:'numoren'"
                   class="easyui-validatebox width-200 value-align" value="<?= $model[0]['part_code'] ?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-part_name"><span class="red">*</span>区位名称：</label>
                <input type="text" maxlength="30" onchange="this.value=this.value.substring(0, 30)" id="partName" name="BsPart[part_name]"
                      class="easyui-validatebox width-200 value-align" value="<?= $model[0]['part_name'] ?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-yn">状态：</label>
                <select id="partsearch-yn" class="width-150 value-align" name="BsPart[YN]">
                    <?php if ($model != null && $model[0]['YN'] == 0) { ?>
                        <option value="1">启用</option>
                        <option value="0" selected="selected">禁用</option>
                    <?php } else { ?>
                        <option value="1" selected="selected">启用</option>
                        <option value="0">禁用</option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="mt-10">
            <div class="inline-block field-partsearch-pm_desc">
                <label class="vertical-top width-100 label-align" for="partsearch-remarks">备注：</label>
                <textarea id="remarks" maxlength="200" onchange="this.value=this.value.substring(0, 200)" class="width-400  value-align" name="BsPart[remarks]" placeholder="最多输入200字 "
                          rows="4"><?= $model[0]['remarks'] ?></textarea>
<!--                <a style="color: red">0/300</a>-->
                <div class="block" id="counter">
                </div>

            </div>
        </div>
        <div class="mt-30">
            <button id="submit" type="submit" class="button-blue-big ml-100">保存</button>
            <button id="close" type="button" class="button-white-big ml-20">取消</button>
        </div>
        <input type="hidden" class="_dd" value="<?= $model[0]['wh_code']?>">
    </div>
    <?php $form->end(); ?>
</div>
<script>
    $(document).ready(function () {
        ajaxSubmitForm($("#form"), function () {
            if($("._dd").val()!=""){
                if ($("#partCode").val() == null || $("#partCode").val() == "") {
                    layer.alert("请输入区位码!", {icon: 2});
                    return false;
                }
//                else if ($("#partCode").val().length < 1 || $("#partName").val().length > 20) {
//                    layer.alert("区位码请输入1-20位的字符!", {icon: 2});
//                    return false;
//                }
//                else if (checkNumber($("#partCode").val())) {
//                    layer.alert("不建议区位码使用纯数字或者纯字母，可使用字母、數字、符號的自由組合方式!", {icon: 2});
//                    return false;
//                }
                if ($("#partName").val() == null || $("#partName").val() == "") {
                    layer.alert("请输入区位名称!", {icon: 2});
                    return false;
                }
//                else if ($("#partName").val().length < 1 || $("#partName").val().length > 20) {
//                    layer.alert("区位名称请输入1-20位的字符!", {icon: 2});
//                    return false;
//                }
                if ($("#remarks").val().length > 200) {
                    layer.alert("备注字数不能超过200!", {icon: 2});
                    return false;
                }
                if (!checkWhCode()) {
                    return false;
                }
                return true;
            }else{
                if ($("#wh_id").val() == null || $("#wh_id").val() == "") {
                    layer.alert("请选择仓库名称!", {icon: 2});
                    return false;
                }
                if ($("#whCode").val() == null || $("#whCode").val() == "") {
                    layer.alert("请输入仓库代码!", {icon: 2});
                    return false;
                }
                if ($("#partCode").val() == null || $("#partCode").val() == "") {
                    layer.alert("请输入区位码!", {icon: 2});
                    return false;
                }
//                else if ($("#partCode").val().length < 1 || $("#partName").val().length >= 21) {
//                    alert($("#partCode").val().length);
//                    layer.alert("区位码请输入1-20位的字符!", {icon: 2});
//                    return false;
//                }
//                else if (checkNumber($("#partCode").val())) {
//                    layer.alert("不建议区位码使用纯数字或者纯字母，可使用字母、數字、符號的自由組合方式!", {icon: 2});
//                    return false;
//                }
                if ($("#partName").val() == null || $("#partName").val() == "") {
                    layer.alert("请输入区位名称!", {icon: 2});
                    return false;
                }
//                else if ($("#partName").val().length < 1 || $("#partName").val().length > 20) {
//                    layer.alert("区位名称请输入1-20位的字符!", {icon: 2});
//                    return false;
//                }
                if ($("#remarks").val().length > 300) {
                    layer.alert("备注字数不能超过300!", {icon: 2});
                    return false;
                }
                if (!checkWhCode()) {
                    return false;
                }
                return true;
            }
        });
        //判断儲位码是否是纯数字或纯字母
        function checkNumber(theObj) {
            var reg = /^[0-9]*$/;
            var reg1 = /^[A-Za-z]*$/;
            if (reg.test(theObj) || reg1.test(theObj)) {
                return true;
            }
            return false;
        }
    });
    $("#wh_id").change(function () {
        var s = $("#wh_id ").val();
        $("#whCode").val(s);
    });
    $("#whCode").blur(function () {
        checkWhCode();
    });

    function countchar() {
        var  count= $("#remarks").val().length;
        $("#counter").text(count+"/300");
    }

    function checkWhCode() {
        var wh_code = $("#whCode").val();
        var tf = false;
        <?php foreach ($downList['whname'] as $val) { ?>
        if (wh_code == "<?=$val['wh_code']?>") {
            tf = true;
        }
        <?php } ?>

        if (tf) {
            $("#wh_id ").val(wh_code);
            return true;
        } else {
            layer.alert("您输入的仓库代码不存在!", {icon: 2});
            return false;
        }
    }
    $("#close").click(function () {
        parent.$.fancybox.close();
    })
</script>