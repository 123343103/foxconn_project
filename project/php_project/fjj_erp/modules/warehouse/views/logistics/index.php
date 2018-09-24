<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/21
 * Time: 上午 10:17
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = '物流信息查询';
$this->params['homeLike'] = ['label' => '仓库物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '物流信息查询', 'url' => ''];
?>

<div class="content">
    <?php echo $this->render('_search',['param'=>$param,'crminfo'=>$crminfo]); ?>
        <?php echo $this->render('_result')?>
</div>
