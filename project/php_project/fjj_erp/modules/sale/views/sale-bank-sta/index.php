<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2016/2/13
 * Time: 14:49
 */
use yii\helpers\Url;

$this->title = '银行流水详情';
$this->params['homeLike'] = ['label' => '收款管理'];
$this->params['breadcrumbs'][] = ['label' => '银行流水列表'];
$queryParam = Yii::$app->request->queryParams;
?>
<h1 class="head-first" style="margin: 10px 2px">银行流水列表</h1>
<div class="content">
    <?php echo $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $search,
    ]); ?>
    <div class="table-content">
        <div class="table-head">
            <p class="head">银行流水绑定订单列表</p>
            <div class="float-right">
                <a onclick="GrabData()" id="grabdata">
                    <div style="height: 23px;width: 80px;float: left;">
                        <p class="grab-data" style="float: left;"></p>
                        <p style="font-size: 14px;margin-top: 2px;">抓取数据</p>
                    </div>
                </a>
                <p style="float: left;" id="grabdata1"> | </p>
                <a id="import" href="<?= Url::to(['batch-import']) ?>">
                    <div style="height: 23px;width: 135px;float: left;">
                        <p class="excel-import" style="float: left;"></p>
                        <p style="font-size: 14px;margin-top: 2px;">批量导入收款信息</p>
                    </div>
                </a>
                <p style="float: left;" id="import1"> | </p>
                <a id="export">
                    <div style="height: 23px;width: 50px;float: left;">
                        <p class="export-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">导出</p>
                    </div>
                </a>
                <p style="float: left;" id="export1"> | </p>
                <a id="back">
                    <div style="height: 23px;width: 50px;float: left">
                        <p class="return-item-bgc" style="float: left;"></p>
                        <p style="font-size: 14px;margin-top: 2px;">返回</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="space-10"></div>
        <div id="data" style="width: 990px;"></div>
        <div class="space-10"></div>

    </div>
    <script>
        $(function () {
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                rownumbers: true,
                method: "get",
                idField: "TRANSID",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                columns: [[
                    <?=$columns?>
                    {
                        field: "state", title: "收款状态", width: 60, formatter: function (value, row) {
                        switch (value) {
                            case "10":
                                return "<a onclick='VerifyView(" + row.rbo_id + ")'>审核中</a>";
                                break;
                            case "20":
                                return "<a onclick='VerifyView(" + row.rbo_id + ")'>已审核</a>";
                                break;
                            case "30":
                                return "<a onclick='VerifyView(" + row.rbo_id + ")'>已驳回</a>";
                                break;
                            case "40":
                                return "<a onclick='VerifyView(" + row.rbo_id + ")'>自动匹配</a>";
                                break;
                            default:
                                return null;
                                break;
                        }
                    }
                    },
                    {
                        field: "optera", title: "操作", width: 80, formatter: function (value, row, index) {
                        if (row.order_no == "") {
                            return '<a id="bindOrder" onclick="BindOrder(\'' + row.rbo_id + '\'' + ',' + '\'' + row.TRANSID + '\'' + ')">绑定订单</a>';
                        }
                        else {
                            if (row.state == 30) {
                                return '<a id="reBind" onclick="BindOrder(\'' + row.rbo_id + '\'' + ')">重新绑定</a>';
                            }
                        }
                    }
                    },
                ]],
                onLoadSuccess: function (data) {
                    datagridTip("#data");
                    showEmpty($(this), data.total, 0);
                    setMenuHeight();
                    if (data.rows.length > 0) {
                        //调用mergeCellsByField()合并单元格
                        mergeCellsByField("data", "state,optera");
                    }
                }
            })
            $('#export').click(function () {
                var index = layer.confirm("确定导出银行流水信息?",
                    {
                        btn: ['確定', '取消'],
                        icon: 2
                    },
                    function () {
                        if (window.location.href = "<?= Url::to(['index', 'export' => 1]) . '&' . http_build_query(Yii::$app->request->queryParams) ?>") {
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
            $("#reset").click(function () {
                window.location.href = "<?=Url::to(['index'])?>";
            })
        })
        function GrabData() {
            $.fancybox({
                href: "<?=Url::to(['grab-data'])?>",
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 400,
                height: 250,
                fitToView: false
            })
        }
        function BindOrder(rbo_id, transid="") {
            if (rbo_id != null) {
                $.ajax({
                    url: "<?=Url::to(['is-batch-or-sign'])?>?rbo_id=" + rbo_id,
                    dataType: "json",
                    type: "get",
                    success: function (data) {
                        if (data == "1" || data == "") {
                            $.fancybox({
                                href: "<?=Url::to(['reveive-order'])?>?transid=" + transid + "&rbo_id=" + rbo_id,
                                type: "iframe",
                                padding: 0,
                                autoSize: false,
                                width: 750,
                                minHeight: 490,
                                fitToView: false
                            })
                        }
                        else if (data == "2") {
                            window.location.href = "<?=Url::to(['batch-reject-view'])?>?rbo_id=" + rbo_id;
                        }
                    }
                })

            }
        }
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
                        CurTxt = tTable.datagrid("getRows")[i]['rbo_id'];
                    }
                    if (PerTxt == CurTxt) {
                        if (CurTxt != null) {
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
        function VerifyView(rbo_id) {
            if (rbo_id != null) {
                $.ajax({
                    url: "<?=Url::to(['is-batch-or-sign'])?>?rbo_id=" + rbo_id,
                    dataType: "json",
                    type: "get",
                    success: function (data) {
                        if (data == "1") {
                            window.location.href = "<?=Url::to(['one-view'])?>?rbo_id=" + rbo_id;
                        }
                        else if (data == "2") {
                            window.location.href = "<?=Url::to(['batch-view'])?>?rbo_id=" + rbo_id;
                        }
                    }
                })

            }
        }
    </script>
