<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\helpers\Url;

$this->title = "账信申请列表";
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '账信申请列表'];
?>
<style>
    .datagrid-cell {
        padding: 0 !important;
    }

    .border_cell {
        width: 100%;
        border-bottom: 1px dotted #ccc;
        display: inline-block;
    }
</style>
<div class="content">
    <?php echo $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $queryParam
    ]); ?>
    <div class="space-10"></div>
    <?php echo $this->render('_action', ['queryParam' => $queryParam]); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data" class="main-table">
        </div>
    </div>
</div>
<script>
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "l_credit_id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            columns: [[
                {field: "check", checkbox: true},
                <?= $columns ?>
                {
                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
                    if (row.credit_status == 11 || row.credit_status == 14) {
                        return '<a title="修改" href="<?=Url::to(['update'])?>?id=' + row.l_credit_id + '"><i class="icon-edit fs-18"></i></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a title="取消" onclick="cancle_apply(' + row.l_credit_id + ')"><i class="icon-minus-sign fs-18"></i></a>';
                    } else {
                        return '<a title="修改" onclick="return false;" style="color:#ccc;"><i class="icon-edit fs-18"></i></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a style="color:#ccc;"><i class="icon-minus-sign fs-18"></i></a>';
                    }
                }
                },
            ]],
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
                }
                btnDisplay();
            },
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.l_credit_id);
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
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].l_credit_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
                btnDisplay();
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                btnDisplay();
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                btnDisplay();
            },
            onLoadSuccess: function (data) {
                $(this).datagrid('clearChecked');
                $(this).datagrid('clearSelections');
                setMenuHeight();
                datagridTip('#data');
                showEmpty($(this), data.total, 1);
                if (data.rows.length > 0) {
                    //调用mergeCellsByField()合并单元格
                    mergeCellsByField("data", "check,credit_code,status,cust_code,cust_sname,creditType,currency,payment_method,payment_clause,initial_day,pay_day,company_name,file,credit_people,credit_date,action");
                }
            },
        })
    });
    /*批量删除客户信息*/
    //    function cancle(data){
    //        var url = "<?//=Url::to(['delete'])?>//";
    //        if(data == null) {
    //            var arr = [];
    //            var arrName = [];
    //            var a = $("#data").datagrid("getChecked");
    //            for (var i = 0; i < a.length; i++) {
    //                if (a[i].credit_status != 11 && a[i].credit_status != 14) {
    ////                if(typeof(a[i]) != 'undefined'){
    //                    arr.push(a[i]);
    ////                }
    //                }
    //            }
    //            var result = [];
    //            for (var x = 0; x < a.length; x++) {
    //                var obj = a[x];
    //                var isSame = 0;
    //                for (var y = 0; y < arr.length; y++) {
    //                    var ns = arr[y];
    //                    if (ns.l_credit_id == obj.l_credit_id) {
    //                        isSame++;
    //                    }
    //                }
    //                if (isSame == 0) {
    //                    result.push(obj.l_credit_id);
    //                }
    //            }
    //            if(result.length === 0){
    //                layer.alert('无法删除',{icon:2});
    //                return false;
    //            }
    //            var id = result.join(',');
    //            var str = '';
    //            if (arr.length == 0) {
    //                str = '确定要删除该申请吗?'
    //            } else {
    //                str = arr[0].cust_sname + '等客户申请无法删除!'
    //            }
    //        }else{
    //            id = data;
    //            str = '确定要删除该申请吗?'
    //        }
    //        layer.confirm(str,
    //            {
    //                btn:['确定', '取消'],
    //                icon:2
    //            },
    //            function () {
    //                $.ajax({
    //                    type: "get",
    //                    dataType: "json",
    //                    async: false,
    //                    data: {"id": id},
    //                    url: url,
    //                    success: function (msg) {
    //                        if( msg.flag === 1){
    //                            layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
    //                        }else{
    //                            layer.alert(msg.msg,{icon:2})
    //                        }
    //                    },
    //                    error :function(msg){
    //                        layer.alert(msg.msg,{icon:2})
    //                    }
    //                })
    //            },
    //            function () {
    //                layer.closeAll();
    //            }
    //        )
    //    }

    // 显示按钮
    function btnDisplay() {
        var check = $("#data").datagrid('getChecked');
        var select = $("#data").datagrid('getSelections');
        if (select.length !== 0) {
            if (select[0].status == '待提交' || select[0].status == '驳回') {
                $("#update,#delete,#check").show();
            }else{
                $("#update,#delete,#check").hide();
            }
//            if(select[0].status == '待提交'){
//                $('#check').show();
//            }else{
//                $('#check').hide();
//            }
        }
//        if (select.length == 1 && (select[0].status == '待提交' || select[0].status == '驳回')) {
//            $("#update,#delete,#check").show();
//            return;
//        }
//
//        if (select.length == 1 && !(select[0].status == '待提交' || select[0].status == '驳回')) {
//            $("#update,#delete,#check").hide();
//            return;
//        }
//        if (check.length == 0) {
//            $("#update,#delete,#check").hide();
//            return;
//        }
        if (check.length > 1) {
            $("#update,#check").hide();
            $("#delete").show();
//            for (i=0;i<check.length;i++) {
//                if (check[i].status == '审核中' || check[i].status == '审核完成') {
//                    $("#delete").hide();
//                    break;
//                } else if (check[i].status == '驳回') {
//                    $("#delete").show();
//                } else {
//                    $("#delete").show();
//                }
//            }
        }
    }
    /*修改*/
    $('#update').click(function () {
        var a = $("#data").datagrid("getSelected");
        if (a == null) {
            layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
            return false;
        } else {
            if (a.credit_status == 12 || a.credit_status == 13) {
                layer.alert("审核中或者审核完成,无法修改!", {icon: 2, time: 5000});
                return false;
            }
            window.location.href = "<?=Url::to(['update'])?>" + "?id=" + a.l_credit_id
        }

    })

    /*详情*/
    $('#view').click(function () {
        var a = $("#data").datagrid("getSelected");
        if (a == null) {
            layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
            return false;
        } else {
            window.location.href = "<?=Url::to(['view'])?>" + "?id=" + a.aid;
        }

    })

    /*送审*/
    $('#check').click(function () {
        var a = $("#data").datagrid("getSelected");
        if (a.credit_status == 12 || a.credit_status == 13) {
            layer.alert("审核中或者审核完成,无法送审!", {icon: 2, time: 5000});
            return false;
        }
        var id = a.l_credit_id;
        var url = "<?=Url::to(['index'])?>" + "?id=" + id;
        var type = a.credit_type;
        $.ajax({
            type: "get",
            dataType: "json",
            async: false,
            data: {"id": id},
            url: "<?= Url::to(['verify']) ?>",
            success: function (msg) {
                if (msg == false) {
                    layer.alert("送审失败!", {icon: 2});
                    return false;
                }
                $.fancybox({
                    href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 750,
                    height: 480
                });
            }
        })

    });
    // 信息导出
    $("#export").click(function () {
        layer.confirm("确定导出?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                layer.closeAll();
                window.location.href = "<?= Url::to(['export', 'CrmCreditApplySearch' => $queryParam['CrmCreditApplySearch']]) ?>"
            },
            function () {
                layer.closeAll();
            }
        );
    });
    //    function fileDownload(file) {
    //        $.ajax({
    //            type: "get",
    //            dataType: "json",
    //            url: "<?//= Url::to("file-download?file=") ?>//" + file,
    //            success: function (msg) {
    //                if( msg.flag === 1){
    //                    layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
    //                }
    //            },
    //            error :function(msg){
    //                alert('error');
    //                layer.alert(msg.msg,{icon:2})
    //            }
    //        })
    //    }


    //合并单元格
    function mergeCellsByField(tableID, colList) {
        var ColArray = colList.split(",");
        var tTable = $("#" + tableID);
        var TableRowCnts = tTable.datagrid("getRows").length;
        var tmpA;
        var PerTxt = "";
        var CurTxt = "";
        var alertStr = "";
        for (j = ColArray.length - 1; j >= 0; j--) {
            PerTxt = "";
            tmpA = 1;
            for (i = 0; i <= TableRowCnts; i++) {
                if (i == TableRowCnts) {
                    CurTxt = "";
                }
                else {
                    CurTxt = tTable.datagrid("getRows")[i]['credit_code'];
                }
                if (PerTxt == CurTxt) {
                    if (CurTxt != null) {
                        tmpA += 1;
                    }
                }
                else {
                    tTable.datagrid("mergeCells", {
                        index: i - tmpA,
                        field: ColArray[j],　　//合并字段
                        rowspan: tmpA,
                        colspan: null
                    });

                    tmpA = 1;
                }
                PerTxt = CurTxt;
            }
        }
    }

    function cancle_apply(data){
            var arr = [];
            var id;
            var a = $("#data").datagrid("getChecked");
            if(data == null){
                for (var i = 0; i < a.length; i++) {
                    if (a[i].credit_status != 11 && a[i].credit_status != 14) {
//                if(typeof(a[i]) != 'undefined'){
                        arr.push(a[i]);
//                }
                    }
                }
                var result = [];
                for (var x = 0; x < a.length; x++) {
                    var obj = a[x];
                    var isSame = 0;
                    for (var y = 0; y < arr.length; y++) {
                        var ns = arr[y];
                        if (ns.l_credit_id == obj.l_credit_id) {
                            isSame++;
                        }
                    }
                    if (isSame == 0) {
                        result.push(obj.l_credit_id);
                    }
                }
                if(result.length === 0){
                    layer.alert('无法删除',{icon:2});
                    return false;
                }
                id = result.join(',');
            }else{
                id = data;
            }

        $.fancybox({
            href:"<?=Url::to(['cancle-apply'])?>?id="+id,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:445,
            height:240
        });
    }
</script>