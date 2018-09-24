<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\JeDateAsset;

JeDateAsset::register($this);
$queryParam = Yii::$app->request->queryParams['OrdDelRptSearch'];
?>

<div class="crm-customer-info-search">

    <?php $form = ActiveForm::begin([
        'action' => [Yii::$app->controller->action->id],
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">营销区域               </label><label>：</label>
               <select name="OrdDelRptSearch[csarea_name]"                     class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList['saleArea'] as                         $key => $val) {?>
                        <option value="<?=$val['csarea_id'] ?>" <?= isset($queryParam['csarea_name'])&& $queryParam['csarea_name']==$val['csarea_id']?"selected":null ?>><?= $val['csarea_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">订单状态</label><label>：</label>
                <select name="OrdDelRptSearch[ord_status]" class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["ordStatus"] as $key => $val) { ?>
                        <option
                            value="<?= $val['os_id'] ?>" <?= isset($queryParam['ord_status']) && $queryParam['ord_status'] == $val['os_id'] ? "selected" : null ?>><?= $val['os_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">销售部门</label><label>：</label>
                <select name="OrdDelRptSearch[cust_department]" class="value-width qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach (array_combine(array_column($department,"organization_code"),array_column($department,"organization_name")) as $key => $val){ ?>
                        <option value="<?= $key ?>" <?= isset($queryParam['cust_department']) && $queryParam['cust_department'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">商品分类</label><label>：</label>
                <select name="OrdDelRptSearch[catg_id]" class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["catgName"] as $key => $val) { ?>
                        <option
                            value="<?= $val["catg_id"] ?>" <?= isset($queryParam['catg_id']) && $queryParam['catg_id'] == $val["catg_id"] ? "selected" : null ?>><?= $val["catg_name"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">商品规格/型号</label><label>：</label>
                <input type="text" name="OrdDelRptSearch[tp_spec]" class="value-width qvalue-align"
                       value="<?= $queryParam['tp_spec'] ?>">
            </div>

            <div class="inline-block">
                <label class="label-width qlabel-align">销售人员</label><label>：</label>
                <input type="text" name="OrdDelRptSearch[staff_name]" class="value-width qvalue-align"
                       value="<?= $queryParam['staff_name'] ?>">
            </div>


            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-25', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-25', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to([Yii::$app->controller->action->id]) . '\'']) ?>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">客户</label><label>：</label>
                <input type="text" name="OrdDelRptSearch[cust_shortname]" class="value-width qvalue-align"
                       value="<?= $queryParam['cust_shortname'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">订单号</label><label>：</label>
                <input type="text" name="OrdDelRptSearch[ord_no]" class="value-width qvalue-align"
                       value="<?= $queryParam['ord_no'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">下单时间</label><label>：</label>
                <input type="text" name="OrdDelRptSearch[start_date]" class="value-width qvalue-align" id="start_time"
                       onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate: '#F{$dp.$D(\'end_time\');}'})"
                       value="<?= $queryParam['start_date'] ?>">
                <label>至</label><label>：</label>
                <input type="text" class="value-width qvalue-align" name="OrdDelRptSearch[end_date]" id="end_time"
                       onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '#F{$dp.$D(\'start_time\');}'})"
                       value="<?= $queryParam['end_date'] ?>">
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>
</div>
