<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/10/10
 * Time: 上午 11:49
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<div class="table-head">
    <!--<p class="head">岗位信息</p>-->
    <div class="float-right">
        <a id="create" href="<?=Url::to(['create'])?>">
            <div class='table-nav'>
                <p class='add-item-bgc float-left'></p>
                <p class='nav-font'>新增</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>
        <a id="update">
            <div class='table-nav'>
                <p class='update-item-bgc float-left'></p>
                <p class='nav-font'>修改</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>
        <a id="deletion">
            <div class='table-nav'>
                <p class='delete-item-bgc float-left'></p>
                <p class='nav-font'>删除</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>
        <a id="deletion" href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
    <p></p>
</div>







