<?php
/**
 * User: F1676624
 * Date: 2017/6/19
 */
use \app\classes\Menu;
use yii\helpers\Url;
$actionId = Yii::$app->controller->action->id;
?>
<div class="table-head">
    <p class="head">出货通知单列表</p>
    <div class="float-right">
    <?= Menu::isAction('/warehouse/shipment-notify/create-pick') ?
        '<a id="create-pick" class="display-none">
            <div style="height: 23px;width: 95px;float: left;">
                <p class="add-item-bgc " style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;生成拣货单</p>
            </div>
        </a>'
    : '' ?>
        <span class='display-none' style='float: left;'>&nbsp;|&nbsp;</span>
    <?= Menu::isAction('/warehouse/shipment-notify/cancel-notify') ?
        '<a id="cancel-notify" class="display-none">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="setting11" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;取消</p>
            </div>
        </a>'
        : '' ?>
        <span class='display-none' style='float: left;'>&nbsp;|&nbsp;</span>
    <a id="export">
        <div style="height: 23px;width: 55px;float: left;">
            <p class="export-item-bgc" style="float: left"></p>
            <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
        </div>
    </a>
        <span style='float: left;'>&nbsp;|&nbsp;</span>
    <a id="return" href="<?= Url::to(['/index/index']) ?>">
        <div style="height: 23px;width: 55px;float: left">
            <p class="return-item-bgc" style="float: left;"></p>
            <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
        </div>
    </a>
    </div>
</div>
