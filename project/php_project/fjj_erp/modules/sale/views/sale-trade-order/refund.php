<?php
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = '新增退款单';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '退款单查询'];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    /*.fancybox-skin{*/
    /*padding:0px !important;*/
    /*}*/
</style>
<div class="content">
    <h1 class="head-first">新增退款单</h1>
    <?php  $form = ActiveForm::begin([
        'id'=>'add-form'
    ]); ?>
    <h2 class="head-second text-left">
        退款基本信息
    </h2>
    <div class="mt-10">
        <div class="inline-block">
            <label class="width-100 ml-20">关联单号</label>
            <input type="text" id="cust_contacts" class="width-200 easyui-validatebox"
                   value="<?= $customer['cust_contacts'] ?>">
            <label class="width-100 ml-140">交易法人</label>
            <input type="text" id="cust_tel2" class="width-200 easyui-validatebox" readonly="readonly"
                   value="<?= $customer['cust_tel2'] ?>">
        </div>
    </div>
    <div class="mt-10">
        <div class="inline-block">
            <label class="width-100 ml-20">订单类型</label>
            <input type="text" id="cust_contacts" class="width-200 easyui-validatebox"
                   value="<?= $customer['cust_contacts'] ?>">
            <label class="width-100 ml-140">购买日期</label>
            <input type="text" id="cust_tel2" class="width-200 easyui-validatebox" readonly="readonly"
                   value="<?= $customer['cust_tel2'] ?>">
        </div>
    </div>
    <div class="mt-10">
        <label class="width-100 ml-20" for="pdsupplier-supplier_trade_condition">订单状态</label>
        <select id="pdsupplier-supplier_trade_condition" class="inline-block width-200 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_trade_condition]">
            <option value="">请选择...</option>
            <?php foreach ($downList['devcon'] as $val){?>
                <option value="<?= $val['dec_id'] ?>" <?= $supplier['trace_condition']==$val['dec_id']?"selected":null ?>><?= $val['dec_sname'] ?></option>
            <?php }?>
        </select>
        <label class="width-100 ml-140" for="pdsupplier-supplier_pay_condition">是否已开票</label>
        <select id="pdsupplier-supplier_pay_condition" class="inline-block width-200 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_pay_condition]">
            <option value="">请选择...</option>
            <?php foreach ($downList['payment'] as $val){?>
                <option value="<?= $val['pac_id']?>" <?= $supplier['pay_condition']==$val['pac_id']?"selected":null ?>><?= $val['pac_sname'] ?></option>
            <?php }?>
        </select>
    </div>
    <div class="mt-10">
        <div class="inline-block">
            <label class="width-100 ml-20">客户全称</label>
            <input type="text" id="cust_contacts" class="width-200 easyui-validatebox"
                   value="<?= $customer['cust_contacts'] ?>">
            <label class="width-100 ml-140">客户代码</label>
            <input type="text" id="cust_tel2" class="width-200 easyui-validatebox" readonly="readonly"
                   value="<?= $customer['cust_tel2'] ?>">
        </div>
    </div>
    <div class="mt-10">
        <div class="inline-block">
            <label class="width-100 ml-20">公司电话</label>
            <input type="text" id="cust_contacts" class="width-200 easyui-validatebox"
                   value="<?= $customer['cust_contacts'] ?>">
            <label class="width-100 ml-140">交易币别</label>
            <input type="text" id="cust_tel2" class="width-200 easyui-validatebox" readonly="readonly"
                   value="<?= $customer['cust_tel2'] ?>">
        </div>
    </div>
    <div class="mt-10">
        <div class="inline-block">
            <label class="width-100 ml-20">收货人</label>
            <input type="text" id="cust_contacts" class="width-200 easyui-validatebox"
                   value="<?= $customer['cust_contacts'] ?>">
            <label class="width-100 ml-140">联系方式</label>
            <input type="text" id="cust_tel2" class="width-200 easyui-validatebox" readonly="readonly"
                   value="<?= $customer['cust_tel2'] ?>">
        </div>
    </div>
    <div class="mt-10">
        <label class="width-100 ml-20">收货地址</label>
        <input class="width-650" type="text" readonly="readonly" id="invoice_title_district"
               name="SaleCustrequireH[title_addr]" value="<?= $quotedHModel['title_addr'] ?>"
               maxlength="100">
    </div>
    <div class="mt-10">
        <div class="inline-block">
            <label class="width-100 ml-20">负责人</label>
            <input type="text" id="cust_contacts" class="width-200 easyui-validatebox" readonly="readonly"
                   value="<?= $customer['cust_contacts'] ?>">
            <label class="width-100 ml-140">联系电话</label>
            <input type="text" id="cust_tel2" class="width-200 easyui-validatebox" readonly="readonly"
                   value="<?= $customer['cust_tel2'] ?>">
        </div>
    </div>
    <h2 class="head-second text-left mt-20">
        商品信息
    </h2>
    <div class="
    <div class="mb-20 tablescroll" style="overflow-x: scroll">
        <table class="table">
            <thead>
            <tr>
                <th><p class="width-40 color-w">序号</p></th>
                <th><p class="width-150 color-w">料号</p></th>
                <th><p class="width-150 color-w">品名</p></th>
                <th><p class="width-150 color-w">规格/型号</p></th>
                <th><p class="width-150 color-w">单位</p></th>
                <th><p class="width-150 color-w">订单数量</p></th>
                <th><p class="width-150 color-w">退款类型</p></th>
                <th><p class="width-150 color-w">退换货数量</p></th>
                <th><p class="width-150 color-w">退款金额</p></th>
                <th><p class="width-150 color-w">退换货原因</p></th>
                <th><p class="width-150 color-w">其他细节</p></th>
                <?php foreach ($columns as $key => $val) { ?>
                    <th><p class="text-center width-150 color-w"><?= $val["field_title"] ?></p></th>
                <?php } ?>
            </tr>
            </thead>
            <tbody id="product_table">
                <tr>
                    <td class="hiden"><span data-id="0"></span></td>
                    <td><span class="width-40">1</span></td>
                    <td><span class="width-40"><input type="checkbox"></span></td>
                    <td><input class="height-30 width-150 text-center  pdt_no" type="text" style="height: 30px"
                               maxlength="20" data-options="required:true,validType:'exist',delay:10000"
                               data-act="<?= Url::to(['pdt-validate']) ?>"
                               data-attr="pdt_no" data-id=""
                               placeholder="请输入"><input class="hiden pdt_id" name="orderL[0][pdt_id]"/></td>
                    <td><input class="height-30 width-150  text-center sapl_quantity" type="number" step="10"
                               maxlength="20"
                               name="orderL[0][sapl_quantity]" data-options="required:true"
                               placeholder="请输入"></td>
                    <td><input class="height-30 width-150 text-center  price" type="text" maxlength="20"
                               name="orderL[0][uprice_ntax_o]" data-options="required:true"
                               placeholder="请输入"></td>
                    <td><input class="height-30 width-150 text-center  cess" type="text" maxlength="20"
                               name="orderL[0][cess]" data-options="required:true"
                               placeholder="请输入"></td>
                    <td><input class="height-30 width-150 text-center  discount" type="text" maxlength="20"
                               name="orderL[0][discount]" data-options="required:true" value="100"
                               placeholder="请输入"></td>
                    <td><select class="height-30 width-150 text-center " type="text" data-options="required:true"
                                name="orderL[0][transport]">
                            <option value="">请选择...</option>
                            <?php foreach ($downList["transport"] as $key => $val) { ?>
                                <option
                                    value="<?= $val["tran_id"] ?>"><?= $val["tran_sname"] ?></option>
                            <?php } ?>
                        </select>
                        </select></td>
                    <td><select class="height-30 width-150 text-center " type="text" data-options="required:true"
                                name="orderL[0][distribution]">
                            <option value="">请选择...</option>
                            <?php foreach ($downList["dispatching"] as $key => $val) { ?>
                                <option
                                    value="<?= $val["bdm_id"] ?>"><?= $val["bdm_sname"] ?></option>
                            <?php } ?>
                        </select></td>
                    <td><input class="height-30 width-150 text-center  freight" type="text" maxlength="20"
                               data-options="required:true"
                               name="orderL[0][freight]" placeholder="请输入"></td>
                    <td><select class="height-30 width-150  text-center " type="text"
                                data-options="required:true"
                                name="orderL[0][whs_id]">
                            <option value="">请选择...</option>
                            <?php foreach ($downList["warehouse"] as $key => $val) { ?>
                                <option
                                    value="<?= $val["wh_id"] ?>"><?= $val["wh_name"] ?></option>
                            <?php } ?>
                        </select></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="space-30"></div>
    <div class="text-center mb-20">
        <button class="button-blue-big" type="button">保存</button>
        <button class="button-blue-big ml-20" type="button">提交</button>
        <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">返回</button>
    </div>
    <?php $form->end(); ?>
</div>
<script>
    $(function(){
        ajaxSubmitForm($("#add-form"));

    });
</script>
