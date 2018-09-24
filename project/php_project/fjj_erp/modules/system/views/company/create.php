<?php

use yii\helpers\Html;

$this->title='新增公司';

?>
<?//=Html::cssFile('@web/css/main.css')?>
<div class="create">
    <div class="pop-head">
        <p><?= Html::encode($this->title) ?></p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'downList'=>$downList,
        'district'=>$district,
        'downListCop' => $downListCop,
        'companyStatus' => $companyStatus
    ]) ?>

</div>
