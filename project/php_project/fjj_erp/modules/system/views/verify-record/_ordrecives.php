<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2018/1/17
 * Time: 下午 03:15
 */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = '单笔流水号审核详情';
$this->params['homeLike'] = ['label' => '审核'];
$this->params['breadcrumbs'][] = ['label' => '待审核申请列表', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '收款审核'];
?>
<style>
    #table1 {
        height: 400px;
        width: 500px;
        border: 1px solid #cccccc;
        border-collapse: collapse;
    }

    td {
        height: 30px;
        color: #333333;
        border: 1px solid #cccccc;
        word-break: break-all;
    }
</style>
<div class="content">
    <h2 class="head-first">收款审核</h2>
    <div class="border-bottom mb-10">
        <p>
            <?= Html::button('通过', ['class' => 'button-blue width-80', 'id' => 'pass']) ?>
            <?= Html::button('驳回', ['class' => 'button-blue width-80', 'id' => 'reject']) ?>
            <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?></p>
    </div>
    <h2 class="head-second">流水详情:</h2>
    <div>
        <input type="hidden" value="<?= $vco_id ?>" id="_id">
    </div>
    <div style="width:100%">
        <table id="table1">
            <tr>
                <td style="width: 30%;text-align: right;">流水号：</td>
                <td style="width: 70%;"><?= $oneTransInfo['TRANSID'] ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align: right;">客户名称：</td>
                <td style="width: 70%;"><?= $oneTransInfo['OPPNAME'] ?></td>
            </tr>
            <tr>
                <td style="width:30%;text-align:right;">发生时间：</td>
                <td style="width:70%;"><?= $oneTransInfo['TRDATE'] ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align: right;">对方账户：</td>
                <td style="width: 70%;"><?= $oneTransInfo['OPPACCNO'] ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align: right;">附言：</td>
                <td style="width: 70%;"><?= $oneTransInfo['POSTSCRIPT'] ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align: right;">摘要：</td>
                <td style="width:70%;"><?= $oneTransInfo['INTERINFO'] ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align:right;">订单号：</td>
                <td style="width: 70%;"><?= $orderlist ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align: right;">订单金额：</td>
                <td style="width: 70%;"><?= $orderSum ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align: right;">收款金额：</td>
                <td style="width: 70%;"><?= $oneTransInfo['TXNAMT'] ?></td>
            </tr>
            <tr>
                <td style="width: 30%;text-align:right;">说明：</td>
                <td style="width: 70%;"><?= $remarks ?></td>
            </tr>
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
        //通过
        $("#pass").on("click", function () {
            var idss = $("#_id").val();
            $.fancybox({
                href: "<?=Url::to(['pass-opinion'])?>?id=" + idss,
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });
        });
        //驳回
        $("#reject").on("click", function () {
            var idss = $("#_id").val();
            $.fancybox({
                href: "<?=Url::to(['opinion'])?>?id=" + idss,
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });
        });
    })
</script>

