<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\classes\Menu;

$dealData = Menu::isAction('/crm/crm-investment-dvelopment/assign-staff') || Menu::isAction('/crm/crm-investment-dvelopment/throw-sea') || Menu::isAction('/crm/crm-investment-dvelopment/turn-sales') || Menu::isAction('/crm/crm-investment-dvelopment/import') || Menu::isAction('/crm/crm-investment-dvelopment/export');
$selected = Menu::isAction('/crm/crm-investment-dvelopment/assign-staff') || Menu::isAction('/crm/crm-investment-dvelopment/throw-sea') || Menu::isAction('/crm/crm-investment-dvelopment/turn-sales');
$disSelected = Menu::isAction('/crm/crm-investment-dvelopment/import') || Menu::isAction('/crm/crm-investment-dvelopment/export');
$disOrhide = $disSelected ? "" : "displayOrnot";
?>
<style>
    .wd-tc-10 {
        width: 10px;
        text-align: center;
    }
    .displayOrnot {
        display: none;
    }
</style>
<div class="table-head">
    <p class="head">招商会员开发列表</p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-investment-dvelopment/create') ?
//            Html::a("<span class='text-center ml--5'>新增</span>",Url::to(['create']), ['id' => 'create'])
            "<a id='create' href='" . Url::to(['create']) . "'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                </a><span class='float-left wd-tc-10'>|</span>"
            : '' ?>
        <?= Menu::isAction('/crm/crm-investment-dvelopment/update') ?
//            Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update'])
            "<a id='update' class='display-none'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
                </a><span class='display-none float-left wd-tc-10'>|</span>"
            : '' ?>

        <?= Menu::isAction('/crm/crm-investment-dvelopment/visit-create') ?
//            Html::a("<span class='text-center ml--5'>回访</span>", null, ['id' => 'backVisit'])
            "<a id='add-visit-record' class='display-none'>
                    <div class='table-nav'>
                        <p class='setting1 float-left'></p>
                        <p class='nav-font'>新增拜访记录</p>
                    </div>
                </a><span class='float-left wd-tc-10 display-none'>|</span>"
            : '' ?>

        <?= Menu::isAction('/crm/crm-investment-dvelopment/shop-info') ?
            "<a id='shopInfo' class='display-none'>
                    <div class='table-nav'>
                        <p class='setting6 float-left'></p>
                        <p class='nav-font'>开店信息录入</p>
                    </div>
                </a><span class='float-left wd-tc-10 display-none'>|</span>"
            : ''; ?>

        <?= Menu::isAction('/crm/crm-investment-dvelopment/reminders') ?
//            Html::a("<span class='width-70 text-center ml--5'>提醒事项</span>", null, ['id' => 'reminders'])
            "<a id='reminders' class='display-none'>
                    <div class='table-nav'>
                        <p class='setbcg2 float-left'></p>
                        <p class='nav-font'>提醒事项</p>
                    </div>
                </a><span class='float-left wd-tc-10 display-none'>|</span>"
            : '' ?>
        <?= Menu::isAction('/crm/crm-investment-dvelopment/send-message') ?
//            Html::a("<span class='text-center ml--5'>回访</span>", null, ['id' => 'backVisit'])
            "<div id='m1' class='float-left'>
                <a href='javascript:void(0)' class='text-center ml--5 width-90'>
                    <div class='table-nav'>
                        <p class='setbcg6 float-left'></p>
                        <p class='nav-font nav-font menu-one' id=\"m-send\">即时通讯</p>
                    </div>
                </a><span class='float-left wd-tc-10'>|</span>
              </div>"
            : '' ?>
        <?= $dealData ?
//            Html::a("<span class='text-center ml--5'>回访</span>", null, ['id' => 'backVisit'])
            "<div class=\"float-left $disOrhide\">
                <a href='javascript:void(0)' class='text-center ml--5 width-90'>
                    <div class='table-nav width-80'>
                        <p class='setbcg5 float-left'></p>
                        <p class='nav-font  menu-one' id='m-deal'>数据处理</p>
                    </div>
                </a><span class='float-left wd-tc-10'>|</span>
              </div>"
            : '' ?>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
    <div id="m-data2" class="width-70 hiden">
        <div><a id="sendMessage" class="menu-span"><span>发信息</span></a></div>
        <div><a id="sendEmail" class="menu-span"><span>发邮件</span></a></div>
    </div>
    <div id="m-data" class="width-70 hiden">
        <?= Menu::isAction('/crm/crm-investment-dvelopment/assign-staff') ?
            '<div id="assignStaff" class="display-none"><a class="menu-span"><span>分配员工</span></a></div>' : '';
        ?>
        <?= Menu::isAction('/crm/crm-investment-dvelopment/throw-sea') ?
            '<div id="switch_potential" class="display-none"><a class="menu-span"><span>抛至公海</span></a></div>' : '';
        ?>
        <?= Menu::isAction('/crm/crm-investment-dvelopment/turn-sales') ?
            '<div id="switch_sale" class="display-none"><a class="menu-span"><span>转销售</span></a></div>' : '';
        ?>
        <?= Menu::isAction('/crm/crm-investment-dvelopment/import') ?
            '<div><a id="importDiv" class="menu-span"><span>批量导入</span></a></div>' : '';
        ?>
        <?= Menu::isAction('/crm/crm-investment-dvelopment/export') ?
            '<div><a id="export" class="menu-span"><span>批量导出</span></a></div>' : '';
        ?>
    </div>

    <div class="display-none">
        <div id="import" style="width:500px; height:260px; overflow:auto">
            <div class="pop-head">
                <p>导入数据</p>
            </div>
            <div class="mt-40">
                <?php $form = ActiveForm::begin([
                    'options' => ['enctype' => 'multipart/form-data'],
                    'action' => ['insert-excel'],
                    'id' => 'fileForm',
                    'fieldConfig' => [
                        'errorOptions' => ['class' => 'error-notice mt-10'],
//                            'labelOptions'=>['class'=>'width-100'],
                        'inputOptions' => ['class' => 'width-200']
                    ]
                ]); ?>
                <div class="ml-40">
                    <div class="inline-block field-uploadForm">
                        <input type="hidden" name="UploadForm[file]" value="">
                        <input type="file" id="uploadForm" class="width-200" name="UploadForm[file]">
                    </div>
                    <?= Html::submitButton('确认', ['class' => 'button-blue ml-20', 'id' => 'sub']) ?>&nbsp;
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {

        $('#m-deal').menubutton({
            menu: '#m-data',
            hasDownArrow: false
        });
        $('#m-send').menubutton({
            menu: '#m-data2',
            hasDownArrow: false
        });
        $('.menu-one').removeClass("l-btn l-btn-small l-btn-plain");
        $('.menu-one').find("span").removeClass("l-btn-left l-btn-text");
    });
</script>
