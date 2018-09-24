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
<style>
    #deal_child .datagrid-header{
        height: 52px !important;
    }
    #deal_child .datagrid-htable{
        height: 54px !important;
    }
</style>
<div class="content">
    <h1 class="head-first"><?=$this->title?><span style="font-size:12px;color:white;float:right;margin-right:15px;"><?='编号：'.$data['countMain']['actch_code']?></span></h1>
    <div class="mb-10">
        <?=Yii::$app->user->identity->is_supper==1?"<button id='delete_btn' type='button' class='button-blue'>删除</button>":''?>
        <?=Menu::isAction('/crm/crm-active-count/index')?"<button type='button' class='button-blue' style='width:80px;' onclick='location.href=\"".Url::to(['index'])."\"'>切换列表</button>":''?>
        <button type="button" class="button-blue" onclick="history.go(-1)">返回</button>
    </div>
    <h1 class="head-second">活动基本信息</h1>
    <div class="mb-20">
        <span class="width-70 ml-30">活动名称：</span>
        <span class="width-200" style="vertical-align:middle;"><?=$data['activeData']['actbs_name']?></span>
        <span class="width-90">活动类型：</span>
        <span class="width-200"><?=$data['activeData']['acttype_name']?></span>
        <span class="width-80">活动方式：</span>
        <span class="width-200"><?=$data['activeData']['activeWay']?></span>
    </div>
    <div class="mb-20">
        <span class="width-70 ml-30">开始时间：</span>
        <span class="width-200"><?=empty($data['activeData']['actbs_start_time'])?'':substr($data['activeData']['actbs_start_time'],0,16)?></span>
        <span class="width-90">结束时间：</span>
        <span class="width-200"><?=empty($data['activeData']['actbs_start_time'])?'':substr($data['activeData']['actbs_end_time'],0,16)?></span>
        <span class="width-80">活动月份：</span>
        <span class="width-200"><?=$data['activeData']['activeMonth']?></span>
    </div>
    <div class="mb-20">
        <span class="width-70 ml-30">活动状态：</span>
        <span class="width-200"><?=$data['activeData']['activeStatus']?></span>
        <span class="width-90">活动成本预算：</span>
        <span class="width-200"><?=$data['activeData']['actbs_cost']?></span>
        <span class="width-80">活动负责人：</span>
        <span class="width-200"><?=$data['activeData']['activeDutyPerson']?></span>
    </div>
    <?php if($data['activeData']['activeWay']=='线上'){?>
        <div class="mb-20">
            <span class="width-70 ml-30">活动网址：</span>
            <span class="width-200"><?=$data['activeData']['actbs_active_url']?></span>
            <span class="width-90">活动PM：</span>
            <span class="width-200"><?=$data['activeData']['actbs_pm']?></span>
        </div>
    <?php }?>
    <?php if($data['activeData']['activeWay']=='线下'){?>
        <div class="mb-10">
            <span class="width-70 ml-30">活动地点：</span>
            <span class="width-200" style="vertical-align:middle;"><?=$data['activeData']['activeAddress']?></span>
            <span class="width-90">活动城市：</span>
            <span class="width-200"><?=$data['activeData']['actbs_city']?></span>
            <span class="width-80">主办单位：</span>
            <span class="width-200"><?=$data['activeData']['actbs_organizers']?></span>
        </div>
        <div id="toggle_btn" class="mb-10 ml-30">
            <a style="font-size:14px;">点击查看更多...</a>
            <a style="font-size:14px;display:none;">收起...</a>
        </div>
        <div id="toggle_div" style="display:none;">
            <div class="mb-20">
                <span class="width-70 ml-30">官方网址：</span>
                <span class="width-200"><?=$data['activeData']['actbs_official_url']?></span>
                <span class="width-90">参与目的：</span>
                <span class="width-200"><?=$data['activeData']['joinPurpose']?></span>
                <span class="width-80">行业类别：</span>
                <span class="width-200"><?=$data['activeData']['industryType']?></span>
            </div>
            <div class="mb-20">
                <span class="width-70 ml-30">展品类别：</span>
                <span style="width:880px;vertical-align:middle;"><?=$data['activeData']['actbs_exhibit']?></span>
            </div>
            <div class="mb-20">
                <span class="width-70 ml-30">活动简介：</span>
                <span style="width:880px;vertical-align:middle;"><?=$data['activeData']['actbs_intro']?></span>
            </div>
            <div class="mb-30">
                <span class="width-70 ml-30">维护人员：</span>
                <span class="width-200"><?=$data['activeData']['maintainPerson']?></span>
                <span class="width-90">维护时间：</span>
                <span class="width-200"><?=empty($data['activeData']['actbs_maintain_time'])?'':substr($data['activeData']['actbs_maintain_time'],0,16)?></span>
                <span class="width-80">维护人员IP：</span>
                <span class="width-200"><?=$data['activeData']['actbs_maintain_ip']?></span>
            </div>
        </div>
    <?php }?>
    <h1 class="head-second">活动统计信息</h1>
    <div id="deal_child">
        <div id="count_child" style="width:100%;"></div>
    </div>
