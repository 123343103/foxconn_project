<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/10/24
 * Time: 上午 08:22
 */

$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '料号管理'];
$this->params['breadcrumbs'][] = ['label' => '料号大分类维护'];
?>
<div class="content" xmlns="http://www.w3.org/1999/html">

    <?= $this->render('_form', [
        'model' => $model
        ,'title' => $title
        , 'level_all' => $level_all
        , 'is_special' => $is_special
    ]);
    ?>
</div>

