<?php
use yii\helpers\Url;
$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label'=>'厂商计划列表','url'=>Url::to(['/ptdt/visit-plan/index'])];
$this->title = '厂商拜访计划列表';
?>
<div class="content">
    <?php  echo $this->render('_search', ['downList' => $downList,'queryParam'=>$queryParam]); ?>
    <div class="table-content">
        <?php  echo $this->render('_action'); ?>
        <div class="space-10"></div>
        <div id="data">
        </div>
    </div>
</div>
<script>
    $(function(){
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers :true,
            method: "get",
            idField: "pvp_planID",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                <?= $columns ?>
            ]],
            onSelect:function(rowIndex,rowData){
                if(rowData.pvp_planID == null){
                    $("#data").datagrid('unselectRow',0);
                    return false;
                }
            },
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
                datagridTip('#data');
            }
        });
        $("#add").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                window.location.href ="<?= Url::to(['add']) ?>"
            }else{
                var id = $("#data").datagrid("getSelected")['firm_id'];
                window.location.href = "<?=Url::to(['add'])?>?id=" + id;
            }
        });
        $("#edit").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请选择一条拜访计划!",{icon:2,time:5000});
            }else{
                var status = $("#data").datagrid("getSelected")['plan_status'];
                if(status !== 10){
                    layer.alert("无法修改!",{icon:2,time:5000});
                }else{
                var id = $("#data").datagrid("getSelected")['pvp_planID'];
                window.location.href = "<?=Url::to(['edit'])?>?id=" + id;
                }

            }
        });
        $("#view").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请选择一条拜访计划!",{icon:2,time:5000});
            } else {
                var id = $("#data").datagrid("getSelected")['pvp_planID'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });
        $("#delete").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请选择一条拜访计划!",{icon:2,time:5000});
            } else {
                var id = $("#data").datagrid("getSelected")['pvp_planID'];
                var status = $("#data").datagrid("getSelected")['plan_status'];
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {"id": id},
                    url: "<?=Url::to(['/ptdt/visit-plan/delete-count']) ?>",
                    success: function (msg) {
                        if( msg === 'false' || status !== 10){
                            layer.alert('无法删除',{icon:2})
                        }else{
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
                                        url: "<?=Url::to(['/ptdt/visit-plan/delete']) ?>",
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
        });
        
        //新增拜访履历
        $("#add_resume").click(function () {
            var obj = $("#data").datagrid("getSelected");
            if(obj == null){
                layer.alert("请选择一条拜访计划!",{icon:2,time:5000});
            } else if(obj.purpose == '拜访' && obj.status == '新增'){
                window.location.href = "<?=Url::to(['/ptdt/visit-resume/add'])?>?firmId=" + obj.firm_id + "&planId=" + obj.pvp_planID;
            }else if(obj.purpose == '代理谈判' && obj.status == '新增'){
                window.location.href = "<?=Url::to(['/ptdt/firm-negotiation/create'])?>?firmId=" + obj.firm_id + "&planId=" + obj.pvp_planID;
            }else{
                layer.alert("无法新增!",{icon:2,time:5000});
            }
        })
    })
</script>

