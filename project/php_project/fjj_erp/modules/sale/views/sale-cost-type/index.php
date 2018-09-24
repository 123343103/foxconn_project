<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/2/17
 * Time: 上午 10:01
 */
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '基础数据设置', 'url' => Url::to([''])];
$this->params['breadcrumbs'][] = ['label' => '业务费用类别列表', 'url' => Url::to([''])];
?>
<div class="content">
    <?php echo $this->render('_search',[
    ]); ?>
    <div class="space-20"></div>
    <?php echo $this->render('_action'); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data">
        </div>
    </div>
    <script>
        var id;
        $(function () {
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                rownumbers: true,
                method: "get",
                idField: "scost_id",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                columns: [[
                    {field:"scost_code",title:"类别编码",width:150},
                    {field:"scost_description",title:"类型描述",width:150},
                    {field:"scost_status",title:"状态",width:100,formatter:function(value,row,index){
                        if(row.scost_status==1){
                            return '有效';
                        }else{
                            return '无效';
                        }
                    }},
                    {field:"scost_remark",title:"备注",width:80,},
                    {field:"create_by",title:"档案创建人",width:150,formatter:function(value,row,index){
                        if(row.createBy){
                            return row.createBy.name;
                        }else{
                            return null;
                        }
                    }},
                    {field:"create_at",title:"建档日期",width:150},
                    {field:"update_by",title:"最后修改人",width:80,formatter:function (value,row,index) {
                        if (row.updateBy){
                            return row.updateBy.name;
                        }else {
                            return null;
                        }
                    }},
                    {field:"update_at",title:"修改日期",width:80},
                ]],
            });
            $("#add").on("click",function(){
                window.location.href = "<?=Url::to(['create'])?>";
            });
            $("#edit").on("click",function(){
                var a = $("#data").datagrid("getSelected");
                if(a == null){
                    layer.alert("请点击选择一条客户信息!",{icon:2,time:5000});
                }else{
                    var id = $("#data").datagrid("getSelected")['cust_id'];
                    window.location.href = "<?=Url::to(['update'])?>?id=" + id;
                }
            });
            $("#view").on("click",function(){
                var a = $("#data").datagrid("getSelected");
                if(a == null){
                    layer.alert("请点击选择一条客户信息!",{icon:2,time:5000});
                }else{
                    var id = $("#data").datagrid("getSelected")['cust_id'];
                    window.location.href = "<?=Url::to(['view'])?>?id=" + id;
                }
            });
            $("#delete").on("click",function(){
                var a = $("#data").datagrid("getSelected");
                if(a == null){
                    layer.alert("请选择一条拜访计划!",{icon:2,time:5000});
                } else {
                    var id = $("#data").datagrid("getSelected")['capply_id'];
                    var index = layer.confirm("確定要刪除這條記錄嗎?",
                        {
                            btn:['確定', '取消'],
                            icon:2
                        },
                        function () {
                            $.ajax({
                                type: "get",
                                dataType: "json",
                                async: false,
                                data: {"id": id},
                                url: "<?=Url::to(['delete']) ?>",
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
            });
            $('#export').click(function() {
                var index = layer.confirm("确定导出客户信息?",
                    {
                        btn:['確定', '取消'],
                        icon:2
                    },
                    function () {
                        if(window.location.href="<?= Url::to(['index', 'export' => '1'])?>"){
                            layer.closeAll();
                        }else{
                            layer.alert('导出客户信息发生错误',{icon:0})
                        }
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            });
        })
    </script>