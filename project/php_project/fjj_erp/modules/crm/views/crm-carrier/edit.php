<?php
/**
 * User: F1677929
 * Date: 2017/3/25
 */
/* @var $this yii\web\View */
?>
<h1 class="head-first">修改载体</h1>
<?=$this->render('_form',[
    'carrierType'=>$data['carrierType'],
    'saleWay'=>$data['saleWay'],
    'carrierStatus'=>$data['carrierStatus'],
    'editData'=>$data['editData']
])?>
