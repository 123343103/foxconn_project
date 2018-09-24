<?php
/**
 * User: F1677929
 * Date: 2017/3/25
 */
/* @var $this yii\web\View */
$this->title='新增活动统计';
echo $this->render('_form',[
        'mediaType'=>$data['mediaType'],
        'activeData'=>$data['activeData']
     ]);
?>