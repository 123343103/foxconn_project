<?php

use yii\helpers\Html;

$this->title='更新组织机构';
?>
<?//=Html::cssFile('@web/css/main.css')?>
<div class="pop-head">
    <p>更新组织机构</p>
</div>
<div class="organization-update">

    <?= $this->render('_form', [
        'downList' => $downList,
        'model'=>$model,
    ]) ?>

</div>
