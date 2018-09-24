<?php
/**
 * User: F1676624
 * Date: 2017/12/5
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\JeDateAsset;

$this->title = '修改订单价格';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '交易订单列表', 'url' => "index"];
$this->params['breadcrumbs'][] = ['label' => $this->title];
JeDateAsset::register($this);

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
foreach ($downList["orderType"] as $key => $val) {
    if ($data["model"]['ord_type'] == $val['business_type_id']) {
        $req_type = $val["business_value"];
        break;
    } else {
        $req_type = "";
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
//dumpE($creditId.$prepayId.$allpay.$fenqiPay);
?>
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

    .width-157 {
        width: 157px;
    }

    .width-158 {
        width: 158px;
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

    .ml-10 {
        margin-left: 10px;
    }

    .wd-60 {
        width: 60px;
    }

    .wd-265 {
        width: 265px;
    }

    .text-no-next {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        display: inline-block;
    }

    input.read-only {
        background-color: rgb(255, 255, 255);
        border: 1px solid #fff;
    }

    thead tr th p {
        color: white;
    }
</style>
<div class="content">
    <h1 class="head-first" xmlns="http://www.w3.org/1999/html">
        <?= $this->title ?>
    </h1>
    <h2 class="head-second">
        基本信息
    </h2>
    <?php $form = ActiveForm::begin(
        ['id' => 'add-form', 'options' => ['enctype' => 'multipart/form-data']]
    ); ?>
    <div class="mb-30">
        <input type="text" id="cust_id" class="value-width easyui-validatebox hiden"
               value="<?= $data["model"]["customer"]['cust_id'] ?>">
        <table width="90%" class="no-border vertical-center label-align ml-25">
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">订单编号：</td>
                <td width="35%" class="no-border vertical-center value-align"><?= $data["model"]["ord_no"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">下单时间：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["nw_date"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">订单来源：</td>
                <td width="35%" class="no-border vertical-center value-align">平台新增订单</td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">订单类型：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["ordType"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">合同编号：</td>
                <td width="35%" class="no-border vertical-center value-align"><?= $data["model"]["contract_no"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">客户全称：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]['customer']["cust_sname"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">客户代码：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["cust_code"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">公司电话：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]['customer']["cust_tel1"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">联系人：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["cust_contacts"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">联系电话：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["cust_tel2"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">客户地址：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["cust_addr"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">交易法人：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["company_name"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">交易模式：</td>
                <td width="35%" class="no-border vertical-center value-align"><?= $data["model"]["trade_mode"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">交易币别：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["curr_code"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">发票类型：</td>
                <td width="35%" class="no-border vertical-center value-align"><?= $data["model"]["invoice_type"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">发票抬头：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["invoice_title"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center label-align ml-25">
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">发票抬头地址：</td>
                <td width="87%"
                    class="no-border vertical-center value-align"><?= $data["model"]["invoice_Title_AreaID"] . $data["model"]["invoice_Title_Addr"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">发票寄送地址：</td>
                <td width="87%"
                    class="no-border vertical-center value-align"><?= $data["model"]["distinct_id"] . $data["model"]["invoice_Address"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">收货人：</td>
                <td width="87%"
                    class="no-border vertical-center value-align"><?= $data["model"]["receipter"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">联系电话：</td>
                <td width="87%"
                    class="no-border vertical-center value-align"><?= $data["model"]["receipter_Tel"] . "  /  " . $data["model"]["addr_tel"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">收货地址：</td>
                <td width="87%"
                    class="no-border vertical-center value-align"><?= $data["model"]["receipt_Address"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">附件：</td>
                <td width="87%" class="no-border vertical-center value-align">
                    <?php foreach ($data["model"]["files"] as $key => $val) { ?>
                        <a class="text-center width-150 color-w ml-10" target="_blank"
                           href="<?= \Yii::$app->ftpPath['httpIP'] ?>/ord/req/<?= explode('_', trim($val['file_new'], '_'))[0] ?>/<?= $val['file_new'] ?>"><?= $val["file_old"] ?></a>
                    <?php } ?>
                </td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">订单备注说明：</td>
                <td width="87%" class="no-border vertical-center value-align"><?= $data["model"]["remark"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
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
                <th><p class="width-150 color-w"><span class="red">*</span>销售单价（含税）</p></th>
                <th><p class="width-150 color-w">配送方式</p></th>
                <th><p class="width-150 color-w"><span class="red">*</span>运输方式</p></th>
                <th><p class="width-150 color-w"><span class="red">*</span>自提仓库</p></th>
                <th><p class="width-150 color-w">运费（含税）</p></th>
                <th><p class="width-150 color-w"><span class="red">*</span>需求交期</p></th>
                <th><p class="width-150 color-w"><span class="red">*</span>交期</p></th>
                <th><p class="width-150 color-w">税率（%）</p></th>
                <th><p class="width-150 color-w">折扣率（%）</p></th>
                <th><p class="width-150 color-w">商品总价（含税）</p></th>
                <th><p class="width-150 color-w">折扣后金额</p></th>
                <th><p class="width-150 color-w">备注</p></th>
                <!--                --><?php //foreach ($columns as $key => $val) { ?>
                <!--                    <th><p class="text-center width-150 color-w">-->
                <? //= $val["field_title"] ?><!--</p></th>-->
                <!--                --><?php //} ?>
            </tr>
            </thead>
            <tbody id="product_table">
            <?php foreach ($data["products"] as $key => $val) { ?>
                <tr>
                    <td class="hiden"><span data-id="<?= $key ?>"></span></td>
                    <td><span class="width-40"><?= ($key + 1) ?></span></td>
                    <td><input class="height-30 width-150 text-center  pdt_no read-only easyui-validatebox"
                               type="text"
                               style="height: 30px"
                               maxlength="20"
                               readonly="readonly" data-id="" value="<?= $val["part_no"] ?>"
                        >
                        <input class="hiden pdt_id" name="orderL[<?= $key ?>][prt_pkid]"
                               value="<?= $val["prt_pkid"] ?>"/>
                        <input class="hiden pdt_id" name="orderL[<?= $key ?>][ord_dt_id]"
                               value="<?= $val["ord_dt_id"] ?>"/>
                    </td>
                    <td><p
                            class="text-center width-150 pdt_name"><?= $val["pdt_name"] ?></p>
                    </td>
                    <td><input
                            class="height-30 width-150  text-center sapl_quantity read-only" type="text"
                            readonly="readonly"
                            maxlength="20" value="<?= $val["sapl_quantity"] ?>"
                            name="orderL[<?= $key ?>][sapl_quantity]" data-min_order="<?= $val["min_order"] ?>"
                            data-quantity="<?= $val["pdt_qty"] ?>"
                            data-options="required:true"
                        >
                    </td>
                    <td><p
                            class="text-center width-150 thePrice"><?= $val["price"] ?></p>
                    </td>
                    <td><input class="height-30 width-150 text-center  price easyui-validatebox" type="text"
                               maxlength="20"
                               name="orderL[<?= $key ?>][uprice_tax_o]"
                               data-options="required:true,validType:['noZero','decimal[7,5]']"
                               value="<?= bcsub($val["uprice_tax_o"], 0, 5) ?> "
                        ></td>
                    <td><select class="height-30 width-150 text-center easyui-validatebox distribution" type="text"
                                data-options="required:true"
                                name="orderL[<?= $key ?>][distribution]">
                            <?php foreach ($downList["dispatching"] as $key1 => $val1) { ?>
                                <option
                                    value="<?= $val1["tran_id"] ?>" <?= ($val['transport'] == -1 && $val1["tran_id"] == $no_take) ? "disabled" : null; ?> <?= ($val['self_take'] != 1 && $val1["tran_id"] == $self_take) ? "disabled" : null; ?> <?= $val['distribution'] == $val1['tran_id'] ? "selected" : null; ?>><?= $val1["tran_sname"] ?></option>
                            <?php } ?>
                        </select></td>
                    <td><select
                            class="height-30 width-150 text-center easyui-validatebox transport <?= $val['distribution'] == $self_take ? "hiden" : null; ?>"
                            type="text"
                            data-options="required:true"
                            name="orderL[<?= $key ?>][transport]">
                            <option value="">请选择...</option>
                            <?php foreach ($val["transport"] as $k => $v) { ?>
                                <option
                                    value="<?= $v["id"] ?>" <?= $v['id'] == $val['transport_code'] ? "selected" : null; ?>><?= $v["name"] ?></option>
                            <?php } ?>
                        </select></td>
                    <td><select
                            class="height-30 width-150  text-center easyui-validatebox whouse <?= $val['distribution'] == $no_take ? "hiden" : null; ?>"
                            type="text"
                            data-options="required:true"
                            name="orderL[<?= $key ?>][whs_id]">
                            <option value="">请选择...</option>
                            <?php foreach ($val["wh"] as $key2 => $val2) { ?>
                                <option
                                    value="<?= $val2["wh_id"] ?>" <?= $val['whs_id'] == $val2['wh_id'] ? "selected" : null; ?>><?= $val2["wh_name"] ?></option>
                            <?php } ?>
                        </select></td>
                    <td><input class="height-30 width-150 text-center  freight read-only" type="text" maxlength="20"
                               data-options="required:true" readonly="readonly"
                               value="<?= empty($val["tax_freight"]) ? "0.00" : bcsub($val["tax_freight"], 0, 2) ?>"
                               name="orderL[<?= $key ?>][tax_freight]"></td>
                    <td><input class="height-30 width-150 select-date-time easyui-validatebox "
                               readonly="readonly" id="start_time_<?= $key ?>"
                               data-options="required:true" value="<?= $val["request_date"] ?>"
                               onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate:'%y-%M-%d %H:%m', maxDate: '#F{$dp.$D(\'end_time_<?= $key ?>\');}' })"
                               name="orderL[<?= $key ?>][request_date]" placeholder="请选择"></td>
                    <td><input class="height-30 width-150 select-date-time easyui-validatebox "
                               readonly="readonly" id="end_time_<?= $key ?>"
                               onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'start_time_<?= $key ?>\');}' })"
                               data-options="required:true" value="<?= $val["consignment_date"] ?>"
                               name="orderL[<?= $key ?>][consignment_date]" placeholder="请选择"></td>
                    <td><input class="height-30 width-150 text-center  cess read-only" type="text" maxlength="20"
                               name="orderL[<?= $key ?>][cess]" data-options="required:true" readonly="readonly"
                               value="<?= bcsub($val["cess"], 0, 2) ?> "
                        ></td>
                    <td><input class="height-30 width-150 text-center  discount read-only" type="text"
                               maxlength="20"
                               name="orderL[<?= $key ?>][discount]" readonly="readonly"
                               value="<?= $val["discount"] ?>"
                        ></td>
                    <td><input class="height-30 width-150 text-center  read-only price_hs" type="text"
                               maxlength="20"
                               name="orderL[<?= $key ?>][tprice_tax_o]"
                               value="<?= $val["tprice_tax_o"] ?>"
                               readonly="readonly">
                    </td>
                    <td><p
                            class="text-center width-150 price_off"></p>
                    </td>
                    <td><input class="height-30 width-150 text-center easyui-validatebox" type="text" maxlength="50"
                               data-options="validType:'maxLength[50]'"
                               name="orderL[<?= $key ?>][sapl_remark]" value="<?= $val["sapl_remark"] ?>"
                        ></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width-big ">总运费(含税)</label><label>：</label>
            <input type="text" class="value-width easyui-validatebox bill_freight read-only"
                   readonly="readonly" name="OrdInfo[tax_freight]" style="color: #F60;"
                   value="<?= $data["model"]['tax_freight'] ?>">
            <label class="label-width-big ">商品总金额(含税)</label><label>：</label>
            <input type="text" class="value-width easyui-validatebox bill_oamount read-only"
                   readonly="readonly" style="color: #F60;"
                   name="OrdInfo[prd_org_amount]" value="<?= $data["model"]['prd_org_amount'] ?>">
            <label class="label-width-big ">订单总金额(含税)</label><label>：</label>
            <input type="text" class="value-width easyui-validatebox total read-only"
                   readonly="readonly" id="total" style="color: #F60;"
                   name="OrdInfo[req_tax_amount]"
                   value="<?= $data["model"]['req_tax_amount'] ?>">
        </div>
    </div>
    <h2 class="head-second">
        支付方式选择
    </h2>
    <p class="color-w hiden allCredit"></p>
    <div style="margin-left: 20px">
        <div class="mb-10" style="margin-top: 20px">
            <div class="inline-block" id="pat1">
                <label class="label-width ">付款方式</label><label>：</label>
                <select type="text" class="value-width easyui-validatebox" id="payid"
                        data-options="required:'true'" name="OrdInfo[pac_id]">
                    <option value="">请选择...</option>
                    <?php foreach ($downList["payment"] as $key => $val) { ?>
                        <option <?= $val['pac_id'] == $creditId ? "" : null; ?>
                            value="<?= $val["pac_id"] ?>" <?= $data["model"]['pac_id'] == $val['pac_id'] ? "selected" : null; ?>><?= $val["pac_sname"] ?></option>
                    <?php } ?>
                </select>
                <label
                    class="label-width <?= ($data["model"]['pay_type'] != 0 && $data["model"]['pac_id'] != $creditId) ? "" : "hiden" ?> paytype"><span
                        class="red">*</span>支付类型</label><label
                    class="paytype <?= ($data["model"]['pay_type'] != 0 && $data["model"]['pac_id'] != $creditId) ? "" : "hiden" ?>">：</label>
                <select type="text"
                        class="require value-width easyui-validatebox <?= ($data["model"]['pay_type'] != 0 && $data["model"]['pac_id'] != $creditId) ? "" : "hiden" ?> paytype"
                        data-options="required:'true'" name="OrdInfo[pay_type]">
                    <option value="">请选择...</option>
                    <?php foreach ($downList["payType"] as $key => $val) { ?>
                        <option
                            value="<?= $val["bsp_id"] ?>" <?= $data["model"]['pay_type'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val["bsp_svalue"] ?></option>
                    <?php } ?>
                </select><label
                    class="label-width  need_pay <?= ($data["model"]['pay_type'] != null || $data["model"]['pac_id'] == $creditId) ? "" : "hiden" ?>">需付款金额</label><label
                    class="<?= ($data["model"]['pay_type'] != null || $data["model"]['pac_id'] == $creditId) ? "" : "hiden" ?> need_pay">：</label><input
                    type="text"
                    class="value-width easyui-validatebox need_pay total <?= ($data["model"]['pay_type'] != null || $data["model"]['pac_id'] == $creditId) ? "" : "hiden" ?>"
                    style="margin-left: 3px"
                    value="<?= $data["model"]['req_tax_amount'] ?>"
                    readonly="readonly">
            </div>
        </div>
        <div class="mb-10 <?= ($data["pay"][0]['stag_times'] != 0) ? "" : "hiden" ?> fenjiqi">
            <div class="inline-block  " id="pat2">
                <label class="label-width <?= ($data["pay"][0]['stag_times'] != 0) ? "" : "hiden" ?> fenjiqi"><span
                        class="red">*</span>分几期</label><label
                    class="fenjiqi">：</label>
                <select type="text"
                        class="require value-width easyui-validatebox <?= ($data["pay"][0]['stag_times'] != 0) ? "" : "hiden" ?> fenjiqi"
                        data-options="required:'true'" name="stag_qty" id="fenjiqi">
                    <option value="2" <?= count($data["pay"]) == 2 ? "selected" : null; ?>>2期</option>
                    <option value="3" <?= count($data["pay"]) == 3 ? "selected" : null; ?>>3期</option>
                    <option value="4" <?= count($data["pay"]) == 4 ? "selected" : null; ?>>4期</option>
                    <option value="5" <?= count($data["pay"]) == 5 ? "selected" : null; ?>>5期</option>
                </select>
            </div>
        </div>
        <?php if ($data["model"]['pac_id'] == $creditId) { ?>
            <div class="mb-10">
                <div class="inline-block" id="edu_pay">
                    <?php foreach ($data["pay"] as $k => $v) { ?>
                        <div class="inline-block mb-10"><label class="label-width ">信用额度类型</label><label>：</label>
                            <span style="width:100px;"><?= $v['credit_name'] ?></span><label
                                style="width:80px;">总额度</label><label>：</label>
                            ￥<span style="width:150px;"><?= $v['approval_limit'] ?></span><label
                                style="width:80px;">剩余额度</label><label>：</label>
                            ￥<span style="width:150px;" class="surplus_limit"><?= $v['surplus_limit'] ?></span>
                            <label style="width:80px;">支付额度</label><label>：</label>
                            <input type="text"
                                   class="value-width easyui-validatebox stag_cost stagCost validatebox-text"
                                   style="width:150px;"
                                   data-options="validType:['eduCompare','decimal[18,2]']"
                                   name="OrdPay[<?= $k ?>][stag_cost]" maxlength="21"
                                   value="<?= bcsub($v["stag_cost"], 0, 2) ?>">
                            <input type="hidden" name="OrdPay[<?= $k ?>][credit_id]" value="<?= $v['credit_id'] ?>">
                        </div><br>
                    <?php } ?>
                </div>
            </div>
            <div class="mb-10" id="fenqi"></div>
        <?php } else if ($data["model"]['pay_type'] == $fenqiPay) { ?>
            <div class="mb-10">
                <div class="inline-block  hiden" id="edu_pay">
                </div>
            </div>
            <div class="mb-10" id="fenqi">
                <?php if (!empty($data["pay"][0]["stag_times"])) { ?>
                    <?php foreach ($data["pay"] as $key => $val) { ?>
                        <div class="inline-block mb-10">
                            <label class="label-width "><span class="red">*</span>第<?= ($key + 1) ?>
                                期付款时间</label><label>：</label><input type="text"
                                                                    class="require value-width easyui-validatebox select-date"
                                                                    id="select_time_<?= $key + 1 ?>"
                                                                    onclick="select_time(<?= $key + 1 ?>,<?= count($data["pay"]) ?>);"
                                                                    name="OrdPay[<?= $key ?>][stag_date]"
                                                                    style="margin-left: 3px"
                                                                    value="<?= $val["stag_date"] ?>"
                                                                    data-options="required:'true'">
                            <input type="text" class="hiden" name="OrdPay[<?= $key ?>][stag_times]"
                                   value="<?= $val["stag_times"] ?>">
                            <label class="label-width "><span class="red">*</span>第<?= ($key + 1) ?>
                                期支付金额</label><label>：</label>
                            <input type="text" class="require value-width easyui-validatebox stag_cost"
                                   name="OrdPay[<?= $key ?>][stag_cost]" maxlength="21"
                                   data-options="required:'true',validType:'decimal[18,2]'"
                                   value="<?= bcsub($val["stag_cost"], 0, 2) ?>"></div>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="mb-10">
                <div class="inline-block  hiden" id="edu_pay">
                </div>
            </div>
            <div class="mb-10" id="fenqi"></div>
        <?php } ?>
        <div class="mb-10" id="pay_left" title="剩余支付金额"
             style="margin-left: 415px;color: red;margin-top: -5px;height: 16px">
        </div>
    </div>
    <h2 class="head-second">
        销售员信息
    </h2>
    <div class="mb-10">
        <div class="inline-block ">
            <label class="label-width ">销售员</label><label>：</label>
            <input type="text" id="SaleMan" class="value-width easyui-validatebox read-only"
                   readonly="readonly"
                   value="<?= $data["seller"]["staff_code"] ?>"><input class="hiden sale_id"
                                                                       name="OrdInfo[sell_delegate]"
                                                                       value="<?= $data["seller"]["staff_id"] ?>"/></td>
            <label class="label-width ">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</label><label>：</label>
            <input type="text" id="name" readonly="readonly" class="value-width easyui-validatebox read-only"
                   value="<?= $data["seller"]["staff"]["name"] ?>">
            <label class="label-width ">客户经理人</label><label>：</label>
            <input type="text" id="SaleManager" class="value-width easyui-validatebox read-only"
                   readonly="readonly" name="" value="<?= $data["seller"]["leader"] ?>">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block ">
            <label class="label-width ">销售部 门</label><label>：</label>
            <input type="text" id="SaleDep" readonly="readonly" class="value-width easyui-validatebox read-only"
                   value="<?= $data["seller"]["staff"]["organization_name"] ?>">
            <label class="label-width ">销售区域</label><label>：</label>
            <input type="text" id="SaleArea" readonly="readonly" class="value-width easyui-validatebox read-only"
                   value="<?= $data["seller"]["csarea"] ?>">
            <label class="label-width ">销售点</label><label>：</label>
            <input type="text" id="SaleStore" class="value-width easyui-validatebox read-only"
                   readonly="readonly" name="" value="<?= $data["seller"]["sts_sname"] ?>">
        </div>
    </div>
    <div class="space-20"></div>
    <div class="text-center">
        <button type="submit" class="button-blue-big save-form" id="submit">提交</button>
        <button class="button-white-big" onclick="window.location.href='<?= Url::to(["index"]) ?>'" type="button">取&nbsp;消
        </button>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    var creditId = "<?= $creditId ?>";
    var prepayId = "<?= $prepayId ?>";
    var allpay = "<?= $allpay ?>";
    var pdtRow = 0;  //输入料号的行数
    var lastTr = 0;   //区别每一行商品
    var firstChange = true;
    var creditHtml = "";
    var currency = <?= $data['model']["cur_id"]?>;
    $(function () {
        $(".Onlynum").numbervalid();
        $(".Onlynum2").numbervalid(2);
        $(".price").numbervalid(5, 7);//最多五位小数，整数位最多8位
        $(".stag_cost").numbervalid(2, 18);
        <?php if($data["model"]['pay_type'] == $fenqiPay){?>
        firstChange = false;
        $(".need_pay").show().appendTo("#pat2");
        <?php }?>
        $.extend($.fn.validatebox.defaults.rules, {
            eduCompare: {
                validator: function () {
                    var type = $(this).prev().prev().prev().val();
//                    var type_edu = parseFloat($('.credit_type' + type).html());
                    var type_edu = parseFloat($(this).parent().find("span.surplus_limit").html());
                    var pay = parseFloat($(this).val());
                    if (pay > type_edu) {
                        $.fn.validatebox.defaults.rules.eduCompare.message = '此类型剩余额度不足';
                    }
                    return (pay <= type_edu && pay >= 0);
                },
                message: '此类型剩余额度不足'
            },
            intnum: {
                validator: function (value) {
                    return /^(?!0+(?:\.0+)?$)(?:[1-9]\d*|0)(?:\.\d{1,2})?$/.test($.trim(value));
                },
                message: '大于0不能超过2位小数'
            },
            noZero: {
                validator: function (value) {
                    if (value <= 0) {
                        $.fn.validatebox.defaults.rules.noZero.message = '销售单价不能小于等于0';
                    }
                    return (value > 0);
                },
                message: '销售单价不能小于等于0'
            }
        });
        optionOnce();
        $("#cust_id").change();
        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });
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
                var total = parseFloat($("#total").val());
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
            return isok;
        }, function (data) {
            if (data.flag == 1) {
                //送审
                var id = <?= $data["model"]["ord_id"] ?>;
                var url = "<?=Url::to(['index'], true)?>";
                var type = <?= $type ?>;
                $.fancybox({
                    href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + parseInt(type) + "&id=" + id + "&url=" + url,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 750,
                    height: 480,
                    afterClose: function () {
                        location.reload();
                    }
                });
            }
            if (data.flag == 0) {
                if ((typeof data.msg) == 'object') {
                    layer.alert(JSON.stringify(data.msg), {icon: 2});
                } else {
                    layer.alert(data.msg, {icon: 2});
                }
                $("button[type='submit']").prop("disabled", false);
            }
        });
        var paytype = "<?= $data["model"]['pay_type']?>";
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
    //单价变化
    $(document).on("change", ".price", function () {
        totalPrices();

    });
    //运费变化
    $(document).on("change", ".freight", function () {
        totalPrices();

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
    //配送方式变化
    $(document).on("change", ".distribution", function () {
        var distri = $(this).val();
        if (distri == "<?= $self_take ?>") {
            $(this).parent().parent().find("select.transport").validatebox({
                required: false
            });
            $(this).parent().parent().find("select.whouse").validatebox({
                required: true
            });
            $(this).parent().parent().find("select.transport option")[0].selected = true;
            $(this).parent().parent().find("select.transport").attr("disabled", true);
            $(this).parent().parent().find("select.whouse").attr("disabled", false);
            $(this).parent().parent().find("select.transport").hide();
            $(this).parent().parent().find("select.whouse").show();
            $(this).parent().parent().find("input.freight").val(0);
        } else if (distri == "<?= $no_take ?>") {
            $(this).parent().parent().find("select.transport").validatebox({
                required: true
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
    //运输方式变化   请求运费
    $(document).on("change", ".transport", function () {
        $pdt_no = $(this);
        var type = $(this).val();
        if (type == "") {
            return false;
        }
        var pdt = $(this).parent().parent().find("input.pdt_id").val();
        var num = $(this).parent().parent().find("input.sapl_quantity").val();
        var addr = "<?= $data["model"]["receipt_areaid"]?>";
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
    //付款方式
    $("#payid").on("change", function () {
        var xin = $(this).val();
        $("#pay_left").html("");
        if (xin == prepayId) {   //预付款
            $(".paytype").show().validatebox({
                required: true
            });
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
            $("#edu_pay").html(creditHtml).show();
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
        var xin = $(this).val();
        $("#pay_left").html("");
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
            for (i = 0; i < 2; i++) {
                $("#fenqi").append('<div class="inline-block mb-10">' +
                    '<label class="label-width ">第' + (i + 1) + '期付款时间</label><label>：</label>' +
                    '<input type="text" class="value-width easyui-validatebox select-date" id="select_time_' + (i + 1) + '" name="OrdPay[' + i + '][stag_date]" onclick="select_time(' + (i + 1) + ',' + xin + ')" style="margin-left: 3px" data-options="required:\'true\'" value="">' +
                    '<input type="text" class="hiden" name="OrdPay[' + i + '][stag_times]" value="' + (i + 1) + '">' +
                    '<label class="label-width " style="margin-left: 4px">第' + (i + 1) + '期支付金额</label><label>：</label>' +
                    '<input type="text" class="value-width easyui-validatebox stag_cost" maxlength="21" name="OrdPay[' + i + '][stag_cost]" data-options="required:\'true\',validType:\'decimal[18,2]\'" style="margin-left: 3px"></div>')
            }
            $(".Onlynum2").numbervalid(2);
            $(".stag_cost").numbervalid(2, 18);
        }
        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });
    });
    var creditType = null;
    var credit_len = $(".edu_pay_add").length;
    //添加额度支付类型
    $(document).on("change", "#total", function () {
        var total = $(this).val();
        var totalEdu = parseFloat($(".allCredit").html());
//        console.log(total);
//        console.log(totalEdu);
        if (total > totalEdu) {
            for (i = 0; i < $('#payid option').length; i++) {
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

    //获取到客户  信用额度变化
    $("#edu").on("change", function () {
        var xin = $(this).val();
        if ($(".total").val() > xin) {
            for (i = 0; i < $('#payid option').length; i++) {
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
    //分期下拉选项
    $("#fenjiqi").on("change", function () {
        var xin = $(this).val();
        $("#fenqi").show().html("");
        $("#pay_left").html("");
        for (i = 0; i < xin; i++) {
            $("#fenqi").append('<div class="inline-block mb-10">' +
                '<label class="label-width ">第' + (i + 1) + '期付款时间</label><label>：</label>' +
                '<input type="text" class="value-width easyui-validatebox select-date" id="select_time_' + (i + 1) + '" name="OrdPay[' + i + '][stag_date]" onclick="select_time(' + (i + 1) + ',' + xin + ')" style="margin-left: 3px" data-options="required:\'true\'" value=""> ' +
                '<input type="text" class="hiden" name="OrdPay[' + i + '][stag_times]" value="' + (i + 1) + '">' +
                '<label class="label-width " >第' + (i + 1) + '期支付金额</label><label>：</label>' +
                '<input type="text" class="value-width easyui-validatebox stag_cost" maxlength="21" name="OrdPay[' + i + '][stag_cost]" data-options="required:\'true\',validType:\'decimal[18,2]\'" style="margin-left: 4px"> </div>'
            );
        }
        $(".Onlynum2").numbervalid(2);
        $(".stag_cost").numbervalid(2, 18);
        $('input[type!="hidden"],select,textarea', $("#fenqi")).each(function () {
            $(this).validatebox();//验证初始化
        });
    });
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
        var ttfreight = 0;//总运费
        var ttprice = 0;//商品总金额(含税)
        var total = 0;//订单总金额(含税)

        for (i = 0; i < trs.length; i++) {
            var quantity = $(trs[i]).find("input.sapl_quantity").val();//数量
            var price = $(trs[i]).find("input.price").val();//销售单价（含税）
            var thePrice = $(trs[i]).find("p.thePrice").html();//商品定价（含税）
            var cess = $(trs[i]).find("input.cess").val();//税率
            var discount = $(trs[i]).find("input.discount").val();//折扣率
            var freight = $(trs[i]).find("input.freight").val();//运费
//            if (quantity && price && cess && discount && freight) {
//            console.log((quantity!=""));
            if (quantity != "" && price != "") {
                quantity = parseFloat(quantity);
                price = parseFloat(price);
                var ttprice_hs = price * quantity;  //折扣后金额
                $(trs[i]).find("p.price_off").html(ttprice_hs.toFixed(2));
                ttprice += ttprice_hs;
            } else {
                $(trs[i]).find("p.price_off").html(0);
            }
            if (quantity != "" && thePrice != "") {
                //商品总金额

                if (thePrice != "面议" && thePrice != "") {
                    thePrice = parseFloat(thePrice);
                    quantity = parseFloat(quantity);
                    $(trs[i]).find("input.price_hs").val((thePrice * quantity).toFixed(2));
                }
            } else {
                $(trs[i]).find("input.price_hs").val(0);
            }
            if (price != "" && thePrice != "") {
                //折扣率
                if (thePrice != "面议" && thePrice != "") {
                    thePrice = parseFloat(thePrice);
                    price = parseFloat(price);
                    $(trs[i]).find("input.discount").val((price * 100 / thePrice).toFixed(2));
                }
            }
            if (freight != "") {
                freight = parseFloat(freight);
                ttfreight += freight;
            }
        }
        total = total + ttfreight + ttprice;
        $(".bill_freight").val(ttfreight.toFixed(2));
        $(".bill_oamount").val(ttprice.toFixed(2));
        $(".total").val(total.toFixed(2));
        $("#total").change();
    }
    //获取客户已有帐信额度
    $("#cust_id").on('change', function () {
        var id = $(this).val();
        var payid = $('#payid').val();
        //获取客户已有帐信额度
        $.ajax({
            type: "get",
            dataType: "json",
            url: "<?= Url::to(["get-cust-credit"])?>" + '?id=' + id + '&currency=' + currency,
            success: function (data) {
                if (data.total != 0 && data.total != "0") {
                    $(".table_credit").show();
                    creditType = data.rows;
                    var totalEdu = 0;
                    creditHtml = "";
                    for (var x in data.rows) {
                        totalEdu += parseFloat(data.rows[x].surplus_limit);
                        creditHtml += '<div class="inline-block mb-10">' +
                            '<label class="label-width ">信用额度类型</label><label>：</label> ' +
                            '<span style="width:100px;">' + data.rows[x].credit_name + '</span>' +
                            '<label style="width:80px;">总额度</label><label>：</label> ' +
                            '￥<span style="width:150px;">' + data.rows[x].approval_limit + '</span>' +
                            '<label style="width:80px;">剩余额度</label><label>：</label> ' +
                            '￥<span style="width:150px;" class="surplus_limit">' + data.rows[x].surplus_limit + '</span>' +
                            '<label style="width:80px;">支付额度</label><label>：</label> ' +
                            '<input type="text" class="value-width easyui-validatebox stag_cost stagCost validatebox-text" maxlength="21" style="width:150px;" data-options="validType:[\'eduCompare\',\'decimal[18,2]\']" name="OrdPay[' + x + '][stag_cost]">' +
                            '<input type="hidden" name="OrdPay[' + x + '][credit_id]" value="' + data.rows[x].credit_type + '">' +
                            '</div></br>';
                    }
//                    $("#edu_pay").show().html(creditHtml);
                    $(".allCredit").html("");
                    $(".allCredit").html(totalEdu);
                    var total = $("#total").val();
                    if (totalEdu >= total) {
                        $(".table_credit").show();
                        for (i = 0; i < $('#payid option').length; i++) {
                            if ($('#payid option')[i].value == creditId) {  //8为信用额度支付
                                $('#payid option')[i].disabled = false;
                            }
                        }
                        if (!firstChange) {
                            $("input.stagCost").change();
                            firstChange = false;
                        }
                    } else {
//                        $(".table_credit").hide();
                        for (i = 0; i < $('#payid option').length; i++) {
                            if ($('#payid option')[i].value == creditId) {  //8为信用额度支付
                                $('#payid option')[i].disabled = true;
                                $($('#payid option')[i]).removeAttr("selected");
                            }
                        }
                        if (payid == creditId) {
                            $('#payid').change();
                        }
                        if (!firstChange) {
                            $("input.stagCost").change();
                            firstChange = false;
                        }
                    }
                } else {
                    for (i = 0; i < $('#payid option').length; i++) {
                        if ($('#payid option')[i].value == creditId) {  //8为信用额度支付
                            $('#payid option')[i].disabled = true;
                            $($('#payid option')[i]).removeAttr("selected");
                        }
                    }
                    if (payid == creditId) {
                        $('#payid').change();
                    }
                    if (!firstChange) {
                        $("input.stagCost").change();
                        firstChange = false;
                    }
                }
                totalPrices();
            }
        })
    });
    //选择商品 重置行
    function reset(obj) {
        var td = $(obj).parents("tr").find("td");
        $(obj).parents("tr").find("input").val("");
    }
    //选择商品 删除行
    function vacc_del(obj, id) {
        $(obj).parents("tr").remove();
        resetNum();
    }
    //选择商品 重新排序
    function resetNum() {
        var len = $('#product_table tr').length;
        for (var i = 0; i < len; i++) {
            //序号重排
            $('#product_table tr:eq(' + i + ') td:first').next().html('<span class="width-40">' + (i + 1) + '</span>');
        }
    }
    function savePrices(obj, prices) {
        $(".(' + obj + ')").data("array", prices);
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
