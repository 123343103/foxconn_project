<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/21
 * Time: 下午 04:54
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<h1 class="head-first">选择收货中心</h1>
<?php $form = ActiveForm::begin(['id' => 'select-form']); ?>
<div style="margin:0 15px">
    <div class="mb-10">
        <input id="rcpno" value="<?=$rcpno['rcp_no']?>" type="text" name="rcp_no" style="width:200px;" placeholder="关联收货中心代码/名称">
        <button id="query_btn" type="submit" class="search-btn-blue" style="margin-left:10px;">查询</button>
        <button id="reset_btn" type="submit" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
    </div>
    <div id="receipt_info" style="width:100%;"></div>
    <div style="text-align:center;">
        <button type="button" class="button-blue" id="confirm_btn">确定</button>
        <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
    </div>
</div>
<?php ActiveForm::end();?>
<script>
    $(function () {
        $("#receipt_info").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            singleSelect: true,
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            idField: "rcp_id",
            columns: [[
                {field: "rcp_no", title: "关联收货中心代码", width: 290},
                {field: "rcp_name", title: "关联收货中心", width: 350}
            ]],
            onLoadSuccess:function(data){
                showEmpty($(this),data.total,1);
            },
            onDblClickRow:function (index,data) {
                var rows = $("#receipt_info").datagrid('getSelected');
                parent.addTax("",rows);//第一个参数为税别/税率 (这里为空)，第二个参数为关联收货中心
                parent.$.fancybox.close();
            }
        });
        $("#confirm_btn").click(function () {
            var rows = $("#receipt_info").datagrid('getSelected');
            if (rows.length == 0) {
                layer.alert("请至少选择一个关联收货中心",{icon:2});
                return false;
            }
            parent.addTax("",rows);//第一个参数为税别/税率 (这里为空)，第二个参数为关联收货中心
            parent.$.fancybox.close();
        });
        $("#query_btn").click(function () {
            var rcp_no=$("#rcpno").val().trim();
           $("#select-form").attr('action','<?=Url::to(['receipt-info'])?>?rcp_no='+rcp_no);
        });
        //重置
        $("#reset_btn").click(function () {
            $("#rcpno").val("");
            var rcp_no="";
            $("#select-form").attr('action','<?=Url::to(['receipt-info'])?>?rcp_no='+rcp_no);
        });
        //ajaxSubmitForm($("#select-form"));
    })
</script>
