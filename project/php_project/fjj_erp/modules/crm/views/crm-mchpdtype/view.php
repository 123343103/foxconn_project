<?php
/**
 * User: F3859386
 * Date: 2017/5/17
 * Time: 17:17
 */

?>
<div class="pop-head">
    <p>招商类目负责人详情</p>
</div>
<div class="content">
    <div class="mb-30 mt-30">
        <label class="width-160">商品分类</label>
        <span class="width-200"><?= $model['category_name'] ?></span>
    </div>
    <div class="mb-30">
        <label class="width-160">人员</label>
        <span class="width-200"><?= $model['staff_name'] ?></span>
    </div>
    <div class="mb-30">
        <label class="width-160">备注</label>
        <span class="width-200"><?= $model['mpdt_remark'] ?></span>
    </div>
</div>
