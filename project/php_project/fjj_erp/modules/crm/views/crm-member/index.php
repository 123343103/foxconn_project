<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use app\classes\Menu;
$this->title="会员列表";
$this->params['homeLike']=['label'=>'客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '会员列表'];
?>
<style>
    .display-none{
        display: none;
    }
</style>
<div class="content">
    <?php  echo $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $queryParam
    ]); ?>

    <?php echo $this->render('_action',['queryParam' => $queryParam]); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data" class="main-table">
        </div>
        <div class="space-30"></div>
        <div id="load-content"></div>
    </div>
</div>
<script>
    $("#add_btn,#edit_btn").addClass('display-none');
    var id;
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck:false,
            checkOnSelect:false,
            columns: [[
                {field:"",checkbox:true},
                <?= $columns ?>
                {
                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
                    return '<?= Menu::isAction('/crm/crm-member/delete')?"<a onclick=\"cancle(' + row.cust_id + ')\"><i class=\"icon-minus-sign fs-18\"></i></a>":'' ?>&nbsp;&nbsp;'+'&nbsp;&nbsp;<?= Menu::isAction('/crm/crm-member/update')?"<a onclick=\"update_mes('+ row.cust_id +')\"><i class=\"icon-edit fs-18\"></i></a>":'' ?>';
                }
                }
            ]],
            onSelect: function(rowIndex,rowData) {
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
                $("#load-content").show();
                $('#load-content').load("<?=Url::to(['load-info']) ?>?id=" + rowData['cust_id'], function () {
                    setMenuHeight();
                });
                $('#data').datagrid('checkRow', index);
                $("#delete,#update,#view,#backVisit,.turn_investment,.turn_sales,#reminders").removeClass('display-none');

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
                    $("#delete,.turn_investment,.turn_sales").removeClass('display-none');
                    $("#update,#view,#backVisit,#reminders").addClass('display-none');
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
                    $('#data').datagrid("unselectAll");
                    $("#load-content").hide();
                    $("#delete,#update,#view,#backVisit,.turn_investment,.turn_sales,#reminders").addClass('display-none');
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
                $("#delete,.turn_investment,.turn_sales").removeClass('display-none');
                $("#update,#view,#backVisit,#reminders").addClass('display-none');
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#load-content").hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                $("#load-content").hide();
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#delete,.turn_investment,.turn_sales").addClass('display-none');
            },
            onLoadSuccess:function(data){
                $(this).datagrid('clearChecked');
                $(this).datagrid('clearSelections');
                $("#delete,#update,#view,#backVisit,.turn_investment,.turn_sales,#reminders").addClass('display-none');
                setMenuHeight();
                datagridTip('#data');
                showEmpty($(this),data.total,1);
            },

        })
    });
    /*修改*/
    $("#update").on("click",function(){
        var a = $("#data").datagrid("getSelected");
        if(a == null){
            layer.alert("请点击选择一条数据!",{icon:2,time:5000});
            return false;
        }else {
            window.location.href = "<?=Url::to(['update'])?>"+"?id=" + a.cust_id;
        }
    });


    /*详情*/
    $("#view").on("click",function(){
        var a = $("#data").datagrid("getSelected");
        if(a == null){
            layer.alert("请点击选择一条数据!",{icon:2,time:5000});
            return false;
        }else {
            window.location.href = "<?=Url::to(['view'])?>"+"?id=" + a.cust_id;
        }
    });
    /*回访*/
    $("#backVisit").on("click",function(){
        var a = $("#data").datagrid("getSelected");
        if(a == null){
            layer.alert("请点击选择一条数据!",{icon:2,time:5000});
            return false;
        }else {
            window.location.href = "<?=Url::to(['visit-create'])?>"+"?id=" + a.cust_id + "&ctype=2";
        }
    });

    /*提醒事项*/
    $("#reminders").on("click",function(){
        var a = $("#data").datagrid("getSelected");

        $("#reminders").fancybox({
            padding: [],
            fitToView: false,
            width: 730,
            height: 450,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['create-reminders']) ?>?id=" +a.cust_id
        });
    });
    function update_mes(id){
        window.location.href = "<?=Url::to(['update'])?>"+"?id=" + id;
    }
    /*批量删除客户信息*/
    function cancle(data){
        var arr = [];
        var id;
        var url = "<?=Url::to(['delete-customer'])?>";
        if(data == null){
            var a = $("#data").datagrid("getChecked");
            for(var i =0;i<a.length;i++){
                arr.push(a[i].cust_id);
            }
            id = arr.join(',');
        }else{
            id = data;
        }
        data_delete(id,url);
    }
</script>
