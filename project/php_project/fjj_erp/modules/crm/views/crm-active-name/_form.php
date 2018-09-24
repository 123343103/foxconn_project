<?php
/**
 * User: F1677929
 * Date: 2017/3/9
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'活动相关设置','url'=>Url::to(['/crm/crm-active-set/index'])];
$this->params['breadcrumbs'][]=['label'=>'活动名称列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?><span style="font-size:12px;color:white;float:right;margin-right:15px;"><?=empty($editData)?'':'编号：'.$editData['actbs_code']?></span></h1>
    <?php ActiveForm::begin(['id'=>'active_name_form']);?>
    <div class="mb-20">
        <label class="width-100">活动方式</label>
        <select id="active_way" class="width-200">
            <?php foreach($addEditData['activeWay'] as $key=>$val){?>
                <option value="<?=$key?>" <?=$val==$activeWayName?'selected':''?>><?=$val?></option>
            <?php }?>
        </select>
        <label class="width-100"><span class="red">*</span>活动类型</label>
        <select id="active_type" class="width-200 easyui-validatebox" data-options="required:true" name="CrmActiveName[acttype_id]">
            <option value="">请选择</option>
            <?php if(!empty($activeTypeName)){?>
                <?php foreach($activeTypeName as $val){?>
                    <option value="<?=$val['acttype_id']?>" <?=$val['acttype_id']==$editData['acttype_id']?'selected':''?>><?=$val['acttype_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <label class="width-100"><span class="red">*</span>活动月份</label>
        <select class="width-200 easyui-validatebox" data-options="required:true" name="CrmActiveName[actbs_month]">
            <option value="">请选择</option>
            <?php foreach($addEditData['activeMonth'] as $key=>$val){?>
                <option value="<?=$key?>" <?=$key==$editData['actbs_month']?'selected':''?>><?=$val?></option>
            <?php }?>
        </select>
    </div>
    <div class="mb-20">
        <label class="width-100"><span class="red">*</span>活动名称</label>
        <input type="text" style="width:506px;" class="easyui-validatebox" data-options="required:true,validType:'length[0,100]'" name="CrmActiveName[actbs_name]" value="<?=$editData['actbs_name']?>">
        <label class="width-100"><span class="red">*</span>活动状态</label>
        <select class="width-200 easyui-validatebox" data-options="required:true" name="CrmActiveName[actbs_status]">
            <option value="">请选择</option>
            <?php foreach($addEditData['activeNameStatus'] as $key=>$val){?>
                <option value="<?=$key?>" <?=$key==$editData['actbs_status']?'selected':''?>><?=$val?></option>
            <?php }?>
        </select>
    </div>
    <div class="mb-20">
        <label class="width-100"><span class="red">*</span>开始时间</label>
        <input type="text" class="width-200 select-date-time start_time_size easyui-validatebox" data-options="required:true" readonly="readonly" name="CrmActiveName[actbs_start_time]" value="<?=empty($editData['actbs_start_time'])?date('Y-m-d').' 08:00':date('Y-m-d H:i',strtotime($editData['actbs_start_time']))?>">
        <label class="width-100"><span class="red">*</span>结束时间</label>
        <input type="text" class="width-200 select-date-time end_time_size easyui-validatebox" data-options="required:true" readonly="readonly" name="CrmActiveName[actbs_end_time]" value="<?=empty($editData['actbs_end_time'])?date('Y-m-d').' 17:30':date('Y-m-d H:i',strtotime($editData['actbs_end_time']))?>">
    </div>
    <div class="mb-20">
        <label class="width-100">活动成本预算</label>
        <input type="text" class="width-200 easyui-validatebox" data-options="validType:['int','length[0,50]']" name="CrmActiveName[actbs_cost]" value="<?=$editData['actbs_cost']?>">
        <label class="width-100">活动负责人</label>
        <input type="hidden" name="CrmActiveName[actbs_duty]" class="staff_id" value="<?=$addEditData['staff']['staff_id']?>">
        <input type="text" class="width-150 easyui-validatebox staff_code_null" data-options="validType:'staffCode',delay:1000000" data-url="<?=Url::to(['/hr/staff/get-staff-info'])?>" value="<?=$addEditData['staff']['staff_code']?>">
        <span style="width:86px;" class="staff_name"><?=$addEditData['staff']['staff_name']?></span>
    </div>
    <div class="online_div"
        <?php if($activeWayName=='线下'){?>
            style="display:none;"
        <?php }?>
    >
        <div class="mb-40">
            <label class="width-100">活动网址</label>
            <input type="text" class="width-200 easyui-validatebox" data-options="validType:['www','length[0,50]']" name="CrmActiveName[actbs_active_url]" value="<?=$editData['actbs_active_url']?>">
            <label class="width-100">活动PM</label>
            <input type="text" class="width-200 easyui-validatebox" data-options="validType:'length[0,50]'" name="CrmActiveName[actbs_pm]" value="<?=$editData['actbs_pm']?>">
        </div>
    </div>
    <div class="offline_div"
        <?php if(empty($activeWayName)||$activeWayName=='线上'){?>
            style="display:none;"
        <?php }?>
    >
        <div class="mb-20">
            <label class="width-100">活动城市</label>
            <input type="text" class="width-200 easyui-validatebox" data-options="validType:'length[0,20]'" name="CrmActiveName[actbs_city]" value="<?=$editData['actbs_city']?>">
            <label class="width-100">行业类别</label>
            <select class="width-200" name="CrmActiveName[actbs_industry]">
                <option value="">请选择</option>
                <?php foreach($addEditData['industryType'] as $key=>$val){?>
                    <option value="<?=$key?>" <?=$key==$editData['actbs_industry']?'selected':''?>><?=$val?></option>
                <?php }?>
            </select>
            <label class="width-100">主办单位</label>
            <input type="text" class="width-200 easyui-validatebox" data-options="validType:'length[0,50]'" name="CrmActiveName[actbs_organizers]" value="<?=$editData['actbs_organizers']?>">
        </div>
        <div class="mb-20">
            <label class="width-100">活动地点</label>
            <select class="width-120 district_change">
                <option value="">请选择</option>
                <?php foreach($addEditData['country'] as $val){?>
                    <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtData['districtId'][0]?'selected':''?>><?=$val['district_name']?></option>
                <?php }?>
            </select>
            <select class="width-120 district_change">
                <option value="">请选择</option>
                <?php if(!empty($districtData)){?>
                    <?php foreach($districtData['districtName'][1] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtData['districtId'][1]?'selected':''?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <select class="width-120 district_change">
                <option value="">请选择</option>
                <?php if(!empty($districtData)){?>
                    <?php foreach($districtData['districtName'][2] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtData['districtId'][2]?'selected':''?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <select class="width-120 district_change" name="CrmActiveName[actbs_address_id]">
                <option value="">请选择</option>
                <?php if(!empty($districtData)){?>
                    <?php foreach($districtData['districtName'][3] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtData['districtId'][3]?'selected':''?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <input type="text" style="width:320px;" name="CrmActiveName[actbs_address]" class="easyui-validatebox" data-options="validType:'length[0,20]'" value="<?=$editData['actbs_address']?>">
        </div>
        <div class="mb-20">
            <label class="width-100">官方网址</label>
            <input type="text" class="width-200 easyui-validatebox" data-options="validType:['www','length[0,50]']" name="CrmActiveName[actbs_official_url]" value="<?=$editData['actbs_official_url']?>">
            <label class="width-100">参与目的</label>
            <select class="width-200" name="CrmActiveName[actbs_purpose]">
                <option value="">请选择</option>
                <?php foreach($addEditData['joinPurpose'] as $key=>$val){?>
                    <option value="<?=$key?>" <?=$key==$editData['actbs_purpose']?'selected':''?>><?=$val?></option>
                <?php }?>
            </select>
        </div>
        <div class="mb-20">
            <label class="width-100 vertical-top">展品类别</label>
            <textarea style="width:814px;height:80px;" class="easyui-validatebox" data-options="validType:'length[0,255]'" name="CrmActiveName[actbs_exhibit]"><?=$editData['actbs_exhibit']?></textarea>
        </div>
        <div class="mb-20">
            <label class="width-100 vertical-top">活动简介</label>
            <textarea style="width:814px;height:80px;" class="easyui-validatebox" data-options="validType:'length[0,255]'" name="CrmActiveName[actbs_intro]"><?=$editData['actbs_intro']?></textarea>
        </div>
        <div class="mb-40">
            <label class="width-100">维护人员</label>
            <input type="hidden" name="CrmActiveName[actbs_maintain]" class="staff_id" value="<?=$addEditData['staff']['staff_id']?>">
            <input type="text" class="width-150 easyui-validatebox staff_code_null" data-options="validType:'staffCode',delay:1000000" data-url="<?=Url::to(['/hr/staff/get-staff-info'])?>" value="<?=$addEditData['staff']['staff_code']?>">
            <span style="width:86px;" class="staff_name"><?=$addEditData['staff']['staff_name']?></span>
            <label class="width-60">维护时间</label>
            <input type="text" class="width-200 select-date-time" readonly="readonly" name="CrmActiveName[actbs_maintain_time]" value="<?=empty($editData)?date('Y-m-d H:i'):date('Y-m-d H:i',strtotime($editData['actbs_maintain_time']))?>">
            <label class="width-100">维护人员IP</label>
            <input type="text" class="width-200 easyui-validatebox" data-options="validType:'ip'" name="CrmActiveName[actbs_maintain_ip]" value="<?=empty($editData)?$_SERVER['REMOTE_ADDR']:$editData['actbs_maintain_ip']?>">
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="button-blue-big mr-20">确定</button>
        <button type="button" class="button-white-big" onclick="history.go(-1)">返回</button>
    </div>
    <?php ActiveForm::end();?>
</div>
<script>
    $(function(){
        //ajax提交表单
        ajaxSubmitForm($("#active_name_form"),function(){
            $(".online_div:hidden,.offline_div:hidden").find("input,select,textarea").val('');
            return true;
        });

        //线上线下切换
        $("#active_way").change(function(){
            $(".online_div,.offline_div").toggle();
            $("#active_type").html("<option value=''>请选择</option>").validatebox();
            $.ajax({
                url:"<?=Url::to(['get-active-type'])?>",
                data:{"wayId":this.value},
                dataType:"json",
                async:false,
                success:function(data){
                    $.each(data,function(i,n){
                        $("#active_type").append("<option value='"+n.acttype_id+"'>"+n.acttype_name+"</option>");
                    });
                }
            });
        });

        //地址联动-郭文聪
        $(".district_change").change(function(){
            var $select=$(this);
            $select.nextAll("select").html("<option value=''>请选择</option>");
            if($select.val()==''||$(".district_change:eq(3)").val()){
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['get-district'])?>",
                data:{"id":$select.val()},
                dataType:"json",
                success:function(data){
                    $.each(data,function(i,n){
                        $select.next().append("<option value='"+n.district_id+"'>"+n.district_name+"</option>");
                    })
                }
            });
        });
    })
</script>