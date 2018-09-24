<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<div class="head-first">选择客户</div>
<div class="mb-10" style="margin-left:15px;margin-right:15px;">
    <div class="mb-10">
        <input id="keyword" type="text" style="width:200px;" placeholder="请输入查询信息">
        <button id="query_btn" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
        <button id="reset_btn" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
    </div>
    <div id="customer_data" style="width:100%;"></div>
</div>
<div class="text-center" style="margin-bottom:20px;">
    <button type="button" class="button-blue" id="confirm_customer">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<script>
    //加载数据
    function loadData(){
        $("#customer_data").datagrid('load',{
            "keyword":$("#keyword").val()
        });
    }

    $(function () {
        $("#customer_data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            singleSelect: true,
            pagination: true,
            pageSize: 10,
            pageList: [10],
            columns: [[
                {field: "cust_filernumber", title: "客户编号", width: 200},
                {field: "cust_sname", title: "客户名称", width: 300},
                {field: "customerManager", title: "客户经理人", width: 212}
            ]],
            onLoadSuccess:function(data){
                datagridTip("#customer_data");
                showEmpty($(this),data.total,0);
            },
            onDblClickRow:function(){
                $("#confirm_customer").click();
            }
        });

        //确定
        $("#confirm_customer").click(function () {
            var obj = $("#customer_data").datagrid('getSelected');
            if (obj == null) {
                parent.layer.alert('请选择客户！', {icon: 2, time: 5000});
                return false;
            }
            if(obj.customerManager==null){
                if("<?=Yii::$app->user->identity->is_supper?>"=="1"){
                    parent.layer.alert("登陆用户非客户经理人，不可认领客户！",{icon:2,time:5000});
                }else{
                    parent.layer.confirm("确认认领该客户吗？",{icon:2},
                        function(){
                            $.ajax({
                                url:"<?=Url::to(['claim-customer'])?>",
                                data:"customerId="+obj.cust_id,
                                dataType:"json",
                                success:function(data){
                                    if(data.flag==1){
                                        parent.layer.alert(data.msg,{icon:1,time:5000},function(){
                                            parent.layer.closeAll();
                                            $("#customer_data").datagrid("reload");
                                        });
                                    }else{
                                        parent.layer.alert(data.msg,{icon:2,time:5000});
                                    }
                                }
                            });
                        },
                        layer.closeAll()
                    );
                }
                return false;
            }
            parent.$("#cust_id").val(obj.cust_id).change();
            parent.$("#customer_info").show();
            parent.$("#cust_sname").html(obj.cust_sname);
            parent.$("#customerType").html(obj.customerType);
            parent.$("#customerManager").html(obj.customerManager);
            parent.$("#csarea_name").html(obj.csarea_name);
            parent.$("#cust_contacts").html(obj.cust_contacts);
            parent.$("#cust_tel2").html(obj.cust_tel2);
            parent.$("#customer_address").html(obj.customerAddress);
            parent.$("#svp_id").val('');
            parent.$("#svp_code").val('');
            parent.$.fancybox.close();
        });

        //查询
        $("#query_btn").click(function(){
            loadData();
        });
        $(document).keydown(function(event){
            if(event.keyCode==13){
                loadData();
            }
        });

        //重置
        $("#reset_btn").click(function(){
            $("input,select").val('');
            loadData();
        });
    })
</script>