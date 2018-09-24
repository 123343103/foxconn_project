<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/26
 * Time: 上午 11:01
 */
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<div class="content">
    <h3 class="head-first">导入错误日志</h3>
    <table class="table">
        <thead>
        <th>#</th>
        <th>错误位置</th>
        <th>错误信息</th>
        </thead>
        <tbody>
        <?php foreach($logs as $k=>$log){ ?>
        <tr>
            <td width="100"><?=$k+1?></td>
            <td width="300" align="left"><?=$log['file'];?></td>
            <td align="left"><?=$log['message'];?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<style>
</style>