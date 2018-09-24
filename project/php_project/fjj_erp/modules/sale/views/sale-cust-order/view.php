<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->title = '客戶需求单详情';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '客戶需求单列表', 'url' => Url::to(["index"])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
//dumpE($data);
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
    <h2 class="head-first">
        <?= $this->title ?>
    </h2>
    <div class="mb-30">
        <div class="border-bottom mb-20">
            <?= (Menu::isAllow('/sale/sale-cust-order/index', 'btn_mody') && $data["saph_status"] == "待报价") ? Html::button('修改', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . yii::$app->request->queryParams["id"] . '\'']) : '' ?>
            <?= (Menu::isAllow('/sale/sale-cust-order/index', 'btn_cancle') && $data["saph_status"] == "待报价") ? Html::button('取消', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'id'=>'cancel-order']) : '' ?>
            <?= (Menu::isAllow('/sale/sale-cust-order/index', 'btn_quote') && $data["saph_status"] == "待报价") ? Html::button('转报价', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["to-quoted"]) . '?id=' . yii::$app->request->queryParams["id"] . '\'']) : '' ?>
            <?= Menu::isAllow('/sale/sale-cust-order/index') ? Html::button('切换列表', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) : '' ?>
            <?= Menu::isAllow('/sale/sale-cust-order/index', 'btn_print') ? Html::button('打印', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'btnPrints()']) : '' ?>
        </div>
        <h2 class="head-second">
            基本信息
        </h2>
        <table width="90%" class="no-border vertical-center label-align ml-25 mb-10">
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">需求单号：</td>
                <td width="35%" class="no-border vertical-center value-align"><?= $data["saph_code"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">下单时间：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["nw_date"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">订单来源：</td>
                <td width="35%" class="no-border vertical-center value-align">平台新增订单</td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">订单类型：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["req_type"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">合同编号：</td>
                <td width="35%" class="no-border vertical-center value-align"><?= $data["contract_no"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">客户全称：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["cust_sname"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">客户代码：</td>
                <td width="35%" class="no-border vertical-center value-align"><?= $data["cust_code"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">公司电话：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["cust_tel1"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">联系人：</td>
                <td width="35%" class="no-border vertical-center value-align"><?= $data["cust_contacts"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">联系电话：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["cust_tel"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">客户地址：</td>
                <td width="35%" class="no-border vertical-center value-align"><?= $data["cust_adress"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">交易法人：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["company_name"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">交易模式：</td>
                <td width="35%" class="no-border vertical-center value-align"><?= $data["tac_sname"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">交易币别：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["cur_code"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">发票类型：</td>
                <td width="35%" class="no-border vertical-center value-align"><?= $data["invoice_type"] ?></td>
                <td width="4%" class="no-border vertical-center label-align"></td>
                <td width="13%" class="no-border vertical-center label-align">发票抬头：</td>
                <td width="35%"
                    class="no-border vertical-center value-align"><?= $data["invoice_title"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">发票抬头地址：</td>
                <td width="87%" colspan="4"
                    class="no-border vertical-center value-align"><?= $data["title_dis"] . $data["title_addr"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">发票寄送地址：</td>
                <td width="87%" colspan="4"
                    class="no-border vertical-center value-align"><?= $data["send_dis"] . $data["send_addr"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">收货人：</td>
                <td width="87%" colspan="4"
                    class="no-border vertical-center value-align"><?= $data["receipter"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">联系电话：</td>
                <td width="87%" colspan="4"
                    class="no-border vertical-center value-align"><?= $data["receipter_Tel"] . (!empty($data["addr_tel"]) ? "  /  " . $data["addr_tel"] : "") ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">收货地址：</td>
                <td width="87%" colspan="4"
                    class="no-border vertical-center value-align"><?= $data["receipt"] . $data["receipt_Address"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">附件：</td>
                <td width="87%" class="no-border vertical-center value-align" colspan="4">
                    <?php foreach ($data["attachments"] as $key => $val) { ?>
                        <a class="text-center width-150 color-w ml-10" target="_blank"
                           href="<?= \Yii::$app->ftpPath['httpIP'] ?>/ord/req/<?= explode('_', trim($val['file_new'], '_'))[0] ?>/<?= $val['file_new'] ?>"><?= $val["file_old"] ?></a>
                    <?php } ?>
                </td>
            </tr>
            <tr class="no-border mb-10">
                <td width="13%" class="no-border vertical-center label-align">订单备注说明：</td>
                <td width="87%" class="no-border vertical-center value-align"
                    colspan="4"><?= $data["saph_remark"] ?></td>
            </tr>
            <tr class="no-border mb-10 <?= ($data["saph_status"] == "已取消") ? '' : 'hiden' ?>">
                <td width="13%" class="no-border vertical-center label-align">单据取消原因：</td>
                <td width="87%" class="no-border vertical-center value-align"
                    colspan="4"><?= $data["can_reason"] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second">
        订单商品信息
    </h2>
    <div id="order_child" style="width:100%;"></div>
    <div class="space-40 "></div>
    <table width="90%" class="no-border vertical-center ml-25 mb-10" style="margin-left: 100px;margin-top: 5px">
        <tr class="no-border mb-10">
            <td width="10%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">总运费(含税)：</td>
            <td width="18%" class="no-border vertical-center value-align"><span
                    style="color:#FF6600">￥<?= bcsub($data["tax_freight"], 0, 2) ?></span></td>
            <td width="13%" class="no-border vertical-center label-align">商品总金额(含税)：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><span
                    style="color:#FF6600">￥<?= bcsub($data["prd_org_amount"], 0, 2) ?></span></td>
            <td width="13%" class="no-border vertical-center label-align">订单总金额(含税)：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><span
                    style="color:#FF6600">￥<?= bcsub($data["req_tax_amount"], 0, 2) ?></span></td>
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
            <td width="18%" class="no-border vertical-center value-align"><?= $data["pac_sname"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">支付类型：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= $data["pay_type_name"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">需付款金额：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= bcsub($data["req_tax_amount"], 0, 2) ?></td>
        </tr>
    </table>
    <table width="90%"
           class="mb-10 no-border vertical-center label-align ml-25 <?= ($data["pay_type_name"] != "分期") ? "hiden" : "" ?>">
        <tr class="no-border mb-10">
            <td width="13%" class="no-border vertical-center label-align">付款方式：</td>
            <td width="18%" class="no-border vertical-center value-align"><?= $data["pac_sname"] ?></td>
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
                class="no-border vertical-center value-align"><?= bcsub($data["req_tax_amount"], 0, 2) ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align"></td>
            <td width="18%"
                class="no-border vertical-center value-align"></td>
        </tr>
        <?php if (!empty($data["pay"][0]["stag_times"])) { ?>
            <?php foreach ($data["pay"] as $key => $val) { ?>
                <tr class="no-border mb-10">
                    <td width="13%" class="no-border vertical-center label-align">第<?= ($key + 1) ?>
                        期付款时间：
                    </td>
                    <td width="18%" class="no-border vertical-center value-align"><?= $val["stag_date"] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td width="13%" class="no-border vertical-center label-align">第<?= ($key + 1) ?>
                        期支付金额：
                    </td>
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
    <table width="90%"
           class="mb-10 no-border vertical-center label-align ml-25 <?= ($data["pac_sname"] == "预付款") ? "hiden" : "" ?>">
        <tr class="no-border mb-10">
            <td width="13%" class="no-border vertical-center label-align">付款方式：</td>
            <td width="18%" class="no-border vertical-center value-align"><?= $data["pac_sname"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">需付款金额：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= bcsub($data["req_tax_amount"], 0, 2) ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align"></td>
            <td width="18%"
                class="no-border vertical-center value-align"></td>
        </tr>
        <?php if (!empty($data["pay"][0]["credit_name"])) { ?>
            <?php foreach ($data["pay"] as $key => $val) { ?>
                <tr class="no-border mb-10">
                    <td width="13%" class="no-border vertical-center label-align">信用额度类型：
                    </td>
                    <td width="18%" class="no-border vertical-center value-align"><?= $val["credit_name"] ?></td>
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
            <td width="18%"
                class="no-border vertical-center value-align"><?= $data["seller"]["staff_code"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">姓名：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= $data["seller"]["staff"]["name"] ?></td>
            <td width="4%" class="no-border vertical-center label-align"></td>
            <td width="13%" class="no-border vertical-center label-align">客户经理人：</td>
            <td width="18%"
                class="no-border vertical-center value-align"><?= $data["seller"]["leader"] ?></td>
        </tr>
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
    <div class="space-40 "></div>
    <!--    <button type="button" class="button-white-big ml-400" id="submit">-->
    <!--        返回-->
    <!--    </button>-->

    <script>
        $(function () {
            $("#submit").on("click", function () {
                window.history.go(-1);
            });
            var id = "<?= $data['req_id'] ?>";
            $("#order_child").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>" + "?id=" + id + "&view=1",
                rownumbers: true,
                method: "get",
                idField: "req_id",
                singleSelect: true,
                pagination: false,
                pageSize: 10,
                pageList: [10, 20, 30],
                columns: [[
                    {field: 'product', title: '商品', width: 180},
                    {field: 'sapl_quantity', title: '下单数量', width: 50},
                    {field: 'unit_name', title: '交易单位', width: 50},
                    {field: 'fixed_price', title: '商品定价（含税）', width: 100},
                    {field: 'uprice_ntax_o', title: '销售单价（未税）', width: 100},
                    {field: 'uprice_tax_o', title: '销售单价（含税）', width: 100},
                    {field: 'bdm_sname', title: '配送方式', width: 50},
                    {field: 'wh_name', title: '自提仓库', width: 100},
                    {field: 'tran_sname', title: '运输方式', width: 100},
                    {field: 'tprice_tax_o', title: '商品总价（含税）', width: 100},
                    {field: 'tprice_ntax_o', title: '商品总价（未税）', width: 100},
                    {field: 'cess', title: '税率(%)', width: 100},
                    {field: 'discount', title: '折扣率(%)', width: 100},
                    {field: 'dis_count_price', title: '折扣后金额', width: 100},
                    {field: 'tax_freight', title: '运费（含税）', width: 100},
                    {field: 'request_date', title: '需求交期', width: 100},
                    {field: 'consignment_date', title: '交期', width: 100},
                    {field: 'sapl_remark', title: '备注', width: 100},
                ]],
                onLoadSuccess: function (data) {
//                    showEmpty($(this), data.total, 0);
                    setMenuHeight();
                    $("#order_child").datagrid('clearSelections');
                    datagridTip("#order_child");
                }
            });
            // 批量取消
            $("#cancel-order").on("click", function () {
                cancelId = <?= yii::$app->request->queryParams["id"] ?>;
                $.fancybox({
                    href: "<?=Url::to(['cancel-box'])?>",
                    type: "iframe",
                    padding: 0,
                    width: 500,
                    height: 300,
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
