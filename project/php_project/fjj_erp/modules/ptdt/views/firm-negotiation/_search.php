<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="pd-negotiation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => false,
//        'fieldConfig' => [
//            'options'=>['class'=>'search-row'],
//            'template' => "{label}\n{input}<div class=\"space-10\"></div>",
//            'labelOptions' => ['class' => 'width-110  text-right'],
//            'inputOptions' => ['class' => 'width-150'],
//        ],
    ]); ?>

    <div class="search-row">
        <label class="width-110  text-right" for="pdnegotiationsearch-name">厂商全称/简称</label>
        <input type="text" id="pdnegotiationsearch-name" class="width-150" name="PdNegotiationSearch[name]" value="<?= $queryParam['PdNegotiationSearch']['name'] ?>">
        <label class="width-110  text-right" for="pdnegotiationsearch-type">类型</label>
        <select id="pdnegotiationsearch-type" class="width-150" name="PdNegotiationSearch[type]">
            <option value="">请选择...</option>
            <?php foreach ($downList['firmType'] as $key=> $val){?>
                <option value="<?= $val['bsp_id']?>" <?= isset($queryParam['PdNegotiationSearch']['type'])&&$queryParam['PdNegotiationSearch']['type'] == $key ? "selected" : null ?>><?= $val['bsp_svalue']?></option>
            <?php }?>
        </select>
        <label class="width-150 text-right" for="pdnegotiationsearch-issupplier">是否为集团供应商</label>
        <select id="pdnegotiationsearch-issupplier" class="width-100" name="PdNegotiationSearch[isSupplier]">
            <option value="">请选择...</option>
            <option value="1" <?= isset($queryParam['PdNegotiationSearch']['isSupplier'])&&$queryParam['PdNegotiationSearch']['isSupplier']=='1'?"selected":null ?>>是</option>
            <option value="0" <?= isset($queryParam['PdNegotiationSearch']['isSupplier'])&&$queryParam['PdNegotiationSearch']['isSupplier']=='0'?"selected":null ?>>否</option>
        </select><div class="space-10"></div>
    </div>
    <div class="search-row field-pdnegotiationsearch-level">
        <label class="width-110  text-right" for="pdnegotiationsearch-level">分级分类</label>
        <select id="pdnegotiationsearch-level" class="width-150" name="PdNegotiationSearch[level]">
            <option value="">请选择...</option>
            <?php foreach ($downList['productTypes'] as $key => $val){?>
                <option value="<?= $key ?>" <?= isset($queryParam['PdNegotiationSearch']['level'])&&$queryParam['PdNegotiationSearch']['level']== $key ?"selected":null ?>><?= $val ?></option>
            <?php }?>
        </select>
        <label class="width-110  text-right" for="pdnegotiationsearch-pdn_status">厂商状态</label>
        <select id="pdnegotiationsearch-pdn_status" class="width-150" name="PdNegotiationSearch[pdn_status]">
            <option value="">请选择...</option>
            <?php foreach ($downList['pdnStatus'] as $key=>$val){?>
                <option value="<?= $key ?>" <?= isset($queryParam['PdNegotiationSearch']['pdn_status'])&&$queryParam['PdNegotiationSearch']['pdn_status']==$key?"selected":null ?>><?= $val ?></option>
            <?php }?>
        </select>

    <?= Html::submitButton('查询', ['class' => 'button-blue ml-50']) ?>&nbsp;
    <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset','onclick'=>'window.location.href=\''.\yii\helpers\Url::to(["index"]).'\'']) ?>

    <?php ActiveForm::end(); ?>
    </div>

</div>
<div class="space-20"></div>