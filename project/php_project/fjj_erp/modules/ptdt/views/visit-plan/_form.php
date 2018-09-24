<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/9/12
 * Time: 11:27
 */
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$plan_time = explode("-",$model['plan_time']);
?>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
        <h2 class="head-second">
            计划信息
        </h2>
        <div class="mb-20">
            <div class="inline-block field-pdvisitplan-pvp_type required">
                <label for="pdvisitplan-pvp_type" class="width-70 ml-60">计划类别</label>
                <select name="PdVisitPlan[pvp_type]" class="width-200 easyui-validatebox " data-options='required:"true"' id="pvp_type">
                    <option value="">请选择</option>
                    <?php foreach ($downList['planType'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($model['pvp_type'])&&$model['pvp_type']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="mb-20">
            <input type="hidden" id="firm_id" class="width-200 easyui-validatebox" data-options='required:"true"' name="PdVisitPlan[firm_id]" value="<?php echo $model['firm_id'] ?>">
            <div style="display:inline-block;">
                <label class="width-130">计划拜访厂商</label>
                <input type="text" readonly="readonly" id="firm_sname" class="width-200 easyui-validatebox" data-options='required:"true"' value="<?php echo $model['firm']['firm_sname'] ?>">
            </div>
            <span class="width-50 ml-10"><a href="<?=Url::to(['/ptdt/visit-plan/select-com']) ?>" id="select-com" class="fancybox.ajax">选择厂商</a></span>

            <div style="display:inline-block;">
                <label style="width:107px;">厂商简称</label>
                <input type="text" readonly="readonly" id="firm_shortname" class="width-200" value="<?php echo $model['firm']['firm_shortname'] ?>">
            </div>
        </div>
        <div class="mb-20">
            <div style="display:inline-block;">
                <label class="width-130">厂商地址</label>
                <input type="text" readonly="readonly" id="firm_compaddress" class="width-200" value="<?php echo $model['firm']['firmAddress']['fullAddress'] ?>">
            </div>
            <div style="display:inline-block;">
                <label class="width-170">厂商类型</label>
                <input type="text" readonly="readonly" id="firm_type" class="width-200" value="<?= $model['firm']['firmType'] ?>">
            </div>
        </div>
        <div class="mb-30">
            <div class="mb-20">
                <div class="inline-block field-pdvisitplan-plan_date required">
                    <label for="pdvisitplan-plan_date" class="width-70 ml-60">计划日期</label>
                    <input type="text" name="PdVisitPlan[plan_date]" class="width-200 select-date easyui-validatebox" id="pdvisitplan-plan_date" data-options='required:"true"' readonly="readonly" value="<?php echo $model['plan_date']; ?>">
                </div>
                <div class="inline-block field-pdvisitplan-plan_starttime required">
                    <label for="pdvisitplan-plan_starttime" class="width-70 ml-30">计划时间</label>
                    <input type="text" name="PdVisitPlan[plan_starttime]" class="width-90 select-time easyui-validatebox" id="plan_starttime" data-options='required:"true"' value="<?= $plan_time[0] ?>" readonly="readonly">
                </div>            至
                <div class="inline-block field-pdvisitplan-plan_endtime required">
                    <input type="text" name="PdVisitPlan[plan_endtime]" class="width-90 select-time easyui-validatebox" id="plan_endtime" data-options='required:"true"' value="<?= $plan_time[0] ?>" readonly="readonly">
                </div>
                <div class="inline-block field-pdvisitplan-plan_place required">
                    <label for="pdvisitplan-plan_place" class="width-70 ml-30">计划地点</label>
                    <input type="text" name="PdVisitPlan[plan_place]" class="width-200 easyui-validatebox" id="pdvisitplan-plan_place" data-options='required:"true"' value="<?php echo $model['plan_place']; ?>" maxlength="100">
                </div>
            </div>
            <div class="mb-20">
                <div class="inline-block field-pdvisitplan-pvp_contact_man required">
                    <label for="pdvisitplan-pvp_contact_man" class="width-80 ml-50">厂商联系人</label>
                    <input type="text" name="PdVisitPlan[pvp_contact_man]" class="width-200 easyui-validatebox" id="pdvisitplan-pvp_contact_man" data-options='required:"true"' value="<?php echo $model['pvp_contact_man']; ?>" maxlength="20" validType="length[0,10]" invalidMessage="不能超多10个字符">
                </div>
                <div class="inline-block field-pdvisitplan-pvp_contact_position required">
                    <label for="pdvisitplan-pvp_contact_position" class="width-50 ml-50">职位</label>
                    <input type="text" name="PdVisitPlan[pvp_contact_position]" class="width-200 easyui-validatebox" id="pdvisitplan-pvp_contact_position" data-options='required:"true"' value="<?php echo $model['pvp_contact_position']; ?>" maxlength="10">
                </div>
                <div class="inline-block field-pdvisitplan-pvp_contact_mobile required">
                    <label for="pdvisitplan-pvp_contact_mobile" class="width-70 ml-30">联系手机</label>
                    <input type="text" name="PdVisitPlan[pvp_contact_mobile]" class="width-200 easyui-validatebox" id="pdvisitplan-pvp_contact_mobile" data-options="required:'true',validType:'mobile'" value="<?php echo $model['pvp_contact_mobile']; ?>" maxlength="14">
                </div>
            </div>
            <div class="mb-20">
                <?php if(empty($model['staffPerson'])){ ?>
                <div class="required" style="display:inline-block;">
                    <label class="width-130">拜访人(商品经理人)</label>
                    <input type="hidden" value="<?php echo yii::$app->user->identity->staff->staff_id; ?>" name="PdVisitPlan[pvp_staff_id]" class="staff_id">
                    <input type="text" class="width-120 easyui-validatebox staff_code" data-options="required:true" value="<?php echo yii::$app->user->identity->staff->staff_code; ?>" onblur="setStaffInfo(this)">
                    <span class="width-80 staff_name"><?php echo yii::$app->user->identity->staff->staff_name; ?></span>
                </div>
                <div class="required" style="display:inline-block;">
                    <label class="width-100">职位</label>
                    <input type="text" value="<?php echo yii::$app->user->identity->staff->job_task ?>" class="width-200 job_task" readonly="readonly">
                </div>
                <div class="required" style="display:inline-block;">
                    <label class="width-100">联系电话</label>
                    <input type="text" readonly="readonly" class="width-200 staff_mobile" value="<?php echo yii::$app->user->identity->staff->staff_mobile?>">
                </div>
                <?php }else{ ?>
                    <div class="required" style="display:inline-block;">
                        <input type="hidden" value="<?= $model['visitPerson']['id'] ?>" name="PdVisitPlan[pvp_staff_id]" class="staff_id">
                        <label class="width-130">拜访人(商品经理人)</label>
                        <input type="text" class="width-120 staff_code" value="<?= $model['visitPerson']['code']?>" onblur="setStaffInfo(this)">
                        <span class="width-80 staff_name"><?= $model['visitPerson']['name'] ?></span>
                    </div>
                    <div class="required" style="display:inline-block;">
                        <label class="width-100">职位</label>
                        <input type="text" value="<?= $model['visitPerson']['job'] ?>" class="width-200 job_task" readonly="readonly">
                    </div>
                    <div class="required" style="display:inline-block;">
                        <label class="width-100">联系电话</label>
                        <input type="text" readonly="readonly" class="width-200 staff_mobile" value="<?= $model['visitPerson']['mobile'] ?>">
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="mb-20" style="width:99%;">
            <p class="ml-50 width-100 float-left">陪同人员信息</p>
            <p style="line-height: 25px;" class="float-right mr-50 "><button class="button-blue text-center" onclick="vacc_add()" type="button">+ 添&nbsp;加</button></p>
            <div class="space-10 clear"></div>
            <table class="table-small">
                <thead>
                <tr>
                    <th class="width-200">工号</th>
                    <th class="width-200">姓名</th>
                    <th class="width-200">职位</th>
                    <th class="width-200">联系电话</th>
                    <th class="width-200">操作</th>
                </tr>
                </thead>
                <tbody id="vacc_body">
                <tr>
                    <td>
                        <input type="text" name="vacc[]" placeholder="请输入工号" class="width-150 text-center easyui-validatebox" data-options='validType:["accompanySame"],delay:10000000,validateOnBlur:true' onblur="job_num(this)">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><a onclick="reset(this)">重置</a>  <a onclick="vacc_del(this)">删除</a></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="space-40"></div>
        <div class="mb-30">
            <div class="mb-20">
                <div class="inline-block field-pdvisitplan-purpose required">
                    <label for="pdvisitplan-purpose" class="width-50 ml-50">目的</label>
                    <select name="PdVisitPlan[purpose]" class="width-200 easyui-validatebox" id="pdvisitplan-purpose" data-options='required:"true"'>
                        <option value="">请选择</option>
                        <?php foreach ($downList['visitPurpose'] as $key => $val) {?>
                            <option value="<?=$val['bsp_id'] ?>" <?= isset($model['purpose_num'])&&$model['purpose_num']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div style="margin-left:103px;" class="mb-20">
                <div class="inline-block">
                    <input type="text" name="PdVisitPlan[purpose_write]" class="width-750 easyui-validatebox" id="pdvisitplan-purpose_write" data-options='required:"true"' value="<?php echo $model['purpose_write']; ?>" maxlength="100">
                </div>
            </div>
            <div class="mb-20">

                <div class="inline-block field-pdvisitplan-note">
                    <label for="pdvisitplan-note" class="width-100 vertical-top">备注</label>
                    <textarea rows="3" name="PdVisitPlan[note]" class="width-750" id="pdvisitplan-note" maxlength="200"><?php echo $model['note']; ?></textarea>
                </div>
            </div>
        </div>
        <div class="width-300 margin-auto">
            <button type="submit" class="button-blue-big" id="save-button">提交</button>
            <button onclick="history.go(-1)" type="button" class="button-white-big ml-20">返回</button>
        </div>
        <div class="space-40"></div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function setStaffInfo(obj) {
        var url = "<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>";
        staffInfo(obj, url);
    }
    //陪同人员信息
    function job_num(obj){
        var td = $(obj).parents("tr").find("td");
        var code = $(obj).val();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"code": code},
            url: "<?= Url::to(['/ptdt/visit-plan/get-visit-manager'])?>",
            success:function(data){
                if(data==0){
                    layer.alert("未找到该工号！",{icon:2,time:5000});
                    td.eq(1).text("");
                    td.eq(2).text("");
                    td.eq(3).text("");
                }else{
                    $(obj).val($(obj).val().toUpperCase());
                    td.eq(1).text(data.staff_name);
                    td.eq(2).text(data.job_task);
                    td.eq(3).text(data.staff_mobile);
                }
            }
        })
    }
    function vacc_del(obj){
        var tr= $("#vacc_body tr").length;
        if(tr>1){
            $(obj).parents("tr").remove();
        }
    }
    function reset(obj){
        var td = $(obj).parents("tr").find("td");
        $(obj).parents("tr").find(td.eq(1)).text("");
        $(obj).parents("tr").find(td.eq(2)).text("");
        $(obj).parents("tr").find(td.eq(3)).text("");
        $(obj).parents("tr").find("input").val("");
    };
    function vacc_add(){
        $("#vacc_body").append(
            '<tr>' +
            '<td>' +
//            '<input onblur="job_num(this)" type="text" class="width-150  no-border text-center" placeholder="请输入工号" name="vacc[]">' +
                "<input type='text' class='text-center width-150 easyui-validatebox' data-options='validType:[\"accompanySame\"],delay:10000000,validateOnBlur:true' placeholder='请点击输入工号' onblur='job_num(this)' name='vacc[]'>"+
            '</td>' +
            '<td>' +
            '</td>' +
            '<td>' +
            '</td>' +
            '<td>' +
            '</td>' +
            '<td><a onclick="reset(this)">重置</a> <a onclick="vacc_del(this)">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse($("#vacc_body").find("tr:last"));//easyui解析

    }
    $(document).ready(function(){
        ajaxSubmitForm($("#add-form"));     //ajax 提交

        var $purpose = $("#pdvisitplan-purpose");
        var $purposeWrite = $("#pdvisitplan-purpose_write");
        <?php if(!empty($model['purpose'])){ ?>
        if($purpose.val() === "100014"){
            $purposeWrite.validatebox(
                {
                    required: false
                }
            )
        }
        <?php } ?>
        $purpose.on("change",function(){
            if($purpose.val() === "100014"){
                $purposeWrite.validatebox(
                    {
                        required: false
                    }
                )
            }else{
                $purposeWrite.validatebox(
                    {
                        required: true
                    }
                )
            }
        });
//验证加载陪同人
        $('#select-com').fancybox(      //選擇廠商彈框
            {
                padding : [],
                fitToView	: false,
                width		: 800,
                height		: 570,
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none',
                type : 'iframe'
            }
        )
    });
<?php  if( \Yii::$app->controller->action->id == "edit") {?>
    <?php if(count($model['accompany']) != 0){?>
        $("#vacc_body tr").remove();
        <?php foreach($model['accompany'] as $item){ ?>
            var editTdStr = '<tr>';
            editTdStr +='<td>' + '<input type="text" onblur="job_num(this)" class="width-200 text-center easyui-validatebox" data-options="validType:[\'accompanySame\'],delay:10000000,validateOnBlur:true" name="vacc[]" value="<?php echo $item['staff_code']?>">' + '</td>';
            editTdStr +='<td>' + '<span class="width-200"><?php echo $item['staff_name']?></span>' + '</td>';
            editTdStr +='<td>' + '<span class="width-200"><?php echo $item['job_task']?></span>' + '</td>';
            editTdStr +='<td>' + '<span class="width-200"><?php echo $item['staff_mobile']?></span>' + '</td>';
            editTdStr +='<td>' + '<a onclick="reset(this)">重置</a>  <a onclick="vacc_del(this)">删除</a>' + '</td>';
            $('#vacc_body').append(editTdStr);
        <?php } ?>
    <?php } ?>
<?php } ?>
<?php  if( \Yii::$app->controller->action->id == "add") {?>
    <?php if($id != null){ ?>
    $("#firm_id").val("<?php echo $firmMessage['firm_id'] ?>");
    $("#firm_sname").val("<?php echo $firmMessage['firm_sname'] ?>");
    $("#firm_shortname").val("<?php echo $firmMessage['firm_shortname'] ?>");
    $("#firm_compaddress").val("<?php echo $firmMessage['firmAddress']['fullAddress'] ?>");
    $("#firm_type").val("<?php echo $firmMessage['firmType'] ?>");
    <?php } ?>
<?php } ?>
    //开始时间结束时间比较大小-郭文聪
    $("#plan_starttime,#plan_endtime").change(function(){
        var startTime = $("#plan_starttime").val();
        var endTime = $("#plan_endtime").val();
        var startTimeH = startTime.substring(0,2);
        var startTimeM = startTime.substring(3,5);
        var endTimeH = endTime.substring(0,2);
        var endTimeM = endTime.substring(3,5);
        if(startTime==''||endTime==''){
            return false;
        }
        var sum = (endTimeH*3600+endTimeM*60)-(startTimeH*3600+startTimeM*60);
        $("#plan_endtime,#plan_starttime").validatebox({validType:'startEndSize['+sum+']'});
    });
</script>



