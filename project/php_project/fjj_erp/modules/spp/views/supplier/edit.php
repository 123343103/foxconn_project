<?php
/**
 * User: F1677929
 * Date: 2017/9/26
 */
/* @var $this yii\web\View */
$this->title='修改供应商申请';
echo $this->render('_form',[
    'addData'=>$data['addData'],
    'editData'=>$data['editData']
]);
?>