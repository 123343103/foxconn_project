<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/7/12
 * Time: 11:02
 */
use yii\helpers\Url;
use app\classes\Menu;
?>
<div class="table-head">
    <p class="head">账信申请列表</p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-credit-apply/update')?
//            Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update'])
            "<a id='update' class='display-none'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>

        <?= Menu::isAction('/system/verify-record/reviewer')?
//            Html::a("<span class='text-center ml--5'>送审</span>", null,['id'=>'check'])
            "<a id='check' class='display-none'>
                    <div class='table-nav'>
                        <p class='submit-item-bgc float-left'></p>
                        <p class='nav-font'>送审</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-credit-apply/delete')?
//            Html::a("<span class='text-center ml--5'>刪除</span>", null,['id'=>'delete','onclick'=>'cancle()'])
            "<a id='delete' onclick='cancle_apply();' class='display-none'>
                    <div class='table-nav'>
                        <p class='icon-minus-sign icon-large' style='float: left;color:#1e7fd0'></p>
                        <p class='nav-font'>取消</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-credit-apply/export')?
            "<a id='export'>
                <div class='table-nav'>
                    <p class='export-item-bgc float-left'></p>
                    <p class='nav-font'>导出</p>
                </div>
            </a>"
        :'' ?>
        <p class="float-left">&nbsp;|&nbsp;</p>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
</div>