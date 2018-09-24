<?php
/**
 * User: F1678086
 * Date: 2017/2/23
 */
use app\classes\Menu;
use yii\helpers\Url;
?>
<div class="table-head">
    <p class="head">计划查询列表</p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-visit-plan/create') ? "<a id='create'>
                <div class='table-nav'>
                    <p class='add-item-bgc float-left'></p>
                    <p class='nav-font'>新增</p>
                </div>
            </a>"
            : '' ?>
        <p class="float-left">&nbsp;|&nbsp;</p>
        <?= Menu::isAction('/crm/crm-visit-plan/update') ? "<a id='update' style='display:none;'>
                <div class='table-nav'>
                    <p class='update-item-bgc float-left'></p>
                    <p class='nav-font'>修改</p>
                </div>
            </a>"
            : '' ?>
        <p class="float-left" id="update1" style='display:none;'>&nbsp;|&nbsp;</p>
        <?= Menu::isAction('/crm/crm-visit-plan/cancel') ? "<a id='cancel' style='display:none;'>
                <div class='table-nav'>
                    <p class=' setting11  float-left'></p>
                    <p class='nav-font'>取消计划</p>
                </div>
            </a>"
            : '' ?>
        <p class="float-left" id="cancel1" style='display:none;'>&nbsp;|&nbsp;</p>
        <?= Menu::isAction('/crm/crm-visit-plan/stop') ? "<a id='stop' style='display:none;'>
                <div class='table-nav'>
                    <p class=' setting12  float-left'></p>
                    <p class='nav-font'>终止计划</p>
                </div>
            </a>"
            : '' ?>
        <p class="float-left" id="stop1" style='display:none;'>&nbsp;|&nbsp;</p>
        <?= Menu::isAction('/crm/crm-visit-record/add') ? "<a id='add-visit-record' style='display:none;'>
                <div class='table-nav'>
                       <p class='setting13 float-left'></p>
                    <p class='nav-font'>添加拜访记录</p>
                </div>
            </a>"
            : '' ?>
        <p class="float-left" id="add-visit-record1" style='display:none;'>&nbsp;|&nbsp;</p>
        <?= Menu::isAction('/crm/crm-visit-plan/export') ? "<a id='export'>
                <div class='table-nav'>
                       <p class='export-item-bgc float-left'></p>
                    <p class='nav-font'>导出</p>
                </div>
            </a>"
            : '' ?>
        <p class="float-left">&nbsp;|&nbsp;</p>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>

    </div>
</div>

