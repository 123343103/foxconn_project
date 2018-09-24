<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->params['homeLike'] = ['label'=>'人事信息','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'员工列表'];
$this->title = '人事资料';
?>
<div class="content">
    <?php  echo $this->render('_search', [
        'search' => $search,
        'downList'=>$downList
    ]); ?>

    <div class="table-content">
        <?php  echo $this->render('_action',['search' => $search,'model'=>$model]); ?>
    <div class="space-10"></div>
        <div id="data"></div>
    </div>
</div>

<script>
    $(function() {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "staff_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                {field: "staff_code", title: "工号", width: 120},
                {field: "staff_name", title: "姓名", width: 120},
                {field: "organization_code", title: "部门代码", width: 180},
                {field: "organization_name", title: "部门名称", width: 180},
                {field: "job_level", title: "资位", width: 120},
                {field: "position", title: "管理职", width: 120},
                {field: "employment_date", title: "入厂日期", width: 180},
                {field: "staff_mobile", title: "手机号码", width: 180},
                {field: "staff_status", title: "员工状态", width: 100,formatter:function (value) {
                    if(value==10){
                        return "在职";
                    }
                    if(value==20){
                        return "离职";
                    }
                    return "员工信息已删除";
                }}
            ]],
            onLoadSuccess: function (data) {
                $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color','white');
            },
        });
        $("#update").on("click",function(){
            var getSelected = $("#data").datagrid("getSelected");
            if(getSelected == null){
                layer.alert("请点击选择一条人员信息!",{icon:2,time:5000});
            }else{
                var id = $("#data").datagrid("getSelected")['staff_id'];
                window.location.href = "<?=Url::to(['update'])?>?id=" + id;
            }
        });
        $("#viewOne").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请点击选择一条人员信息!",{icon:2,time:5000});
            }else{
                var id = $("#data").datagrid("getSelected")['staff_id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });
        $("#delete").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请选择一条人员信息!",{icon:2,time:5000});
            } else {
                var id = $("#data").datagrid("getSelected")['staff_id'];
                var index = layer.confirm("确定要删除这条记录吗?",
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            async: false,
                            data: {"id": id},
                            url: "<?=Url::to(['/hr/staff/delete']) ?>",
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
        })
    })

</script>


