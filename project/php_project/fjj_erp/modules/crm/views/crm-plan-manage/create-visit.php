<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\CrmVisitPlan */
?>
<style>
    .fancybox-wrap{
        top:  0px !important;
        left: 0px !important;
    }
</style>
<div class="no-padding">
            <div class="create-plan">
                <h2 class="head-first">添加拜访计划</h2>
                <?php $form =\yii\widgets\ActiveForm::begin([
                    'id'=>'visit-plan',
                    'enableAjaxValidation' => true,
                    'action'=>['/crm/crm-visit-plan/create']
                ])?>
                <div class="space-10"></div>
                <div class="mt-10 ml-30">
                    <label class="width-100"><span class="red">*</span>客户名称</label>
                    <input type="hidden" class="from" name="CrmVisitPlan[from]">
                    <input type="hidden" class="cust_id" name="CrmVisitPlan[cust_id]">
                    <span class="cust_name"></span>
                    <a class='select_customer ml-10'>选择客户</a>
                </div>
                <div id="customer_info" style="display:none;">
                    <div class="mt-20 ml-30">
                        <label class="width-100">联<span style="width:6px;"></span>系<span style="width:6px;"></span>人</label>
                        <span class="width-200 cust_contact"></span>
                        <label class="width-90">联系电话</label>
                        <span class="width-200 cust_tel"></span>
                    </div>
                    <div class="mt-20 ml-30">
                        <label class="width-100">公司地址</label>
                        <span class="cust_address" style="width:540px;"></span>
                    </div>
                </div>
                <div class="mt-20 ml-30">
                    <label class="width-100"><span class="red">*</span>拜<span style="width:6px;"></span>访<span style="width:6px;"></span>人</label>
                    <input class="width-130 easyui-validatebox" data-options="required:true,validType:'staffCode',delay:10000000" data-url="<?=Url::to(['/hr/staff/get-staff-info'])?>" name="CrmVisitPlan[svp_staff_code]"
                        <?php if(!Yii::$app->user->identity->is_supper){?>
                            readonly="readonly" value="<?=empty($staff)?'':$staff['staff_code']?>"
                        <?php }?>
                    >
                    <span class="staff_name" style="width:66px;">
                       <?php if(!Yii::$app->user->identity->is_supper){?>
                           <?=empty($staff)?'':$staff['staff_name']?>
                       <?php }?>
                    </span>
                    <label class="width-90"><span class="red">*</span>拜访类型</label>
                    <select name="CrmVisitPlan[svp_type]" class="width-130 easyui-validatebox validateboxs"  data-options="required:true">
                        <option value="">请选择...</option>
                        <?php foreach($downList['visitType'] as $val){ ?>
                            <option value="<?= $val['bsp_id']?>"><?= $val['bsp_svalue']?></option>
                        <?php  } ?>
                    </select>
                </div>
                <div class="mt-20 ml-30">
                    <label class="width-100"><span class="red">*</span>开始时间</label>
                    <input type="text"  class="width-130 select-date-time startDate easyui-validatebox  start_time_size"  data-options="required:true" id="startDate" name="CrmVisitPlan[start]" value="<?=$dateTime['start']?>"/>
                    <label class="width-160"><span class="red">*</span>结束时间</label>
                    <input type="text" class="width-130 select-date-time endDate easyui-validatebox  end_time_size"  data-options="required:true" id="endDate" name="CrmVisitPlan[end]" value="<?=$dateTime['end']?>"/>
                </div>
