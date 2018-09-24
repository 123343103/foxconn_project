<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/13
 * Time: 17:10
 */
use yii\helpers\Url;
use yii\helpers\Html;
use \app\classes\Menu;

?>

<div class="table-head">
    <p class="head">客户代码申请列表</p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-customer-apply/customer-info') ?
            "<a id=\"update\" style=\"display: none;\">
            <div style=\"height: 23px;width: 55px;float: left;\">
            <p class=\"update-item-bgc\" style=\"float: left;\"></p>
            <p style=\"font - size: 14px;margin - top: 2px;\">修改</p>
            </div>
        </a>"
            : '' ?>
        <?= Menu::isAction('/system/verify-record/new-reviewer') ?
            "<a id=\"check\" style=\"display:none\">
            <div style=\"height: 23px;width: 55px;float: left;\">
                <p class=\"submit-item-bgc\" style=\"float: left;\"></p>
                <p style=\"font-size: 14px;margin-top: 2px;\">送审</p>
            </div>
        </a>"
            : '' ?>
        <?= Menu::isAction('/crm/crm-customer-apply/cannel') ?
            "<a id=\"cannel\" style=\"display:none\">
            <div style=\"height: 23px;width: 55px;float: left;\">
                <p class=\"icon-minus-sign  fs-18\" style=\"float: left;color:#1e7fd0\"></p>
                <p style=\"font-size: 14px;margin-top: 2px;\">取消</p>
            </div>
        </a>"
            : '' ?>
        <!--<a id="view">
            <div style="height: 23px;width: 55px;float: left;">
            <p class="details-item-bgc" style="float: left;"></p>
            <p style="font-size: 14px;margin-top: 2px;">详情</p>
            </div>
        </a>-->
        <?= Menu::isAction('/crm/crm-customer-apply/export') ?
            "<a id=\"export\">
            <div style=\"height: 23px;width: 55px;float: left;\">
                <p class=\"export-item-bgc\" style=\"float: left;\"></p>
                <p style=\"font-size: 14px;margin-top: 2px;\">导出</p>
            </div>
        </a>"
            : '' ?>
        <?= Html::a("<p class='return-item-bgc' style=\"float: left;\"></p><p style=\"font-size: 14px;margin-top: 2px;\">返回</p>", Url::to(['/index/index'])) ?>

    </div>
</div>