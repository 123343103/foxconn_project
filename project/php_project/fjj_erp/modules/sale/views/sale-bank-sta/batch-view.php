<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2018/2/7
 * Time: 上午 11:27
 */
use yii\helpers\Url;
$this->title="多笔流水号审核详情";
$this->params['homeLike']=['label'=>'销售管理'];
$this->params['breadcrumbs'][]=['label'=>'收款绑定订单','url'=>'index'];
$this->params['breadcrumbs'][]=['label'=>'收款审核'];
?>
<div class="content">
    <h2 class="head-first">收款审核</h2>
    <h2 class="head-second">流水详情:</h2>
    <div style="width: 100%;overflow-x: auto">
        <table class="table" style="width:200%">
            <thead>
            <th style="width: 3%;">序号</th>
            <th style="width: 11%;">流水号</th>
            <th style="width: 10%;">订单号</th>
            <th style="width: 10%;">客户名称</th>
            <th style="width: 6%;">订单金额</th>
            <th style="width: 6%;">收款金额</th>
            <th style="width: 8%;">发生时间</th>
            <th style="width: 8%;">银行</th>
            <th style="width: 8%;">银行账户</th>
            <th style="width: 8%;">对方账户</th>
            <th style="width: 15%;">摘要</th>
            <th style="width: 15%;">说明</th>
            </thead>
            <tbody>
            <?php for ($i=0;$i<count($data);$i++) { ?>
            <tr>
                <td style="word-break: break-all"><?=$i+1?></td>
                <td style="word-break: break-all"><?=$data[$i]['TRANSID']?></td>
                <td style="word-break: break-all"><?=$data[$i]['ord_no']?></td>
                <td style="word-break: break-all"><?=$data[$i]['OPPNAME']?></td>
                <td style="word-break: break-all"><?=$data[$i]['stag_cost']?></td>
                <td style="word-break: break-all"><?=$data[$i]['TXNAMT']?></td>
                <td style="word-break: break-all"><?=$data[$i]['TRDATE']?></td>
                <td style="word-break: break-all"><?=$data[$i]['BRANCH_NME']?></td>
                <td style="word-break: break-all"><?=$data[$i]['ACCOUNTS']?></td>
                <td style="word-break: break-all"><?=$data[$i]['OPPACCNO']?></td>
                <td style="word-break: break-all"><?=$data[$i]['INTERINFO']?></td>
                <td style="word-break: break-all"><?=$data[$i]['remark']?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top: 30px;">
        <h2 class="head-second">签核流程:</h2>
        <div id="record"></div>
    </div>
</div>
<script>
    $(function () {
        //审核记录详情
        $("#record").datagrid({
            url: "<?= Url::to(['/system/verify-record/load-record']);?>?id=" + <?= $vco_id ?>,
            rownumbers: true,
            method: "get",
            idField: "vcoc_id",
            loadMsg: false,
//                    pagination: true,
            singleSelect: true,
//                    pageSize: 5,
//                    pageList: [5, 10, 15],
            columns: [[
                {
                    field: "verifyOrg", title: "审核节点", width: 150
                },
                {field: "verifyName", title: "审核人", width: 150},
                {
                    field: "vcoc_datetime", title: "审核时间", width: 156
                },
                {field: "verifyStatus", title: "操作", width: 150},
                {
                    field: "vcoc_remark", title: "审核意见", width: 200
                },
                {
                    field: "vcoc_computeip", title: "审核IP", width: 150
                },

            ]],
            onLoadSuccess: function (data) {
                datagridTip('#record');
                showEmpty($(this), data.total, 0);
            }
        });
    })
</script>