</div>
<script>
    //删除统计信息
    function deleteChild(value){
        layer.confirm('确定删除吗？',{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['delete-child'])?>",
                    data:{"childId":value},
                    dataType:"json",
                    success:function(data){
                        if(data.flag==1){
                            if(data.last==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    location.href="<?=Url::to(['index'])?>";
                                    layer.closeAll();
                                });
                            }else{
                                layer.alert(data.msg,{icon:1},function(){
                                    $("#count_child").datagrid('reload');
                                    layer.closeAll();
                                });
                            }
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
        event.stopPropagation();
    }

    $(function(){
        var $countChild=$("#count_child");
        var editAuth="<?=Menu::isAction('/crm/crm-active-count/edit')?>";
        var deleteAuth="<?=Menu::isAction('/crm/crm-active-count/delete')?>";
        var columns=[];
        if("<?=$data['activeData']['activeWay']?>"=='线上'){
            columns=[[
                {field:"actc_datetime",title:"统计时间",width:115,rowspan:2,formatter:function(value){
                    return value?value.substr(0,16):'';
                }},
                {title:'活动推广',colspan:5},
                {title:'活动效果',colspan:6},
                {field:"actc_cost",title:"活动成本",width:50,rowspan:2},
                {field:"actc_roi",title:"ROI",width:50,rowspan:2},
                {field:"actc_remark",title:"备注",width:100,rowspan:2},
                {field:"actc_iid",title:"操作",width:80,rowspan:2,formatter:function(value,row,index){
                    var str="";
                    if(editAuth=="1"){
                        str+="<a href='<?=Url::to(['edit'])?>?childId="+value+"' onclick='event.stopPropagation();'>修改</a>";
                    }
                    if(deleteAuth=="1"){
                        str+="&nbsp;&nbsp;&nbsp;<a onclick='deleteChild("+value+")'>删除</a>";
                    }
                    if(editAuth!="1"&&deleteAuth!="1"){
                        str="无操作权限";
                    }
                    return str;
                }}
            ],[
                {field:"actc_SEM",title:"SEM",width:50},
                {field:"mediaType",title:"媒体",width:50},
                {field:"actc_vqty",title:"大V",width:50},
                {field:"actc_watch",title:"微信",width:100},
                {field:"actc_emailqty",title:"邮件",width:150},
                {field:"actc_UV",title:"UV",width:50},
                {field:"actc_PV",title:"PV",width:50},
                {field:"actc_memqty",title:"会员注册数",width:70},
                {field:"actc_custqty",title:"成交客户",width:60},
                {field:"actc_ordersqty",title:"订单数量",width:60},
                {field:"actc_ordcountqyt",title:"订单总额",width:60}
            ]];
        }
        if("<?=$data['activeData']['activeWay']?>"=='线下'){
            columns=[[
                {field:"actc_datetime",title:"统计时间",width:115,rowspan:2,formatter:function(value){
                    return value?value.substr(0,16):'';
                }},
                {field:"actc_extent",title:"活动规模(参加人数)",width:70,rowspan:2},
                {field:"actc_peopleqty",title:"参与人次",width:70,rowspan:2},
                {field:"actc_dwqty",title:"发放DW/宣传册数",width:70,rowspan:2},
                {title:'发放礼品数',colspan:3},
                {field:"actc_travelqty",title:"差旅费用",width:70,rowspan:2},
                {field:"actc_countqty",title:"总费用",width:70,rowspan:2},
                {field:"actc_memqty",title:"获得会员数",width:70,rowspan:2},
                {field:"actc_cpa",title:"CPA",width:70,rowspan:2},
                {field:"actc_artqty",title:"收集名片数",width:70,rowspan:2},
                {field:"actc_watqyt",title:"当天微信关注数",width:70,rowspan:2},
                {field:"actc_bUV",title:"活动前一工作日UV",width:70,rowspan:2},
                {field:"actc_UV",title:"当天UV数",width:70,rowspan:2},
                {field:"actc_UVadd",title:"UV增量",width:70,rowspan:2},
                {field:"actc_iid",title:"操作",width:80,rowspan:2,formatter:function(value,row,index){
                    var str="";
                    if(editAuth=="1"){
                        str+="<a href='<?=Url::to(['edit'])?>?childId="+value+"' onclick='event.stopPropagation();'>修改</a>";
                    }
                    if(deleteAuth=="1"){
                        str+="&nbsp;&nbsp;&nbsp;<a onclick='deleteChild("+value+")'>删除</a>";
                    }
                    if(editAuth!="1"&&deleteAuth!="1"){
                        str="无操作权限";
                    }
                    return str;
                }}
            ],[
                {field:"actc_sjkqty",title:"手机壳",width:50},
                {field:"actc_boxqty",title:"名片盒",width:50},
                {field:"actc_ldqty",title:"领带",width:50}
            ]];
        }
        $(window).load(function(){
            $countChild.datagrid({
                url:"<?=Url::to(['load-count'])?>",
                queryParams:{"mainId":"<?=$data['countMain']['actch_id']?>"},
                rownumbers:true,
                method:"get",
                idField:"actc_iid",
                singleSelect:true,
//                pagination:true,
                columns:columns,
                onLoadSuccess:function(data){
                    datagridTip($countChild);
                    setMenuHeight();
                    if("<?=$data['activeData']['activeWay']?>"=='线下'){
                        $countChild.datagrid('appendRow',data.footer[0]);
                        $("#deal_child").find("td:last").html('/');
                    }
                }
            });
        });

        //点击查看更多
        $("#toggle_btn").click(function(){
            $(this).find("a").toggle();
            $("#toggle_div").toggle();
        });

        //删除
        $("#delete_btn").click(function(){
            layer.confirm('确定删除吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['delete-main'])?>",
                        data:{"mainId":"<?=$data['countMain']['actch_id']?>"},
                        dataType:"json",
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    location.href="<?=Url::to(['index'])?>";
                                    layer.closeAll();
                                });
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
        });
    })
</script>
