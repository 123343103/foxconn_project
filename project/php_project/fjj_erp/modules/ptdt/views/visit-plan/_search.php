<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>
<div class="mb-10">
    <div class="inline-block field-pdvisitplansearch-pvp_plancode">
        <label for="pdvisitplansearch-pvp_plancode" class="width-100">计划编号</label>
        <input type="text" name="PdVisitPlanSearch[pvp_plancode]" class="width-200" id="pdvisitplansearch-pvp_plancode" value="<?= $queryParam['PdVisitPlanSearch']['pvp_plancode'] ?>">
    </div>
    <div class="inline-block field-pdvisitplansearch-pvp_type">
        <label for="pdvisitplansearch-pvp_type" class="width-100">计划类别</label>
        <select name="PdVisitPlanSearch[pvp_type]" class="width-200" id="pdvisitplansearch-pvp_type">
            <option value="">请选择</option>
            <?php foreach ($downList['planType'] as $key => $val) {?>
                <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['PdVisitPlanSearch']['pvp_type'])&&$queryParam['PdVisitPlanSearch']['pvp_type']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="inline-block field-pdvisitplansearch-plan_status">
        <label for="pdvisitplansearch-plan_status" class="width-100">计划状态</label>
        <select name="PdVisitPlanSearch[plan_status]" class="width-200" id="pdvisitplansearch-plan_status">
            <option value="">请选择</option>
            <option value="10" <?= isset($queryParam['PdVisitPlanSearch']['plan_status'])&&$queryParam['PdVisitPlanSearch']['plan_status']=='10'?"selected":null; ?>>新增</option>
            <option value="20" <?= isset($queryParam['PdVisitPlanSearch']['plan_status'])&&$queryParam['PdVisitPlanSearch']['plan_status']=='20'?"selected":null; ?>>执行中</option>
            <option value="30" <?= isset($queryParam['PdVisitPlanSearch']['plan_status'])&&$queryParam['PdVisitPlanSearch']['plan_status']=='20'?"selected":null; ?>>执行完成</option>
        </select>
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-pdvisitplansearch-plan_date">
        <label for="pdvisitplansearch-plan_date" class="width-100">计划日期</label>
        <input type="text" name="PdVisitPlanSearch[plan_date]" class="width-100 select-date" id="pdvisitplansearch-plan_date" value="<?= $queryParam['PdVisitPlanSearch']['plan_date'] ?>">
    </div>
    <div class="inline-block ">
        <label class="width-100">建立计划日期</label>
        <input type="text" class="width-100 select-date easyui-validatebox start_time_size" name="PdVisitPlanSearch[startDate]"
               value="<?= $queryParam['PdVisitPlanSearch']['startDate'] ?>">
    </div>
    <div class="inline-block">
        <label class="no-after">~</label>
        <input type="text" class="width-100 select-date easyui-validatebox end_time_size" name="PdVisitPlanSearch[endDate]"
               value="<?= $queryParam['PdVisitPlanSearch']['endDate'] ?>">
    </div>
    <div class="inline-block field-pdvisitplansearch-create_by">
        <label for="pdvisitplansearch-create_by" class="width-100">建立人</label>
        <input type="text" name="PdVisitPlanSearch[create_by]" class="width-100" id="pdvisitplansearch-create_by" value="<?= $queryParam['PdVisitPlanSearch']['create_by'] ?>">
    </div>
    <?= Html::submitButton('查询', ['class' => 'button-blue ml-50', 'type' => 'submit']) ?>
    <?= Html::button('重置', ['class' => 'button-blue', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
</div>
<div class="space-40"></div>
<?php ActiveForm::end(); ?>
