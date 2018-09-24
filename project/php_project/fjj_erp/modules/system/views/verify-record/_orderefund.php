<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/20
 * Time: 13:37
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = '退款单审核';
$this->params['homeLike'] = ['label' => '审核'];
$this->params['breadcrumbs'][] = ['label' => '待审核申请单列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .mb-20{
        margin-bottom: 20px;
    }
    .pb-10{
        padding-bottom: 10px;
    }
    .width-150{
        width:150px;
    }
    .value-width{
        width:200px;
    }
    .space-20{
        width:100%;
        height:20px;
    }
    #product_table tr td p{
        white-space: nowrap;
        display: inline-block;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" name="id" id="cid" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <h2 class="head-first">
        <?= $this->title; ?>
    </h2>
    <div class="border-bottom mb-20 pb-10">
        <?= Html::button('通过', ['class' => 'button-blue width-80 opt-btn','id'=>'pass']) ?>
        <?= Html::button('驳回', ['class' => 'button-blue width-80 opt-btn','id' => 'reject']) ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
    <div>
        <h2 class="head-second">
            退款基本信息
        </h2>
        <div class="mb-10 ml-20">
            <table width="90%" class="no-border vertical-center mb-10" style="border-collapse:separate; border-spacing:5px;">
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">退款单号<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['refund_no'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">单据状态<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['refund_status'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">关联订单号<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['ord_no'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">交易法人<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['company_name'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">订单类型<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['ordType'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">购买日期<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['ordDate'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">订单状态<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['ordStatus'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">客户代码<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['custCode'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">客户名称<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['custName'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">公司电话<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['custTel1'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">联系人<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['cust_contacts'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">联系电话<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['custTel2'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">订单负责人<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['manager'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">负责人电话<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['mg_tel'] ?></td>
                </tr>
            </table>
        </div>
        <h2 class="head-second">
            商品信息
        </h2>
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
                <?php if(!empty($dt)){ ?>
                    <?php foreach ($dt as $key => $val){ ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['part_no'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['pdt_name'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['tp_spec'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['uprice_tax_o'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['sapl_quantity'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['unit_name'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['rfndType'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['rfnd_nums'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['rfnd_amount'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['remarks'] ?></p></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <p class="float-right">
            <label class="label-align">退款总金额 <label>：</label></label>
            <span class="value-align value-width" style="color:#f60;"><?= $data['currency_mark']. bcsub($data['tax_fee'],0,2) ?></span>
            <label class="label-align">订单总金额 <label>：</label></label>
            <span class="value-align value-width" style="color:#f60;"><?= $data['currency_mark']. bcsub($data['req_tax_amount'],0,2) ?></span>
        </p>
    </div>
    <div class="overflow-auto space-20"></div>
    <h2 class="head-second">
        签核记录
    </h2>
    <div class="mb-30">
        <div class="mb-20">
            <div id="record" width="100%"></div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("#record").datagrid({
            url: "<?= Url::to(['/system/verify-record/load-record','id'=>$id,'token'=>$token]);?>",
            rownumbers: true,
            method: "get",
            idField: "vcoc_id",
            loadMsg: false,
//                    pagination: true,
            singleSelect: true,
//                    pageSize: 5,
//                    pageList: [5, 10, 15],
            columns: [[
                {
                    field: "verifyOrg", title: "审核节点", width: 150
                },
                {field: "verifyName", title: "审核人", width: 150},
                {
                    field: "vcoc_datetime", title: "审核时间", width: 156
                },
                {field: "verifyStatus", title: "操作", width: 150},
                {
                    field: "vcoc_remark", title: "审核意见", width: 200
                },
                {
                    field: "vcoc_computeip", title: "审核IP", width: 150
                },

            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
            }
        });
        $("#pass").on("click", function () {
            var id = $('#cid').val();
            $.fancybox({
                href:"<?=Url::to(['pass-opinion'])?>?id="+id,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:435,
                height:240
            });
        });
        $("#reject").on("click", function () {
            var id = $('#cid').val();
            $.fancybox({
                href:"<?=Url::to(['opinion'])?>?id="+id,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:435,
                height:240
            });
        });
    })
</script>
