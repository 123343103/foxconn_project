<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>

<?php
$form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]);
$search = Yii::$app->request->queryParams;
if(!isset($search['PdSupplierSearch'])){
        $search['PdSupplierSearch']=null;
}
?>
<div class="mb-10">
        <label class="width-100" for="supplier-name-search">公司全称\简称</label>
        <input type="text" id="supplier-name-search" class="form-control" name="PdSupplierSearch[supplier_sname]" value="<?= $search['PdSupplierSearch']['supplier_sname']?>">
        <label class="width-100" for="supplier-isupplier-search">集团供应商</label>
        <select id="supplier-isupplier-search" class="form-control width-170" name="PdSupplierSearch[supplier_issupplier]">
                <option value="">请选择...</option>
                 <option value="1">是</option>
                 <option value="0">否</option>
        </select>
        <label class="width-100">新增时间</label>
        <input type="text" id="startDate" class="width-120 select-date" name="PdSupplierSearch[startDate]" value="<?=$search['PdSupplierSearch']['PdSupplierSearch']['startDate'] ?>">~
        <input type="text" id="endDate" class="width-120 select-date" name="PdSupplierSearch[endDate]" value="<?= $search['PdSupplierSearch']['PdSupplierSearch']['endDate'] ?>">

</div>
<div class="mb-10">
        <label class="width-100" for="supplier_transacttype-search">商品类别</label>
        <select id="supplier_transacttype-search" class="form-control width-170" name="PdSupplierSearch[supplier_transacttype]">
                <option value="">请选择...</option>
                <?php foreach($model['productType'] as $val){  ?>
                        <option value="<?=$val['type_id']?>" <?= $search['PdSupplierSearch']['supplier_transacttype']==$val['type_id'] ? 'selected':''?>><?=$val['type_name']?></option>
                <?php }?>
        </select>
        <label class="width-100" for="supplier-status">状态</label>
        <input type="text" id="supplier-status-search" class="form-control" name="PdSupplierSearch[supplier_status]" value="<?= $search['PdSupplierSearch']['PdSupplierSearch']['supplier_status'] ?>">
</div>
<div class="mb-10">
        <label class="width-100" for="firm-type-search">供应商分类</label>
        <select id="firm-type-search" class="form-control width-170" name="PdSupplierSearch[supplier_type]">
                <option value="">请选择...</option>
                <?php foreach($model['firmType'] as $val){  ?>
                        <option value="<?=$val['bsp_id']?>" <?= $search['PdSupplierSearch']['supplier_type']==$val['bsp_id'] ? 'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
        </select>
        <label class="width-100" for="create-by-search">新增人</label>
        <input type="text" id="create-by-search" class="form-control" name="PdSupplierSearch[createBy]" value="<?= $search['PdSupplierSearch']['PdSupplierSearch']['createBy'] ?>">
    <?= Html::submitButton('查询', ['class' => 'button-blue ml-40']) ?>
    <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["index"]).'\'']) ?>
</div>
<?php ActiveForm::end(); ?>
