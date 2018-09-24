<?php
/**
 * F3858995
 *  2016/9/26
 */
?>
<div class="table-head">
    <p class="head">商品信息</p>
</div>
<div class="space-10"></div>
<div id="load-products"></div>
<script>
    $(function () {
        "use strict";
        $("#load-products").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "product_name",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "product_name", title: "商品名称"},
                {field: "product_size", title: "商品规格型号", width: 80},
                {field: "product_unit", title: "单位", width: 50},
                {field: "product_brand", title: "品牌", width: 60},
                {field: "material", title: "材质", width: 150},
                {field: "quantity", title: "需求数量/月", width: 150},
//                {field: "typeName", title: "一阶", width: 150},
//                {field: "typeName", title: "二阶", width: 150},
//                {field: "typeName", title: "三阶", width: 150},
//                {field: "typeName", title: "四阶", width: 150},
//                {field: "typeName", title: "五阶", width: 150},
//                {field: "typeName", title: "六阶", width: 150},
            ]],
//            onSelect: function (rowIndex, rowData) {    //选择触发事件
//                if (rowData.products.length == 0) {
//                    return false;
//                }
//                var id = rowData['pdq_id'];
//                setMenuHeight();
//            },
            onLoadSuccess: function (data) {
                if (data.total == 0) {
                    $(this).datagrid('appendRow', {pdq_code: '<div style="text-align:center;color:dimgray">无数据！</div>'}).datagrid('mergeCells', {
                        index: 0,
                        field: 'pdq_code',
                        colspan: 11
                    });
                    $(this).closest('div.datagrid-wrap').find('div.datagrid-pager').hide();
                }
            }
        });
//        $('#load-products').datagrid({loadFilter:pagerFilter}).datagrid('load-products', productList);
    })
</script>

