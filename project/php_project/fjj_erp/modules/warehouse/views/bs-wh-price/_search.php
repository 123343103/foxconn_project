<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/12
 * Time: 下午 03:37
 */
?>
<style>
    .width-100 {
        width: 100px;
    }

    .width-130 {
        width: 130px;
    }

    .ml-20 {
        margin-left: 20px;
    }

    .width-60 {
        width: 60px;
    }
</style>
<?php $from = ActiveForm::begin(['method' => "get", "action" => "index"]) ?>
<div class="search-div mb-10" style="margin: 0px;">
    <div class="mb-10">
        <div class="inline-block">
            <label class="width-100">费用代码：</label>
            <input type="text" name="whpb_code" class="width-130 qvalue-align"
                   value="<?= $get['whpb_code'] ?>">
        </div>
        <div class="inline-block">
            <label class="width-100">费用名称：</label>
            <input type="text" name="whpb_sname" class="width-130 qvalue-align"
                   value="<?= $get['whpb_sname'] ?>">
        </div>
        <div class="inline-block">
            <label class="width-60 qlabel-align">状态:</label>
            <select class="width-130 qvalue-align" name="stcl_status">
                <option value="">全部</option>
                <option
                    value="1" <?= ($get['stcl_status'] == 1 && isset($get['stcl_status'])) ? 'selected="selected"' : '' ?>>
                    启用
                </option>
                <option
                    value="0" <?= ($get['stcl_status'] == 0 && isset($get['stcl_status'])) ? 'selected="selected"' : '' ?>>
                    禁用
                </option>
            </select>
        </div>
        <?= Html::submitButton('查询', ['id' => 'search', 'class' => 'search-btn-blue', 'type' => 'submit', 'style' => 'margin-left:80px']) ?>
        <?= Html::resetButton('重置', ['id' => 'reset', 'style' => 'margin-left:30px', 'class' => 'reset-btn-yellow ', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
