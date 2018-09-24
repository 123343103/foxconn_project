<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use \app\classes\Menu;
$this->title="会员开发任务列表";
$this->params['homeLike']=['label'=>'客户关系管理'];
$this->params['breadcrumbs'][]=['label'=>'会员开发'];
$this->params['breadcrumbs'][] = ['label' => '分配给我的资料列表'];
?>
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
            singleSelect: true,         //只能选中一行
            selectOnCheck: false,        //选中复选框同时选中行
            checkOnSelect: false,        //选中行同时选中复选框
            columns: [[
                {field:"",checkbox:true},
                <?= $columns ?>
                {
                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
                    <?php if(Menu::isAction('/crm/crm-member-develop/update')){ ?>
                        return '<a href="<?=Url::to(['update'])?>?id=' + row.cust_id + '"><i class="icon-edit fs-18"></i></a>';
                    <?php } ?>
                }

                },
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
                $("#update,#addVisit,#data1,#reminders").removeClass('display-none');
                $('#record').datagrid("loading");
                $('#reminder').datagrid("loading");
                $('#message').datagrid("loading");
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
                    $("#data1").removeClass('display-none');
                    $("#update,#addVisit,#reminders").addClass('display-none');
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
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                        $("#load-content").hide();
                    }
                    $("#update,#addVisit,#data1,#reminders").addClass('display-none');
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $("#data1").removeClass('display-none');
                $("#update,#addVisit,#reminders").addClass('display-none');
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#load-content").hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                $("#load-content").hide();
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#update,#addVisit,#data1,#reminders").addClass('display-none');
            },
            onLoadSuccess: function (data) {
                $(this).datagrid('clearChecked');
                $(this).datagrid('clearSelections');
                $("#update,#addVisit,#data1,#reminders").addClass('display-none');
                datagridTip('#data');
                showEmpty($(this),data.total,1);
            },

        })
    });
//    $("#create").on('click',function(){
//        $("#create").attr("href", "<?//= Url::to(['create']) ?>//");
//        $("#create").fancybox({
//            padding: [],
//            fitToView: false,
//            width: 800,
//            height: 530,
//            autoSize: false,
//            closeClick: false,
//            openEffect: 'none',
//            closeEffect: 'none',
//            type: 'iframe'
//        });
//    });
    /*修改*/
    $("#update").on("click",function(){
        var a = $("#data").datagrid("getSelected");
        if(a == null){
            layer.alert("请点击选择一条数据!",{icon:2,time:5000});
            return false;
        }else{
//            $("#update").attr("href", "<?//= Url::to(['update']) ?>//?id="+a.cust_id);
            window.location.href = "<?=Url::to(['update'])?>?id=" + a.cust_id;
        }
    });
    /*详情*/
//    $("#view").on("click", function () {
//        var a = $("#data").datagrid("getSelected");
//        if(a == null){
//            layer.alert("请点击选择一条数据!",{icon:2,time:5000});
//            return false;
//        }else{
//            var c = $("#record").datagrid("getSelected");
//            if(c == null){
//                window.location.href = "<?//=Url::to(['view'])?>//?id=" + a.cust_id+"&ctype=1";
//            }else{
//                window.location.href = "<?//=Url::to(['view'])?>//?id=" + a.cust_id + "&childId="+ c.sil_id+"&ctype=1";
//            }
//        }
//    });

</script>