<!--                <div class="mt-20 ml-30">-->
<!--                    <label class="width-100">计划用时</label>-->
<!--                    <input type="text" class="width-30 text-center use_day" id="day" name="day"/> 天-->
<!--                    <input type="text" class="width-30 text-center use_hour" id="hours" name="hours"/> 时-->
<!--                    <input type="text" class="width-30 text-center use_minute" id="minutes" name="minutes"/> 分-->
<!--                </div>-->
                <div class="mt-20 ml-30">
                    <label class="width-100"><span class="red">*</span>计划内容</label>
                    <textarea type="text" rows="3" class="width-500 text-top easyui-validatebox validateboxs"  data-options="required:true" name="CrmVisitPlan[svp_content]"></textarea>
                </div>
                <div class="space-20"></div>
                <div class="mb-20 text-center">
                    <button class="button-blue" type="submit" id="visit-plan-submit">确定</button>
                    <button class="button-white ml-20 close" type="button">取消</button>
                </div>
                <?php $form->end(); ?>
                <div class="space-30"></div>
            </div>
            <div class="create-info" >
                <h2 class="head-first">添加拜访记录</h2>
                <?php $form =\yii\widgets\ActiveForm::begin([
                    'id'=>'visit-info',
                    'enableAjaxValidation' => true,
                    'action'=>['/crm/crm-visit-record/add']
                ])?>
                <div class="space-10"></div>
                <div class="mt-10 ml-30">
                    <label class="width-100"><span class="red">*</span>客户名称</label>
                    <input type="hidden" class="from" name="CrmVisitRecord[from]">
                    <input type="hidden" class="cust_id" name="CrmVisitRecord[cust_id]">
                    <span class="cust_name"></span>
                    <a class='select_customer ml-10'>选择客户</a>
                </div>
                <div id="customer_info" style="display:none;">
                    <div class="mt-20 ml-30">
                        <label class="width-100">联<span style="width:6px;"></span>系<span style="width:6px;"></span>人</label>
                        <span class="width-200 cust_contact"></span>
                        <label class="width-90">联系电话</label>
                        <span class="width-200 cust_tel"></span>
                    </div>
                    <div class="mt-20 ml-30">
                        <label class="width-100">公司地址</label>
                        <span class="cust_address" style="width:540px;"></span>
                    </div>
                </div>
<!--                <div class="mt-20 ml-30">-->
<!--                    <label class="width-100">拜访日期</label>-->
<!--                    <input type="text" class="select-date validateboxss easyui-validatebox"  data-options="required:true" name="CrmVisitRecordChild[sil_date]"/>-->
<!--                </div>-->
                <div class="mt-20 ml-30">
                    <label class="width-100"><span class="red">*</span>拜<span style="width:6px;"></span>访<span style="width:6px;"></span>人</label>
                    <input class="width-130 easyui-validatebox" data-options="required:true,validType:'staffCode',delay:10000000" data-url="<?=Url::to(['/hr/staff/get-staff-info'])?>" name="CrmVisitRecordChild[sil_staff_code]"
                        <?php if(!Yii::$app->user->identity->is_supper){?>
                            readonly="readonly" value="<?=empty($staff)?'':$staff['staff_code']?>"
                        <?php }?>
                    >
                    <span class="staff_name" style="width:66px;">
                        <?php if(!Yii::$app->user->identity->is_supper){?>
                            <?=empty($staff)?'':$staff['staff_name']?>
                        <?php }?>
                    </span>
                    <label class="width-90"><span class="red">*</span>拜访类型</label>
                    <select name="CrmVisitRecordChild[sil_type]" class="width-130 validateboxss easyui-validatebox" data-options="required:true">
                        <option value="">请选择...</option>
                        <?php foreach($downList['visitType'] as $val){ ?>
                            <option value="<?= $val['bsp_id']?>"><?= $val['bsp_svalue']?></option>
                        <?php  } ?>
                    </select>
                </div>
                <div class="mt-20 ml-30">
                    <label class="width-100"><span class="red">*</span>开始时间</label>
                    <input type="text"  class="width-130 select-date-time startDate  easyui-validatebox start_time_size" data-options="required:true" id="arriveDate" name="CrmVisitRecordChild[start]" value="<?=$dateTime['start']?>"/>
                    <label class="width-160"><span class="red">*</span>结束时间</label>
                    <input type="text" class="width-130 select-date-time endDate  easyui-validatebox end_time_size" data-options="required:true" id="leaveDate" name="CrmVisitRecordChild[end]" value="<?=$dateTime['end']?>"/>
                </div>
