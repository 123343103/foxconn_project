<?php
/**
 * User: F1677929
 * Date: 2016/11/11
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title = '厂商评鉴申请列表';
$this->params['homeLike'] = ['label'=>'供应商管理','url' => Url::to(['/index/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <?= $this->render('_search', ['indexData' => $indexData]) ?>
    <div class="table-head mb-10">
        <p class="head">厂商评鉴申请列表</p>
        <p class="float-right">
            <a id="add"><span>新增</span></a><a id="edit"><span>修改</span></a><a id="view"><span>查看</span></a><a id="delete"><span>刪除</span></a><a id="check"><span>送审</span></a><a id="firm-evaluate"><span class="width-90">厂商评鉴</span></a><a id="close"><span>关闭</span></a>
        </p>
    </div>
    <div id="data"></div>
</div>
<script>
    $(function () {
        //严格模式
        "use strict";

        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "apply_id",
            pagination: true,
            singleSelect: true,
            pageSize: 10,
            pageList: [10,20,30],
            columns: [[
                {field:"firm_code",title:"厂商编号",width:150,formatter:function(value,row){
                    return row.firmInfo.firm_code;
                }},
                {field:"firm_sname",title:"厂商名称",width:201,formatter:function(value,row){
                    return row.firmInfo.firm_sname;
                }},
                {field:"firm_compprincipal",title:"负责人",width:100,formatter:function(value,row){
                    return row.firmInfo.firm_compprincipal;
                }},
                {field:"firmPosition",title:"厂商定位",width:100,formatter:function(value,row){
                    return row.firmInfo.bsPubdata.firmPosition;
                }},
                {field:"create_at",title:"新增时间",width:150},
                {field:"apply_type",title:"评鉴类型",width:100},
                {field:"apply_status",title:"状态",width:100}
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });

        //新增拜访履历
        $("#add").on("click", function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                window.location.href = "<?= Url::to(['add']) ?>";
            } else {
                window.location.href = "<?= Url::to(['add']) ?>?firmId=" + obj.firm_id;
            }
        });

        //修改拜访履历
        $("#edit").on("click", function () {
            var mainObj = $("#data").datagrid('getSelected');
            if (mainObj == null) {
                layer.alert("请先选择一条厂商信息", {icon:2,time:5000});
            } else {
                var childObj = $("#load-resume").datagrid('getSelected');
                if (childObj == null) {
                    layer.alert("请点击选择一条拜访履历信息", {icon:2,time:5000});
                } else {
                    window.location.href = "<?= Url::to(['edit']) ?>?id=" + childObj.vil_id;
                }
            }
        });

        //查看拜访履历
        $("#view").on("click", function () {
            var mainObj = $("#data").datagrid('getSelected');
            if (mainObj == null) {
                layer.alert("请先选择一条厂商信息", {icon:2,time:5000});
            } else {
                var childObj = $("#load-resume").datagrid('getSelected');
                if (childObj == null) {
                    layer.alert("请点击选择一条拜访履历信息", {icon:2,time:5000});
                } else {
                    window.location.href = "<?= Url::to(['view']) ?>?id=" + childObj.vil_id;
                }
            }
        });

        //删除拜访履历
        $("#delete").on("click", function () {
            var mainObj = $("#data").datagrid('getSelected');
            if (mainObj == null) {
                layer.alert("请先选择一条厂商信息", {icon:2,time:5000});
            } else {
                var childObj = $("#load-resume").datagrid('getSelected');
                if (childObj == null) {
                    layer.alert("请点击选择一条拜访履历信息", {icon:2,time:5000});
                } else {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {"id": childObj.vil_id},
                        url: "<?= Url::to(['visit-resume/delete-judge']) ?>",
                        success: function (data) {
                            if(data == 1) {
                                layer.confirm("确定删除这条履历吗?",
                                    {
                                        btn:['确定', '取消'],
                                        icon:2
                                    },
                                    function () {
                                        $.ajax({
                                            type: "get",
                                            dataType: "json",
                                            data: {"id": childObj.vil_id},
                                            url: "<?= Url::to(['visit-resume/delete-child']) ?>",
                                            success: function (data) {
                                                if(data == true) {
                                                    layer.alert("该条履历删除成功",{icon:1},function(){
                                                        location.reload();
                                                    });
                                                    setTimeout(function(){location.reload()},5000);
                                                } else {
                                                    layer.alert("该条履历删除失败",{icon:2});
                                                }
                                            }
                                        })
                                    },
                                    function () {
                                        layer.closeAll();
                                    }
                                )
                            } else {
                                if (data == 2) {
                                    layer.confirm("确定删除这条履历吗?",
                                        {
                                            btn:['确定', '取消'],
                                            icon:2
                                        },
                                        function () {
                                            $.ajax({
                                                type: "get",
                                                dataType: "json",
                                                data: {"id": childObj.vil_id},
                                                url: "<?= Url::to(['visit-resume/delete-main-child']) ?>",
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
                                } else {
                                    layer.alert("该条拜访履历不是最新的，不可删除",{icon:2});
                                }
                            }
                        }
                    })
                }
            }
        });

        //拜访完成
        $("#visit_complete").on("click", function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                layer.alert("请先选择一条厂商信息", {icon:2,time:5000});
            } else {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {"id": obj.vih_id},
                    url: "<?= Url::to(['visit-resume/visit-status-judge']) ?>",
                    success: function (data) {
                        if (data == true) {
                            layer.confirm("确定对这个厂商进行拜访完成操作吗?",
                                {
                                    btn:['确定', '取消'],
                                    icon:2
                                },
                                function () {
                                    $.ajax({
                                        type: "get",
                                        dataType: "json",
                                        data: {"id": obj.vih_id},
                                        url: "<?= Url::to(['visit-resume/visit-complete']) ?>",
                                        success: function (data) {
                                            if(data == true) {
                                                layer.alert("该厂商拜访完成",{icon:1},function(){
                                                    location.reload();
                                                });
                                                setTimeout(function(){location.reload()},5000);
                                            } else {
                                                layer.alert("该厂商拜访完成操作失败",{icon:2});
                                            }
                                        }
                                    })
                                },
                                function () {
                                    layer.closeAll();
                                }
                            )
                        } else {
                            layer.alert("该条厂商不可进行拜访完成操作", {icon:2,time:5000});
                        }
                    }
                })
            }
        });

        //新增谈判
        $("#add_negotiation").on("click",function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                window.location.href = "<?= Url::to(['/ptdt/firm-negotiation/create']) ?>";
            } else {
                window.location.href = "<?= Url::to(['/ptdt/firm-negotiation/create']) ?>?firmId=" + obj.firm_id;
            }
        });

        //关闭
        $("#close").on("click", function () {
            window.location.href = "<?= Url::to(['/index/index']) ?>";
        })
    })
</script>