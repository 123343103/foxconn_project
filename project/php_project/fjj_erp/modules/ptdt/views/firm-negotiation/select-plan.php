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
<div class="head-first">选择关联拜访计划</div>
<div style="margin: 0 20px 20px;">
    <div class="mb-10">
        <?php ActiveForm::begin(['id'=>'plan_form','method'=>'get','action'=>Url::to(['select-plan','firmId'=>$firmId])]);?>
        <label class="width-60">关键词</label>
        <input type="text" class="width-200" name="searchKeyword" value="<?=$params['searchKeyword']?>">
        <button type="submit" class="button-blue">查询</button>
        <button type="button" class="button-white" onclick="window.location.href='<?=Url::to(['select-plan','firmId'=>$firmId])?>'">重置</button>
        <?php ActiveForm::end();?>
    </div>
    <div id="plan_data" style="width:100%;"></div>
</div>
<div class="text-center">
    <button type="button" class="button-blue" id="confirm_plan">确定</button>
    <button type="button" class="button-white ml-20" onclick="parent.$.fancybox.close()">取消</button>
</div>
<script>
    $(function () {
        $("#plan_data").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url?>",
            rownumbers:true,
            method:"get",
            idField:"pvp_planID",
            singleSelect:true,
            pagination:true,
            pageSize:10,
            pageList:[10,20,30],
            columns:[[
                {field:"pvp_plancode",title:"档案编号",width:222},
                {field:"sname",title:"客户名称",width:240,formatter:function(value,row){
                    return row.firm.firm_sname;
                }},
                {field:"plan_date",title:"计划拜访日期",width:240}
            ]],
            onLoadSuccess:function(data){
                showEmpty($(this),data.total,0);
            }
        });

        //搜索
//        $("#search_plan").click(function(){
//            $("#plan_form").submit();
//        });

        //确定
        $("#confirm_plan").click(function(){
            var obj=$("#plan_data").datagrid('getSelected');
            if(obj==null){
                parent.layer.alert('请选择计划！',{icon:2,time:5000});
                return false;
            }
            $("#plan_id",window.parent.document).val(obj.pvp_planID);
            $("#plan_code",window.parent.document).val(obj.pvp_plancode);
            parent.$.fancybox.close();
        });
    })
</script>