<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/10/24
 * Time: 10:01
 */
use yii\helpers\Url;
$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程'];
$this->params['breadcrumbs'][] = ['label' => '厂商呈报列表'];
$this->params['breadcrumbs'][] = ['label' => '新增呈报'];
$this->title = '新增厂商呈报列表';/*BUG修正 增加title*/
?>
<div class="content">
    <h1 class="head-first">
        新增呈报
    </h1>

        <?= $this->render("_form",[
            'downList'=>$downList,
            'result'=>$result,
        ]) ?>

</div>