<!--                <div class="mt-20 ml-30">-->
<!--                    <label class="width-100">拜访用时</label>-->
<!--                    <input type="text" class="width-30 text-center use_day" id="day" name="day"/> 天-->
<!--                    <input type="text" class="width-30 text-center use_hour" id="hours" name="hour"/> 时-->
<!--                    <input type="text" class="width-30 text-center use_minute" id="minutes" name="minute"/> 分-->
<!--            </div>-->
                <div style="margin:20px 30px 3px;">接待人员信息：</div>
                <div style="margin:0 30px;">
                    <table class="table">
                        <thead>
                        <tr>
                            <th style="width:50px;">序号</th>
                            <th style="width:250px;">接待人员姓名</th>
                            <th style="width:250px;">职位</th>
                            <th style="width:250px;">联系电话</th>
                            <th style="width:130px;">操作</th>
                        </tr>
                        </thead>
                        <tbody id="reception_tbody">
                        <tr>
                            <td>1</td>
                            <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="required:true,validType:['tdSame','length[0,20]']" name="reception[0][CrmReception][rece_sname]"></td>
                            <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="validType:'length[0,20]'" name="reception[0][CrmReception][rece_position]"></td>
                            <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="required:true,validType:'mobile'" name="reception[0][CrmReception][rece_mobile]"></td>
                            <td><a class="reset_reception">重置</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="text-align: right;margin: 3px 50px 0;"><a id="add_reception">+添加行</a></div>
                <div class="mt-20 ml-30">
                    <label class="width-100">过程描述</label>
                    <textarea type="text" style="width:500px;height:50px;" class="text-top easyui-validatebox" name="CrmVisitRecordChild[sil_process_descript]" maxlength="255"></textarea>
                </div>
                <div class="mt-20 ml-30">
                    <label class="width-100">追踪事项</label>
                    <textarea type="text" style="width:500px;height:50px;" class="text-top easyui-validatebox" name="CrmVisitRecordChild[sil_track_item]" maxlength="255"></textarea>
                </div>
                <div class="mt-20 ml-30">
                    <label class="width-100">谈判事项</label>
                    <textarea type="text" style="width:500px;height:50px;" class="text-top easyui-validatebox" name="CrmVisitRecordChild[sil_next_interview_notice]" maxlength="255"></textarea>
                </div>
                <div class="mt-20 ml-30">
                    <label class="width-100"><span class="red">*</span>拜访总结</label>
                    <textarea type="text" style="width:500px;height:50px;" class="text-top validateboxss easyui-validatebox" data-options="required:true,validType:'length[0,255]'" name="CrmVisitRecordChild[sil_interview_conclus]"></textarea>
                </div>
                <div class="mt-20 ml-30">
                    <label class="width-100">备<span style="width:24px;"></span>注</label>
                    <textarea type="text" style="width:500px;height:50px;" class="text-top easyui-validatebox" name="CrmVisitRecordChild[remark]" maxlength="200"></textarea>
                </div>
                <div class="mt-20 ml-30">
                    <label class="width-100">拜访计划</label>
                    <input type="hidden" name="CrmVisitRecordChild[svp_plan_id]" id="svp_id"/>
                    <input type="text" class="width-130" id="svp_code"/>
                    <a id="select_plan">选择拜访计划</a>
                </div>
                <div class="space-20"></div>
                <div class="mb-20 text-center">
                    <button class="button-blue" type="submit" id="visit-info-submit">确定</button>
                    <button class="button-white ml-20 close" type="button">取消</button>
                </div>
                <?php $form->end(); ?>
            </div>
