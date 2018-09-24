<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/27
 * Time: 14:56
 * 分配客户经理人
 */
use yii\helpers\Url;
use \yii\helpers\Html;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<style>
    .label-width{
        width:80px;
    }
    .value-width{
        width:200px;
    }
    .space-20{
        width:100%;
        height:20px;
    }
    .width-600{
        width:600px;
    }
    .ml-45{
        margin-left:45px;
    }
</style>
<div >
    <?php $form=\yii\widgets\ActiveForm::begin([
        "id"=>"add-form"
    ]);?>
    <h1 class="head-first">分配客户经理人</h1>
    <div class="mb-10 ml-45">
        <label class="label-width label-align" for="">被分配部门<label>：</label></label>
        <?=Html::dropDownList("",$orgCode,array_combine(array_column($department,"organization_code"),array_column($department,"organization_name")),["prompt"=>"请选择...","encode"=>false,"id"=>"department","class"=>"value-width easyui-validatebox","data-options"=>"required:true"])?>
        <label class="label-width label-align" for="">被分配人员<label>：</label></label>
        <?=Html::dropDownList("CrmCustPersoninch[ccpich_personid]",\Yii::$app->user->identity->staff_id,array_combine(array_column($orgStaff,"staff_id"),array_column($orgStaff,"staff_name")),["prompt"=>"请选择...","id"=>"allotman","class"=>"value-width easyui-validatebox","data-options"=>"required:true"])?>
    </div>
    <div class="space-20"></div>
    <input type="hidden" id="customers" name="customers" value="">
    <div class="red ml-45">*审核中的客户无法分配</div>
    <div class="width-600 ml-45">
        <div id="assign-data"></div>
    </div>
    <div class="space-20"></div>
    <div class="text-center">
        <button type="submit" class="button-blue-big sub">确定</button>
        <button class="button-white-big" onclick="close_select()" type="button">返回</button>
    </div>
    <?php $form->end();?>
</div>
<script>
    $(function(){
        $('.sub').click(function(){
            $("#add-form").attr('action', '<?= Url::to(['assign']) ?>');
           return ajaxSubmitForm($("#add-form"),'',function(res){
               parent.layer.alert(res.msg,{icon:1});
               parent.$("#data").datagrid("reload");
               parent.$.fancybox.close();
           });
        })



        $("#assign-data").datagrid({
            method: "get",
            idField: "cust_id",
            loadMsg: "加载数据请稍候。。。",
            rownumbers: true,
            singleSelect: true,
//            checkOnSelect: true,
//            selectOnCheck: true,
            columns:[[
                {field:"cust_filernumber",title:"客户编码",width:180},
                {field:"apply_code",title:"客户代码",width:180},
                {field:"cust_sname",title:"公司名称",width:180},
                {field:"cust_shortname",title:"公司简称",width:180},
            ]],
            onLoadSuccess:function(data){
                $("#assign-data").datagrid("resize");
            }
        });
        var rows = parent.$('#data').datagrid('getChecked');
        var arr = [];
        var arr1 = [];
        for(var i=0;i<rows.length;i++){
            if(rows[i].apply_status !== '30' && rows[i].apply_status !== '20'){
                arr.push(rows[i]);
                arr1.push(rows[i].cust_id);
            }
        }
        if(arr.length == 0){
            $("#assign-data").datagrid("insertRow",{
                index:0,
                row:{cust_filernumber:'<div class="red">无记录</div>'}
            }).datagrid('mergeCells', {
                index: 0,
                field: 'cust_filernumber',
                colspan: 4,
            });
        }
        for(var j=0;j<arr.length;j++){
            $("#assign-data").datagrid("insertRow",{
                index:0,
                row:arr[j]
            });
        }
        $('#customers').val(arr1.join(','));
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
    })
</script>
