<?php
/**
 * 客户拜访计划表单
 * User: F3859386
 * Date: 2016/12/20
 */
/* @var $this yii\web\view */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '拜访计划管理', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <h1 class="head-first"><?= $this->title ?><span
            style="font-size:12px;color:white;float:right;margin-right:15px;"><?= empty($model['svp_code']) ? '' : '编号：' . $model['svp_code'] ?></span>
    </h1>
    <?php ActiveForm::begin([
        'id' => 'record_form',
        'enableAjaxValidation' => true,
    ]); ?>
    <h2 class="head-second">
        客户基本信息
        <?php if(Yii::$app->controller->action->id === 'create' || Yii::$app->controller->action->id === 'plan-create'){?>
            <a id="select_customer" style="margin-left:20px;">选择客户</a>
        <?php }?>
    </h2>
    <input id="cust_id" type="hidden" name="CrmVisitPlan[cust_id]" value="<?=$model['customerInfo']['customerId']?>">
    <div id="customer_info"
        <?php if(empty($model['customerInfo']['customerId'])){?>
            style="display:none;"
        <?php }?>
    >
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户名称<label>：</label></td>
                <td id="cust_sname" class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerName']?></td>
                <td class="no-border vertical-center label-align" width="10%">客户类型<label>：</label></td>
                <td id="customerType" class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerType']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户经理<label>：</label></td>
                <td id="customerManager" class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerManager']?></td>
                <td class="no-border vertical-center label-align" width="10%">营销区域<label>：</label></td>
                <td id="csarea_name" class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerSaleArea']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">联系人<label>：</label></td>
                <td id="cust_contacts" class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerContacts']?></td>
                <td class="no-border vertical-center label-align" width="10%">联系电话<label>：</label></td>
                <td id="cust_tel2" class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerTel2']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">公司地址<label>：</label></td>
                <td id="customer_address" class="no-border vertical-center qvalue-align" colspan="3" width="80%"><?=$model['customerInfo']['customerDistrict']?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second">拜访计划</h2>
