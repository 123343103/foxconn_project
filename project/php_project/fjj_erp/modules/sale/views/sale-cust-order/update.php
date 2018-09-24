<?php

use yii\helpers\Url;


$from = Yii::$app->request->get('action');
if ($from == 'quoted-list') {
    $this->title = '修改报价单';
    $this->params['homeLike'] = ['label' => '销售管理'];
    $this->params['breadcrumbs'][] = ['label' => '客戶需求单列表', 'url' => "index"];
    $this->params['breadcrumbs'][] = ['label' => $this->title];
} else {
    $this->title = '修改客戶需求单';
    $this->params['homeLike'] = ['label' => '销售管理'];
    $this->params['breadcrumbs'][] = ['label' => '客戶需求单列表', 'url' => "index"];
    $this->params['breadcrumbs'][] = ['label' => $this->title];
}
?>
<div class="content">
    <?= $this->render('_form', [
        'downList' => $downList,
        'quotedHModel' => $quotedHModel,
        'quotedLModel' => $quotedLModel,
        'SaleStaging' => $SaleStaging,
        'seller' => $seller,
        'title_district' => $title_district,
        'files' => $files,
        'send_district' => $send_district,
//        'credits' => $credits,
        'ReqPay' => $ReqPay,
        'customer' => $customer
    ]); ?>
</div>