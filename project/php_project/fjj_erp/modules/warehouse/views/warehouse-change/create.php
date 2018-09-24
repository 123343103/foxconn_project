<?php
/**
 * User: F1676624
 * Date: 2017/7/22
 */
$this->title = '新增仓库异动';
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '仓库异动列表'];
$this->params['breadcrumbs'][] = ['label' => '新增仓库异动'];
?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
    <?= $this->render("_form", [
        'downList' => $downList,
        'whname'=>$whname
//        'model' => $model,
    ]);
    ?>
</div>