<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCreditApply */

$this->title = 'Create Crm Credit Apply';
$this->params['breadcrumbs'][] = ['label' => 'Crm Credit Applies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-credit-apply-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
