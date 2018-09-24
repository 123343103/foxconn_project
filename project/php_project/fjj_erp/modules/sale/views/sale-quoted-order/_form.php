<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\JeDateAsset;

JeDateAsset::register($this);
/**
 * User: F1676624
 * Date: 2017/6/19
 */
//$cust_attachment = isset($quotedHModel["cust_attachment"]) ? unserialize($quotedHModel["cust_attachment"]) : null;
//$seller_attachment = isset($quotedHModel["seller_attachment"]) ? unserialize($quotedHModel["seller_attachment"]) : null;
$creditId = $prepayId = $allpay = $fenqiPay = 0;
foreach ($downList["payment"] as $key => $val) {
    if ($val['pac_code'] == 'credit-amount') {
        $creditId = $val["pac_id"];
    }
    if ($val['pac_code'] == 'pre-pay') {
        $prepayId = $val["pac_id"];
    }
}
foreach ($downList["payType"] as $key => $val) {
    if ($val['bsp_svalue'] == '全额') {
        $allpay = $val["bsp_id"];
    }
    if ($val['bsp_svalue'] == '分期') {
        $fenqiPay = $val["bsp_id"];
    }
}

foreach ($downList["dispatching"] as $key => $val) {
    if ($val['tran_sname'] == '上门自提') {
        $self_take = $val["tran_id"];
    }
    if ($val['tran_sname'] == '送货上门') {
        $no_take = $val["tran_id"];
    }
}

