<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/11
 * Time: 下午 02:33
 */
$this->title = '物流订单查询';
$this->params['homeLike'] = ['label' => '仓库物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '物流订单查询', 'url' => ''];
?>
<div class="content">
    <?php echo $this->render('_search',['param'=>$param]);?>
    <?php echo $this->render('_action');?>
    <?php echo $this->render('_result');?>
</div>
