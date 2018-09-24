<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\JeDateAsset;
JeDateAsset::register($this);
?>
<style type="text/css">
    .width-60{
        width: 60px;
    }
    .width-150{
        width: 150px;
    }
</style>
<?php $form = ActiveForm::begin(['method' => "get","action"=>"index"]); ?>
        <div class="inline-block ">
            <label class="width-60" >关键字</label><label>：</label>
            <input type="text"  class="width-150" name="LogSearch[searchPara]" value="<?= $search['searchPara']?>">
        </div>
        <div class="inline-block ">
            <label class="width-60">时间</label><label>：</label>
            <input type="text" id="start_time" class="width-150 Wdate" name="LogSearch[startTime]" value="<?= $search['startTime']?>" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate: '#F{$dp.$D(\'end_time\');}' })">
        </div>
        <div class="inline-block">
            <label class="no-after">~</label>
            <input type="text" id="end_time" class="width-100 Wdate" name="LogSearch[endTime]" value="<?= $search['endTime']?>" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '#F{$dp.$D(\'start_time\');}' })" onfocus="this.blur()">

        <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue ml-50', 'style'=>'margin-left:50px']) ?>
        <?= Html::resetButton('重置', ['class' => 'button-blue reset-btn-yellow', 'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["index"]).'\'']) ?>
        </div>
    <?php ActiveForm::end(); ?>

<div class="space-20"></div>
