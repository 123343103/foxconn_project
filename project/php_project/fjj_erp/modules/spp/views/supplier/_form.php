<?php
/**
 * User: F1677929
 * Date: 2017/9/26
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
$this->params['homeLike']=['label'=>'供应商管理'];
$this->params['breadcrumbs'][]=['label'=>'供应商列表','url'=>'index'];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?></h1>
    <?php ActiveForm::begin();?>
    <h2 class="head-second" style="cursor:pointer;text-indent:5px;">
        <i class="icon-caret-down" style="font-size:25px;vertical-align:middle;"></i><span>供应商基本信息</span>
    </h2>
    <div>
        <?php if(empty($editData)){?>
            <div class="mb-10">
                <label style="width:150px;"><span style="color:red;">*</span>供应商全称：</label>
                <input type="text" style="width:200px;" class="easyui-validatebox" data-options="required:true,validType:['unique'],delay:1000000,validateOnBlur:true" name="BsSupplier[spp_fname]" data-attr="spp_fname" data-act="<?=Url::to(['validate'])?>" maxlength="50">
                <label style="width:250px;"><span style="color:red;">*</span>供应商简称：</label>
                <input type="text" style="width:200px;" class="easyui-validatebox" data-options="required:true" name="BsSupplier[spp_sname]" placeholder="最多输入20个字" maxlength="20">
            </div>
            <div class="mb-10">
                <label style="width:150px;"><span style="color:red;">*</span>供应商集团简称：</label>
                <input type="text" style="width:200px;" class="easyui-validatebox" data-options="required:true" name="BsSupplier[spp_gsname]" placeholder="最多输入20个字" maxlength="20">
                <label style="width:250px;"><span style="color:red;">*</span>品牌：</label>
                <input type="text" style="width:200px;" class="easyui-validatebox" data-options="required:true" name="BsSupplier[spp_brand]" maxlength="20" placeholder="最多输入20个字">
            </div>
        <?php }else{?>
            <div class="mb-10">
                <label style="width:150px;vertical-align:top;">供应商全称：</label>
                <span style="width:200px;"><?=$editData['spp_fname']?></span>
                <label style="width:250px;">供应商简称：</label>
                <span style="width:200px;"><?=$editData['spp_sname']?></span>
            </div>
            <div class="mb-10">
                <label style="width:150px;">供应商集团简称：</label>
                <span style="width:200px;"><?=$editData['spp_gsname']?></span>
                <label style="width:250px;">品牌：</label>
                <span style="width:200px;"><?=$editData['spp_brand']?></span>
            </div>
        <?php }?>
        <div class="mb-10">
            <label style="width:150px;"><span style="color:red;">*</span>Commodify：</label>
            <select style="width:200px;" class="easyui-validatebox" data-options="required:true" name="BsSupplier[commodify]">
                <option value="">--请选择--</option>
                <?php foreach($addData['commodify'] as $key=>$val){?>
                    <option value="<?=$val['catg_id']?>" <?=$val['catg_id']===$editData['commodify']?'selected':''?>><?=$val['catg_name']?></option>
                <?php }?>
            </select>
            <label style="width:250px;"><span style="color:red;">*</span>新增类型：</label>
            <select style="width:200px;" class="easyui-validatebox" data-options="required:true" name="BsSupplier[add_type]">
                <option value="">--请选择--</option>
                <?php foreach($addData['addType'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['add_type']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
        </div>
        <div class="mb-10">
            <label style="width:150px;">供应商状态：</label>
            <select style="width:200px;" name="BsSupplier[isvalid]">
                <option value="1" <?=$editData['isvalid']==='1'?'selected':''?>>正常</option>
                <option value="0" <?=$editData['isvalid']==='0'?'selected':''?>>封存</option>
            </select>
            <label style="width:250px;">集团供应商：</label>
            <input type="radio" name="BsSupplier[group_spp]" value="Y" checked="checked" <?=$editData['group_spp']==='Y'?'checked':''?>>是
            <input type="radio" name="BsSupplier[group_spp]" value="N" style="margin-left:10px;" <?=$editData['group_spp']==='N'?'checked':''?>>否
        </div>
        <div class="mb-10">
            <label style="width:150px;"><span style="color:red;">*</span>供应商类型：</label>
            <select id="spp_type" style="width:200px;" class="easyui-validatebox" data-options="required:true" name="BsSupplier[spp_type]">
                <option value="">--请选择--</option>
                <?php foreach($addData['sppType'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['spp_type']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
            <input type="text" style="width:452px;<?=empty($editData['spp_type_dsc'])?"display:none;":""?>" name="BsSupplier[spp_type_dsc]" maxlength="50" placeholder="请补充说明" value="<?=$editData['spp_type_dsc']?>">
        </div>
        <div class="mb-10">
            <label style="width:150px;"><span style="color:red;">*</span>供应商来源：</label>
            <select id="spp_source" style="width:200px;" class="easyui-validatebox" data-options="required:true" name="BsSupplier[spp_source]">
                <option value="">--请选择--</option>
                <?php foreach($addData['sppSource'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['spp_source']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
            <input type="text" style="width:452px;<?=empty($editData['spp_source_dsc'])?"display:none;":""?>" name="BsSupplier[spp_source_dsc]" maxlength="50" placeholder="请补充说明" value="<?=$editData['spp_source_dsc']?>">
        </div>
        <div style="margin-bottom:3px;">
            <label style="width:150px;"><span style="color:red;">*</span>供应商地址：</label>
            <select style="width:161px;">
                <option value="">中国</option>
            </select>
            <select style="width:161px;" class="easyui-validatebox district_change" data-options="required:true">
                <option value="">省</option>
                <?php foreach($addData['province'] as $key=>$val){?>
                    <option value="<?=$val['district_id']?>" <?=$val['district_id']==$editData['edit_addr']['districtId'][1]?'selected':''?>><?=$val['district_name']?></option>
                <?php }?>
            </select>
            <select style="width:161px;" class="easyui-validatebox district_change" data-options="required:true">
                <option value="">市</option>
                <?php foreach($editData['edit_addr']['districtName'][2] as $key=>$val){?>
                    <option value="<?=$val['district_id']?>" <?=$val['district_id']==$editData['edit_addr']['districtId'][2]?'selected':''?>><?=$val['district_name']?></option>
                <?php }?>
            </select>
            <select style="width:161px;" name="BsSupplier[spp_addr_id]" class="easyui-validatebox district_change" data-options="required:true">
                <option value="">县/区</option>
                <?php foreach($editData['edit_addr']['districtName'][3] as $key=>$val){?>
                    <option value="<?=$val['district_id']?>" <?=$val['district_id']==$editData['edit_addr']['districtId'][3]?'selected':''?>><?=$val['district_name']?></option>
                <?php }?>
            </select>
        </div>
        <div class="mb-10">
            <input type="text" style="width:655px;margin-left:153px;" name="BsSupplier[spp_addr_det]" class="easyui-validatebox" data-options="required:true,validType:'maxLength[50]'" value="<?=$editData['spp_addr_det']?>" placeholder="请输入公司详细地址，例如街道名称，门牌号码，楼层等信息" maxlength="50">
        </div>
    </div>
    <h2 class="head-second" style="cursor:pointer;text-indent:5px;">
        <i class="icon-caret-down" style="font-size:25px;vertical-align:middle;"></i><span>供应商详细信息</span>
    </h2>
    <div>
        <div class="mb-10">
            <label style="width:150px;">供应商法人：</label>
            <input type="text" style="width:200px;" name="BsSupplier[spp_legal_per]" value="<?=$editData['spp_legal_per']?>" maxlength="10">
            <label style="width:250px;">供应商地位：</label>
            <select style="width:200px;" name="BsSupplier[spp_position]">
                <option value="">--请选择--</option>
                <?php foreach($addData['sppPosition'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['spp_position']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
        </div>
        <div class="mb-10">
            <label style="width:150px;">交易币别：</label>
            <select style="width:200px;" name="BsSupplier[trade_cy]">
                <?php foreach($addData['currency'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['trade_cy']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
            <label style="width:250px;">年度营业额：</label>
            <input type="text" style="width:98px;" name="BsSupplier[year_turn]" class="easyui-validatebox Onlynum" data-options="validType:'int'" value="<?=$editData['year_turn']?>" maxlength="20">
            <select style="width:98px;" name="BsSupplier[year_turn_cy]">
                <?php foreach($addData['currency'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['year_turn_cy']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
        </div>
        <div class="mb-10">
            <label style="width:150px;">预计年销售额：</label>
            <input type="text" style="width:98px;" name="BsSupplier[sale_turn]" class="easyui-validatebox Onlynum" data-options="validType:'int'" value="<?=$editData['sale_turn']?>" maxlength="20">
            <select style="width:98px;" name="BsSupplier[sale_turn_cy]">
                <?php foreach($addData['currency'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['sale_turn_cy']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
            <label style="width:250px;">预计年销售利润：</label>
            <input type="text" style="width:98px;" name="BsSupplier[sale_profit]" class="easyui-validatebox Onlynum" data-options="validType:'int'" value="<?=$editData['sale_profit']?>" maxlength="20">
            <select style="width:98px;" name="BsSupplier[sale_profit_cy]">
                <?php foreach($addData['currency'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['sale_profit_cy']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
        </div>
        <div class="mb-10">
            <label style="width:150px;">交货条件：</label>
            <select style="width:200px;" name="BsSupplier[delivery_cond]">
                <option value="">--请选择--</option>
                <?php foreach($addData['deliveryCond'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['delivery_cond']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
            <label style="width:250px;">付款条件：</label>
            <select style="width:200px;" name="BsSupplier[pay_cond]">
                <option value="">--请选择--</option>
                <?php foreach($addData['payCond'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['pay_cond']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
        </div>
        <div class="mb-10">
            <label style="width:150px;">来源类别：</label>
            <select style="width:200px;" name="BsSupplier[source_type]">
                <option value="">--请选择--</option>
                <?php foreach($addData['sourceType'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['source_type']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
        </div>
        <div class="mb-10">
            <label style="width:150px;vertical-align:top;">主营范围：</label>
            <textarea style="width:655px;height:60px;" name="BsSupplier[main_business]" placeholder="最多输入200个字" maxlength="200" class="easyui-validatebox" data-options="validType:'maxLength[200]'"><?=$editData['main_business']?></textarea>
        </div>
        <div class="mb-10">
            <label style="width:150px;vertical-align:top;">外部目标客戶：</label>
            <textarea style="width:655px;height:60px;" name="BsSupplier[target_customer]" placeholder="最多输入200个字" maxlength="200" class="easyui-validatebox" data-options="validType:'maxLength[200]'"><?=$editData['target_customer']?></textarea>
        </div>
        <div class="mb-10">
            <label style="width:150px;vertical-align:top;">客戶品质等级要求：</label>
            <textarea style="width:655px;height:60px;" name="BsSupplier[customer_quality]" placeholder="最多输入200个字" maxlength="200" class="easyui-validatebox" data-options="validType:'maxLength[200]'"><?=$editData['customer_quality']?></textarea>
        </div>
        <div class="mb-10 easyui-tabs" style="margin-left:30px;margin-right:30px;">
            <div title="供应商联系信息" style="display:none;">
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width:40px;">序号</th>
                        <th style="width:200px;">联系人</th>
                        <th style="width:200px;">联系电话</th>
                        <th style="width:200px;">邮箱</th>
                        <th style="width:200px;">传真</th>
                        <th style="width:90px;">操作</th>
                    </tr>
                    </thead>
                    <tbody id="cont_tbody"></tbody>
                </table>
                <p style="margin-left:30px;"><a id="add_cont">+添加联系人</a></p>
            </div>
            <div title="供应商主营商品" style="display:none;">
                <div style="overflow:auto;">
                    <table class="table" style="width:1150px;">
                        <thead>
                        <tr>
                            <th style="width:40px;">序号</th>
                            <th style="width:200px;">主营项目</th>
                            <th style="width:200px;">商品优势与不足</th>
                            <th style="width:200px;">销售渠道与区域</th>
                            <th style="width:100px;">年销售量(单位)</th>
                            <th style="width:100px;">市场份额(%)</th>
                            <th style="width:120px;">是否公开销售(Y/N)</th>
                            <th style="width:100px;">是否代理(Y/N)</th>
                            <th style="width:90px;">操作</th>
                        </tr>
                        </thead>
                        <tbody id="mpdt_tbody"></tbody>
                    </table>
                </div>
                <p style="margin-left:30px;"><a id="add_mpdt">+添加商品</a></p>
            </div>
            <div title="拟采购商品" style="display:none;">
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width:40px;">序号</th>
                        <th style="width:150px;">商品料号</th>
                        <th style="width:200px;">商品名称</th>
                        <th style="width:150px;">规格型号</th>
                        <th style="width:200px;">商品类型</th>
                        <th style="width:100px;">单位</th>
                        <th style="width:90px;">操作</th>
                    </tr>
                    </thead>
                    <tbody id="purpdt_tbody"></tbody>
                </table>
                <p style="margin-left:30px;"><a id="add_purpdt">+添加商品</a></p>
            </div>
        </div>
    </div>
    <h2 class="head-second" style="cursor:pointer;text-indent:5px;">
        <i class="icon-caret-down" style="font-size:25px;vertical-align:middle;"></i><span>代理事项</span>
    </h2>
    <div>
        <div class="mb-10">
            <label style="width:150px;">是否取得代理授权：</label>
            <select style="width:200px;" name="BsSupplier[agency_auth]" class="easyui-validatebox" data-options="required:true">
                <option value="Y" <?=$editData['agency_auth']=='Y'?'selected':''?>>是</option>
                <option value="N" <?=$editData['agency_auth']=='N'?'selected':''?>>否</option>
            </select>
            <label style="width:250px;">授权期限：</label>
            <input id="auth_stime" type="text" style="width:90px;" name="BsSupplier[auth_stime]" value="<?=$editData['auth_stime']?>" readonly="readonly" class="Wdate">
            <label>至</label>
            <input id="auth_etime" type="text" style="width:91px;" name="BsSupplier[auth_etime]" value="<?=$editData['auth_etime']?>" readonly="readonly" class="Wdate">
        </div>
        <div class="mb-10">
            <label style="width:150px;">代理等级：</label>
            <select style="width:200px;" name="BsSupplier[agency_level]">
                <option value="">--请选择--</option>
                <?php foreach($addData['agencyLevel'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['agency_level']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
            <label style="width:250px;">授权商品类别：</label>
            <select style="width:200px;" name="BsSupplier[auth_product]">
                <option value="">--请选择--</option>
                <?php foreach($addData['commodify'] as $key=>$val){?>
                    <option value="<?=$val['catg_id']?>" <?=$val['catg_id']===$editData['auth_product']?'selected':''?>><?=$val['catg_name']?></option>
                <?php }?>
            </select>
        </div>
        <div class="mb-10">
            <label style="width:150px;">授权区域：</label>
            <select style="width:200px;" name="BsSupplier[auth_area]">
                <option value="">--请选择--</option>
                <?php foreach($addData['authArea'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['auth_area']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
            <label style="width:250px;">授权范围：</label>
            <select style="width:200px;" name="BsSupplier[auth_scope]">
                <option value="">--请选择--</option>
                <?php foreach($addData['authScope'] as $key=>$val){?>
                    <option value="<?=$val['bsp_id']?>" <?=$val['bsp_id']===$editData['auth_scope']?'selected':''?>><?=$val['bsp_svalue']?></option>
                <?php }?>
            </select>
        </div>
        <div class="mb-10">
            <label style="width:150px;">供应商主谈人：</label>
            <input type="text" style="width:200px;" name="BsSupplier[spp_neg]" value="<?=$editData['spp_neg']?>" maxlength="10">
            <label style="width:250px;">供应商主谈人职务：</label>
            <input type="text" style="width:200px;" name="BsSupplier[spp_neg_p]" value="<?=$editData['spp_neg_p']?>" maxlength="10">
        </div>
        <div class="mb-10">
            <label style="width:150px;">富金机主谈人：</label>
            <input type="text" style="width:200px;" name="BsSupplier[fox_neg]" value="<?=$editData['fox_neg']?>" maxlength="10">
            <label style="width:250px;">富金机主谈人分机：</label>
            <input type="text" style="width:200px;" class="IsTel" name="BsSupplier[fox_neg_t]" value="<?=$editData['fox_neg_t']?>" maxlength="10" placeholder="请输入:xxx-xxxxx">
        </div>
        <div class="mb-10">
            <label style="width:150px;vertical-align:top;">新增需求说明：</label>
            <textarea style="width:657px;height:60px;" name="BsSupplier[requ_desc]" placeholder="最多输入200个字" maxlength="200" class="easyui-validatebox" data-options="validType:'maxLength[200]'"><?=$editData['requ_desc']?></textarea>
        </div>
        <div class="mb-10">
            <label style="width:150px;vertical-align:top;">优势：</label>
            <textarea style="width:657px;height:60px;" name="BsSupplier[advantage]" placeholder="最多输入200个字" maxlength="200" class="easyui-validatebox" data-options="validType:'maxLength[200]'"><?=$editData['advantage']?></textarea>
        </div>
        <div class="mb-10">
            <label style="width:150px;vertical-align:top;">商机：</label>
            <textarea style="width:657px;height:60px;" name="BsSupplier[business]" placeholder="最多输入200个字" maxlength="200" class="easyui-validatebox" data-options="validType:'maxLength[200]'"><?=$editData['business']?></textarea>
        </div>
        <div class="mb-10">
            <label style="width:150px;vertical-align:top;">未取得受理原因：</label>
            <textarea style="width:657px;height:60px;" name="BsSupplier[cause]" placeholder="最多输入200个字" maxlength="200" class="easyui-validatebox" data-options="validType:'maxLength[200]'"><?=$editData['cause']?></textarea>
        </div>
    </div>
    <div style="text-align:center;">
        <button class="button-blue-big" type="submit">保存</button>
        <button class="button-blue-big" type="submit" style="margin-left:40px;">提交</button>
        <button class="button-white-big" type="button" onclick="history.go(-1)">返回</button>
    </div>
    <?php ActiveForm::end();?>
</div>
<script>
    //拟采购商品
    var purpdtIndex=0;
    function addPurpdt(){
        var trStr="<tr>";
        trStr+="<td></td>";
        trStr+="<td><input type='hidden' name='supPurpdt["+purpdtIndex+"][SupplierPurpdt][prt_pkid]'><span></span></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td><a class='icon-remove icon-large' title='删除'></a></td>";
        trStr+="</tr>";
        $("#purpdt_tbody").append(trStr).find("tr").each(function(index){
            $(this).find("td:first").html(index+1);
        });
        purpdtIndex++;
    }
    function purpdtVal(rows){
        $.each(rows,function(i,n){
            addPurpdt();
            var $trLast=$("#purpdt_tbody").find("tr:last");
            $trLast.find("input").val(n.pkmt_id);
            $trLast.find("span").html(n.part_no);
            $trLast.find("td:eq(2)").html(n.pdt_name);
            $trLast.find("td:eq(3)").html(n.tp_spec);
            $trLast.find("td:eq(4)").html(n.category);
            $trLast.find("td:eq(5)").html(n.unit);
        })
    }
    function productSelectorCallback(rows){
        purpdtVal(rows);
    }

    //document ready
    $(function(){
        //ajax提交表单
        var btnFlag='';
        $("button[type='submit']").click(function(){
            btnFlag=$(this).text();
        });
        ajaxSubmitForm("form","",function(data){
            if (data.flag == 1) {
                if(btnFlag == '提交'){
                    var id=data.billId;
                    var url="<?=Url::to(['view'],true)?>?id="+id;
                    var type=data.billTypeId;
                    $.fancybox({
                        href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                        type:"iframe",
                        padding:0,
                        autoSize:false,
                        width:750,
                        height:480,
                        afterClose:function(){
                            location.href="<?=Url::to(['view'])?>?id="+id;
                        }
                    });
                }else{
                    layer.alert(data.msg, {
                        icon: 1,
                        end: function () {
                            if (data.url != undefined) {
                                parent.location.href = data.url;
                            }
                        }
                    });
                }
            }
            if (data.flag == 0) {
                if((typeof data.msg)=='object'){
                    layer.alert(JSON.stringify(data.msg),{icon:2});
                }else{
                    layer.alert(data.msg,{icon:2});
                }
                $("button[type='submit']").prop("disabled", false);
            }
        });

        //模块显示隐藏
        $("h2").click(function(){
            if($(this).next().is(":visible")){
                $(this).next().hide();
                $(this).find("i").removeClass().addClass("icon-caret-right");
                setMenuHeight();
            }else{
                $(this).next().show();
                $(this).find("i").removeClass().addClass("icon-caret-down");
                if($(this).find("span").html() == '供应商详细信息'){
                    $(".easyui-tabs").tabs("resize");
                }
                setMenuHeight();
            }
        });

        //供应商类型、供应商来源选择其他时增加补充说明
        $("#spp_type,#spp_source").change(function(){
            var val=$(this).find("option:selected").text();
            if(val==='其他' || val==='其它'){
                $(this).next().show().validatebox({required:true});
            }else{
                $(this).next().hide().val('').validatebox({required:false});
            }
        });

        //地址联动
        $(".district_change:not(:last)").change(function(){
            var $currSelect=$(this);
            $currSelect.nextAll("select").html("<option value=''>--请选择--</option>");
            if($currSelect.val()===''){
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['get-district'])?>",
                data:{"id":$currSelect.val()},
                dataType:"json",
                success:function(data){
                    $.each(data,function(i,n){
                        $currSelect.next().append("<option value='"+n.district_id+"'>"+n.district_name+"</option>");
                    })
                }
            });
        });

        //供应商联系人
        var contIndex=0;
        var $contTbody=$("#cont_tbody");
        var $addCont=$("#add_cont");
        $addCont.click(function(){
            var trStr="<tr>";
            trStr+="<td></td>";
            trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"tdSame\"' name='supCont["+contIndex+"][SupplierCont][name]' maxlength='10'></td>";
            trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"mobile\"' name='supCont["+contIndex+"][SupplierCont][mobile]' placeholder='请输入:138 xxxx xxxx' maxlength='20'></td>";
            trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"email\"' name='supCont["+contIndex+"][SupplierCont][email]' placeholder='请输入:xxxx@xxx.xx' maxlength='50'></td>";
            trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"fax\"' name='supCont["+contIndex+"][SupplierCont][fax]' placeholder='格式：区号-固定电话' maxlength='20'></td>";
            trStr+="<td><a class='icon-remove icon-large' title='删除'></a><a class='icon-repeat icon-large reset_cont' title='重置' style='margin-left:20px;'></a></td>";
            trStr+="</tr>";
            $contTbody.append(trStr);
            $contTbody.find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
            $.parser.parse($contTbody.find("tr:last"));
            contIndex++;
            $contTbody.find("tr:last input:eq(1)").numbervalid();
        });
        $contTbody.on("blur","input",function(){
            var $currTr=$(this).parents("tr");
            var validate=false;
            $.each($currTr.find("input"),function(i,n){
                if($(n).val() !== ''){
                    validate=true;
                }
            });
            $currTr.find("input:lt(3)").not($(this)).validatebox({required:validate});
        });

        //供应商主营商品
        var mpdtIndex=0;
        var $mpdtTbody=$("#mpdt_tbody");
        var $addMpdt=$("#add_mpdt");
        $addMpdt.click(function(){
            var trStr="<tr>";
            trStr+="<td></td>";
            trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"tdSame\"' name='supMpdt["+mpdtIndex+"][SupplierMpdt][mian_pdt]' maxlength='50'></td>";
            trStr+="<td><input type='text' style='width:100%;text-align:center;' name='supMpdt["+mpdtIndex+"][SupplierMpdt][pdt_ad]' maxlength='200' placeholder='最多输入200个字'></td>";
            trStr+="<td><input type='text' style='width:100%;text-align:center;' name='supMpdt["+mpdtIndex+"][SupplierMpdt][pdt_sca]' maxlength='200' placeholder='最多输入200个字'></td>";
            trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"int\"' name='supMpdt["+mpdtIndex+"][SupplierMpdt][sale_quan]' maxlength='20'></td>";
            trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"two_percent\"' name='supMpdt["+mpdtIndex+"][SupplierMpdt][market_share]'></td>";
            trStr+="<td><select style='width:60px;' name='supMpdt["+mpdtIndex+"][SupplierMpdt][open_sale]'><option value='Y'>Y</option><option value='N'>N</option></select></td>";
            trStr+="<td><select style='width:60px;' name='supMpdt["+mpdtIndex+"][SupplierMpdt][agency]'><option value='Y'>Y</option><option value='N'>N</option></select></td>";
            trStr+="<td><a class='icon-remove icon-large' title='删除'></a><a class='icon-repeat icon-large reset_mpdt' title='重置' style='margin-left:20px;'></a></td>";
            trStr+="</tr>";
            $mpdtTbody.append(trStr);
            $mpdtTbody.find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
            $.parser.parse($mpdtTbody.find("tr:last"));
            mpdtIndex++;
            $mpdtTbody.find("tr:last input:eq(3)").numbervalid();
        });
        $mpdtTbody.on("blur","input",function(){
            var $currTr=$(this).parents("tr");
            var validate=false;
            $.each($currTr.find("input"),function(i,n){
                if($(n).val() !== ''){
                    validate=true;
                }
            });
            $currTr.find("input:first").not($(this)).validatebox({required:validate});
        });

        //统一处理供应商联系人、供应商主营商品、拟采购商品删除重置操作
        $(document).on("click",".icon-remove",function(){
            var $tbody=$(this).parents("tbody");
            $(this).parents("tr").remove();
            $tbody.find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
        });
        $(document).on("click",".icon-repeat",function(){
            $(this).parents("tr").find("input").val('');
        });

        //拟采购商品
        $("#add_purpdt").click(function(){
            //排除已选中的商品
//            var $selectedRows=$("#purpdt_tbody").find("input");
//            var selectedId='';
//            if($selectedRows.length > 0){
//                $.each($selectedRows,function(i,n){
//                    if(n.value != ''){
//                        selectedId+=n.value+',';
//                    }
//                });
//                selectedId=selectedId.substr(0,selectedId.length-1);
//            }
//            $.fancybox({
//                width:720,
//                height:500,
//                padding:[],
//                autoSize:false,
//                type:"iframe",
//                href:"<?//=\yii\helpers\Url::to(['/ptdt/product-list/product-selector'])?>//?filters="+selectedId
//            });
            //排除已选中的商品
            var rows=$("#purpdt_tbody").find("input");
            var filters='';
            $.each(rows,function(i,n){
                filters+=n.value+',';
            });
            filters=filters.substr(0,filters.length-1);
            var url="<?=Url::to(['/spp/supplier/select-pno'])?>";
            var idField="part_no";
            $.fancybox({
                width:720,
                height:500,
                padding:[],
                autoSize:false,
                type:"iframe",
                href:"<?=\yii\helpers\Url::to(['/ptdt/product-list/product-selector'])?>?filters="+filters+"&url="+url+"&idField="+idField
            });
        });

        //新增处理
        <?php if(Yii::$app->controller->action->id === 'add'){?>
            $addCont.click();
            $addMpdt.click();
        <?php }?>

        //修改处理
        <?php if(Yii::$app->controller->action->id === 'edit'){?>
            //获取供应商联系人
            $.ajax({
                url:"<?=Url::to(['get-contacts'])?>",
                data:{"id":<?=$editData['spp_id']?>},
                dataType:"json",
                success:function(data){
                    $.each(data.rows,function(i,n){
                        $addCont.click();
                        for(var a in n){
                            if(n[a]){
                                n[a]=n[a].decode();
                            }
                        }
                        $contTbody.find("tr:last input:eq(0)").val(n.name);
                        $contTbody.find("tr:last input:eq(1)").val(n.mobile);
                        $contTbody.find("tr:last input:eq(2)").val(n.email);
                        $contTbody.find("tr:last input:eq(3)").val(n.fax);
                    })
                }
            });
            //获取供应商主营商品
            $.ajax({
                url:"<?=Url::to(['get-main-product'])?>",
                data:{"id":<?=$editData['spp_id']?>},
                dataType:"json",
                success:function(data){
                    $.each(data.rows,function(i,n){
                        $addMpdt.click();
                        for(var a in n){
                            if(n[a]){
                                n[a]=n[a].decode();
                            }
                        }
                        $mpdtTbody.find("tr:last input:eq(0)").val(n.mian_pdt);
                        $mpdtTbody.find("tr:last input:eq(1)").val(n.pdt_ad);
                        $mpdtTbody.find("tr:last input:eq(2)").val(n.pdt_sca);
                        $mpdtTbody.find("tr:last input:eq(3)").val(n.sale_quan);
                        $mpdtTbody.find("tr:last input:eq(4)").val(n.market_share);
                        $mpdtTbody.find("tr:last select:eq(0) option[value="+n.open_sale+"]").prop("selected",true);
                        $mpdtTbody.find("tr:last select:eq(1) option[value="+n.agency+"]").prop("selected",true);
                    })
                }
            });
            //获取拟采购商品
            $.ajax({
                url:"<?=Url::to(['get-purchase-product'])?>",
                data:{"id":<?=$editData['spp_id']?>},
                dataType:"json",
                success:function(data){
                    purpdtVal(data.rows);
                }
            });
        <?php }?>

        //授权期限
        $("#auth_stime").click(function(){
            WdatePicker({
                skin:"whyGreen"
            });
        });
        $("#auth_etime").click(function(){
            if($("#auth_stime").val() === ''){
                layer.alert('请先选择开始时间',{icon:2});
                return false;
            }
            WdatePicker({
                skin:"whyGreen",
                minDate:"#F{$dp.$D('auth_stime',{d:1})}"
            });
        });

        //输入控制
        $(".IsTel").telphone();
        $(".Onlynum").numbervalid();
    })
</script>