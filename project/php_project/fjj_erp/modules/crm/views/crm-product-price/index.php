<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/1/11
 * Time: 下午 02:13
 */
$this->title = "商品定价查询";
$this->params['homeLike'] = ['label' => '客户关系系统'];
$this->params['breadcrumbs'][] = ['label' => '商品定价查询'];
?>
<div class="content">
    <?php echo $this->render('_search', [
        'productTypeIdToValue' => $productTypeIdToValue
        , 'type2' => $type2
        , 'type3' => $type3
        , 'type4' => $type4
        , 'type5' => $type5
        , 'type6' => $type6
        , 'statusType' => $statusType
    ]); ?>
    <div class="space-10"></div>
    <div class="table-head">
        <p class="head">商品列表</p>
    </div>
    <div class="table-content">

        <div class="space-10"></div>
        <div id="data"></div>
    </div>

    <div class="table-head">
        <p class="head">价格详情</p>
    </div>
    <div class="tab-content mt-10">

        <div class="space-10"></div>
        <div id="price-info"></div>
    </div>
</div>

<script>
    $(function () {
        $("#data").datagrid({
            method: "get",
            url: "<?=\yii\helpers\Url::current()?>",
            pagination: true,
            singleSelect: true,
            frozenColumns: [[
                {field: 'pdt_no', title: '料号'}
                , {field: 'pdt_name', title: '商品名称'}
                , {field: 'unit', title: '单位'},
                {
                    field: 'status',
                    title: '状态',
                    formatter: function (val, row, index) {
                        var statusArr = ["未定价", "发起定价", "商品开发维护", "审核中", "已定价", "被驳回", "已逾期", "重新定价"];
                        return statusArr[row.status];
                    }
                }
            ]],
            columns: [[
//                 {field: 'center', title: '开发中心'}
//                ,{field: 'applydep', title: '开发部'}
//                , {field: 'pdt_manager', title: '商品经理人'}
                {field: 'brand_name', title: '品牌'}
//                , {field: 'archrival', title: '競爭對手'}
                , {field: 'company_name', title: '所属资料库'}
//                , {field: 'tp_spec', title: '規格型號'}
//                , {field: 'salearea', title: '销售区域'}
                , {field: 'bs_category_id', title: '类别'}
                , {field: 'type_1', title: '一阶'}
                , {field: 'type_2', title: '二阶'}
                , {field: 'type_3', title: '三阶'}
                , {field: 'type_4', title: '四阶'}
                , {field: 'type_5', title: '五阶'}
                , {field: 'type_6', title: '六阶'}
                , {
                    field: 'iskz',
                    title: '是否客制化',
                    formatter: function (value, row, index) {
                        return row.iskz == 1 ? "Y" : "N";
                    }
                }
                , {
                    field: 'isproxy',
                    title: '是否代理',
                    formatter: function (value, row, index) {
                        return row.isproxy == 1 ? "Y" : "N";
                    }
                }
                , {
                    field: 'isonlinesell',
                    title: '是否线上销售',
                    formatter: function (value, row, index) {
                        return row.isonlinesell == 1 ? "Y" : "N";
                    }
                }
                , {
                    field: 'risk_level',
                    title: '法务风险等级',
                    formatter: function (value, row, index) {
                        var levelArr = ['高', '中', '低'];
                        return levelArr[row.risk_level];
                    }
                }
                , {
                    field: 'istitle',
                    title: '是否拳头商品',
                    formatter: function (value, row, index) {
                        return row.istitle == 1 ? "Y" : "N";
                    }
                }
            ]],
            onSelect: function (index, row) {
                $("#price-info").datagrid({
                    url: "<?=\yii\helpers\Url::to(['price-info'])?>",
                    singleSelect: true,
                    queryParams: {
                        part_no: row.pdt_no
                    },
                    columns: [[
                        {field: "part_no", title: "料号"}
                        , {field: "price_no", title: "定价编号"}
                        , {field: 'pdt_name', title: '品名'}
                        , {
                            field: 'pdt_level',
                            title: '商品定位',
                            formatter: function (value, row, index) {
                                switch (row.pdt_level) {
                                    case 1:
                                        return "高";
                                        break;
                                    case 2:
                                        return "中";
                                        break;
                                    case 3:
                                        return "低";
                                        break;
                                }
                            }
                        }
                        , {field: 'unit', title: '交易单位'}
                        , {field: 'min_order', title: '最小订购量'}
                        , {field: 'currency', title: '交易币别'}
                        , {field: 'market_price', title: '采购价（未税）'}
                        , {field: 'min_price', title: '低价（未税）'}
                        , {field: 'ws_lower_price', title: '商品定价下线（未税）'}
                        , {field: 'ws_upper_price', title: '商品定价上线（未税）'}
                        , {field: 'num_area', title: '量价区间'}
                        , {field: 'market_price', title: '市场均价'}
                        , {field: 'valid_date', title: '价格有效期'}
                        , {field: 'gross_profit', title: '毛利润'}
                        , {field: 'gross_profit_margin', title: '毛利润率'}
                        , {field: 'pre_tax_profit', title: '税前利润'}
                        , {field: 'pre_tax_profit_rate', title: '税前利润率'}
                        , {field: 'after_tax_profit', title: '税后利润'}
                        , {field: 'after_tax_profit_margin', title: '税后利润率'}
                    ]]
                });
            }
        });
    });
</script>