?>
<div class="">
    <style>
        .width-75 {
            width: 75px;
        }

        .width-40 {
            width: 40px;
        }

        .width-150 {
            width: 150px;
        }

        .width-100 {
            width: 100px;
        }

        .label-width {
            width: 90px;
        }

        .label-width-big {
            width: 100px;
        }

        .value-width {
            width: 200px;
        }

        .value-m {
            width: 152px;
        }

        .value-s {
            width: 120px;
        }

        .mt-5 {
            margin-top: 5px;
        }

        .mr-10 {
            margin-right: 10px;
        }

        .ml-10 {
            margin-left: 10px;
        }

        .ml-60 {
            margin-left: 60px;
        }

        .ml-90 {
            margin-left: 88px;
        }

        .ml-140 {
            margin-left: 140px;
        }

        .wd-547 {
            width: 5.47px;
        }

        .ml-155 {
            margin-left: 155px;
        }

        .wd-60 {
            width: 60px;
        }

        .wd-265 {
            width: 265px;
        }

        /*仅设为默认地址使用*/
        .grey {
            color: grey;
        }

        .address-select {
            border: 1px solid #cccccc;
            width: 330px;
            height: 85px;
            display: inline-block;
            padding: 5px;
            margin-right: 5px;
            cursor: pointer;
        }

        .address-add {
            border: 1px solid #cccccc;
            width: 330px;
            height: 85px;
            display: inline-block;
            padding: 5px;
            margin-right: 5px;
            cursor: pointer;
        }

        .text-no-next {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            display: inline-block;
        }

        .ml-40 {
            margin-left: 40px;
        }

        .space-40 {
            width: 100%;
            height: 40px;
        }

        input.read-only:read-only {
            background-color: rgb(255, 255, 255);
            border: 1px solid #fff;
        }

        thead tr th p {
            color: white;
        }

        .text-no-next {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            display: inline-block;
        }
    </style>

    <?php $form = ActiveForm::begin(
        ['id' => 'add-form', 'options' => ['enctype' => 'multipart/form-data']]
    ); ?>
    <h2 class="head-second">
        基本信息
    </h2>
    <div class="mb-10 ml-20">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">报价单号<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['price_no'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="13%">关联需求单号<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['saph_code'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">下单时间<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['price_date'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="13%">订单来源<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['origin_svalue'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">订单类型<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['business_value'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="13%">合同编号<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['contract_no'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">客户全称<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['customer']['cust_sname'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="13%">客户代码<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['cust_code'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">联系人<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['cust_contacts'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="13%">联系电话<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['cust_tel2'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">客户地址<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['cust_addr'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="13%">公司电话<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['cust_tel1'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">交易法人<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['company'] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="5%">交易模式<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $data['trade_mode_sname'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">交易币别<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><input type="hidden" id="cur_id"
                                                                         value="<?= $data['cur_id'] ?>"><?= $data['currency_name'] ?>
                </td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td class="no-border vertical-center label-align" width="13%">发票类型<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?= $data['invoice_type_name'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">发票抬头<label>：</label></td>
                <td class="no-border vertical-center" width="35%" colspan="4"><?= $data['invoice_title'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">发票抬头地址<label>：</label></td>
                <td class="no-border vertical-center" width="35%" colspan="4"><?= $data['invoice_Title_Addr'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">发票寄送地址<label>：</label></td>
                <td class="no-border vertical-center" width="35%" colspan="4"><?= $data['invoice_Address'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">收货人<label>：</label></td>
                <td class="no-border vertical-center" width="35%" colspan="4"><?= $data['receipter'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">联系电话<label>：</label></td>
                <td class="no-border vertical-center" width="35%" colspan="4"><?= $data['receipter_Tel'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">收货地址<label>：</label></td>
                <td class="no-border vertical-center" width="35%" colspan="4"><?= $data['receipt_Address'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">附件<label>：</label></td>
                <td class="no-border vertical-center" width="35%" colspan="4">
                    <?php foreach ($data['files'] as $key => $val) { ?>
                        <a class="text-center width-150 color-w ml-10" target="_blank"
                           href="<?= \Yii::$app->ftpPath['httpIP'] ?>/ord/req/<?= explode('_', trim($val['file_new'], '_'))[0] ?>/<?= $val['file_new'] ?>"><?= $val["file_old"] ?></a>
                    <?php } ?>
                </td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="13%">订单备注说明<label>：</label></td>
                <td class="no-border vertical-center" width="35%" colspan="4"><?= $data['remark'] ?></td>
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
                    <td><p class="text-center width-150"><?= $val["pdt_no"] ?></p>
                        <input type="hidden" name="PriceDt[<?= $key ?>][price_dt_id]"
                               value="<?= $val["price_dt_id"] ?>"/>
                        <input type="hidden" class="prt_pkid" value="<?= $val["prt_pkid"] ?>"/>
                    </td>
                    <td><p class="text-center width-150 pdt_name text-no-next"><?= $val["pdt_name"] ?></p></td>
                    <td>
                        <p class="text-center width-150 sapl_quantity"><?= number_format($val["sapl_quantity"], '2', '.', '') ?></p>
                    </td>
                    <td>
                        <p class="text-center width-150 thePrice"><?= ($val["price"] != '-1' ? number_format($val["price"], '5', '.', '') : "面议") ?></p>
                    </td>
                    <td><input class="height-30 width-150 text-center price" type="text" maxlength="13"
                               name="PriceDt[<?= $key ?>][uprice_tax_o]"
                               data-options="required:true,validType:['noZero','decimal[7,5]']"
                               value="<?= number_format($val["uprice_tax_o"], '5', '.', '') ?>">
                    </td>
                    <td>
                        <select class="height-30 width-150 text-center distribution" type="text"
                                name="PriceDt[<?= $key ?>][distribution]">
                            <!--                                --><?php //foreach ($downList["dispatching"] as $key1 => $val1) { ?>
                            <!--                                    <option value="-->
                            <? //= $val1["tran_id"] ?><!--" -->
                            <? //= $val['distribution'] == $val1['tran_id'] ? "selected" : null; ?><!-->-->
                            <? //= $val1["tran_sname"] ?><!--</option>-->
                            <!--                                --><?php //} ?>
                            <?php foreach ($downList["dispatching"] as $key1 => $val1) { ?>
                                <option
                                    value="<?= $val1["tran_id"] ?>" <?= ($val['transport'] == -1 && $val1["tran_id"] == $no_take) ? "disabled" : null; ?> <?= ($val['self_take'] != 1 && $val1["tran_id"] == $self_take) ? "disabled" : null; ?> <?= $val['distribution'] == $val1['tran_id'] ? "selected" : null; ?>><?= $val1["tran_sname"] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <select
                            class="height-30 width-150 text-center transport <?= $val['distribution'] == 0 ? 'display-none' : '' ?>"
                            type="text" data-options="required:true"
                            name="PriceDt[<?= $key ?>][transport]" <?= ($val['distribution'] == $self_take) ? "disabled" : null; ?>>
                            <option value="">请选择...</option>
                            <?php foreach ($downList["transport"] as $key2 => $val2) { ?>
                                <option
                                    value="<?= $val2["tran_code"] ?>" <?= $val['transport_id'] == $val2['tran_code'] ? "selected" : null; ?>><?= $val2["tran_sname"] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <select
                            class="height-30 width-150 text-center whouse <?= $val['distribution'] == 1 ? 'display-none' : '' ?>"
                            type="text" data-options="required:true"
                            name="PriceDt[<?= $key ?>][whs_id]" <?= ($val['distribution'] == $no_take) ? "disabled" : null; ?>>
                            <option value="">请选择...</option>
                            <?php foreach ($downList["warehouse"] as $key2 => $val2) { ?>
                                <option
                                    value="<?= $val2["wh_id"] ?>" <?= $val['whs_id'] == $val2['wh_id'] ? "selected" : null; ?>><?= $val2["wh_name"] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <p class="text-center width-150 freight"><?= empty($val["tax_freight"]) ? "0" : sprintf('%.2f', $val["tax_freight"]) ?></p>
                    </td>
                    <td>
                        <input class="height-30 width-150 easyui-validatebox " id="demand_time_<?= $key ?>"
                               onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate: '#F{$dp.$D(\'delivery_time_<?= $key ?>\');}'})"
                               onfocus="this.blur()"
                               data-options="required:true" value="<?= $val["request_date"] ?>"
                               name="PriceDt[<?= $key ?>][request_date]" placeholder="请选择...">
                    </td>
                    <td>
                        <input class="height-30 width-150 easyui-validatebox " id="delivery_time_<?= $key ?>"
                               onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '#F{$dp.$D(\'demand_time_<?= $key ?>\');}'})"
                               onfocus="this.blur()"
                               data-options="required:true" value="<?= $val["consignment_date"] ?>"
                               name="PriceDt[<?= $key ?>][consignment_date]" placeholder="请选择...">
                    </td>
                    <td>
                        <p class="text-center width-150 cess read-only"><?= number_format($val["cess"], '2', '.', '') ?></p>
                    </td>
                    <td>
                        <p class="text-center width-150 discount read-only"><?= number_format($val["discount"], '2', '.', '') ?></p>
                    </td>
                    <td>
                        <input class="height-30 width-150 text-center price_hs" type="hidden" maxlength="20"
                               name="PriceDt[<?= $key ?>][tprice_tax_o]" data-options="required:true"
                               value="<?= number_format($val["tprice_tax_o"], '2', '.', '') ?>">
                        <p class="text-center width-150  read-only price_hs"><?= number_format($val["tprice_tax_o"], '2', '.', '') ?></p>
                    </td>
                    <td><p class="text-center width-150 price_off"></p></td>
                    <td>
                        <input class="height-30 width-150 text-center easyui-validatebox" type="text" maxlength="50"
                               data-options="validType:'maxLength[50]'"
                               name="PriceDt[<?= $key ?>][sapl_remark]" value="<?= $val["sapl_remark"] ?>"
                        >
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width-big label-align">总运费(含税)</label><label>：</label>
            <input type="hidden" class="value-width easyui-validatebox bill_freight read-only" style="color:#FF6600;"
                   readonly="readonly" name="PriceInfo[tax_freight]"
                   value="<?= $data['currency_mark'] . $data['tax_freight'] ?>">
            <span class="value-width bill_freight" style="color:#FF6600;"><?= $data['tax_freight'] ?></span>
            <label class="label-width-big label-align">商品总金额(含税)</label><label>：</label>
            <input type="hidden" class="value-width easyui-validatebox bill_oamount read-only" style="color:#FF6600;"
                   readonly="readonly" name="PriceInfo[prd_org_amount]"
                   value="<?= $data['currency_mark'] . $data['prd_org_amount'] ?>">
            <span class="value-width bill_oamount" style="color:#FF6600;"><?= $data['prd_org_amount'] ?></span>
            <label class="label-width-big label-align">订单总金额(含税)</label><label>：</label>
            <input type="hidden" class="value-width easyui-validatebox total read-only" style="color:#FF6600;"
                   readonly="readonly" name="PriceInfo[req_tax_amount]"
                   value="<?= $data['currency_mark'] . $data['req_tax_amount'] ?>" id="total">
            <span class="value-width total" style="color:#FF6600;"><?= $data['req_tax_amount'] ?></span>
        </div>
    </div>
    <h2 class="head-second">
        支付方式选择
    </h2>
    <div class="mb-10" style="margin-top: 20px">
        <p class="color-w hiden allCredit"><?= $data['customer']['cust_apply'] ?></p>
        <div class="inline-block" id="pat1">
            <label class="label-width ">付款方式</label><label>：</label>
            <select type="text" class="value-width easyui-validatebox" id="payid"
                    data-options="required:'true'" name="PriceInfo[pac_id]">
                <option value="">请选择...</option>
                <?php foreach ($downList["payment"] as $key => $val) { ?>
                    <option <?= $val['pac_id'] == $data['pac_id'] ? "" : null; ?>
                        value="<?= $val["pac_id"] ?>" <?= $data['pac_id'] == $val['pac_id'] ? "selected" : null; ?> ><?= $val["pac_sname"] ?></option>
                <?php } ?>
            </select>
            <label
                class="label-width <?= ($data['pay_type'] != 0 && $data['pac_id'] != $creditId) ? "" : "hiden" ?> paytype"><span
                    class="red">*</span>支付类型</label><label
                class="paytype <?= ($data['pay_type'] != 0 && $data['pac_id'] != $creditId) ? "" : "hiden" ?>">：</label>
            <select type="text"
                    class="require value-width easyui-validatebox <?= ($data['pay_type'] != 0 && $data['pac_id'] != $creditId) ? "" : "hiden" ?> paytype"
                    data-options="required:'true'" name="PriceInfo[pay_type]">
                <option value="">请选择...</option>
                <?php foreach ($downList["payType"] as $key => $val) { ?>
                    <option
                        value="<?= $val["bsp_id"] ?>" <?= $data['pay_type'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val["bsp_svalue"] ?></option>
                <?php } ?>
            </select><label
                class="label-width  need_pay <?= ($data['pay_type'] != null || $data['pac_id'] == $creditId) ? "" : "hiden" ?>">需付款金额</label><label
                class="<?= ($data['pay_type'] != null || $data['pac_id'] == $creditId) ? "" : "hiden" ?> need_pay">：</label>
            <input type="text"
                   class="value-width easyui-validatebox need_pay total <?= ($data['pay_type'] != null || $data['pac_id'] == $creditId) ? "" : "hiden" ?>"
                   style="margin-left: 3px"
                   value="<?= $data['req_tax_amount'] ?>"
                   readonly="readonly">
        </div>
    </div>
    <div class="mb-10 <?= ($data['pay'][0]['stag_times']) != 0 ? "" : "hiden" ?> fenjiqi">
        <div class="inline-block  " id="pat2">
            <label class="label-width <?= ($data['pay'][0]['stag_times']) != 0 ? "" : "hiden" ?> fenjiqi"><span
                    class="red">*</span>分几期</label><label
                class="fenjiqi <?= ($data['pay'][0]['stag_times']) != 0 ? "" : "hiden" ?>">：</label>
            <select type="text"
                    class="require value-width easyui-validatebox <?= ($data['pay'][0]['stag_times'] != 0) ? "" : "hiden" ?> fenjiqi"
                    data-options="required:'true'" name="stag_qty" id="fenjiqi">
                <option value="2" <?= count($data['pay']) == 2 ? "selected" : null; ?>>2期</option>
                <option value="3" <?= count($data['pay']) == 3 ? "selected" : null; ?>>3期</option>
                <option value="4" <?= count($data['pay']) == 4 ? "selected" : null; ?>>4期</option>
                <option value="5" <?= count($data['pay']) == 5 ? "selected" : null; ?>>5期</option>
            </select>
        </div>
    </div>
    <?php if ($data['pac_id'] == $creditId) { ?>
        <div class="mb-10">
            <div class="inline-block" id="edu_pay">
                <?php foreach ($credits['rows'] as $k => $v) { ?>
                    <div class="inline-block mb-10"><label class="label-width ">信用额度类型</label><label>：</label>
                        <span style="width:100px;"><?= $v['credit_name'] ?></span><label
                            class="label-width ">总额度</label><label>：</label>
                        <span style="width:150px;"><?= $v['approval_limit'] ?></span><label
                            class="label-width ">剩余额度</label><label>：</label>
                        <span style="width:150px;" class="surplus_limit"><?= $v['surplus_limit'] ?></span>
                        <label class="label-width ">支付额度</label><label>：</label>
                        <input type="text"
                               class="value-width easyui-validatebox stag_cost stagCost Onlynum2 validatebox-text"
                               style="width:150px;" data-options="validType:['eduCompare','decimal[18,2]']"
                               name="PricePay[<?= $k ?>][stag_cost]"
                               value="<?= number_format($data['pay'][$k]["stag_cost"], '2', '.', '') ?>">
                        <input type="hidden" name="PricePay[<?= $k ?>][credit_id]" value="<?= $v['credit_type'] ?>">
                    </div><br>
                <?php } ?>
            </div>
        </div>
        <div class="mb-10" id="fenqi"></div>
    <?php } else if ($data['pay_type'] == $fenqiPay) { ?>
        <div class="mb-10">
            <div class="inline-block  hiden" id="edu_pay">
            </div>
        </div>
        <div class="mb-10" id="fenqi">
            <?php if (!empty($data['pay'])) { ?>
                <?php foreach ($data['pay'] as $key => $val) { ?>
                    <div class="inline-block mb-10">
                        <label class="width-100 "><span class="red">*</span>第 <?= ($key + 1) ?>
                            期付款时间</label><label>：</label>
                        <input type="text" class="require value-width easyui-validatebox"
                               id="select_time_<?= $key + 1 ?>"
                               onclick="select_time(<?= $key + 1 ?>,<?= count($data['pay']) ?>);"
                               name="PricePay[<?= $key ?>][stag_date]" id="stag_times_<?= $key + 1 ?>"
                               value="<?= $val["stag_date"] ?>"
                               data-options="required:'true'" onfocus="this.blur()">
                        <label class="width-100 "><span class="red">*</span>第<?= ($key + 1) ?>
                            期支付金额</label><label>：</label>
                        <input type="text" class="require value-width easyui-validatebox stag_cost"
                               name="PricePay[<?= $key ?>][stag_cost]"
                               data-options="required:'true'"
                               value="<?= number_format($val["stag_cost"], '2', '.', '') ?>">
                        <input type="text" class="hiden" name="PricePay[<?= $key ?>][stag_times]"
                               value="<?= $val["stag_times"] ?>">
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="mb-10" id="fenqi"></div>
        <div class="mb-10">
            <div class="inline-block  hiden" id="edu_pay">
            </div>
        </div>
    <?php } ?>
    <div class="mb-10" id="pay_left" title="剩余支付金额"
         style="margin-left: 415px;color: red;margin-top: -5px;height: 16px">
    </div>
    <h2 class="head-second">
        销售员信息
    </h2>
    <div class="mb-10">
        <div class="inline-block ">
            <label class="label-width "><span class="red">*</span>销售员</label><label>：</label>
            <input type="text" id="SaleMan" class="value-width easyui-validatebox read-only"
                   readonly="readonly"
                   value="<?= $seller["staff_code"] ?>">
            <!--                <input class="hiden sale_id" name="PriceSale[sell_delegate]" value="-->
            <? //= $seller["staff_code"] ?><!--"/>-->
            <label class="label-width ">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</label><label>：</label>
            <input type="text" id="name" readonly="readonly" class="value-width easyui-validatebox read-only"
                   value="<?= $seller['staff']["name"] ?>">
            <label class="label-width ">客户经理人</label><label>：</label>
            <input type="text" id="SaleManager" class="value-width easyui-validatebox read-only"
                   readonly="readonly" name="" value="<?= $seller['isrule'] == 1?$seller['staff_code'].'&nbsp;'.$seller['staff']['name']:$seller["leader"] ?>">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block ">
            <label class="label-width ">销售部门</label><label>：</label>
            <input type="text" id="SaleDep" readonly="readonly" class="value-width easyui-validatebox read-only"
                   value="<?= $seller["staff"]["organization_name"] ?>">
            <label class="label-width ">销售区域</label><label>：</label>
            <input type="text" id="SaleArea" readonly="readonly" class="value-width easyui-validatebox read-only"
                   value="<?= $seller["csarea"] ?>">
            <label class="label-width ">销售点</label><label>：</label>
            <input type="text" id="SaleStore" class="value-width easyui-validatebox read-only"
                   readonly="readonly" name="" value="<?= $seller["sts_sname"] ?>">
        </div>
    </div>
    <div class="space-40"></div>
    <div class="text-center">
        <?php if (Yii::$app->controller->action->id == 'update') { ?>
            <button type="submit" class="button-blue-big sub" id="submit">保存</button>
        <?php } ?>
        <button type="submit" class="button-blue-big save-form ml-40" id="submit">提交</button>
        <button class="button-white-big" onclick="window.location.href='<?= Url::to(["index"]) ?>'" type="button">取&nbsp;消
        </button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    var creditId = "<?= $creditId ?>";
    var prepayId = "<?= $prepayId ?>";
    var allpay = "<?= $allpay ?>";
    var firstChange = true;
    $(function () {
        //输入控制
        $(".IsTel").telphone();
        $(".Onlynum").numbervalid();
        $(".Onlynum2").numbervalid(2);
        $(".price").numbervalid(2, 7);//最多六位小数，整数位最多13位
        $(".stag_cost").numbervalid(2, 18);
        <?php if($data['pay_type'] == $fenqiPay){?>
        firstChange = false;
        $(".need_pay").show().appendTo("#pat2");
        <?php }?>
        $.extend($.fn.validatebox.defaults.rules, {
            eduCompare: {
                validator: function () {
                    var type_edu = parseFloat($(this).parent().find("span.surplus_limit").html());
                    var pay = parseFloat($(this).val());
                    if (pay <= 0) {
                        $.fn.validatebox.defaults.rules.eduCompare.message = '金额不为零';
                    } else if (pay > type_edu) {
                        $.fn.validatebox.defaults.rules.eduCompare.message = '此类型剩余额度不足';
                    }
                    return (pay <= type_edu && pay >= 0);
                },
                message: '此类型剩余额度不足'
            },
        });
        optionOnce();
        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });
        <?php if(Yii::$app->controller->action->id == 'create'){ ?>
        $('.save-form').click(function () {
            $('#add-form').attr('action', '<?= Url::to(["create?id=" . $id]) ?>&is_apply=1&status=20');
            $("button.sub").prop("disabled", true);
        });
        $('.sub').click(function () {
            $('#add-form').attr('action', '<?= Url::to(["create?id=" . $id]) ?>');
            $("button.save-form").prop("disabled", true);
        });
        <?php }?>
        <?php if(Yii::$app->controller->action->id == 'update'){ ?>
        $('.save-form').click(function () {
            $('#add-form').attr('action', '<?= Url::to(["update?id=" . $id]) ?>&is_apply=1&status=20');
            $("button.sub").prop("disabled", true);
        });
        $('.sub').click(function () {
            $('#add-form').attr('action', '<?= Url::to(["update?id=" . $id]) ?>');
            $("button.save-form").prop("disabled", true);
        });
        <?php }?>
        ajaxSubmitForm($("#add-form"), function () {
            $('input,select,textarea', $("#add-form")).each(function () {
                if ($(this).is(':hidden')) {
                    $(this).validatebox({
                        required: false
                    });
                }
            });
            var isok = true;
            if ($(".stag_cost").length != 0) {
                var fenqi = 0;
                var total = parseFloat($(".total").val());
                $(".stag_cost").each(function () {
                    if (!isNaN(parseFloat($(this).val()))) {
                        fenqi += parseFloat($(this).val());
                    }
                });
                if (fenqi != total) {
                    layer.alert("支付总金额应等于需付款金额!", {icon: 2});
                    isok = false;
                }
            }
            if ($("#product_table").children().length < 1) {
                isok = false;
                layer.alert("请选择商品", {icon: 2});
            }
            return isok;
        });
        totalPrices();
        var paytype = "<?= $data['pay_type'] ?>";
        if (paytype == allpay) {
        }
    });
    var fieldArr = new Array();
    <?php foreach ($columns as $key => $val) { ?>
    fieldArr[<?= $key ?>] = "<?= $val["field_field"] ?>";
    <?php } ?>


    $('input[type!="hidden"]', $("#product_table")).on("change", function () {
        totalPrices();
    });

    //下单数量变换  商品定价变化  转为整数倍包装数
    $(document).on("change", ".sapl_quantity", function () {
        var packNum = parseFloat($(this).data("quantity"));//包装数
        if (isNaN(packNum)) {
            packNum = 1;
        }
        var min_order = parseFloat($(this).data("min_order"));//最小订单数
        if (isNaN(min_order)) {
            min_order = 1;
        }
        var $quantity = $(this);
        var quantity = parseFloat($(this).val());
//        console.log(min_order);
        var curr = $("#cur_id").val();//币别
        var pdt_no = $quantity.parent().parent().find("input.pdt_no").val();//料号

        if (!isNaN(min_order) && quantity < min_order) {
            quantity = min_order;
        }

        if (!isNaN(packNum)) {
            var yushu = quantity % packNum;
            if (yushu != 0) {
                quantity = parseInt((parseFloat($(this).val()) / packNum + 1)) * packNum;
            }
            $(this).val(quantity);
        }
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: "<?= Url::to(['get-price'])?>" + "?pdt_no=" + pdt_no + "&num=" + quantity + "&curr=" + curr,
            success: function (data) {
//                console.log(data);
                if (data != null) {
                    $quantity.parent().parent().find("p.thePrice").html(data);
                }
            },
            error: function (data) {
//                layer.alert("未找到该料号!", {icon: 2});
            }
        });
        totalPrices();

    });

    //运输方式变化   请求运费
    $(document).on("change", ".transport", function () {
        $pdt_no = $(this);
        var type = $(this).val();
        if (type == "") {
            return false;
        }
        var pdt = $(this).parent().parent().find("input.prt_pkid").val();
        var num = $(this).parent().parent().find("p.sapl_quantity").text();
        var addr = $("#myAddress").val();
        if (addr == "" || num == "") {
            return false;
        }
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: "<?= Url::to(['get-freight'])?>" + "?pdt=" + pdt + "&num=" + num + "&addr=" + addr + "&TransType=" + type,
            success: function (data) {
                if (data == "0") {
                    $pdt_no.parent().parent().find("input.freight").val(0);
                }
                else if (data[0] != undefined) {
                    $pdt_no.parent().parent().find("input.freight").val(data[0].LogisticsCost);
                } else if (data.message != "") {
                    layer.alert(data.message, {
                        icon: 1,
                        end: function () {
                            $pdt_no.find("option")[0].selected = true;
                            $pdt_no.parent().parent().find("input.freight").val(0);
                        }
                    });
                }
                totalPrices();
            }
        })
        ;
    });

    //单价变化
    $(document).on("change", ".price", function () {
        totalPrices();

    });
    //运费变化
    $(document).on("change", ".freight", function () {
        totalPrices();

    });

    //配送方式变化
    $(document).on("change", ".distribution", function () {
        var distri = $(this).val();
        if (distri == "<?= $self_take ?>") {
            $(this).parent().parent().find("select.transport").validatebox({
                required: false
            });
            $(this).parent().parent().find("select.whouse").validatebox({
                required: false
            });
            $(this).parent().parent().find("select.transport option")[0].selected = true;
            $(this).parent().parent().find("select.transport").attr("disabled", true);
            $(this).parent().parent().find("select.whouse").attr("disabled", false);
            $(this).parent().parent().find("select.transport").hide();
            $(this).parent().parent().find("select.whouse").show();
            $(this).parent().parent().find("input.freight").val(0);
        } else if (distri == "<?= $no_take ?>") {
            $(this).parent().parent().find("select.transport").validatebox({
                required: false
            });
            $(this).parent().parent().find("select.whouse").validatebox({
                required: false
            });
            $(this).parent().parent().find("select.whouse option")[0].selected = true;
            $(this).parent().parent().find("select.transport").disabled = false;
            $(this).parent().parent().find("select.transport").attr("disabled", false);
            $(this).parent().parent().find("select.whouse").attr("disabled", true);
            $(this).parent().parent().find("select.transport").show();
            $(this).parent().parent().find("select.whouse").hide();
        }
        totalPrices();
    });

    //付款方式
    $("#payid").on("change", function () {
        $("#pay_left").html("");
        var xin = $(this).val();
        if (xin == prepayId) {   //预付款
            $(".paytype").show().validatebox({
                required: true
            });
            ;
            $('.paytype option')[0].selected = "selected";
            $("#fenqi").hide().html("");
            $('.fenjiqi option')[0].selected = "selected";
            $(".fenjiqi").hide();
            $("#edu_pay").hide();
            $("#edu_pay").hide().html("");
            $(".need_pay").hide();
//            $(".paytype").hide();
        } else if (xin == creditId) {  //信用支付
            $(".need_pay").show().appendTo("#pat1");
            $(".paytype").hide().validatebox({
                required: false
            });
            $('.paytype option')[0].selected = "selected";
            $("#fenqi").hide().html("");
            $(".fenjiqi").hide();
            $('.fenjiqi option')[0].selected = "selected";
            if ($(".edu_pay_del").length == 1) {
                $(".edu_pay_del").hide();
            }
            var creditHtml = '';
            <?php foreach ($credits['rows'] as $key=>$val){ ?>
            creditHtml += '<div class="inline-block mb-10">' +
                '<label class="label-width ">信用额度类型</label><label>：</label> ' +
                '<span style="width:100px;"><?= $val['credit_name'] ?></span>' +
                '<label class="label-width ">总额度</label><label>：</label> ' +
                '<span style="width:150px;"><?= $val['approval_limit'] ?></span>' +
                '<label class="label-width ">剩余额度</label><label>：</label> ' +
                '<span style="width:150px;" class="surplus_limit"><?= $val['surplus_limit'] ?></span>' +
                '<label class="label-width ">支付额度</label><label>：</label> ' +
                '<input type="text" class="value-width easyui-validatebox stag_cost stagCost Onlynum2 validatebox-text" style="width:150px;"data-options="validType:[\'eduCompare\',\'decimal[18,2]\']" name="PricePay[<?= $key ?>][stag_cost]">' +
                '<input type="hidden" name="PricePay[<?= $key ?>][credit_id]" value="<?= $val['credit_type'] ?>">' +
                '</div></br>';
            <?php } ?>
            $("#edu_pay").append(creditHtml);
            $("#edu_pay").show();
            $("#total").change();
        } else {
            $("#edu_pay").hide();
            $(".need_pay").hide();
            $("#fenqi").hide().html("");
            $(".fenjiqi").hide();
            $('.fenjiqi option')[0].selected = "selected";
            $(".paytype").hide().validatebox({
                required: false
            });
            $('.paytype option')[0].selected = "selected";

        }
        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });
    });

    //支付类型
    $(".paytype").on("change", function () {
        $("#pay_left").html("");
        var xin = $(this).val();
        if (xin == allpay) {   //全额支付
            $(".need_pay").show().appendTo("#pat1");
            $("#fenqi").hide().html("");
            $(".fenjiqi").hide();
            $('.fenjiqi option')[0].selected = "selected";
        } else if (xin == "") {
            $(".need_pay").hide();
            $(".fenjiqi").hide();
            $('.fenjiqi option')[0].selected = "selected";
            $("#fenqi").hide().html("");
        } else {     //分期支付
            $(".fenjiqi").show();
            $("#fenqi").show();
            $(".need_pay").show().appendTo("#pat2");
            for (var i = 0; i < 2; i++) {
                $("#fenqi").append('<div class="inline-block mb-10">' +

                    '<label class="label-width " style="margin-left: 4px">第' + (i + 1) + '期付款时间</label><label>：</label>' +
                    '<input type="text" class="value-width easyui-validatebox" id="select_time_' + (i + 1) + '" name="PricePay[' + i + '][stag_date]" onclick="select_time(' + (i + 1) + ',' + xin + ')" onfocus="this.blur()" style="margin-left: 3px" data-options="required:\'true\'" name="" value=""> ' +
                    '<label class="label-width ">第' + (i + 1) + '期支付金额</label><label>：</label>' +
                    '<input type="text" class="value-width easyui-validatebox stag_cost" maxlength="21" name="PricePay[' + i + '][stag_cost]" data-options="required:\'true\'" style="margin-left: 4px">' +
                    '<input type="text" class="hiden" name="PricePay[' + i + '][stag_times]" value="' + (i + 1) + '">' +
                    '</div>')
            }
            $(".Onlynum2").numbervalid(2);
            $(".stag_cost").numbervalid(2, 18);
        }
        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });
    });
    var credit_len = $(".edu_pay_add").length;
    //添加额度支付类型
    $(document).on("click", ".edu_pay_add", function () {
        var opt_selected = new Array();
        $(".creditType").each(function (i) {
            opt_selected[i] = $(this).find("option:selected").val();
        });
        var limit_num = $(".old_credits").length / 2 - 1;
        //最多为额度类型的种数
        var most_one = false;
        if ($(".edu_pay_add").length == limit_num) {
            most_one = true;
        }
        credit_len++;
        $(".edu_pay_add").hide();
        $('.edu_pay_del').show();
        $('.last_one').remove("last_one");
        var opts = "";
//        if (creditType != null) {
//            for (var x in creditType) {
//                opts += '<option value="' + creditType[x].id + '">' + creditType[x].credit_name + '</option>';
//            }
//        }
        <?php foreach ($downList["creditType"] as $key => $val) { ?>
        var id = "<?= $val["id"] ?>";
        opts += '<option value="' + id + '" ' + (contains(opt_selected, id) ? "disabled" : "") + '> <?= $val["credit_name"] ?></option>';
        <?php } ?>
        /*追加信用额度类型*/
        $("#edu_pay").append(
            '<div class="mb-10"><label class="label-width "><span class="red">*</span>信用额度类型</label><label class="">：</label>' +
            '<select type="text" class="require value-width easyui-validatebox creditType" style="margin-left: 3px" data-options="required:\'true\'" name="PricePay[' + credit_len + '][credit_id]">' +
            opts +
            '</select> ' +
            '<label class="label-width  patid">付款金额</label><label class="">：</label>' +
            '<input type="text" class="value-width easyui-validatebox stag_cost stagCost" maxlength="21" data-options="required:\'true\',validType:\'eduCompare\'" style="margin-left: 7px" name="PricePay[' + credit_len + '][stag_cost]">' +
            '<a class="icon-minus edu_pay_del" style="margin-left: 10px">删除</a>' +
            '<a class="icon-plus edu_pay_add last_one" style="margin-left: 8px;' + ((most_one) ? "display:none" : "") + '" onclick="">添加</a></div>'
        );
        optionOnce();
    });
    /*删除信用额度类型*/
    $(document).on("click", ".edu_pay_del", function () {
        if ($(this).parent().find(".edu_pay_add").length == 1 && $("#edu_pay").children().length == 2) {
            $(this).parent().prev().find(".edu_pay_add").show();
        }
        $(this).parent().remove();
        if ($("#edu_pay").children().length == 1) {
            $('.edu_pay_add').show();
            $('.edu_pay_del').hide();
        }
        var len = $("#edu_pay").children().length - 1;
        $("#edu_pay").find('.edu_pay_add:eq(' + len + ')').show();
        optionOnce();
    });
    /*添加信用额度类型*/
    $(document).on("click", ".creditType", function () {
        $(this).parent().find("input.stagCost").change();
        optionOnce();
    });
    $(document).on("change", "#total", function () {
        var total = $(this).val();
        var totalEdu = parseFloat($(".allCredit").html());
//        console.log(total);
//        console.log(totalEdu);
        if (isNaN(totalEdu)) {
            totalEdu = 0;
        }
        if (total > totalEdu) {
            for (var i = 0; i < $('#payid option').length; i++) {
                if ($('#payid option')[i].value == creditId) {  //8为信用额度支付
                    $('#payid option')[i].disabled = true;
                    $($('#payid option')[i]).removeAttr("selected");
                }
            }
            //   $('#payid').change();
        } else {
            if ($('#payid option:selected').val() == creditId && $(".stag_cost").length == 1) {
                $(".stag_cost").val(total);
            }
            for (i = 0; i < $('#payid option').length; i++) {
                if ($('#payid option')[i].value == creditId) {  //8为信用额度支付
                    $('#payid option')[i].disabled = false;
                }
            }
        }
    });
    $(document).on("change", ".stag_cost", function () {
        var total = $("#total").val();
        var pay = 0.00;
        for (var i = 0; i < $(".stag_cost").length; i++) {
            if ($(".stag_cost")[i].value != "") {
                pay = pay + parseFloat($(".stag_cost")[i].value);
            }
        }
        if (total != pay) {
            $("#pay_left").html((total - pay).toFixed(2));
        } else {
            $("#pay_left").html("");
        }
    });

    //获取到客户  信用额度变化
    $("#edu").on("change", function () {
        var xin = $(this).val();
        if ($(".total").val() > xin) {
            for (var i = 0; i < $('#payid option').length; i++) {
                if ($('#payid option')[i].value == creditId) {  //8为信用额度支付
//                    $('#payid option')[i].disabled = "disabled";
                    if ($('#payid option:selected').val() == creditId) {
                        $('#payid option')[0].selected = "selected";
                        //       $('#payid').change();
                    }
                }
            }
        } else {
            for (i = 0; i < $('#payid option').length; i++) {
                if ($('#payid option')[i].value == creditId) {  //8为信用额度支付
                    $('#payid option')[i].disabled = false;
                }
            }
        }
    });

    /*分几期*/
    $("#fenjiqi").on("change", function () {
        var xin = $(this).val();
        $("#fenqi").show().html("");
        $("#pay_left").html("");
        for (var i = 0; i < xin; i++) {
            $("#fenqi").append('<div class="inline-block mb-10">' +
                '<label class="label-width " style="margin-left: 4px">第 ' + (i + 1) + '期付款时间</label><label>：</label>' +
                '<input type="text" class="value-width easyui-validatebox select-date" id="select_time_' + (i + 1) + '" name="PricePay[' + i + '][stag_date]" onclick="select_time(' + (i + 1) + ',' + xin + ')" onfocus="this.blur()" " data-options="required:\'true\'" name="" value=""> ' +
                '<label class="label-width ">第 ' + (i + 1) + '期支付金额</label><label>：</label>' +
                '<input type="text" class="value-width easyui-validatebox stag_cost" maxlength="21" name="PricePay[' + i + '][stag_cost]" data-options="required:\'true\'" style="margin-left: 4px">' +
                '<input type="text" class="hiden" name="PricePay[' + i + '][stag_times]" value="' + (i + 1) + '">' +
                '</div>')
        }
        $(".Onlynum2").numbervalid(2);
        $(".stag_cost").numbervalid(2, 18);
        $('input[type!="hidden"],select,textarea', $("#fenqi")).each(function () {
            $(this).validatebox();//验证初始化
        });
    });


    /*判断数组是否存在某元素*/
    function contains(arr, obj) {
        var i = arr.length;
        while (i--) {
            if (arr[i] === obj) {
                return true;
            }
        }
        return false;
    }

    //计算总价格
    function totalPrices() {
        var trs = $("#product_table").children();
        var allinput = true;
        var ttfreight = 0;//总运费
        var ttprice = 0;//商品总金额(含税)
        var total = 0;//订单总金额(含税)
        for (var i = 0; i < trs.length; i++) {
            var quantity = $(trs[i]).find("p.sapl_quantity").text();//数量
            var price = $(trs[i]).find("input.price").val();//销售单价（含税）
            var thePrice = $(trs[i]).find("p.thePrice").text();//商品定价（含税）
            var cess = $(trs[i]).find("p.cess").text();//税率
            var discount = $(trs[i]).find("p.discount").text();//折扣率
            var freight = $(trs[i]).find("p.freight").text();//运费
//            if (quantity && price && cess && discount && freight) {
            if (quantity != "" && price != "") {
                quantity = parseFloat(quantity);
                price = parseFloat(price).toFixed(5);
                var ttprice_hs = price * quantity;  //折扣后金额
                $(trs[i]).find("p.price_off").text(ttprice_hs.toFixed(2));
                ttprice += ttprice_hs;
            } else {
                allinput = false;
            }
            if (quantity != "" && thePrice != "") {
                //商品总金额

                if (thePrice != "面议" && thePrice != "") {
                    thePrice = parseFloat(thePrice);
                    quantity = parseFloat(quantity);
                    $(trs[i]).find("p.price_hs").text((thePrice * quantity).toFixed(2));
                    $(trs[i]).find("input.price_hs").val((thePrice * quantity).toFixed(2));
                }
            }
            if (price != "" && thePrice != "") {
                //折扣率
                if (thePrice != "面议" && thePrice != "") {
                    thePrice = parseFloat(thePrice);
                    price = parseFloat(price);
                    $(trs[i]).find("p.discount").text((price * 100 / thePrice).toFixed(2));
                }
            }
            if (freight != "") {
                freight = parseFloat(freight);
                ttfreight += freight;
            } else {
                allinput = false;
            }
        }
        if (allinput) {
            total = total + ttfreight + ttprice;
            $(".bill_freight").val(ttfreight.toFixed(2));
            $(".bill_freight").text("<?= $data['currency_mark'] ?>" + ttfreight.toFixed(2));
            $(".bill_oamount").val(ttprice.toFixed(2));
            $(".bill_oamount").text("<?= $data['currency_mark'] ?>" + ttprice.toFixed(2));
            $(".total").val(total.toFixed(2));
            $(".total").text("<?= $data['currency_mark'] ?>" + total.toFixed(2));
            $("#total").change();
        }
    }


    //使帐信支付类型职能选一次
    function optionOnce() {
        var selected = new Array();
        $(".creditType").each(function (i) {
            selected[i] = $(this).find("option:selected").val();
        });
        $(".creditType").each(function (i) {
            var val = $(this).find("option:selected").val();
            for (var j = 0; j < $(this).find("option").length; j++) {
                var $opt = $(this).find("option")[j];
                if ($opt.value != val && contains(selected, $opt.value)) {  //8为信用额度支付
                    $opt.disabled = true;
                } else if (!contains(selected, $opt.value)) {
                    $opt.disabled = false;
                }
            }
        });
//        console.log(selected);
    }
</script>