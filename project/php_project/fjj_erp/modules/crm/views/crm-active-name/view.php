<?php
/**
 * User: F1677929
 * Date: 2017/3/9
 */
use yii\helpers\Url;
use app\classes\Menu;
$this->title='查看活动名称';
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'活动相关设置','url'=>Url::to(['/crm/crm-active-set/index'])];
$this->params['breadcrumbs'][]=['label'=>'活动名称列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<style>
    .div_tab_display {
        display: table;
        margin-bottom: 5px;
    }
    .div_tab_display > label {
        display: table-cell;
        vertical-align: middle;
        width: 100px;
    }
    .div_tab_display > span {
        display: table-cell;
        vertical-align: middle;
        width: 200px;
    }
</style>
<div class="content">
    <h1 class="head-first"><?=$this->title?><span style="font-size:12px;color:white;float:right;margin-right:15px;"><?='编号：'.$data['actbs_code']?></span></h1>
    <div class="div_tab_display">
        <label>活动名称：</label>
        <span style="width:500px;"><?=$data['actbs_name']?></span>
    </div>
    <div class="div_tab_display">
        <label>活动类型：</label>
        <span><?=$data['acttype_name']?></span>
        <label>活动方式：</label>
        <span><?=$data['activeWay']?></span>
    </div>
    <div class="div_tab_display">
        <label>活动月份：</label>
        <span><?=$data['activeMonth']?></span>
        <label>活动状态：</label>
        <span id="active_name_status"><?=$data['activeNameStatus']?></span>
    </div>
    <div class="div_tab_display">
        <label>开始时间：</label>
        <span><?=date('Y-m-d H:i',strtotime($data['actbs_start_time']))?></span>
        <label>结束时间：</label>
        <span><?=date('Y-m-d H:i',strtotime($data['actbs_end_time']))?></span>
    </div>
    <div class="div_tab_display">
        <label>活动成本预算：</label>
        <span><?=$data['actbs_cost']?></span>
        <label>活动负责人：</label>
        <span><?=$data['activeDuty']?></span>
    </div>
    <?php if($data['activeWay']=='线上'){?>
        <div class="mb-40">
            <label>活动网址：</label>
            <span><?=$data['actbs_active_url']?></span>
            <label>活动PM：</label>
            <span><?=$data['actbs_pm']?></span>
        </div>
    <?php }?>
    <?php if($data['activeWay']=='线下'){?>
        <div class="div_tab_display">
            <label>活动城市：</label>
            <span><?=$data['actbs_city']?></span>
            <label>行业类别：</label>
            <span><?=$data['industryType']?></span>
        </div>
        <div class="div_tab_display">
            <label>主办单位：</label>
            <span><?=$data['actbs_organizers']?></span>
            <label>活动地点：</label>
            <span><?=$data['activeAddress']?></span>
        </div>
        <div class="div_tab_display">
            <label>官方网址：</label>
            <span><?=$data['actbs_official_url']?></span>
            <label>参与目的：</label>
            <span><?=$data['joinPurpose']?></span>
        </div>
        <div class="div_tab_display">
            <label>展品类别：</label>
            <span style="width:500px;"><?=$data['actbs_exhibit']?></span>
        </div>
        <div class="div_tab_display">
            <label>活动简介：</label>
            <span style="width:500px;"><?=$data['actbs_intro']?></span>
        </div>
        <div class="div_tab_display">
            <label>维护人员：</label>
            <span><?=$data['activeMaintain']?></span>
            <label class="width-90">维护时间：</label>
            <span><?=date('Y-m-d H:i',strtotime($data['actbs_maintain_time']))?></span>
            <label class="width-90">维护人员IP：</label>
            <span><?=$data['actbs_maintain_ip']?></span>
        </div>
    <?php }?>
    <div class="text-center">
        <?php if(Menu::isAction('/crm/crm-active-name/cancel-active')&&$data['activeNameStatus']!='已取消'&&$data['activeNameStatus']!='已结束'){?>
            <button id="cancel_active" type="button" class="button-blue-big mr-20">取消活动</button>
        <?php }?>
        <?php if(Menu::isAction('/crm/crm-active-name/edit')){?>
        <button type="button" class="button-blue-big mr-20" onclick="window.location.href='<?=Url::to(['edit','nameId'=>$data['actbs_id']])?>'">修改</button>
        <?php }?>
        <button id="return_btn" type="button" class="button-white-big">返回</button>
    </div>
</div>
<script>
    $(function(){
        //取消活动
        $("#cancel_active").click(function(){
            layer.confirm('确定取消吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['cancel-active']);?>",
                        data:{"nameId":<?=$data['actbs_id']?>},
                        dataType:"json",
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    location.reload();
                                });
                            }else{
                                layer.alert(data.msg,{icon:2});
                            }
                        }
                    })
                },
                layer.closeAll()
            )
        });

        //返回
        $("#return_btn").click(function(){
            <?php if($from=='calendar'){?>
            location.href="<?=Url::to(['/crm/crm-active-calendar/index'])?>";
            <?php }else{?>
            location.href="<?=Url::to(['index'])?>";
            <?php }?>
        });
    })
</script>