<!--    实施中只能修改结束时间-->
    <?php if(Yii::$app->controller->action->id=='update' && $model['svp_status']==40){?>
        <div class="mb-10">
            <input type="hidden" name="CrmVisitPlan[svp_staff_code]" value="<?=$model['svp_staff_code']?>" class="staff_code">
            <label style="width:99px;">拜访人：</label>
            <span style="width:200px;"><?=$model['svp_staff_code'].'('.$model['visitPerson'].')'?></span>
            <label style="width:238px;">拜访类型：</label>
            <span style="width:200px;"><?=$model['visitType']?></span>
        </div>
        <div class="mb-10">
            <label style="width:99px;">开始时间：</label>
            <input id="start_time" type="hidden" value="<?=$model['start']?>">
            <span style="width:200px;"><?=$model['start']?></span>
            <label style="width:238px;"><span class="red">*</span>结束时间：</label>
            <input id="end_time" style="width:200px;" class="Wdate easyui-validatebox" data-options="required:true,validType:'timeCompare'" name="CrmVisitPlan[end]" readonly="readonly" value="<?=$model['customerInfo']['customerId']?date('Y-m-d H:i',strtotime($model['end'])):''?>">
        </div>
        <div class="mb-10" style="margin-left:30px;margin-right:30px;">
            <p style="margin-bottom:2px;font-size:13px;font-weight:900;">陪同人员信息：</p>
            <table class="table">
                <thead>
                <tr>
                    <th style="width:4%;">序号</th>
                    <th style="width:24%;">工号</th>
                    <th style="width:24%;">姓名</th>
                    <th style="width:24%;">职位</th>
                    <th style="width:24%;">联系电话</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($accompanyData)){?>
                    <?php foreach($accompanyData as $key=>$val){?>
                        <tr>
                            <td><?=$key+1?></td>
                            <td><?=$val['staff_code']?></td>
                            <td><?=$val['staff_name']?></td>
                            <td><?=$val['title_name']?></td>
                            <td><?=$val['acc_mobile']?></td>
                        </tr>
                    <?php }?>
                <?php }?>
                </tbody>
            </table>
        </div>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="10%">计划内容<label>：</label></td>
                    <td class="no-border vertical-center qvalue-align" width="80%"><?=$model['svp_content']?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="10%">备注<label>：</label></td>
                    <td class="no-border vertical-center qvalue-align" width="80%"><?=$model['svp_remark']?></td>
                </tr>
            </table>
        </div>
    <?php }else{?>
    <div class="mb-10">
        <?php if(Yii::$app->user->identity->is_supper == 1){?>
            <label style="width:99px;"><span class="red">*</span>拜访人：</label>
            <select style="width:200px;" name="CrmVisitPlan[svp_staff_code]" class="easyui-validatebox staff_code" data-options="required:true">
            </select>
        <?php }else{?>
            <label style="width:99px;">拜访人：</label>
            <input type="hidden" name="CrmVisitPlan[svp_staff_code]" value="<?=$staff['staff_code']?>" class="staff_code">
            <span style="width:200px;"><?=$staff['staff_code'].'('.$staff['staff_name'].')'?></span>
        <?php }?>
        <label style="width:238px;"><span class="red">*</span>拜访类型：</label>
        <select name="CrmVisitPlan[svp_type]" style="width:200px;" class="easyui-validatebox " data-options="required:true">
            <option value="">请选择</option>
            <?php foreach ($downList['visitType'] as $key => $val) { ?>
                <option value="<?= $key ?>" <?= $model['svp_type'] == $key ? "selected" : null ?>><?= $val ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label style="width:99px;"><span class="red">*</span>开始时间：</label>
        <input id="start_time" style="width:200px;" class="Wdate easyui-validatebox" data-options="required:true,validType:'timeCompare'" name="CrmVisitPlan[start]" readonly="readonly" value="<?=$model['start']?date('Y-m-d H:i',strtotime($model['start'])):''?>">
        <label style="width:238px;"><span class="red">*</span>结束时间：</label>
        <input id="end_time" style="width:200px;" class="Wdate easyui-validatebox" data-options="required:true,validType:'timeCompare'" name="CrmVisitPlan[end]" readonly="readonly" value="<?=$model['end']?date('Y-m-d H:i',strtotime($model['end'])):''?>">
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
    <div class="mb-10">
        <label style="width:99px;" class="vertical-top"><span class="red">*</span>计划内容：</label>
        <textarea name="CrmVisitPlan[svp_content]" style="width:792px;height:70px;font-size:12px;" class="easyui-validatebox" data-options="required:true" maxlength="200" placeholder="最多输入200个字"><?=$model['svp_content']?></textarea>
    </div>
    <div class="mb-10">
        <label style="width:99px;" class="vertical-top">备注：</label>
        <textarea name="CrmVisitPlan[svp_remark]" maxlength="200" style="width:792px;height:70px;font-size:12px;" placeholder="最多输入200个字"><?=$model['svp_remark']?></textarea>
    </div>
    <?php }?>
    <div class="mb-10 text-center">
        <button class="button-blue-big" type="submit">保存</button>
        <button class="button-white-big" type="button" onclick="history.go(-1);">返回</button>
    </div>
    <?php ActiveForm::end()?>
</div>
<script>
    $(function () {
        //ajax提交表单
        ajaxSubmitForm("form",function(){
            if($("#cust_id").val() === ''){
                layer.alert('请选择客户',{icon:2});
                return false;
            }
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
        $("#select_customer").click(function () {
            $.fancybox({
                href: "<?=Url::to(['/crm/crm-visit-record/select-customer'])?>",
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 800,
                height: 520
            });
        });

        //处理开始时间结束时间
//        $("#start_time").jeDate({
//            zIndex: 5,
//            format:"YYYY-MM-DD hh:mm",
//            minDate: $.nowDate({DD:0}).substring(0,10)+' 00:00:00',
//            maxDate: '2099-12-30 23:59:00',
//            okfun: function(obj) {
//                $(obj.elem[0]).validatebox('validate');
//            },
//            clearfun: function(elem, val) {
//                $(elem.elem[0]).validatebox('validate');
//            }
//        });
//        $("#end_time").jeDate({
//            zIndex: 5,
//            format:"YYYY-MM-DD hh:mm",
//            minDate: $.nowDate({DD:0}).substring(0,10)+' 00:00:00',
//            maxDate: '2099-12-30 23:59:00',
//            okfun: function(obj) {
//                $(obj.elem[0]).validatebox('validate');
//            },
//            clearfun: function(elem, val) {
//                $(elem.elem[0]).validatebox('validate');
//            }
//        });
        $("#start_time").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd HH:mm",
                minDate:"%y-%M-%d %H:%m",
                maxDate:"#F{$dp.$D('end_time')}",
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

        //管理员时追加拜访人
        <?php if(Yii::$app->user->identity->is_supper == 1){?>
            <?php if(Yii::$app->controller->action->id=='create' || (Yii::$app->controller->action->id=='update' && $model['svp_status']==10)){?>
                $("#cust_id").change(function(){
                    $(".staff_code:first").html("<option value=''>请选择</option>");
                    if($(this).val() == ''){
                        return false;
                    }
                    $.ajax({
                        url:"<?=Url::to(['/crm/crm-visit-record/get-customer-manager'])?>",
                        data:{"id":$(this).val()},
                        dataType:"json",
                        success:function(data){
                            $.each(data,function(i,n){
                                $(".staff_code:first").append("<option value='"+n.staff_code+"'>"+n.staff_code+'-'+n.staff_name+"</option>");
                            });
                            $(".staff_code:first").find("option[value='<?=$model['svp_staff_code']?>']").prop("selected",true);
                        }
                    });
                }).change();
            <?php }?>
        <?php }?>
    })
</script>