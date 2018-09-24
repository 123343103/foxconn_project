<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCreditMaintain */

$this->title = '信用类型额度详情';

?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="ml-30">
    <div class="mb-20">
        <label class="width-90">编码</label>
        <span class="width-300"><?= $model['code'] ?></span>
    </div>
    <div class="mb-20">
        <label for="" class="width-90">信用额度类型</label>
        <span class="width-300"><?= $model['credit_name'] ?></span>
    </div>
    <div class="mb-20">
        <label for="" class="width-90">状态</label>
        <span class="width-300"><?= $model['credit_status']=='10'?'启用':'禁用'; ?></span>
    </div>
    <div class="mb-20">
        <label for="" class="width-90">描述</label>
        <span class="width-300"><?= $model['remark'] ?></span>
    </div>
    <div class="text-center mt-10">
        <button class="button-white-big ml-20" onclick="close_select()">返回</button>
    </div>
</div>

