<?php
/**
 * User: F1677929
 * Date: 2016/9/18
 */
/* @var $this yii\web\view */
use yii\helpers\Url;
use app\classes\Menu;
use yii\helpers\Html;
$this->title='查看拜访详情';
$this->params['homeLike']=['label'=>'商品开发进程','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'厂商拜访列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?><span style="font-size:12px;color:white;float:right;margin-right:15px;">编号：<?=$data['resumeChild']['vil_code']?></span></h1>
    <div style="margin-bottom:5px;">
        <?php if($data['flag']){?>
            <?=Menu::isAction('/ptdt/visit-resume/edit')?Html::button('修改',['class'=>'button-blue','onclick'=>"window.location.href='".Url::to(['edit','childId'=>$data['resumeChild']['vil_id']])."'"]):''?>
            <?=Menu::isAction('/ptdt/visit-resume/delete')?Html::button('删除',['class'=>'button-blue','id'=>'delete_btn']):''?>
        <?php }?>
        <?=Menu::isAction('/ptdt/visit-resume/index')?Html::button('切换列表',['class'=>'button-blue','style'=>'width:80px;','onclick'=>"window.location.href='".Url::to(['index'])."'"]):''?>
        <?=Menu::isAction('/ptdt/visit-resume/add')?Html::button('新增拜访履历',['class'=>'button-blue','style'=>'width:100px;','onclick'=>"window.location.href='".Url::to(['add','firmId'=>$data['firmInfo']['firm_id']])."'"]):''?>
    </div>
    <div style="height:2px;background-color:#9acfea;margin-bottom:10px;"></div>
    <h2 class="head-second">厂商基本信息</h2>
    <div class="mb-20">
        <span class="width-100 ml-20">注册公司名称：</span>
        <span class="width-200 vertical-center vertical-center"><?=$data['firmInfo']['firm_sname']?></span>
        <span class="width-110 ml-20">简称：</span>
        <span class="width-200 vertical-center"><?=$data['firmInfo']['firm_shortname']?></span>
        <span class="width-50 ml-20">品牌：</span>
        <span class="width-200 vertical-center"><?=$data['firmInfo']['firm_brand']?></span>
    </div>
    <div class="mb-20">
        <span class="width-100 ml-20">公司英文名称：</span>
        <span class="width-200 vertical-center"><?=$data['firmInfo']['firm_ename']?></span>
        <span class="width-110 ml-20">是否为集团供应商：</span>
        <span class="width-200 vertical-center"><?=$data['firmInfo']['issupplier']?></span>
    </div>
    <div class="mb-20">
        <span class="width-100 ml-20">厂商来源：</span>
        <span class="width-200 vertical-center"><?=$data['firmInfo']['firmSource']?></span>
        <span class="width-110 ml-20">厂商类型：</span>
        <span class="width-200 vertical-center"><?=$data['firmInfo']['firmType']?></span>
    </div>
    <div class="mb-20">
        <span class="width-100 ml-20">厂商地址：</span>
        <span style="width:835px;vertical-align:middle;"><?=$data['firmInfo']['firmAddress']['fullAddress']?></span>
    </div>
    <div class="mb-40">
        <span class="width-100 ml-20">分级分类：</span>
        <span style="width:835px;vertical-align:middle;"><?=$data['firmInfo']['category']?></span>
    </div>
    <h2 class="head-second">拜访履历</h2>
    <div class="mb-20">
        <span class="width-110 ml-20">拜访时间：</span>
        <span style="width:835px;vertical-align:middle;"><?=$data['resumeChild']['vil_start_time'].'&nbsp;~&nbsp;'.$data['resumeChild']['vil_start_time']?></span>
    </div>
    <?php if(!empty($data['receptionPerson'])){?>
        <?php foreach($data['receptionPerson'] as $val){?>
        <div class="mb-20">
            <span class="width-110 ml-20">厂商接待人员：</span>
            <span class="width-200 vertical-center"><?=$val['rece_sname']?></span>
            <span class="width-40">职位：</span>
            <span class="width-200 vertical-center"><?=$val['rece_position']?></span>
            <span class="width-60">厂商电话：</span>
            <span class="width-200 vertical-center"><?=$val['rece_mobile']?></span>
        </div>
        <?php }?>
    <?php }?>
    <div class="mb-20">
        <span class="width-110 ml-20">商品经理人：</span>
        <span class="width-200 vertical-center"><?=$data['visitPerson']['staff_name']?></span>
        <span class="width-40">职位：</span>
        <span class="width-200 vertical-center"><?=$data['visitPerson']['job_task']?></span>
        <span class="width-60">联系方式：</span>
        <span class="width-200 vertical-center"><?=$data['visitPerson']['staff_mobile']?></span>
    </div>
    <?php if(!empty($data['accompanyPerson'])){?>
        <?php foreach($data['accompanyPerson'] as $val){?>
            <div class="mb-20">
                <span class="width-110 ml-20">陪同人：</span>
                <span class="width-200 vertical-center"><?=$val['staffInfo']['staffCode'].'<span>&nbsp;</span>'.$val['staffInfo']['staffName']?></span>
                <span class="width-40">职位：</span>
                <span class="width-200 vertical-center"><?=$val['staffInfo']['staffJob']?></span>
                <span class="width-60">联系电话：</span>
                <span class="width-200 vertical-center"><?=$val['staffInfo']['staffMobile']?></span>
            </div>
        <?php }?>
    <?php }?>
    <div class="mb-20">
        <span class="width-110 ml-20">过程描述：</span>
        <span style="width:835px;vertical-align:middle;"><?=$data['resumeChild']['vil_process_Descript']?></span>
    </div>
    <div class="mb-20">
        <span class="width-110 ml-20">访谈结论：</span>
        <span style="width:835px;vertical-align:middle;"><?=$data['resumeChild']['vil_interview_Conclus']?></span>
    </div>
    <div class="mb-20">
        <span class="width-110 ml-20">追踪事项：</span>
        <span style="width:835px;vertical-align:middle;"><?=$data['resumeChild']['vil_track_Item']?></span>
    </div>
    <div class="mb-20">
        <span class="width-110 ml-20">下次访谈注意事项：</span>
        <span style="width:835px;vertical-align:middle;"><?=$data['resumeChild']['vil_next_Interview_Notice']?></span>
    </div>
    <div class="mb-20">
        <span class="width-110 ml-20">其它：</span>
        <span style="width:835px;vertical-align:middle;"><?=$data['resumeChild']['vil_other']?></span>
    </div>
    <div class="mb-20">
        <span class="width-110 ml-20">备注：</span>
        <span style="width:835px;vertical-align:middle;"><?=$data['resumeChild']['vil_remark']?></span>
    </div>
    <div>
        <span class="width-110 ml-20">关联计划：</span>
        <span style="width:835px;vertical-align:middle;"><?=Menu::isAction('/ptdt/visit-plan/view')?"<a href='".Url::to(['/ptdt/visit-plan/view','id'=>$data['visitPlan']['pvp_planID']])."'>".$data['visitPlan']['pvp_plancode']."</a>":$data['visitPlan']['pvp_plancode']?></span>
    </div>
</div>
<script>
    $(function(){
        $("#delete_btn").click(function(){
            layer.confirm("确定删除吗?",{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['delete-child'])?>",
                        data:{"childId":<?=$data['resumeChild']['vil_id']?>},
                        dataType:"json",
                        success:function(data){
                            if(data.flag==1){
                                layer.alert(data.msg,{icon:1},function(){
                                    window.location.href="<?=Url::to(['index'])?>";
                                });
                            }else{
                                layer.alert(data.msg,{icon:2});
                            }
                        }
                    })
                },
                layer.closeAll()
            );
        });
    })
</script>