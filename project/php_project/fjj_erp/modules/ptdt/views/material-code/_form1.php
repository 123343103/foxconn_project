<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\ptdt\models\PdMaterialCode;
$get = Yii::$app->request->get();
if(!isset($get['PdMaterialCodeSearch'])){
    $get['PdMaterialCodeSearch']=null;
}

?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>
<div class="mb-10">
    <div class="inline-block field-pdvisitplansearch-pvp_plancode">
        <label for="pdvisitplansearch-pvp_plancode" class="width-100 text-right">料号</label>
        <input type="text" name="PdVisitPlanSearch[pvp_plancode]" class="width-200" id="pdvisitplansearch-pvp_plancode" value="<?= $get['PdMaterialCodeSearch']['material_code'] ?>">
    </div>
    <div class="inline-block field-pdvisitplansearch-pvp_type">
        <label for="pdvisitplansearch-pvp_type" class="width-100 text-right">品名</label>
        <input type="text" name="PdVisitPlanSearch[pvp_plancode]" class="width-200" id="pdvisitplansearch-pvp_plancode" value="<?= $get['PdMaterialCodeSearch']['pro_name'] ?>">
        <!--<select name="PdVisitPlanSearch[pvp_type]" class="width-200" id="pdvisitplansearch-pvp_type">
            <option value="">请选择</option>
            <?php /*foreach ($model['downList']['planType'] as $key => $val) {*/?>
                <option value="<?/*=$val['bsp_id'] */?>" <?/*= isset($get['PdVisitPlanSearch']['pvp_type'])&&$get['PdVisitPlanSearch']['pvp_type']==$val['bsp_id']?"selected":null */?>><?/*= $val['bsp_svalue'] */?></option>
            <?php /*} */?>
        </select>-->

        <div class="help-block"></div>
    </div>
    <div class="inline-block field-pdvisitplansearch-plan_status">
        <label for="pdvisitplansearch-plan_status" class="width-100">型号规格</label>
        <input type="text" name="PdVisitPlanSearch[pvp_plancode]" class="width-200" id="pdvisitplansearch-pvp_plancode" value="<?= $get['PdMaterialCodeSearch']['pro_size'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-pdvisitplansearch-create_by">
        <label for="pdvisitplansearch-create_by" class="width-100">新旧程度</label>
        <input type="text" name="PdVisitPlanSearch[create_by]" class="width-200" id="pdvisitplansearch-create_by" value="<?= $get['PdMaterialCodeSearch']['status'] ?>">
    </div>
    <div class="inline-block field-pdvisitplansearch-create_at">
        <label for="pdvisitplansearch-create_at" class="width-100">创建时间</label>
        <input type="text" name="PdVisitPlanSearch[create_at]" class="width-200 select-date" id="pdvisitplansearch-create_at" value="<?= $get['PdMaterialCodeSearch']['create_time'] ?>">
    </div>
    <?= Html::submitButton('查询', ['class' => 'button-blue ml-50', 'type' => 'submit']) ?>
    <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["index"]).'\'']) ?>
</div>
<div class="space-40"></div>
<?php ActiveForm::end(); ?>
</div>