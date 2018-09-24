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

$this->title = '客户需求单明细表';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '客户需求单列表', 'url' => Url::to(['/sale/sale-cust-order/index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];

$queryParam = Yii::$app->request->get();
if (!isset($queryParam['ReqDtSearch'])) {
    $queryParam['ReqDtSearch'] = null;
}
?>
<style>
    .label-width {
        width: 100px;
    }

    .width-110 {
        width: 110px;
    }

    .label-width {
        width: 70px;
    }

    .space-20 {
        width: 100%;
        height: 20px;
    }

    .value-width {
        width: 160px;
    }

    .ml-25 {
        margin-left: 27px;
    }

    .ml-16 {
        margin-left: 16px;
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
                    <label class="label-width">需求单号</label><label>：</label>
                    <input type="text" name="ReqDtSearch[saph_code]" class="value-width"
                           value="<?= $queryParam['ReqDtSearch']['saph_code'] ?>">
                </div>
                <div class="inline-block">
                    <label class="label-width">需求单状态</label><label>：</label>
                    <select name="ReqDtSearch[saph_status]" class="value-width">
                        <option value="">全部</option>
                        <?php foreach ($downList["status"] as $key => $val) { ?>
                            <option
                                value="<?= $key ?>" <?= isset($queryParam['ReqDtSearch']['saph_status']) && $queryParam['ReqDtSearch']['saph_status'] == $key ? "selected" : null ?>><?= $val ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="inline-block">
                    <label class="label-width">订单类型</label><label>：</label>
                    <select name="ReqDtSearch[saph_type]" class="value-width">
                        <option value="">全部</option>
                        <?php foreach ($downList["orderType"] as $key => $val) { ?>
                            <option
                                value="<?= $val["business_type_id"] ?>" <?= isset($queryParam['ReqDtSearch']['saph_type']) && $queryParam['ReqDtSearch']['saph_type'] == $val["business_type_id"] ? "selected" : null ?>><?= $val["business_value"] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width">品名</label><label>：</label>
                    <input type="text" name="ReqDtSearch[pdt_name]" class="value-width"
                           value="<?= $queryParam['ReqDtSearch']['pdt_name'] ?>">
                </div>
                <div class="inline-block">
                    <label class="label-width">料号</label><label>：</label>
                    <input name="ReqDtSearch[pdt_no]" class="value-width"
                           value="<?= $queryParam['ReqDtSearch']['pdt_no'] ?>">
                </div>
                <div class="inline-block">
                    <label class="label-width">交易法人</label><label>：</label>
                    <select name="ReqDtSearch[corporate]" class="value-width">
                        <option value="">全部</option>
                        <?php foreach ($downList["corporate"] as $key => $val) { ?>
                            <option
                                value="<?= $val["company_id"] ?>" <?= isset($queryParam['ReqDtSearch']['corporate']) && $queryParam['ReqDtSearch']['corporate'] == $val["company_id"] ? "selected" : null ?>><?= $val["company_name"] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?= Html::submitButton('查询', ['class' => 'button-blue search-btn-blue ml-25', 'type' => 'submit']) ?>
                <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-16', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["list"]) . '\'']) ?>
            </div>
            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width">客户全称</label><label>：</label>
                    <input type="text" name="ReqDtSearch[cust_sname]" class="value-width"
                           value="<?= $queryParam['ReqDtSearch']['cust_sname'] ?>">
                </div>
                <div class="inline-block">
                    <label class="label-width">客户代码</label><label>：</label>
                    <input type="text" name="ReqDtSearch[applyno]" class="value-width"
                           value="<?= $queryParam['ReqDtSearch']['applyno'] ?>">
                </div>
                <div class="inline-block">
                    <label class="label-width">下单时间</label><label>：</label>
                    <input type="text" name="ReqDtSearch[start_date]" class="value-width select-date"
                           value="<?= $queryParam['ReqDtSearch']['start_date'] ?>" id="start_time"
                           onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate: '#F{$dp.$D(\'end_time\');}' })"
                           onfocus="this.blur();">
                    <label>至</label><label>：</label>
                    <input type="text" class="value-width select-date" name="ReqDtSearch[end_date]" id="end_time"
                           onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '#F{$dp.$D(\'start_time\');}' })"
                           onfocus="this.blur();"
                           value="<?= $queryParam['ReqDtSearch']['end_date'] ?>">
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
        <div class="space-30"></div>
    </div>
    <div class="space-10"></div>
    <div class="table-head">
        <p class="head"><?= $this->title ?></p>
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
//                    {field: "saph_status", title: "订单状态", width: 80, formatter:function(value,row,index){
//                        switch (row.saph_status) {
//                            case '10':
//                                return '新增';
//                                break;
//                            case '21':
//                                return '转报价';
//                                break;
//                            case '22':
//                                return '审核中';
//                                break;
//                            case '23':
//                                return '已报价';
//                                break;
//                            case '24':
//                                return '报价驳回';
//                                break;
//                        }
//                    }},
//                    {field: "business_value", width: 80},
//                    {field: "saph_date", title: "下单时间", width: 80},
//                    {field: "ctg_pname", title: "类别", width: 150},
//                    {field: "pdt_name", title: "商品", width: 150},
//                    {field: "invt_num", title: "商品库存", width: 80},
//                    {field: "sapl_quantity", title: "下单数量", width: 80},
//                    {field: "require_quantity", title: "还需采购", width: 80, formatter:function(value,row,index){
//                        if (row.invt_num != null && row.sapl_quantity) {
//                            return row.sapl_quantity>row.invt_num ? row.sapl_quantity-row.invt_num : null;
//                        } else {
//                            return null;
//                        }
//                    }},
//                    {field: "unit_name", title: "交易单位", width: 80},
//                    {field: "uprice_ntax_o", title: "商品单价（未税）", width: 150},
//                    {field: "uprice_tax_o", title: "商品单价（含税）", width: 150},
//                    {field: "tprice_ntax_o", title: "商品总价（未税）", width: 150},
//                    {field: "tprice_tax_o", title: "商品总价（含税）", width: 150}
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
                        if (window.location.href = "<?= Url::to([Yii::$app->controller->action->id, 'export' => 1, 'ReqDtSearch[saph_code]' => !empty($queryParam) ? $queryParam['ReqDtSearch']['saph_code'] : null, 'ReqDtSearch[saph_type]' => !empty($queryParam) ? $queryParam['ReqDtSearch']['saph_type'] : null, 'ReqDtSearch[cust_sname]' => !empty($queryParam) ? $queryParam['ReqDtSearch']['cust_sname'] : null, 'ReqDtSearch[applyno]' => !empty($queryParam) ? $queryParam['ReqDtSearch']['applyno'] : null, 'ReqDtSearch[corporate]' => !empty($queryParam) ? $queryParam['ReqDtSearch']['corporate'] : null, 'ReqDtSearch[saph_status]' => !empty($queryParam) ? $queryParam['ReqDtSearch']['saph_status'] : null, 'ReqDtSearch[ccpich_personid]' => !empty($queryParam) ? $queryParam['ReqDtSearch'][''] : null, 'ReqDtSearch[]' => !empty($queryParam) ? $queryParam['ReqDtSearch']['ccpich_personid'] : null, 'ReqDtSearch[pat_id]' => !empty($queryParam) ? $queryParam['ReqDtSearch']['pat_id'] : null, 'ReqDtSearch[start_date]' => !empty($queryParam) ? $queryParam['ReqDtSearch']['start_date'] : null, 'ReqDtSearch[end_date]' => !empty($queryParam) ? $queryParam['ReqDtSearch']['end_date'] : null]) ?>") {
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