<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\view */
$this->title='修改拜访记录';
echo $this->render('_form',[
    'customerInfo'=>$data['customerInfo'],
    'visitPerson'=>$data['visitPerson'],
    'visitType'=>$data['visitType'],
    'recordChild'=>$data['recordChild'],
    'visitPlan'=>$data['visitPlan'],
    'userModel'=>$userModel,
    'receptionData'=>$data['receptionData'],
    'accompanyData'=>$data['accompanyData']
]);
?>