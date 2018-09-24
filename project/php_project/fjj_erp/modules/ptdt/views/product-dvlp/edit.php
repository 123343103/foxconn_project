<?php
/**
 * 修改 
 * F3858995
 * 2016/9/27
 */
$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '商品开发计划'];
$this->params['breadcrumbs'][] = ['label' => '修改商品开发需求'];
$this->title = '修改商品开发需求';/*BUG修正 增加title*/
?>
<div class="content">
    <h1 class="head-first">
        修改商品开发需求
    </h1>

    <?= $this->render("_form", [
        'planModel' => $planModel,
        'developDep' => $developDep,
        'downList' =>$downList,
        'reviews'=>$reviews
    ]) ?>
</div>
