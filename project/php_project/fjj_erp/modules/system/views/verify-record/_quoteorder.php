<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/7
 * Time: 16:22
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = '报价单审核';
$this->params['homeLike'] = ['label' => '审核'];
$this->params['breadcrumbs'][] = ['label' => '待审核申请单列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .label-width-big {
        width: 100px;
    }

    .label-width {
        width: 80px;
    }

    .value-width {
        width: 200px;
    }
    .width-100{
        width:100px;
    }
    .width-150{
        width:150px;
    }
    .width-80{
        width:80px;
    }
    .ml-10 {
        margin-left: 10px;
    }

    .ml-30 {
        margin-left: 30px;
    }

    .width-150 {
        width: 150px;
    }

    .width-40 {
        width: 40px;
    }

    thead tr th p {
        color: white;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .pb-10 {
        padding-bottom: 10px;
    }
    .text-no-next {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        display: inline-block;
    }
</style>
<div <?= Yii::$app->controller->action->id=='verify'?'class="content"':'style="margin:5px;"'; ?>>
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" name="id" id="cid" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <h2 class="head-first">
        <?= $this->title; ?>
    </h2>
    <div class="border-bottom mb-20 pb-10">
        <?= Html::button('通过', ['class' => 'button-blue width-80 opt-btn', 'id' => 'pass']) ?>
        <?= Html::button('驳回', ['class' => 'button-blue width-80 opt-btn', 'id' => 'reject']) ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
    <h2 class="head-second">
        基本信息
    </h2>
    <div class="mb-10 ml-20">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">报价单号<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['price_no'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="15%">关联需求单号<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['saph_code'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">下单时间<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['price_date'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="15%">订单来源<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['origin_svalue'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">订单类型<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['business_value'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="15%">合同编号<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['contract_no'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">客户全称<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['customer']['cust_sname'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="15%">客户代码<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['cust_code'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">联系人<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['cust_contacts'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="15%">联系电话<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['cust_tel2'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">客户地址<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['cust_addr'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="15%">公司电话<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['cust_tel1'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">交易法人<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['company'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="5%">交易模式<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $data['trade_mode_sname'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">交易币别<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><input type="hidden" id="cur_id"
                                                                         value="<?= $data['cur_id'] ?>"><?= $data['currency_name'] ?>
                </td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="15%">发票类型<label>：</label></td>
                <td class="no-border vertical-center" width="32%"><?= $data['invoice_type_name'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">发票抬头<label>：</label></td>
                <td class="no-border vertical-center" width="32%" colspan="4"><?= $data['invoice_title'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">发票抬头地址<label>：</label></td>
                <td class="no-border vertical-center" width="32%" colspan="4"><?= $data['invoice_Title_Addr'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">发票寄送地址<label>：</label></td>
                <td class="no-border vertical-center" width="32%" colspan="4"><?= $data['invoice_Address'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">收货人<label>：</label></td>
                <td class="no-border vertical-center" width="32%" colspan="4"><?= $data['receipter'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">联系电话<label>：</label></td>
                <td class="no-border vertical-center" width="32%" colspan="4"><?= $data["receipter_Tel"] . (!empty($data["addr_tel"]) ? "  /  " . $data["addr_tel"] : "") ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">收货地址<label>：</label></td>
                <td class="no-border vertical-center" width="32%" colspan="4"><?= $data['receipt_Address'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">附件<label>：</label></td>
                <td class="no-border vertical-center" width="32%" colspan="4">
                    <?php foreach ($data['files'] as $key => $val) { ?>
                        <a class="text-center width-150 color-w ml-10" target="_blank"
                           href="<?= \Yii::$app->ftpPath['httpIP'] ?>/ord/req/<?= explode('_', trim($val['file_new'], '_'))[0] ?>/<?= $val['file_new'] ?>"><?= $val["file_old"] ?></a>
                    <?php } ?>
                </td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">订单备注说明<label>：</label></td>
                <td class="no-border vertical-center" width="32%" colspan="4"><?= $data['remark'] ?></td>
            </tr>
        </table>
        <input type="hidden" id="myAddress" value="<?= $data['receipt_areaid'] ?>">
    </div>
    <h2 class="head-second">
        订单商品信息 <span class="text-right float-right">
    </h2>
    <div class="mb-10 tablescroll" style="overflow-x: scroll">
        <table class="table">
            <thead>
            <tr>
                <th><p class="width-40 color-w">序号</p></th>
                <th><p class="width-150 color-w">料号</p></th>
                <th><p class="width-150 color-w">品名</p></th>
                <th><p class="width-150 color-w">下单数量</p></th>
                <th><p class="width-150 color-w">商品定价（含税）</p></th>
                <th><p class="width-150 color-w"><span class="red">*</span> 销售单价（含税）</p></th>
                <th><p class="width-150 color-w">配送方式</p></th>
                <th><p class="width-150 color-w">运输方式</p></th>
                <th><p class="width-150 color-w">自提仓库</p></th>
                <th><p class="width-150 color-w">运费（含税）</p></th>
                <th><p class="width-150 color-w"><span class="red">*</span> 需求交期</p></th>
                <th><p class="width-150 color-w"><span class="red">*</span> 交期</p></th>
                <th><p class="width-150 color-w">税率（%）</p></th>
                <th><p class="width-150 color-w">折扣率（%）</p></th>
                <th><p class="width-150 color-w">商品总价（含税）</p></th>
                <th><p class="width-150 color-w">折扣后金额</p></th>
                <th><p class="width-150 color-w">备注</p></th>
            </tr>
            </thead>
            <tbody id="product_table">
            <?php foreach ($dt as $key => $val) { ?>
                <tr>
                    <td><span class="width-40"><?= ($key + 1) ?></span></td>
                    <td><p class="text-center width-150 text-no-next"><?= $val["pdt_no"] ?></p>
                        <input type="hidden" name="PriceDt[<?= $key ?>][price_dt_id]" value="<?= $val["price_dt_id"] ?>"/>
                    </td>
                    <td><p class="text-center width-150 text-no-next"><?= $val["pdt_name"] ?></p></td>
                    <td><p class="text-center width-150 text-no-next"><?= sprintf("%.2f",$val["sapl_quantity"]) ?></p></td>
                    <td>
                        <p class="text-center width-150 text-no-next"><?= ($val["price"] != '-1' ? number_format($val["price"], '5', '.', '') : "面议") ?></p>
                    </td>
                    <td><p class="text-center width-150 text-no-next"><?= sprintf("%.5f",$val["uprice_tax_o"]) ?></p></td>
                    <td><p class="text-center width-150 text-no-next"><?= $val['distribution_name'] ?></p></td>
                    <td><p class="text-center width-150 text-no-next"><?= $val['transport_name'] ?></p></td>
                    <td><p class="text-center width-150 text-no-next"><?= $val['wh_name'] ?></p></td>
                    <td>
                        <p class="text-center width-150 text-no-next"><?= empty($val["tax_freight"]) ? "0" : sprintf("%.2f",$val["tax_freight"]) ?></p>
                    </td>
                    <td><p class="text-center width-150 text-no-next"><?= $val["request_date"] ?></p></td>
                    <td><p class="text-center width-150 text-no-next"><?= $val["consignment_date"] ?></p></td>
                    <td><p class="text-center width-150 text-no-next"><?= sprintf("%.2f",$val["cess"]) ?></p></td>
                    <td><p class="text-center width-150 text-no-next"><?= sprintf("%.2f",$val["discount"]) ?></p></td>
                    <td><p class="text-center width-150 text-no-next"><?= sprintf("%.2f",$val["price"] * $val['sapl_quantity']) ?></p></td>
                    <td><p class="text-center width-150 text-no-next"><?= sprintf("%.2f",$val['uprice_tax_o'] * $val['sapl_quantity']) ?></p></td>
                    <td><p class="text-center width-150 text-no-next"><?= $val["sapl_remark"] ?></p></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="mb-10 overflow-auto">
        <div class="inline-block float-right">
            <label class="label-width-big label-align">总运费(含税)</label><label>：</label>
            <span class="label-width-big value-align"
                  style="color:#FF6600;"><?= $data['currency_mark'] . sprintf("%.2f", $data['tax_freight']) ?></span>
            <label class="label-width-big label-align">商品总金额(含税)</label><label>：</label>
            <span class="label-width-big value-align"
                  style="color:#FF6600;"> <?= $data['currency_mark'] . sprintf("%.2f", $data['prd_org_amount']) ?></span>
            <label class="label-width-big label-align">订单总金额(含税)</label><label>：</label>
            <span class="label-width-big value-align"
                  style="color:#FF6600;"> <?= $data['currency_mark'] . sprintf("%.2f", $data['req_tax_amount']) ?></span>
        </div>
    </div>
    <h2 class="head-second">
        支付方式选择
    </h2>
    <?php if ($data['priceValue']['pac_sname'] === '预付款') { ?>
        <?php if ($data['priceValue']['pay_type_name'] === '全额') { ?>
            <div class="mb-10 ml-20">
                <table width="90%" class="no-border vertical-center mb-10"
                       style="border-collapse:separate; border-spacing:5px;">
                    <tr class="no-border mb-10">
                        <td class="no-border vertical-center label-align" width="15%">付款方式<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="20%"><?= $data['priceValue']['pac_sname'] ?></td>
                        <td class="no-border vertical-center label-align" width="15%">支付类型<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="20%"><?= $data['priceValue']['pay_type_name'] ?></td>
                        <td class="no-border vertical-center label-align" width="15%">需付款金额<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="20%"><?= $data['currency_mark'] . sprintf('%.2f',$data['req_tax_amount']) ?></td>
                    </tr>
                </table>
            </div>
        <?php } else if ($data['priceValue']['pay_type_name'] === '分期') { ?>
            <div class="mb-10 ml-20">
                <table width="90%" class="no-border vertical-center mb-10"
                       style="border-collapse:separate; border-spacing:5px;">
                    <tr class="no-border mb-10">
                        <td class="no-border vertical-center label-align" width="15%">付款方式<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="32%"><?= $data['priceValue']['pac_sname'] ?></td>
                        <td width="4%" class="no-border vertical-center label-align"></td>
                        <td class="no-border vertical-center label-align" width="15%">支付类型<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="32%"><?= $data['priceValue']['pay_type_name'] ?></td>
                    </tr>
                    <tr class="no-border mb-10">
                        <td class="no-border vertical-center label-align" width="15%">分几期<label>：</label></td>
                        <td class="no-border vertical-center value-align" width="32%"><?= count($data['pay']) ?> 期</td>
                        <td width="4%" class="no-border vertical-center label-align"></td>
                        <td class="no-border vertical-center label-align" width="15%">需付款金额<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="32%"><?= $data['currency_mark'] . sprintf('%.2f',$data['req_tax_amount']) ?></td>
                    </tr>
                    <?php foreach ($data['pay'] as $key => $val) { ?>
                        <tr class="no-border mb-10">
                            <td class="no-border vertical-center label-align" width="15%">第<?= $key + 1 ?>
                                期付款时间<label>：</label></td>
                            <td class="no-border vertical-center value-align" width="32%"><?= $val["stag_date"] ?></td>
                            <td width="4%" class="no-border vertical-center label-align"></td>
                            <td class="no-border vertical-center label-align" width="15%">第<?= $key + 1 ?>
                                期支付金额<label>：</label></td>
                            <td class="no-border vertical-center value-align" width="32%"><?= $data['currency_mark'].sprintf('%.2f',$val["stag_cost"]) ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="mb-10 ml-20">
            <div class="mb-10">
                <label class="width-100 label-align">付款方式<label>：</label></label>
                <span class="width-150 value-align"><?= $data['priceValue']['pac_sname'] ?></span>
                <label class="width-100 label-align">需付款金额<label>：</label></label>
                <span class="width-150 value-align"><?= $data['currency_mark'] . sprintf('%.2f',$data['req_tax_amount']) ?></span>
            </div>
            <?php foreach ($credits['rows'] as $k => $v) { ?>
            <div class="mb-10">
                <label class="width-100 label-align">信用额度类型<label>：</label></label>
                <span class="width-100 value-align"><?= $v['credit_name'] ?></span>
                <label class="width-80 label-align">总额度<label>：</label></label>
                <span class="width-150 value-align"><?= $data['currency_mark'] . $v['approval_limit'] ?></span>
                <label class="width-80 label-align">剩余额度<label>：</label></label>
                <span class="width-150 value-align"><?= $data['currency_mark'] . $v['surplus_limit'] ?></span>
                <label class="width-80 label-align">支付金额<label>：</label></label>
                <span class="width-150 value-align"><?= $data['currency_mark'] . sprintf('%.2f',$data['pay'][$k]["stag_cost"]) ?></span>
            </div>
            <?php } ?>
<!--            <table width="90%" class="no-border vertical-center mb-10"-->
<!--                   style="border-collapse:separate; border-spacing:5px;">-->
<!--                <tr class="no-border mb-10">-->
<!--                    <td class="no-border vertical-center label-align" width="15%">付款方式<label>：</label></td>-->
<!--                    <td class="no-border vertical-center value-align"-->
<!--                        width="10%">--><?//= $data['priceValue']['pac_sname'] ?><!--</td>-->
<!--                    <td class="no-border vertical-center label-align" width="15%">需付款金额<label>：</label></td>-->
<!--                    <td class="no-border vertical-center value-align" width="50%"-->
<!--                        colspan="5">--><?//= $data['currency_mark'] . $data['req_tax_amount'] ?><!--</td>-->
<!--                </tr>-->
<!--                --><?php //foreach ($credits['rows'] as $k => $v) { ?>
<!--                    <tr class="no-border mb-10">-->
<!--                        <td class="no-border vertical-center label-align" width="15%">信用额度类型<label>：</label></td>-->
<!--                        <td class="no-border vertical-center value-align" width="10%">--><?//= $v['credit_name'] ?><!--</td>-->
<!--                        <td class="no-border vertical-center label-align" width="15%">总额度<label>：</label></td>-->
<!--                        <td class="no-border vertical-center value-align"-->
<!--                            width="10%">--><?//= $data['currency_mark'] . $v['approval_limit'] ?><!--</td>-->
<!--                        <td class="no-border vertical-center label-align" width="15%">剩余额度<label>：</label></td>-->
<!--                        <td class="no-border vertical-center value-align"-->
<!--                            width="10%">--><?//= $data['currency_mark'] . $v['surplus_limit'] ?><!--</td>-->
<!--                        <td class="no-border vertical-center label-align" width="15%">支付额度<label>：</label></td>-->
<!--                        <td class="no-border vertical-center value-align"-->
<!--                            width="10%">--><?//= $data['currency_mark'] . number_format($data['pay'][$k]["stag_cost"], '2', '.', '') ?><!--</td>-->
<!--                    </tr>-->
<!---->
<!--                --><?php //} ?>
            </table>
        </div>

    <?php } ?>
    <h2 class="head-second">
        销售员信息
    </h2>
    <div class="mb-10 ml-20">
        <table width="90%" class="no-border vertical-center mb-10"
               style="border-collapse:separate; border-spacing:5px;">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">销售员<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $seller["staff_code"] ?></td>
                <td class="no-border vertical-center label-align" width="15%">姓名<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $seller['staff']["name"] ?></td>
                <td class="no-border vertical-center label-align" width="15%">客户经理人<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $seller['isrule'] == 1?$seller['staff_code'].'&nbsp;'.$seller['staff']['name']:$seller["leader"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">销售部门<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="20%"><?= $seller["staff"]["organization_name"] ?></td>
                <td class="no-border vertical-center label-align" width="15%">销售区域<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $seller["csarea"] ?></td>
                <td class="no-border vertical-center label-align" width="15%">销售点<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $seller["sts_sname"] ?></td>
            </tr>
        </table>
    </div>
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
    $(function () {
        $("#record").datagrid({
            url: "<?= Url::to(['/system/verify-record/load-record', 'id' => $id, 'token' => $token]);?>",
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
                showEmpty($(this), data.total, 0);
                datagridTip('#record');
            }
        });
        $("#pass").on("click", function () {
            var id = $('#cid').val();
            $.fancybox({
                href: "<?=Url::to(['pass-opinion'])?>?id=" + id,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 435,
                height: 240
            });
        });
        $("#reject").on("click", function () {
            var id = $('#cid').val();
            $.fancybox({
                href: "<?=Url::to(['opinion'])?>?id=" + id,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 435,
                height: 240
            });
        });

    });
</script>
