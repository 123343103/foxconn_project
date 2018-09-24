<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/22
 * Time: 下午 02:54
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

//dumpE($get);

\app\assets\JeDateAsset::register($this);
?>
<style>
    .width-70 {
        width: 70px;
    }

    .width-80 {
        width: 80px;
    }

    .width-120 {
        width: 120px;
    }

    .label-right {
        text-align: right;
    }

    .input-left {
        text-align: left;
    }

    .mt-10 {
        margin-top: 10px;
    }
</style>
<?php $from = ActiveForm::begin(['method' => 'get', 'action' => 'index']) ?>
<div>
    <div>
        <div class="inline-block">
            <label class="width-70 label-right">单号：</label>
            <input type="text" name="o_whcode" value="<?= $get['o_whcode'] ?>" class="width-120 input-left"
                   maxlength="20">
        </div>
        <div class="inline-block">
            <label class="width-80 label-right">法人简称：</label>
            <select name="company" class="width-120 input-left">
                <option value="">请选择</option>
                <?php foreach ($downList['company'] as $key => $val) { ?>
                    <option
                        value="<?= $key ?>" <?= isset($get['company']) && $get['company'] == $key ? "selected" : null ?>><?= $val ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="width-80 label-right">单据类型：</label>
            <select name="type" class="width-120 input-left">
                <option value="">请选择</option>
                <?php foreach ($downList['type'] as $key => $val) { ?>
                    <option
                        value="<?= $key ?>" <?= isset($get['type']) && $get['type'] == $key ? "selected" : null ?>><?= $val ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="width-70 label-right">仓库：</label>
            <input type="text" name="wh_name" class="width-120 input-left" value="<?= $get['wh_name'] ?>"
                   maxlength="20">
        </div>
    </div>
    <div class="mt-10">
        <div class="inline-block">
            <label class="width-70 label-right">仓库属性：</label>
            <select name="wh_attr" class="width-120 input-left">
                <option value="">请选择</option>
                <?php foreach ($downList['wh_attr'] as $key => $val) { ?>
                    <option
                        value="<?= $key ?>" <?= isset($get['wh_attr']) && $get['wh_attr'] == $key ? "selected='selected'" : '' ?>><?= $val ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="width-80 label-right">状态：</label>
            <select name="status" class="width-120 input-left">
                <option>请选择</option>
                <?php foreach ($downList['status'] as $key => $val) { ?>
                    <option
                        value="<?= $key ?>" <?= isset($get['status']) && $get['status'] == $key && $get['status'] != '请选择' ? "selected" : null ?>><?= $val ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="width-80 label-right">时间：</label>
            <input id="start" type="text" name="begin-date" class="Wdate width-120 input-left" readonly="readonly"
                   value="<?= $get['begin-date'] ?>">

        </div>
        <div class="inline-block">
            <label class="width-70 label-right" style="margin-left: -20px;margin-right: 20px;">至</label>
            <input id="end" type="text" name="end-date" class="Wdate width-120 input-left" readonly="readonly"
                   value="<?= $get['end-date'] ?>">

        </div>

        <?= Html::submitButton('查询', ['id' => 'search', 'class' => 'search-btn-blue', 'type' => 'submit', 'style' => 'margin-left:20px']) ?>
        <?= Html::resetButton('重置', ['id' => 'reset', 'style' => 'margin-left:20px', 'class' => 'reset-btn-yellow ', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
</div>
<script>

    $(function () {


        //日期
        $("#start").click(function () {
            WdatePicker({
                skin: "whyGreen",
                maxDate: "#F{$dp.$D('end',{d:-1})}"
            });
        });
        $("#end").click(function () {
            WdatePicker({
                skin: "whyGreen",
                minDate: "#F{$dp.$D('start',{d:1})}"
            });
        });
    })
</script>

<?php ActiveForm::end() ?>
