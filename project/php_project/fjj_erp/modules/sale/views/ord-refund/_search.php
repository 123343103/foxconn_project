<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/1
 * Time: 11:45
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
?>
<style>
    .qlabel-width{
        width:80px;
    }
    .qvalue-width{
        width:120px;
    }
    .ml-35{
        margin-left:35px;
    }
    .space-30{
        width:100%;
        height:30px;
    }
</style>
<div class="crm-customer-info-search">

    <?php $form = ActiveForm::begin([
        'action' => 'index',
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">退款单号</label><label>：</label>
                <input type="text" name="OrdRefundSearch[refund_no]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['OrdRefundSearch']['refund_no'] ?>">
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">单据状态</label><label>：</label>
                <select name="OrdRefundSearch[rfnd_status]" class="qvalue-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["refund_status"] as $key => $val) { ?>
                        <option
                                value="<?= $key ?>" <?= isset($queryParam['OrdRefundSearch']['rfnd_status']) && $queryParam['OrdRefundSearch']['rfnd_status'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">关联订单号</label><label>：</label>
                <input type="text" name="OrdRefundSearch[ord_no]" class="qvalue-width qvalue-align" value="<?= $queryParam['OrdRefundSearch']['ord_no'] ?>">
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">订单状态</label><label>：</label>
                <select name="OrdRefundSearch[order_status]" class="qvalue-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["order_status"] as $key => $val) { ?>
                        <option
                                value="<?= $key ?>" <?= isset($queryParam['OrdRefundSearch']['order_status']) && $queryParam['OrdRefundSearch']['order_status'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">订单类型</label><label>：</label>
                <select name="OrdRefundSearch[ord_type]" class="qvalue-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["orderType"] as $key => $val) { ?>
                        <option
                                value="<?= $val["business_type_id"] ?>" <?= isset($queryParam['OrdRefundSearch']['ord_type']) && $queryParam['OrdRefundSearch']['ord_type'] == $val["business_type_id"] ? "selected" : null ?>><?= $val["business_value"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">客户名称</label><label>：</label>
                <input type="text" name="OrdRefundSearch[cust_sname]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['OrdRefundSearch']['cust_sname'] ?>">
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">负责人</label><label>：</label>
                <input type="text" name="OrdRefundSearch[manager]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['OrdRefundSearch']['manager'] ?>">
            </div>
            <?= Html::submitButton('查询', ['class' => 'button-blue ml-35 search-btn-blue', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to([Yii::$app->controller->action->id]) . '\'']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>
</div>
