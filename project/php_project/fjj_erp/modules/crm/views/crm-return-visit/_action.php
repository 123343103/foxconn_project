<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/23
 * Time: 19:49
 */

use yii\helpers\Url;
use yii\helpers\Html;
use app\classes\Menu;
?>

<div class="table-head">
    <p class="head">会员列表</p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-return-visit/create')?
    //        Html::a("<span>新增</span>",null, ['id' => 'create'])
            "<a href='".Url::to(['create'])."'>
                        <div class='table-nav'>
                            <p class='add-item-bgc float-left'></p>
                            <p class='nav-font'>新增</p>
                        </div>
                    </a>"
            :'' ?>

<!--         Menu::isAction('/crm/crm-return-visit/update')?-->
<!--    //        Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update','class'=>'display-none'])-->
<!--            "<a id='update' class='display-none'>-->
<!--                    <p class=\"float-left\">&nbsp;|&nbsp;</p>-->
<!--                    <div class='table-nav'>-->
<!--                        <p class='update-item-bgc float-left'></p>-->
<!--                        <p class='nav-font'>修改</p>-->
<!--                    </div>-->
<!--                </a>"-->
<!--            :'' -->
<!---->
<!---->
<!--        Menu::isAction('/crm/crm-return-visit/delete')?-->
<!--    //        Html::a("<span class='text-center ml--5'>刪除</span>", null,['id'=>'delete','class'=>'display-none'])-->
<!--            "<a id='delete' class='display-none'>-->
<!--                        <p class=\"float-left\">&nbsp;|&nbsp;</p>-->
<!--                        <div class='table-nav'>-->
<!--                            <p class='delete-item-bgc float-left'></p>-->
<!--                            <p class='nav-font'>删除</p>-->
<!--                        </div>-->
<!--                    </a>"-->
<!--            :'' -->
        <p class="float-left">&nbsp;|&nbsp;</p>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
</div>

