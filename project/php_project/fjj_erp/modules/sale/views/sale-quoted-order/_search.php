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
    .qlabel-width {
        width: 80px;
    }

    .qvalue-width {
        width: 150px;
    }

    .ml-15 {
        margin-left: 15px;
    }

    .space-30 {
        width: 100%;
        height: 30px;
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
                <label class="qlabel-width qlabel-align">报价单号</label><label>：</label>
                <input type="text" name="PriceInfoSearch[price_no]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['PriceInfoSearch']['price_no'] ?>">
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">报价单状态</label><label>：</label>
                <select name="PriceInfoSearch[audit_id]" class="qvalue-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["quoted_status"] as $key => $val) { ?>
                        <option
                            value="<?= $val['audit_id'] ?>" <?= isset($queryParam['PriceInfoSearch']['audit_id']) && $queryParam['PriceInfoSearch']['audit_id'] == $val['audit_id'] ? "selected" : null ?>><?= $val['audit_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">订单编号</label><label>：</label>
                <input type="text" name="PriceInfoSearch[saph_code]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['PriceInfoSearch']['saph_code'] ?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">订单类型</label><label>：</label>
                <select name="PriceInfoSearch[price_type]" class="qvalue-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["orderType"] as $key => $val) { ?>
                        <option
                            value="<?= $val["business_type_id"] ?>" <?= isset($queryParam['PriceInfoSearch']['price_type']) && $queryParam['PriceInfoSearch']['price_type'] == $val["business_type_id"] ? "selected" : null ?>><?= $val["business_value"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">客户名称</label><label>：</label>
                <input type="text" name="PriceInfoSearch[cust_sname]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['PriceInfoSearch']['cust_sname'] ?>">
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">客户代码</label><label>：</label>
                <input type="text" name="PriceInfoSearch[applyno]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['PriceInfoSearch']['applyno'] ?>">
            </div>
            <?= Html::submitButton('查询', ['class' => 'button-blue ml-15 search-btn-blue', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to([Yii::$app->controller->action->id]) . '\'']) ?>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">交易法人</label><label>：</label>
                <select name="PriceInfoSearch[corporate]" class="qvalue-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["corporate"] as $key => $val) { ?>
                        <option
                            value="<?= $val["company_id"] ?>" <?= isset($queryParam['PriceInfoSearch']['corporate']) && $queryParam['PriceInfoSearch']['corporate'] == $val["company_id"] ? "selected" : null ?>><?= $val["company_name"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">付款方式</label><label>：</label>
                <select name="PriceInfoSearch[pac_id]" class="qvalue-width qvalue-align">
                    <option value="">全部</option>
                    <option
                        value="credit-amount" <?= isset($queryParam['PriceInfoSearch']['pac_id']) && $queryParam['PriceInfoSearch']['pac_id'] == 'credit-amount' ? "selected" : null ?>>
                        信用额度支付
                    </option>
                    <?php foreach ($downList["payType"] as $key => $val) { ?>
                        <option
                            value="<?= $val["bsp_id"] ?>" <?= isset($queryParam['PriceInfoSearch']['pac_id']) && $queryParam['PriceInfoSearch']['pac_id'] == $val["bsp_id"] ? "selected" : null ?>><?= $val["bsp_svalue"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">下单时间</label><label>：</label>
                <input type="text" name="PriceInfoSearch[start_date]" class="qvalue-width qvalue-align select-date"
                       id="start_time"
                       value="<?= $queryParam['PriceInfoSearch']['start_date'] ?>"
                       onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate: '#F{$dp.$D(\'end_time\');}' })">
                <label>至</label>
                <input type="text" class="qvalue-width qvalue-align select-date" name="PriceInfoSearch[end_date]"
                       id="end_time"
                       value="<?= $queryParam['PriceInfoSearch']['end_date'] ?>"
                       onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '#F{$dp.$D(\'start_time\');}' })"
                       onfocus="this.blur()">
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>
</div>
