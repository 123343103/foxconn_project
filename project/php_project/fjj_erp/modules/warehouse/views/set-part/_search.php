<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/8/7
 * Time: 上午 11:14
 */
use app\assets\JqueryUIAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
JqueryUIAsset::register($this);
$get = Yii::$app->request->get();
$queryParam = Yii::$app->request->queryParams;
?>
<style>
   .width-100{
       width: 100px;
   }
    .width-130{
        width: 130px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .width-60{
        width: 60px;
    }
</style>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div class="search-div,mb-10" style="margin:0;">
    <div class="mb-10">
      <!--  <div class="inline-block">
            <label class="width-100">仓库名称:</label>
            <select name="wh_name" class="width-120">
                <option value="">---全部---</option>
                <?php foreach ($downList['whname'] as $val) { ?>
                    <?php if ($get['wh_name'] == $val['wh_code']) { ?>
                        <option selected="selected" value="<?= $val['wh_code'] ?>"
                                name="wh_code"><?= $val['wh_name'] ?></option>
                    <?php } else { ?>
                        <option value="<?= $val['wh_code'] ?>" name="wh_code"><?= $val['wh_name'] ?></option>
                    <?php } ?>

                <?php } ?>
            </select>
        </div>
        -->
        <div class="inline-block">
            <label class="width-100 qlabel-align">仓库名称/代码:</label>
            <input type="text" name="wh_code" class="width-130 qvalue-align"
                   value="<?= $queryParam['wh_code'] ?>">
        </div>
        <div class="inline-block ml-20">
            <label class="width-100 qlabel-align">区位名称/区位码:</label>
            <input type="text" name="part_code" class="width-130 qvalue-align"
                   value="<?= $queryParam['part_code'] ?>">
        </div>
            <label class="width-60 qlabel-align">状态:</label>
            <select class="width-130 qvalue-align" name="type">
                <option value="" <?=$get["type"]==""?'selected':''?>>全部</option>
                <option value="1" <?=$get["type"]=='1' || !isset($get["type"])?'selected':''?>>启用</option>
                <option value="0" <?=$get["type"]=='0'?'selected':''?>>禁用</option>
            </select>

        <!--
        <div class="inline-block">
            <label class="width-100">区位名称:</label>
            <input type="text" name="part_name" class="width-120"
                   value="<?= $queryParam['part_name'] ?>">
        </div>
        -->
        <?= Html::submitButton('查询', ['id'=>'search','class' => 'search-btn-blue', 'type' => 'submit','style'=>'margin-left:15px']) ?>
        <?= Html::resetButton('重置', ['id'=>'reset', 'style'=>'margin-left:15px','class' => 'reset-btn-yellow ', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>