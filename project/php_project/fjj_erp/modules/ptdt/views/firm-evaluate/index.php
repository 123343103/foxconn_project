<?php
/**
 * User: F1677929
 * Date: 2016/11/11
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title = '厂商评鉴列表';
$this->params['homeLike'] = ['label'=>'供应商管理','url' => Url::to(['/index/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <?= $this->render('_search', ['indexData' => $indexData]) ?>
    <div class="table-head mb-10" style="position: relative;">
        <p class="head">评鉴厂商主表</p>
        <p class="float-right">
            <a id="add"><span>新增</span></a><a id="edit"><span>修改</span></a><a id="view"><span>查看</span></a><a id="delete"><span>刪除</span></a><span class="width-80" id="evaluate_idea" style="cursor:default;">评鉴意见</span><a id="close"><span>关闭</span></a>
        </p>
        <p id="evaluate_option" style="display: none; position: absolute; left: 849px; bottom: -40px; background-color: white; z-index: 1;" class="width-80">
            <a id="purchase_evaluate"><span class="width-80">采购评鉴</span></a>
            <a id="manage_evaluate"><span class="width-80">工管评鉴</span></a>
        </p>
    </div>
    <div id="evaluate_main"></div>
    <div id="evaluate_child_title"></div>
    <div id="evaluate_child"></div>
</div>
<script>
    $(function () {
        "use strict";
        //评鉴按钮显示隐藏
        $("#evaluate_idea,#evaluate_option").on("mouseover",function () {
            $("#evaluate_option").show();
            $(this).on("mouseout",function () {
                $("#evaluate_option").hide();
            })
        });

        //评鉴主表数据
        $("#evaluate_main").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "evaluate_id",
            pagination: true,
            singleSelect: true,
            pageSize: 10,
            pageList: [10,20,30],
            columns: [[
                {field:"firm_code",title:"供应商编码",width:150,formatter:function(value,row){
                    return row.firmInfo.firm_code;
                }},
                {field:"firm_sname",title:"供应商名称",width:200,formatter:function(value,row){
                    return row.firmInfo.firm_sname;
                }},
                {field:"firmType",title:"供应商分类",width:100,formatter:function(value,row){
                    return row.firmInfo.bsPubdata.firmType;
                }},
                {field:"firmSource",title:"厂商来源",width:100,formatter:function(value,row){
                    return row.firmInfo.bsPubdata.firmSource;
                }},
                {field:"issupplier",title:"集团供应商",width:100,formatter:function(value,row){
                    return row.firmInfo.issupplier;
                }},
                {field:"category",title:"商品类别",width:200,formatter:function(value,row){
                    return row.firmInfo.category;
                }},
                {field:"staff_name",title:"新增人",width:100,formatter:function(value,row){
                    return row.createPerson.staff_name;
                }},
                {field:"create_at",title:"新增日期",width:150},
                {field:"evaluate_status",title:"评鉴状态",width:100}
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            },
            onSelect: function (rowIndex, rowData) {
                $("#evaluate_child_title").addClass("table-head mb-10 mt-20").html("<p class='head'>评鉴厂商明细信息</p>");
                $("#evaluate_child").datagrid({
                    url: "<?= Yii::$app->request->getHostInfo() . Url::to(['load-evaluate']) ?>?id=" + rowData.evaluate_id,
                    rownumbers: true,
                    method: "get",
                    idField: "evaluate_child_id",
                    pagination: true,
                    singleSelect: true,
                    pageSize: 10,
                    pageList: [10,20,30],
                    columns: [[
                        {field:"evaluate_date",title:"评鉴日期",width:200},
                        {field:"evaluateDepartment",title:"评鉴部门",width:200},
                        {field:"evaluatePerson",title:"评鉴人",width:200},
                        {field:"evaluateResult",title:"评鉴意见",width:200},
                        {field:"waitEvaluateDepartment",title:"待评鉴部门",width:200}
                    ]],
                    onLoadSuccess: function () {
                        setMenuHeight();
                    }
                });
            }
        });

        //新增厂商评鉴
        $("#add").on("click", function () {
            var obj = $("#evaluate_main").datagrid('getSelected');
            if (obj == null) {
                window.location.href = "<?= Url::to(['add']) ?>";
            } else {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {"id": obj.evaluate_id},
                    url: "<?= Url::to(['firm-evaluate/evaluate-no-pass-firm']) ?>",
                    success: function (data) {
                        if (data == true) {
                            window.location.href = "<?= Url::to(['add']) ?>?firmId=" + obj.firm_id;
                        } else {
                            window.location.href = "<?= Url::to(['add']) ?>";
                        }
                    }
                })
            }
        });

        //修改厂商评鉴
        $("#edit").on("click", function () {
            var mainObj = $("#evaluate_main").datagrid('getSelected');
            if (mainObj == null) {
                layer.alert("请点击选择一条厂商信息！",{icon:2,time:5000});
            } else {
                var childObj = $("#evaluate_child").datagrid('getSelected');
                if (childObj == null) {
                    layer.alert("请点击选择一条厂商评鉴信息！",{icon:2,time:5000});
                } else {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {"id": childObj.evaluate_child_id},
                        url: "<?= Url::to(['firm-evaluate/edit-judge']) ?>",
                        success: function (data) {
                            if (data == 20) {
                                window.location.href = "<?= Url::to(['purchase-evaluate']) ?>?id=" + childObj.evaluate_child_id;
                            } else {
                                if (data == 30) {
                                    window.location.href = "<?= Url::to(['manage-evaluate']) ?>?id=" + childObj.evaluate_child_id;
                                } else {
                                    layer.alert("该条记录不可修改!",{icon:2,time:5000});
                                }
                            }
                        }
                    })
                }
            }
        });

        //查看厂商评鉴
        $("#view").on("click", function () {
            var mainObj = $("#evaluate_main").datagrid('getSelected');
            if (mainObj == null) {
                layer.alert("请点击选择一条厂商信息！",{icon:2,time:5000});
            } else {
                var childObj = $("#evaluate_child").datagrid('getSelected');
                if (childObj == null) {
                    layer.alert("请点击选择一条厂商评鉴信息！",{icon:2,time:5000});
                } else {
                    window.location.href = "<?= Url::to(['view']) ?>?id=" + childObj.evaluate_child_id;
                }
            }
        });

        //删除厂商评鉴
        $("#delete").on("click", function () {
            var mainObj = $("#evaluate_main").datagrid('getSelected');
            if (mainObj == null) {
                layer.alert("请点击选择一条厂商信息！",{icon:2,time:5000});
            } else {
                var childObj = $("#evaluate_child").datagrid('getSelected');
                if (childObj == null) {
                    //删除主表判断
                    $.ajax({
                        url: "<?= Url::to(['firm-evaluate/delete-main-judge']) ?>",
                        data: {"id": mainObj.evaluate_id},
                        success: function (data) {
                            if(data == true){
                                layer.confirm("确定要删除这条记录吗?",
                                    {
                                        btn:['确定', '取消'],
                                        icon:2
                                    },
                                    function () {
                                        $.ajax({
                                            url: "<?= Url::to(['firm-evaluate/delete-main']) ?>",
                                            data: {"id": mainObj.evaluate_id},
                                            dataType: "json",
                                            success: function (msg) {
                                                if( msg.flag === 1){
                                                    layer.alert(msg.msg,{icon:1},function(){
                                                        location.reload();
                                                    });
                                                    setTimeout(function(){ location.reload()},5000)
                                                }else{
                                                    layer.alert(msg.msg,{icon:2})
                                                }
                                            }
                                        })
                                    },
                                    function () {
                                        layer.closeAll();
                                    }
                                )
                            }else{
                                layer.alert("该厂商采购或工管已评鉴不可删除!",{icon:2,time:5000});
                            }
                        }
                    })
                } else {
                    //删除子表判断
                    $.ajax({
                        url: "<?= Url::to(['firm-evaluate/delete-child-judge']) ?>",
                        data: {"id": childObj.evaluate_child_id},
                        dataType: "json",
                        success: function (data) {
                            if(data == true){
                                layer.confirm("确定要删除这条记录吗?",
                                    {
                                        btn:['确定', '取消'],
                                        icon:2
                                    },
                                    function () {
                                        $.ajax({
                                            url: "<?= Url::to(['firm-evaluate/delete-child']) ?>",
                                            data: {"id": childObj.evaluate_child_id},
                                            dataType: "json",
                                            success: function (msg) {
                                                if( msg.flag === 1){
                                                    layer.alert(msg.msg,{icon:1},function(){
                                                        location.reload();
                                                    });
                                                    setTimeout(function(){ location.reload()},5000)
                                                }else{
                                                    layer.alert(msg.msg,{icon:2})
                                                }
                                            }
                                        })
                                    },
                                    function () {
                                        layer.closeAll();
                                    }
                                )
                            }else{
                                layer.alert("该记录采购或工管已评鉴不可删除!",{icon:2,time:5000});
                            }
                        }
                    })
                }
            }
        });

        //采购评鉴
        $("#purchase_evaluate").on("click",function () {
            var mainObj = $("#evaluate_main").datagrid('getSelected');
            if (mainObj == null) {
                layer.alert("请点击选择一条厂商信息！",{icon:2,time:5000});
            } else {
                var childObj = $("#evaluate_child").datagrid('getSelected');
                if (childObj == null) {
                    layer.alert("请点击选择一条厂商评鉴信息！",{icon:2,time:5000});
                } else {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {"id": childObj.evaluate_child_id},
                        url: "<?= Url::to(['purchase-evaluate-judge']) ?>",
                        success: function (data) {
                            if (data == true) {
                                window.location.href = "<?= Url::to(['purchase-evaluate']) ?>?id="+childObj.evaluate_child_id;
                            } else {
                                layer.alert("请点击选择需要采购评鉴的信息！",{icon:2,time:5000});
                            }
                        }
                    });
                }
            }
        });

        //工管评鉴
        $("#manage_evaluate").on("click",function () {
            var mainObj = $("#evaluate_main").datagrid('getSelected');
            if (mainObj == null) {
                layer.alert("请点击选择一条厂商信息！",{icon:2,time:5000});
            } else {
                var childObj = $("#evaluate_child").datagrid('getSelected');
                if (childObj == null) {
                    layer.alert("请点击选择一条厂商评鉴信息！",{icon:2,time:5000});
                } else {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {"id": childObj.evaluate_child_id},
                        url: "<?= Url::to(['manage-evaluate-judge']) ?>",
                        success: function (data) {
                            if (data == true) {
                                window.location.href = "<?= Url::to(['manage-evaluate']) ?>?id="+childObj.evaluate_child_id;
                            } else {
                                layer.alert("请点击选择需要工管评鉴的信息！",{icon:2,time:5000});
                            }
                        }
                    });
                }
            }
        });

        //关闭
        $("#close").on("click", function () {
            window.location.href = "<?= Url::to(['/index/index']) ?>";
        })
    })
</script>