<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->title = '交易订单详情';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '交易订单列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<style>
    td p {
        display: block;
        overflow: hidden;
        word-break: break-all;
        word-wrap: break-word;
    }

    thead tr th p {
        color: white;
    }
</style>
<div class="content">
    <div class="mb-30">
        <h2 class="head-first">
            <?= $this->title ?>
        </h2>
        <div class="border-bottom mb-20">
            <?= (Menu::isAllow('/sale/sale-trade-order/index', 'btn_cancle') && ( $data["model"]["ordStatus"] == '订单已提交' || $data["model"]["ordStatus"] == '订单改价驳回' )) ? Html::button('取消订单', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'id' => 'cancel']) : '' ?>
            <?= (Menu::isAction('/sale/sale-trade-order/index', 'btn_chang_price') && ($data["model"]["ordStatus"] == '订单已提交' || $data["model"]["ordStatus"] == '订单改价驳回' || $data["model"]["ordStatus"] == '订单改价取消')) ? Html::button('订单改价', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["reprice"]) . '?id=' . yii::$app->request->queryParams["id"] . '\'']) : '' ?>
            <?= (Menu::isAction('/sale/sale-trade-order/index', 'btn_chang_cancel') && $data["model"]["ordStatus"] == '订单改价驳回') ? Html::button('取消改价', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button','id'=>'reprice-cancel']) : '' ?>
            <?= (Menu::isAction('/sale/sale-trade-order/index', 'btn_notice') && ($data["model"]["ordStatus"] == '部分已通知出货' || $data["model"]["ordStatus"] == '订单已付款' || $data["model"]["ordStatus"] == '部份已付款')) ? Html::button('出货通知', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'id' => 'out-note']) : '' ?>
            <?= Menu::isAction('/sale/sale-trade-order/index') ? Html::button('切换列表', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) : '' ?>
            <?= Menu::isAction('/sale/sale-trade-order/index', 'btn_print') ? Html::button('打印', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'btnPrints()']) : '' ?>
        </div>
        <h2 class="head-second">
            基本信息
        </h2>
    </div>
    <div class="mb-30">
        <input type="text" id="cust_id" class="value-width easyui-validatebox hiden"
               value="<?= $data["model"]["cust"]['cust_id'] ?>">
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
                    class="no-border vertical-center value-align"><?= $data["model"]["customer"]["cust_sname"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">客户代码：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["cust_code"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">公司电话：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["customer"]["cust_tel1"] ?></td>
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
                    class="no-border vertical-center value-align"><?= $data["model"]["invoice_Title_Addr"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">发票寄送地址：</td>
                <td width="87%"
                    class="no-border vertical-center value-align"><?= $data["model"]["invoice_Address"] ?></td>
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
            <tr class="no-border mb-10 <?= ($data["model"]["ordStatus"] == "订单已取消") ? '' : 'hiden' ?>">
                <td width="13%" class="no-border vertical-center label-align">订单取消原因：</td>
                <td width="87%" class="no-border vertical-center value-align"><?= $data["model"]["can_reason"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
    </div>
    <h2 class="head-second">
        订单商品信息
    </h2>
    <!--    <div class="mb-20">-->
    <!--        <table class="table">-->
    <!--            <thead>-->
    <!--            <tr style="height: 50px">-->
    <!--                <th><p class="width-40">序号</p></th>-->
    <!--                <th><p class="width-140">商品</p></th>-->
    <!--                <th><p class="width-80">下单数量</p></th>-->
    <!--                <th><p class="width-80">交易单位</p></th>-->
    <!--                <th><p class="width-80">商品单价 <br/>（含税）</p></th>-->
    <!--                <th><p class="width-80">商品总价 <br/>（含税）</p></th>-->
    <!--                <th><p class="width-80">折扣后金额</p></th>-->
    <!--                <th><p class="width-80">配送方式</p></th>-->
    <!--                <th><p class="width-80">出仓仓库</p></th>-->
    <!--                <th><p class="width-80">需求交期</p></th>-->
    <!--                <th><p class="width-80">交期</p></th>-->
    <!--                <th><p class="width-80">备注</p></th>-->
    <!--            </tr>-->
    <!--            </thead>-->
    <!--            <tbody id="product_table">-->
    <!--            --><?php //foreach ($data["products"] as $key => $val) { ?>
    <!--                <tr style="height: 50px;">-->
    <!--                    <td><p class="width-40">--><? //= ($key + 1) ?><!--</p></td>-->
    <!--                    <td><p class="width-140">--><? //= $val[""] ?><!--</p></td>-->
    <!--                    <td><p class="width-80">--><? //= $val["sapl_quantity"] ?><!--</p></td>-->
    <!--                    <td><p class="width-80">--><? //= $val["unit"] ?><!--</p></td>-->
    <!--                    <td><p class="width-80">--><? //= $val["uprice_tax_o"] ?><!--</p></td>-->
    <!--                    <td><p class="width-80">--><? //= $val["tprice_tax_o"] ?><!--</p></td>-->
    <!--                    <td><p class="width-80">-->
    <? //= ($val["tprice_tax_o"] * $val["discount"]) ?><!--</p></td>-->
    <!--                    <td><p class="width-80">--><? //= $val["bdm_sname"] ?><!--</p></td>-->
    <!--                    <td><p class="width-80">--><? //= $val["wh_name"] ?><!--</p></td>-->
    <!--                    <td><p class="width-80">--><? //= $val["delivery_date"] ?><!--</p></td>-->
    <!--                    <td><p class="width-80">--><? //= $val["consignment_date"] ?><!--</p></td>-->
    <!--                    <td><p class="width-80">--><? //= $val["sapl_remark"] ?><!--</p></td>-->
    <!--                </tr>-->
    <!--            --><?php //} ?>
    <!--            </tbody>-->
    <!--        </table>-->
    <!--    </div>-->
    <div class="space-40 "></div>
    <div id="order_child" style="width:100%;"></div>
    <table width="90%" class="no-border vertical-center label-align ml-25">
        <tr class="no-border mb-10">
            <td width="13%" class="no-border vertical-center label-align">总运费(含税)：</td>
            <td width="18%" class="no-border vertical-center value-align"><span
                    style="color:#FF6600">￥<?= bcsub($data["model"]['tax_freight'], 0, 2) ?></span></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">商品总金额(含税)：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><span
                    style="color:#FF6600">￥<?= bcsub($data["model"]['prd_org_amount'], 0, 2) ?></span></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">订单总金额(含税)：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><span
                    style="color:#FF6600">￥<?= bcsub($data["model"]['req_tax_amount'], 0, 2) ?></span></td>
        </tr>
    </table>
    <div class="space-40 "></div>
    <h2 class="head-second">
        支付方式
    </h2>
    <table width="90%"
           class="mb-10 no-border vertical-center label-align ml-25 <?= ($data["pay_type_name"] != "全额") ? "hiden" : "" ?>">
        <tr class="no-border mb-10">
            <td width="13%" class="no-border vertical-center label-align">付款方式：</td>
            <td width="18%" class="no-border vertical-center value-align"><?= $data["pac_name"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">支付类型：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= $data["pay_type_name"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">需付款金额：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= bcsub($data["model"]['req_tax_amount'], 0, 2) ?></td>
        </tr>
    </table>
    <table width="90%"
           class="mb-10 no-border vertical-center label-align ml-25 <?= ($data["pay_type_name"] != "分期") ? "hiden" : "" ?>">
        <tr class="no-border mb-10">
            <td width="13%" class="no-border vertical-center label-align">付款方式：</td>
            <td width="18%" class="no-border vertical-center value-align"><?= $data["pac_name"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">支付类型：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= $data["pay_type_name"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align"></td>
            <td width="18%"
                class="no-border vertical-center value-align"></td>
        </tr>
        <tr class="no-border mb-10">
            <td width="13%" class="no-border vertical-center label-align">分几期：</td>
            <td width="18%" class="no-border vertical-center value-align"><?= count($data["pay"]) . " 期" ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">需付款金额：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= bcsub($data["model"]['req_tax_amount'], 0, 2) ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align"></td>
            <td width="18%"
                class="no-border vertical-center value-align"></td>
        </tr>
        <?php if (!empty($data["pay"][0]["stag_times"])) { ?>
            <?php foreach ($data["pay"] as $key => $val) { ?>
                <tr class="no-border mb-10">
                    <td width="13%" class="no-border vertical-center label-align">第<?= ($key + 1) ?>
                        期支付金额：
                    </td>
                    <td width="18%" class="no-border vertical-center value-align"><?= $val["stag_cost"] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td width="13%" class="no-border vertical-center label-align">第<?= ($key + 1) ?>
                        期付款时间：
                    </td>
                    <td width="18%"
                        class="no-border vertical-center value-align"><?= $val["stag_date"] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td width="13%" class="no-border vertical-center label-align"></td>
                    <td width="18%"
                        class="no-border vertical-center value-align"></td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>
    <table width="90%"
           class="mb-10 no-border vertical-center label-align ml-25 <?= ($data["pac_name"] == "预付款") ? "hiden" : "" ?>">
        <tr class="no-border mb-10">
            <td width="13%" class="no-border vertical-center label-align">付款方式：</td>
            <td width="18%" class="no-border vertical-center value-align"><?= $data["pac_name"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">需付款金额：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= bcsub($data["model"]['req_tax_amount'], 0, 2) ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align"></td>
            <td width="18%"
                class="no-border vertical-center value-align"></td>
        </tr>
        <?php if (!empty($data["pay"][0]["credit_id"])) { ?>
            <?php foreach ($data["pay"] as $key => $val) { ?>
                <tr class="no-border mb-10">
                    <td width="13%" class="no-border vertical-center label-align">信用额度类型：
                    </td>
                    <td width="18%" class="no-border vertical-center value-align"><?= $val["credit_id"] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td width="13%" class="no-border vertical-center label-align">付款金额：</td>
                    <td width="18%"
                        class="no-border vertical-center value-align"><?= bcsub($val["stag_cost"], 0, 2) ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td width="13%" class="no-border vertical-center label-align"></td>
                    <td width="18%"
                        class="no-border vertical-center value-align"></td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>
    <div class="space-40 "></div>
    <h2 class="head-second">
        销售员信息
    </h2>
    <table width="90%" class="no-border vertical-center label-align ml-25">
        <tr class="no-border mb-10">
            <td width="13%" class="no-border vertical-center label-align">销售员：</td>
            <td width="18%" class="no-border vertical-center value-align"><?= $data["seller"]["staff_code"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">姓名：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= $data["seller"]["staff"]["name"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">客户经理人：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= $data["seller"]["leader"] ?></td>
        </tr>
    </table>
    <div class="space-40 "></div>
    <table width="90%" class="no-border vertical-center label-align ml-25">
        <tr class="no-border mb-10">
            <td width="13%" class="no-border vertical-center label-align">销售部门：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= $data["seller"]["staff"]["organization_name"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">销售区域：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= $data["seller"]["csarea"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">销售点：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= $data["seller"]["sts_sname"] ?></td>
        </tr>
    </table>
    <?php if (!empty($verify)) { ?>
        <h2 class="head-second">
            签核记录
        </h2>
        <div class="mb-30">
            <table class="product-list" style="width:990px;">
                <thead>
                <tr>
                    <th class="width-60">序号</th>
                    <th>签核节点</th>
                    <th>签核人员</th>
                    <th>签核日期</th>
                    <th>操作</th>
                    <th>签核意见</th>
                    <th>签核人IP</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($verify as $key => $val) { ?>
                    <tr>
                        <th><?= $key + 1 ?></th>
                        <th><?= $val['verifyOrg'] ?></th>
                        <th><?= $val['verifyName'] ?></th>
                        <th><?= $val['vcoc_datetime'] ?></th>
                        <th><?= $val['verifyStatus'] ?></th>
                        <th><?= $val['vcoc_remark'] ?></th>
                        <th><?= $val['vcoc_computeip'] ?></th>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <!--    <button type="button" class="button-white-big ml-400" id="submit">-->
    <!--        返回-->
    <!--    </button>-->

    <script>
        $(function () {
            $("#submit").on("click", function () {
                window.history.go(-1);
            });
            var id = "<?= $data["model"]['ord_id'] ?>";
            $("#order_child").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>" + "?id=" + id + "&view=1",
                rownumbers: true,
                method: "get",
                idField: "ord_id",
                singleSelect: true,
                pagination: false,
                pageSize: 10,
                pageList: [10, 20, 30],
                columns: [[
                    {field: 'pdt_name', title: '商品', width: 200},
                    {field: 'sapl_quantity', title: '下单数量', width: 150},
                    {field: 'unit_name', title: '交易单位', width: 150},
                    {field: 'out_num', title: '出货数量', width: 150},
                    {field: 'tax_price', title: '商品定价（含税）', width: 150},
                    {field: 'uprice_ntax_o', title: '销售单价（未税）', width: 150},
                    {field: 'uprice_tax_o', title: '销售单价（含税）', width: 150},
                    {field: 'distrustion', title: '配送方式', width: 150},
                    {field: 'wh_name', title: '自提仓库', width: 150},
                    {field: 'tran_sname', title: '运输方式', width: 150},
                    {field: 'tprice_tax_o', title: '商品总价（含税）', width: 150},
                    {field: 'tprice_ntax_o', title: '商品总价（未税）', width: 150},
                    {field: 'cess', title: '税率（%）', width: 150},
                    {field: 'discount', title: '折扣率（%）', width: 150},
                    {field: 'price_off', title: '折扣后金额', width: 150},
                    {field: 'tax_freight', title: '运费（含税）', width: 150},
                    {field: 'request_date', title: '需求交期', width: 150},
                    {field: 'consignment_date', title: '交期', width: 150},
                    {field: 'sapl_remark', title: '备注', width: 150},
                ]],
                onLoadSuccess: function (data) {
//                    showEmpty($(this), data.total, 0);
                    setMenuHeight();
                    $("#order_child").datagrid('clearSelections');
                    datagridTip("#order_child");
                },
                onSelect: function (rowIndex, rowData) {
//                if (rowData.sil_id == flag) {
//                    $("#order_child").datagrid('clearSelections');
//                    flag = '';
//                } else {
//                    flag = rowData.sil_id;
//                }
                }
            });
            $("#cancel").on("click", function () {
                 cancelId = "<?= yii::$app->request->queryParams["id"] ?>";
                $.fancybox({
                    href: "<?=Url::to(['cancel-box?id=']) . yii::$app->request->queryParams["id"]?>",
                    type: "iframe",
                    padding: 0,
                    width: 500,
                    height: 300,
                });
            });
            // 出货通知
            $("#out-note").click(function () {
                $.fancybox({
                    href: "<?=Url::to(['out-note?id=']) . yii::$app->request->queryParams["id"]?>",
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 800,
                    height: 520,
                    'onCancel': function () {
                        layer.alert("该订单没有可发送的通知!", {icon: 2, time: 5000});
                    },
                });
            });
            $("#reprice-cancel").on("click", function () {
                 cancel_id = <?= yii::$app->request->queryParams["id"]?>;
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {
                        "custId": cancel_id,
                        "type": 12
                    },
                    url: "<?=Url::to(['reprice-cancel'])?>" + "?id=" + cancel_id,
                    success: function (data) {
                        if (data.flag == 1) {
                            layer.alert(data.msg, {
                                icon: 1, end: function () {
                                    window.location.reload();
                                }
                            });
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }
                    }
                });
            });
        });
        function btnPrints() {
            $('.content').jqprint({
                debug: false,
                importCSS: true,
                printContainer: true,
                operaSupport: false
            })
        }
    </script>
