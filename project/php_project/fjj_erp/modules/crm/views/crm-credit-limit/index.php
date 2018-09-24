<?php

use yii\helpers\Html;
use yii\grid\GridView;
use  \yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\crm\models\CrmCreditApplySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title="信用额度查询";
$this->params['homeLike']=['label'=>'客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<style>
    .datagrid-cell{
        padding:0 !important;
    }
    .border_cell{
        width:100%;
        border-bottom:1px dotted #ccc;
        display: inline-block;
    }
</style>
<div class="content">
    <?php  echo $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $queryParam
    ]); ?>

    <div class="space-10"></div>
    <?php echo $this->render('_action',['queryParam' => $queryParam]); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data" class="main-table">
        </div>
        <div class="space-10"></div>
        <div class="second-title mb-10"></div>
        <div id="limit"></div>
    </div>
</div>
<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "aid",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck:true,
            checkOnSelect:true,
            columns: [[
                <?= $columns ?>
                {
                    field: "action", title: "操作", width: 150, formatter: function (val, row, index) {
//                    return '<a href="<?//= Url::to(['list']) ?>//?id='+ row.l_credit_id +'">查看履历</a>&nbsp;<a>还款记录</a>';
                }
                },
            ]],
            onSelect: function(rowIndex,rowData) {

            },
            onLoadSuccess:function(data){
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                setMenuHeight();
                datagridTip('#data');
                showEmpty($(this),data.total,0);
                if (data.rows.length > 0) {
                    //调用mergeCellsByField()合并单元格
                    mergeCellsByField("data", "cust_sname,creditType,grand_total_limit,payment_clause,file,apply_remark,company_name,credit_date,action");
                }
            },
        })

        // 信息导出
        $("#export").click(function () {
            layer.confirm("确定导出?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    layer.closeAll();
                    window.location.href = "<?= Url::to(['export', 'CrmCreditLimitSearch'=>$queryParam['CrmCreditLimitSearch']]) ?>"
                },
                function () {
                    layer.closeAll();
                }
            );
        });

        function mergeCellsByField(tableID, colList) {
            var ColArray = colList.split(",");
            var tTable = $("#" + tableID);
            var TableRowCnts = tTable.datagrid("getRows").length;
            var tmpA;
            var PerTxt = "";
            var CurTxt = "";
            var alertStr = "";
            for (j = ColArray.length - 1; j >= 0; j--) {
                PerTxt = "";
                tmpA = 1;
                for (i = 0; i <= TableRowCnts; i++) {
                    if (i == TableRowCnts) {
                        CurTxt = "";
                    }
                    else {
                        CurTxt = tTable.datagrid("getRows")[i]['cust_code'];
                    }
                    if (PerTxt == CurTxt) {
                        if (CurTxt!=null)
                        {
                            tmpA += 1;
                        }
                    }
                    else {
                        tTable.datagrid("mergeCells", {
                            index: i - tmpA,
                            field: ColArray[j],　　//合并字段
                            rowspan: tmpA,
                            colspan: null
                        });

                        tmpA = 1;
                    }
                    PerTxt = CurTxt;
                }
            }
        }
    });
</script>