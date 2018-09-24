<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/21
 * Time: 上午 10:46
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<style>
     .label-width {
        width: 80px;
    }
    .width-130{
        width: 170px;
    }
    .width-400{
        width: 430px;
    }
</style>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div style="margin-top: 20px">
    <div class=" mb-10">
        <div class="inline-block">
            <label class="label-align label-width" for="soh_code">订单号：</label>
            <input type="text" id="soh_code" class="width-130" value="<?= $param['soh_code'] ?>"
                   name="soh_code"/>
            <div class="help-block"></div>
        </div>
        <div class="inline-block">
            <label class="label-align label-width" for="ORDERNO">物流单号：</label>
            <input type="text" id="orderno" class="width-130" name="ORDERNO" value="<?= $param['ORDERNO'] ?>">
            <div class="help-block"></div>
        </div>
        <div class="inline-block">
            <label class="label-align label-width" for="invh_code">出货单号：</label>
            <input type="text" id="invh_code" class="width-130" name="invh_code" value="<?= $param['invh_code'] ?>">
            <div class="help-block"></div>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-align label-width" for="cust_sname">客户全称：</label>
            <input type="text" id="cust_sname" class="cust_sname width-130" name="cust_sname"
                   value="<?= $crminfo['cust_sname'] ?>"
                   readonly="true">
            <div class="help-block"></div>
        </div>
        <div class="inline-block ">
            <label class="label-align label-width" for="cust_code">客户代码：</label>
            <input type="text" id="cust_code" class="cust_code width-130" name="cust_code"
                   value="<?= $crminfo['cust_code'] ?>"
                   readonly="true">
            <div class="help-block"></div>
        </div>
        <div class="inline-block">
            <label class="label-align label-width" for="cust_contacts">联系人：</label>
            <input type="text" id="cust_contacts" class="cust_contacts width-130" name="cust_contacts"
                   value="<?= $crminfo['cust_contacts'] ?>"
                   readonly="true">
            <div class="help-block"></div>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-align label-width" for="cust_tel2">联系电话：</label>
            <input type="text" id="cust_tel2" class="cust_tel2 width-130" name="cust_tel2"
                   value="<?= $crminfo['cust_tel2'] ?>"
                   readonly="true">
            <div class="help-block"></div>
        </div>
        <div class="inline-block">
            <label class="label-align label-width" for="cust_adress">客户地址：</label>
            <input type="text" id="cust_adress" class="cust_adress width-400" name="cust_adress"
                   value="<?= $crminfo['address'] ?>"
                   readonly="true">
            <div class="help-block"></div>
        </div>
        <div class="inline-block" style="margin-left: 30px;">
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue', 'id' => 'select', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow', 'style'=>'margin-left:20px;', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        </div>
    </div>

</div>
<?php ActiveForm::end(); ?>

<script>
    //    $(document).ready(function () {
    //
    //        function getcrminfo($orderno, $kdno, $invhcode) {
    //            $.ajax({
    //                url: "<?//=Url::to(['crm-info']);?>//",
    //                data: {"orderno": $orderno, "kdno": $kdno, "invhcode": $invhcode},
    //                dataType: "json",
    //                type: "get",
    //                async: false,
    //                success: function (data) {
    //                    $("#cust_sname").val(data.cust_sname);
    //                    $("#cust_code").val(data.cust_code);
    //                    $("#cust_contacts").val(data.cust_contacts);
    //                    $("#cust_tel2").val(data.cust_tel2);
    //                    $("#cust_adress").val(data.address);
    //                }
    //            });
    //        }
    //
    //        //订单号
    //        $("#soh_code").blur(function () {        //失去焦点事件
    //            // $("#soh_code").css("background-color","red");
    //            var orderno = $("#soh_code").val();
    //            var kdno = $("#orderno").val();
    //            var invhcode = $("#invh_code").val();
    //            getcrminfo(orderno, kdno, invhcode);
    //        });
    //        //物流单号
    //        $("#orderno").blur(function () {        //失去焦点事件
    //            // $("#soh_code").css("background-color","red");
    //            var orderno = $("#soh_code").val();
    //            var kdno = $("#orderno").val();
    //            var invhcode = $("#invh_code").val();
    //            getcrminfo(orderno, kdno, invhcode);
    //        });
    //        //出货单号
    //        $("#invh_code").blur(function () {        //失去焦点事件
    //            // $("#soh_code").css("background-color","red");
    //            var orderno = $("#soh_code").val();
    //            var kdno = $("#orderno").val();
    //            var invhcode = $("#invh_code").val();
    //            getcrminfo(orderno, kdno, invhcode);
    //        });
    //    });
</script>
