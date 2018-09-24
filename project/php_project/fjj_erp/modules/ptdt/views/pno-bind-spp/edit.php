<?php
/**
 * User: F1677929
 * Date: 2017/11/28
 */
/* @var $this yii\web\View */
$this->title='修改核价';
echo $this->render('_form',[
    'addData'=>$data['addData'],
    'editData'=>$data['editData']
]);
?>