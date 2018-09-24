<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/11/21
 * Time: 下午 04:01
 */
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */
//use app\assets\JqueryUIAsset;
//use app\assets\AppAsset;
//JqueryUIAsset::register($this);
//AppAsset::register($this);
$this->params['homeLike'] = ['label'=>'系统平台设置','url'=>'index'];
$this->params['breadcrumbs'][] = ['label'=>'用户管理','url'=>'index'];
$this->params['breadcrumbs'][] = ['label'=>'新增用户'];
$this->title = '新增用户';
?>
<div class="content">

    <h1 class="head-first">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
        'roles'=>$roles,
        'company'=>$company,
        'bspnamelist'=>$bspnamelist,
        'factory' => $factory
    ]) ?>

</div>
