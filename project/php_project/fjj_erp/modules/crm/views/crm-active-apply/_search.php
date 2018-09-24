<?php
/**
 * User: f1677929
 * Date: 2017/2/23
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<?php ActiveForm::begin(['method'=>'get','action'=>Url::to(['index'])]);?>
<div class="mb-15">
    <label class="width-80">活动类型</label>
    <select id="active_type" class="width-180" name="acttype_name">
        <option value="">请选择...</option>
        <?php foreach($activeType as $key=>$val){?>
            <option value="<?=$key?>" <?=$params['acttype_name']==$key?'selected':''?>><?=$val?></option>
        <?php }?>
    </select>
    <label class="width-100">活动名称</label>
    <select id="active_name" class="width-180" name="actbs_id">
        <option value="">请选择...</option>
    </select>
    <label class="width-100">公司名称</label>
    <input type="text" class="width-180" name="cust_sname" value="<?=$params['cust_sname']?>">
</div>
<div class="mb-20">
    <label class="width-80">姓名</label>
    <input type="text" class="width-180" name="acth_name" value="<?=$params['acth_name']?>">
    <label class="width-100">签到信息</label>
    <select class="width-180" name="acth_ischeckin">
        <option value="">请选择...</option>
        <option value="0" <?=$params['acth_ischeckin']=='0'?'selected':''?>>未签到</option>
        <option value="1" <?=$params['acth_ischeckin']=='1'?'selected':''?>>已签到</option>
    </select>
    <label class="width-100">活动日期</label>
    <input class="select-date" type="text" style="width:81px;" readonly="readonly" name="actbs_start_time" value="<?=$params['actbs_start_time']?>">
    <span>至</span>
    <input class="select-date" type="text" style="width:80px;" readonly="readonly" name="actbs_end_time" value="<?=$params['actbs_end_time']?>">
    <button type="submit" class="button-blue">查询</button>
    <button type="button" class="button-white" onclick="window.location.href='<?=Url::to(['index'])?>'">重置</button>
</div>
<?php ActiveForm::end();?>
<script>
    $(function(){
        //活动类型
        $("#active_type").change(function(){
            $("#active_name").html("<option value=''>请选择</option>");
            var typeId=$(this).val();
            if(typeId==''){
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['get-active-name'])?>",
                data:{"typeId":typeId},
                dataType:"json",
                success:function(data){
                    $.each(data,function(i,n){
                        $("#active_name").append("<option value='"+n.actbs_id+"'>"+n.actbs_name+"</option>");
                    })
                }
            })
        });
    })
</script>
