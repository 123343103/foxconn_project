<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/18
 * Time: 下午 04:40
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);

$this->title = '请购转采购单';
$this->params['homeLike'] = ['label' => '采购管理'];
$this->params['breadcrumbs'][] = ['label' => '采购前置作业', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '请购转采购单'];
?>

