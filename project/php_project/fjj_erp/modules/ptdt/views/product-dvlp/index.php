<?php
/**
 * User: F3858995
 * Date: 2016/9/12
 * Time: 下午 03:39
 */
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \app\classes\Menu;

$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '商品开发计划'];
$this->params['breadcrumbs'][] = ['label' => '商品开发需求列表'];
$this->title = '商品开发需求列表';/*BUG修正 增加title*/
?>
<div class="content">

    <?php echo $this->render('_search', [
        'requirementType' => $requirementType,
        'developCenter'   => $developCenter,
        'developDep'      => $developDep,
        'pdqStatus'   => $pdqStatus
    ]); ?>

    <div class="space-30"></div>
    <div class="table-content">
        <div class="table-head">
            <p class="head">需求单信息</p>
            <p class="float-right">
                <?= Menu::isAction('/ptdt/product-dvlp/add')?'<a href='.Url::to(['/ptdt/product-dvlp/add']).'><span>新增</span></a>':''?>
                <?= Menu::isAction('/ptdt/product-dvlp/edit')?'<a id="edit" class="display-none"><span class="text-center ml--5">修改</span></a>':''?>
                <?= Menu::isAction('/ptdt/product-dvlp/view')?'<a id="view" class="display-none"><span class="text-center ml--5">详情</span></a>':''?>
                <?= Menu::isAction('/ptdt/product-dvlp/check')?'<a id="check" class="display-none"><span class="text-center ml--5">送审</span></a>':''?>
                <?= Menu::isAction('/ptdt/product-dvlp/delete')?'<a id="delete" class="display-none"><span class="text-center ml--5">删除</a></a>':''?>
                <a href="<?= Url::to(['/index/index']) ?>" class="ml--5"><span>返回</span></a>
            </p>
        </div>
        <div class="space-10"></div>
        <div id="data"></div>
        <div id="load-content_title"> </div>
        <div id="load-content" class="overflow-auto"></div>
    </div>
