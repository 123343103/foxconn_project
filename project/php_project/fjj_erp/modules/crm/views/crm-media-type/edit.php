<?php
/**
 * User: F1677929
 * Date: 2017/3/25
 */
/* @var $this yii\web\View */
?>
<h1 class="head-first">修改媒体类型</h1>
<?=$this->render('_form',[
    'mediaStatus'=>$data['mediaStatus'],
    'editData'=>$data['editData'],
])?>
