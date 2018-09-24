<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/11/1
 * Time: 上午 10:03
 */
use yii\helpers\Url;
?>

<div class="table-head">
    <p class="head">计划列表</p>
    <p class="float-right">
        <a href="<?= Url::to(['/ptdt/material-code/create']) ?>"> <span>新增</span></a>
        <a class="modify"><span>修改</span></a>
        <a class="check"><span>查看</span></a>
        <a><span>提交</span></a>
        <a class="cancle"><span>刪除</a>
        <a id="add_resume"><span class="width-100">定价申请</span></a>
        <!--<a><span>关闭</span></a>-->
    </p>
</div>
