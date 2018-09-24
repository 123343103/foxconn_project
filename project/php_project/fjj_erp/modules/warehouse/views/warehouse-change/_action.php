<?php
/**
 * User: F1676624
 * Date: 2017/7/22
 */
use yii\helpers\Url;
use yii\helpers\Html;
use \app\classes\Menu;

?>
<div class="table-head">
    <p class="head"><?= $this->title ?></p>
    <div class="float-right">
        <a id="add">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="add-item-bgc " style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>
        <a id="update" style="display: none">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="update-item-bgc " style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
            </div>
        </a>
        <p style="float: left; display: none">&nbsp;|&nbsp;</p>
<!--        <a id="view">-->
<!--            <div style="height: 23px;width: 55px;float: left;">-->
<!--                <p class="details-item-bgc" style="float: left"></p>-->
<!--                <p style="font-size: 14px;margin-top: 2px;">&nbsp;详情</p>-->
<!--            </div>-->
<!--        </a>-->
<!--        <p style="float: left;">&nbsp;|&nbsp;</p>-->
        <a id="check" style="display: none">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="submit-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;送审</p>
            </div>
        </a>
        <p style="float: left; display: none">&nbsp;|&nbsp;</p>
        <a id="delete" style="display: none">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="delete-row-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;删除</p>
            </div>
        </a>
        <p style="float: left; display: none">&nbsp;|&nbsp;</p>
        <a id="inware_btn" style="display: none">
            <div style="height: 23px;width: 100px;float: left;">
                <p class="details-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">移仓入库通知</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>
        <a id="export">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="export-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>
        <?= Html::a('<div style="height: 23px;width: 55px;float: left">
                    <p class="return-item-bgc" style="float: left;"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                </div>', Url::to(['/index/index'])) ?>

    </div>
</div>