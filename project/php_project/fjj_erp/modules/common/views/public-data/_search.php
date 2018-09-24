<?php
/**
 * User: F1677929
 * Date: 2017/3/10
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<?php ActiveForm::begin(['method'=>'get','action'=>Url::to(['index'])])?>
    <div class="mb-20">
        <label class="width-100">参数名称</label>
        <input type="text" class="width-200" name="searchKeyword" value="<?=$params['searchKeyword']?>">
        <button type="submit" class="search-btn-blue">查询</button>
        <button type="button" class="reset-btn-yellow" onclick="window.location.href='<?=Url::to(['index'])?>'">重置</button>
    </div>
<?php ActiveForm::end()?>