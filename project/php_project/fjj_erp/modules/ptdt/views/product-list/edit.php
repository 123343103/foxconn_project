<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '商品列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '修改商品信息'];
$form=($step==1)?"_form_1":"_form_2";
$this->title =($step==1)?'修改商品信息':'维护料号信息';

?>
<div class="content">
    <h1 class="head-first">
        <?= Html::encode($this->title) ?>
    </h1>
    <?= $this->render($form, [
        'model' => $model,
        'options' => $options,
        'busTypeId'=>$busTypeId
    ]) ?>

</div>
