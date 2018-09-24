<?php
/**
 * User: F1677929
 * Date: 2017/3/9
 */
/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'活动统计列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?><span style="font-size:12px;color:white;float:right;margin-right:15px;"><?=!empty($countMain)?'编号：'.$countMain['actch_code']:''?></span></h1>
    <?php ActiveForm::begin(['id'=>'count_form']);?>
    <input id="actbs_id" type="hidden" name="CrmActiveCount[actbs_id]" value="<?=$activeData['actbs_id']?>">
    <div class="mb-20">
        <label class="width-100"><span class="red">*</span>活动名称</label>
        <input style="width:507px;" class="easyui-validatebox" data-options="required:true" id="actbs_name" readonly="readonly" placeholder="请点击选择活动名称" value="<?=$activeData['actbs_name']?>">
        <span><?=empty($editData)?"<a id='select_active'>选择</a>":''?></span>
    </div>
    <div class="mb-20">
        <label class="width-100">活动方式</label>
        <input class="width-200" id="activeWay" readonly="readonly" value="<?=$activeData['activeWay']?>">
        <label class="width-100">活动类型</label>
        <input class="width-200" id="acttype_name" readonly="readonly" value="<?=$activeData['acttype_name']?>">
        <label class="width-120">活动月份</label>
        <input class="width-200" id="activeMonth" readonly="readonly" value="<?=$activeData['activeMonth']?>">
    </div>
    <div class="mb-20">
        <label class="width-100">开始时间</label>
        <input class="width-200" id="actbs_start_time" readonly="readonly" value="<?=$activeData['actbs_start_time']?>">
        <label class="width-100">结束时间</label>
        <input class="width-200" id="actbs_end_time" readonly="readonly" value="<?=$activeData['actbs_end_time']?>">
        <label class="width-120">活动状态</label>
        <input class="width-200" id="activeStatus" readonly="readonly" value="<?=$activeData['activeStatus']?>">
    </div>
    <div class="mb-20">
        <label class="width-100">活动成本预算</label>
        <input class="width-200" id="actbs_cost" readonly="readonly" value="<?=$activeData['actbs_cost']?>">
        <label class="width-100">活动负责人</label>
        <input class="width-200" id="activeDutyPerson" readonly="readonly" value="<?=$activeData['activeDutyPerson']?>">
    </div>
    <div id="online_div"
    <?php if($activeData['activeWay']=='线下'){?>
        style="display:none;"
    <?php }?>
    >
        <div class="mb-20">
            <label class="width-100">活动网址</label>
            <input class="width-200" id="actbs_active_url" readonly="readonly" value="<?=$activeData['actbs_active_url']?>">
            <label class="width-100">活动PM</label>
            <input class="width-200" id="actbs_pm" readonly="readonly" value="<?=$activeData['actbs_pm']?>">
            <label class="width-120"><span class="red">*</span>统计时间</label>
            <input class="width-200 select-date-time easyui-validatebox count_time" data-options="required:true" readonly="readonly" name="CrmActiveCountChild[actc_datetime]" value="<?=empty($editData['actc_datetime'])?date('Y-m-d').' 08:00':date('Y-m-d H:i',strtotime($editData['actc_datetime']))?>">
        </div>
        <div class="mb-20">
            <label class="width-100">ROI</label>
            <input class="width-200 easyui-validatebox" data-options="validType:'length[0,5]'" name="CrmActiveCountChild[actc_roi]" value="<?=$editData['actc_roi']?>">
            <label class="width-100">当天UV数</label>
            <input class="width-200 easyui-validatebox count_uv" data-options="validType:['price','length[0,15]']" name="CrmActiveCountChild[actc_UV]" value="<?=empty($editData['actc_UV'])?'':round($editData['actc_UV'],3)?>">
            <label class="width-120"><span class="red">*</span>媒体类型</label>
            <select class="width-200 easyui-validatebox" data-options="required:true" name="CrmActiveCountChild[cc_id]">
                <option value="">请选择</option>
                <?php if(!empty($mediaType)){?>
                    <?php foreach($mediaType as $val){?>
                        <option value="<?=$val['cmt_id']?>" <?=$editData['cc_id']==$val['cmt_id']?'selected':''?>><?=$val['cmt_type']?></option>
                    <?php }?>
                <?php }?>
            </select>
        </div>
        <div class="mb-20">
            <label class="width-100">微信</label>
            <input class="width-200 easyui-validatebox" data-options="validType:'length[0,20]'" name="CrmActiveCountChild[actc_watch]" value="<?=$editData['actc_watch']?>">
            <label class="width-100">邮件</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['length[0,50]','email']" name="CrmActiveCountChild[actc_emailqty]" value="<?=$editData['actc_emailqty']?>">
            <label class="width-120">大V</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_vqty]" value="<?=$editData['actc_vqty']?>">
        </div>
        <div class="mb-20">
            <label class="width-100">PV</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_PV]" value="<?=$editData['actc_PV']?>">
            <label class="width-100">会员注册数</label>
            <input class="width-200 easyui-validatebox member_number" data-options="validType:['length[0,9]','int']" name="CrmActiveCountChild[actc_memqty]" value="<?=$editData['actc_memqty']?>">
            <label class="width-120">SEM</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_SEM]" value="<?=$editData['actc_SEM']?>">
        </div>
        <div class="mb-20">
            <label class="width-100">订单数量</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['price','length[0,15]']" name="CrmActiveCountChild[actc_ordersqty]" value="<?=empty($editData['actc_ordersqty'])?'':round($editData['actc_ordersqty'],3)?>">
            <label class="width-100">订单总额</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['length[0,15]','price']" name="CrmActiveCountChild[actc_ordcountqyt]" value="<?=empty($editData['actc_ordcountqyt'])?'':round($editData['actc_ordcountqyt'],3)?>">
            <label class="width-120">成交客户</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_custqty]" value="<?=$editData['actc_custqty']?>">
        </div>
        <div class="mb-20">
            <label class="width-100">活动成本</label>
            <input class="width-200 easyui-validatebox" data-options="validType:'length[0,20]'" name="CrmActiveCountChild[actc_cost]" value="<?=$editData['actc_cost']?>">
        </div>
    </div>
    <div id="offline_div"
        <?php if($activeData['activeWay']!='线下'){?>
            style="display:none;"
        <?php }?>
    >
        <div class="mb-10">
            <label class="width-100">活动城市</label>
            <input class="width-200" id="actbs_city" readonly="readonly" value="<?=$activeData['actbs_city']?>">
            <label class="width-100">行业类别</label>
            <input class="width-200" id="industryType" readonly="readonly" value="<?=$activeData['industryType']?>">
            <label class="width-120">主办单位</label>
            <input class="width-200" id="actbs_organizers" readonly="readonly" value="<?=$activeData['actbs_organizers']?>">
        </div>
        <div id="toggle_btn" class="mb-10 ml-40">
            <a style="font-size:14px;">点击查看更多...</a>
            <a style="font-size:14px;display:none;">收起...</a>
        </div>
        <div id="toggle_div" style="display:none;">
            <div class="mb-20">
                <label class="width-100">活动地点</label>
                <input style="width:814px;" id="activeAddress" readonly="readonly" value="<?=$activeData['activeAddress']?>">
            </div>
            <div class="mb-20">
                <label class="width-100">官方网址</label>
                <input class="width-200" id="actbs_official_url" readonly="readonly" value="<?=$activeData['actbs_official_url']?>">
                <label class="width-100">参与目的</label>
                <input class="width-200" id="joinPurpose" readonly="readonly" value="<?=$activeData['joinPurpose']?>">
            </div>
            <div class="mb-20">
                <label class="width-100 vertical-top">展品类别</label>
                <textarea id="actbs_exhibit" style="width:814px;height:70px;background-color: rgb(235, 235, 228);" disabled="disabled"><?=$activeData['actbs_exhibit']?></textarea>
            </div>
            <div class="mb-20">
                <label class="width-100 vertical-top">活动简介</label>
                <textarea id="actbs_intro" style="width:814px;height:70px;background-color: rgb(235, 235, 228);" disabled="disabled"><?=$activeData['actbs_intro']?></textarea>
            </div>
            <div class="mb-20">
                <label class="width-100">维护人员</label>
                <input class="width-200" id="maintainPerson" readonly="readonly" value="<?=$activeData['maintainPerson']?>">
                <label class="width-100">维护时间</label>
                <input class="width-200" id="actbs_maintain_time" readonly="readonly" value="<?=$activeData['actbs_maintain_time']?>">
                <label class="width-100">维护人员IP</label>
                <input class="width-200" id="actbs_maintain_ip" readonly="readonly" value="<?=$activeData['actbs_maintain_ip']?>">
            </div>
        </div>
        <div class="mb-20">
            <label class="width-100"><span class="red">*</span>统计时间</label>
            <input class="width-200 select-date-time easyui-validatebox count_time" data-options="required:true" readonly="readonly" name="CrmActiveCountChild[actc_datetime]" value="<?=empty($editData['actc_datetime'])?date('Y-m-d').' 08:00':date('Y-m-d H:i',strtotime($editData['actc_datetime']))?>">
            <label class="width-100"><span class="red">*</span>活动规模</label>
            <input class="width-200 easyui-validatebox" data-options="required:true,validType:'length[0,200]'" name="CrmActiveCountChild[actc_extent]" value="<?=$editData['actc_extent']?>">
            <label class="width-120"><span class="red">*</span>参与人次</label>
            <input class="width-200 easyui-validatebox" data-options="required:true,validType:'length[0,2]'" name="CrmActiveCountChild[actc_peopleqty]" value="<?=$editData['actc_peopleqty']?>">
        </div>
        <div class="mb-20">
            <label class="width-100">发放DW</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_dwqty]" value="<?=$editData['actc_dwqty']?>">
            <label class="width-100">手机壳</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_sjkqty]" value="<?=$editData['actc_sjkqty']?>">
            <label class="width-120">参与人员</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_partyqty]" value="<?=$editData['actc_partyqty']?>">
        </div>
        <div class="mb-20">
            <label class="width-100">名片盒</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_boxqty]" value="<?=$editData['actc_boxqty']?>">
            <label class="width-100">领带</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_ldqty]" value="<?=$editData['actc_ldqty']?>">
            <label class="width-120">差旅费用</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['price','length[0,15]']" name="CrmActiveCountChild[actc_travelqty]" value="<?=empty($editData['actc_travelqty'])?'':round($editData['actc_travelqty'],3)?>">
        </div>
        <div class="mb-20">
            <label class="width-100">总费用</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['price','length[0,9]']" name="CrmActiveCountChild[actc_countqty]" value="<?=empty($editData['actc_countqty'])?'':round($editData['actc_countqty'],3)?>">
            <label class="width-100">获得会员数</label>
            <input class="width-200 easyui-validatebox member_number" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_memqty]" value="<?=$editData['actc_memqty']?>">
            <label class="width-120">CPA</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['price','length[0,15]']" name="CrmActiveCountChild[actc_cpa]" value="<?=empty($editData['actc_cpa'])?'':round($editData['actc_cpa'],3)?>">
        </div>
        <div class="mb-20">
            <label class="width-100">收集名片数</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_artqty]" value="<?=$editData['actc_artqty']?>">
            <label class="width-100">当天微信关注数</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['int','length[0,9]']" name="CrmActiveCountChild[actc_watqyt]" value="<?=$editData['actc_watqyt']?>">
            <label class="width-120">活动前一工作日UV</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['price','length[0,15]']" name="CrmActiveCountChild[actc_bUV]" value="<?=empty($editData['actc_bUV'])?'':round($editData['actc_bUV'],3)?>">
        </div>
        <div class="mb-20">
            <label class="width-100">当天UV数</label>
            <input class="width-200 easyui-validatebox count_uv" data-options="validType:['price','length[0,15]']" name="CrmActiveCountChild[actc_UV]" value="<?=empty($editData['actc_UV'])?'':round($editData['actc_UV'],3)?>">
            <label class="width-100">UV增量</label>
            <input class="width-200 easyui-validatebox" data-options="validType:['price','length[0,15]']" name="CrmActiveCountChild[actc_UVadd]" value="<?=empty($editData['actc_UVadd'])?'':round($editData['actc_UVadd'],3)?>">
        </div>
    </div>
    <div class="mb-30">
        <label class="width-100 vertical-top">备注</label>
        <textarea style="width:834px;height:70px;" class="easyui-validatebox" data-options="validType:'length[0,200]'" name="CrmActiveCountChild[actc_remark]"><?=$editData['actc_remark']?></textarea>
    </div>
    <div class="text-center">
        <button class="button-blue-big mr-20" type="submit">确定</button>
        <button class="button-white-big" type="button" onclick="history.go(-1)">取消</button>
    </div>
    <?php ActiveForm::end();?>
