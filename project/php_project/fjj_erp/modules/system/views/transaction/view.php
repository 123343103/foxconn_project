<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\common\models\BsTransaction */

//$this->title = '交易方式詳情';
//$this->params['breadcrumbs'][] = ['label' => 'Bs Transactions', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
$this->title = '交易方式详情';
$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '平台交易相关设置', 'url' => Url::to(['/system/transaction/index'])];
$this->params['breadcrumbs'][] = ['label' => '交易方式列表', Url::to(['/system/transaction/transaction-index'])];
$this->params['breadcrumbs'][] = ['label' => '交易方式详情'];
?>
<div class="content">
    <!--<p>
        <?/*= Html::a('Update', ['update', 'id' => $model->tac_id], ['class' => 'btn btn-primary']) */?>
        <?/*= Html::a('Delete', ['delete', 'id' => $model->tac_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>-->
    <h1 class="head-first">
        交易方式详情
    </h1>
    <div class="mb-30">
    <div class="mb-10">
        <label class="width-100">代&nbsp;&nbsp;&nbsp;码</label>
        <span class="width-300"><?= $model->tac_code ?></span>
        <label class="width-100 ">交易名称</label>
        <span class="width-300"><?= $model->tac_sname?></span>
    </div>
    <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-100">创建人</label>
            <span class="width-300"><?=$staffName?></span>
            <label class="width-100">创建时间</label>
                <span class="width-300"><?=$model->create_at?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-100">备&nbsp;&nbsp;&nbsp;注</label>
            <span class="width-500 no-border vertical-center"><?= $model->remarks?></span>
        </div>
    </div>
    <div class="space-40 "></div>
    <button type="button" class="button-white-big ml-320" id="submit">
        返回
    </button>
</div>
<script>
    $(function () {
        $("#submit").click(function () {
            window.location.href="<?=Url::to(['transaction-index'])?>";
        });
    });
</script>