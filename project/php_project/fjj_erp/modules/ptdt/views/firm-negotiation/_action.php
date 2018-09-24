<?php
/**
 * User: F3859386
 * Date: 2016/10/13
 * Time: 8:55
 */
use yii\helpers\Html;
use app\classes\Menu;
?>

<div class="table-head">
    <p class="head">厂商谈判履历主表</p>
    <p class="float-right">
        <?= Menu::isAction('/ptdt/firm-negotiation/create')?Html::a("<span class='text-center ml--5'>新增</span>", null,['id'=>'create']):'' ?>
        <?= Menu::isAction('/ptdt/firm-negotiation/update')?Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update','class'=>'display-none']):'' ?>
        <?= Menu::isAction('/ptdt/firm-negotiation/delete')?Html::a("<span class='text-center ml--5'>删除</span>", null,['id'=>'delete','class'=>'display-none']):'' ?>
        <?= Menu::isAction('/ptdt/firm-negotiation/view')?Html::a("<span class='text-center ml--5'>详情</span>", null,['id'=>'view','class'=>'display-none']):'' ?>
        <?= Html::a("<span class='text-center ml--5'>谈判完成</span>", null,['id'=>'nego','class'=>'display-none']) ?>
        <?= Html::a("<span class='text-center ml--5'>新增呈报</span>", null,['id'=>'report','class'=>'display-none']) ?>
        <?= Menu::isAction('/ptdt/firm-negotiation/analysis')?Html::a("<span class='text-center ml--5'>谈判分析表</span>", null,['id'=>'analysis','class'=>'display-none']):'' ?>
        <?= Html::a("<span class='text-center ml--5'>返回</span>", \yii\helpers\Url::to(['/index/index'])) ?>
    </p>
</div>
<div class="space-10"></div>
