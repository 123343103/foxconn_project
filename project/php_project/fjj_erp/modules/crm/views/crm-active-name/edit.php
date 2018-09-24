<?php
/**
 * User: F1677929
 * Date: 2017/3/25
 */
/* @var $this yii\web\View */
$this->title='修改活动名称';
echo $this->render('_form',[
    'addEditData'=>$data['addEditData'],
    'editData'=>$data['editData'],
    'activeTypeName'=>$data['activeType']['activeTypeName'],
    'activeWayName'=>$data['activeType']['activeWayName'],
    'districtData'=>$data['districtData']
]);
?>