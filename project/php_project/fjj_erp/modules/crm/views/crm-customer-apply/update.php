<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/7
 * Time: 下午 01:55
 */
use yii\helpers\Url;

if (isset($status)){
    $this->title = '客户代码申请';
    $this->params['homeLike'] = ['label' => '客户关系管理'];
    $this->params['breadcrumbs'][] = ['label' => '客户列表','url'=>Url::to(['crm-customer-info/index'])];
    $this->params['breadcrumbs'][] = ['label' => '客户代码申请'];
}
else{
    $this->title = '客户代码申请修改';
    $this->params['homeLike'] = ['label' => '客户关系管理'];
    $this->params['breadcrumbs'][] = ['label' => '客户代码申请列表','url'=>Url::to(['index'])];
    $this->params['breadcrumbs'][] = ['label' => '客户代码申请修改'];
}

?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
        <span class="head-code">客户编号:<?= $model['cust_filernumber'] ?></span>
    </h1>
    <?php $updataView = '_form';
    if ($caModel['status'] == 50 || $status == 10) {
        $updataView = '_form';
    } elseif ($caModel['status'] == 40) {
        $updataView = '_form1';
    } else {
        $updataView = '_form';
    }
    ?>
    <?= $this->render($updataView, [
        'result'=>$result,
        'staffname' => $staffname,
        'model' => $model,
        'status'=>$status,
        'caModel' => $caModel,
        'downList' => $downList,
        'district' => $district,
        'salearea' => $salearea,
        'country' => $country,
        'industryType' => $industryType,
        'districtAll2' => $districtAll2,
        'districtAll3' => $districtAll3,
        'crmcertf' => $crmcertf
    ]);
    ?>
</div>