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
<?php if (empty($res)) {?>
    <div>该订单没有需要发送的采购通知</div>
    <script>
        $(function () {
            parent.$.fancybox.cancel();
        })
    </script>
    <?php } else {?>
<div>
    <div class="head-first">采购通知</div>
    <div style="margin: 0 20px 20px;">
        <div class="mb-10">
            <?php ActiveForm::begin(['id' => 'note-form', 'method' => 'get', 'action' => Url::to(['purchase-note'])]); ?>
            <div class="mb-10">
                <label class="width-100">订&nbsp;单&nbsp;编&nbsp;号</label>
                <span class="width-120"><?= $res['saph_code'] ?></span>
                <label class="width-100">被通知部门</label>
                <select class="width-120 text-center easyui-validatebox" name="SalePurchasenoteh[notify_toganid]" data-options="required:'true'">
                    <option value="">请选择部门</option>
                    <?php foreach ($res['orgList'] as $k => $v) {?>
                        <option value="<?= $v['organization_id'] ?>" data-org="<?= $v['organization_id'] ?>" ><?= str_repeat('|-',$v['organization_level']).$v['organization_name'] ?></option>
                    <?php } ?>
                </select>
                    <label class="width-100">订单类型</label>
                    <select name="SalePurchasenoteh[bill_type]" class="width-120 easyui-validatebox" data-options="required:'true'">
                        <option value="">请选择...</option>
                        <?php foreach ($res["billType"] as $key => $val) { ?>
                            <option
                                value="<?= $val["business_type_id"] ?>" ><?= $val["business_value"] ?></option>
                        <?php } ?>
                    </select>
                </div>
            <div class="mb-20">
                <label class="width-100">发送人部门</label>
                <span class="width-120"><?= $res['organization_name'] ?></span>
                <label class="width-100">发送通知人</label>
                <span class="width-120"><?= $res['staff_name'] ?></span>
                <label class="width-100">通知优先级</label>
                <select class="width-120 text-center" name="SalePurchasenoteh[pri]">
                    <option value="1">一般</option>
                    <option value="2">急</option>
                    <option value="3">特急</option>
                </select>
            </div>
            <div class="mb-20 tablescroll" style="overflow-x: scroll;">
                <table class="table">
                    <thead>
                    <tr style="font-size: 14px;">
                        <th><p class="width-40 white">序号</p></th>
                        <th><p class="width-40"><input type="checkbox" id="checkAll"></p></th>
                        <th><p class="width-140 white">料号</p></th>
                        <th><p class="width-140 white">品名</p></th>
                        <th><p class="width-100 white">单位</p></th>
                        <th><p class="width-70 white">需采购数量</p></th>
                        <th><p class="width-100 white">申请采购数量</p></th>
                        <th><p class="width-100 white">需求日期</p></th>
                        <th><p class="width-100 white">备注</p></th>
                    </tr>
                    </thead>
                    <tbody id="product_table" style="font-size: 12px;">
                    <?php foreach ($res['products'] as $k=>$v) {?>
                    <tr>
                        <!-- 订单子表ID -->
                        <input type="hidden" name="SalePurchasenotel[<?= $k ?>][lbill_id]" value="<?= $v['sol_id'] ?>">
                        <!-- 源单ID -->
                        <input type="hidden" name="SalePurchasenotel[<?= $k ?>][origin_id]" value="<?= $v['sol_id'] ?>">
                        <!-- 上级单ID -->
                        <input type="hidden" name="SalePurchasenotel[<?= $k ?>][p_bill_id]" value="<?= $v['sol_id'] ?>">
                        <input type="hidden" name="SalePurchasenotel[<?= $k ?>][pdt_id]" value="<?= $v['pdt_id'] ?>">
                        <input type="hidden" name="SalePurchasenotel[<?= $k ?>][bill_quantity]" value="<?= $v['sapl_quantity'] ?>">
                        <input type="hidden" name="SalePurchasenotel[<?= $k ?>][require_qty]" value="<?= 0-$v['require_qty'] ?>">
                        <input type="hidden" class="gt" value="<?= (0-$v['require_note_qty'])*$res['gt'] ?>">

                        <td><?= $k+1 ?></td>
                        <td><input class="width-40 td-box" type="checkbox" name="choose[]" value="<?= $k ?>"></td>
                        <td><?= $v['pdt_no'] ?></td>
                        <td><?= $v['pdt_name'] ?></td>
                        <td ><?= $v['unit_name'] ?></td>
                        <td ><?= 0-$v['require_note_qty'] ?></td>
                        <td><input class="width-100 pur-qty" type="" name="SalePurchasenotel[<?= $k ?>][apply_qty]" value="<?= (0-$v['require_note_qty']) ?>"></td>
                        <td><input class="height-30 width-100 select-date-time easyui-validatebox "
                                   readonly="readonly"
                                   value="<?= $v["request_date"] ?>"
                                   name="SalePurchasenotel[<?= $k ?>][require_date]" placeholder="请选择"></td>
                        <td><input class="width-100 easyui-validatebox" data-options="validType:'length[0,100]'" name="SalePurchasenotel[<?= $k ?>][sonl_remark]" value="" ></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div>
                <input type="hidden" name="SalePurchasenoteh[comp_id]" value="<?= $res['comp_id'] ?>">
                <input type="hidden" name="SalePurchasenoteh[cust_id]" value="<?= $res['cust_id'] ?>">
                <input type="hidden" name="SalePurchasenoteh[bill_id]" value="<?= $res['soh_id'] ?>"> <!-- 订单ID -->
                <input type="hidden" name="SalePurchasenoteh[origin_id]" value="<?= $res['soh_id'] ?>"> <!-- 源单ID -->
                <input type="hidden" name="SalePurchasenoteh[p_bill_id]" value="<?= $res['soh_id'] ?>"> <!-- 上级单ID -->
                <input type="hidden" name="SalePurchasenoteh[notify_from]" value="<?= $res['staff_id'] ?>">
                <input type="hidden" name="SalePurchasenoteh[notify_foganid]" value="<?= $res['organization_id'] ?>">
                <input type="hidden" name="SalePurchasenoteh[notfy_status]" value="1">
                <input type="hidden" name="SalePurchasenoteh[create_by]" value="<?= $res['staff_id'] ?>">
                <input type="hidden" name="SalePurchasenoteh[update_by]" value="<?= $res['staff_id'] ?>">
            </div>
            <?php ?>
            <div></div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="text-center mb-20">
        <button type="button" class="button-blue mr-20" id="note">确定</button>
        <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
    </div>
</div>
<?php } ?>
<script>
    $(function () {
        //全选
        $(".table").on('click', "th input[type='checkbox']", function () {
            if ($(this).is(":checked")) {
                $('.table').find("td input[type='checkbox']").prop("checked", true);
            } else {
                $('.table').find("td input[type='checkbox']").prop("checked", false);
            }
        });

        $('#note').click(function(){
            if ($("#note-form").form('validate')) {
                $(this).attr('disabled',true);
                $.ajax({
                    type:'post',
                    dataType:'json',
                    data:$("#note-form").serialize(),
                    url:"<?= \yii\helpers\Url::to(['purchase-note?id=']) . $res['soh_id'] ?>",
                    success:function(data){
                        if(data.status == 1){
                            layer.alert("通知发送成功！",{icon:1,end: function () {
                                parent.window.location.reload();
                            }});
                        } else {
                            layer.alert(data.msg,{icon:1,end: function () {
                                $('#note').attr('disabled',false);
                            }});
                        }
                    }
                })
            }
        })

        $(".select-date-time").click(function () {
            jeDate({
                dateCell: this,
                isToday: false,
                zIndex: 8831,
//                format: "YYYY-MM-DD hh:mm",
                format: "YYYY-MM-DD",
                skinCell: "jedatedeep",
                isTime: true,
                ishmsVal: true,
                okfun: function (elem) {
                    $(elem).change();
                },
                //点击日期后的回调, elem当前输入框ID, val当前选择的值
                choosefun: function (elem) {
                    $.parser.parse(obj);
                    return $(elem).click();
                },
                //清空时间
                clearfun: function (elem, val) {
                    $(".use_day,.use_hour,.use_minute").val('');
                    $(elem).change();
                },
            });
        })

        $(".pur-qty").change(function () {
            num = $(this).parent().parent().find('.gt').val() - $(this).val();
            $(this).validatebox({validType:'gt['+ num +']'});
        })
    })
</script>