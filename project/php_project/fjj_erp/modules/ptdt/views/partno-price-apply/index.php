<?php
/**
 * User: F1676624
 * Date: 2016/10/24
 */

use yii\helpers\Url;
use app\classes\Menu;

$this->params['homeLike'] = ['label' => '商品开发与管理', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label' => '商品定价','url'=>['index']];
$this->params['breadcrumbs'][] = ['label' => '商品定价申请表'];
?>
<div class="content">
    <?php echo $this->render('_search',[
        'get'=>\Yii::$app->request->get(),
        'statusType'=>$statusType,
        'downlist'=>$downlist
    ]); ?>
    <div class="table-content">
        <div class="table-head">
            <p class="float-right">
                <?= Menu::isAction('/ptdt/partno-price-apply/edit')?'<a id="price"><span>去定价</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-apply/create')?'<a id="add"><span>新增申请</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-confirm/edit')?'<a id="check"><span>送审</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-apply/view')?'<a id="view"><span>查看</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-apply/edit')?'<a id="edit"><span>修改</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-apply/index')?'<a id="export"><span>导出</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-apply/delete')?'<a id="delete"><span>删除</span></a>':'' ?>
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
                {field: 'ck', checkbox: true, align: 'left'},
                <?=$columns;?>
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this),data.total,1);
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

        //定价申请详情
        $("#view").on("click", function () {
            var row = $("#data").datagrid("getSelected");

            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row['id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });
        //新增定价申请
        $("#add").on("click", function () {
            window.location.href = "<?=Url::to(['create'])?>";
        });

        //送审
        $("#check").on("click", function () {
            var row = $("#data").datagrid("getSelected");

            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row['id'];
                window.location.href = "<?=Url::to(['partno-price-confirm/edit'])?>?id=" + id;
            }
        });

        //修改定价申请
        $("#edit").on("click", function () {
            var row = $("#data").datagrid("getSelected");

            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row['id'];
                window.location.href = "<?=Url::to(['edit'])?>?id=" + id;
            }
        });
        //去定价
        $("#price").on("click", function () {
            var row = $("#data").datagrid("getSelected");
            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row.id;
                window.location.href = "<?=Url::to(['partno-price-confirm/edit'])?>?id=" + id;
            }
        });


        //数据导出
        $('#export').click(function() {
            var page = $("#data" ).datagrid("getPager" ).data("pagination" ).options.pageNumber;
            var rows = $("#data" ).datagrid("getPager" ).data("pagination" ).options.pageSize;
            var index = layer.confirm("确定导出定价申请信息?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },
                function () {
                    if(window.location.href="<?= Url::to(['index', 'export' => '1'])?>&page="+page+"&rows="+rows){
                        layer.closeAll();
                    }else{
                        layer.alert('导出定价申请信息发生错误',{icon:0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        });


        //删除定价申请
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
                    btn: ['確定', '取消'],
                    icon: 2
                },function(){
                    $.ajax({
                        type:"get",
                        dataType:"json",
                        data:{id:ids.join()},
                        url: "<?=Url::to(['/ptdt/partno-price-apply/delete']) ?>",
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


