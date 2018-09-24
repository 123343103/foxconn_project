<?php
/**
 * User: F1677929
 * Date: 2017/2/10
 */
/* @var $this yii\web\View */
$this->title='修改活动报名';
echo $this->render('_form',[
    'addEditData'=>$data['addEditData'],
    'editData'=>$data['editData'],
    'activeName'=>$data['activeName'],
    'districtData'=>$data['districtData'],
]);
?>