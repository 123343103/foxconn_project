<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCreditApply */

$this->title = '修改账信申请信息';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '账信申请列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">

    <h1 class="head-first"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'downList'=>$downList,
        'model'=>$model,
        'apply'=>$apply,
    ]) ?>

</div>
