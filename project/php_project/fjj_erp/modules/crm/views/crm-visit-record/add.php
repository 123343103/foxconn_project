<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\view */
$this->title='新增拜访记录';
echo $this->render('_form',[
    'customerInfo'=>$data['customerInfo'],
    'visitPerson'=>$data['visitPerson'],
    'visitType'=>$data['visitType'],
    'visitPlan'=>$data['visitPlan'],
    'userModel'=>$userModel,
    'planId'=>$planId,
    'accompanyData'=>$data['visitPlan']['accompanyData']
]);
?>