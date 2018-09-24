<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/7/17
 * Time: 下午 02:26
 */

use yii\helpers\Url;
?>

<div class="space-10"></div>
<table class="product-list" style="width:100%">
    <thead>
    <tr>
        <th style="width:3%">序号</th>
        <th style="width:15%">商品料号</th>
        <th style="width:8%">商品名称</th>
        <th style="width:8%">库存下限</th>
        <th style="width:8%">安全库存</th>
        <th style="width:8%">现有库存</th>
        <th style="width:8%"">库存上限</th>
        <th style="width:15%">备注</th>
    </tr>
    </thead>
    <tbody>
<?php $int =1 ?>
    <?php foreach ($priceList as $key => $val) { ?>
        <tr>
            <td> <?= $int ?></td>
            <td><?= $val->part_no ?></td>
            <td><?= $val->pdt_name ?></td>
            <td><?= $val->down_nums ?></td>
            <td><?= $val->save_num ?></td>
            <td><?= $val->invt_num ?></td>
            <td><?= $val->up_nums ?></td>
            <td title="<?= $val->remarks ?>"><?= $val->remarks ?></td>
        </tr>
        <?php $int =$int+1 ?>
    <?php } ?>
    </tbody>
    </thead>
</table>
