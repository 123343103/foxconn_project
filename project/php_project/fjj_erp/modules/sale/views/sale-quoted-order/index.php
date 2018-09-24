<?php
use yii\helpers\Url;
$this->title = '报价单列表';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '报价单列表'];

?>
<style>
    .space-10{
        width:100%;
        height:10px;
    }
</style>
<div class="content">
    <?php echo $this->render('_search',[
        'queryParam' => $queryParam,
        'downList' => $downList,
    ]) ?>
    <?php echo $this->render('_action',[
        'queryParam' => $queryParam,
    ]) ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
    </div>
    <div id="load-content">
        <div id="order_child_title" style="height: 25px;margin-top: 5px"></div>
        <div id="order_child" style="width:100%;"></div>
        <div id="order_child_title2" style="height: 5px;margin-top: 5px"></div>
    </div>
</div>

<script>
    var id;
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "price_id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck:false,
            checkOnSelect:false,
            columns: [[
                {field:"",checkbox:true},
                <?= $columns ?>
                {
                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
                        if(row.audit_id == '15'){
                            return '<a title="取消报价" onclick="cancle_quote(' + row.price_id + ')"><i class="icon-minus-sign fs-18"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        }else if(row.audit_id == '10' || row.audit_id == '13'){
                            return '<a title="取消报价" onclick="cancle_quote(' + row.price_id + ')"><i class="icon-minus-sign fs-18"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a title="修改" href="<?= Url::to(['update'])?>?id='+ row.price_id +'"><i class="icon-edit fs-18"></i></a>';
                        }
                }
                }
            ]],
            onSelect: function(rowIndex,rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.price_id);
                $('#data').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    var a = $("#data").datagrid("getChecked");
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                    isCheck = false;
                }
                /*待报价状态*/
                if(rowData.audit_id === '15'){
                    $('#order-quotation').show();
                }else{
                    $('#order-quotation').hide();
                }
                /*驳回状态*/
                if(rowData.audit_id === '13' || rowData.audit_id === '10'){
                    $('#update,#check').show();
                }else{
                    $('#update,#check').hide();
                }
                /*取消报价*/
                if(rowData.audit_id === '10' || rowData.audit_id === '13' || rowData.audit_id === '15'){
                    $('#cancle-quote').show();
                }else{
                    $('#cancle-quote').hide();
                }
                var oderh = $("#data").datagrid("getSelected");
//                $("#data").datagrid("uncheckAll");
//                $("#data").datagrid("checkRow",rowIndex);
                $("#load-content").show();
                $("#order_child_title").addClass("table-head mb-5 mt-20").html("<p class='head'>商品信息</p>");
                $("#order_child_title2").addClass("table-head mb-5 mt-20 red").html("<p class='head' style='float: right;'>总运费（含税）：<span style='color:#f60;'>"+ oderh['currency_mark'] + oderh["bill_freight"] + '</span>&nbsp;&nbsp;&nbsp;' + "商品总金额（含税）：<span style='color:#f60;'>"+ oderh['currency_mark'] + oderh["bill_oamount"] + '</span>&nbsp;&nbsp;&nbsp;' + "订单总金额（含税）：<span style='color:#f60;'>"+ oderh['currency_mark'] + oderh["bill_tax_amount"] + "</span></p>");
                var id = oderh['price_id'];
                $("#order_child").datagrid({
                    url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>" + "?id=" + id,
                    rownumbers: true,
                    method: "get",
                    idField: "price_dt_id",
                    singleSelect: true,
                    pagination: true,
                    pageSize: 10,
                    pageList: [10, 20, 30],
                    columns: [[
                        <?= $child_columns ?>
                    ]],
                    onLoadSuccess: function (data) {
                        showEmpty($(this), data.total,1,1);
                        setMenuHeight();
                        $("#order_child").datagrid('clearSelections');
                        datagridTip("#order_child");
                    },
//                    onSelect: function (rowIndex, rowData) {
//                        if (rowData.sil_id == flag) {
//                            $("#order_child").datagrid('clearSelections');
//                            flag = '';
//                        } else {
//                            flag = rowData.sil_id;
//                        }
//                    }
                });
                $('#data').datagrid('checkRow', index);

            },
            onCheck: function (rowIndex, rowData) {
                var a1 = $("#data").datagrid("getChecked");
                if (a1.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                } else {
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    $('#cancle-quote').show();
                    $('#check,#order-quotation,#update').hide();
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].price_id);
                        $('#data').datagrid('selectRow', b);
                    }
//                    $('#order-quotation,#update,#check,#cancle-quote').hide();
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                    $('#order-quotation,#update,#check,#cancle-quote').hide();
                }else{
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                    $('#cancle-quote').show();
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $('#cancle-quote').show();
                $('#check,#order-quotation,#update').hide();
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#load-content").hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $('#order-quotation,#update,#check,#cancle-quote').hide();
            },
            onLoadSuccess:function(data){
                $(this).datagrid('clearChecked');
                $(this).datagrid('clearSelections');
                setMenuHeight();
                datagridTip('#data');
                showEmpty($(this),data.total,1,1);
            },

        })
    });

    /*取消报价*/
    function cancle_quote(id){
        $.fancybox({
            href:"<?=Url::to(['cancle-quote'])?>?id="+id,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:445,
            height:240
        });
    }
</script>

