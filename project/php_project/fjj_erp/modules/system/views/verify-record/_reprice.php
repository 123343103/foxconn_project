<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;
use yii\widgets\ActiveForm;

$this->title = '交易订单改价';
$this->params['homeLike'] = ['label' => '主页'];
$this->params['breadcrumbs'][] = ['label' => '审核列表', 'url' => 'index'];
$this->params['breadcrumbs'][] = $this->title;
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
        <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
        <input type="hidden" name="_ids" class="_ids" value="<?= $id ?>">
        <?php ActiveForm::end(); ?>
        <h1 class="head-first">
            交易订单详情
            <span
                style="font-size:12px;color:white;float:right;margin-right:15px;">交易订单编号：<?= $data['model']['ord_no'] ?></span>
        </h1>
        <div class="mb-10">
            <?= Html::button('通过', ['class' => 'button-blue', 'id' => 'pass']) ?>
            <?= Html::button('驳回', ['class' => 'button-blue', 'id' => 'reject']) ?>
            <?= Html::button('切换列表', ['class' => 'button-blue', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        </div>
    </div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">基本信息</a>
    </h2>
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
                    class="no-border vertical-center value-align"><?= $data["model"]["customer"]["cust_sname"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">客户代码：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["cust_code"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">公司电话：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["model"]["cust_tel1"] ?></td>
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
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">订单取消原因：</td>
                <td width="87%" class="no-border vertical-center value-align"><?= $data["model"]["can_reason"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
    </div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">订单商品信息</a>
    </h2>
    <div>
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
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center label-align">总运费(含税)：</td>
                <td width="18%"
                    class="no-border vertical-center value-align"><?= bcsub($data["model"]['tax_freight'], 0, 2) ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">商品总金额(含税)：</td>
                <td width="18%"
                    class="no-border vertical-center value-align"><?= bcsub($data["model"]['prd_org_amount'], 0, 2) ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">订单总金额(含税)：</td>
                <td width="18%"
                    class="no-border vertical-center value-align"><?= bcsub($data["model"]['req_tax_amount'], 0, 2) ?></td>
            </tr>
        </table>
    </div>
    <div class="space-40 "></div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">支付方式</a>
    </h2>
    <div>
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
                    <tr class="no-border">
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
                    <tr class="no-border">
                        <td width="13%" class="no-border vertical-center label-align">信用额度类型：
                        </td>
                        <td width="18%" class="no-border vertical-center value-align"><?= $val["credit_id"] ?></td>
                        <td width="4%" class="no-border vertical-center label-align"></td>
                        <td width="13%" class="no-border vertical-center label-align">付款金额：</td>
                        <td width="18%"
                            class="no-border vertical-center value-align"><?= $val["stag_cost"] ?></td>
                        <td width="4%" class="no-border vertical-center label-align"></td>
                        <td width="13%" class="no-border vertical-center label-align"></td>
                        <td width="18%"
                            class="no-border vertical-center value-align"></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </table>
    </div>
    <div class="space-40 "></div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">销售员信息</a>
    </h2>
    <div>
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
    </div>
    <!--    <button type="button" class="button-white-big ml-400" id="submit">-->
    <!--        返回-->
    <!--    </button>-->
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">签核信息</a>
    </h2>
    <div class="mb-30">
        <div class="mb-10">
            <div id="record"></div>
        </div>
    </div>
    <script>
        $(function () {
            $(".head-second").next("div:eq(0)").css("display", "block");
            $(".head-second>a").click(function () {
                $(this).parent().next().slideToggle();
                $(this).prev().toggleClass("icon-caret-down");
                $(this).prev().toggleClass("icon-caret-right");
                $(".retable").datagrid("resize");
            });
            var id = "<?= $data["model"]['ord_id'] ?>";
            $("#order_child").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Url::to(['/sale/sale-trade-order/get-log-product']) ?>" + "?id=" + id + "&view=1",
                rownumbers: true,
                method: "get",
                idField: "req_id",
                singleSelect: true,
                pagination: false,
                pageSize: 10,
                pageList: [10, 20, 30],
                columns: [[
                    {field: 'pdt_name', title: '商品', width: 180},
                    {field: 'sapl_quantity', title: '下单数量', width: 150},
                    {field: 'unit_name', title: '交易单位', width: 150},
                    {field: 'tax_price', title: '商品定价（含税）', width: 150},
                    {field: 'uprice_tax_o', title: '销售单价（含税）', width: 150},
                    {field: 'uprice_ntax_o', title: '销售单价（未税）', width: 150},
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
            $("#record").datagrid({
                url: "<?= Url::to(['/system/verify-record/load-record']);?>?id=" + <?= $id ?>,
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
                    datagridTip('#record');
                    showEmpty($(this), data.total, 0);
                    $(this).datagrid("resize");
                }
            });
            //驳回
            $("#reject").on("click", function () {
                var idss = $("._ids").val();
                $.fancybox({
                    href: "<?=Url::to(['opinion'])?>?id=" + idss,
                    type: 'iframe',
                    padding: 0,
                    autoSize: false,
                    width: 435,
                    height: 280,
                    fitToView: false
                });
            });

            //通过
            $("#pass").on("click", function () {
                var idss = $("._ids").val();
                $.fancybox({
                    href: "<?=Url::to(['pass-opinion'])?>?id=" + idss,
                    type: 'iframe',
                    padding: 0,
                    autoSize: false,
                    width: 435,
                    height: 280,
                    fitToView: false
                });
            });
        });
    </script>