<?php
/**
 * User: F1677929
 * Date: 2017/3/25
 */
/* @var $this yii\web\View */
$this->title='修改活动统计';
echo $this->render('_form',[
        'mediaType'=>$data['addEditData']['mediaType'],
        'activeData'=>$data['addEditData']['activeData'],
        'countMain'=>$data['countMain'],
        'editData'=>$data['editData']
     ]);
?>