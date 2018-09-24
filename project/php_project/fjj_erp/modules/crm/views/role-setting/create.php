<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '新增销售角色';

?>
<div>

    <?= $this->render('_form', ["employeeType" => $employeeType, "roleStatus" => $roleStatus]) ?>
</div>
