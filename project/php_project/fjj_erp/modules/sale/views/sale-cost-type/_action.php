<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/13
 * Time: 17:10
 */
use yii\helpers\Url;
?>

<div class="table-head">
    <p class="head">业务费用类别列表</p>
    <p class="float-right">
        <a href="<?= Url::to(['/sale/sale-cost-type/create']) ?>"><span>新增</span></a><span>编辑</span></a><a  id=""><span>查看</span></a><a><span>删除</span></a><a href="<?= Url::to(['/index/index']) ?>"><span>关闭</span></a>
    </p>
</div>