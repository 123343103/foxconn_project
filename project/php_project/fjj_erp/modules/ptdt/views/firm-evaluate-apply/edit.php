<?php
/**
 * User: F1677929
 * Date: 2016/11/2
 */
/* @var $this yii\web\View */
/* @var $applyModel app\modules\ptdt\models\PdFirmEvaluateApply */
/* @var $productLevel app\modules\common\models\BsPubdata */
use yii\helpers\Url;
$this->params['homeLike'] = ['label'=>'供应商管理','url' => Url::to([''])];
$this->params['breadcrumbs'][] = ['label' => '厂商评鉴申请列表', 'url' => Url::to([''])];
$this->params['breadcrumbs'][] = ['label' => '修改厂商评鉴申请', 'url' => Url::to([''])];
?>
<div class="content">
    <h1 class="head-first">评鉴申请修改</h1>
    <?= $this->render("_form", [
        'applyModel'=>$applyModel,
        'productLevel' => $productLevel,
        'conditionVal' => $conditionVal,
    ]) ?>
</div>