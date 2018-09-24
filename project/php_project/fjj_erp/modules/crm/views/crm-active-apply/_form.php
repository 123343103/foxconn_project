<?php
/**
 * User: F1677929
 * Date: 2017/2/10
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'报名列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?><span style="font-size:12px;color:white;float:right;margin-right:15px;"><?=!empty($editData)?'编号：'.$editData['acth_code']:''?></span></h1>
    <?php ActiveForm::begin(['id'=>'active_apply_form']);?>
    <h2 class="head-second">
        <i class="icon-caret-down icon" style="vertical-align:middle;font-size:25px;"></i>
        <i class="icon-caret-right icon" style="vertical-align:middle;font-size:25px;display:none;"></i>
        报名信息
    </h2>
    <div>
        <div class="mb-20">
            <label class="width-100"><span class="red">*</span>活动类型</label>
            <select id="active_type" class="width-200 easyui-validatebox" data-options="required:true" name="CrmActiveApply[acttype_id]">
                <option value="">请选择</option>
                <?php foreach($addEditData['activeType'] as $val){?>
                    <option value="<?=$val['acttype_id']?>" <?=$val['acttype_id']==$editData['acttype_id']?'selected':''?>><?=$val['acttype_name']?></option>
                <?php }?>
            </select>
            <label class="width-200"><span class="red">*</span>活动名称</label>
            <select id="active_name" class="width-200 easyui-validatebox" data-options="required:true" name="CrmActiveApply[actbs_id]">
                <option value="">请选择</option>
                <?php foreach($activeName as $val){?>
                    <option value="<?=$val['actbs_id']?>" <?=$val['actbs_id']==$editData['actbs_id']?'selected':''?>><?=$val['actbs_name']?></option>
                <?php }?>
            </select>
        </div>
        <div class="mb-20">
            <label class="width-100">开始时间</label>
            <input id="active_start_time" class="width-200" type="text" readonly="readonly" value="<?=$editData['actbs_start_time']?>">
            <label class="width-200">结束时间</label>
            <input id="active_end_time" class="width-200" type="text" readonly="readonly" value="<?=$editData['actbs_end_time']?>">
        </div>
        <div class="mb-20">
            <label class="width-100"><span class="red">*</span>姓名</label>
            <input type="text" class="width-200 easyui-validatebox" required="required" data-options="validType:'length[0,20]'" name="CrmActiveApply[acth_name]" value="<?=$editData['acth_name']?>">
            <label class="width-200"><span class="red">*</span>职位</label>
            <input type="text" class="width-200 easyui-validatebox" required="required" data-options="validType:'length[0,20]'" name="CrmActiveApply[acth_position]" value="<?=$editData['acth_position']?>">
        </div>
        <div class="mb-20">
            <label class="width-100"><span class="red">*</span>手机号码</label>
            <input type="text" class="width-200 easyui-validatebox" required="required" data-options="validType:'mobile'" name="CrmActiveApply[acth_phone]" value="<?=$editData['acth_phone']?>">
            <label class="width-200"><span class="red">*</span>邮箱</label>
            <input type="text" class="width-200 easyui-validatebox" required="required" data-options="validType:'email'" name="CrmActiveApply[acth_email]" value="<?=$editData['acth_email']?>">
        </div>
        <div class="mb-20">
            <label class="width-100"><span class="red">*</span>参会身份</label>
            <select class="width-200 easyui-validatebox" required="required" name="CrmActiveApply[acth_identity]">
                <option value="">请选择</option>
                <?php foreach($addEditData['joinIdentity'] as $key=>$val){?>
                    <option value="<?=$key?>" <?=$key==$editData['acth_identity']?'selected':''?>><?=$val?></option>
                <?php }?>
            </select>
            <label class="width-200">用餐信息</label>
            <select class="width-200" name="CrmActiveApply[acth_ismeal]">
                <option value="0" <?=$editData['acth_ismeal']=='0'?'selected':''?>>否</option>
                <option value="1" <?=$editData['acth_ismeal']=='1'?'selected':''?>>是</option>
            </select>
        </div>
        <div class="mb-20">
            <label class="width-100">是否需缴费</label>
            <select id="is_need_pay" class="width-200" name="CrmActiveApply[acth_ispay]">
                <option value="0" <?=$editData['acth_ispay']=='0'?'selected':''?>>否</option>
                <option value="1" <?=$editData['acth_ispay']=='1'?'selected':''?>>是</option>
            </select>
            <label class="width-200">需缴费金额</label>
            <input id="need_pay_money" type="text" class="width-200" name="CrmActiveApply[acth_payamount]" value="<?=$editData['acth_payamount']?>">
        </div>
        <div class="mb-20">
            <label class="width-100">是否开票</label>
            <select id="is_bill" class="width-200" name="CrmActiveApply[acth_isbill]">
                <option value="0" <?=$editData['acth_isbill']=='0'?'selected':''?>>否</option>
                <option value="1" <?=$editData['acth_isbill']=='1'?'selected':''?>>是</option>
            </select>
        </div>
        <div class="mb-30">
            <label class="width-100 vertical-top">备注</label>
            <textarea style="width:607px;height:50px;" class="easyui-validatebox" data-options="validType:'length[0,255]'" name="CrmActiveApply[acth_remark]"><?=$editData['acth_remark']?></textarea>
        </div>
    </div>
    <div id="customer_div">
        <h2 class="head-second">
            <i class="icon-caret-down icon" style="vertical-align:middle;font-size:25px;"></i>
            <i class="icon-caret-right icon" style="vertical-align:middle;font-size:25px;display:none;"></i>
            公司基本信息
        </h2>
        <div>
            <div class="mb-20">
                <input id="cust_id" type="hidden" name="CrmCustomerInfo[cust_id]" value="<?=$editData['cust_id']?>">
                <label class="width-100"><span class="red">*</span>公司名称</label>
                <input id="cust_sname" type="text" class="width-200 easyui-validatebox" data-options="required:true,validType:'length[0,100]'" name="CrmCustomerInfo[cust_sname]" value="<?=$editData['cust_sname']?>">
                <label class="width-200">公司简称</label>
                <input id="cust_shortname" type="text" class="width-200 easyui-validatebox" data-options="validType:'length[0,100]'" name="CrmCustomerInfo[cust_shortname]" value="<?=$editData['cust_shortname']?>">
            </div>
            <div class="mb-20">
                <label class="width-100">公司电话</label>
                <input id="cust_tel1" type="text" class="width-200 easyui-validatebox" data-options="validType:'telphone'" name="CrmCustomerInfo[cust_tel1]" value="<?=$editData['cust_tel1']?>" placeholder="格式:07557668666">
                <label class="width-200">邮编</label>
                <input id="member_compzipcode" type="text" class="width-200 easyui-validatebox" data-options="validType:'postcode'" name="CrmCustomerInfo[member_compzipcode]" value="<?=$editData['member_compzipcode']?>">
            </div>
            <div>
                <label class="width-100">详细地址</label>
                <select style="width:149px;" class="district_change">
                    <option value="">请选择</option>
                    <?php foreach($addEditData['country'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtData['districtId'][0]?'selected':''?>><?=$val['district_name']?></option>
                    <?php }?>
                </select>
                <select style="width:149px;" class="district_change">
                    <option value="">请选择</option>
                    <?php if(!empty($districtData)){?>
                        <?php foreach($districtData['districtName'][1] as $val){?>
                            <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtData['districtId'][1]?'selected':''?>><?=$val['district_name']?></option>
                        <?php }?>
                    <?php }?>
                </select>
                <select style="width:149px;" class="district_change">
                    <option value="">请选择</option>
                    <?php if(!empty($districtData)){?>
                        <?php foreach($districtData['districtName'][2] as $val){?>
                            <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtData['districtId'][2]?'selected':''?>><?=$val['district_name']?></option>
                        <?php }?>
                    <?php }?>
                </select>
                <select style="width:149px;" class="district_change" name="CrmCustomerInfo[cust_district_2]">
                    <option value="">请选择</option>
                    <?php if(!empty($districtData)){?>
                        <?php foreach($districtData['districtName'][3] as $val){?>
                            <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtData['districtId'][3]?'selected':''?>><?=$val['district_name']?></option>
                        <?php }?>
                    <?php }?>
                </select>
            </div>
            <div class="mb-30" style="margin-left:103px;">
                <input id="cust_adress" type="text" style="width:607px;" name="CrmCustomerInfo[cust_adress]" class="easyui-validatebox" data-options="validType:'length[0,120]'" value="<?=$editData['cust_adress']?>">
            </div>
        </div>
        <h2 class="head-second">
            <i class="icon-caret-down icon" style="vertical-align:middle;font-size:25px;display:none;"></i>
            <i class="icon-caret-right icon" style="vertical-align:middle;font-size:25px;"></i>
            公司详细信息
        </h2>
        <div style="display:none;">
            <div class="mb-20">
                <label class="width-100">法人代表</label>
                <input id="cust_inchargeperson" type="text" class="width-200 easyui-validatebox" data-options="validType:'length[0,20]'" name="CrmCustomerInfo[cust_inchargeperson]" value="<?=$editData['cust_inchargeperson']?>">
                <label class="width-200">注册时间</label>
                <input id="cust_regdate" type="text" class="width-200 select-date" readonly="readonly" name="CrmCustomerInfo[cust_regdate]" value="<?=empty($editData['cust_regdate'])?date('Y-m-d'):$editData['cust_regdate']?>">
            </div>
            <div class="mb-20">
                <label class="width-100">注册货币</label>
                <select id="member_regcurr" class="width-200" name="CrmCustomerInfo[member_regcurr]">
                    <option value="">请选择</option>
                    <?php foreach($addEditData['registerCurrency'] as $key=>$val){?>
                        <option value="<?=$key?>" <?=$key==$editData['member_regcurr']?'selected':''?>><?=$val?></option>
                    <?php }?>
                </select>
                <label class="width-200">注册资金</label>
                <input id="cust_regfunds" type="text" class="width-200 easyui-validatebox" data-options="" name="CrmCustomerInfo[cust_regfunds]" value="<?=$editData['cust_regfunds']?>">
            </div>
            <div class="mb-20">
                <label class="width-100">公司类型</label>
                <select id="cust_type" class="width-200" name="CrmCustomerInfo[cust_type]">
                    <option value="">请选择</option>
                    <?php foreach($addEditData['companyType'] as $key=>$val){?>
                        <option value="<?=$key?>" <?=$key==$editData['cust_type']?'selected':''?>><?=$val?></option>
                    <?php }?>
                </select>
                <label class="width-200">客户来源</label>
                <select id="member_source" class="width-200" name="CrmCustomerInfo[member_source]">
                    <option value="">请选择</option>
                    <?php foreach($addEditData['customerSource'] as $key=>$val){?>
                        <option value="<?=$key?>" <?=$key==$editData['member_source']?'selected':''?>><?=$val?></option>
                    <?php }?>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-100">经营模式</label>
                <select id="cust_businesstype" class="width-200" name="CrmCustomerInfo[cust_businesstype]">
                    <option value="">请选择</option>
                    <?php foreach($addEditData['manageModel'] as $key=>$val){?>
                        <option value="<?=$key?>" <?=$key==$editData['cust_businesstype']?'selected':''?>><?=$val?></option>
                    <?php }?>
                </select>
                <label class="width-200">交易币种</label>
                <select id="member_curr" class="width-200" name="CrmCustomerInfo[member_curr]">
                    <option value="">请选择</option>
                    <?php foreach($addEditData['TradingCurrency'] as $key=>$val){?>
                        <option value="<?=$key?>" <?=$key==$editData['member_curr']?'selected':''?>><?=$val?></option>
                    <?php }?>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-100">年营业额</label>
                <input id="member_compsum" type="text" class="width-200 easyui-validatebox" data-options="validType:'six_decimal'" name="CrmCustomerInfo[member_compsum]" value="<?=$editData['member_compsum']?>">RMB
                <label style="width:173px;">年采购额</label>
                <input id="cust_pruchaseqty" type="text" class="width-200 easyui-validatebox" data-options="validType:'six_decimal'" name="CrmCustomerInfo[cust_pruchaseqty]" value="<?=$editData['cust_pruchaseqty']?>">RMB
            </div>
            <div class="mb-20">
                <label class="width-100">员工人数</label>
                <input id="cust_personqty" type="text" class="width-200 easyui-validatebox" data-options="validType:'length[0,20]'" name="CrmCustomerInfo[cust_personqty]" value="<?=$editData['cust_personqty']?>">
                <label class="width-200">发票需求</label>
                <input id="member_compreq" type="text" class="width-200 easyui-validatebox" data-options="validType:'length[0,200]'" name="CrmCustomerInfo[member_compreq]" value="<?=$editData['member_compreq']?>">
            </div>
            <div class="mb-20">
                <label class="width-100">潜在需求</label>
                <select id="member_reqflag" class="width-200" name="CrmCustomerInfo[member_reqflag]">
                    <option value="">请选择</option>
                    <?php foreach($addEditData['potentialRequired'] as $key=>$val){?>
                        <option value="<?=$key?>" <?=$key==$editData['member_reqflag']?'selected':''?>><?=$val?></option>
                    <?php }?>
                </select>
                <label class="width-200">需求类目</label>
                <select id="member_reqitemclass" class="width-200" name="CrmCustomerInfo[member_reqitemclass]">
                    <option value="">请选择</option>
                    <?php foreach($addEditData['requiredType'] as $val){?>
                        <option value="<?=$val['category_id']?>" <?=$val['category_id']==$editData['member_reqitemclass']?'selected':''?>><?=$val['category_sname']?></option>
                    <?php }?>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-100">需求类别</label>
                <input id="member_reqdesription" type="text" class="width-200 easyui-validatebox" data-options="validType:'length[0,200]'" name="CrmCustomerInfo[member_reqdesription]" value="<?=$editData['member_reqdesription']?>">
                <label class="width-200">主要市场</label>
                <input id="member_marketing" type="text" class="width-200 easyui-validatebox" data-options="validType:'length[0,200]'" name="CrmCustomerInfo[member_marketing]" value="<?=$editData['member_marketing']?>">
            </div>
            <div class="mb-20">
                <label class="width-100">主要客户</label>
                <input id="member_compcust" type="text" class="width-200 easyui-validatebox" data-options="validType:'length[0,200]'" name="CrmCustomerInfo[member_compcust]" value="<?=$editData['member_compcust']?>">
                <label class="width-200">主页</label>
                <input id="member_compwebside" type="text" class="width-200 easyui-validatebox" data-options="validType:'url'" name="CrmCustomerInfo[member_compwebside]" value="<?=$editData['member_compwebside']?>">
            </div>
            <div class="mb-20">
                <label class="width-100 vertical-top">经营范围</label>
                <textarea id="member_businessarea" style="width:607px;height:50px;" name="CrmCustomerInfo[member_businessarea]" class="easyui-validType" data-options="validType:'length[0,200]'"><?=$editData['member_businessarea']?></textarea>
            </div>
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
        ajaxSubmitForm($("#active_apply_form"));
        //活动类型
        $("#active_type").change(function(){
            $("#active_name").html("<option value=''>请选择</option>");
            $("#active_start_time").val('');
            $("#active_end_time").val('');
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

        //活动名称
        $("#active_name").change(function(){
            $("#active_start_time").val('');
            $("#active_end_time").val('');
            var nameId=$(this).val();
            if(nameId==''){
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['get-active-time'])?>",
                data:{"nameId":nameId},
                dataType:"json",
                success:function(data){
                    $("#active_start_time").val(data.actbs_start_time.substr(0,16));
                    $("#active_end_time").val(data.actbs_end_time.substr(0,16));
                }
            })
        });

        //是否需缴费
        $("#is_need_pay").change(function(){
            if($(this).val()=="0"){
                $("#need_pay_money").validatebox({novalidate:true,disabled:true}).val('');
                $("#need_pay_money").css("background-color","rgb(235, 235, 228)");
                $("#is_bill").prop("disabled",true).find("option:first").prop("selected",true);
                $("#is_bill").css("background-color","rgb(235, 235, 228)");
            }
            if($(this).val()=="1"){
                $("#need_pay_money").validatebox({novalidate:false,disabled:false,validType:['length[0,20]','int']});
                $("#need_pay_money").css("background-color","");
                $("#is_bill").prop("disabled",false);
                $("#is_bill").css("background-color","");
            }
        }).change();

        //新增时若客户存在则失去焦点带出客户信息
        $("#cust_sname").blur(function(){
            $("#customer_div").find("input:not(#cust_sname,#cust_id),select,textarea").val("");
            $(".level:not(:first)").html("<option value=''>请选择</option>");
            var name=$(this).val();
            if(name==''){
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['get-customer-info'])?>",
                data:{"name":name},
                success:function(data){
                    data=$.trim(data);
                    if(data==''){
                        return false;
                    }
                    data=JSON.parse(data);
                    $("#cust_id").val(data.customerInfo.cust_id);
                    $("#cust_shortname").val(data.customerInfo.cust_shortname.decode());
                    $("#cust_tel1").val(data.customerInfo.cust_tel1);
                    if(data.districtData!=null){
                        $(".district_change:first").html("<option value=''>请选择</option>");
                        $.each(data.districtData.districtName,function(m,n){
                            $.each(n,function(i,j){
                                $(".district_change:eq("+m+")").append("<option value='"+j.district_id+"'>"+j.district_name+"</option>");
                            });
                            $(".district_change:eq("+m+") option[value="+data.districtData.districtId[m]+"]").prop("selected",true);
                        });
                    }
                    $("#cust_adress").val(data.customerInfo.cust_adress.decode());
                    $("#member_businessarea").val(data.customerInfo.member_businessarea.decode());
                    $("#cust_businesstype").find("option[value='"+data.customerInfo.cust_businesstype+"']").prop("selected",true);
                    $("#member_reqdesription").val(data.customerInfo.member_reqdesription.decode());
                    $("#member_compzipcode").val(data.customerInfo.member_compzipcode);
                    $("#cust_inchargeperson").val(data.customerInfo.cust_inchargeperson.decode());
                    $("#cust_type").find("option[value='"+data.customerInfo.cust_type+"']").prop("selected",true);
                    $("#cust_regdate").val(data.customerInfo.cust_regdate);
                    $("#cust_regfunds").val(data.customerInfo.cust_regfunds);
                    $("#member_regcurr").find("option[value='"+data.customerInfo.member_regcurr+"']").prop("selected",true);
                    $("#cust_personqty").val(data.customerInfo.cust_personqty);
                    $("#cust_pruchaseqty").val(data.customerInfo.cust_pruchaseqty);
                    $("#member_compsum").val(data.customerInfo.member_compsum);
                    $("#member_marketing").val(data.customerInfo.member_marketing.decode());
                    $("#member_compcust").val(data.customerInfo.member_compcust.decode());
                    $("#member_source").find("option[value='"+data.customerInfo.member_source+"']").prop("selected",true);
                    $("#member_compreq").val(data.customerInfo.member_compreq.decode());
                    $("#member_compwebside").val(data.customerInfo.member_compwebside);
                    $("#member_reqflag").find("option[value='"+data.customerInfo.member_reqflag+"']").prop("selected",true);
                    $("#member_reqitemclass").find("option[value='"+data.customerInfo.member_reqitemclass+"']").prop("selected",true);
                    $("#member_curr").find("option[value='"+data.customerInfo.member_curr+"']").prop("selected",true);
                }
            });
        });

        //显示隐藏模块
        $(".head-second").hover(
            function(){$(this).css("cursor","pointer")},
            function(){$(this).css("cursor","default")}
        ).click(function(){
            $(this).next().toggle();
            $(this).children(".icon").toggle();
            setMenuHeight();
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