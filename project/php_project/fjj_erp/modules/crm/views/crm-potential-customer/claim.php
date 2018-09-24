<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/16
 * Time: 下午 03:44
 */

use app\assets\JqueryUIAsset;
use yii\helpers\Url;
JqueryUIAsset::register($this);
?>
<?php $form=\yii\widgets\ActiveForm::begin([
        "id"=>"claim-form"
]);?>
<div id="emailbox">
    <h2 class="head-first">客户认领</h2>
    <div style="padding:30px;">
        <input id="customers" type="hidden" name="customers" value="">
        <div class="mt-20">
            <label class="width-100" for="">认领部门</label>
            <select class="width-200 easyui-validatebox"  id="department" data-options="required:true">
                <option value="">请选择...</option>
                <?php foreach($department as $item){ ?>
                    <option <?=$item[organization_code]==$claim_info[organization_code]?"selected":""?> value="<?=$item['organization_code'];?>"><?=$item['organization_name'];?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mt-20">
            <label class="width-100" for="">认领客户经理</label>
            <select class="width-200 salearea easyui-validatebox" id="allotman" data-options="required:true">
                <?php if($claim_info){ ?>
                    <option selected value="<?=$claim_info['staff_id'];?>"><?=$claim_info["staff_name"];?></option>
                <?php }else{ ?>
                    <option value="">请选择</option>
                <?php }?>
            </select>
        </div>
        <div class="text-center mt-20">
            <?php if($claim_info){ ?>
                <button type="button" class="button-white undo">取消认领</button>
            <?php }else{ ?>
                <button type="submit" class="button-blue ensure">确定</button>
            <?php } ?>
                <button type="button" class="button-white cancel">取消</button>
        </div>
    </div>
</div>
<?php $form->end();?>
<script>
    $(function(){
        var row=parent.$("#data").datagrid("getSelected");
        $("#customers").val(row.cust_id);


        //客户认领ajax表单
        ajaxSubmitForm($("#claim-form"),'',function(data){
            parent.$.fancybox.close();
            parent.layer.alert(data.msg,{icon:1});
            parent.window.location.reload();
        });

        //取消认领
        $(".undo").click(function(){
            var row=parent.$("#data").datagrid("getSelected");
            if($("#allotman").val()==""){
                parent.layer.alert("请选择销售人员",{icon:2});
                return false;
            }
            if(row.personinch==""){
                parent.layer.alert("当前客户未被认领",{icon:2});
                return false;
            }
            $.get("<?=Url::to(['undo-claim'])?>?customers="+$("#customers").val(),function(res){
                var data=JSON.parse(res);
                parent.$.fancybox.close();
                parent.layer.alert(data.msg,{icon:1});
                parent.window.location.reload();
            });
        });


        //关闭弹窗
        $(".cancel").click(function(){
            parent.$.fancybox.close();
        });


        //选择部门
        $("#department").on("change",function(){
            $("#allotman option").remove();
            $("#allotman").append("<option value=''>请选择</option>").prop("disabled",true);
            $.get("<?=Url::to(['allotman-select'])?>?org_code="+$(this).val(),function(res){
                var data=JSON.parse(res);
                $("#allotman").prop("disabled",false);
                $("#allotman option:gt(0)").remove();
                $("#allotman").attr("name","CrmCustPersoninch[ccpich_personid]");
                for(item in data){
                    $("#allotman").append("<option value='"+data[item].staff_id+"'>"+data[item].staff_name+"</option>");
                }
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
</style>