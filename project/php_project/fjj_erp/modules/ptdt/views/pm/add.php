<?php
/**
 * 新增商品经理人
 * F3858995
 * 2016/10/22
 */



$this->params['homeLike'] = ['label' => '商品开发'];
$this->params['breadcrumbs'][] = ['label' => '设置'];
$this->params['breadcrumbs'][] = ['label' => '新增商品经理人'];
$this->title = '新增商品经理人';/*BUG修正 增加title*/
?>
<div class="pop-head">
    <p>新增商品经理人</p>
</div>
<div class="content">
    <?=$this->render("_form",[
            'options'=>$options
    ]) ?>
</div>
