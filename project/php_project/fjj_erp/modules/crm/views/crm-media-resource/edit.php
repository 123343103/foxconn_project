<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/13
 * Time: 上午 10:07
 */
use yii\helpers\Url;
$this->title="修改媒体资源";
$this->params["homeLike"]=["label"=>"客户关系管理","url"=>Url::to(['/'])];
$this->params["breadcrumbs"][]=["label"=>"媒体资源管理列表","url"=>['index']];
$this->params["breadcrumbs"][]=["label"=>$this->title];
?>
<div class="content">
    <h2 class="head-first mb-20">修改媒体资源</h2>
    <?=$this->render("_form",[
        "model"=>$model,
        "options"=>$options
    ]);?>
</div>