</div>
<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers :true,
            method: "get",
            idField: "pdq_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                {
                field: "pdq_code", title: "需求编号", width: 150, formatter: function (value, row) {
                var url = "<?= Url::to(['view']) ?>?id=" + row.pdq_id;
                return "<a href='" + url + "'>" + row.pdq_code + "</a>";
        }},
                <?= $columns ?>
            ]],
            onSelect: function (rowIndex, rowData) {    //选择触发事件
                var status = rowData['pdq_status'];
                $("#view").show();
                if(status == 20 || status == 30){
                    $("#check,#delete,#edit").hide();

                }else{
                    $("#check,#delete,#edit").show();
                }
                $("#load-content_title").addClass("table-head mb-5 mt-20").html("<p class='head'>商品信息</p>");
                $("#load-content").datagrid({
                    url: "<?=Url::to(['/ptdt/product-dvlp/load-product']) ?>?id=" + rowData['pdq_id'],
                    rownumbers: true,
                    method: "get",
                    idField: "product_id",
                    loadMsg: false,
                    pagination: true,
                    singleSelect: false,
                    checkOnSelect:false,
                    pageSize: 5,
                    pageList: [5, 10, 15],
                    columns: [[
                        {field: "product_name", title: "商品名称"},
                        {field: "product_size", title: "商品规格型号", width: 80},
                        {field: "levelName", title: "商品定位", width: 50},
//                        {field: "product_level_id", title: "品牌", width: 60},
//                        {field: "material", title: "材质", width: 150},
//                        {field: "quantity", title: "需求数量/月", width: 150},
                        {field: "typeName0", title: "一阶", width: 150,formatter:function (value,row) {
                        if(row['typeName'] !=undefined){
                            return row['typeName'][0]
                        }

                        }},
                        {field: "typeName1", title: "二阶", width: 150,formatter:function (value,row) {
                            if(row['typeName'] !=undefined){
                                return row['typeName'][1]
                            }

                        }},
                        {field: "typeName2", title: "三阶", width: 150,formatter:function (value,row){
                            if(row['typeName'] !=undefined){
                                return row['typeName'][2]
                            }

                        }},
                        {field: "typeName3", title: "四阶", width: 150,formatter:function (value,row){
                            if(row['typeName'] !=undefined){
                                return row['typeName'][3]
                            }

                        }},
                        {field: "typeName4", title: "五阶", width: 150,formatter:function (value,row){
                            if(row['typeName'] !=undefined){
                                return row['typeName'][4]
                            }

                        }},
                        {field: "typeName5", title: "六阶", width: 150,formatter:function (value,row){
                            if(row['typeName'] !=undefined){
                                return row['typeName'][5]
                            }

                        }},
                    ]],
//            onSelect: function (rowIndex, rowData) {    //选择触发事件
//                if (rowData.products.length == 0) {
//                    return false;
//                }
//                var id = rowData['pdq_id'];
//                setMenuHeight();
//            },
                    onLoadSuccess: function (data) {
                        showEmpty($(this),data.total,0);
                    }
                });
            },
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
                setMenuHeight();
            }
        });

        $("#view").on("click",function(){
            var data = $("#data").datagrid("getSelected");
            var url = "<?=Url::to(['view'])?>";
            if (data == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                if(data.pdq_status == '20' || data.pdq_status == '30'){
                    window.location.href = url+"?id=" + data['pdq_id'];
                }else{
                    window.location.href = url+"?id=" + data['pdq_id'];
                }
            }
        });

        $("#edit").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            var url = "<?=Url::to(['edit'])?>";
            if (data == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else if(data['pdq_status']==20){
                layer.alert("需求审核中,无法修改", {icon: 2, time: 5000});
            } else if(data['pdq_status']==30){
                layer.alert("需求审核完成,无法修改", {icon: 2, time: 5000});
            }else{
                window.location.href = url+"?id=" + data['pdq_id'];
            }
        });

        /*送审*/
        $("#check").on("click",function(){
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
                return false;
            }
            if(data['pdq_status'] == 20){
                layer.alert("需求正在审核中", {icon: 2, time: 5000});
                return false;
            }
            if(data['pdq_status'] == 30){
                layer.alert("需求已审核通过", {icon: 2, time: 5000});
                return false;
            }
            var id=data['pdq_id'];
            var url="<?=Url::to(['view'],true)?>?id="+id;
            var type=11;
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        });
        //审核记录
        $("#CheckView").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            var url = "<?=Url::to(['check-view'])?>";
            if (data == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else if(data['pdq_status']==10){
                layer.alert("需求未送审,无法查看审核记录", {icon: 2, time: 5000});
            }else{
                window.location.href = url+"?id=" + data['pdq_id'];
            }
        });

        $("#delete").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条需求单信息",{icon:2,time:5000});
            } else {
                var selectId = $("#data").datagrid("getSelected")['pdq_id'];
                var status = $("#data").datagrid("getSelected")['pdq_status'];
                if(status == '20'){
                    layer.alert("审核中,无法删除",{icon:2,time:5000});
                }else{
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {"id": selectId},
                        url: "<?=Url::to(['/ptdt/product-dvlp/delete-count']) ?>",
                        success: function (msg) {
                            if(msg === false){
                                layer.alert('无法删除',{icon:2})
                            }else{
                                layer.confirm("确定要删除这条记录吗",
                                    {
                                        btn:['确定', '取消'],
                                        icon:2
                                    },
                                    function () {
                                        $.ajax({
                                            type: "get",
                                            dataType: "json",
                                            data: {"id": selectId},
                                            url: "<?=Url::to(['/ptdt/product-dvlp/delete']) ?>",
                                            success: function (msg) {
                                                if( msg.flag === 1){
                                                    layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
                                                }else{
                                                    layer.alert(msg.msg,{icon:2})
                                                }
                                            },
                                            error :function(msg){
                                                layer.alert(msg.msg,{icon:2})
                                            }
                                        })
                                    },
                                    function () {
                                        layer.closeAll();
                                    }
                                )
                            }
                        },
                    })

                }
            }
        });
    });
//    $(".close").click(function(){
//    });
</script>

