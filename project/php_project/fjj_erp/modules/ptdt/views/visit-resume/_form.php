<?php
/**
 * User: F1677929
 * Date: 2016/10/7
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="content">
    <h1 class="head-first"><?=$this->title?><span style="font-size:12px;color:white;float:right;margin-right:15px;"><?=empty($resumeChild)?'':'编号：'.$resumeChild['vil_code']?></span></h1>
    <?php ActiveForm::begin(['id'=>'resume_form'])?>
    <h2 class="head-second">厂商基本信息</h2>
    <input id="firm_id" type="hidden" name="PdVisitResume[firm_id]" value="<?=empty($firmInfo)?'':$firmInfo['firm_id']?>">
    <div class="mb-20">
        <label class="width-100"><span class="red">*</span>注册公司名称</label>
        <input class="width-200 easyui-validatebox" data-options="required:true" id="firm_sname" readonly="readonly" placeholder="请点击选择厂商" value="<?=empty($firmInfo)?'':$firmInfo['firm_sname']?>">
        <span class="width-50"><?=empty($resumeChild)?"<a id='select_firm'>选择厂商</a>":''?></span>
        <label style="width:146px;">简称</label>
        <input class="width-200" id="firm_shortname" readonly="readonly" value="<?=empty($firmInfo)?'':$firmInfo['firm_shortname']?>">
    </div>
    <div class="mb-20">
        <label class="width-100">英文全称</label>
        <input class="width-200" id="firm_ename" readonly="readonly" value="<?=empty($firmInfo)?'':$firmInfo['firm_ename']?>">
        <label class="width-200">简称</label>
        <input class="width-200" id="firm_eshortname" readonly="readonly" value="<?=empty($firmInfo)?'':$firmInfo['firm_eshortname']?>">
    </div>
    <div class="mb-20">
        <label class="width-100">品牌&nbsp;&nbsp;&nbsp;中文</label>
        <input class="width-200" id="firm_brand" readonly="readonly" value="<?=empty($firmInfo)?'':$firmInfo['firm_brand']?>">
        <label class="width-200">英文</label>
        <input class="width-200" id="firm_brand_english" readonly="readonly" value="<?=empty($firmInfo)?'':$firmInfo['firm_brand_english']?>">
    </div>
    <div class="mb-20">
        <label class="width-100">厂商地址</label>
        <input type="text" id="firm_address" readonly="readonly" style="width:804px;" value="<?=empty($firmInfo)?'':$firmInfo['firmAddress']['fullAddress']?>">
    </div>
    <div class="mb-20">
        <label class="width-100">厂商来源</label>
        <input class="width-200" id="firm_source" readonly="readonly" value="<?=empty($firmInfo)?'':$firmInfo['firmSource']?>">
        <label class="width-70">厂商类型</label>
        <input class="width-200" id="firm_type" readonly="readonly" value="<?=empty($firmInfo)?'':$firmInfo['firmType']?>">
        <label class="width-120">是否为集团供应商</label>
        <input class="width-200" id="firm_issupplier" readonly="readonly" value="<?=empty($firmInfo)?'':$firmInfo['issupplier']?>">
    </div>
    <div class="mb-30">
        <label class="width-100 vertical-top">分级分类</label>
        <textarea id="firm_category_id" style="width:804px;height:50px;background-color: rgb(235, 235, 228);" readonly="readonly"><?=empty($firmInfo)?'':$firmInfo['category']?></textarea>
    </div>
    <h2 class="head-second">拜访信息</h2>
    <div class="mb-20">
        <label class="width-100"><span class="red">*</span>开始时间</label>
        <input class="width-200 select-date-time easyui-validatebox start_time_size" data-options="required:true" name="PdVisitResumeChild[vil_start_time]" readonly="readonly" value="<?=empty($resumeChild)?'':$resumeChild['vil_start_time']?>">
        <label class="width-100"><span class="red">*</span>结束时间</label>
        <input class="width-200 select-date-time easyui-validatebox end_time_size" data-options="required:true" name="PdVisitResumeChild[vil_end_time]" readonly="readonly" value="<?=empty($resumeChild)?'':$resumeChild['vil_end_time']?>">
    </div>
    <div class="mb-15">
        <input type="hidden" name="PdVisitResumeChild[vih_vis_person]" class="staff_id" value="<?=$visitPerson['staff_id']?>">
        <label class="width-100"><span class="red">*</span>拜访人</label>
        <input type="text" class="width-150 easyui-validatebox staff_code_null" data-options="required:true,validType:'staffCode',delay:10000000" data-url="<?=Url::to(['/hr/staff/get-staff-info'])?>" value="<?=$visitPerson['staff_code']?>">
        <span style="width:86px;" class="staff_name"><?=$visitPerson['staff_name']?></span>
        <label class="width-60">职位</label>
        <input type="text" class="width-200 job_task" readonly="readonly" value="<?=$visitPerson['job_task']?>">
        <label class="width-120">联系方式</label>
        <input type="text" class="width-200 staff_mobile" readonly="readonly" value="<?=$visitPerson['staff_mobile']?>">
    </div>
    <div style="margin: 0 30px 5px;">陪同人员信息</div>
    <div style="padding:0 30px 15px">
        <table class="table-small" style="width:100%;">
            <thead>
            <tr>
                <th style="width:40px;">序号</th>
                <th class="width-200">工号</th>
                <th class="width-200">姓名</th>
                <th class="width-200">职位</th>
                <th class="width-200">联系电话</th>
                <th><a id="add_accompany_person" style="cursor:pointer;">+添加</a></th>
            </tr>
            </thead>
            <tbody id="accompany_person_tbody">
            <?php if(!empty($accompanyPerson)){?>
                <?php foreach($accompanyPerson as $key=>$val){?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><input type="text" class="text-center easyui-validatebox staff_code_null" data-options="validType:['tdSame','staffCode'],delay:10000000" data-url="<?=Url::to(['/hr/staff/get-staff-info'])?>" style="width:200px;" name="accompanyArr[<?=$key?>][PdAccompany][staff_code]" value="<?=$val['staff_code']?>"></td>
                        <td class="staff_name"><?=$val['staffInfo']['staffName']?></td>
                        <td class="job_task"><?=$val['staffInfo']['staffTitle']?></td>
                        <td class="staff_mobile"><?=$val['staffInfo']['staffMobile']?></td>
                        <td><a onclick="resetAccompanyPerson(this)" style="cursor:pointer;">重置</a><?=$key==0?'':"&nbsp;<a onclick='deleteAccompanyPerson(this)'>删除</a>"?></td>
                    </tr>
                <?php }?>
            <?php }else{?>
                <tr>
                    <td>1</td>
                    <td><input type="text" class="text-center easyui-validatebox staff_code_null" data-options="validType:['tdSame','staffCode'],delay:10000000" data-url="<?=Url::to(['/hr/staff/get-staff-info'])?>" style="width:200px;" name="accompanyArr[0][PdAccompany][staff_code]" placeholder="请点击输入工号"></td>
                    <td class="staff_name"></td>
                    <td class="job_task"></td>
                    <td class="staff_mobile"></td>
                    <td><a onclick="resetAccompanyPerson(this)">重置</a></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div style="margin: 0 30px 5px;">厂商接待人信息</div>
    <div style="padding:0 30px 20px">
        <table class="table-small" style="width:100%;">
            <thead>
            <tr>
                <th style="width:40px;">序号</th>
                <th class="width-250">厂商接待人员</th>
                <th class="width-250">职位</th>
                <th class="width-250">联系电话</th>
                <th><a id="add_reception_person" style="cursor:pointer;">+添加</a></th>
            </tr>
            </thead>
            <tbody id="reception_person_tbody">
            <?php if(!empty($receptionPerson)){?>
                <?php foreach($receptionPerson as $key=>$val){?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><input type="text" class="text-center easyui-validatebox" data-options="validType:['length[0,20]','tdSame']<?=$key==0?',required:true':''?>" style="width:250px;" name="receptionArr[<?=$key?>][PdReception][rece_sname]" value="<?=$val['rece_sname']?>"></td>
                        <td><input type="text" class="text-center easyui-validatebox" data-options="validType:'length[0,20]'<?=$key==0?',required:true':''?>" style="width:250px;" name="receptionArr[<?=$key?>][PdReception][rece_position]" value="<?=$val['rece_position']?>"></td>
                        <td><input type="text" class="text-center easyui-validatebox" data-options="validType:'mobile'<?=$key==0?',required:true':''?>" style="width:250px;" name="receptionArr[<?=$key?>][PdReception][rece_mobile]" value="<?=$val['rece_mobile']?>"></td>
                        <td><a onclick="resetReceptionPerson(this)">重置</a><?=$key==0?'':"&nbsp;<a onclick='deleteReceptionPerson(this)'>删除</a>"?></td>
                    </tr>
                <?php }?>
            <?php }else{?>
                <tr>
                    <td>1</td>
                    <td><input type="text" class="text-center easyui-validatebox" data-options="validType:['length[0,20]','tdSame'],required:true" style="width:250px;" name="receptionArr[0][PdReception][rece_sname]" placeholder="必填"></td>
                    <td><input type="text" class="text-center easyui-validatebox" data-options="validType:'length[0,20]',required:true" style="width:250px;" name="receptionArr[0][PdReception][rece_position]" placeholder="必填"></td>
                    <td><input type="text" class="text-center easyui-validatebox" data-options="validType:'mobile',required:true" style="width:250px;" name="receptionArr[0][PdReception][rece_mobile]" placeholder="必填"></td>
                    <td><a onclick="resetReceptionPerson(this)">重置</a></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div class="mb-20">
        <label class="width-100 vertical-top"><span class="red">*</span>过程描述</label>
        <textarea class="easyui-validatebox" data-options="required:true,validType:'length[0,255]'" name="PdVisitResumeChild[vil_process_Descript]" style="width:800px;height:50px;"><?=empty($resumeChild)?'':$resumeChild['vil_process_Descript']?></textarea>
    </div>
    <div class="mb-20">
        <label class="width-100 vertical-top"><span class="red">*</span>访谈结论</label>
        <textarea class="easyui-validatebox" data-options="required:true,validType:'length[0,255]'" name="PdVisitResumeChild[vil_interview_Conclus]" style="width:800px;height:50px;"><?=empty($resumeChild)?'':$resumeChild['vil_interview_Conclus']?></textarea>
    </div>
    <div class="mb-20">
        <label class="width-100 vertical-top"><span class="red">*</span>追踪事项</label>
        <textarea class="easyui-validatebox" data-options="required:true,validType:'length[0,255]'" name="PdVisitResumeChild[vil_track_Item]" style="width: 800px;height:50px;"><?=empty($resumeChild)?'':$resumeChild['vil_track_Item']?></textarea>
    </div>
    <div class="mb-20">
        <label class="width-100 vertical-top"><span class="red">*</span>下次访谈注意事项</label>
        <textarea class="easyui-validatebox" data-options="required:true,validType:'length[0,255]'" name="PdVisitResumeChild[vil_next_Interview_Notice]" style="width:800px;height:50px;"><?=empty($resumeChild)?'':$resumeChild['vil_next_Interview_Notice']?></textarea>
    </div>
    <div class="mb-20">
        <label class="width-100 vertical-top">其它</label>
        <textarea class="easyui-validatebox" data-options="validType:'length[0,255]'" name="PdVisitResumeChild[vil_other]" style="width:800px;height:50px;"><?=empty($resumeChild)?'':$resumeChild['vil_other']?></textarea>
    </div>
    <div class="mb-20">
        <label class="width-100 vertical-top">备注</label>
        <textarea class="easyui-validatebox" data-options="validType:'length[0,255]'" name="PdVisitResumeChild[vil_remark]" style="width:800px;height:50px;"><?=empty($resumeChild)?'':$resumeChild['vil_remark']?></textarea>
    </div>
    <div class="mb-20">
        <input type="hidden" id="plan_id" name="PdVisitResumeChild[visit_planID]" value="<?=empty($visitPlan)?'':$visitPlan['pvp_planID']?>">
        <label class="width-100">关联拜访计划</label>
        <input type="text" id="plan_code" class="width-200" readonly="readonly" placeholder="请点击选择关联拜访计划" value="<?=empty($visitPlan)?'':$visitPlan['pvp_plancode']?>">
        <a id="select_plan">选择关联拜访计划</a>
    </div>
    <div class="text-center">
        <button class="button-blue-big mr-20" type="submit">确定</button>
        <button class="button-white-big" type="button" onclick="history.go(-1)">返回</button>
    </div>
    <?php ActiveForm::end();?>
</div>
<script>
    //删除接待人
    function deleteReceptionPerson(obj){
        $(obj).parents("tr").remove();
        $("#reception_person_tbody").find("tr").each(function(index){
            $(this).find("td:first").text(index+1);
        })
    }

    //重置接待人
    function resetReceptionPerson(obj){
        $(obj).parents("tr").find("input").val("");
    }

    //删除陪同人
    function deleteAccompanyPerson(obj){
        $(obj).parents("tr").remove();
        $("#accompany_person_tbody").find("tr").each(function(index){
            $(this).find("td:first").text(index+1);
        });
    }

    //重置陪同人
    function resetAccompanyPerson(obj){
        $(obj).parents("tr").find("input").val("");
        $(obj).parents("tr").find("td:gt(1):lt(3)").text("");
    }

    $(function(){
        //ajax提交表单
        ajaxSubmitForm("#resume_form");

        //选择厂商
        $("#select_firm").click(function(){
            $.fancybox({
                href:"<?=Url::to(['select-firm'])?>",
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:570
            });
        });

        //关联拜访计划
        $("#select_plan").click(function(){
            var firmId=$("#firm_id").val();
            if(firmId==''){
                layer.alert("请选择厂商！",{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['select-plan'])?>?firmId="+firmId,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:530
            })
        });

        //添加接待人
        var i = 100;
        $("#add_reception_person").click(function(){
            var receptionTr = "<tr>";
            receptionTr += "<td></td>";
            receptionTr += "<td>";
            receptionTr += "<input type='text' class='text-center easyui-validatebox' data-options='validType:[\"length[2,6]\",\"tdSame\"]' style='width:250px;' name='receptionArr["+i+"][PdReception][rece_sname]' placeholder='请点击填写'>";
            receptionTr += "</td>";
            receptionTr += "<td>";
            receptionTr += "<input type='text' class='text-center easyui-validatebox' data-options='validType:\"length[2,6]\"' style='width:250px;' name='receptionArr["+i+"][PdReception][rece_position]' placeholder='请点击填写'>";
            receptionTr += "</td>";
            receptionTr += "<td>";
            receptionTr += "<input type='text' class='text-center easyui-validatebox' data-options='validType:\"mobile\"' style='width:250px;' name='receptionArr["+i+"][PdReception][rece_mobile]' placeholder='请点击填写'>";
            receptionTr += "</td>";
            receptionTr += "<td>";
            receptionTr += "<a onclick='resetReceptionPerson(this)'>重置</a>&nbsp;";
            receptionTr += "<a onclick='deleteReceptionPerson(this)'>删除</a>";
            receptionTr += "</td>";
            receptionTr += "</tr>";
            $("#reception_person_tbody").append(receptionTr);
            $.parser.parse($("#reception_person_tbody").find("tr:last"));//easyui解析
            $("#reception_person_tbody").find("tr").each(function(index){
                $(this).find("td:first").text(index+1);
            });
            i++;
        });

        //添加陪同人
        var j = 100;
        $("#add_accompany_person").click(function(){
            var accompanyTr = "<tr>";
            accompanyTr += "<td></td>";
            accompanyTr += "<td>";
            accompanyTr += "<input type='text' class='text-center easyui-validatebox staff_code_null' data-options='validType:[\"tdSame\",\"staffCode\"],delay:10000000,validateOnBlur:true' data-url='<?=Url::to(['/hr/staff/get-staff-info'])?>' style='width:200px;' name='accompanyArr["+j+"][PdAccompany][staff_code]' placeholder='请点击输入工号'>";
            accompanyTr += "</td>";
            accompanyTr += "<td class='staff_name'></td>";
            accompanyTr += "<td class='job_task'></td>";
            accompanyTr += "<td class='staff_mobile'></td>";
            accompanyTr += "<td>";
            accompanyTr += "<a onclick='resetAccompanyPerson(this)'>重置</a>&nbsp;";
            accompanyTr += "<a onclick='deleteAccompanyPerson(this)'>删除</a>";
            accompanyTr += "</td>";
            accompanyTr += "</tr>";
            $("#accompany_person_tbody").append(accompanyTr);
            $.parser.parse($("#accompany_person_tbody").find("tr:last"));//easyui解析
            $("#accompany_person_tbody").find("tr").each(function(index){
                $(this).find("td:first").text(index+1);
            });
            j++;
        });
    })
</script>