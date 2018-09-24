<?php
/**
 * User: F1676624
 * Date: 2017/6/19
 */
use yii\helpers\Url;
use app\classes\Menu;

$actionId = Yii::$app->controller->action->id;
?>

<div class="table-head">
    <p class="head">交易订单列表</p>
    <div class="float-right">
        <?= Menu::isAllow('/sale/sale-trade-order/index', 'btn_purch') ?
            "<a id='purchase-note-bak' style='display: none'>
            <div style='height: 23px;width: 80px;float: left;'>
                <p class='add-item-bgc' style='float: left'></p>
                <p style='font-size: 14px;margin-top: 2px;'>&nbsp;商品请购</p>
            </div>
            </a>
            <p style='float: left;display: none'>&nbsp;|&nbsp;</p>"
             : '' ?>
        <?= Menu::isAllow('/sale/sale-trade-order/index', 'btn_chang_price') ?
            '<a id="reprice" style="display: none">
            <div style="height: 23px;width: 80px;float: left;">
                <p class="add-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;订单改价</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>'
            : '' ?>
        <?= Menu::isAllow('/sale/sale-trade-order/index', 'btn_chang_cancel') ?
            '<a id="reprice-cancel" style="display: none">
            <div style="height: 23px;width: 80px;float: left;">
                <p class="add-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;取消改价</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>'
            : '' ?>
        <?= Menu::isAllow('/sale/sale-trade-order/index', 'btn_notice') ?
            '<a id="out-note" style="display: none">
            <div style="height: 23px;width: 80px;float: left;">
                <p class="add-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;出货通知</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>'
            : '' ?>
        <?= Menu::isAllow('/sale/sale-trade-order/index', 'btn_invoice') ?
//            '<a id="fapiao">
//            <div style="height: 23px;width: 80px;float: left;">
//                <p class="add-item-bgc" style="float: left"></p>
//                <p style="font-size: 14px;margin-top: 2px;">&nbsp;发票申请</p>
//            </div>
//        </a>
//        <p style="float: left;">&nbsp;|&nbsp;</p>'
            '' : '' ?>
        <?= Menu::isAllow('/sale/sale-trade-order/index', 'btn_pay') ?
            '<a id="pay" style="display: none">
            <div style="height: 23px;width: 80px;float: left;">
                <p class="add-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;支付确认</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>'
            : '' ?>
        <?= Menu::isAllow('/sale/sale-trade-order/index', 'btn_cancle') ?
            '<a id="cancel" style="display: none">
            <div style="height: 23px;width: 80px;float: left;">
                <p class="add-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;取消订单</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>'
            : '' ?>
        <?= Menu::isAllow('/sale/sale-trade-order/index', 'btn_detail') ?
            "<a href='" . Url::to(['detail-list']) . "'>
            <div style='height: 23px;width: 95px;float: left;'>
                <p class='add-item-bgc' style='float: left'></p>
                <p style='font-size: 14px;margin-top: 2px;'>&nbsp;订单明细表</p>
            </div>
            </a>
            <p style='float: left;'>&nbsp;|&nbsp;</p>"
            : '' ?>
        <!--        <a><span>导出</span></a>-->
        <?= Menu::isAllow('/sale/sale-trade-order/index', 'btn_export') ?
            '<a id="export">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="add-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>'
            : '' ?>
        <a href="<?= Url::home() ?>">
            <div class='icon-btn'>
                <p class='return-item-bgc float-left ml-4'></p>
                <p class='text-btn'>&nbsp;返回</p>
            </div>
        </a>
    </div>
</div>
