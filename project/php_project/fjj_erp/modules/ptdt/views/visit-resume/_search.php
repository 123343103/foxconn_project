<?php
/**
 * User: F1677929
 * Date: 2016/11/2
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<?php ActiveForm::begin(['method'=>'get','action'=>Url::to(['index'])])?>
<div class="mb-15">
    <label class="width-100">公司全称/简称</label>
    <input type="text" class="width-180" name="firmName" value="<?=$params['firmName']?>">
    <label class="width-100">厂商类型</label>
    <select type="text" class="width-180" name="firmType">
        <option value="">请选择...</option>
        <?php foreach($firmType as $key=>$val){?>
            <option value="<?=$key?>" <?=$params['firmType']==$key?'selected':''?>><?=$val?></option>
        <?php }?>
    </select>
    <label class="width-100">集团供应商</label>
    <select class="width-180" name="groupSupplier">
        <option value="">请选择...</option>
        <option value="1" <?=$params['groupSupplier']=='1'?'selected':''?>>是</option>
        <option value="0" <?=$params['groupSupplier']=='0'?'selected':''?>>否</option>
    </select>
</div>
<div class="mb-20">
    <label class="width-100">分级分类</label>
    <select class="width-180" name="oneCategory">
        <option value="">请选择...</option>
        <?php foreach($oneCategory as $key=>$val){?>
            <option value="<?=$key?>" <?=$params['oneCategory']==$key?'selected':''?>><?=$val?></option>
        <?php }?>
    </select>
    <label class="width-100">状态</label>
    <select class="width-180" name="visitStatus">
        <option value="">请选择...</option>
        <?php foreach($visitStatus as $key=>$val){?>
            <option value="<?=$key?>" <?=$params['visitStatus']==$key?'selected':''?>><?=$val?></option>
        <?php }?>
    </select>
    <button type="submit" class="button-blue ml-30">查询</button>
    <button type="button" class="button-white" onclick="window.location.href='<?=Url::to(['index'])?>'">重置</button>
</div>
<?php ActiveForm::end()?>
