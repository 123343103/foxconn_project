<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/12
 * Time: 16:25
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
$this->title = '报价单明细列表';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '报价单列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    .qlabel-width{
        width:80px;
    }
    .qvalue-width{
        width:150px;
    }
    .ml-15{
        margin-left:15px;
    }
    .space-30{
        width:100%;
        height:30px;
    }
</style>
<div class="content">
    <?php $form = ActiveForm::begin([
//        'action' => 'index',
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">报价单号</label><label>：</label>
                <input type="text" name="PriceDtSearch[price_no]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['PriceDtSearch']['price_no'] ?>">
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">报价单状态</label><label>：</label>
                <select name="PriceDtSearch[audit_id]" class="qvalue-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["quoted_status"] as $key => $val) { ?>
                        <option
                            value="<?= $val['audit_id'] ?>" <?= isset($queryParam['PriceDtSearch']['audit_id']) && $queryParam['PriceDtSearch']['audit_id'] == $val['audit_id'] ? "selected" : null ?>><?= $val['audit_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">关联需求单号</label><label>：</label>
                <input type="text" name="PriceDtSearch[saph_code]" class="qvalue-width qvalue-align" value="<?= $queryParam['PriceDtSearch']['saph_code'] ?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">订单类型</label><label>：</label>
                <select name="PriceDtSearch[price_type]" class="qvalue-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["orderType"] as $key => $val) { ?>
                        <option
                            value="<?= $val["business_type_id"] ?>" <?= isset($queryParam['PriceDtSearch']['price_type']) && $queryParam['PriceDtSearch']['price_type'] == $val["business_type_id"] ? "selected" : null ?>><?= $val["business_value"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">交易法人</label><label>：</label>
                <select name="PriceDtSearch[corporate]" class="qvalue-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["corporate"] as $key => $val) { ?>
                        <option
                            value="<?= $val["company_id"] ?>" <?= isset($queryParam['PriceDtSearch']['corporate']) && $queryParam['PriceDtSearch']['corporate'] == $val["company_id"] ? "selected" : null ?>><?= $val["company_name"] ?></option>
                    <?php } ?>
                </select>
            </div>
<!--            <div class="inline-block">-->
<!--                <label class="qlabel-width qlabel-align">客户名称</label><label>：</label>-->
<!--                <input type="text" name="PriceDtSearch[cust_sname]" class="qvalue-width qvalue-align"-->
<!--                       value="--><?//= $queryParam['PriceDtSearch']['cust_sname'] ?><!--">-->
<!--            </div>-->
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">客户全称/代码</label><label>：</label>
                <input type="text" name="PriceDtSearch[cust_sname]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['PriceDtSearch']['cust_sname'] ?>">
            </div>
            <?= Html::submitButton('查询', ['class' => 'button-blue ml-15 search-btn-blue', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to([Yii::$app->controller->action->id]) . '\'']) ?>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">品名</label><label>：</label>
                <input type="text" name="PriceDtSearch[pdt_name]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['PriceDtSearch']['pdt_name'] ?>">
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">料号</label><label>：</label>
                <input type="text" name="PriceDtSearch[part_no]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['PriceDtSearch']['part_no'] ?>">
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">下单时间</label><label>：</label>
                <input type="text" name="PriceDtSearch[start_date]" class="qvalue-width qvalue-align select-date" id="start_time"
                       value="<?= $queryParam['PriceDtSearch']['start_date'] ?>" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate: '#F{$dp.$D(\'end_time\');}' })">
                <label>至</label>
                <input type="text" class="qvalue-width qvalue-align select-date" name="PriceDtSearch[end_date]" id="end_time"
                       value="<?= $queryParam['PriceDtSearch']['end_date'] ?>" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '#F{$dp.$D(\'start_time\');}' })" onfocus="this.blur()">
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>
    <div class="table-head">
        <p class="head">报价单明细表</p>
        <div class="float-right">
            <a id='export'>
                <div class='table-nav'>
                    <p class='export-item-bgc float-left'></p>
                    <p class='nav-font'>&nbsp;导出</p>
                </div>
                <p class="float-left">&nbsp;|&nbsp;</p>
            </a>
            <a href="<?= Url::to(['index']) ?>">
                <div class='table-nav'>
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>&nbsp;返回</p>
                </div>
            </a>
        </div>
    </div>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
    </div>
</div>
<script>
    $(function(){
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "price_dt_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                <?= $columns ?>
            ]],
            onLoadSuccess: function (data) {
                datagridTip("#data");
                showEmpty($(this), data.total, 0);
                setMenuHeight();
                MergeCells('data','price_no,saph_code')
            }
        });

        $("#export").click(function () {
            layer.confirm("确定导出报价单信息?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['detail-list-export', 'PriceDtSearch[audit_id]' => !empty($queryParam) ? $queryParam['PriceDtSearch']['audit_id'] : null, 'PriceDtSearch[price_type]' => !empty($queryParam) ? $queryParam['PriceDtSearch']['price_type'] : null, 'PriceDtSearch[corporate]' => !empty($queryParam) ? $queryParam['PriceDtSearch']['corporate'] : null, 'PriceDtSearch[saph_code]' => !empty($queryParam) ? $queryParam['PriceDtSearch']['saph_code'] : null, 'PriceDtSearch[price_no]' => !empty($queryParam) ? $queryParam['PriceDtSearch']['price_no'] : null, 'PriceDtSearch[cust_sname]' => !empty($queryParam) ? $queryParam['PriceDtSearch']['cust_sname'] : null, 'PriceDtSearch[part_no]' => !empty($queryParam) ? $queryParam['PriceDtSearch']['part_no'] : null, 'PriceDtSearch[pdt_name]' => !empty($queryParam) ? $queryParam['PriceDtSearch']['pdt_name'] : null, 'PriceDtSearch[start_date]' => !empty($queryParam) ? $queryParam['PriceDtSearch']['start_date'] : null, 'PriceDtSearch[end_date]' => !empty($queryParam) ? $queryParam['PriceDtSearch']['end_date'] : null]) ?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出报价单错误!', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            );
        });
    })
    /*
     * EasyUI DataGrid根据字段动态合并单元格
     * @param fldList 要合并table的id
     * @param fldList 要合并的列,用逗号分隔(例如："name,department,office");
     */
    function MergeCells(tableID, fldList) {
        var Arr = fldList.split(",");
        var dg = $('#' + tableID);
        var fldName;
        var RowCount = dg.datagrid("getRows").length;
        var span;
        var PerValue = "";
        var CurValue = "";
        var length = Arr.length - 1;
        var sonFldname = "";
        var fldNameS = "";
        for (i = length; i >= 0; i--) {
            fldName = Arr[i];
            PerValue = "";
            span = 1;
            for (row = 0; row <= RowCount; row++) {
                if (row == RowCount) {
                    CurValue = "";
                } else {
                    if(fldName.indexOf('.')>0){
                        var ArrFldName = fldName.split('.')
                        fldNameS = ArrFldName[0];
                        sonFldname = ArrFldName[1];
                        CurValue = dg.datagrid("getRows")[row][fldNameS][sonFldname];
                    }else{
                        CurValue = dg.datagrid("getRows")[row][fldName];
                    }
                }
                if (PerValue == CurValue) {
                    span += 1;
                } else {
                    var index = row - span;
                    dg.datagrid('mergeCells', {
                        index: index,
                        field: fldName,
                        rowspan: span,
                        colspan: null
                    });
                    span = 1;
                    PerValue = CurValue;
                }
            }
        }
    }
</script>
