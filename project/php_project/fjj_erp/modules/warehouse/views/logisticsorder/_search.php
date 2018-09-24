<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/11
 * Time: 下午 02:39
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
?>
<style>
    .label-width {
        width: 100px;
    }

    .width-100 {
        width: 100px;
    }

    .width-130 {
        width: 130px;
    }
</style>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div class="mb-10">
    <div class="inline-block">
        <label class="label-align label-width" for="ORDERNO">物流订单号：</label>
        <input type="text" id="orderno" class="width-130" name="lg_no" value="<?= $param['lg_no'] ?>">
        <div class="help-block"></div>
    </div>
    <div class="inline-block">
        <label class="label-align label-width" for="t">运输模式：</label>
        <select id="transmodel" name="transmodel" class="width-130" data-options="required:true">
            <option value="">全部</option>
            <option value="5" <?= $param['transmodel'] == '5' ? 'selected' : null ?>>陆运</option>
            <option value="20" <?= $param['transmodel'] == '20' ? 'selected' : null ?>>快递</option>
        </select>
        <div class="help-block"></div>
    </div>
    <div class="inline-block">
        <label class="label-align label-width" for="t">单据状态：</label>
        <select id="transmodel" name="check_status" class="width-130" data-options="required:true">
            <option value="">全部</option>
            <option value="2" <?= $param['check_status'] == '2' ? 'selected' : null ?>>开立</option>
            <option value="0" <?= $param['check_status'] == '0' ? 'selected' : null ?>>审核中</option>
            <option value="1" <?= $param['check_status'] == '1' ? 'selected' : null ?>>审核完成</option>
            <option value="-1" <?= $param['check_status'] == '-1' ? 'selected' : null ?>>驳回</option>
            <option value="3" <?= $param['check_status'] == '3' ? 'selected' : null ?>>发送物流</option>
        </select>
        <div class="help-block"></div>
    </div>
</div>

<div class="mb-10">
    <div class="inline-block">
        <label class="label-align label-width" for="Transportno">运输单号：</label>
        <input type="text" id="Transportno" class="width-130" name="Transportno" value="<?= $param['Transportno'] ?>">
        <div class="help-block"></div>
    </div>
    <div class="inline-block">
        <label class="label-align label-width" for="person">开立人：</label>
        <input type="text" id="person" class="width-130" name="person" value="<?= $param['person'] ?>">
        <div class="help-block"></div>
    </div>
    <div class="inline-block">
        <div class="inline-block">
            <label class="label-align label-width">出货时间区间：</label>
            <input id="start_time" name="startdate" type="text" style="width:80px;" class="Wdate" readonly="readonly">
            <label>~</label>
            <input id="end_time"  name="enddate" type="text" style="width:81px;" class="Wdate" readonly="readonly">
        </div>
    </div>
    <div class="inline-block">
        <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-40', 'id' => 'select', 'type' => 'submit']) ?>
        <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-20', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        //出货时间区间
        $("#start_time").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd",
                maxDate:"#F{$dp.$D('end_time',{d:-1})}"
            });
        });
        $("#end_time").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd",
                minDate:"#F{$dp.$D('start_time',{d:1})}"
            });
        });
    })
</script>
