<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\crm\models\search\CrmCreditMaintainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '信用额度类型设置列表';
$this->params['homeLike']=['label'=>'客户关系管理'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <?php  echo $this->render('_search', [
        'queryParam' => $queryParam
    ]); ?>

    <div class="space-10"></div>
    <?php echo $this->render('_action',['queryParam' => $queryParam]); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data" class="main-table">
        </div>
    </div>
</div>
<script>
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            columns: [[
                {field:"",checkbox:true},
                <?= $columns ?>
                {
                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
                        return '<a onclick="cancle(' + row.id + ')"><i class="icon-minus-sign fs-18"></i></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a onclick="updateType(' + row.id + ')"><i class="icon-edit fs-18"></i></a>';
                    }
                },
            ]],
            onCheck:function(rowIndex,rowData){
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
                }
                btnDisplay();
            },
            onSelect: function(rowIndex,rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.id);
                $('#data').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                    isCheck = false;
                }
                $('#data').datagrid('checkRow', index);
//                $('#view,#update,#delete').show();
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    $("#load-content").hide();
                    $("#delete,#update,#view,#backVisit,.turn_investment,.turn_sales,#reminders").addClass('display-none');
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                        $("#load-content").hide();
                    }
                }
                btnDisplay();
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                btnDisplay();
            },
            onUncheckAll: function (rowIndex, rowData) {
                $("#load-content").hide();
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                btnDisplay();
            },
            onLoadSuccess:function(data){
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                setMenuHeight();
                datagridTip('#data');
                showEmpty($(this),data.total,1);
            },
        })
    });

    // 显示按钮
    function btnDisplay() {
        var check = $("#data").datagrid('getChecked');
        var select = $("#data").datagrid('getSelections');
        console.log(select.length , '--', check.length)
        if (select.length == 1) {
            $("#update,#delete,#create").show();
            return;
        }
        if (check.length == 0) {
            $("#delete,#update").hide();
            return;
        }
        if (check.length > 1) {
            $("#update").hide();
        }
    }

</script>
