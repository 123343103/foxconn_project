<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/9/22
 * Time: 19:49
 */

use yii\helpers\Url;
use \app\classes\Menu;
use \yii\helpers\Html;
?>

<div class="table-head">
    <p class="head">厂商计划列表</p>
    <div class="float-right">
        <?= Menu::isAction('/ptdt/visit-plan/add')?
            "<a href='". Url::to(['add']) ."'>
                <div class='table-nav'>
                    <p class='add-item-bgc float-left'></p>
                    <p class='nav-font'>新增</p>
                </div>
            </a>"
            : '' ?>
        <p class="float-left">&nbsp;|&nbsp;</p>
        <?= Menu::isAction('/ptdt/visit-plan/edit')?
            "<a id='edit'>
                <div class='table-nav'>
                    <p class='update-item-bgc float-left'></p>
                    <p class='nav-font'>修改</p>
                </div>
            </a>"
            :'' ?>
        <p class="float-left">&nbsp;|&nbsp;</p>
        <?= Menu::isAction('/ptdt/visit-plan/view')?
            "<a id='view'>
                <div class='table-nav'>
                    <p class='details-item-bgc float-left'></p>
                    <p class='nav-font'>详情</p>
                </div>
            </a>"
            :'' ?>
        <p class="float-left">&nbsp;|&nbsp;</p>
        <?= Menu::isAction('/ptdt/visit-plan/delete')?
            "<a id='delete'>
                <div class='table-nav'>
                    <p class='delete-item-bgc float-left'></p>
                    <p class='nav-font'>删除</p>
                </div>
            </a>"
            :'' ?>
        <p class="float-left">&nbsp;|&nbsp;</p>
        <a id="add_resume">
            <div class='table-nav'>
                <p class='add-item-bgc float-left'></p>
                <p class='nav-font'>新增履历</p>
            </div>
        </a>
        <p class="float-left">&nbsp;|&nbsp;</p>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
</div>

