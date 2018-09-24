<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/7/11
 * Time: 上午 09:59
 */
use yii\helpers\Html;
$this->title = '修改报废信息';
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '报废申请列表'];
$this->params['breadcrumbs'][] = ['label' => '修改报废信息'];
?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
    <?= $this->render("_form", [
        'invChangeInfoH' => $invChangeInfoH,
        'invChangeInfoL' => $invChangeInfoL,
        'downList' => $downList,
        'whname'=>$whname
    ]);
    ?>
</div>