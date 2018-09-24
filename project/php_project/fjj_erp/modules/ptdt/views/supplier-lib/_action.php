<?php
/**
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2016/10/13
 * Time: 8:55
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="table-head">
    <p class="head">供应商申请列表</p>

    <div class="float-right">
        <?=
        Html::a("<div class='icon-btn'> 
        <p class='add-item-bgc  float-left ml-4 '></p>
        <p class='text-btn border-right'>&nbsp;新增</p>
        </div>", null,['id'=>'create'])
        ?>
        <?=
        Html::a("<div class='icon-btn'>
        <p class='update-item-bgc  float-left ml-4 '></p>
        <p class='text-btn border-right'>&nbsp;修改</p>
        </div>", null,['id'=>'edit'])
        ?>
        <?=
        Html::a("<div class='icon-btn'>
        <p class='details-item-bgc  float-left ml-4 '></p>
        <p class='text-btn border-right'>&nbsp;查看</p>
        </div>", null,['id'=>'view'])
        ?>
        <?=
        Html::a("<div class='icon-btn'>
        <p class='delete-row-item-bgc  float-left ml-4 ' style='background-color: #1f7ed0'></p>
        <p class='text-btn'>&nbsp;删除</p>
        </div>", null,['id'=>'delete'])
        ?>
    </div>
</div>
<div class="space-10"></div>