</div>
<script>

    $(function () {
        window.onload=function(){
            $(".select-date-time").change();
        };
        //验证规则
        $.extend($.fn.validatebox.methods, {
            remove: function(jq, newposition){
                return jq.each(function(){
                    $(this).removeClass("validatebox-text validatebox-invalid").unbind('focus.validatebox').unbind('blur.validatebox');
                });
            },
            reduce: function(jq, newposition){
                return jq.each(function(){
                    var opt = $(this).data().validatebox.options;
                    $(this).addClass("validatebox-text").validatebox(opt);
                });
            }
        });
        $.extend($.fn.validatebox.defaults.rules, {
            checkStaff: {//检查验证码
                validator: function (value) {
                    $(".staff_name").html(" ");
                    var check = false;
//                    if(value.match(/^[F]\d{7}$/)==null){
//                        msg='工号格式错误';
//                    }else{
                        $.ajax({
                            async:false,
                            type: 'get',
                            url: "<?=\yii\helpers\Url::to(['/hr/staff/get-staff-info']) ?>",
                            data: {
                                'code': value
                            },
                            dataType: "json",
                            success: function (data) {
                                if (data.staff_name == null) {
                                    msg = "未找到该工号员工";
                                }
                                if (data.staff_name != null ) {
                                    var name = data.staff_name;
                                    $(".staff_name").html(name);
                                    check = true;
                                }
                            }
                        });
//                    }
                    return check;
                },
                message: function () {
                    return msg;
                }
            }
        });
        $("#visit-plan-submit").one("click",function(){
//            $('.validateboxss').validatebox('remove');
                $('.validateboxs').validatebox('reduce');
                return  ajaxSubmitForm($("#visit-plan"),function(){
                    if($(".cust_id").val() === ''){
                        layer.alert('请选择客户',{icon:2});
                        return false;
                    }
                    return true;
                },function(){
                    parent.layer.alert("新增成功！",{icon:1},function(){
                        parent.window.location.reload();
                    });
                });
        });
        $("#visit-info-submit").one("click",function(){
//            $('.validateboxs').validatebox('remove');
            $('.validateboxss').validatebox('reduce');
            return  ajaxSubmitForm($("#visit-info"),function(){
                if($(".cust_id").val() === ''){
                    layer.alert('请选择客户',{icon:2});
                    return false;
                }
                return true;
            },function(){
                parent.layer.alert("新增成功！",{icon:1},function(){
                    parent.window.location.reload();
                });
            });
        });

        //选择拜访计划
        $("#select_plan").click(function(){
            var customerId=$(".cust_id:last").val();
            if(customerId==''){
                layer.alert('请先选择客户信息！',{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=\yii\helpers\Url::to(['/crm/crm-visit-record/select-plan'])?>?customerId="+customerId,
                type:"iframe",
                padding:0,
                autoSize:false,
                fitToView: false,
                width:700,
                height:540
            });
        });
        //选择客户
        $(".select_customer").click(function(){
            $.fancybox({
                href:"<?=\yii\helpers\Url::to(['select-customer'])?>",
                padding: [],
                fitToView: false,
                width: 700,
                height: 540,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',

            });
        });

        // 首页日历
        var from = "<?= $from ?>" || null;
        $(".from").val(from);
        <?php if(!empty($dateTime)) {?>
//            var startDate = "<?//= $dateTime['start']?>//"
//            var endDate="<?//= $dateTime['end']?>//"
            var time="<?= $dateTime['time']?>"
            if(time==1) {
                $(".create-info").remove();
            }
            if(time==0) {
                $(".create-plan").remove();
            }
//            dataTime(startDate,endDate,1);
//            dataTime(startDate,endDate,2);
//            $(".startDate").val(startDate);
//            $(".endDate").val(endDate);
        <?php }?>
        $(".close").click(function(){
            parent.$.fancybox.close();
        });
        //选择供应商窗口
        $('.select-cust').fancybox(
            {
                padding: [],
                fitToView: false,
                width: 600,
                height: 400,
                autoSize: false,
                closeClick: true,
                closeBtn:false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
            }
        );

        //添加接待人
        var reception_index=100;
        $("#add_reception").click(function(){
            var reception_tr="<tr>";
            reception_tr+="<td></td>";
            reception_tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:[\"tdSame\",\"length[0,20]\"]' name='reception["+reception_index+"][CrmReception][rece_sname]'></td>";
            reception_tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"length[0,20]\"' name='reception["+reception_index+"][CrmReception][rece_position]'></td>";
            reception_tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"mobile\"' name='reception["+reception_index+"][CrmReception][rece_mobile]'></td>";
            reception_tr+="<td><a class='delete_reception'>删除</a><a class='reset_reception ml-20'>重置</a></td>";
            reception_tr+="</tr>";
            $("#reception_tbody").append(reception_tr).find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
            reception_index++;
            $.parser.parse($("#reception_tbody").find("tr:last"));
            setMenuHeight();
        });
        $(".no-padding").on("click",".delete_reception",function(){
            $(this).parents("tr").remove();
            $("#reception_tbody").find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
        });
        $(".no-padding").on("click",".reset_reception",function(){
            $(this).parents("tr").find("input").val('');
        });
    });

</script>