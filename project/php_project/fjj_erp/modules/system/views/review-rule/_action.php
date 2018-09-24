<?php
/**
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2016/9/13
 * Time: 10:09
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="table-head mt-20">
<!--    <p class="head">用户列表信息</p>-->
    <p class="float-right">
        <?= Html::a("<span class='text-center ml--5'>审核流</span>", null,['id'=>'createRule']) ?>
        <?= Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'edit']) ?>
        <?= Html::a("<span class='text-center ml--5'>查看</span>", null,['id'=>'view']) ?>
        <?= Html::a("<span class='text-center ml--5'>删除</span>", null,['id'=>'deletion']) ?>
    </p>
</div>
<div class="space-20"></div>
<!--    <div class="table-head">-->
<!--        <p class="head">-->
<!--        </p>-->
<!--        <p class="float-right">-->
<!--            <a href="--><?//= Url::to(['/ptdt/product-dvlp/add']) ?><!--"> <span>新增</span></a><a-->
<!--                id="edit"><span>修改</span></a><a id="view"><span>查看</span></a><a><span>关闭</span></a>-->
<!--        </p>-->
<!--    </div>-->
