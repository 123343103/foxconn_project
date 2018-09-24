<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\OrdRefund */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .label-width{
        width:80px;
    }
    .value-width{
        width:200px;
    }
    .width-40{
        width: 40px;
    }
    .width-100{
        width: 100px;
    }
    .width-150{
        width: 150px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .ml-40{
        margin-left: 40px;
    }
    thead tr th p {
        color: white;
    }
    .space-20{
        height:20px;
        width:100%;
    }
    #product_table tr td p{
        white-space: nowrap;
        display: inline-block;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
<?php $form = ActiveForm::begin(['id'=>'add-form']); ?>
<h2 class="head-second">退款基本信息</h2>
<div class="mb-10 ml-20">
    <table width="90%" class="no-border vertical-center mb-10" style="border-collapse:separate; border-spacing:5px;">
        <tr class="no-border mb-10">
            <td class="no-border vertical-center label-align" width="13%">关联订单号<label>：</label></td>
            <td class="no-border vertical-center" width="35%">
                <input type="hidden" name="OrdRefund[ord_no]" id="ord_no" value="<?= $data['ord_no'] ?>">
                <?= $data['ord_no'] ?>
            </td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td class="no-border vertical-center label-align" width="13%">交易法人<label>：</label></td>
            <td class="no-border vertical-center" width="35%"><?= $data['company_name'] ?></td>
        </tr>
        <tr class="no-border mb-10">
            <td class="no-border vertical-center label-align" width="13%">订单类型<label>：</label></td>
            <td class="no-border vertical-center" width="35%"><?= $data['ordType'] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td class="no-border vertical-center label-align" width="13%">下单日期<label>：</label></td>
            <td class="no-border vertical-center" width="35%"><?= date('Y-m-d',strtotime($data['ord_date'])) ?></td>
        </tr>
        <tr class="no-border mb-10">
            <td class="no-border vertical-center label-align" width="13%">订单状态<label>：</label></td>
            <td class="no-border vertical-center" width="35%"><?= $data['ordStatus'] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td class="no-border vertical-center label-align" width="13%">客户代码<label>：</label></td>
            <td class="no-border vertical-center" width="35%"><?= $data['cust_code'] ?></td>
        </tr>
        <tr class="no-border mb-10">
            <td class="no-border vertical-center label-align" width="13%">客户名称<label>：</label></td>
            <td class="no-border vertical-center" width="35%"><?= $data['customer']['cust_sname'] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td class="no-border vertical-center label-align" width="13%">公司电话<label>：</label></td>
            <td class="no-border vertical-center" width="35%"><?= $data['cust_tel1'] ?></td>
        </tr>
        <tr class="no-border mb-10">
            <td class="no-border vertical-center label-align" width="13%">联系人<label>：</label></td>
            <td class="no-border vertical-center" width="35%"><?= $data['cust_contacts'] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td class="no-border vertical-center label-align" width="13%">联系电话<label>：</label></td>
            <td class="no-border vertical-center" width="35%"><?= $data['cust_tel2'] ?></td>
        </tr>
        <tr class="no-border mb-10">
            <td class="no-border vertical-center label-align" width="13%">订单负责人<label>：</label></td>
            <td class="no-border vertical-center" width="35%">
                <input type="hidden" name="OrdRefund[manger]" id="manger" value="<?= $data['staff']['staff_id'] ?>">
                <?= $data['staff']['code'] ?>--<?= $data['staff']['name'] ?>
            </td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td class="no-border vertical-center label-align" width="13%">负责人电话<label>：</label></td>
            <td class="no-border vertical-center" width="35%">
                <input type="hidden" name="OrdRefund[mg_tel]" id="mg_tel" value="<?= $data['staff']['tel'] ?>">
                <?= $data['staff']['tel'] ?>
            </td>
        </tr>
    </table>
</div>
<h2 class="head-second">商品信息</h2>
<div class="mb-10 ml-20" style="overflow-x: scroll">
    <table class="table">
        <thead>
            <tr>
                <th><p class="width-40">序号</p></th>
                <th><p class="width-150">料号</p></th>
                <th><p class="width-150">品名</p></th>
                <th><p class="width-150">规格/型号</p></th>
                <th><p class="width-150">平均单价</p></th>
                <th><p class="width-150">订单数量</p></th>
                <th><p class="width-150">单位</p></th>
                <th><p class="width-150">退款类型</p></th>
                <th><p class="width-150">退/换货数量</p></th>
                <th><p class="width-150">退款金额</p></th>
                <th><p class="width-150">退/换货原因</p></th>
            </tr>
        </thead>
        <tbody id="product_table">
            <?php if(Yii::$app->controller->action->id == 'create'){ ?>
                <?php if(!empty($dt)){ ?>
                    <?php foreach ($dt as $key => $val){ ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td>
                                <p class="text-center width-150 text-no-next">
                                    <input type="hidden" name="OrdRefundDt[<?= $key ?>][sol_id]" value="<?= $val['ord_dt_id'] ?>"><?= $val['part_no'] ?>
                                </p>
                            </td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['pdt_name'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['tp_spec'] ?></p></td>
                            <td class="uprice_tax"><?= $val['uprice_tax_o'] ?></td>
                            <td class="sapl_quantity_<?= $key ?>"><?= $val['sapl_quantity'] ?></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['unit_name'] ?></p></td>
                            <td>
                                <select name="OrdRefundDt[<?= $key ?>][rfnd_type]" class="width-150 value-align rfnd_type">
                                    <option value="">请选择...</option>
                                    <?php foreach ($downList["refundType"] as $k => $v) { ?>
                                        <option value="<?= $v["bsp_id"] ?>" <?= isset($queryParam['OrdRefundDt']['rfnd_type']) && $queryParam['OrdRefundDt']['rfnd_type'] == $v["bsp_id"] ? "selected" : null ?>><?= $v["bsp_svalue"] ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" value="<?= $key ?>">
                                <input type="number" class="value-align width-150 rfnd_nums easyui-validatebox <?= $key ?>" data-options="validType:'numCompare'" name="OrdRefundDt[<?= $key ?>][rfnd_nums]" maxlength="19" onblur="totalPrice(this)">
                            </td>
                            <td><input type="text" class="value-align width-150 rfnd_amount easyui-validatebox" data-options="validType:'priceCompare'" name="OrdRefundDt[<?= $key ?>][rfnd_amount]" maxlength="19"></td>
                            <td><input type="text" class="value-align width-150" name="OrdRefundDt[<?= $key ?>][remarks]" maxlength="200"></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            <?php }else if(Yii::$app->controller->action->id == 'update'){ ?>
                <?php if(!empty($dt)){ ?>
                    <?php foreach ($dt as $key => $val){ ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td>
                                <p class="text-center width-150 text-no-next">
                                <input type="hidden" name="OrdRefundDt[<?= $key ?>][rfnd_dt_id]" value="<?= $val['rfnd_dt_id'] ?>">
                                <input type="hidden" name="OrdRefundDt[<?= $key ?>][sol_id]" value="<?= $val['ord_dt_id'] ?>">
                                <?= $val['part_no'] ?>
                                </p>
                            </td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['pdt_name'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['tp_spec'] ?></p></td>
                            <td class="uprice_tax"><?= $val['uprice_tax_o'] ?></td>
                            <td class="sapl_quantity_<?= $key ?>"><?= $val['sapl_quantity'] ?></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['unit_name'] ?></p></td>
                            <td>
                                <select name="OrdRefundDt[<?= $key ?>][rfnd_type]" class="width-150 value-align rfnd_type">
                                    <option value="">请选择...</option>
                                    <?php foreach ($downList["refundType"] as $k => $v) { ?>
                                        <option value="<?= $v["bsp_id"] ?>" <?= isset($val['rfnd_type']) && $val['rfnd_type'] == $v["bsp_id"] ? "selected" : null ?>><?= $v["bsp_svalue"] ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" value="<?= $key ?>">
                                <input type="number" class="value-align width-150 rfnd_nums easyui-validatebox <?= $key ?>" data-options="validType:'numCompare'" name="OrdRefundDt[<?= $key ?>][rfnd_nums]" maxlength="19" onblur="totalPrice(this)" value="<?= bcsub($val['rfnd_nums'],0,2) ?>">
                            </td>
                            <td><input type="text" class="value-align width-150 rfnd_amount easyui-validatebox" data-options="validType:'priceCompare'" name="OrdRefundDt[<?= $key ?>][rfnd_amount]" maxlength="19" value="<?= bcsub($val['rfnd_amount'],0,2) ?>"></td>
                            <td><input type="text" class="value-align width-150" name="OrdRefundDt[<?= $key ?>][remarks]" maxlength="200" value="<?= $val['remarks'] ?>"></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="mb-10">
    <div class="inline-block float-right">
        <label class="width-100 label-align" style="color:#FF6600;">退款总金额</label><label style="color:#FF6600;">：</label>
        <input type="hidden" name="OrdRefund[tax_fee]" class="tax_fee" value="">
        <span style="color:#FF6600;" class="width-100 value-align total_refund"><?= $data['currency_mark']. bcsub($model['tax_fee'],0,2) ?></span>
        <label class="width-100 label-align" style="color:#FF6600;">订单总金额(含税)</label><label style="color:#FF6600;">：</label>
        <span style="color:#FF6600;" class="width-100 value-align req_tax_amount"><?= $data['currency_mark']. bcsub($data['req_tax_amount'],0,2) ?></span>
    </div>
</div>
<div class="space-20 overflow-auto"></div>
<div class="text-center">

    <button type="submit" class="button-blue-big save-form ml-40">提交</button>
    <button class="button-white-big" onclick="window.location.href='<?= Url::to(["index"]) ?>'" type="button">取&nbsp;消
    </button>
</div>
<?php ActiveForm::end(); ?>

<script>
    $(function(){
        var id = '<?= $id ?>';
        <?php if(Yii::$app->controller->action->id == 'create'){ ?>
//        $('.sub').click(function(){
//            $('#add-form').attr('action','<?//= Url::to(["create"]) ?>//');
//        })
        $('.save-form').click(function(){
            $('#add-form').attr('action','<?= Url::to(["create"]) ?>?is_apply=1');
        })
        ajaxSubmitForm($('#add-form'));
        <?php } ?>
        <?php if(Yii::$app->controller->action->id == 'update'){ ?>
//        $('.sub').click(function(){
//            $('#add-form').attr('action','<?//= Url::to(["update"]) ?>//?id='+id);
//        })
        $('.save-form').click(function(){
            $('#add-form').attr('action','<?= Url::to(["update"]) ?>?id='+ id +'&is_apply=1');
        })
        ajaxSubmitForm($('#add-form'));
        <?php } ?>
        /*退货数量与订单数量比较 --start--*/
        $.extend($.fn.validatebox.defaults.rules, {
            numCompare: {
                validator: function () {
                    var num = parseFloat($(this).val());
                    var n = $(this).prev().val();
                    var sum = parseFloat($('.sapl_quantity_'+n).html());
                    return (num <= sum);
                },
                message: '退换货数量不能大于订单总数'
            },
            priceCompare: {
                validator: function () {
                    var $lis = $(this).parent().siblings();
                    var uprice_tax_o = $lis.eq(4).text();
                    var sapl_quantity = $lis.eq(5).text();
                    var total = uprice_tax_o*sapl_quantity;
                    var rfnd_amount = parseFloat($(this).val());
                    return (rfnd_amount <= total);
            },
                message: '退换货总金额不能大于订单总金额'
            }
        });

        /*退货数量与订单数量比较 --end--*/
        /*选择退款类型,金额原因是否加readonly和必要性验证 --start--*/
        var trs = $('#product_table').children();
        for(var i=0;i<=trs.length;i++){
            var type = $(trs[i]).find('select.rfnd_type').val();
            if(type == ''){
                $(trs[i]).find('input.rfnd_nums').attr('readonly','readonly');
                $(trs[i]).find('input.rfnd_amount').attr('readonly','readonly');
            }else{
                $(trs[i]).find('input.rfnd_nums').removeAttr('readonly');
                $(trs[i]).find('input.rfnd_amount').removeAttr('readonly');
            }
        }
//        var type = $('#rfnd_type').val();
//        if(type == ''){
//            $('#rfnd_type').parent().next('td').children('input').attr('readonly','readonly');
//            $('#rfnd_type').parent().next('td').next('td').children('input').attr('readonly','readonly');
//        }
        $('.rfnd_type').on('change',function(){
            for(var i=0;i<=trs.length;i++){
                var type = $(trs[i]).find('select.rfnd_type').val();
                if(type == ''){
                    $(trs[i]).find('input.rfnd_nums').validatebox({required:false}).attr('readonly','readonly');
                    $(trs[i]).find('input.rfnd_amount').validatebox({required:false}).attr('readonly','readonly');
                }else{
                    $(trs[i]).find('input.rfnd_nums').validatebox({required:true}).removeAttr('readonly');
                    $(trs[i]).find('input.rfnd_amount').validatebox({required:true}).removeAttr('readonly');
                }
            }
        })
        /*选择退款类型,金额原因是否加readonly和必要性验证 --end--*/

    });

    /*当退款数量发生改变时,退款总金额发生改变*/
    $(document).on('change','.rfnd_nums',function(){
        var trs = $('#product_table').children();
        var total = 0;
        for(var i = 0;i<=trs.length;i++){
            var uprice_tax = $(trs[i]).find('td.uprice_tax').text();
            var rfnd_nums = $(trs[i]).find('input.rfnd_nums').val();
            if(rfnd_nums != '' && uprice_tax != ''){
                uprice_tax = parseFloat(uprice_tax);
                rfnd_nums = parseFloat(rfnd_nums);
                var total_hs = uprice_tax*rfnd_nums;
                total += total_hs;
            }
        }
        $('.total_refund').text(total);
        $('.tax_fee').val(total);
        var req_tax_amount = $('.req_tax_amount').text();
        if(total > req_tax_amount){
            layer.alert('退款总金额不能大于订单总金额', {icon: 2,time:5000});
        }
    });
    /*当退款金额发生改变时,退款总金额发生改变*/
    $(document).on('change','.rfnd_amount',function(){
        var trs = $('#product_table').children();
        var total = 0;
        for(var i = 0;i<=trs.length;i++){
            var uprice_tax = $(trs[i]).find('td.uprice_tax').text();
            var rfnd_amount = $(trs[i]).find('input.rfnd_amount').val();
            if(rfnd_amount != '' && uprice_tax != ''){
                uprice_tax = parseFloat(uprice_tax);
                rfnd_amount = parseFloat(rfnd_amount);
                total += rfnd_amount;
            }
        }
        $('.total_refund').text(total);
        $('.tax_fee').val(total);
        var req_tax_amount = $('.req_tax_amount').text();
        if(total > req_tax_amount){
            layer.alert('退款总金额不能大于订单总金额', {icon: 2,time:5000});
        }
    });


    /*计算退款金额*/
    function totalPrice(obj){
        /*获取所有td级元素*/
        var $lis = $(obj).parent().siblings();
        /*获取单价*/
        var uprice_tax_o = $lis.eq(4).text();
        /*获取退货数量*/
        var rfnd_nums = $(obj).val();
        /*计算金额*/
        var total = uprice_tax_o*rfnd_nums;
        if(total == 0){
            total = '';
        }
        $(obj).parent().next('td').children('input').val(total);
    }

</script>


