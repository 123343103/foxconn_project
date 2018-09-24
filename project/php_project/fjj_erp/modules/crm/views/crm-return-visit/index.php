<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/23
 * Time: 17:30
 */
use yii\helpers\Url;
$this->title="会员回访记录列表";
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][] = ['label' => '会员回访记录', 'url' => Url::to(['/crm/crm-return-visit/index'])];
?>
<div class="content">
    <?php  echo $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $queryParam
    ]); ?>

    <div class="space-20"></div>
    <?php echo $this->render('_action'); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data">
        </div>
        <div class="space-30"></div>
        <div id="load-content"></div>

    </div>

</div>
<script>
    var id;
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "sih_id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck:false,
            checkOnSelect:false,
            columns: [[
//                {field:"",checkbox:true},
                <?= $columns ?>
//                {
//                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
//                    return '&nbsp;&nbsp;<a onclick="cancle(' + row.sih_id + ')"><i class="icon-minus-sign fs-18"></i></a>';
//                }
//                }
            ]],
            onSelect: function(rowIndex,rowData){
                $("#delete,#update").removeClass('display-none');
                var id = rowData['cust_id'];
                $('#load-content').load("<?=Url::to(['load-content']) ?>?id=" + id, function () {
                    setMenuHeight();
                });
                $('#record').datagrid("loading");
                $('#reminder').datagrid("loading");
            },

            onLoadSuccess: function (data) {
                $(this).datagrid('clearChecked');
                $(this).datagrid('clearSelections');
                $("#delete,#update").addClass('display-none');
                datagridTip('#data');
                showEmpty($(this),data.total,0,1);
                setMenuHeight();
            }
        });
        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录',
        });
//        $("#create").on("click", function () {
//            var a = $("#data").datagrid('getSelected');
//            if (a == null) {
//                window.location.href = "<?//=Url::to(['create'])?>//";
//            }else{
//                window.location.href = "<?//=Url::to(['create'])?>//?id=" + a.cust_id;
//            }
//        });
//        $("#update").on("click", function () {
//            var a = $("#data").datagrid('getSelected');
//            if (a == null) {
//                layer.alert("请点击选择一条会员信息!", {icon: 2, time: 5000});
//            }else{
//                var b = $("#record").datagrid('getSelected');
//                if(b == null){
//                    layer.alert("请点击选择一条子表信息!", {icon: 2, time: 5000});
//                }else{
//                    var id = $("#data").datagrid("getSelected")['sih_id'];
//                    var childId = $("#record").datagrid("getSelected")['sil_id'];
//                    window.location.href = "<?//=Url::to(['update'])?>//?id=" + id + "&childId=" + childId;
//                }
//            }
//        });
//        //删除拜访履历
//        $("#delete").on("click", function () {
//            var mainObj = $("#data").datagrid('getSelected');
//            var a = $('#data').datagrid('getChecked');
//            if (mainObj == null && a.length == 0) {
//                layer.alert("请先选择一条客户信息！", {icon:2,time:5000});
//                return false;
//            } else if(mainObj != null && a.length == 1){
//                var childObj = $("#record").datagrid('getSelected');
//                var reminderObj = $("#reminder").datagrid('getSelected');
//                if(childObj != null){
//                    layer.confirm("确定删除这条拜访记录吗?",
//                        {
//                            btn:['确定', '取消'],
//                            icon:2
//                        },
//                        function () {
//                            $.ajax({
//                                type: "get",
//                                dataType: "json",
//                                data: {"id": mainObj.sih_id,"childId": childObj.sil_id},
//                                url: "<?//= Url::to(['delete']) ?>//",
//                                success: function (msg) {
//                                    if( msg.flag === 1){
//                                        layer.alert(msg.msg,{icon:1},function(){
//                                            location.reload();
//                                        });
//                                        setTimeout(function(){ location.reload()},5000)
//                                    }else{
//                                        layer.alert(msg.msg,{icon:2})
//                                    }
//                                }
//                            })
//                        },
//                        function () {
//                            layer.closeAll();
//                        }
//                    )
//                } else if(reminderObj != null){
//                    layer.confirm("确定删除这条提醒事项吗?",
//                        {
//                            btn:['确定', '取消'],
//                            icon:2
//                        },
//                        function () {
//                            $.ajax({
//                                type: "get",
//                                dataType: "json",
//                                data: {"id": mainObj.sih_id,"imesgId": reminderObj.imesg_id},
//                                url: "<?//= Url::to(['delete']) ?>//",
//                                success: function (msg) {
//                                    if( msg.flag === 1){
//                                        layer.alert(msg.msg,{icon:1},function(){
//                                            location.reload();
//                                        });
//                                        setTimeout(function(){ location.reload()},5000)
//                                    }else{
//                                        layer.alert(msg.msg,{icon:2})
//                                    }
//                                }
//                            })
//                        },
//                        function () {
//                            layer.closeAll();
//                        }
//                    )
//                }
//            }else if(a.length != 0){
//                var arr = [];
//                var id;
//                var url = "<?//=Url::to(['delete-customer'])?>//";
//                for(var i =0;i<a.length;i++){
//                    arr.push(a[i].sih_id);
//                }
//                id = arr.join(',');
//                data_delete(id,url);
//            }
//        });
        /*详情*/
        $("#view").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请点击选择一条数据!",{icon:2,time:5000});
            }else{
                var c = $("#record").datagrid("getSelected");
                if(c == null){
                    window.location.href = "<?=Url::to(['view'])?>?id=" + a.sih_id;
                }else{
                    window.location.href = "<?=Url::to(['view'])?>?id=" + a.sih_id + "&childId="+ c.sil_id;
                }
            }
        });
    });
</script>