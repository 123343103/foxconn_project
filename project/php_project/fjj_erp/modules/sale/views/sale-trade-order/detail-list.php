<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2016/2/13
 * Time: 14:49
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\JeDateAsset;

JeDateAsset::register($this);
$this->title = '交易订单明细表';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '交易订单列表', 'url' => Url::to(['/sale/sale-trade-order/index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];

$queryParam = Yii::$app->request->get();
if (!isset($queryParam['OrdInfoSearch'])) {
    $queryParam['OrdInfoSearch'] = null;
}
?>
<style>
    .label-width {
        width: 80px;
    }

    .value-width {
        width: 150px;
    }

    .space-20 {
        height: 20px;
    }

    .width-100 {
        width: 100px;
    }

    .width-110 {
        width: 110px;
    }

    .ml-20 {
        margin-left: 20px;
    }

    .ml-30 {
        margin-left: 30px;
    }

</style>
<div class="content">

    <div class="crm-customer-info-search">

        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>

        <div class="search-div" style="margin:0;">
            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width qlabel-align">订单编号</label><label>：</label>
                    <input type="text" name="OrdInfoSearch[ord_no]" class="value-width qvalue-align"
                           value="<?= $queryParam['OrdInfoSearch']['ord_no'] ?>">
                </div>
                <div class="inline-block">
                    <label class="label-width qlabel-align">订单状态</label><label>：</label>
                    <select name="OrdInfoSearch[ord_status]" class="value-width qvalue-align">
                        <option value="">全部</option>
                        <?php foreach ($downList["status"] as $key => $val) { ?>
                            <option
                                value="<?= $val['os_id'] ?>" <?= isset($queryParam['OrdInfoSearch']['ord_status']) && $queryParam['OrdInfoSearch']['ord_status'] == $val['os_id'] ? "selected" : null ?>><?= $val['os_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="inline-block">
                    <label class="label-width qlabel-align">订单类型</label><label>：</label>
                    <select name="OrdInfoSearch[ord_type]" class="value-width qvalue-align">
                        <option value="">全部</option>
                        <?php foreach ($downList["orderType"] as $key => $val) { ?>
                            <option
                                value="<?= $val["business_type_id"] ?>" <?= isset($queryParam['OrdInfoSearch']['ord_type']) && $queryParam['OrdInfoSearch']['ord_type'] == $val["business_type_id"] ? "selected" : null ?>><?= $val["business_value"] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="space-10"></div>
            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width qlabel-align">品名</label><label>：</label>
                    <input type="text" name="OrdInfoSearch[pdt_name]" class="value-width qvalue-align"
                           value="<?= $queryParam['OrdInfoSearch']['pdt_name'] ?>">
                </div>
                <div class="inline-block">
                    <label class="label-width qlabel-align">料号</label><label>：</label>
                    <input name="OrdInfoSearch[pdt_no]" class="value-width qvalue-align"
                           value="<?= $queryParam['OrdInfoSearch']['pdt_no'] ?>">
                </div>
                <div class="inline-block">
                    <label class="label-width qlabel-align">交易法人</label><label>：</label>
                    <select name="OrdInfoSearch[corporate]" class="value-width qvalue-align">
                        <option value="">全部</option>
                        <?php foreach ($downList["corporate"] as $key => $val) { ?>
                            <option
                                value="<?= $val["company_id"] ?>" <?= isset($queryParam['OrdInfoSearch']['corporate']) && $queryParam['OrdInfoSearch']['corporate'] == $val["company_id"] ? "selected" : null ?>><?= $val["company_name"] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?= Html::submitButton('查询', ['class' => 'button-blue ml-30 search-btn-blue', 'type' => 'submit']) ?>
                <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-20', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["detail-list"]) . '\'']) ?>
            </div>
            <div class="space-10"></div>
            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width qlabel-align">客户全称</label><label>：</label>
                    <input type="text" name="OrdInfoSearch[cust_sname]" class="value-width qvalue-align"
                           value="<?= $queryParam['OrdInfoSearch']['cust_sname'] ?>">
                </div>
                <div class="inline-block">
                    <label class="label-width qlabel-align">客户代码</label><label>：</label>
                    <input type="text" name="OrdInfoSearch[cust_code]" class="value-width qvalue-align"
                           value="<?= $queryParam['OrdInfoSearch']['cust_code'] ?>">
                </div>
                <div class="inline-block">
                    <label class="label-width qlabel-align">下单时间</label><label>：</label>
                    <input type="text" name="OrdInfoSearch[start_date]" class="value-width qvalue-align select-date" id="start_time"
                           onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate: '#F{$dp.$D(\'end_time\');}'})"
                           value="<?= $queryParam['OrdInfoSearch']['start_date'] ?>">
                    <label>至</label><label>：</label>
                    <input type="text" class="value-width qvalue-align select-date" name="OrdInfoSearch[end_date]" id="end_time"
                           onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '#F{$dp.$D(\'start_time\');}'})"
                           value="<?= $queryParam['OrdInfoSearch']['end_date'] ?>">
                </div>
            </div>

        </div>

        <?php ActiveForm::end(); ?>
        <div class="space-10"></div>
    </div>
    <div class="table-head">
        <p class="head">交易订单管理明细表</p>
        <div class="float-right">
            <a id="export">
                <div class='icon-btn'>
                    <p class='export-item-bgc float-left ml-4'></p>
                    <p class='text-btn border-right'>&nbsp;导出</p>
                </div>
            </a>
            <a href="<?= Url::to(["index"]) ?>">
                <div class='icon-btn'>
                    <p class='return-item-bgc float-left ml-4'></p>
                    <p class='text-btn'>&nbsp;返回</p>
                </div>
            </a>
        </div>
    </div>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
    </div>
    <script>
        $(function () {
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                rownumbers: true,
                method: "get",
                idField: "saph_id",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                columns: [[
//                    {field: "saph_code", title: "订单编号", width: 150},
//                    {field: "saph_status", title: "订单状态", width: 80},
//                    {field: "saph_type", title: "订单类型", width: 80},
//                    {field: "productCtg", title: "类别", width: 150},
//                    {field: "product", title: "商品", width: 150},
//                    {field: "products", title: "商品库存", width: 80},
//                    {field: "quantity", title: "下单数量", width: 80},
//                    {field: "recede_quantity", title: "还需采购", width: 80},
//                    {field: "cusorder_qty", title: "交易单位", width: 80},
//                    {field: "uprice_ntax_c", title: "商品单价（未税）", width: 150},
//                    {field: "uprice_ntax_o", title: "商品单价（含税）", width: 150},
//                    {field: "uprice_tax_c", title: "商品总价（未税）", width: 150},
//                    {field: "uprice_tax_o", title: "商品总价（含税）", width: 150}
                    <?= $columns ?>
                ]],
                onLoadSuccess: function (data) {
                    datagridTip("#data");
                    showEmpty($(this), data.total, 0);
                    setMenuHeight();
                }
            });
            $('#export').click(function () {
                var index = layer.confirm("确定导出订单信息?",
                    {
                        btn: ['確定', '取消'],
                        icon: 2
                    },
                    function () {
                        if (window.location.href = "<?= Url::to(['detail-list', 'export' => 1]). '&' . http_build_query(Yii::$app->request->queryParams) ?>") {
                            layer.closeAll();
                        } else {
                            layer.alert('导出订单信息发生错误', {icon: 0})
                        }
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            });
        })
    </script>