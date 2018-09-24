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
use app\classes\Menu;

$this->title = '报价单审核详情';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '报价单列表', 'url' => Url::to(['index'])];
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

    .mb-10 {
        margin-bottom: 10px;
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
<div class="content">
    <div class="border-bottom mb-10  pb-10">
        <?= Html::button('修 &nbsp; 改', ['class' => 'button-blue width-100 display-none', 'id' => 'update', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $data['price_id'] . '\'']) ?>
        <?= Html::button('送 &nbsp; 审', ['class' => 'button-blue width-100 display-none', 'type' => 'button', 'id' => 'check']) ?>
        <?= Html::button('订单报价', ['class' => 'button-blue width-100 display-none', 'id' => 'quote', 'onclick' => 'window.location.href=\'' . Url::to(["create"]) . '?id=' . $data['price_id'] . '\'']) ?>
        <?= Html::button('取消报价', ['class' => 'button-blue width-100 display-none', 'type' => 'button', 'id' => 'cancle-quote']) ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <?= Html::button('打 &nbsp; 印', ['class' => 'button-blue width-80', 'onclick' => 'btnPrints()']) ?>

    </div>
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
                <td class="no-border vertical-center" width="35%" colspan="4"><?= $data["receipter_Tel"] . (!empty($data["addr_tel"]) ? "  /  " . $data["addr_tel"] : "") ?></td>
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
                    <td><p class="text-center width-150 text-no-next"><?= $val["pdt_no"] ?></p><input type="hidden"
                                                                                         name="PriceDt[<?= $key ?>][price_dt_id]"
                                                                                         value="<?= $val["price_dt_id"] ?>"/>
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
                        <td class="no-border vertical-center label-align" width="10%">付款方式<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="20%"><?= $data['priceValue']['pac_sname'] ?></td>
                        <td class="no-border vertical-center label-align" width="10%">支付类型<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="20%"><?= $data['priceValue']['pay_type_name'] ?></td>
                        <td class="no-border vertical-center label-align" width="10%">需付款金额<label>：</label></td>
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
                        <td class="no-border vertical-center label-align" width="13%">付款方式<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="35%"><?= $data['priceValue']['pac_sname'] ?></td>
                        <td width="4%" class="no-border vertical-center label-align"></td>
                        <td class="no-border vertical-center label-align" width="13%">支付类型<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="35%"><?= $data['priceValue']['pay_type_name'] ?></td>
                    </tr>
                    <tr class="no-border mb-10">
                        <td class="no-border vertical-center label-align" width="13%">分几期<label>：</label></td>
                        <td class="no-border vertical-center value-align" width="35%"><?= count($data['pay']) ?> 期</td>
                        <td width="4%" class="no-border vertical-center label-align"></td>
                        <td class="no-border vertical-center label-align" width="13%">需付款金额<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="35%"><?= $data['currency_mark'] . sprintf('%.2f',$data['req_tax_amount']) ?></td>
                    </tr>
                    <?php foreach ($data['pay'] as $key => $val) { ?>
                        <tr class="no-border mb-10">
                            <td class="no-border vertical-center label-align" width="13%">第<?= $key + 1 ?>
                                期付款时间<label>：</label></td>
                            <td class="no-border vertical-center value-align" width="35%"><?= $val["stag_date"] ?></td>
                            <td width="4%" class="no-border vertical-center label-align"></td>
                            <td class="no-border vertical-center label-align" width="13%">第<?= $key + 1 ?>
                                期支付金额<label>：</label></td>
                            <td class="no-border vertical-center value-align" width="35%"><?= $data['currency_mark'] . sprintf('%.2f',$val['stag_cost']) ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="mb-10 ml-20">
            <table width="90%" class="no-border vertical-center mb-10"
                   style="border-collapse:separate; border-spacing:5px;">
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="10%">付款方式<label>：</label></td>
                    <td class="no-border vertical-center value-align"
                        width="10%"><?= $data['priceValue']['pac_sname'] ?></td>
                    <td class="no-border vertical-center label-align" width="10%">需付款金额<label>：</label></td>
                    <td class="no-border vertical-center value-align" width="50%"
                        colspan="5"><?= $data['currency_mark'] . $data['req_tax_amount'] ?></td>
                </tr>
                <?php foreach ($credits['rows'] as $k => $v) { ?>
                    <tr class="no-border mb-10">
                        <td class="no-border vertical-center label-align" width="10%">信用额度类型<label>：</label></td>
                        <td class="no-border vertical-center value-align" width="10%"><?= $v['credit_name'] ?></td>
                        <td class="no-border vertical-center label-align" width="10%">总额度<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="10%"><?= $data['currency_mark'] . $v['approval_limit'] ?></td>
                        <td class="no-border vertical-center label-align" width="10%">剩余额度<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="10%"><?= $data['currency_mark'] . $v['surplus_limit'] ?></td>
                        <td class="no-border vertical-center label-align" width="10%">支付额度<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="10%"><?= $data['currency_mark'] . number_format($data['pay'][$k]["stag_cost"], '2', '.', '') ?></td>
                    </tr>

                <?php } ?>
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
                <td class="no-border vertical-center label-align" width="10%">销售员<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $seller["staff_code"] ?></td>
                <td class="no-border vertical-center label-align" width="10%">姓名<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $seller['staff']["name"] ?></td>
                <td class="no-border vertical-center label-align" width="10%">客户经理人<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $seller['isrule'] == 1?$seller['staff_code'].'&nbsp;'.$seller['staff']['name']:$seller["leader"] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">销售部门<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="20%"><?= $seller["staff"]["organization_name"] ?></td>
                <td class="no-border vertical-center label-align" width="10%">销售区域<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $seller["csarea"] ?></td>
                <td class="no-border vertical-center label-align" width="10%">销售点<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $seller["sts_sname"] ?></td>
            </tr>
        </table>
    </div>
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
</div>
<script>
    $(function () {
        <?php if($data['audit_id'] == 15){ ?>
        $('#quote').show();
        <?php }else if($data['audit_id'] == 13 || $data['audit_id'] == 10){ ?>
        $('#update,#check,#cancle-quote').show();
        <?php } ?>

        var isApply = "<?= $isApply ?>";
        var id = '<?= $id ?>';
        var type = '<?= $data["price_type"] ?>';
        var url = "<?=Url::to(['view'], true)?>?id=" + id;
        if (isApply == 1) {
            $.fancybox({
                href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 750,
                height: 480
            });
        }

        /*送审*/
        $('#check').click(function () {
            $.fancybox({
                href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 750,
                height: 480
            });
        });

        $('#cancle-quote').click(function () {
            $.fancybox({
                href: "<?=Url::to(['cancle-quote'])?>?id=" + id,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 445,
                height: 240
            });
        })
    })

    function btnPrints() {
        $('.content').jqprint({
            debug: false,
            importCSS: true,
            printContainer: true,
            operaSupport: false
        })
    }
</script>
