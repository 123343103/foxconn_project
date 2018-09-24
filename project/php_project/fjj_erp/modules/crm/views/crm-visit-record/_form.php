<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\classes\Menu;
\app\assets\JeDateAsset::register($this);
$this->params['homeLike']=['label'=>'客户关系管理'];
$this->params['breadcrumbs'][]=['label'=>'拜访记录管理','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?><span style="font-size:12px;color:white;float:right;margin-right:15px;"><?=empty($recordChild)?'':'编号：'.$recordChild['sil_code']?></span></h1>
    <?php ActiveForm::begin(['id'=>'record_form']);?>
    <h2 class="head-second">
        客户基本信息
        <?php if(empty($planId)){?>
            <?=(Yii::$app->controller->action->id==='add' || Yii::$app->controller->action->id==='record-add')?"<a id='select_customer' style='margin-left: 20px;'>选择客户</a>":""?>
        <?php }?>
    </h2>
    <input id="cust_id" type="hidden" name="CrmVisitRecord[cust_id]" value="<?=empty($customerInfo)?'':$customerInfo['cust_id']?>">
    <div id="customer_info"
        <?php if(empty($customerInfo)){?>
            style="display:none;"
        <?php }?>
    >
        <table width="62%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户名称<label>：</label></td>
                <td id="cust_sname" class="no-border vertical-center" width="20%"><?=$customerInfo['cust_sname']?></td>
                <td class="no-border vertical-center label-align" width="10%">客户类型<label>：</label></td>
                <td id="customerType" class="no-border vertical-center" width="20%"><?=$customerInfo['customerType']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户经理<label>：</label></td>
                <td id="customerManager" class="no-border vertical-center" width="20%"><?=$customerInfo['customerManager']?></td>
                <td class="no-border vertical-center label-align" width="10%">营销区域<label>：</label></td>
                <td id="csarea_name" class="no-border vertical-center" width="20%"><?=$customerInfo['csarea_name']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">联系人<label>：</label></td>
                <td id="cust_contacts" class="no-border vertical-center" width="20%"><?=$customerInfo['cust_contacts']?></td>
                <td class="no-border vertical-center label-align" width="10%">联系电话<label>：</label></td>
                <td id="cust_tel2" class="no-border vertical-center" width="20%"><?=$customerInfo['cust_tel2']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">公司地址<label>：</label></td>
                <td id="customer_address" class="no-border vertical-center qvalue-align" colspan="3" width="50%"><?=$customerInfo['customerAddress']?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second">拜访记录</h2>
    <div class="mb-10">
        <?php if(Yii::$app->user->identity->is_supper == 1){?>
            <label style="width:100px;"><span class="red">*</span>拜访人：</label>
            <select style="width:200px;" name="CrmVisitRecordChild[sil_staff_code]" class="easyui-validatebox staff_code" data-options="required:true">
            </select>
        <?php }else{?>
            <label style="width:100px;">拜访人：</label>
            <input type="hidden" name="CrmVisitRecordChild[sil_staff_code]" value="<?=$visitPerson['staff_code']?>" class="staff_code">
            <span style="width:200px;"><?=$visitPerson['staff_code'].'('.$visitPerson['staff_name'].')'?></span>
        <?php }?>
        <label style="width:100px;"><span class="red">*</span>拜访类型：</label>
        <select style="width:200px;" class="easyui-validatebox" data-options="required:true" name="CrmVisitRecordChild[sil_type]">
            <option value="">请选择</option>
            <?php foreach($visitType as $key=>$val){?>
                <option value="<?=$key?>" <?=!empty($recordChild)&&$recordChild['sil_type']==$key?'selected':''?>><?=$val?></option>
            <?php }?>
        </select>
        <label style="width:100px;">拜访方式：</label>
        <?php if(empty($planId)){?>
            <?php if(Yii::$app->controller->action->id == 'add'){?>
                <select id="visit_way" name="CrmVisitRecordChild[type]" style="width:200px;display:none;">
                    <option value="30">临时拜访</option>
                    <option value="20">计划拜访</option>
                </select>
                <input id="visit_way_input" type="hidden" name="CrmVisitRecordChild[type]" value="30">
                <span id="visit_way_span" style="width:200px;display:none;">临时拜访</span>
            <?php }?>
            <?php if(Yii::$app->controller->action->id == 'edit'){?>
                <span style="width:200px;">
                    <?php
                        if($recordChild['type']==='20'){
                            echo '计划拜访';
                        }elseif($recordChild['type']==='30'){
                            echo '临时拜访';
                        }
                    ?>
                </span>
            <?php }?>
        <?php }else{?>
            <span style="width:200px;">计划拜访</span>
        <?php }?>
    </div>
    <?php if($recordChild['type']!=='30'){?>
    <div id="plan_div" class="mb-10"
    <?php if(!empty($planId) || $recordChild['type']==='20'){?>
        style="display:block;"
    <?php }else{?>
        style="display:none;"
    <?php }?>
    >
        <input id="svp_id" type="hidden" name="CrmVisitRecordChild[svp_plan_id]" value="<?=empty($visitPlan)?'':$visitPlan['svp_id']?>">
        <?php if(empty($planId) && (Yii::$app->controller->action->id==='add' || Yii::$app->controller->action->id==='record-add')){?>
            <label style="width:100px;"><span style="color:red;">*</span>关联拜访计划：</label>
            <input style="width:200px;" id="svp_code" readonly="readonly" value="<?=empty($visitPlan)?'':$visitPlan['svp_code']?>" placeholder="请点击选择关联拜访计划">
        <?php }?>
        <?php if(!empty($planId) || Yii::$app->controller->action->id=='edit'){?>
            <label style="width:100px;">关联拜访计划：</label>
            <span style="width:200px;">
                <?php if(Menu::isAction('/crm/crm-visit-plan/view')){?>
                    <a href="<?=Url::to(['/crm/crm-visit-plan/view','id'=>$visitPlan['svp_id']])?>"><?=$visitPlan['svp_code']?></a>
                <?php }else{ ?>
                    <?=$visitPlan['svp_code']?>
                <?php }?>
            </span>
        <?php }?>
        <label style="width:100px;">计划开始时间：</label>
        <span id="plan_st" style="width:200px;"><?=substr($visitPlan['start'],0,16)?></span>
        <label style="width:100px;">计划结束时间：</label>
        <span id="plan_et" style="width:200px;"><?=substr($visitPlan['end'],0,16)?></span>
    </div>
    <?php }?>
    <div class="mb-10">
        <label style="width:100px;"><span class="red">*</span>记录开始时间：</label>
        <input id="start_time" style="width:200px;" class="Wdate easyui-validatebox" data-options="required:true,validType:'timeCompare'" name="CrmVisitRecordChild[start]" readonly="readonly" value="<?=empty($recordChild['start'])?substr($visitPlan['start'],0,16):date('Y-m-d H:i',strtotime($recordChild['start']))?>">
        <label style="width:100px;"><span class="red">*</span>记录结束时间：</label>
        <input id="end_time" style="width:200px;" class="Wdate easyui-validatebox" data-options="required:true,validType:'timeCompare'" name="CrmVisitRecordChild[end]" readonly="readonly" value="<?=empty($recordChild['end'])?substr($visitPlan['end'],0,16):date('Y-m-d H:i',strtotime($recordChild['end']))?>">
    </div>
    <div class="mb-10" style="margin-left:30px;margin-right:30px;">
        <p style="margin-bottom:2px;font-size:13px;font-weight:900;">陪同人员信息：</p>
        <table class="table">
            <thead>
            <tr>
                <th style="width:4%;">序号</th>
                <th style="width:21%;"><span style="color:red;">*</span>工号</th>
                <th style="width:21%;">姓名</th>
                <th style="width:21%;">职位</th>
                <th style="width:21%;"><span style="color:red;">*</span>联系电话</th>
                <th style="width:12%;">操作</th>
            </tr>
            </thead>
            <tbody id="accompany_tbody">
            <?php if(!empty($accompanyData)){?>
                <?php foreach($accompanyData as $key=>$val){?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td>
                            <input class="staff_id" type="hidden" name="accompany[<?=$key?>][CrmAccompany][acc_id]" value="<?=$val['staff_id']?>">
                            <input type="text" style="width:100%;text-align:center;" class="easyui-validatebox staff_code" data-options="validType:['tdSame','staffCode'],validateOnBlur:true,delay:1000000" data-url="<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>" value="<?=$val['staff_code']?>">
                        </td>
                        <td class="staff_name"><?=$val['staff_name']?></td>
                        <td class="job_task"><?=$val['title_name']?></td>
                        <td>
                            <input type="text" style="width:100%;text-align:center;" class="easyui-validatebox staff_mobile" data-options="validType:'mobile'" name="accompany[<?=$key?>][CrmAccompany][acc_mobile]" placeholder="请输入:138xxxxxxxx" value="<?=$val['acc_mobile']?>">
                        </td>
                        <td>
                            <a class="delete_accompany">删除</a>
                            <a class="reset_accompany" style="margin-left:15px;">重置</a>
                        </td>
                    </tr>
                <?php }?>
            <?php }else{?>
                <tr>
                    <td>1</td>
                    <td>
                        <input class="staff_id" type="hidden" name="accompany[0][CrmAccompany][acc_id]">
                        <input type="text" style="width:100%;text-align:center;" class="easyui-validatebox staff_code" data-options="validType:['tdSame','staffCode'],validateOnBlur:true,delay:1000000" data-url="<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>">
                    </td>
                    <td class="staff_name"></td>
                    <td class="job_task"></td>
                    <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox staff_mobile" name="accompany[0][CrmAccompany][acc_mobile]" data-options="validType:'mobile'" placeholder='请输入:138xxxxxxxx'></td>
                    <td>
                        <a class="delete_accompany">删除</a>
                        <a class="reset_accompany" style="margin-left:15px;">重置</a>
                    </td>
                </tr>
            <?php }?>
            </tbody>
        </table>
        <p style="margin:5px 0 0 850px;"><a id="add_accompany">+添加行</a></p>
    </div>
    <div class="mb-10" style="margin-left:30px;margin-right:30px;">
        <p style="margin-bottom:2px;font-size:13px;font-weight:900;">接待人员信息：</p>
        <table class="table">
            <thead>
            <tr>
                <th style="width:4%;">序号</th>
                <th style="width:28%;"><span style="color: red;">*</span>接待人员姓名</th>
                <th style="width:28%;">职位</th>
                <th style="width:28%;"><span style="color: red;">*</span>联系电话</th>
                <th style="width:12%;">操作</th>
            </tr>
            </thead>
            <tbody id="reception_tbody">
            <?php if(!empty($receptionData)){?>
                <?php foreach($receptionData as $key=>$val){?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox <?=$key==0?'':'rece_sname'?>" data-options="<?=$key==0?'required:true,':''?>validType:['tdSame','length[0,20]']" name="reception[<?=$key?>][CrmReception][rece_sname]" value="<?=$val['rece_sname']?>"></td>
                        <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox <?=$key==0?'':'rece_position'?>" data-options="validType:'length[0,20]'" name="reception[<?=$key?>][CrmReception][rece_position]" value="<?=$val['rece_position']?>"></td>
                        <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox <?=$key==0?'':'rece_mobile'?>" data-options="<?=$key==0?'required:true,':''?>validType:'mobile'" name="reception[<?=$key?>][CrmReception][rece_mobile]" value="<?=$val['rece_mobile']?>"></td>
                        <td>
                            <?php if($key==0){?>
                                <a class="reset_reception">重置</a>
                            <?php }else{?>
                                <a class="delete_reception" style="margin-right:20px;">删除</a><a class="reset_reception">重置</a>
                            <?php }?>
                        </td>
                    </tr>
                <?php }?>
            <?php }else{?>
                <tr>
                    <td>1</td>
                    <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="required:true,validType:['tdSame','length[0,20]']" name="reception[0][CrmReception][rece_sname]"></td>
                    <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="validType:'length[0,20]'" name="reception[0][CrmReception][rece_position]"></td>
                    <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="required:true,validType:'mobile'" name="reception[0][CrmReception][rece_mobile]" placeholder="请输入:138xxxxxxxx"></td>
                    <td><a class="reset_reception">重置</a></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
        <p style="margin:5px 0 0 850px;"><a id="add_reception">+添加行</a></p>
    </div>
    <div class="mb-10">
        <label style="width:100px;" class="vertical-top">过程描述：</label>
        <textarea class="easyui-validatebox" data-options="validType:'length[0,200]'" style="width:815px;height:70px;font-size:12px;" name="CrmVisitRecordChild[sil_process_descript]" placeholder="最多输入200个字"><?=empty($recordChild)?'':$recordChild['sil_process_descript']?></textarea>
    </div>
    <div class="mb-10">
        <label style="width:100px;" class="vertical-top">追踪事项：</label>
        <textarea class="easyui-validatebox" data-options="validType:'length[0,200]'" style="width:815px;height:70px;font-size:12px;" name="CrmVisitRecordChild[sil_track_item]" placeholder="最多输入200个字"><?=empty($recordChild)?'':$recordChild['sil_track_item']?></textarea>
    </div>
    <div class="mb-10">
        <label style="width:100px;" class="vertical-top">谈判注意事项：</label>
        <textarea class="easyui-validatebox" data-options="validType:'length[0,200]'" style="width:815px;height:70px;font-size:12px;" name="CrmVisitRecordChild[sil_next_interview_notice]" placeholder="最多输入200个字"><?=empty($recordChild)?'':$recordChild['sil_next_interview_notice']?></textarea>
    </div>
    <div class="mb-10">
        <label style="width:100px;" class="vertical-top"><span class="red">*</span>拜访总结：</label>
        <textarea class="easyui-validatebox" data-options="required:true,validType:'length[0,200]'" style="width:815px;height:70px;font-size:12px;" name="CrmVisitRecordChild[sil_interview_conclus]" placeholder="最多输入200个字"><?=empty($recordChild)?'':$recordChild['sil_interview_conclus']?></textarea>
    </div>
    <div class="mb-10">
        <label style="width:100px;" class="vertical-top">备注：</label>
        <textarea class="easyui-validatebox" data-options="validType:'length[0,200]'" style="width:815px;height:70px;font-size:12px;" name="CrmVisitRecordChild[remark]" placeholder="最多输入200个字"><?=empty($recordChild)?'':$recordChild['remark']?></textarea>
    </div>
    <div class="text-center">
        <button class="button-blue-big" type="submit">保存</button>
        <button class="button-white-big" type="button" onclick="history.go(-1)">返回</button>
    </div>
    <?php ActiveForm::end();?>
</div>
<script>
    $(function(){
        //ajax提交表单
        ajaxSubmitForm("form",function(){
            //客户验证
            if($("#cust_id").val() === ''){
                layer.alert('请选择客户',{icon:2});
                return false;
            }
            //记录开始时间要大于等于计划开始时间小于计划结束时间
//            var visitWay=$("#visit_way").val();
//            if(visitWay === '20'){
//                var pst=$("#plan_st").text();
//                var pet=$("#plan_et").text();
//                if((pst!=='') && (pet!=='')){
//                    var rst=$("#start_time").val();
//                    pst=Date.parse(pst.replace(/-/g,'/'));
//                    pet=Date.parse(pet.replace(/-/g,'/'));
//                    rst=Date.parse(rst.replace(/-/g,'/'));
//                    if(rst < pst){
//                        layer.alert('记录开始时间要大于等于计划开始时间',{icon:2});
//                        return false;
//                    }
//                    if(rst >= pet){
//                        layer.alert('记录开始时间要小于计划结束时间',{icon:2});
//                        return false;
//                    }
//                }
//            }
            //拜访方式为“临时拜访”，关联拜访计划隐藏，开始时间<结束时间<=当前时间
//            if(visitWay === '30'){
//                var ret=$("#end_time").val();
//                if(ret !== ''){
//                    ret=Date.parse(ret.replace(/-/g,'/'));
//                    var date=new Date();
//                    if(ret > date.getTime()){
//                        layer.alert('拜访记录结束时间要小于等于当前时间',{icon:2});
//                        return false;
//                    }
//                }
//            }
            //防止陪同人和拜访人相同
            var flag=true;
            var visitPersonCode=$(".staff_code").val();
            if(visitPersonCode != ''){
                $.each($("#accompany_tbody").find("tr"),function(i,n){
                    if($(n).find(".staff_code").val() == visitPersonCode){
                        flag=false;
                        return false;
                    }
                });
            }
            if(flag==false){
                layer.alert('陪同人不可为拜访人',{icon:2});
            }
            return flag;
        });

        //选择客户
        $("#select_customer").click(function(){
            $.fancybox({
                href:"<?=Url::to(['/crm/crm-visit-record/select-customer'])?>",
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:520
            });
        });

        //选择拜访计划
        $("#svp_code").click(function(){
            var customerId=$("#cust_id").val();
            if(customerId==''){
                layer.alert('请选择客户！',{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['/crm/crm-visit-record/select-plan'])?>?customerId="+customerId,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:540
            });
        });

        //添加接待人
        var reception_index=100;
        $("#add_reception").click(function(){
            var reception_tr="<tr>";
            reception_tr+="<td></td>";
            reception_tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox rece_sname' data-options='validType:[\"tdSame\",\"length[0,20]\"]' name='reception["+reception_index+"][CrmReception][rece_sname]'></td>";
            reception_tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox rece_position' data-options='validType:\"length[0,20]\"' name='reception["+reception_index+"][CrmReception][rece_position]'></td>";
            reception_tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox rece_mobile' data-options='validType:\"mobile\"' name='reception["+reception_index+"][CrmReception][rece_mobile]' placeholder='请输入:138xxxxxxxx'></td>";
            reception_tr+="<td><a class='delete_reception' style='margin-right: 20px;'>删除</a><a class='reset_reception'>重置</a></td>";
            reception_tr+="</tr>";
            $("#reception_tbody").append(reception_tr).find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
            reception_index++;
            $.parser.parse($("#reception_tbody").find("tr:last"));
            setMenuHeight();
        });
        $(".content").on("click",".delete_reception",function(){
            $(this).parents("tr").remove();
            $("#reception_tbody").find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
        });
        $(".content").on("click",".reset_reception",function(){
            $(this).parents("tr").find("input").val('');
        });

        //处理开始时间结束时间
//        var opts={
//            zIndex: 5,
//            format:"YYYY-MM-DD hh:mm",
//            okfun: function(obj) {
//                $(obj.elem[0]).validatebox('validate');
//            },
//            clearfun: function(elem, val) {
//                $(elem.elem[0]).validatebox('validate');
//            }
//        };
//        $("#start_time").jeDate(opts);
//        $("#end_time").jeDate(opts);
        $("#start_time").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd HH:mm",
                maxDate:"%y-%M-%d %H:%m",
                onpicked:function(){
                    $(this).validatebox('validate');
                }
            });
        });
        $("#end_time").click(function(){
            if($("#start_time").val() === ''){
                layer.alert('请先选择开始时间',{icon:2});
                return false;
            }
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd HH:mm",
                minDate:"#F{$dp.$D('start_time')}",
                maxDate:"%y-%M-%d %H:%m",
                onpicked:function(){
                    $(this).validatebox('validate');
                }
            });
        });
        $.extend($.fn.validatebox.defaults.rules,{
            timeCompare:{
                validator:function(){
                    var st=$('#start_time').val();
                    var et=$('#end_time').val();
                    if(st==='' || et===''){
                        return true;
                    }
                    var diff=Date.parse(et.replace(/-/g,'/'))-Date.parse(st.replace(/-/g,'/'));
                    var name=$(this).attr('id');
                    if(name === 'start_time'){
                        $.fn.validatebox.defaults.rules.timeCompare.message='开始时间必须小于结束时间';
                    }
                    if(name === 'end_time'){
                        $.fn.validatebox.defaults.rules.timeCompare.message='结束时间必须大于开始时间';
                    }
                    return diff > 0;
                },
                message:'时间错误'
            }
        });

        //拜访方式
        $("#visit_way").change(function(){
            if($(this).val() === '20'){
                $("#plan_div").show();
                $("#svp_code").validatebox({
                    required:true
                });
            }
            if($(this).val() === '30'){
                $("#plan_div").hide().find("input").val('');
                $("#svp_code").validatebox({
                    required:false
                });
            }
        });

        //添加陪同人
        var accompany_index=100;
        $("#add_accompany").click(function(){
            var accompany_tr="<tr>";
            accompany_tr+="<td></td>";
            accompany_tr+="<td><input class='staff_id' type='hidden' name='accompany["+accompany_index+"][CrmAccompany][acc_id]'><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox staff_code' data-options='validType:[\"tdSame\",\"staffCode\"],delay:10000000,validateOnBlur:true' data-url='<?=Url::to(['/hr/staff/get-staff-info'])?>'></td>";
            accompany_tr+="<td class='staff_name'></td>";
            accompany_tr+="<td class='job_task'></td>";
            accompany_tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox staff_mobile' data-options='validType:\"mobile\"' name='accompany["+accompany_index+"][CrmAccompany][acc_mobile]' placeholder='请输入:138xxxxxxxx'></td>";
            accompany_tr+="<td><a class='delete_accompany'>删除</a><a class='reset_accompany' style='margin-left:15px;'>重置</a></td>";
            accompany_tr+="</tr>";
            $("#accompany_tbody").append(accompany_tr).find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
            accompany_index++;
            $.parser.parse($("#accompany_tbody").find("tr:last"));
            setMenuHeight();
        });
        $(".content").on("click",".delete_accompany",function(){
            $(this).parents("tr").remove();
            $("#accompany_tbody").find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
        });
        $(".content").on("click",".reset_accompany",function(){
            $(this).parents("tr").find("input").val('');
        });

        //处理工号手机号同步
        $(document).on("blur",".staff_code",function(){
            var $staff_mobile=$(this).parents("tr").find(".staff_mobile");
            if($(this).val()===''){
                $staff_mobile.validatebox({required:false});
            }else{
                $staff_mobile.validatebox({required:true});
            }
        });
        $(document).on("blur",".staff_mobile",function(){
            var $staff_code=$(this).parents("tr").find(".staff_code");
            if($(this).val()===''){
                $staff_code.validatebox({required:false});
            }else{
                $staff_code.validatebox({required:true});
            }
        });

        //处理接待人姓名手机号同步
        $(document).on("blur",".rece_sname",function(){
            var $rece_mobile=$(this).parents("tr").find(".rece_mobile");
            if($(this).val()===''){
                $rece_mobile.validatebox({required:false});
            }else{
                $rece_mobile.validatebox({required:true});
            }
        });
        $(document).on("blur",".rece_mobile",function(){
            var $rece_sname=$(this).parents("tr").find(".rece_sname");
            if($(this).val()===''){
                $rece_sname.validatebox({required:false});
            }else{
                $rece_sname.validatebox({required:true});
            }
        });

        //管理员时追加拜访人
        function getPlanFlag(id,code){
            $.ajax({
                url:"<?=Url::to(['get-plan-flag'])?>",
                data:{"id":id,"code":code},
                dataType:"json",
                success:function(data){
                    if(data == 1){
//                        $("#visit_way_input").val(20);
//                        $("#visit_way_span").html("计划拜访");
//                        $("#plan_div").show();
//                        $("#svp_code").validatebox("enableValidation");

                        $("#visit_way").show();
                        $("#visit_way_input").remove();
                        $("#visit_way_span").remove();
                    }else{
//                        $("#visit_way_input").val(30);
//                        $("#visit_way_span").html("临时拜访");
//                        $("#plan_div").hide().find("input").val('');
//                        $("#svp_code").validatebox("disableValidation");

                        $("#visit_way").remove();
                        $("#visit_way_input").show();
                        $("#visit_way_span").show();
                        $("#plan_div").remove();
                    }
                }
            });
        }
        $("#cust_id").change(function(){
            $(".staff_code:first").html("<option value=''>请选择</option>");
            if($(this).val() == ''){
                return false;
            }
            var customer_id=$(this).val();
            <?php if(Yii::$app->user->identity->is_supper == 1){?>
                $.ajax({
                    url:"<?=Url::to(['get-customer-manager'])?>",
                    data:{"id":customer_id},
                    dataType:"json",
                    success:function(data){
                        $.each(data,function(i,n){
                            $(".staff_code:first").append("<option value='"+n.staff_code+"'>"+n.staff_code+'-'+n.staff_name+"</option>");
                        });
                        $(".staff_code:first").find("option[value='<?=$recordChild['sil_staff_code']?>']").prop("selected",true);
                    }
                });
                $(".staff_code:first").change(function(){
                    var code=$(this).val();
                    if(code == ''){
//                        $("#visit_way_span").html("");
//                        $("#plan_div").hide().find("input").val('');
//                        $("#svp_code").validatebox("disableValidation");
                        return false;
                    }
                    getPlanFlag(customer_id,code);
                });
            <?php }else{?>
                getPlanFlag(customer_id,$(".staff_code:first").val());
            <?php }?>
        }).change();
    });
</script>