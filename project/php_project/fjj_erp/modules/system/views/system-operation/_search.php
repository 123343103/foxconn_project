<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/11/20
 * Time: 上午 09:36
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
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
    <label class="width-60" >按钮</label><label>：</label>
    <input type="text"  class="width-150" name="btn_name" placeholder="按钮名称/按钮" value="<?= $queryParam['btn_name']?>">
</div>
<div class="inline-block ">
    <label class="width-60">有效否</label><label>：</label>
    <select name="btn_yn" class="width-150" id="btn_yn" >
        <option value="2" <?= isset($queryParam['btn_yn']) && $queryParam['btn_yn'] == 2 ? "selected" : null ?>>全部</option>
        <option value="1" <?= isset($queryParam['btn_yn']) && $queryParam['btn_yn'] == 1 ? "selected" : null ?>>是</option>
        <option value="3" <?= isset($queryParam['btn_yn']) && $queryParam['btn_yn'] == 3 ? "selected" : null ?>>否</option>
    </select>
</div>
    <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue ml-50', 'style'=>'margin-left:50px']) ?>
<?php ActiveForm::end(); ?>

<div style="margin-bottom: 10px"></div>