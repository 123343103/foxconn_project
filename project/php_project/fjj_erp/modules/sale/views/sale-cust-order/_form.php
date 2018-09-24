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
$cust_attachment = isset($quotedHModel["cust_attachment"]) ? unserialize($quotedHModel["cust_attachment"]) : null;
$seller_attachment = isset($quotedHModel["seller_attachment"]) ? unserialize($quotedHModel["seller_attachment"]) : null;
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
foreach ($downList["currency"] as $key => $val) {
    if ($val["bsp_svalue"] == "RMB") {
        $RMB = $val["bsp_id"];
    }
}
//dumpE($self_take);
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

    .width-159 {
        width: 159px;
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
        width: 250px;
    }

    .space-30 {
        height: 30px;
    }

    /*仅设为默认地址使用*/
    .grey {
        color: grey;
    }

    .address-select {
        border: 1px solid #cccccc;
        width: 313px;
        height: 85px;
        display: inline-block;
        padding: 5px;
        margin-right: 5px;
        cursor: pointer;
    }

    .address-add {
        border: 1px solid #cccccc;
        width: 313px;
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

    input.read-only {
        background-color: rgb(255, 255, 255);
        border: 1px solid #fff;
    }

    thead tr th p {
        color: white;
    }
</style>
<h1 class="head-first" xmlns="http://www.w3.org/1999/html">
    <?= $this->title ?>
</h1>
<h2 class="head-second">
    基本信息
</h2>
<div class="mb-30">

    <?php $form = ActiveForm::begin(
        ['id' => 'add-form', 'options' => ['enctype' => 'multipart/form-data']]
    ); ?>
    <div class="mb-10 ml-60">
        <div class="inline-block  ">
            <label class="label-width"><span class="red">*</span>订单类型</label><label>：</label>
            <select type="text" class="value-width easyui-validatebox"
                    data-options="required:'true'" name="ReqInfo[saph_type]">
                <option value="">请选择...</option>
                <?php foreach ($downList["orderType"] as $key => $val) { ?>
                    <option
                        value="<?= $val["business_type_id"] ?>" <?= $quotedHModel['saph_type'] == $val['business_type_id'] ? "selected" : null; ?>><?= $val["business_value"] ?></option>
                <?php } ?>
            </select>
            <label class="label-width ml-140" for="SaleCostType[contract_no]">合同编号</label><label>：</label>
            <input type="text" class="value-width easyui-validatebox" data-options="validType:'maxLength[20]'"
                   name="ReqInfo[contract_no]" maxlength="20"
                   value="<?= $quotedHModel['contract_no'] ?>">
        </div>
    </div>
    <div class="mb-10 ml-60">
        <div class="inline-block ">
            <label class="label-width "><span class="red">*</span>客戶全称</label><label>：</label>
            <input type="text" id="cust_sname" readonly="readonly"
                   class="value-width easyui-validatebox read-only text-no-next"
                   style="<?= empty($customer['cust_sname']) ? "display:none" : "" ?>"
                   data-options="required:'true'" value="<?= $customer['cust_sname'] ?>">
            <input type="text" id="cust_id" readonly="readonly" class="value-width easyui-validatebox hiden"
                   data-options="required:'true'" name="ReqInfo[cust_id]"
                   value="<?= $quotedHModel['cust_id'] ?>">
            <span class="width-50"
                  style="<?= (yii::$app->controller->action->id == 'update') ? 'visibility: hidden' : '' ?>"><a
                    id='select_customer'>选择客户</a></span>
            <input type="text" id="bu_wei" readonly="readonly"
                   class="value-width easyui-validatebox read-only <?= empty($customer['cust_sname']) ? "" : "hiden" ?>"
                   data-options="required:'true'">
            <span class="red ml-90"></span><label class="label-width">客户代码</label><label>：</label>
            <input type="text" id="apply_code" readonly="readonly" class="value-width easyui-validatebox read-only"
                   value="<?= $customer['cust_code'] ?>">
        </div>
    </div>
    <div class="hiden moreInfo">
        <div class="mb-10 ml-60">
            <div class="inline-block ">
                <label class="label-width"><span class="red">*</span>联系人</label><label>：</label>
                <input type="text" id="cust_contacts" class="value-width easyui-validatebox"
                       name="ReqInfo[cust_contacts]" data-options="required:'true'" maxlength="20"
                       value="<?= $quotedHModel['cust_contacts'] ?>">
                <label class="label-width ml-140"><span class="red">*</span>联系电话</label><label>：</label>
                <input type="text" id="cust_tel2" class="value-width IsTel easyui-validatebox"
                       data-options="required:'true',validType:'mobile'"
                       name="ReqInfo[cust_tel]" maxlength="15" placeholder="请输入 138xxxxxxxx"
                       value="<?= $quotedHModel['cust_tel'] ?>">
            </div>
        </div>
        <div class="mb-10 ml-60">
            <div class="inline-block ">
                <label class="label-width ">客户地址</label><label>：</label>
                <input type="text" id="customer_address" readonly="readonly"
                       class="easyui-validatebox read-only text-no-next" style="width: 200px"
                       value="<?= $customer['cust_adress'] ?>">
                <label class="label-width ml-140">公司电话</label><label>：</label>
                <input type="text" id="cust_tel1" readonly="readonly"
                       class="value-width easyui-validatebox read-only"
                       name=""
                       value="<?= $customer['cust_tel1'] ?>">
            </div>
        </div>
        <div class="mb-10 ml-60">
            <div class="inline-block ">
                <label class="label-width ">交易法人</label><label>：</label>
                <select type="text" class="value-width easyui-validatebox"
                        name="ReqInfo[corporate]">
                    <option value="">请选择...</option>
                    <?php foreach ($downList["corporate"] as $key => $val) { ?>
                        <option value="<?= $val["company_id"] ?>"
                            <?= (!empty($quotedHModel['corporate']) ? $quotedHModel['corporate'] : $seller['company_id']) == $val['company_id'] ? "selected" : null; ?>><?= $val["company_name"] ?></option>
                    <?php } ?>                    </select>
                <label class="label-width ml-140">交易模式</label><label>：</label>
                <select type="text" class="value-width easyui-validatebox"
                        name="ReqInfo[trade_mode]">
                    <option value="">请选择...</option>
                    <?php foreach ($downList["pattern"] as $key => $val) { ?>
                        <option
                            value="<?= $val["tac_id"] ?>" <?= $quotedHModel['trade_mode'] == $val['tac_id'] ? "selected" : null; ?>><?= $val["tac_sname"] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="mb-10 ml-60">
            <div class="inline-block ">
                <label class="label-width "><span class="red">*</span>交易币别</label><label>：</label>
                <select type="text" class="value-width easyui-validatebox" id="cur_id" disabled="disabled"
                        data-options="required:'true'" name="Req_Value[cur_id]">
                    <?php foreach ($downList["currency"] as $key => $val) { ?>
                        <option
                            value="<?= $val["bsp_id"] ?>" <?= (!empty($quotedHModel['cur_id']) ? $quotedHModel['cur_id'] : $RMB) == $val['bsp_id'] ? "selected" : null; ?>><?= $val["bsp_svalue"] ?></option>
                    <?php } ?>
                </select>
                <input type="hidden" name="ReqInfo[cur_id]"
                       value="<?= (!empty($quotedHModel['cur_id']) ? $quotedHModel['cur_id'] : $RMB) ?>">
                <label class="label-width ml-140">发票类型</label><label>：</label>
                <select type="text" class="value-width easyui-validatebox" id="invoice_type"
                        name="ReqInfo[invoice_type]">
                    <?php foreach ($downList["invoiceType"] as $key => $val) { ?>
                        <option
                            value="<?= $val["bsp_id"] ?>" <?= $quotedHModel['invoice_type'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val["bsp_svalue"] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="mb-10 ml-60">
            <div class="inline-block ">
                <label class="label-width ">发票抬头</label><label>：</label>
                <input type="text" id="invoice_title" readonly="readonly"
                       class="easyui-validatebox read-only" style="width: 646px" name="ReqInfo[invoice_title]"
                       value="<?= $quotedHModel['invoice_title'] ?>">
            </div>
        </div>
        <div class="mb-10 overflow-auto  ml-60">
            <div class=" float-left"><label class="label-width "><span
                        class="red red_fapiao hiden">*</span>发票抬头地址</label><label>：</label></div>
            <div class="float-left" style="margin-left: 4px;width: 700px">
                <select class="width-159 disName easyui-validatebox mb-10 fapiao require_or_not"
                        id="title_address_country">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['country'] as $key => $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $title_district['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                </select>
                <select class="width-159 disName easyui-validatebox fapiao require_or_not"
                        id="title_address_province">
                    <option value="">请选择...</option>
                    <?php if (!empty($title_district)) { ?>
                        <?php foreach ($title_district['twoLevel'] as $val) { ?>
                            <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $title_district['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <select class="width-159 disName easyui-validatebox fapiao require_or_not"
                        id="title_address_city">
                    <option value="">请选择...</option>
                    <?php if (!empty($title_district)) { ?>
                        <?php foreach ($title_district['threeLevel'] as $val) { ?>
                            <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $title_district['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <select class="width-159 disName easyui-validatebox fapiao require_or_not"
                        id="title_address_town"
                        name="ReqInfo[invoice_Title_AreaID]">
                    <option value="">请选择...</option>
                    <?php if (!empty($title_district)) { ?>
                        <?php foreach ($title_district['fourLevel'] as $val) { ?>
                            <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $title_district['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <input class="remove-require width-552 easyui-validatebox fapiao require_or_not" type="text"
                       name="ReqInfo[invoice_Title_Addr]"
                       style="width: 646px" placeholder="最多输入200个字"
                       value="<?= $quotedHModel['invoice_Title_Addr'] ?>" maxlength="200" id="invoice_title_address">
            </div>
        </div>
        <div class="mb-10 overflow-auto  ml-60">
            <div class=" float-left"><label class="label-width "><span
                        class="red red_fapiao hiden">*</span>发票寄送地址</label><label>：</label></div>
            <div class="float-left width-552" style="margin-left: 4px;width: 700px">
                <select class="width-159 disName easyui-validatebox mb-10 fapiao require_or_not"
                        id="send_address_country">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['country'] as $key => $val) { ?>
                        <option
                            value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $send_district['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                </select>
                <select class="width-159 disName easyui-validatebox fapiao require_or_not"
                        id="send_address_province">
                    <option value="">请选择...</option>
                    <?php if (!empty($send_district)) { ?>
                        <?php foreach ($send_district['twoLevel'] as $val) { ?>
                            <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $send_district['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <select class="width-159 disName easyui-validatebox fapiao require_or_not"
                        id="send_address_city">
                    <option value="">请选择...</option>
                    <?php if (!empty($send_district)) { ?>
                        <?php foreach ($send_district['threeLevel'] as $val) { ?>
                            <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $send_district['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <select class="width-159 disName easyui-validatebox fapiao require_or_not"
                        id="send_address_town"
                        name="ReqInfo[invoice_AreaID]">
                    <option value="">请选择...</option>
                    <?php if (!empty($send_district)) { ?>
                        <?php foreach ($send_district['fourLevel'] as $val) { ?>
                            <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $send_district['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <input class="remove-require width-552 easyui-validatebox fapiao require_or_not" type="text"
                       name="ReqInfo[invoice_Address]"
                       style="width: 646px" placeholder="最多输入200个字"
                       value="<?= $quotedHModel['invoice_Address'] ?>" maxlength="200" id="invoice_mail_address">
            </div>
        </div>
        <div class="mb-10 ml-60">
            <div class="inline-block ">
                <label class="label-width ">附件</label><label>：</label>
                <span style="text-align: center;">
                <?php foreach ($files as $key => $val) { ?>
                    <a class="text-center width-150 color-w ml-10" target="_blank"
                       href="<?= \Yii::$app->ftpPath['httpIP'] ?>/ord/req/<?= explode('_', trim($val['file_new'], '_'))[0] ?>/<?= $val['file_new'] ?>"><?= $val["file_old"] ?></a>
                <?php } ?>
            </span>
                <a href="javascript:;" class="file ml-20"
                   style="text-align: center;"><?= empty($cust_attachment) ? "选择档案" : "重新选择" ?>
                    <input type="file" onchange="change(this)" multiple="multiple"
                           class="width-60" style="width:65px;"
                           name="Upload[custAttach][]"
                           value=""/>
                </a>
            </div>
        </div>
        <div class="mb-10 ml-60">
            <div class="inline-block ">
                <label class="label-width ">订单备注说明</label><label>：</label>
                <input type="text" class="easyui-validatebox" style="width: 646px" maxlength="200"
                       placeholder="最多输入200个字"
                       name="ReqInfo[saph_remark]" value="<?= $quotedHModel['saph_remark'] ?>">
            </div>
        </div>
        <div style="display: inline-block">
            <div class="mb-10  ml-60" style="display: inline-block">
                <label class="label-width"><span class="red">*</span>收货地址</label><label>：</label>
            </div>
            <span><a id="editAdrress"
                     style="text-align-last: left;">使用新的收货地址</a></span><span
                class="ml-10 hiden">您的收货地址已达到8个,不能新增新地址 !</span>
        </div>
        <div class="ml-155" id="selectAddrr">
        </div>
        <input type="text" class="easyui-validatebox hiden" id="myAddress"
               name="delivery_addr" value="<?= $quotedHModel['delivery_addr'] ?>">
    </div>
    <div class="mb-10 mt-20 ml-60">

        <a id="more" href="javascript:more()">更多...</a>
        <a id="less" class="hiden" href="javascript:less()">收起...</a>
    </div>

    <h2 class="head-second">
        订单商品信息 <span class="text-right float-right">
    </h2>
    <div class="mb-10 tablescroll" style="overflow-x: scroll">
        <table class="table">
            <thead>
            <tr>
                <th><p class="width-40 color-w">序号</p></th>
                <th><p class="width-40 color-w"><input type="checkbox" id="checkAll"></p></th>
                <th><p class="width-150 color-w"><span class="red">*</span>料号</p></th>
                <th><p class="width-150 color-w">品名</p></th>
                <th><p class="width-150 color-w"><span class="red">*</span>下单数量</p></th>
                <th><p class="width-150 color-w">商品定价（含税）</p></th>
                <th><p class="width-150 color-w">销售单价（含税）</p></th>
                <th><p class="width-150 color-w">配送方式</p></th>
                <th><p class="width-150 color-w"><span class="red">*</span>运输方式</p></th>
                <th><p class="width-150 color-w">自提仓库</p></th>
                <th><p class="width-150 color-w">运费（含税）</p></th>
                <th><p class="width-150 color-w"><span class="red">*</span>需求交期</p></th>
                <th><p class="width-150 color-w">交期</p></th>
                <th><p class="width-150 color-w">税率（%）</p></th>
                <th><p class="width-150 color-w">折扣率（%）</p></th>
                <th><p class="width-150 color-w">商品总价（含税）</p></th>
                <th><p class="width-150 color-w">折扣后金额</p></th>
                <th><p class="width-150 color-w">备注</p></th>
                <th><p class="width-150 color-w">操作</p></th>
            </tr>
            </thead>
            <tbody id="product_table">
            <?php if (\Yii::$app->controller->action->id == "update") { ?>
                <?php foreach ($quotedLModel as $key => $val) { ?>
                    <tr>
                        <td class="hiden"><span data-id="<?= $key ?>"></span></td>
                        <td><span class="width-40"><?= ($key + 1) ?></span></td>
                        <td><span class="width-40"><input type="checkbox"></span></td>
                        <td><input class="height-30 width-150 text-center  pdt_no easyui-validatebox" type="text"
                                   style="height: 30px"
                                   maxlength="20" data-options="required:true,validType:'exist',delay:10000"
                                   data-act="<?= Url::to(['pdt-validate']) ?>" data-scenario="exist"
                                   data-attr="part_no" data-id="" value="<?= $val["pdt_no"] ?>"
                            ><input class="hiden pdt_id" name="orderL[<?= $key ?>][prt_pkid]"
                                    value="<?= $val["prt_pkid"] ?>"/></td>
                        <td><p
                                class="text-center text-no-next width-150 pdt_name"
                                title="<?= $val["pdt_name"] ?>"><?= $val["pdt_name"] ?></p>
                        </td>
                        <td><a class="icon-minus minus_quantity"
                               style="position: relative;display:inline;left: 4px"></a><input
                                class="height-30 width-150 easyui-validatebox text-center sapl_quantity"
                                type="text"
                                style="margin-left: -10px;margin-right: -10px"
                                maxlength="7" value="<?= bcsub($val["sapl_quantity"], 0, 2) ?>"
                                name="orderL[<?= $key ?>][sapl_quantity]" data-min_order="<?= $val["min_order"] ?>"
                                data-quantity="<?= $val["pdt_qty"] ?>"
                                data-options="required:true,validType:'decimal[7,2]'"
                            ><a class="icon-plus add_quantity" onclick=""
                                style="position: relative;display:inline;right: 4px"></a>
                        </td>
                        <td><p
                                class="text-center width-150 thePrice"><?= ($val["price"] == -1) ? "面议" : bcsub($val["price"], 0, 5) ?></p>
                        </td>
                        <td><input class="height-30 width-150 text-center  price easyui-validatebox" type="text"
                                   maxlength="14"
                                   name="orderL[<?= $key ?>][uprice_tax_o]"
                                   data-options="validType:['noZero','decimal[7,5]']"
                                   value="<?= bcsub($val["uprice_tax_o"], 0, 5) ?>"
                            ></td>
                        <td><select class="height-30 width-150 text-center easyui-validatebox distribution" type="text"
                                    data-options="required:'true'"
                                    name="orderL[<?= $key ?>][distribution]">
                                <?php foreach ($downList["dispatching"] as $key1 => $val1) { ?>
                                    <option
                                        value="<?= $val1["tran_id"] ?>" <?= ($val['transport'] == -1 && $val1["tran_id"] == $no_take) ? "disabled" : null; ?> <?= ($val['self_take'] != 1 && $val1["tran_id"] == $self_take) ? "disabled" : null; ?> <?= $val['distribution'] == $val1['tran_id'] ? "selected" : null; ?>><?= $val1["tran_sname"] ?></option>
                                <?php } ?>
                            </select></td>
                        <td><select
                                class="height-30 width-150 text-center transport <?= ($val['distribution'] == $self_take) ? "hiden" : ""; ?>"
                                type="text"
                                name="orderL[<?= $key ?>][transport]">
                                <option value="">请选择...</option>
                                <?php foreach ($val["transport"] as $key1 => $val1) { ?>
                                    <option
                                        value="<?= $val1["id"] ?>" <?= $val['transport_id'] == $val1['id'] ? "selected" : null; ?>><?= $val1["name"] ?></option>
                                <?php } ?>
                            </select></td>
                        <td><select
                                class="height-30 width-150  text-center whouse <?= ($val['distribution'] == $no_take) ? "hiden" : null; ?>"
                                type="text"
                                name="orderL[<?= $key ?>][whs_id]">
                                <option value="">请选择...</option>
                                <?php foreach ($val["wh"] as $key2 => $val2) { ?>
                                    <option
                                        value="<?= $val2["wh_id"] ?>" <?= $val['whs_id'] == $val2['wh_id'] ? "selected" : null; ?>><?= $val2["wh_name"] ?></option>
                                <?php } ?>
                            </select></td>
                        <td><input class="height-30 width-150 text-center  freight read-only" readonly="readonly"
                                   type="text" maxlength="20"
                                   value="<?= empty($val["tax_freight"]) ? "0" : bcsub($val["tax_freight"], 0, 5) ?>"
                                   name="orderL[<?= $key ?>][tax_freight]"></td>
                        <td><input class="height-30 width-150 select-date-time easyui-validatebox "
                                   readonly="readonly" id="start_time_<?= $key ?>"
                                   data-options="required:true" value="<?= $val["request_date"] ?>"
                                   onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate:'%y-%M-%d %H:%m', maxDate: '#F{$dp.$D(\'end_time_<?= $key ?>\');}' })"
                                   name="orderL[<?= $key ?>][request_date]" placeholder="请选择"></td>
                        <td><input class="height-30 width-150 select-date-time easyui-validatebox "
                                   readonly="readonly" id="end_time_<?= $key ?>"
                                   onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'start_time_<?= $key ?>\');}' })"
                                   value="<?= $val["consignment_date"] ?>"
                                   name="orderL[<?= $key ?>][consignment_date]" placeholder="请选择"></td>
                        <td><input class="height-30 width-150 text-center easyui-validatebox  cess read-only"
                                   type="text" maxlength="20"
                                   name="orderL[<?= $key ?>][cess]" data-options="required:true" readonly="readonly"
                                   value="<?= bcsub($val["cess"], 0, 2) ?>"
                            ></td>
                        <td><input class="height-30 width-150 text-center  discount read-only" type="text"
                                   maxlength="20"
                                   name="orderL[<?= $key ?>][discount]" readonly="readonly"
                                   value="<?= bcsub($val["discount"], 0, 2) ?>"
                            ></td>
                        <td><input class="height-30 width-150 text-center  read-only price_hs" type="text"
                                   maxlength="20"
                                   name="orderL[<?= $key ?>][tprice_tax_o]"
                                   value="<?= bcsub($val["tprice_tax_o"], '2', '.', '') ?>"
                                   readonly="readonly">
                        </td>
                        <td><p
                                class="text-center width-150 price_off"></p>
                        </td>
                        <td><input class="height-30 width-150 text-center easyui-validatebox " type="text"
                                   maxlength="50"
                                   data-options="validType:'maxLength[50]'"
                                   name="orderL[<?= $key ?>][sapl_remark]" value="<?= $val["sapl_remark"] ?>"
                            ></td>
                        <td><a class="width-150" onclick="reset(this)">重置</a> <a
                                onclick="vacc_del(this,'product_table')">删除</a></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td class="hiden"><span data-id="0"></span></td>
                    <td><span class="width-40">1</span></td>
                    <td><span class="width-40"><input type="checkbox"></span></td>
                    <td><input class="height-30 width-150 text-center easyui-validatebox pdt_no" type="text"
                               style="height: 30px"
                               data-value=""
                               maxlength="20" data-options="required:true,validType:'exist',delay:1000"
                               data-act="<?= Url::to(['pdt-validate']) ?>"
                               data-attr="part_no" data-id="" data-scenario="exist"
                        ><input class="hiden pdt_id" name="orderL[0][prt_pkid]"/></td>
                    <td><p
                            class="text-center text-no-next width-150 pdt_name"></p>
                    </td>
                    <td><a class="icon-minus minus_quantity"
                           style="position: relative;display:inline;left: 4px"></a><input
                            class="height-30 width-150  text-center easyui-validatebox sapl_quantity"
                            type="text"
                            style="margin-left: -10px;margin-right: -10px"
                            maxlength="7"
                            name="orderL[0][sapl_quantity]" data-options="required:true,validType:'decimal[7,2]'"
                        ><a class="icon-plus add_quantity"
                            style="position: relative;display:inline;right: 4px"></a></td>
                    <td><p
                            class="text-center width-150 thePrice"></p>
                    </td>
                    <td><input class="height-30 width-150 text-center easyui-validatebox  price" type="text"
                               maxlength="14"
                               name="orderL[0][uprice_tax_o]" data-options="validType:['noZero','decimal[7,5]']"
                        ></td>
                    <td><select class="height-30 width-150 text-center easyui-validatebox distribution" type="text"
                                data-options="required:true"
                                name="orderL[0][distribution]">
                            <?php foreach ($downList["dispatching"] as $key => $val) { ?>
                                <option
                                    value="<?= $val["tran_id"] ?>"><?= $val["tran_sname"] ?></option>
                            <?php } ?>
                        </select></td>
                    <td><select class="height-30 width-150 text-center transport" type="text"
                                name="orderL[0][transport]">
                            <option value="">请选择...</option>
                        </select>
                        </select></td>
                    <td><select class="height-30 width-150  text-center whouse" type="text"
                                name="orderL[0][whs_id]">
                            <option value="">请选择...</option>
                        </select></td>
                    <td><input class="height-30 width-150 text-center  freight read-only" readonly="readonly"
                               type="text" maxlength="20"
                               name="orderL[0][tax_freight]"></td>
                    <td><input class="height-30 width-150 select-date-time easyui-validatebox "
                               readonly="readonly" id="start"
                               data-options="required:true"
                               onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate:'%y-%M-%d %H:%m', maxDate: '#F{$dp.$D(\'end\');}' })"
                               name="orderL[0][request_date]" placeholder="请选择"></td>
                    <td><input class="height-30 width-150 select-date-time easyui-validatebox "
                               readonly="readonly" id="end"
                               onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'start\');}' })"
                               name="orderL[0][consignment_date]" placeholder="请选择"></td>
                    <td><input class="height-30 width-150 text-center  cess read-only" type="text" maxlength="20"
                               name="orderL[0][cess]" readonly="readonly" value="17.00"></td>
                    <td><input class="height-30 width-150 text-center  discount read-only" type="text" maxlength="20"
                               name="orderL[0][discount]" readonly="readonly"></td>
                    <td><input class="height-30 width-150 text-center  read-only price_hs" type="text" maxlength="20"
                               name="orderL[0][tprice_tax_o]" readonly="readonly"></td>
                    <td><p
                            class="text-center width-150 price_off"></p>
                    </td>
                    <td><input class="height-30 width-150 easyui-validatebox text-center " type="text" maxlength="50"
                               name="orderL[0][sapl_remark]" data-options="validType:'maxLength[50]'"
                        ></td>
                    <td><a class="width-150" onclick="reset(this)">重置</a> <a
                            onclick="vacc_del(this,'product_table')">删除</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <p class="text-right mb-10">
        <a class="icon-plus" onclick="add_product()">添加行</a>
        <a id="select_product">批量添加商品</a>
        <a id="delete_product">删除</a></span>
    </p>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width-big ">总运费(含税)</label><label>：</label>
            <input type="text" class="value-width easyui-validatebox bill_freight"
                   readonly="readonly" name="ReqInfo[tax_freight]"
                   value="<?= $quotedHModel['tax_freight'] ?>">
            <label class="label-width-big ">商品总金额(含税)</label><label>：</label>
            <input type="text" class="value-width easyui-validatebox bill_oamount" readonly="readonly"
                   name="ReqInfo[prd_org_amount]" value="<?= $quotedHModel['prd_org_amount'] ?>">
            <label class="label-width-big ">订单总金额(含税)</label><label>：</label>
            <input type="text" class="value-width easyui-validatebox total" readonly="readonly" id="total"
                   name="ReqInfo[req_tax_amount]"
                   value="<?= $quotedHModel['req_tax_amount'] ?>">
        </div>
    </div>
    <h2 class="head-second">
        支付方式选择
    </h2>
    <p class="color-w hiden allCredit"></p>
    <div style="margin-left: 20px;">
        <div class="mb-10" style="margin-top: 20px">
            <div class="inline-block" id="pat1">
                <label class="label-width "><span class="red">*</span>付款方式</label><label>：</label>
                <select type="text" class="value-width easyui-validatebox" id="payid"
                        data-options="required:'true'" name="ReqInfo[pac_id]">
                    <option value="">请选择...</option>
                    <?php foreach ($downList["payment"] as $key => $val) { ?>
                        <option <?= $val['pac_id'] == $creditId ? "" : null; ?>
                            value="<?= $val["pac_id"] ?>" <?= $quotedHModel['pac_id'] == $val['pac_id'] ? "selected" : null; ?>><?= $val["pac_sname"] ?></option>
                    <?php } ?>
                </select>
                <label
                    class="label-width <?= ($quotedHModel['pay_type'] != 0 && $quotedHModel['pac_id'] != $creditId) ? "" : "hiden" ?> paytype"><span
                        class="red">*</span>支付类型</label><label
                    class="paytype <?= ($quotedHModel['pay_type'] != 0 && $quotedHModel['pac_id'] != $creditId) ? "" : "hiden" ?>">：</label>
                <select type="text" id="paytype"
                        class=" value-width easyui-validatebox <?= ($quotedHModel['pay_type'] != 0 && $quotedHModel['pac_id'] != $creditId) ? "" : "hiden" ?> paytype"
                         name="ReqInfo[pay_type]">
                    <option value="">请选择...</option>
                    <?php foreach ($downList["payType"] as $key => $val) { ?>
                        <option
                            value="<?= $val["bsp_id"] ?>" <?= $quotedHModel['pay_type'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val["bsp_svalue"] ?></option>
                    <?php } ?>
                </select><label
                    class="label-width  need_pay <?= ($quotedHModel['pay_type'] != null || $quotedHModel['pac_id'] == $creditId) ? "" : "hiden" ?>">需付款金额</label><label
                    class="<?= ($quotedHModel['pay_type'] != null || $quotedHModel['pac_id'] == $creditId) ? "" : "hiden" ?> need_pay">：</label><input
                    type="text"
                    class="value-width easyui-validatebox need_pay total <?= ($quotedHModel['pay_type'] != null || $quotedHModel['pac_id'] == $creditId) ? "" : "hiden" ?>"
                    style="margin-left: 3px" value="<?= $quotedHModel['req_tax_amount'] ?>"
                    readonly="readonly">
            </div>
        </div>
        <div class="mb-10 <?= ($ReqPay[0]['stag_times'] != 0) ? "" : "hiden" ?> fenjiqi">
            <div class="inline-block  " id="pat2">
                <label class="label-width <?= ($ReqPay[0]['stag_times'] != 0) ? "" : "hiden" ?> fenjiqi"><span
                        class="red">*</span>分几期</label><label
                    class="fenjiqi">：</label>
                <select type="text"
                        class="require value-width easyui-validatebox <?= ($ReqPay[0]['stag_times'] != 0) ? "" : "hiden" ?> fenjiqi"
                        data-options="required:'true'" name="stag_qty" id="fenjiqi">
                    <option value="2" <?= $ReqPay[0]['stag_times'] == 2 ? "selected" : null; ?>>2期</option>
                    <option value="3" <?= $ReqPay[0]['stag_times'] == 3 ? "selected" : null; ?>>3期</option>
                    <option value="4" <?= $ReqPay[0]['stag_times'] == 4 ? "selected" : null; ?>>4期</option>
                    <option value="5" <?= $ReqPay[0]['stag_times'] == 5 ? "selected" : null; ?>>5期</option>
                </select>
            </div>
        </div>
        <?php if (\Yii::$app->controller->action->id == "update" && $quotedHModel['pac_id'] == $creditId) { ?>
            <div class="mb-10">
                <div class="inline-block" id="edu_pay">
                    <?php foreach ($ReqPay as $k => $v) { ?>
                        <div class="inline-block mb-10"><label class="label-width ">信用额度类型</label><label>：</label>
                            <span style="width:100px;"><?= $v['credit_name'] ?></span><label
                                style="width:80px;">总额度</label><label>：</label>
                            <span style="width:150px;"><?= $v['approval_limit'] ?></span><label
                                style="width:80px;">剩余额度</label><label>：</label>
                            <span style="width:150px;" class="surplus_limit"><?= $v['surplus_limit'] ?></span>
                            <label style="width:80px;">支付额度</label><label>：</label>
                            <input type="text" maxlength="21"
                                   class="value-width easyui-validatebox stag_cost stagCost validatebox-text"
                                   style="width:150px;"
                                   data-options="required:'true',validType:['eduCompare','decimal[18,2]']"
                                   name="ReqPay[<?= $k ?>][stag_cost]"
                                   value="<?= bcsub($v["stag_cost"], 0, 2) ?>">
                            <input type="hidden" name="ReqPay[<?= $k ?>][stag_type]" value="<?= $v['stag_type'] ?>">
                        </div><br>
                    <?php } ?>
                </div>
            </div>
            <div class="mb-10" id="fenqi"></div>
        <?php } else if (\Yii::$app->controller->action->id == "update" && $quotedHModel['pay_type'] == $fenqiPay) { ?>
            <div class="mb-10">
                <div class="inline-block  hiden" id="edu_pay">
                </div>
            </div>
            <div class="mb-10" id="fenqi">
                <?php if (!empty($ReqPay[0]["stag_times"])) { ?>
                    <?php foreach ($ReqPay as $key => $val) { ?>
                        <div class="inline-block mb-10">
                            <label class="label-width "><span class="red">*</span>第<?= ($key + 1) ?>
                                期付款时间</label><label>：</label><input type="text"
                                                                    class="require value-width easyui-validatebox select-date"
                                                                    id="select_time_<?= $key + 1 ?>"
                                                                    onclick="select_time(<?= $key + 1 ?>,<?= count($ReqPay) ?>);"
                                                                    name="ReqPay[<?= $key ?>][stag_date]"
                                                                    style="margin-left: 3px"
                                                                    value="<?= $val["stag_date"] ?>"
                                                                    data-options="required:'true'">
                            <input type="text" class="hiden" name="ReqPay[<?= $key ?>][stag_times]"
                                   value="<?= $val["stag_times"] ?>">
                            <label class="label-width "><span class="red">*</span>第<?= ($key + 1) ?>
                                期支付金额</label><label>：</label>
                            <input type="text" class="require value-width easyui-validatebox stag_cost"
                                   name="ReqPay[<?= $key ?>][stag_cost]" maxlength="21"
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
        <div class="mb-10 text-no-next value-width" id="pay_left" title="剩余支付金额"
             style="margin-left: 415px;color: red;margin-top: -5px;height: 16px">
        </div>
    </div>
    <h2 class="head-second">
        销售员信息
    </h2>
    <div class="mb-10">
        <div class="inline-block ">
            <label class="label-width "><span class="red">*</span>销售员</label><label>：</label>
            <input type="text" id="SaleMan" class="value-width easyui-validatebox" readonly="readonly"
                   value="<?= $seller["staff_code"] ?>"><input class="hiden sale_id"
                                                               name="ReqInfo[sell_delegate]"
                                                               value="<?= $seller["staff_id"] ?>"/></td>
            <label class="label-width ">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</label><label>：</label>
            <input type="text" id="name" readonly="readonly" class="value-width easyui-validatebox"
                   value="<?= $seller["staff"]["name"] ?>">
            <label class="label-width ">客户经理人</label><label>：</label>
            <input type="text" id="SaleManager" class="value-width easyui-validatebox"
                   readonly="readonly" name="" value="<?= $seller["leader"] ?>">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block ">
            <label class="label-width ">销售部 门</label><label>：</label>
            <input type="text" id="SaleDep" readonly="readonly" class="value-width easyui-validatebox"
                   value="<?= $seller["staff"]["organization_name"] ?>">
            <label class="label-width ">销售区域</label><label>：</label>
            <input type="text" id="SaleArea" readonly="readonly" class="value-width easyui-validatebox"
                   value="<?= $seller["csarea"] ?>">
            <label class="label-width ">销售点</label><label>：</label>
            <input type="text" id="SaleStore" class="value-width easyui-validatebox"
                   readonly="readonly" name="" value="<?= $seller["sts_sname"] ?>">
        </div>
    </div>
    <div class="space-30"></div>
    <div class="text-center">
        <button type="submit" class="button-blue-big save-form" id="submit">确&nbsp;定</button>
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
    var invoiceChange = false;
    var creditHtml = "";
    var uid = <?= $seller["user_id"] ?>;
    $(function () {
        //输入控制
        $(".IsTel").telphone();
        $(".Onlynum").numbervalid();
        $(".Onlynum2").numbervalid(2);
        $(".sapl_quantity").numbervalid(2, 7);
        $(".stag_cost").numbervalid(2, 18);
        $(".price").numbervalid(5, 7);//最多六位小数，整数位最多13位

        <?php if($quotedHModel['pay_type'] == $fenqiPay){?>
        firstChange = false;
        $(".need_pay").show().appendTo("#pat2");
//        $(".pdt_id").change();
        <?php }?>
        $.extend($.fn.validatebox.defaults.rules, {
            eduCompare: {
                validator: function () {
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
        });
        optionOnce();
        $("#cust_id").change();
        $("#invoice_type").change();
        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });
        ajaxSubmitForm($("#add-form"), function () {
                $('input,select,textarea', $("#add-form")).each(function () {
                    if ($(this).is(':hidden')) {
                        if ($("#invoice_type option:selected").text() == "不需要发票" || !$(this).hasClass("require_or_not")) {
                            $(this).validatebox({
                                required: false
                            });
                        }
                    }
                });
                var isok = true;
                if ($("#myAddress").val() == "") {
                    isok = false;
                    layer.alert("请选择收货地址！", {icon: 2});
                }
                if ($("#cust_id").val() == "") {
                    isok = false;
                    layer.alert("请选择客户！", {icon: 2});
                }
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
                    layer.alert("请选择商品！", {icon: 2});
                }
                return isok;
            }
        );
        var paytype = "<?= $quotedHModel['pay_type']?>";
        if (paytype == allpay) {
        }
    });
    //选择客户
    $("#select_customer").click(function () {
        $.fancybox({
            href: "<?=Url::to(['select-customer'])?>",
            type: "iframe",
            padding: 0,
            autoSize: false,
            width: 800,
            height: 400
//            height: 520
        });
    });
    //选择商品 批量添加商品
    $("#select_product").click(function () {
        var $selectedRows = $("#product_table").find("input.pdt_id");
        var curr = $("#cur_id option:selected").val();//币别
        var selectedId = '';
        if ($selectedRows.length > 0) {
            $.each($selectedRows, function (i, n) {
                if (n.value != '') {
                    selectedId += n.value + ',';
                }
            });
            selectedId = selectedId.substr(0, selectedId.length - 1);
        }
        $.fancybox({
            width: 720,
            height: 500,
            padding: [],
            autoSize: false,
            type: "iframe",
            href: "<?=\yii\helpers\Url::to(['/ptdt/product-list/product-selector'])?>?filters=" + selectedId + "&curr=" + curr + "&uid=" + uid
        });
    });
    $('input[type!="hidden"]', $("#product_table")).on("change", function () {
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
        var addr = $("#myAddress").val();
        if (addr == "") {
            layer.alert("请先选择收货地址！", {icon: 2});
            $pdt_no.find("option")[0].selected = true;
            return false;
        }
        if (addr == "") {
            layer.alert("请先填写下单数量！", {icon: 2});
            $pdt_no.find("option")[0].selected = true;
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
    $(document).on("change", ".pdt_no", function () {
        var $pdt_no = $(this);
        var exsit_num = 0;
        $(".pdt_no").each(function () {
            if($(this).val()==$pdt_no.val()){
                exsit_num++;
            }
        });
        if(exsit_num>1){
            layer.alert("此料号已下单！", {icon: 2});
            $(this).val("");
            return false;
        }
        $pdt_no.validatebox();//验证初始化
        var curr = $("#cur_id option:selected").val();//币别
        var pdt_no = $(this).val();
        //       console.log($(this).parent().prev().find("span").html());
        var url = "<?= Url::to(['get-pdt'])?>";
        reset(this);
        $(this).val(pdt_no);
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"pdt_no": pdt_no},
            url: url,
            success: function (data) {
                if (data.part_no != null) {
                    //               console.log(data);
                    $pdt_no.next().val(data.prt_pkid);
                    $pdt_no.parent().parent().find("p.pdt_name").html(data.pdt_name);
                    $pdt_no.parent().parent().find("p.pdt_name").attr("title", data.pdt_name);
                    $pdt_no.parent().parent().find("input.sapl_quantity").data("quantity", data.pack_num);
                    $pdt_no.parent().parent().find("input.sapl_quantity").data("min_order", data.min_order);
                    $pdt_no.parent().parent().find("input.sapl_quantity").val((data.min_order + 0)).change();
//                    $pdt_no.parent().parent().find(".thePrice").html(data.min_order);
//                    $pdt_no.parent().find(".pdt_id").change();
                    var dis_opt = $pdt_no.parent().parent().find("select.distribution option");

                    if (data.transport == '-1') { //不承运
//                        $pdt_no.parent().parent().find("select.distribution").html(str);
                        for (var op in dis_opt) {
                            if (dis_opt[op].value == "<?= $no_take?>") {
                                dis_opt[op].disabled = true;
                            } else {
                                dis_opt[op].selected = true;
                            }
                        }
                    } else if (data.transport.length != 0) {  //运送方式
                        var str = '<option value="">请选择...</option>';
                        for (var i in data.transport) {
                            str += '<option value="' + data.transport[i]["id"] + '">' + data.transport[i]["name"] + '</option>';
                        }
                        $pdt_no.parent().parent().find("select.transport").html(str);
                        for (var op2 in dis_opt) {
                            if (dis_opt[op2].value == "<?= $no_take?>") {
                                dis_opt[op2].disabled = false;
                            }
                        }
                    }
                    if (data.self_take == 1) { //自提 自提仓库
                        if (data.wh.length != 0) {
                            var str2 = '<option value="">请选择...</option>';
                            for (var j in data.wh) {
                                str2 += '<option value="' + data.wh[j]["wh_id"] + '">' + data.wh[j]["wh_name"] + '</option>';
                            }
                            $pdt_no.parent().parent().find("select.whouse").html(str2);
                        }
                        for (var op3 in dis_opt) {
                            if (dis_opt[op3].value == "<?= $self_take?>") {
                                dis_opt[op3].disabled = false;
                            }
                        }
                    } else if (data.self_take != 1) {
                        for (var op4 in dis_opt) {
                            if (dis_opt[op4].value == "<?= $self_take?>") {
                                dis_opt[op4].disabled = true;
                            } else {
                                dis_opt[op4].selected = true;
                            }
                        }
                    }
                    $pdt_no.parent().parent().find("select.distribution").change();
                } else {
                    var $selectedRows = $("#product_table").find("input.pdt_id");
                    var selectedId = '';
                    if ($selectedRows.length > 0) {
                        $.each($selectedRows, function (i, n) {
                            if (n.value != '') {
                                selectedId += n.value + ',';
                            }
                        });
                        selectedId = selectedId.substr(0, selectedId.length - 1);
                    }
                    pdtRow = $pdt_no.parent().prev().prev().find("span").html();//行数
                    $.fancybox({
                        width: 720,
                        height: 500,
                        padding: [],
                        autoSize: false,
                        type: "iframe",
                        href: "<?=\yii\helpers\Url::to(['/ptdt/product-list/product-selector'])?>?filters=" + selectedId + "&kwd=" + pdt_no + "&curr=" + curr + "&uid=" + uid
                    });
                }
            },
            error: function (data) {
//                layer.alert("未找到该料号!", {icon: 2});
            }
        })
    });
    //下单数量变换  商品定价变化  转为整数倍包装数
    $(document).on("change", ".sapl_quantity", function () {
        var packNum = parseFloat($(this).data("quantity"));//包装数
//        console.log(packNum);
        if (isNaN(packNum)) {
            packNum = 1;
        }
        var min_order = parseFloat($(this).data("min_order"));//最小订单数
//        console.log(min_order);
        if (isNaN(min_order)) {
            min_order = 1;
        }
        var $quantity = $(this);
        var quantity = parseFloat($(this).val());
        if (isNaN(quantity)) {
            quantity = min_order;
        }
//        console.log(min_order);
        var curr = $("#cur_id option:selected").val();//币别
        var pdt_no = $quantity.parent().parent().find("input.pdt_no").val();//料号

        if (!isNaN(min_order) && quantity < min_order) {
            quantity = min_order;
        }
        quantity = parseInt(quantity * 100000);
        packNum = packNum * 100000;
        if (!isNaN(packNum)) {
            quantity = Math.ceil(quantity / packNum) * packNum;
        }
        quantity = quantity / 100000;
        $(this).val(quantity.toFixed(2));
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: "<?= Url::to(['get-price'])?>" + "?pdt_no=" + pdt_no + "&num=" + quantity + "&curr=" + curr,
            success: function (data) {
//                console.log(data);
                if (data != null) {
                    if (parseFloat(data) == '-1') {
                        $quantity.parent().parent().find("p.thePrice").html('面议');
                    } else {
                        $quantity.parent().parent().find("p.thePrice").html(parseFloat(data).toFixed(5));
                        $quantity.parent().parent().find("input.price").val(parseFloat(data).toFixed(5)).change();
                    }
                }
            },
            error: function (data) {
//                layer.alert("未找到该料号!", {icon: 2});
            }
        });
        $(this).parent().parent().find("select.transport").change();//数量变化重新获取运费
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
    //发票类型变化
    $(document).on("change", "#invoice_type", function () {
        var text = $(this).find("option:selected").text();
        if (text == "不需要发票") {
            if (invoiceChange) {
//            $(this).attr("disabled", true);
                $('#invoice_type option').filter(function () {
                    return $(this).text() == "增值税普通发票";
                }).attr("disabled", true);
                $('#invoice_type option').filter(function () {
                    return $(this).text() == "增值税专用发票";
                }).attr("disabled", true);
            }
            $(".red_fapiao").hide();
            $(".fapiao").validatebox({
                required: false
            });
        } else if (text == "增值税普通发票") {
            $(this).attr("disabled", false);
            if (invoiceChange) {
                $('#invoice_type option').filter(function () {
                    return $(this).text() == "增值税普通发票";
                }).attr("disabled", false);
                $('#invoice_type option').filter(function () {
                    return $(this).text() == "增值税专用发票";
                }).attr("disabled", true);
            }
            $(".red_fapiao").show();
            $(".fapiao").validatebox({
                required: true
            });
        } else {   //增值税专用发票
            $(this).attr("disabled", false);
            if (invoiceChange) {
                $('#invoice_type option').filter(function () {
                    return $(this).text() == "增值税普通发票";
                }).attr("disabled", false);
                $('#invoice_type option').filter(function () {
                    return $(this).text() == "增值税专用发票";
                }).attr("disabled", false);
            }
            $(".red_fapiao").show();
            $(".fapiao").validatebox({
                required: true
            });
        }
        invoiceChange = false;
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
    //点击加号 添加一个包装数量
    $(document).on("click", ".add_quantity", function () {
        var num = parseFloat($(this).prev().val());

        if (isNaN(num)) {
            return false;
        }
        var packNum = parseFloat($(this).prev().data("quantity"));

        if (isNaN(packNum)) {
            packNum = 10;
        }
        var quantity = num * 100000 + packNum * 100000;
        $(this).parent().find("input").val(quantity / 100000).change();

    });
    //点击加号 减少一个包装数量
    $(document).on("click", ".minus_quantity", function () {
        var num = parseFloat($(this).next().val());
        if (isNaN(num)) {
            return false;
        }
        var packNum = parseFloat($(this).next().data("quantity"));
        if (isNaN(packNum)) {
            packNum = 10;
        }
        var quantity = num * 100000 - packNum * 100000;
        $(this).next().val(quantity / 100000).change();

    });
    //收货地址变化 重新请求运费
    $(document).on("change", "#myAddress", function () {
        var trs = $("#product_table").children();
        var isSelected = false;  //是否选择运输方式   如果有选中时地址改变时提醒重新选择运输方式
        for (i = 0; i < trs.length; i++) {
            if ($(trs[i]).find("select.transport").val() != "") {
                isSelected = true;
            }
        }
        if (isSelected) {
            layer.alert("请重新选择商品运输方式！", {
                icon: 1,
                end: function () {
                    for (i = 0; i < trs.length; i++) {
                        $(trs[i]).find("select.transport option")[0].selected = true;
                        $(trs[i]).find("input.freight").val(0);
                    }
                }
            });
        }

    });
    //    function toFixed(num, s) {
    //        var times = Math.pow(10, s);
    //        var des = num * times + 0.5;
    //        des = parseInt(des, 10) / times;
    //        return des + '';
    //    }
    $("#payid").on("change", function () {
        $("#pay_left").html("");
        var xin = $(this).val();
        if (xin == prepayId) {   //预付款
            $(".paytype").show();
            $('.paytype option')[0].selected = "selected";
            $("#paytype").validatebox({
                required: true
            });
            $("#fenqi").hide().html("");
            $('.fenjiqi option')[0].selected = "selected";
            $(".fenjiqi").hide();
            $("#edu_pay").hide();
            $("#edu_pay").hide().html("");
            $(".need_pay").hide();
//            $(".paytype").hide();
        } else if (xin == creditId) {  //信用支付
            $(".need_pay").show().appendTo("#pat1");
            $(".paytype").hide();
            $('.paytype option')[0].selected = "selected";
            $("#fenqi").hide().html("");
            $(".fenjiqi").hide();
            $('.fenjiqi option')[0].selected = "selected";
            if ($(".edu_pay_del").length == 1) {
                $(".edu_pay_del").hide();
            }
            $("#paytype").validatebox({
                required: false
            });
            $("#pay_left").css("margin-left", '784px');
            $("#edu_pay").html(creditHtml).show();
            $(".Onlynum2").numbervalid(2);
            $(".stag_cost").numbervalid(2, 18);
            $("#total").change();
        } else {
            $("#edu_pay").html("").hide();
            $(".need_pay").hide();
            $("#fenqi").hide().html("");
            $(".fenjiqi").hide();
            $('.fenjiqi option')[0].selected = "selected";
            $(".paytype").hide();
            $('.paytype option')[0].selected = "selected";
            $("#paytype").validatebox({
                required: false
            });
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
            $("#pay_left").css("margin-left", '415px');
            $(".need_pay").show().appendTo("#pat2");
            for (i = 0; i < 2; i++) {
                $("#fenqi").append('<div class="inline-block mb-10">' +
                    '<label class="label-width ">第' + (i + 1) + '期付款时间</label><label>：</label>' +
                    '<input type="text" class="value-width easyui-validatebox select-date" id="select_time_' + (i + 1) + '" name="ReqPay[' + i + '][stag_date]" onclick="select_time(' + (i + 1) + ',' + xin + ')" style="margin-left: 3px" data-options="required:\'true\'" name="" value="">' +
                    '<input type="text" class="hiden" name="ReqPay[' + i + '][stag_times]" value="' + (i + 1) + '">' +
                    '<label class="label-width " style="margin-left: 4px">第' + (i + 1) + '期支付金额</label><label>：</label>' +
                    '<input type="text" class="value-width easyui-validatebox stag_cost" name="ReqPay[' + i + '][stag_cost]"  maxlength="21" data-options="required:\'true\',validType:\'decimal[18,2]\'" style="margin-left: 3px"></div>')
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
        var obj = $("#edu_pay").append(
            '<div class="mb-10"><label class="label-width "><span class="red">*</span>信用额度类型</label><label class="">：</label>' +
            '<select type="text" class="require value-width easyui-validatebox creditType" style="margin-left: 3px" data-options="required:\'true\'" name="ReqPay[' + credit_len + '][stag_type]">' +
            opts +
            '</select> ' +
            '<label class="label-width  patid">付款金额</label><label class="">：</label> ' +
            '<input type="text" class="value-width easyui-validatebox stag_cost stagCost" data-options="required:\'true\',validType:\'eduCompare\'"  name="ReqPay[' + credit_len + '][stag_cost]">' +
            '<a class="icon-minus edu_pay_del" style="margin-left: 10px">删除</a>' +
            '<a class="icon-plus edu_pay_add last_one" style="margin-left: 8px;' + ((most_one) ? "display:none" : "") + '" onclick="">添加</a></div>'
        );
        $.parser.parse(obj);
        $(".Onlynum2").numbervalid(2);
        $(".stag_cost").numbervalid(2, 18);
        optionOnce();
    });
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
        $(".stag_cost").change();
    });
    $(document).on("click", ".creditType", function () {
        $(this).parent().find("input.stagCost").change();
        optionOnce();
    });
    $(document).on("change", ".stag_cost", function () {
        var total = $("#total").val();
        var pay = 0.00;
        for (var i = 0; i < $(".stag_cost").length; i++) {
            if ($(".stag_cost")[i].value != "" && !isNaN($(".stag_cost")[i].value)) {
                pay = pay + parseFloat($(".stag_cost")[i].value);
            }
        }
        if (total != pay) {
            $("#pay_left").html((total - pay).toFixed(2));
        } else {
            $("#pay_left").html("");
        }
    });
    $(document).on("change", "#total", function () {
        var total = $(this).val();
        var totalEdu = parseFloat($(".allCredit").html());
//        console.log(total);
        if (isNaN(totalEdu)) {
            totalEdu = 0;
        }
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
    //分期下拉选项
    $("#fenjiqi").on("change", function () {
        var xin = parseInt($(this).val());
        $("#fenqi").show().html("");
        $("#pay_left").html("");
        for (i = 0; i < xin; i++) {
            $("#fenqi").append('<div class="inline-block mb-10">' +
                '<label class="label-width ">第' + (i + 1) + '期付款时间</label><label>：</label>' +
                '<input type="text" class="value-width easyui-validatebox select-date" id="select_time_' + (i + 1) + '" name="ReqPay[' + i + '][stag_date]" onclick="select_time(' + (i + 1) + ',' + xin + ')" style="margin-left: 3px" data-options="required:\'true\'" name="" value=""> ' +
                '<input type="text" class="hiden" name="ReqPay[' + i + '][stag_times]" value="' + (i + 1) + '">' +
                '<label class="label-width " >第' + (i + 1) + '期支付金额</label><label>：</label>' +
                '<input type="text" class="value-width easyui-validatebox stag_cost" name="ReqPay[' + i + '][stag_cost]" maxlength="21"  data-options="required:\'true\',validType:\'decimal[18,2]\'" style="margin-left: 4px"> </div>'
            );
        }
        $(".Onlynum2").numbervalid(2);
        $(".stag_cost").numbervalid(2, 18);
        $('input[type!="hidden"],select,textarea', $("#fenqi")).each(function () {
            $(this).validatebox();//验证初始化
        });
    });
    function more() {
        $(".moreInfo").show();
        $("#less").show();
        $("#more").hide();
    }
    function less() {
        $(".moreInfo").hide();
        $("#more").show();
        $("#less").hide();
    }
    function add_product() {
        lastTr++;
        var b = lastTr;
        var url = "<?= Url::to(['pdt-validate']) ?>";
        var obj = $("#product_table").append(
            '<tr>' +
            '<td class="hiden"><span data-id="' + b + '"></span></td>' +
            '<td><span class="width-40">' + b + '</span></td>' +
            '<td class="width-40">' + '<span><input type="checkbox"></span>' + '</td>' +
            '<td><input class="height-30 width-150 easyui-validatebox text-center  pdt_no" data-options="required:\'true\',validType:\'exist\',delay:10000"' +
            'data-act="' + url + '" data-scenario="exist"' +
            'data-attr="part_no" data-id="" type="text"  maxlength="20" ><input class="hiden pdt_id" name="orderL[' + b + '][prt_pkid]"/></td>' +
            '<td><p class="text-center text-no-next width-150 pdt_name"></p></td>' +
            '<td><a class="icon-minus minus_quantity" onclick="" style="position: relative;display:inline;left: 4px"></a><input class="height-30 width-150 easyui-validatebox text-center sapl_quantity" style="margin-left: -10px;margin-right: -10px" type="text" data-options="required:\'true\',validType:\'decimal[7,2]\'"  maxlength="12" name="orderL[' + b + '][sapl_quantity]" ><a class="icon-plus add_quantity" onclick="" style="position: relative;display:inline;right: 4px"></a></td>' +
            '<td><p class="text-center width-150 thePrice"></p></td>' +
            '<td><input class="height-30 width-150  text-center easyui-validatebox price" type="text" data-options="required:\'true\',validType:\'decimal[7,5]\'"  maxlength="20" name="orderL[' + b + '][uprice_tax_o]" ></td>' +
            '<td><select class="height-30 width-150 easyui-validatebox text-center distribution" type="text" data-options="required:\'true\'" name="orderL[' + b + '][distribution]">' +
            <?php foreach ($downList["dispatching"] as $key => $val) { ?>
            '<option value="' + "<?= $val["tran_id"] ?>" + '">' + "<?= $val["tran_sname"] ?>" + '</option>' +
            <?php } ?>
            '</select></td>' +
            '<td><select class="height-30 width-150  text-center transport" type="text" name="orderL[' + b + '][transport]">' +
            '<option value="">请选择...</option>' +
            '</select></td>' +
            '<td><select class="height-30 width-150  text-center whouse" type="text" name="orderL[' + b + '][whs_id]">' +
            '<option value="">请选择...</option>' +
            '</select></td>' +
            '<td><input class="height-30 width-150  text-center freight read-only" readonly="readonly" type="text"  name="orderL[' + b + '][tax_freight]" ></td>' +
            '<td><input class="height-30 width-150 select-date-time easyui-validatebox " readonly="readonly" id="' + 'start_' + b + '" data-options="required:\'true\'" name="orderL[' + b + '][request_date]" onclick="WdatePicker({skin: \'whyGreen\', dateFmt: \'yyyy-MM-dd\',minDate:\'%y-%M-%d %H:%m\',maxDate: \'#F{$dp.$D(\\\'end_' + b + '\\\');}\' })" placeholder="请选择"></td>' +
            '<td><input class="height-30 width-150 select-date-time easyui-validatebox " readonly="readonly" id="' + 'end_' + b + '"  name="orderL[' + b + '][consignment_date]" onclick="WdatePicker({skin: \'whyGreen\', dateFmt: \'yyyy-MM-dd\',minDate: \'#F{$dp.$D(\\\'start_' + b + '\\\');}\' })" placeholder="请选择"></td>' +
            '<td><input class="height-30 width-150  text-center easyui-validatebox cess read-only" type="text" data-options="required:\'true\'"  maxlength="20" name="orderL[' + b + '][cess]"  readonly="readonly" value="17.00" ></td>' +
            '<td><input class="height-30 width-150  text-center discount read-only" type="text"  maxlength="20" name="orderL[' + b + '][discount]" readonly="readonly" ></td>' +
            '<td><input class="height-30 width-150  text-center read-only price_hs" type="text"  maxlength="20" name="orderL[' + b + '][tprice_tax_o]"  readonly="readonly" ></td>' +
            '<td><p class="text-center width-150 price_off"></p></td>' +
            '<td><input class="height-30 width-150 easyui-validatebox text-center" type="text" data-options="validType:\'maxLength[50]\'"   maxlength="50" name="orderL[' + b + '][sapl_remark]" ></td>' +
            '<td><a class="width-150" onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'product_table'" + ')">删除</a></td>' +
            '</tr>'
        );
        $(".Onlynum2").numbervalid(2);
        $(".stag_cost").numbervalid(2, 18);
        resetNum();
//        $("#add-form").form('validate');
        $('input[type!="hidden"],select,textarea', $("#product_table")).each(function () {
            $(this).validatebox();//验证初始化
        });
        $('input[type!="hidden"]', $("#product_table")).on("change", function () {
            totalPrices();
        });
    }
    function contains(arr, obj) {
        var i = arr.length;
        while (i--) {
            if (arr[i] === obj) {
                return true;
            }
        }
        return false;
    }
    //input file
    function change(obj) {
        var length = obj.files.length;
        var span = obj.parentNode.previousSibling.previousSibling;
        var temp = "";
        for (var i = 0; i < length; i++) {
            if (i == 0) {
                temp = obj.files[i].name;
            } else {
                temp = temp + "&nbsp,&nbsp" + obj.files[i].name;
            }
            span.innerHTML = temp;
        }
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
//                console.log($(trs[i]).find("input.price_hs"));
                if (thePrice != "面议") {
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
    //全选
    $(".table").on('click', "th input[type='checkbox']", function () {
//        console.log(000);
//        $("input[name='checkbox']").attr("checked","true");
        if ($(this).is(":checked")) {
            $('.table').find("td input[type='checkbox']").prop("checked", true);
        } else {
            $('.table').find("td input[type='checkbox']").prop("checked", false);
        }
    });
    $("#delete_product").on('click', function () {
//        console.log(000);
        $('#product_table input:checkbox:checked').each(function () {
            vacc_del(this, 'product_table');
        });
    });
    //选择完公司获取收货地址
    $("#cust_id").on('change', function () {
        var id = $(this).val();
        if (id != "") {
            $("#cust_sname").show();
            $("#bu_wei").hide();
        } else {
            return false;
        }
        $.ajax({
            type: "get",
            dataType: "json",
            async: false,
            data: {
                "custId": id,
                "type": 12
            },
            url: "<?= Url::to(["select-address"])?>",
            success: function (data) {
                $("#selectAddrr").find(".address-select").remove();
                var ba_id = "<?= $quotedHModel['delivery_addr'] ?>";
                var isborder = false;
                for (var x in data) {
                    if (ba_id != "" && data[x].ba_id == ba_id) {
                        isborder = true;
                    } else if (ba_id == "" && data[x].ba_status == 11) {
                        isborder = true;
                    } else {
                        isborder = false;
                    }
                    $("#selectAddrr").append(
                        '<div class="mb-10 address-select" ' + ((isborder) ? 'style="border: 1px solid #1f7ed0;"' : '') + '>' +
                        '<input type="text" class="radioGroup hiden" value="' + data[x].ba_id + '">' +
                        '<span class="mb-10">收 货  人</span><span>：</span><a class="icon-remove float-right deleteAddress"></a>' +
                        '<span class="inline-block">' + data[x].contact_name + '</span><br/>' +
                        '<span class="mb-10">联系电话</span><span>：</span>' +
                        '<span class="inline-block">' + data[x].contact_tel + '</span>' +
                        '<div>' +
                        '<span class="vertical-top wd-60">收货地址：</span>' +
                        '<p class="text-no-next wd-265" title="' + data[x].ba_address + '">' + data[x].ba_address + '</p>' +
                        '</div>' +
                        '<a class="float-right mt-5 editAddress">修改</a><a class="float-right mt-5 mr-10 ' + ((data[x].ba_status == 11) ? 'grey' : 'defaultAddress') + '">设为默认地址</a>' +
                        '</div>'
                    );
                    if (data[x].ba_status == 11) {
                        $("#myAddress").val(data[x].ba_id);
                    }
                }
                if (data != null && data.length >= 8) {
                    $("#editAdrress").parent().next().show();
                    $("#editAdrress").parent().hide();
                } else {
                    $("#editAdrress").parent().next().hide();
                    $("#editAdrress").parent().show();
                }
                //最后添加新增块
//                if (data.length<8){
//                    $("#selectAddrr").append(
//                        '<div class="mb-10 address-add">' +
//                        '<span class="mb-10" style="visibility: hidden">收 货  人</span></span>' +
//                        '<span class="inline-block" style="visibility: hidden">收 货  收 货</span><br/>' +
//                        '<span class="mb-10" style="visibility: hidden">收 货  人</span>' +
//                        '<span class="inline-block" style="visibility: hidden">收 货  人  人收 货  人</span>' +
//                        '<div style="visibility: hidden">' +
//                        '<span class="vertical-top wd-60" style="visibility: hidden">收货地址：</span>' +
//                        '<p class="text-no-next wd-265" style="visibility: hidden">收 货  人收 货  人收 货  人收 货  人收 货  人收 货  人</p>' +
//                        '</div>' +
//                        '<a class="float-right mt-5 editAddress" style="visibility: hidden">修改</a>' +
//                        '</div>'
//                    );
//                }
            }

        });
        var currency = $("#cur_id").val();
        var curNmae = $("#cur_id option:selected").text();
        var payid = $('#payid').val();
        $(".table_credit_curr").html(curNmae);
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
                            '<input type="text" class="value-width easyui-validatebox stag_cost stagCost validatebox-text" maxlength="21" style="width:150px;" data-options="validType:[\'eduCompare\',\'decimal[18,2]\']" name="ReqPay[' + x + '][stag_cost]">' +
                            '<input type="hidden" name="ReqPay[' + x + '][stag_type]" value="' + data.rows[x].credit_type + '">' +
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
    //新增收货地址
    $("#editAdrress").click(function () {
        var id = $("#cust_id").val();
        if (id) {
            $.fancybox({
                href: "<?=Url::to(['add-address'])?>" + "?custId=" + id + "&type=12",
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 700,
                height: 420
            });
        } else {
            layer.alert("请选择客户!", {icon: 2});
        }
    });
    $(document).on("change", "#cur_id", function () {
        var currency = $("#cur_id").val();
        var cust_id = $("#cust_id").val();
        var curNmae = $("#cur_id option:selected").text();
        $(".table_credit_curr").html(curNmae);
        var payid = $('#payid').val();
        //获取客户已有帐信额度
        $.ajax({
            type: "get",
            dataType: "json",
            url: "<?= Url::to(["get-cust-credit"])?>" + '?id=' + cust_id + '&currency=' + currency,
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
                            '<span style="width:150px;">' + data.rows[x].approval_limit + '</span>' +
                            '<label style="width:80px;">剩余额度</label><label>：</label> ' +
                            '<span style="width:150px;" class="surplus_limit">' + data.rows[x].surplus_limit + '</span>' +
                            '<label style="width:80px;">支付额度</label><label>：</label> ' +
                            '<input type="text" class="value-width easyui-validatebox stag_cost stagCost validatebox-text"  maxlength="21" style="width:150px;" data-options="validType:[\'eduCompare\',\'decimal[18,2]\']" name="ReqPay[' + x + '][stag_cost]">' +
                            '<input type="hidden" name="ReqPay[' + x + '][stag_type]" value="' + data.rows[x].credit_type + '">' +
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
            }
        })
    });
    //选择收货地址
    $(document).on("click", ".address-select", function () {
        $(".address-select").css("border-color", "#cccccc");
        $(this).css("border-color", "#1f7ed0");
//        $(".radioGroup").removeAttr("checked");
        $("#myAddress").val($(this).find(".radioGroup").val()).change();
    });
    //设为默认收货地址
    $(document).on("click", ".defaultAddress", function () {
        var defaultAddress = $(this);
        var id = $(this).parent().find(".radioGroup").val();
        $.ajax({
            type: "get",
            dataType: "json",
            async: false,
            url: "<?=Url::to(['default-address'])?>" + "?id=" + id,
            success: function (data) {
                if (data.status == 1) {
                    layer.alert(data.msg, {
                        icon: 1,
                        end: function () {
                            $(".grey").addClass("defaultAddress");
                            $(".grey").removeClass("grey");
                            defaultAddress.removeClass("defaultAddress");
                            defaultAddress.addClass("grey");
                        }
                    });
                }
                if (data.status == 0) {
                    if ((typeof data.msg) == 'object') {
                        layer.alert(JSON.stringify(data.msg), {icon: 2});
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }

                }
            }

        });
    });
    //修改收货地址
    $(document).on("click", ".editAddress", function () {
        var id = $(this).parent().find(".radioGroup").val();
        $(this).parent().addClass("edit_address");
        $.fancybox({
            href: "<?=Url::to(['edit-address'])?>" + "?id=" + id,
            type: "iframe",
            padding: 0,
            autoSize: false,
            width: 700,
            height: 420
        });
        return false;
    });
    //删除收货地址
    $(document).on("click", ".deleteAddress", function () {
        var deleteAddress = $(this);
        var id = $(this).parent().find("input").val();
        $.ajax({
            type: "get",
            dataType: "json",
            async: false,
            url: "<?=Url::to(['del-address'])?>" + "?id=" + id,
            success: function (data) {
                if (data.status == 1) {
                    layer.alert(data.msg, {
                        icon: 1,
                        end: function () {
                            deleteAddress.parent().remove();
                            $("#editAdrress").parent().next().hide();
                            $("#editAdrress").parent().show();
                        }
                    });
                }
                if (data.status == 0) {
                    if ((typeof data.msg) == 'object') {
                        layer.alert(JSON.stringify(data.msg), {icon: 2});
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }

                }
            }
        });
        return false;
    });

    /*地址联动*/
    $('.disName,.disName1,.disName2,.disName3').on("change", function () {
        var $select = $(this);
        var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
        getNextDistrict($select, $url, "select");
    });
    //select-customer 调用获取 发票抬头地址 发票寄送地址
    function changeAddress(id, type) {
        var url = "<?= Url::to(["get-all-district"])?>";
        $.ajax({
            type: "get",
            dataType: "json",
            async: false,
            data: {"id": id},
            url: url,
            success: function (data) {
                if (type == 1) {
                    $("#title_address_country").html('<option value>请选择...</option>');
                    $("#title_address_province").html('<option value>请选择...</option>');
                    $("#title_address_city").html('<option value>请选择...</option>');
                    $("#title_address_town").html('<option value>请选择...</option>');
                    for (var i in data.oneLevel) {
                        $("#title_address_country").append('<option value="' + data.oneLevel[i].district_id + '" ' + ((data.oneLevel[i].district_id == data.oneLevelId) ? 'selected' : "") + '>' + data.oneLevel[i].district_name + '</option>'
                        );
                    }
                    for (var i in data.twoLevel) {
                        $("#title_address_province").append('<option value="' + data.twoLevel[i].district_id + '" ' + ((data.twoLevel[i].district_id == data.twoLevelId) ? 'selected' : "") + '>' + data.twoLevel[i].district_name + '</option>'
                        );
                    }
                    for (var i in data.threeLevel) {
                        $("#title_address_city").append('<option value="' + data.threeLevel[i].district_id + '" ' + ((data.threeLevel[i].district_id == data.threeLevelId) ? 'selected' : "") + '>' + data.threeLevel[i].district_name + '</option>'
                        );
                    }
                    for (var i in data.fourLevel) {
                        $("#title_address_town").append('<option value="' + data.fourLevel[i].district_id + '" ' + ((data.fourLevel[i].district_id == data.fourLevelId) ? 'selected' : "") + '>' + data.fourLevel[i].district_name + '</option>'
                        );
                    }
                } else {
                    $("#send_address_country").html('<option value>请选择...</option>');
                    $("#send_address_province").html('<option value>请选择...</option>');
                    $("#send_address_city").html('<option value>请选择...</option>');
                    $("#send_address_town").html('<option value>请选择...</option>');
                    for (var i in data.oneLevel) {
                        $("#send_address_country").append('<option value="' + data.oneLevel[i].district_id + '" ' + ((data.oneLevel[i].district_id == data.oneLevelId) ? 'selected' : "") + '>' + data.oneLevel[i].district_name + '</option>'
                        );
                    }
                    for (var i in data.twoLevel) {
                        $("#send_address_province").append('<option value="' + data.twoLevel[i].district_id + '" ' + ((data.twoLevel[i].district_id == data.twoLevelId) ? 'selected' : "") + '>' + data.twoLevel[i].district_name + '</option>'
                        );
                    }
                    for (var i in data.threeLevel) {
                        $("#send_address_city").append('<option value="' + data.threeLevel[i].district_id + '" ' + ((data.threeLevel[i].district_id == data.threeLevelId) ? 'selected' : "") + '>' + data.threeLevel[i].district_name + '</option>'
                        );
                    }
                    for (var i in data.fourLevel) {
                        $("#send_address_town").append('<option value="' + data.fourLevel[i].district_id + '" ' + ((data.fourLevel[i].district_id == data.fourLevelId) ? 'selected' : "") + '>' + data.fourLevel[i].district_name + '</option>'
                        );
                    }
                }
            }

        })
    }
    //选择商品后的回调
    function productSelectorCallback(rows) {
        var first_tr = $("#product_table tr:first-child").find("input.pdt_no").val();
        if (first_tr == "" && $("#product_table tr").length == 1) {
            $("#product_table tr:first-child").remove();
        }
        var obj = "";
        var a = $("#product_table tr:last-child").find("td.hiden").children().data("id");
        if (isNaN(a)) {
            a = 0;
        }
        var url = "<?= Url::to(['pdt-validate']) ?>";
        for (var x in rows) {
            lastTr++;
            var b = lastTr;
            var str, str2, str3 = "";
            if (rows[x].transport == '-1') { //不承运
                str = '<option value="">不承运！</option>';
            } else if (rows[x].transport.length != 0) {  //运送方式
                str = '<option value="">请选择...</option>';
                for (var i in rows[x].transport) {
                    str += '<option value="' + rows[x].transport[i]["id"] + '">' + rows[x].transport[i]["name"] + '</option>';
                }
            }
            if (rows[x].self_take == 1 && rows[x].wh.length != 0) { //自提 自提仓库
                str2 = '<option value="">请选择...</option>';
                for (var j in rows[x].wh) {
                    str2 += '<option value="' + rows[x].wh[j]["wh_id"] + '">' + rows[x].wh[j]["wh_name"] + '</option>';
                }
            } else if (rows[x].self_take != 1) {
                str2 = '<option value="">不提供自提服务</option>';
            }
            if (rows[x].transport == '-1' && rows[x].self_take != 1) { //不承运 不自提
                str3 = "此料号不能下单！"
            }
            obj =
                '<tr>' +
                '<td class="hiden"><span data-id="' + b + '"></span></td>' +
                '<td><span class="width-40">' + b + '</span></td>' +
                '<td class="width-40">' + '<span><input type="checkbox"></span>' + '</td>' +
                '<td><input class="height-30 width-150 easyui-validatebox text-center  pdt_no" data-options="required:\'true\',validType:\'exist\',delay:10000"' +
                'data-act="' + url + '" data-scenario="exist"' +
                'data-attr="part_no" data-id="" type="text"  maxlength="20" value="' + rows[x].part_no + '" ><input class="hiden pdt_id" name="orderL[' + b + '][prt_pkid]" value="' + rows[x].prt_pkid + '"/></td>' +
                '<td><p class="text-center text-no-next width-150 pdt_name" title="' + rows[x].pdt_name + '">' + rows[x].pdt_name + '</p></td>' +
                '<td><a class="icon-minus minus_quantity" style="position: relative;display:inline;left: 4px"></a><input class="height-30 width-150 easyui-validatebox text-center sapl_quantity ' + ('sapl_quantity_' + b) + '" style="margin-left: -10px;margin-right: -10px"' +
                'data-quantity="' + rows[x].pack_num + '" data-min_order="' + rows[x].min_order + '" type="text" data-options="required:\'true\',validType:\'decimal[7,2]\'"  maxlength="12" name="orderL[' + b + '][sapl_quantity]" value="' + parseFloat(rows[x].min_order).toFixed(2) + '" ><a class="icon-plus add_quantity" onclick="" style="position: relative;display:inline;right: 4px"></a></td>' +
                '<td><p class="text-center width-150 thePrice">' + (parseFloat(rows[x].price[0].price) != "-1" ? parseFloat(rows[x].price[0].price).toFixed(5) : "面议") + '</p></td>' +
                '<td><input class="height-30 width-150 easyui-validatebox text-center price" type="text" data-options="required:\'true\',validType:[\'noZero\',\'decimal[7,5]\']"  maxlength="20" name="orderL[' + b + '][uprice_tax_o]" value="' + (parseFloat(rows[x].price[0].price) != "-1" ? parseFloat(rows[x].price[0].price).toFixed(5) : "") + '"></td>' +
                '<td><select class="height-30 width-150 easyui-validatebox text-center distribution" ' + (str3 == "" ? "" : "") + ' type="text" data-options="required:\'true\'" name="orderL[' + b + '][distribution]">' +
                (str3 == "" ? "" : '<option value="">' + str3 + '</option>') +
                <?php foreach ($downList["dispatching"] as $key => $val) { ?>
                '<option value="' + "<?= $val["tran_id"] ?>" + '" ' + ((rows[x].transport == -1 && "<?= $val["tran_id"] == $no_take ?>") ? "disabled" : "") + ((rows[x].self_take != 1 && "<?= $val["tran_id"] == $self_take ?>") ? "disabled" : "") + ' >' + "<?= $val["tran_sname"] ?>" + '</option>' +
                <?php } ?>
                '</select></td>' +
                '<td><select class="height-30 width-150  text-center transport ' + ((rows[x].transport == -1 || rows[x].self_take == 1) ? "hiden" : "") + '" type="text" data-options="required:\'true\'" name="orderL[' + b + '][transport]">' + str +
                '</select></td>' +
                '<td><select class="height-30 width-150  text-center whouse ' + ((rows[x].self_take != 1) ? "hiden" : "") + '"   type="text" name="orderL[' + b + '][whs_id]">' + str2 +
                '</select></td>' +
                '<td><input class="height-30 width-150  text-center freight read-only" readonly="readonly" type="text"  name="orderL[' + b + '][tax_freight]" value="0" ></td>' +
                '<td><input class="height-30 width-150 select-date-time easyui-validatebox " readonly="readonly" id="' + 'start2_' + b + '"data-options="required:\'true\'" name="orderL[' + b + '][request_date]" onclick="WdatePicker({skin: \'whyGreen\', dateFmt: \'yyyy-MM-dd\',minDate:\'%y-%M-%d %H:%m\',maxDate: \'#F{$dp.$D(\\\'end2_' + b + '\\\');}\' })" placeholder="请选择"></td>' +
                '<td><input class="height-30 width-150 select-date-time easyui-validatebox " readonly="readonly" id="' + 'end2_' + b + '"  name="orderL[' + b + '][consignment_date]" onclick="WdatePicker({skin: \'whyGreen\', dateFmt: \'yyyy-MM-dd\',minDate: \'#F{$dp.$D(\\\'start2_' + b + '\\\');}\' })" placeholder="请选择"></td>' +
                '<td><input class="height-30 width-150  text-center cess read-only" type="text"  maxlength="20" name="orderL[' + b + '][cess]"  readonly="readonly" value="17.00" ></td>' +
                '<td><input class="height-30 width-150  text-center discount read-only" type="text"  maxlength="20" name="orderL[' + b + '][discount]" value="100.00" readonly="readonly"></td>' +
                '<td><input class="height-30 width-150 easyui-validatebox text-center price_hs read-only" type="text" data-options="required:\'true\'"  maxlength="20" name="orderL[' + b + '][tprice_tax_o]" value="' + (parseFloat(rows[x].price[0].price) != "-1" ? (rows[x].price[0].price * rows[x].min_order).toFixed(2) : "0.00") + '"   readonly="readonly" ></td>' +
                '<td><p class="text-center width-150 price_off">' + (parseFloat(rows[x].price[0].price) != "-1" ? (rows[x].price[0].price * rows[x].min_order).toFixed(2) : "") + '</p></td>' +
                '<td><input class="height-30 width-150 easyui-validatebox text-center" type="text" data-options="validType:\'maxLength[50]\'"   maxlength="50" name="orderL[' + b + '][sapl_remark]" ></td>' +
                '<td><a class="width-150" onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'product_table'" + ')">删除</a></td>' +
                '</tr>'
            ;
            if (pdtRow != 0) {
                $(obj).insertAfter($("#product_table tr")[(pdtRow - 1)]);
            } else {
                $(obj).appendTo($("#product_table"));
            }
            var select = '.sapl_quantity_' + b;
            $(select).data("quantity", rows[x].pack_num);
            $(".distribution").change();
            $.parser.parse($("#product_table"));
        }
        if (pdtRow != 0) {
            $($("#product_table tr")[(pdtRow - 1)]).remove();
        }
        $(".Onlynum2").numbervalid(2);
        pdtRow = 0;
        resetNum();
        totalPrices();
    }
    //选择商品 重置行
    function reset(obj) {
        var td = $(obj).parents("tr").find("td");
        $(obj).parents("tr").find("input").val("");
        $(obj).parents("tr").find("input.cess").val("17.00");
        $(obj).parents("tr").find("p").html("");
        $(obj).parents("tr").find("select.distribution option")[0].selected = true;
        $(obj).parents("tr").find("select.transport option")[0].selected = true;
        $(obj).parents("tr").find("select.whouse option")[0].selected = true;
        totalPrices();
    }

    //选择商品 删除行
    function vacc_del(obj, id) {
        $(obj).parents("tr").remove();
        resetNum();
        totalPrices();
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
    }
</script>