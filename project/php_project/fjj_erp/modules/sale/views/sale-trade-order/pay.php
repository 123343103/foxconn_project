<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<style>
    .width-170 {
        width: 170px;
    }

    .space-10 {
        height: 10px;
    }
</style>
<h3 class="head-first" style="margin-bottom: 0">信用额度支付确认</h3>
<div class="content" style="height: 200px;width: 500px">
    <label class="label-width ">订单编号</label><label>：</label>
    <span style="width:200px;"><?= $data["model"]["ord_no"] ?></span>
    <label class="label-width ">订单总金额</label><label>：</label>
    <span style="width:155px;"><?= "￥ ".bcsub($data["model"]["req_tax_amount"], 0, 2) ?></span>
    <?php foreach ($data["pay"] as $k => $v) { ?>
        <div class="inline-block mb-10">
            <label class="label-width ">信用额度类型</label><label>：</label>
            <span style="width:175px;"><?= $v['credit_id'] ?></span>
            <label
                class="label-width ">付款金额</label><label>：</label>
            <span style="width:165px;"><?= "￥ ".bcsub($v['stag_cost'], 0, 2) ?></span>
        </div><br>
    <?php } ?>
    <div class="space-10"></div>
    <span class="mb-10" style="width:265px;font-size: 18px"></span><span
        style="width:115px;font-size: 18px;font-weight: bold">确定支付吗？</span>
    <div class="space-10"></div>    <div class="space-10"></div>
    <div class="mt-20 text-center">
        <button class="button-blue sub" type="button">确定</button>
        <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
    </div>
</div>
<script>
    $(function () {
        var id =<?= $data["model"]["ord_id"]?>;
        $('.sub').click(function () {
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                url: "<?=Url::to(['order-pay'])?>" + "?id=" + id,
                success: function (data) {
                    if (data.flag == 1) {
                        parent.layer.alert(data.msg, {
                            icon: 1, end: function () {
                                parent.$.fancybox.close();
                                parent.window.location.reload();
                            }
                        });
                    } else {
                        parent.layer.alert(data.msg, {
                            icon: 1, end: function () {
                                return false;
                            }
                        });
                    }
                }
            });
        })
    });
</script>