<?php
/**
 * User: F3859386
 * Date: 2016/12/20
 */
$this->title = '新增拜访计划';
echo $this->render('_form', [
    'downList' => $downList,
    'staff'=>$userInfo['staff'],
    'model'=>$model
]);
?>