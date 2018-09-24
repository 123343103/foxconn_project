<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;

JqueryUIAsset::register($this);
?>
<div class="head-first">选择用户</div>
<div style="margin: 0 20px 20px;">
    <div class="mb-10">
        <?php ActiveForm::begin(['id' => 'select-user', 'method' => 'get', 'action' => Url::to(['select-user'])]); ?>
        <label class="width-60">关键词</label>
        <input type="text" class="width-200" id="searchText"
               name="UserSearch[searchKeyword]"
               placeholder="账户\工号\名称\部门"
               value="<?= $params['searchKeyword'] ?>">
        <button class="button-blue" id="search">查询</button>
        <button type="button" class="button-white" onclick="window.location.href='<?= Url::to(['select-product']) ?>'">
            重置
        </button>
        <?php ActiveForm::end(); ?>
    </div>
    <table id="data" style="width:100%;max-height: 250px;"></table>
</div>
<div class="text-center mb-20">
    <button type="button" class="button-blue mr-20" id="confirm-user">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "user_account", title: "账户", width: 120},
                {field: "staff_code", title: "工号", width: 120},
                {field: "staff_name", title: "名称", width: 120},
                {field: "organization_name", title: "部门", width: 284}
            ]],
            onLoadSuccess: function (data) {
                datagridTip("#data");
                showEmpty($(this), data.total, 1);
            }
        });

        //添加到parent
        $("#confirm-user").click(function () {
            var select = $("#data").datagrid('getSelected');
            if (select == null) {
                layer.alert('请选择一条数据！',{icon:2});
            }
            parent.pObj.val(select.staff_name);
            parent.pObj.prev().val(select.user_id);
            if (parent.pObj.prev().hasClass('user')) {
                parent.changeUser(select.staff_name);
            }
            parent.$.fancybox.close();
        });
    });
</script>