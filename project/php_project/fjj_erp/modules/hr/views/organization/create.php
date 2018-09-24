<?php

use yii\helpers\Html;


$this->title='新增组织机构';
?>
<?//=Html::cssFile('@web/css/main.css')?>
<div class="organization-create">
    <div class="pop-head">
        <p><?= Html::encode($this->title) ?></p>
    </div>

    <?= $this->render('_form', [
        'downList' => $downList,
        'model'=>$model
    ]) ?>

</div>
