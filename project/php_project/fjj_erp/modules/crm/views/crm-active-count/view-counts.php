<?php
/**
 * User: F1677929
 * Date: 2017/6/30
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
$this->title='查看活动统计';
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'活动统计列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?><span style="font-size:12px;color:white;float:right;margin-right:15px;"><?='编号：'.$data['activeInfo']['actch_code']?></span></h1>
    <div class="mb-10">
        <?=Menu::isAction('/crm/crm-active-count/index')?"<button type='button' class='button-blue' style='width:80px;' onclick='location.href=\"".Url::to(['index'])."\"'>切换列表</button>":''?>
        <button type="button" class="button-blue" onclick="history.go(-1)">返回</button>
    </div>
    <h1 class="head-second">活动基本信息</h1>
    <div class="mb-20">
        <span class="width-80 ml-30">活动名称：</span>
        <span class="width-200" style="vertical-align:middle;"><?=$data['activeInfo']['actbs_name']?></span>
        <span class="width-100">活动类型：</span>
        <span class="width-200"><?=$data['activeInfo']['activeType']?></span>
        <span class="width-120">活动方式：</span>
        <span class="width-200"><?=$data['activeInfo']['activeWay']?></span>
    </div>
    <div class="mb-20">
        <span class="width-80 ml-30">开始时间：</span>
        <span class="width-200"><?=empty($data['activeInfo']['actbs_start_time'])?'':substr($data['activeInfo']['actbs_start_time'],0,16)?></span>
        <span class="width-100">结束时间：</span>
        <span class="width-200"><?=empty($data['activeInfo']['actbs_start_time'])?'':substr($data['activeInfo']['actbs_end_time'],0,16)?></span>
        <span class="width-120">活动月份：</span>
        <span class="width-200"><?=$data['activeInfo']['activeMonth']?></span>
    </div>
    <div class="mb-20">
        <span class="width-80 ml-30">活动状态：</span>
        <span class="width-200"><?=$data['activeInfo']['activeStatus']?></span>
        <span class="width-100">活动成本预算：</span>
        <span class="width-200"><?=$data['activeInfo']['actbs_cost']?></span>
        <span class="width-120">活动负责人：</span>
        <span class="width-200"><?=$data['activeInfo']['activeDutyPerson']?></span>
    </div>
    <?php if($data['activeInfo']['activeWay']=='线上'){?>
        <div class="mb-30">
            <span class="width-80 ml-30">活动网址：</span>
            <span class="width-200"><?=$data['activeInfo']['actbs_active_url']?></span>
            <span class="width-100">活动PM：</span>
            <span class="width-200"><?=$data['activeInfo']['actbs_pm']?></span>
        </div>
    <?php }?>
    <?php if($data['activeInfo']['activeWay']=='线下'){?>
        <div class="mb-10">
            <span class="width-80 ml-30">活动地点：</span>
            <span class="width-200" style="vertical-align:middle;"><?=$data['activeInfo']['activeAddress']?></span>
            <span class="width-100">活动城市：</span>
            <span class="width-200"><?=$data['activeInfo']['actbs_city']?></span>
            <span class="width-120">主办单位：</span>
            <span class="width-200"><?=$data['activeInfo']['actbs_organizers']?></span>
        </div>
        <div id="toggle_btn" class="mb-10 ml-30">
            <a style="font-size:14px;">点击查看更多...</a>
            <a style="font-size:14px;display:none;">收起...</a>
        </div>
        <div id="toggle_div" style="display:none;">
            <div class="mb-20">
                <span class="width-80 ml-30">官方网址：</span>
                <span class="width-200"><?=$data['activeInfo']['actbs_official_url']?></span>
                <span class="width-100">参与目的：</span>
                <span class="width-200"><?=$data['activeInfo']['joinPurpose']?></span>
                <span class="width-120">行业类别：</span>
                <span class="width-200"><?=$data['activeInfo']['industryType']?></span>
            </div>
            <div class="mb-20">
                <span class="width-80 ml-30">展品类别：</span>
                <span style="width:870px;vertical-align:middle;"><?=$data['activeInfo']['actbs_exhibit']?></span>
            </div>
            <div class="mb-20">
                <span class="width-80 ml-30">活动简介：</span>
                <span style="width:870px;vertical-align:middle;"><?=$data['activeInfo']['actbs_intro']?></span>
            </div>
            <div class="mb-30">
                <span class="width-80 ml-30">维护人员：</span>
                <span class="width-200"><?=$data['activeInfo']['maintainPerson']?></span>
                <span class="width-100">维护时间：</span>
                <span class="width-200"><?=empty($data['activeInfo']['actbs_maintain_time'])?'':substr($data['activeInfo']['actbs_maintain_time'],0,16)?></span>
                <span class="width-120">维护人员IP：</span>
                <span class="width-200"><?=$data['activeInfo']['actbs_maintain_ip']?></span>
            </div>
        </div>
    <?php }?>
    <h1 class="head-second">活动统计信息</h1>
    <?php if(!empty($data['countInfo'])){?>
    <?php foreach($data['countInfo'] as $key=>$val){?>
            <div class="easyui-panel"
                 data-options="collapsible:true<?=$key==0?"":",collapsed:true"?>,onCollapse:function(){setMenuHeight()},onExpand:function(){setMenuHeight()}"
                 title="<?="<span style='color:black;'>统计时间：".substr($val['actc_datetime'],0,16)."</span>"?>&nbsp;&nbsp;&nbsp;&nbsp;<?=Menu::isAction('/crm/crm-active-count/edit')?"<a href='".Url::to(['edit','childId'=>$val['actc_iid']])."'>修改</a>":""?>&nbsp;&nbsp;&nbsp;&nbsp;<?=Menu::isAction('/crm/crm-active-count/delete')?"<a onclick='deleteCount(".$val['actc_iid'].")'>删除</a>":""?>">
                <div class="space-10"></div>
                <?php if($data['activeInfo']['activeWay']=='线上'){?>
                    <div class="mb-20">
                        <span class="width-80 ml-30">统计时间：</span>
                        <span class="width-200"><?=empty($val['actc_datetime'])?'':substr($val['actc_datetime'],0,16)?></span>
                        <span class="width-100">SEM：</span>
                        <span class="width-200"><?=$val['actc_SEM']?></span>
                        <span class="width-120">媒体：</span>
                        <span class="width-200"><?=$val['cmt_type']?></span>
                    </div>
                    <div class="mb-20">
                        <span class="width-80 ml-30">大V：</span>
                        <span class="width-200"><?=$val['actc_vqty']?></span>
                        <span class="width-100">微信：</span>
                        <span class="width-200"><?=$val['actc_watch']?></span>
                        <span class="width-120">邮件：</span>
                        <span class="width-200"><?=$val['actc_emailqty']?></span>
                    </div>
                    <div class="mb-20">
                        <span class="width-80 ml-30">UV：</span>
                        <span class="width-200"><?=$val['actc_UV']?></span>
                        <span class="width-100">PV：</span>
                        <span class="width-200"><?=$val['actc_PV']?></span>
                        <span class="width-120">会员注册数：</span>
                        <span class="width-200"><?=$val['actc_memqty']?></span>
                    </div>
                    <div class="mb-20">
                        <span class="width-80 ml-30">成交客户：</span>
                        <span class="width-200"><?=$val['actc_custqty']?></span>
                        <span class="width-100">订单数量：</span>
                        <span class="width-200"><?=$val['actc_ordersqty']?></span>
                        <span class="width-120">订单总额：</span>
                        <span class="width-200"><?=$val['actc_ordcountqyt']?></span>
                    </div>
                    <div class="mb-20">
                        <span class="width-80 ml-30">活动成本：</span>
                        <span class="width-200"><?=$val['actc_cost']?></span>
                        <span class="width-100">ROI：</span>
                        <span class="width-200"><?=$val['actc_roi']?></span>
                    </div>
                    <div class="mb-20">
                        <span class="width-80 ml-30">备注：</span>
                        <span style="width:800px;vertical-align:middle;"><?=$val['actc_remark']?></span>
                    </div>
                <?php }?>
                <?php if($data['activeInfo']['activeWay']=='线下'){?>
                    <div class="mb-20">
                        <span class="width-80 ml-30">统计时间：</span>
                        <span class="width-200"><?=empty($val['actc_datetime'])?'':substr($val['actc_datetime'],0,16)?></span>
                        <span class="width-100">活动规模：</span>
                        <span class="width-200"><?=$val['actc_extent']?></span>
                        <span class="width-120">参与人次：</span>
                        <span class="width-200"><?=$val['actc_peopleqty']?></span>
                    </div>
                    <div class="mb-20">
                        <span class="width-80 ml-30">发放DW：</span>
                        <span class="width-200"><?=$val['actc_dwqty']?></span>
                        <span class="width-100">手机壳：</span>
                        <span class="width-200"><?=$val['actc_sjkqty']?></span>
                        <span class="width-120">参与人员：</span>
                        <span class="width-200"><?=$val['actc_partyqty']?></span>
                    </div>
                    <div class="mb-20">
                        <span class="width-80 ml-30">名片盒：</span>
                        <span class="width-200"><?=$val['actc_boxqty']?></span>
                        <span class="width-100">领带：</span>
                        <span class="width-200"><?=$val['actc_ldqty']?></span>
                        <span class="width-120">差旅费用：</span>
                        <span class="width-200"><?=$val['actc_travelqty']?></span>
                    </div>
                    <div class="mb-20">
                        <span class="width-80 ml-30">总费用：</span>
                        <span class="width-200"><?=$val['actc_countqty']?></span>
                        <span class="width-100">获得会员数：</span>
                        <span class="width-200"><?=$val['actc_memqty']?></span>
                        <span class="width-120">CPA：</span>
                        <span class="width-200"><?=$val['actc_cpa']?></span>
                    </div>
                    <div class="mb-20">
                        <span class="width-80 ml-30">收集名片数：</span>
                        <span class="width-200"><?=$val['actc_artqty']?></span>
                        <span class="width-100">当天微信关注数：</span>
                        <span class="width-200"><?=$val['actc_watqyt']?></span>
                        <span class="width-120">活动前一工作日UV：</span>
                        <span class="width-200"><?=$val['actc_bUV']?></span>
                    </div>
                    <div class="mb-20">
                        <span class="width-80 ml-30">当天UV数：</span>
                        <span class="width-200"><?=$val['actc_UV']?></span>
                        <span class="width-100">UV增量：</span>
                        <span class="width-200"><?=$val['actc_UVadd']?></span>
                    </div>
                    <div class="mb-20">
                        <span class="width-80 ml-30">备注：</span>
                        <span class="width-200 vertical-center"><?=$val['actc_remark']?></span>
                    </div>
                <?php }?>
            </div>
            <div class="space-10"></div>
    <?php }?>
    <?php }?>
</div>
<script>
    //删除
    function deleteCount(childId){
        layer.confirm('确定删除吗？',{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['delete-child'])?>",
                    data:{"childId":childId},
                    dataType:"json",
                    success:function(data){
                        if(data.flag==1){
                            location.reload();
                        }else{
                            layer.alert(data.msg,{icon:2},function(){
                                layer.closeAll();
                            });
                        }
                    }
                });
            },
            function(){
                layer.closeAll();
            }
        );
    }

    $(function(){
        //点击查看更多
        $("#toggle_btn").click(function(){
            $(this).find("a").toggle();
            $("#toggle_div").toggle();
        });
    })
</script>
