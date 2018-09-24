<?php
/**
 * User: F1677929
 * Date: 2017/12/5
 */
/* @var $this yii\web\View */
$this->title='修改收货中心';
echo $this->render('_form',[
    'addData'=>$data['addData'],
    'editData'=>$data['editData']
]);
?>