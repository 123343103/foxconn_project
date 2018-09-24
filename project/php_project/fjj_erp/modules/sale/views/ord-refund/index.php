<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\sale\models\search\OrdRefundSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '退款列表';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .space-10{
        width:100%;
        height:10px;
    }
    .space-30{
        width:100%;
        height:30px;
    }
</style>
<div class="content">
    <?php echo $this->render('_search',[
        'queryParam' => $queryParam,
        'downList' => $downList,
    ]) ?>
    <?php echo $this->render('_action',[
        'queryParam' => $queryParam,
        'typeId' => $typeId
    ]) ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
    </div>
    <div class="space-30"></div>
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
            idField: "refund_id",
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
                    if(row.rfnd_status === '13'){
                        return '<a title="取消退款" onclick="cancle_quote(' + row.refund_id + ')"><i class="icon-minus-sign fs-18"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a title="修改" href="<?=Url::to(['update'])?>?id='+row.refund_id+'"><i class="icon-edit fs-18"></i></a>';
                    }
                }
                }
            ]],
            onSelect: function(rowIndex,rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.refund_id);
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

                var oderh = $("#data").datagrid("getSelected");
                /*审核完成*/
                if(oderh.rfnd_status === '12'){
                    $('#confirm').show();
                }else{
                    $('#confirm').hide();
                }
                if(oderh.rfnd_status === '13'){
                    $('#check,#cancle-quote,#update').show();
                }else{
                    $('#check,#cancle-quote,#update').hide();
                }
                $("#load-content").show();
                $("#order_child_title").addClass("table-head mb-5 mt-20").html("<p class='head'>商品信息</p>");
                $("#order_child_title2").addClass("table-head mb-5 mt-20 red").html("<p class='head' style='float: right;'>退款总金额（含税）：<span style='color:#f60;'>" + oderh['currency_mark'] + oderh["bill_oamount"] + '</span>&nbsp;&nbsp;&nbsp;' + "订单总金额（含税）：<span style='color:#f60;'>"+ oderh['currency_mark'] + oderh["bill_tax_amount"] + "</span></p>");
                var id = oderh['refund_id'];
                $("#order_child").datagrid({
                    url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>" + "?id=" + id,
                    rownumbers: true,
                    method: "get",
                    idField: "req_id",
                    singleSelect: true,
                    pagination: true,
                    pageSize: 10,
                    pageList: [10, 20, 30],
                    columns: [[
                        <?= $child_columns ?>
                    ]],
                    onLoadSuccess: function (data) {
                        showEmpty($(this), data.total, 0);
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
                    $('#check,#cancle-quote,#confirm').show();
                    $('#update').hide();
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].refund_id);
                        $('#data').datagrid('selectRow', b);
                    }
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
                    $('#confirm,#update,#check,#cancle-quote').hide();
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
                    $('#check,#cancle-quote,#confirm').show();
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $('#check,#cancle-quote,#confirm').show();
                $('#update').hide();
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#load-content").hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $('#confirm,#update,#check,#cancle-quote').hide();
            },
            onLoadSuccess:function(data){
                $(this).datagrid('clearChecked');
                $(this).datagrid('clearSelections');
                setMenuHeight();
                datagridTip('#data');
                showEmpty($(this),data.total,1);
            },

        })
    });

    /*取消退款*/
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