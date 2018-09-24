<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/14
 * Time: 上午 11:05
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

//dumpE($downList);
?>
    <style>
        .width-100 {
            width: 100px;
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
    </style>
<?php $from = ActiveForm::begin(['method' => 'get', 'action' => 'index']) ?>
    <div class="search-div">
        <div class="inline-block">
            <label class="width-100 label-right">仓库名称/代码：</label>
            <input type="text" name="wh_name" value="<?= $get['wh_name'] ?>" class="width-120 input-left"
                   maxlength="20">
        </div>
        <div class="inline-block">
            <label class="width-100 label-right">操作类型：</label>
            <select name="op_id" class="width-120 input-left">
                <option value="">请选择</option>
                <?php foreach ($downList['type'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>" <?= isset($get['op_id']) && $get['op_id'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="width-100 label-right">创建人：</label>
            <input type="text" name="staff_name" class="width-120 input-left" value="<?= $get['staff_name'] ?>"
                   maxlength="20">
        </div> <?= Html::submitButton('查询', ['id' => 'search', 'class' => 'search-btn-blue', 'type' => 'submit', 'style' => 'margin-left:80px']) ?>
        <?= Html::resetButton('重置', ['id' => 'reset', 'style' => 'margin-left:30px', 'class' => 'reset-btn-yellow ', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>

<?php ActiveForm::end() ?>