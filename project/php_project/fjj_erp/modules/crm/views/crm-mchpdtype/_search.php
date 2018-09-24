<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<style>
    .label-width {
        width: 80px;
    }

    .value-width {
        width: 150px;
    }
</style>
<div class="crm-customer-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="search-div mb-10">
        <div class="inline-block">
            <label class="width-70">商品分类</label> <label>：</label>
            <select class="width-200" name="CrmMchpdtypeSearch[category_id]">
                <option value="">全部</option>
                <?php foreach ($downList['productType'] as $val) { ?>
                    <option
                        value="<?= $val['catg_id'] ?>" <?= $search['category_id'] == $val['catg_id'] ? "selected" : null; ?>><?= $val['catg_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <?= Html::submitButton('查询', ['class' => 'search-btn-blue', 'style' => 'margin-left:30px', 'type' => 'submit']) ?>
        <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-20', 'type' => 'reset', 'id' => 'reset', 'onclick' => 'window.location.href=\'' . \yii\helpers\Url::to(["index"]) . '\'']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
