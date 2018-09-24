<?php
/**
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2016/10/13
 * Time: 8:55
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="table-head">
    <p class="head">供应商申请列表</p>
    <p class="float-right">
<!--        --><?//= Html::a("<span class='text-center ml--5'>新增</span>", null,['id'=>'create']) ?>
        <?= Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update']) ?>
        <?= Html::a("<span class='text-center ml--5'>查看</span>", null,['id'=>'view']) ?>
        <?= Html::a("<span class='text-center ml--5'>删除</span>", null,['id'=>'delete']) ?>
    </p>
</div>
<div class="space-10"></div>
<script>
    var childId;
    $(function () {
        /**
         * 选中事件
         * 1.开啓多选
         * 2.加载子表url
         * 3.加载子表的位置
         */
        selectable(true,"<?= Url::to(['load-info']) ?>",$("#load-info"));

        //删除
        deleteOne($("#delete"), "<?=Url::to(['/ptdt/supplier/delete']) ?>");
        //更新
        updateButton($("#update"), "<?=Url::to(['/ptdt/supplier/update'])?>", {parameters: "id", child: false});
        //查看
        viewButton($("#view"), "<?=Url::to(['/ptdt/supplier/view'])?>", {parameters: "id", child: false});

    })
</script>
