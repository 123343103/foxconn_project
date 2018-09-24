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
    <p class="head">客户需求单列表</p>
    <div class="float-right">
        <?= Menu::isAllow('/sale/sale-cust-order/index','btn_add') ?
            "<a href='" . Url::to(['create']) . "'>
                                    <div style='height: 23px;width: 100px;float: left;'>
                    <p class='add-item-bgc ' style='float: left'></p>
                    <p style='font-size: 14px;margin-top: 2px;'>&nbsp;新增需求单</p>
                </div>
            </a>
            <p style='float: left;'>&nbsp;|&nbsp;</p>"
            : '' ?>
        <?= Menu::isAllow('/sale/sale-cust-order/index','btn_mody') ?
            '<a id="update" class="hiden">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="update-item-bgc " style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>'
            : '' ?>
        <?= Menu::isAllow('/sale/sale-cust-order/index','btn_quote') ?
            '<a id="quoted" class="hiden">
            <div style="height: 23px;width: 75px;float: left;">
                <p class="to-price-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;转报价</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>'
            : '' ?>
        <?= Menu::isAllow('/sale/sale-cust-order/index','btn_cancle') ?
            '<a id="cancel-order" class="hiden">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="cancel-order-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;取消</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>'
            : '' ?>
        <?= Menu::isAllow('/sale/sale-cust-order/index','btn_detail') ?
            "<a href='" . Url::to(['list']) . "'>
            <div style='height: 23px;width: 85px;float: left;'>
                <p class='details-item-bgc' style='float: left'></p>
                <p style='font-size: 14px;margin-top: 2px;'>&nbsp;明细列表</p>
            </div>
            </a>
            <p style='float: left;'>&nbsp;|&nbsp;</p>"
            : '' ?>
        <?= Menu::isAllow('/sale/sale-cust-order/index','btn_export') ?
            '<a id="export">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="export-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>'
            : '' ?>
        <a href="<?= Url::home() ?>">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="return-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
            </div>
        </a>
    </div>
</div>
