<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/16
 * Time: 下午 03:43
 */
use yii\helpers\Url;
use \yii\helpers\Html;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<?php $form=\yii\widgets\ActiveForm::begin([
    "id"=>"allot-form"
]);?>
<style>
    .label-width{
        width: 100px;
    }
    .value-width{
        width: 200px;
    }
</style>
<div id="allotbox">
    <div style="padding:0px 10px;">
        <input type="hidden" id="customers" name="customers" value="">
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align" for="">被分配部门：</label>
            <?=Html::dropDownList("",$orgCode,array_combine(array_column($department,"organization_code"),array_column($department,"organization_name")),["prompt"=>"请选择","encode"=>false,"id"=>"department","class"=>"value-width easyui-validatebox","data-options"=>"required:true",Yii::$app->user->identity->is_supper?"":"disabled"=>"disabled"])?>
        </div>
        <div class="mb-10">
            <label class="label-width label-align" for="">被分配人员：</label>
            <?=Html::dropDownList("CrmCustPersoninch[ccpich_personid]",\Yii::$app->user->identity->staff_id,array_combine(array_column($orgStaff,"staff_id"),array_column($orgStaff,"staff_name")),["prompt"=>"请选择","id"=>"allotman","class"=>"value-width easyui-validatebox","data-options"=>"required:true"])?>
        </div>
        <div id="allot-data" style="width:580px;"></div>
        <div class="space-10"></div>
        <div class="allotbox-bottom">
            <p style="width: 100%;text-align: center;">
                <button type="button" class="button-white undo" style="margin-left: 0">取消分配</button>
                <button class="button-blue ensure">分配</button>
                <button type="button" class="button-white cancel" style="margin-left: 0">取消</button>
            </p>
        </div>
    </div>
</div>
<?php $form->end();?>
<script>
        $(function(){
            var data="";
            $("#allot-form").on("beforeSubmit", function () {
                if (!$(this).form('validate')) {
                    return false;
                }
            });
            //客户分配ajax表单
            $("#allot-form").ajaxForm(function(res){
                parent.$.fancybox.close();
                res=JSON.parse(res);
                if(res.flag==0){
                    parent.layer.alert(res.msg,{icon:2});
                    return ;
                }
                parent.layer.alert(res.msg,{icon:1});
                var index;
                var rows=parent.$("#data").datagrid("getChecked");
                for(var x=0;x<rows.length;x++) {
                    index=parent.$("#data").datagrid("getRowIndex",rows[x]);
                    rows[x].allotman=$("#allotman option:selected").text();
                    rows[x].allotman_staff_id=$("#allotman").val();
                    parent.$("#data").datagrid("refreshRow",index);
                };
            });


            //未分配客户
            $("#allot-data").datagrid({
                method: "get",
                idField: "part_no",
                loadMsg: "加载数据请稍候。。。",
                rownumbers: true,
                singleSelect: false,
                checkOnSelect: true,
                selectOnCheck: true,
                columns:[[
                    {field:"cust_sname",title:"公司名称",width:180},
                    {field:"cust_shortname",title:"公司简称",width:180},
                    {field:"cust_contacts",title:"联系人",width:180}
                ]],
                onLoadSuccess:function(data){
                    $("#allot-data").datagrid("resize");
                    showEmpty($(this),data.total,0);
                }
            });
            var rows=parent.$("#data").datagrid("getChecked");
            for(var x=0;x<rows.length;x++) {
                $("#allot-data").datagrid("insertRow",{
                    index:1,
                    row:rows[x]
                });
            };

            updateCustomers();

            $(".undo").click(function(){
                $.ajax({
                    url:"<?=Url::to(['undo-allot'])?>",
                    type:"get",
                    data:{
                        customers:$("#customers").val()
                    },
                    dataType:"json",
                    success:function(res){
                        parent.$.fancybox.close();
                        parent.layer.alert(res.msg,{icon:1});
                        parent.$("#data").datagrid("reload");
                    }
                });
            });
        //关闭弹窗
            $(".cancel,.exit").click(function(){
                parent.$.fancybox.close();
            });


            //更新输入框的客户数据
            function updateCustomers(){
                var rows=$("#allot-data").datagrid("getRows");
                var tmp=new Array();
                for(var x=0;x<rows.length;x++){
                    tmp.push(rows[x].cust_id);
                }
                $("[name=customers]").val(tmp.join(","));
            }


            //部门选择
            $("#department").on("change",function(){
                $("#allotman option").remove();
                $("#allotman").append("<option value=''>请选择</option>");
                if($(this).val()){
                    $.get("<?=Url::to(['allotman-select'])?>?org_code="+$(this).val(),function(res){
                        var data=JSON.parse(res);
                        $("#allotman option:gt(0)").remove();
                        $("#allotman").attr("name","CrmCustPersoninch[ccpich_personid]");
                        for(var x=0;x<data.length;x++){
                            $("#allotman").append("<option value='"+data[x].staff_id+"'>"+data[x].staff_name+"</option>");
                        }
                    });
                }
            });

            //分配人选择
            $("#allotman").on("change",function(){
                $("#allot-data").datagrid("reload");
                $("#allot-data").datagrid("unselectAll");
                $("#has-alloted-data").datagrid("unselectAll");
                $("#has-alloted-data").datagrid("load",{
                    "staff_id":$(this).val()
                });
            });

        });
</script>
<style type="text/css">
    textarea{
        width:100%;
    }
    button{
        font-size: 12px;
    }
    button:hover {
        cursor: pointer;
        border: 1px solid #0e0e0e;
    }
    .datagrid-view{
        max-height: 180px !important;
    }
    .datagrid-body{
        width: 550px !important;
        max-height: 160px !important;
        overflow-x: hidden;
    }
    .datagrid-wrap{
        min-height: 160px;
    }
</style>
