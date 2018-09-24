<?php
/**
 * User: F1677929
 * Date: 2016/12/20
 */
/* @var $this yii\web\view */
$this->title = '修改拜访计划';
echo $this->render('_form', [
    'model'=>$model,
    'accompanyData' => $accompanyData,
    'downList'=>$downList,
    'staff'=>$userInfo['staff']
]);
?>