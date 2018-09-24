<?php
/**
 * User: F3859386
 * Date: 2017/2/23
 * Time: 17:30
 */
use yii\helpers\Url;

$this->title = "招商会员列表";
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '招商会员列表'];

if (Yii::$app->user->identity->is_supper != 1) {
    $search['mchpdtype_sname'] = yii::$app->user->identity->staff->staff_name;
}
?>
<div class="content">
    <div class="space-10"></div>
    <?php echo $this->render('_search', [
        'downList' => $downList,
        'search' => $search
    ]); ?>
    <div class="space-20"></div>
    <?php echo $this->render('_action', [
        'search' => $search
    ]); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data" class="main-table">
        </div>
        <div id="load-content"></div>

    </div>

</div>
<script>
    var id;
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    var name = "<?=$search['mchpdtype_sname']?>";
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
//            queryParams:{"CrmCustomerInfoSearch[mchpdtype_sname]":name},
            method: "get",
            idField: "cust_id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            columns: [[
                {field: "ck", checkbox: true, width: 200},
                <?= $columns ?>
                {
                    field: "name", title: "操作", width: 50, formatter: function (val, row) {
                    return '<a href="<?=Url::to(['update'])?>?id=' + row.cust_id + '"><i class="icon-edit fs-18"></i></a>'
                }
                }
            ]],
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.cust_id);
                $('#data').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    var a = $("#data").datagrid("getChecked");
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                    isCheck = false;
                }
//                isCheck = false;
                //console.log(isCheck);
                //console.log(isSelect);
                //console.log(onlyOne);
                $('#data').datagrid('checkRow', index);
                $("#load-content").show();

                $("#update").show();
                $("#update").next().show();
                $("#shopInfo").show();
                $("#shopInfo").next().show();
                $("#reminders").show();
                $("#reminders").next().show();
                $("#add-visit-record").show();
                $("#add-visit-record").next().show();

//                $("#data").datagrid("uncheckAll");
//                $("#data").datagrid("checkRow",rowIndex);
                $(".datagrid-menu .hide").removeClass("hide");
                var id = rowData['cust_id'];
                $('#load-content').load("<?=Url::to(['load-info']) ?>?id=" + id + "&ctype=6", function () {
                    setMenuHeight();
                });
            },
            onCheck: function (rowIndex, rowData) {
                var a1 = $("#data").datagrid("getChecked");
                if (a1.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                } else {
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");

                    $("#update").hide();
                    $("#update").next().hide();
                    $("#shopInfo").hide();
                    $("#shopInfo").next().hide();
                    $("#reminders").hide();
                    $("#reminders").next().hide();
                    $("#add-visit-record").hide();
                    $("#add-visit-record").next().hide();
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].cust_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $("#update").hide();
                    $("#update").next().hide();
                    $("#shopInfo").hide();
                    $("#shopInfo").next().hide();
                    $("#reminders").hide();
                    $("#reminders").next().hide();
                    $("#add-visit-record").hide();
                    $("#add-visit-record").next().hide();
                    $('#data').datagrid("unselectAll");
                    $("#load-content").hide();
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                        $("#load-content").hide();
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $("#update").hide();
                $("#update").next().hide();
                $("#shopInfo").hide();
                $("#shopInfo").next().hide();
                $("#reminders").hide();
                $("#reminders").next().hide();
                $("#add-visit-record").hide();
                $("#add-visit-record").next().hide();
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#load-content").hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                $("#load-content").hide();
                isCheck = false;
                isSelect = false;
                onlyOne = false;
            },
            onLoadSuccess: function (data) {
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                $("#update").hide();
                $("#update").next().hide();
                $("#shopInfo").hide();
                $("#shopInfo").next().hide();
                $("#reminders").hide();
                $("#reminders").next().hide();
                $("#add-visit-record").hide();
                $("#add-visit-record").next().hide();
                datagridTip('#data');
                showEmpty($(this), data.total, 1);
            }
        });
    });
</script>

