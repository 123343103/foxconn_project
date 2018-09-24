<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['homeLike'] = ['label'=>'人事信息','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'岗位信息'];
$this->title = '人事资料';
?>
<div class="content">
    <?php  echo $this->render('_search', [
        'search' => $search,
    ]); ?>
    <div class="table-content">
        <?php  echo $this->render('_action'); ?>
        <div class="space-10"> </div>
        <div id="data"></div>
    </div>
</div>

<script>
    $(function() {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "title_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                <?= $columns ?>
            ]],
        });
        $("#update").on("click",function(){
            var getSelected = $("#data").datagrid("getSelected");
            if(getSelected == null){
                layer.alert("请点击选择一条岗位信息!",{icon:2,time:5000});
            }else{
                var id = $("#data").datagrid("getSelected")['title_id'];
                window.location.href = "<?=Url::to(['update'])?>?id=" + id;
            }
        });
        $("#viewOne").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请点击选择一条岗位信息!",{icon:2,time:5000});
            }else{
                var id = $("#data").datagrid("getSelected")['title_id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });
        $("#deletion").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请选择一条岗位信息!",{icon:2,time:5000});
            } else {
                var id = $("#data").datagrid("getSelected")['title_id'];
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
                            url: "<?=Url::to(['/hr/staff-title/delete']) ?>",
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
















