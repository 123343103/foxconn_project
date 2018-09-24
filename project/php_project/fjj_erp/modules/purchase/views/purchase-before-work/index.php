<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/13
 * Time: 上午 08:16
 */
use yii\helpers\Url;

$this->title = "采购前置作业";
$this->params['homeLike'] = ['label' => '采购管理'];
$this->params['breadcrumbs'][] = ['label' => '采购前置作业'];
?>
<div class="content">
    <?= $this->render('_search', [
        'downList' => $downList,
        'ReqDcts'=>$ReqDcts,
        'sppdpt' => $sppdpt,
        'comman' => $comman,
        'param' => $param,
        'receipt'=>$receipt,
        'buyaddr'=>$buyaddr
    ]) ?>
    <div class="table-content">
        <?php echo $this->render('_action'); ?>
        <div class="space-10"></div>
        <div id="data"></div>
    </div>
</div>
<script>
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function () {
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "req_dt_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect:true,
            checkOnSelect: false,
            selectOnCheck: false,
            pageSize: 10,
            pageList: [10, 20, 30],
            columns: [[
                {field: 'ck', checkbox: true},
                <?= $fields ?>
            ]],
            onLoadSuccess: function (data) {
                if (data.total === 0) {
                    $("#export").hide().next().hide();
                } else {
                    $("#export").show().next().show();
                }
                datagridTip("#data");
                showEmpty($(this), data.total, 1);
                setMenuHeight();
                $("#data").datagrid('clearSelections').datagrid('clearChecked');
                $("#procurement").hide().next().hide();
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
                }
                else if (a1.length==0){
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    $("#procurement").hide();
                    $("#procurement").prev().hide();
                    $("#add-visit-record1").hide();
                    $("#add-visit-record1").prev().hide();
                }
                else{
                    var a2 = $("#data").datagrid("getChecked");
                    $('#data').datagrid("unselectAll");
                    for(var i=0;i<a2.length;i++) {
                        $("#procurement").show();
                        $("#procurement").prev().show();
                        $("#add-visit-record1").show();
                        $("#add-visit-record1").prev().show();
                    }
                }

            },
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.req_dt_id);
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
                $('#data').datagrid('checkRow', index);
                $("#procurement").show();
                $("#procurement").prev().show();
                $("#add-visit-record1").show();
                $("#add-visit-record1").prev().show();
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length >= 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].req_dt_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $("#procurement").hide();
                    $("#procurement").prev().hide();
                    $("#add-visit-record1").hide();
                    $("#add-visit-record1").prev().hide();
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
                $("#procurement").show();
                $("#procurement").prev().show();
                $("#add-visit-record1").show();
                $("#add-visit-record1").prev().show();
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#procurement").hide();
                $("#procurement").prev().hide();
                $("#add-visit-record1").hide();
                $("#add-visit-record1").prev().hide();
                isCheck = false;
                isSelect = false;
                onlyOne = false;
            },
            onUncheckAll: function (rowIndex, rowData) {
                $("#procurement").hide();
                $("#procurement").prev().hide();
                $("#add-visit-record1").hide();
                $("#add-visit-record1").prev().hide();
                isCheck = false;
                isSelect = false;
                onlyOne = false;
            }
        })

        $("#procurement").on("click", function () {
            var arr = [];
            var areaid=null;
            var obj = $("#data").datagrid("getChecked");
            for (var i = 0; i < obj.length; i++) {
                arr.push(obj[i].req_dt_id);
                areaid=obj[i].area_id;
            }
            var id = arr.join(',');
            var buyer=<?=$param['staff']?>;
            var reqdct=$("#reqdct").val();
            var legid=$("#legid").val();
            window.location.href = "<?=Url::to(['procurement'])?>" + "?id=" + id + "&buyer="+buyer+"&reqdct="+reqdct+"&legid="+legid+"&areaid="+areaid;
        });
    })
</script>
