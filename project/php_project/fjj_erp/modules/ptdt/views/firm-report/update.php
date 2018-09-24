<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\PdFirmReport */
use yii\helpers\Url;
$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程'];
$this->params['breadcrumbs'][] = ['label' => '厂商呈报列表'];
$this->params['breadcrumbs'][] = ['label' => '修改呈报'];
$this->title = '修改厂商呈报';/*BUG修正 增加title*/
?>
<div class="content">
    <h1 class="head-first">
        修改呈报
        <div class="float-right mr-50">
            <label class="width-100" style="color:#fff;">呈报编号</label>
            <span style="color:#fff;"><?= $result['report']['report_code'] ?></span>
        </div>
    </h1>
    <?= $this->render('_form',[
        'downList'=>$downList,
        'result'=>$result,
    ]); ?>

</div>
