<?php
/**
 * User: 3859386
 * date:
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '招商类目负责人设置列表';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <div class="width-450 float-right mt-20">
        <?php echo $this->render('_action'); ?>
    </div>
    <div class="mt-20">
        <?php echo $this->render('_search', [
            'downList' => $downList,
            'search' => $search
        ]); ?>
    </div>

    <!--    <div class="space-20"></div>-->
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data">
        </div>
    </div>
</div>
<script>
    $(function () {
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url;?>",
            rownumbers: true,
            method: "get",
            idField: "id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            columns: [[
                {field: "ck", checkbox: true, width: 20},
                {field: "staff_code", title: "人员工号", width: 150},
                {field: "staff_name", title: "人员姓名", width: 100},
                {field: "typeName", title: "商品分类", width: 200},
                {field: "mpdt_status", title: "状态", width: 100},
                {field: "mpdt_remark", title: "备注", width: 100},
                {field: "create_by", title: "档案建立人", width: 100},
                {field: "create_at", title: "建档日期", width: 150},
                {field: "update_by", title: "最后修改人", width: 100},
                {field: "update_at", title: "修改日期", width: 150},
                {
                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
                    return '<a onclick="MchpdtDelete(' + row.id + ')"><i class="icon-minus-sign fs-18"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;' + '<a onclick="action(true,' + row.id + ')"><i class="icon-edit fs-18"></i></a>';
                }
                }
            ]],
            onLoadSuccess: function (data) {
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                $("#update").hide();
                $("#update").next().hide();
                $("#delete").hide();
                $("#delete").next().hide();
                setMenuHeight();
                datagridTip('#data');
                showEmpty($(this), data.total, 1);
            },
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.id);
                $('#data').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    var a = $("#data").datagrid("getChecked");
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                }
                isCheck = false;
                $('#data').datagrid('checkRow', index);

                $("#update").show();
                $("#update").next().show();
                $("#delete").show();
                $("#delete").next().show();
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
                    $("#delete").show();
                    $("#delete").next().show();
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $("#update").hide();
                    $("#update").next().hide();
                    $("#delete").show();
                    $("#delete").next().show();
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $("#update").hide();
                $("#update").next().hide();
                $("#delete").show();
                $("#delete").next().show();
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#load-content").hide();
                $("#delete").hide();
                $("#delete").next().hide();
            },
        });

    })
    function MchpdtDelete(data) {
        var arr = [];
        var id;
        var url = "<?=Url::to(['delete'])?>";
        console.log("url");
        if (data == null) {
            var a = $("#data").datagrid("getChecked");
            for (var i = 0; i < a.length; i++) {
                arr.push(a[i].id);
            }
            id = arr.join(',');
        } else {
            id = data;
        }
        data_delete(id, url);
    }
</script>