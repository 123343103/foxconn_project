<?php
use yii\helpers\Html;

$this->title = '修改销售角色';
?>
<div>

    <?= $this->render('_form', [
        'model' => $model,
        "employeeType" => $employeeType, "roleStatus" => $roleStatus
    ]) ?>

</div>
