<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/6
 * Time: 下午 03:15
 */
?>
<h1 class="head-first"> 查看进度</h1>
<div class="content">
    <div id="record" style="width:740px;"></div>
</div>
<script>
    $(function(){
        $("#record").datagrid({
            url: "<?= \yii\helpers\Url::to(['/system/verify-record/load-record']);?>?id=<?=$verifyId?>",
            rownumbers: true,
            method: "get",
            idField: "vcoc_id",
            loadMsg: false,
            singleSelect: true,
            columns: [[
                {field: "verifyOrg", title: "审核节点", width: 100},
                {field: "verifyName", title: "审核人", width: 100},
                {field: "vcoc_datetime", title: "审核时间", width: 100},
                {field: "verifyStatus", title: "操作", width: 100},
                {field: "vcoc_remark", title: "审核意见", width: 150},
                {field: "vcoc_computeip", title: "审核IP", width: 150}

            ]],
            onLoadSuccess: function (data) {
                datagridTip('#record');
                showEmpty($(this), data.total, 0);
            }
        });
    });
</script>
