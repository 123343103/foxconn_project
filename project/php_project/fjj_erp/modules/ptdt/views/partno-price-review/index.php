<?php
/**
 * User: F1676624
 * Date: 2016/10/24
 */

use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '商品定价'];
$this->params['breadcrumbs'][] = ['label' => '商品审核表'];
?>
<div class="content">
    <?php echo $this->render('_search',[
        'statusType'=>$statusType
    ]); ?>
    <div class="table-content">
        <div class="table-head">
            <p class="float-right">
                <a id="price"><span>去定价</span></a>
                <a id="check"><span>核价</span></a>
                <a id="view"><span>查看</span></a>
                <a id="edit"><span>修改</span></a>
                <a id="export"><span>导出</span></a>
                <a id="delete"><span>删除</a>
            </p>
        </div>
        <div class="space-30"></div>
        <div id="data"></div>
        <div id="load-content" class="overflow-auto"></div>
    </div>
    <div class="space-30"></div>
</div>
<script>
    $(function () {
        "use strict";
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "part_no",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            //设置复选框和行的选择状态不同步
            checkOnSelect: false,
            selectOnCheck: false,
            fitColumns: true,
            columns: [[
                {field: 'ck', checkbox: true, align: 'left'}
                , {field: 'part_no', title: '料号'}
                , {field: 'pdt_manager', title: '商品经理人'}
                ,{
                    field: 'pdt_level',
                    title: '商品定位',
                    formatter:function(value,row,index){
                        var statusArr=["","高","中","低"];
                        return statusArr[row.pdt_level];
                    }
                }
                , {field: 'price_no', title: '申请单号',width:30}
                , {field: 'supplier_name_shot', title: '供应商简称',width:30}
                , {field: 'num_area', title: '数量区间',width:30}
                , {field: 'buy_price', title: '价格',width:30}
                , {field: 'valid_date', title: '有效期',width:30}
                ,{
                    field: 'status',
                    title: '状态',
                    formatter:function(value,row,index){
                        var statusArr=["未定价","发起定价","商品开发维护","审核中","已定价","被驳回","已逾期","重新定价"];
                        return statusArr[row.status];
                    }
                }
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            },
            onCheckAll: function (rowIndex, rowData) {  //checkbox 全选中事件
                //设置选中事件，清除之前单行选择
                $("#data").datagrid("unselectAll");
                $('#load-content').empty();
            },
            onCheck: function (rowIndex, rowData) {  //checkbox 选中事件
                //设置选中事件，清除之前单行选择
                $("#data").datagrid("unselectAll");
                $('#load-content').empty();
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var id = rowData['part_no'];
                //设置选中事件，清除之前多行选择
                $("#data").datagrid("uncheckAll");
            }
        });

        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录'
        });
        $("#view").on("click", function () {
            var row = $("#data").datagrid("getSelected");

            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row['id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });

        $("#check").on("click", function () {
            var row = $("#data").datagrid("getSelected");

            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row['id'];
                window.location.href = "<?=Url::to(['edit'])?>?id=" + id;
            }
        });

        $("#edit").on("click", function () {
            var row = $("#data").datagrid("getSelected");

            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row['id'];
                window.location.href = "<?=Url::to(['edit'])?>?id=" + id;
            }
        });
        $("#price").on("click", function () {
            var row = $("#data").datagrid("getSelected");
            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row.id;
                window.location.href = "<?=Url::to(['partno-price-confirm/edit'])?>?id=" + id;
            }
        });


        $('#export').click(function() {
            var page = $("#data" ).datagrid("getPager" ).data("pagination" ).options.pageNumber;
            var rows = $("#data" ).datagrid("getPager" ).data("pagination" ).options.pageSize;
            var index = layer.confirm("确定导出核价信息?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },
                function () {
                    if(window.location.href="<?= Url::to(['index', 'export' => '1'])?>&page="+page+"&rows="+rows){
                        layer.closeAll();
                    }else{
                        layer.alert('导出核价信息发生错误',{icon:0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        });



        $("#delete").on("click",function(){
            var rows = $("#data").datagrid("getChecked");
            var ids=new Array();
            for(var x=0;x<rows.length;x++){
                ids.push(rows[x].id);
            }
            if(ids.length<1){
                layer.alert("请选择一条记录",{icon: 2});
            }else{
                layer.confirm("确定要删除选中的记录吗？",{
                    btn: ['确定', '取消'],
                    icon: 2
                },function(){
                    $.ajax({
                        type:"get",
                        dataType:"json",
                        data:{id:ids.join()},
                        url: "<?=Url::to(['delete']) ?>",
                        success:function(res){
                            if(res.flag==1){
                                layer.closeAll();
                                $("#data").datagrid("reload",{
                                    url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                                    onLoadSuccess:function(){
                                        $("#data").datagrid("clearChecked");
                                    }
                                });
                            }else{
                                layer.alert(res.msg, {icon: 2})
                            }
                        },
                        error:function(res){
                            layer.alert(res.msg, {icon: 2});
                        }
                    });
                },function(){
                    layer.closeAll();
                });
            }

        });

    });
</script>


