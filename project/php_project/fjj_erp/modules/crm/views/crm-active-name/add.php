<?php
/**
 * User: F1677929
 * Date: 2017/3/25
 */
/* @var $this yii\web\View */
$this->title='新增活动名称';
echo $this->render('_form',[
    'addEditData'=>$data['addEditData'],
    'activeTypeName'=>$data['activeTypeName']
]);
?>