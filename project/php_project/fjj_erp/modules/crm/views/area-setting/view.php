<?php
use yii\helpers\Url;
use \app\classes\Menu;
use \yii\helpers\Html;
$this->title = '营销区域详情';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '营销区域列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
//\app\classes\Menu::isAction('/crm/area-setting/update') ? \yii\helpers\Html::button('修改', ['class' => 'button-blue', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $model['staff_code'] . '\'']) : ''
?>
<style xmlns="http://www.w3.org/1999/html">
    .width100{
        width: 100px;
    }
</style>
<div class="content">
    <h1 class="head-first">营销区域详情</h1>

    <div class="border-bottom mb-10  pb-10">
        <?php if($model["csarea_status"]==20){?>
            <?= Html::button('修改', ['class' => 'button-mody', 'onclick' => 'window.location.href=\'' . Url::to(["update","id"=>$model["csarea_id"]]) . '\'']) ?>
        <?php } ?>
        <?= Html::button('切换列表', ['class' => 'button-change', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100">区域代码<label>：</label></label>
        <label class="label-width text-left"><?= $model["csarea_code"] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100">区域名称<label>：</label></label>
        <label class="label-width text-left"><?= $model["csarea_name"] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100">包含地区<label>：</label></label>
        <?php foreach ($arr as $key => $val) { ?>
                    <?php if ($key == 0) { ?>
                <label class="label-width text-left"><?= $val ?></label></br>
                    <?php } else { ?>
                <label class="label-width text-left" style="margin-left: 103px"><?= $val ?></label></br>
                    <?php } ?>

                </tr>
        <?php } ?>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100">状态<label>：</label></label>
        <label class="label-width text-left"> <?= $model["csarea_status"] == 20 ? '启用' : '禁用' ?></label>
    </div>
<!--    <table width="90%" class="no-border vertical-center ml-25 mb-20">-->
<!--        <tr class="no-border">-->
<!--            <td width="4%" class="no-border vertical-center"></td>-->
<!--            <td class="no-border vertical-center" width="5%">区域代码:</td>-->
<!--            <td class="no-border vertical-center" width="30%">--><?//= $model["csarea_code"] ?><!--</td>-->
<!--        </tr>-->
<!--    </table>-->
<!--    <table width="90%" class="no-border vertical-center ml-25 mb-20">-->
<!--        <tr class="no-border">-->
<!--            <td width="4%" class="no-border vertical-center"></td>-->
<!--            <td class="no-border vertical-center" width="5%">区域名称:</td>-->
<!--            <td class="no-border vertical-center" width="30%">--><?//= $model["csarea_name"] ?><!--</td>-->
<!--        </tr>-->
<!--    </table>-->
<!--    --><?php //foreach ($arr as $key => $val) { ?>
<!--        <table width="90%" class="no-border vertical-center ml-25 mb-20">-->
<!--            <tr class="no-border">-->
<!--                --><?php //if ($key == 0) { ?>
<!--                    <td width="4%" class="no-border vertical-center"></td>-->
<!--                    <td class="no-border vertical-center" width="5%">包含地区:</td>-->
<!--                --><?php //} else { ?>
<!--                    <td width="9%" class="no-border vertical-center"></td>-->
<!--                --><?php //} ?>
<!--                <td class="no-border vertical-center" width="30%">--><?//= $val ?><!--</td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    --><?php //} ?>
<!--    <table width="90%" class="no-border vertical-center ml-25 mb-20">-->
<!--        <tr class="no-border">-->
<!--            <td width="4%" class="no-border vertical-center"></td>-->
<!--            <td class="no-border vertical-center" width="5%">状态:</td>-->
<!--            <td class="no-border vertical-center" width="30%">--><?//= $model["csarea_status"] == 20 ? '启用' : '禁用' ?><!--</td>-->
<!--        </tr>-->
<!--    </table>-->
</div>