</div>
<script>
    $(function(){
        //选择活动
        $("#select_active").click(function(){
            $.fancybox({
                href:"<?=Url::to(['select-active'])?>",
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:520,
                fitToView:false
            });
        });

        //ajax提交表单
        ajaxSubmitForm($("#count_form"),
            function(){
                var $hiddenDiv=$("#online_div:hidden,#offline_div:hidden");
                $hiddenDiv.find(".count_time").removeAttr('name');
                $hiddenDiv.find(".count_uv").removeAttr('name');
                $hiddenDiv.find(".member_number").removeAttr('name');
                $hiddenDiv.find(".easyui-validatebox").validatebox({novalidate:true});
                $hiddenDiv.find("input,select,textarea").val('');
                return true;
            },
            function(data){
                if (data.flag == 1) {
                    layer.alert(data.msg, {
                        icon: 1,
                        end: function () {
                            if (data.url != undefined) {
                                parent.location.href = data.url;
                            }
                        }
                    });
                }
                var $hiddenDiv=$("#online_div:hidden,#offline_div:hidden");
                if (data.flag == 0) {
                    if((typeof data.msg)=='object'){
                        layer.alert(JSON.stringify(data.msg),{icon:2});
                    }else{
                        layer.alert(data.msg,{icon:2});
                    }
                    $hiddenDiv.find(".count_time").attr("name","CrmActiveCountChild[actc_datetime]");
                    $hiddenDiv.find(".count_time").attr("name","CrmActiveCountChild[actc_UV]");
                    $hiddenDiv.find(".member_number").attr("name","CrmActiveCountChild[actc_memqty]");
                    $hiddenDiv.find(".easyui-validatebox").validatebox({novalidate:false});
                    $("button[type='submit']").prop("disabled", false);
                }
            }
        );

        //点击查看更多
        $("#toggle_btn").click(function(){
            $(this).find("a").toggle();
            $("#toggle_div").toggle();
        });
    })
</script>