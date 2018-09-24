<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->params['homeLike'] = ['label' => '仓储物流管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][] = ['label' => '库存预警人员列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '库存预警人员新增页面'];
$this->title = '库存预警人员新增页面';
?>
<div class="content">
    <h1 class="head-first">
        <?= Html::encode($this->title) ?>
    </h1>
    <?= $this->render('_form', [
        'model' => $model,
        'StaffCode'=>$StaffCode,
        'downList'=>$downList,
        'opper'=>$opper,
        'search'=>$search

    ]) ?>

</div>

