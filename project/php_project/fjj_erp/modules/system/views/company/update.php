<?php

use yii\helpers\Html;

$this->title = '编辑公司: ' . $model['company_name'];
?>
<div class="create">
    <div class="pop-head">
        <p><?= Html::encode($this->title) ?></p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'downList' => $downList,
        'district'=>$district,
        'downListCop' => $downListCop,
        'companyStatus' => $companyStatus,
        'districtAll2' => $districtAll2,
        'staffInfo' => $staffInfo,
    ]) ?>

</div>
