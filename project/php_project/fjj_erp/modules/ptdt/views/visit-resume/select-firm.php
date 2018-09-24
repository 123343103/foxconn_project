<?php
/**
 * User: F1677929
 * Date: 2016/11/3
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<style>
    .fancybox-wrap{
        top:  0px !important;
        left: 0px !important;
    }
</style>
<div class="head-first">选择厂商</div>
<div style="margin: 0 20px 20px;">
    <div class="mb-10">
        <?php ActiveForm::begin(['id'=>'firm_form','method'=>'get','action'=>Url::to(['select-firm'])]);?>
        <label class="width-60">关键词</label>
        <input type="text" class="width-200" name="searchKeyword" value="<?=$params['searchKeyword']?>">
        <button type="submit" class="button-blue">查询</button>
        <button type="button" class="button-white" onclick="window.location.href='<?=Url::to(['select-firm'])?>'">重置</button>
        <button id="add_firm" type="button" class="button-blue float-right" style="width:80px;">新增厂商</button>
        <?php ActiveForm::end();?>
    </div>
    <div id="firm_data" style="width:100%;"></div>
</div>
<div class="text-center mb-20">
    <button type="button" class="button-blue mr-20" id="confirm_firm">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<script>
    $(function () {
        $("#firm_data").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url?>",
            rownumbers:true,
            method:"get",
            idField:"firm_id",
            singleSelect:true,
            pagination:true,
            pageSize:10,
            pageList:[10,20,30],
            columns:[[
                {field:"firm_sname",title:"厂商全称",width:200},
                {field:"firm_shortname",title:"厂商简称",width:100},
                {field:"firmAddress",title:"厂商地址",width:300},
                {field:"firmType",title:"厂商类型",width:94}
            ]],
            onLoadSuccess:function(data){
                showEmpty($(this),data.total,0);
            }
        });

        //确定
        $("#confirm_firm").click(function(){
            var obj=$("#firm_data").datagrid('getSelected');
            if(obj==null){
                parent.layer.alert('请选择厂商！',{icon:2,time:5000});
                return false;
            }
            for(x in obj){
                if(typeof obj[x]=="string"){
                    obj[x]=obj[x].decode();
                }
            }
            parent.$("#firm_id").val(obj.firm_id);
            parent.$("#firm_sname").val(obj.firm_sname).change();
            parent.$("#firm_shortname").val(obj.firm_shortname);
            parent.$("#firm_ename").val(obj.firm_ename);
            parent.$("#firm_eshortname").val(obj.firm_eshortname);
            parent.$("#firm_brand").val(obj.firm_brand);
            parent.$("#firm_brand_english").val(obj.firm_brand_english);
            parent.$("#firm_address").val(obj.firmAddress);
            parent.$("#firm_source").val(obj.firmSource);
            parent.$("#firm_type").val(obj.firmType);
            parent.$("#firm_issupplier").val(obj.firm_issupplier);
            parent.$("#firm_category_id").val(obj.firm_category_id);
            parent.$("#plan_id").val('');
            parent.$("#plan_code").val('');
            parent.$.fancybox.close();
        });

        $("#add_firm").click(function(){
            $.fancybox({
                href:"<?=Url::to(['/ptdt/visit-plan/add-firm'])?>",
                padding: [],
                fitToView: false,
                width: 815,
                height: 570,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
            });
        });
    })
</script>