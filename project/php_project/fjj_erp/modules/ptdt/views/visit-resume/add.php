<?php
/**
 * User: F1677929
 * Date: 2016/9/14
 */
/* @var $this yii\web\view */
$this->title='新增拜访履历';
$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '厂商拜访履历', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '新增拜访履历', 'url' => ""];
echo $this->render('_form',[
    'firmInfo'=>$data['firmInfo'],
    'visitPlan'=>$data['visitPlan'],
    'visitPerson'=>$data['visitPerson'],
]);
?>