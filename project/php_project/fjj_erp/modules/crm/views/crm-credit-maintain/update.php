<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCreditMaintain */

$this->title = '修改信用额度类型';

?>
<h1 class="head-first"><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form',[
    'model'=>$model
]) ?>

