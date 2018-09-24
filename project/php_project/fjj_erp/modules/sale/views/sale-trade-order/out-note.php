<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;

//dumpE($res);
JqueryUIAsset::register($this);
?>
<style>
    .width-40 {
        width: 40px;
    }

    .width-70 {
        width: 70px;
    }

    .width-90 {
        width: 90px;
    }

    .width-140 {
        width: 140px;
    }

    .ml-100 {
        margin-left: 100px;
    }

    .mb-20 {
        margin-bottom: 20px;
    }
</style>
<?php if (empty($res)) { ?>
    <div>该订单没有需要发送的出货通知</div>
    <script>
        $(function () {
            parent.$.fancybox.cancel();
        })
    </script>
<?php } else { ?>
    <div>
        <div class="head-first">出货通知</div>
        <div style="margin: 0 20px 20px;">
            <div class="mb-10">
                <?php ActiveForm::begin(['id' => 'note-form', 'method' => 'get']) ?>
                <div class="mb-10">
                    <label class="width-80">订单编号</label><label>：</label>
                    <span class="width-120"><?= $res['ord_no'] ?></span>
                </div>
                <div class="mb-10">
                    <label class="width-80">发送人部门</label><label>：</label>
                    <span class="width-120"><?= $res['organization_name'] ?></span>
                    <label class="width-80 ml-100">发送通知人</label><label>：</label>
                    <span class="width-120"><?= $res['staff_name'] ?></span>
                </div>
                <div class="mb-20">
                    <label class="width-80">优先级</label><label>：</label>
                    <select class="width-60" name="OmsShpNt[urg]">
                        <option value="1">一般</option>
                        <option value="2">急</option>
                        <option value="3">特急</option>
                    </select>
                    <input type="hidden" name="OmsShpNt[soh_id]" value="<?= $res['ord_id'] ?>">
                    <input type="hidden" id="is_all" name="IsAll" value="20">
                    <input type="hidden" name="OmsShpNt[wh_id]" value="<?= $res['products'][0]["wh_id"] ?>">
                </div>
                <div class="mb-20 tablescroll" style="overflow-x: scroll;">
                    <table class="table">
                        <thead>
                        <tr style="font-size: 14px;">
                            <th><p class="width-40 white">序号</p></th>
                            <th><p class="width-40"><input type="checkbox" id="checkAll"></p></th>
                            <th><p class="width-140 white">料号</p></th>
                            <th><p class="width-140 white">品名</p></th>
                            <th><p class="width-70 white">下单数量</p></th>
                            <th><p class="width-70 white">剩余数量</p></th>
                            <th><p class="width-70 white"><span class="red">*</span>出货数量</p></th>
                            <th><p class="width-90 white">单位</p></th>
                            <th><p class="width-90 white">配送方式</p></th>
                            <th><p class="width-90 white">运输方式</p></th>
                            <th><p class="width-90 white">出仓/自提仓库</p></th>
                            <th><p class="width-90 white">交期</p></th>
                            <th><p class="width-90 white">收货人</p></th>
                            <th><p class="width-90 white">备注</p></th>
                        </tr>
                        </thead>
                        <tbody id="product_table" style="font-size: 12px;">
                        <?php foreach ($res['products'] as $k => $v) { ?>
                            <tr><input type="hidden" name="OmsShpNtDt[<?= $k ?>][sol_id]"
                                       value="<?= $v['ord_dt_id'] ?>">
                                <input type="hidden" name="OmsShpNtDt[<?= $k ?>][part_no]" value="<?= $v['part_no'] ?>">
                                <td><?= $k + 1 ?></td>
                                <td><input class="width-40 td-box"
                                           type="checkbox" <?= ($v['sapl_quantity'] != $v['delivery_num']) ? "checked" : "disabled" ?>
                                           name="OmsShpNtDt[<?= $k ?>][checkbox]">
                                </td>
                                <td><?= $v['part_no'] ?></td>
                                <td><?= $v['pdt_name'] ?></td>
                                <td><?= $v['sapl_quantity'] ?></td>
                                <td class="left_num"><?= ($v['sapl_quantity'] - $v['delivery_num']) ?></td>
                                <td><input
                                        class="width-70 out-qty easyui-validatebox" <?= ($v['sapl_quantity'] == $v['delivery_num']) ? "disabled" : "" ?>
                                        name="OmsShpNtDt[<?= $k ?>][nums]"
                                        value="<?= ($v['sapl_quantity'] == $v['delivery_num']) ? "已出完" : ($v['sapl_quantity'] - $v['delivery_num']) ?>">
                                </td>
                                <td><?= $v['unit'] ?></td>
                                <td><?= $v['distrustion'] ?></td>
                                <td><?= $v['tran_sname'] ?></td>
                                <td class="wh" data-whid="<?= $v['wh_id'] ?>"><?= $v['wh_name'] ?></td>
                                <td><?= $v['consignment_date'] ?></td>
                                <td><?= $v['addr_man'] ?></td>
                                <td><input class="width-70 easyui-validatebox" data-options="validType:'length[0,100]'"
                                           name="OmsShpNtDt[<?= $k ?>][marks]" value="<?= $v['sapl_remark'] ?>"></td>
                                <input type="hidden" class="gt"
                                       value="<?= ($v['sapl_quantity'] - $v['delivery_num']) ?>">
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="text-center mb-20">
            <button type="button" class="button-blue mr-20" id="note">发送通知</button>
            <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
        </div>
    </div>
<?php } ?>
<script>
    $(function () {
        var is_all = true;   //判断是否全部出货
        //全选
        $(".table").on('click', "th input[type='checkbox']", function () {
            if ($(this).is(":checked")) {
                $('.table').find("td input[type='checkbox']").prop("checked", true);
            } else {
                $('.table').find("td input[type='checkbox']").prop("checked", false);
            }
        });

        $('#note').click(function () {
            // 是否选择产品
            if ($('.table').find("td input[type='checkbox']:checked").length == 0) {
                layer.alert('没有选择产品', {
                    icon: 1, end: function () {
                        $('#note').attr('disabled', false);
                    }
                })
                return false;
            }
            $('#product_table tr').each(function () {
                var left_num = parseFloat($(this).find("td.left_num").html())*100000;
                var out_num = parseFloat($(this).find("input.out-qty").val())*100000;
                if (isNaN(left_num)) {
                    left_num = 0;
                }
                if (isNaN(out_num)) {
                    out_num = 0;
                }
                if (left_num > out_num) {
                    is_all = false;
                }
            });
            if (is_all) {
                $("#is_all").val(10);  //全部出货
            } else {
                $("#is_all").val(20);   //部分出货
            }
            // 发送通知
            if ($("#note-form").form('validate')) {
                $(this).attr('disabled', true);
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data: $("#note-form").serialize(),
                    url: "<?= \yii\helpers\Url::to(['out-note?id=']) . $res['ord_id'] ?>",
                    success: function (data) {
                        if (data.status == 1) {
                            layer.alert("通知发送成功！", {
                                icon: 1, end: function () {
                                    parent.window.location.reload();
                                }
                            });
                        } else {
                            layer.alert(data.msg, {
                                icon: 1, end: function () {
                                    $('#note').attr('disabled', false);
                                }
                            });
                        }
                    }
                })
            }
        })

        $(".out-qty").change(function () {
            if ($(this).val() != "") {
                $(this).parent().parent().find("input[type='checkbox']").prop("checked", true);
            } else {
                $(this).parent().parent().find("input[type='checkbox']").prop("checked", false);
            }
            num = $(this).parent().parent().find('.gt').val() - $(this).val();
            $(this).validatebox({validType: 'gt[' + num + ']'});
        })
    });
</script>