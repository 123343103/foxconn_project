<?php
/**
 * 修改
 * F3858995
 * 2016/10/24
 */

$this->params['homeLike'] = ['label' => '商品开发'];
$this->params['breadcrumbs'][] = ['label' => '设置'];
$this->params['breadcrumbs'][] = ['label' => '修改商品经理人'];
$this->title = '修改商品经理人';/*BUG修正 增加title*/
?>
<div class="pop-head">
    <p>修改商品经理人</p>
</div>
<div class="content">
    <?=$this->render("_form",[
            'model'=>$model,
            'options'=>$options
    ]) ?>
</div>
<script>
    $(function(){
        getStaffInfo($("#pdproductmanager-staff_code").val());
    });
</script>

