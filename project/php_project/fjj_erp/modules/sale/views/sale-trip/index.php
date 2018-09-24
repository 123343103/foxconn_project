<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2016/2/13
 * Time: 14:49
 */
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '出差申请及消单报告列表', 'url' => Url::to(['/crm/crm-customer-info/index'])];
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
                idField: "capply_id",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                columns: [[
                    {field:"cust_code",title:"申请编码",width:150,formatter:function (value,row,index) {
                        if (row.customer){
                            return row.customer.cust_code
                        }else {
                            return null;
                        }
                    }},
                    {field:"cust_filernumber",title:"数据类型",width:150,formatter:function(value,row,index){
                        if(row.customer){
                            return row.customer.cust_filernumber;
                        }else{
                            return null;
                        }
                    }},
//                    {field:"cust_manager",title:"客户经理",width:150,formatter:function(value,row,index){
//                        if(row.manager){
//                            return row.manager.managerName;
//                        }else{
//                            return null;
//                        }
//                    }},
                    {field:"cust_sname",title:"出差申请人",width:100,formatter:function(value,row,index){
                        if(row.customer){
                            return row.customer.cust_sname;
                        }else{
                            return null;
                        }
                    }},
                    {field:"cust_shortname",title:"职位",width:80,formatter:function(value,row,index){
                        if(row.customer){
                            return row.customer.cust_shortname;
                        }else{
                            return null;
                        }
                    }},
//                    {field:"cust_level",title:"客户等级",width:150,formatter:function(value,row,index){
//                        if(row.bsPubdata){
//                            return row.bsPubdata.custLevel;
//                        }else{
//                            return null;
//                        }
//                    }},
                    {field:"cust_level",title:"部门",width:150,formatter:function(value,row,index){
                        if(row.custLevel){
                            return row.custLevel;
                        }else{
                            return null;
                        }
                    }},
                    {field:"cust_type",title:"所诉军区",width:150,formatter:function(value,row,index){
                        if(row.custType){
                            return row.custType;
                        }else{
                            return null;
                        }
                    }},
                    {field:"custArea",title:"费用代码",width:80},
                    {field:"custSalearea",title:"状态",width:80},
                    /*{field:"custManager",title:"客户经理人",width:150},
                    {field:"contactPerson",title:"联系人",width:150,formatter:function (value,row) {
                        if(row.contactPerson){
                            return row.contactPerson.ccper_name;
                        }else{
                            return null;
                        }
                    }},
                    {field:"ccper_tel",title:"联系电话",width:150,formatter:function (value,row) {
                        if(row.contactPerson){
                            return row.contactPerson.ccper_tel;
                        }else{
                            return null;
                        }
                    }},
                    {field:"applyPerson",title:"申请人",width:150,formatter:function(value,row,index){
                        if(row.applyPerson){
                            return row.applyPerson.name;
                        }else{
                            return null;
                        }
                    }},
                    {field:"status",title:"审核状态",width:150},*/
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