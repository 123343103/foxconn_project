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
    <p class="head">出差申请及消单报告列表</p>
    <p class="float-right">
        <a href="<?= Url::to(['/sale/sale-trip/create-travel-apply']) ?>"><span>新增出差申请</span></a><a href="<?= Url::to(['/sale/sale-trip/create-report']) ?>" id=""><span>新增消单报告</span></a><a id="" href="<?= Url::to(['/sale/sale-trip/create-cost']) ?>"><span>新增费用报销</span></a><a  id="send"><span>编辑</span></a><a  id="export"><span>查看</span></a><a><span>变更</span></a><a><span>送审</span></a><a><span>查看</span></a><a><span>导出</span></a><a href="<?= Url::to(['/index/index']) ?>"><span>关闭</span></a>
    </p>
</div>