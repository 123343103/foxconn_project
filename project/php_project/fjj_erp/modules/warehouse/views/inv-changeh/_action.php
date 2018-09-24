<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/7/11
 * Time: 上午 10:10
 */
use yii\helpers\Url;
use yii\helpers\Html;
use \app\classes\Menu;
?>
<!--<div class="float-right">-->
<!--    <p class="float-right">-->
<!--        <a id="add" ><span class='text-center ml--5'>新增</span></a>-->
<!--        <a id="update"><span class='text-center ml--5'>修改</span></a>-->
<!--        <a id="view"><span class='text-center ml--5'>详情</span></a>-->
<!--        <a  id="check"><span class='text-center ml--5'>送审</span></a>-->
<!--        <a id="delete"><span class='text-center ml--5'>删除</span></a>-->
<!--        <a  id="export"><span class='text-center ml--5'>导出</span></a>-->
<!---->
<!--    </p>-->
<div class="float-right">
    <a id="add">
        <div style="height: 23px;width: 55px;float: left;">
            <p class="add-item-bgc " style="float: left"></p>
            <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
        </div>
    </a>
    <p style="float: left;">&nbsp;|&nbsp;</p>
    <a id="update">
        <div style="height: 23px;width: 55px;float: left;">
            <p class="update-item-bgc " style="float: left"></p>
            <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
        </div>
    </a>
    <p style="float: left;">&nbsp;|&nbsp;</p>
<!--    <a id="view">-->
<!--        <div style="height: 23px;width: 55px;float: left;">-->
<!--            <p class="details-item-bgc" style="float: left"></p>-->
<!--            <p style="font-size: 14px;margin-top: 2px;">&nbsp;详情</p>-->
<!--        </div>-->
<!--    </a>-->
<!--    <p style="float: left;">&nbsp;|&nbsp;</p>-->
    <a id="check">
        <div style="height: 23px;width: 55px;float: left;">
            <p class="submit-item-bgc" style="float: left"></p>
            <p style="font-size: 14px;margin-top: 2px;">&nbsp;送审</p>
        </div>
    </a>
    <p style="float: left;">&nbsp;|&nbsp;</p>
    <a id="delete">
        <div style="height: 23px;width: 55px;float: left;">
            <p class="delete-row-item-bgc" style="float: left"></p>
            <p style="font-size: 14px;margin-top: 2px;">&nbsp;作废</p>
        </div>
    </a>
    <p style="float: left;">&nbsp;|&nbsp;</p>
    <a id="export">
        <div style="height: 23px;width: 55px;float: left;">
            <p class="export-item-bgc" style="float: left"></p>
            <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
        </div>
    </a>
    <p style="float: left;">&nbsp;|&nbsp;</p>
    <a id="return">
        <div style="height: 23px;width: 55px;float: left">
            <p class="return-item-bgc" style="float: left;"></p>
            <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
        </div>
    </a>
</div>