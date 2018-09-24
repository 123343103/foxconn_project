<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/20
 * Time: 下午 02:32
 */
use app\assets\JqueryUIAsset;
use yii\widgets\ActiveForm;
JqueryUIAsset::register($this);
?>
<h3 class="head-first">新增统计</h3>
<?php ActiveForm::begin([
        "id"=>"count-form"
]) ?>
<input type="hidden" name="CrmCommunityChild[commuc_person]" value="<?=\Yii::$app->user->identity->id?>">
<?php if($model["commu_type"]==100855){ ?>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">编码</label>
        <input type="text" class="width-120" value="<?=$model['commu_code']?>" disabled >
        <label class="width-80 ml-20 text-left" for="">载体</label>
        <?=\yii\helpers\Html::dropDownList("",$model['cmt_id'],$options["publish_carrier"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-center" style="vertical-align: middle" for="">载体名称/所<br />属群分类</label>
        <input type="text" class="width-120" value="<?=$model['cmt_intor']?>" disabled  >
    </div>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">社群营销方式</label>
        <?=\yii\helpers\Html::dropDownList("",$model['commu_type'],$options["commu_type"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">时间</label>
        <input type="text" class="width-120 select-date" value="<?=$model["commu_postime"]?>"  disabled >
    </div>


    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">主题</label>
        <input type="text"  style="width:350px;" value="<?=$model['commu_arttitle']?>" disabled >
        <label class="width-80 ml-20 text-left" for="">类型</label>
        <?=\yii\helpers\Html::dropDownList("",$model['commu_plantype'],$options["plan_type"],["class"=>"width-120","disabled"=>"disabled"])?>
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">链接</label>
        <input type="text" class="width-120" value="<?=$model['commu_link']?>" disabled>
        <label class="width-80 ml-20 text-left" for="">状态</label>
        <?=\yii\helpers\Html::dropDownList("",$model['commu_status'],$options["commu_status"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">负责人</label>
        <input name="" type="text" class="width-120" value="<?=$model['commu_man']?>" disabled>
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">统计时间</label>
        <input name="CrmCommunityChild[commuc_datetime]" type="text" class="width-120 select-date" readonly>
        <label class="width-80 ml-20 text-left" for="">文案来源</label>
        <?=\yii\helpers\Html::dropDownList("",$model['commu_source'],$options["plan_from"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">点赞量</label>
        <input  name="CrmCommunityChild[upvote_num]" type="text" class="width-120 easyui-validatebox" data-options="validType:'int'" maxlength="11">
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">操作日期</label>
        <input name="CrmCommunityChild[create_at]" type="text" class="width-120 select-date" readonly>
        <label class="width-80 ml-20 text-left" for="">阅读量</label>
        <input name="CrmCommunityChild[read_num]" type="text" class="width-120 easyui-validatebox" data-options="validType:'int'" maxlength="11">
        <label class="width-80 ml-20 text-left" for="">转载分享量</label>
        <input name="CrmCommunityChild[share_num]" type="text" class="width-120 easyui-validatebox" data-options="validType:'int'" maxlength="11">
    </div>
    <div class="mb-20">
        <label style="vertical-align: top;" class="width-80 ml-20 text-left" for="">备注</label>
        <textarea name="CrmCommunityChild[commuc_remark]" style="width:570px;height:50px;" maxlength="200"></textarea>
    </div>
<?php } ?>
<?php if($model["commu_type"]==100856){ ?>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">编码</label>
        <input type="text" class="width-120" value="<?=$model["commu_code"]?>" disabled>
        <label class="width-80 ml-20 text-left" for="">载体</label>
        <?=\yii\helpers\Html::dropDownList("",$model['cmt_id'],$options["publish_carrier"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">载体名称<br />/所属群分类</label>
        <input type="text" class="width-120" value="<?=$model['cmt_intor']?>" disabled  >
    </div>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">社群营销方式</label>
        <?=\yii\helpers\Html::dropDownList("",$model['commu_type'],$options["commu_type"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">时间</label>
        <input type="text" class="width-120 select-date" value="<?=$model["commu_postime"]?>"  disabled >
        <label class="width-80 ml-20 text-left" for="">类型</label>
        <?=\yii\helpers\Html::dropDownList("",$model['commu_plantype'],$options["plan_type"],["class"=>"width-120","disabled"=>"disabled"])?>
    </div>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">主题</label>
        <input type="text"  style="width:350px;" value="<?=$model['commu_arttitle']?>" disabled >
    </div>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">链接</label>
        <input type="text" class="width-120" value="<?=$model['commu_link']?>" disabled>
        <label class="width-80 ml-20 text-left" for="">状态</label>
        <?=\yii\helpers\Html::dropDownList("",$model['commu_status'],$options["commu_status"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">负责人</label>
        <input type="text" class="width-120" value="<?=$model['commu_man']?>" disabled>
    </div>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">统计时间</label>
        <input name="CrmCommunityChild[commuc_datetime]" type="text" class="width-120 select-date" readonly>
        <label class="width-80 ml-20 text-left" for="">CPA</label>
        <input name="CrmCommunityChild[commuc_cpa]" type="text" class="width-120" maxlength="19">
        <label class="width-80 ml-20 text-left" for="">活动成本</label>
        <input name="CrmCommunityChild[commuc_cost]" type="text" class="width-120 easyui-validatebox" data-options="validType:'price'" maxlength="19">
    </div>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">活动获奖人数</label>
        <input name="CrmCommunityChild[commuc_perples]" type="text" class="width-120 easyui-validatebox" data-options="validType:'int'" maxlength="11">
        <label class="width-80 ml-20 text-left" for="">活动增粉量</label>
        <input name="CrmCommunityChild[commuc_add]" type="text" class="width-120 easyui-validatebox" data-options="validType:'int'" maxlength="11">
    </div>
    <div class="mb-20">
        <label style="vertical-align: top;" class="width-80 ml-20 text-left" for="">备注</label>
        <textarea name="CrmCommunityChild[commuc_remark]" id="" cols="30" rows="10" style="width:570px;height: 50px;" maxlength="200"></textarea>
    </div>
<?php } ?>
<?php if($model["commu_type"]==100857){ ?>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">编码</label>
        <input type="text" class="width-120" value="<?=$model["commu_code"]?>" disabled>
        <label class="width-80 ml-20 text-left" for="">载体</label>
        <?=\yii\helpers\Html::dropDownList("",$model['cmt_id'],$options["publish_carrier"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">载体名称<br />/所属群分类</label>
        <input type="text" class="width-120" value="<?=$model['cmt_intor']?>" disabled  >
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">社群营销方式</label>
        <?=\yii\helpers\Html::dropDownList("",$model['commu_type'],$options["commu_type"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">时间</label>
        <input type="text" class="width-120 select-date" value="<?=$model["commu_postime"]?>"  disabled >
        <label class="width-80 ml-20 text-left" for="">私聊账号/群号</label>
        <input type="text" class="width-120" value="<?=$model['private_commu_account']?>" maxlength="50">
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">客户账号</label>
        <input type="text" class="width-120" value="<?=$model['cust_account']?>" maxlength="50">
        <label class="width-80 ml-20 text-left" for="">状态</label>
        <?=\yii\helpers\Html::dropDownList("",$model['commu_status'],$options["commu_status"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">负责人</label>
        <input type="text" class="width-120" value="<?=$model['commu_man']?>" disabled>
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">公司名称</label>
        <input type="text" class="width-120" value="<?=$model['cust_cmp_name']?>" maxlength="50">
        <label class="width-80 ml-20 text-left" for="">客户姓名</label>
        <input type="text" class="width-120" value="<?=$model['cust_name']?>" maxlength="50">
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">联系方式</label>
        <input type="text" class="width-120" value="<?=$model['cust_contcats']?>" maxlength="50">
        <label class="width-80 ml-20 text-left" for="">客户意向</label>
        <input type="text" class="width-120 easyui-validatebox" data-options="required:true" name="CrmCommunityChild[cust_intent]" maxlength="100">
    </div>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">私聊开始时间</label>
        <input type="text" id="start-time" class="width-120 select-date easyui-validatebox" data-options="required:true,validType:'timeCompare'" data-type="le" data-target="#end-time" name="CrmCommunityChild[private_commu_start_time]" readonly>
        <label class="width-80 ml-20 text-left" for="">私聊结束时间</label>
        <input id="end-time" type="text" class="width-120 select-date easyui-validatebox" data-options="required:true,validType:'timeCompare'" data-type="ge" data-target="#start-time" name="CrmCommunityChild[private_commu_end_time]" readonly>
    </div>
    <div class="mb-20">
        <label style="vertical-align: top;" class="width-80 ml-20 text-left" for="">互动总结</label>
        <textarea name="CrmCommunityChild[interact_summary]" id="" cols="30" rows="10" style="width:570px;height:50px;" maxlength="200"></textarea>
    </div>
    <div class="mb-20">
        <label style="vertical-align: top;" class="width-80 ml-20 text-left" for="">备注</label>
        <textarea name="CrmCommunityChild[commuc_remark]" id="" cols="30" rows="10" style="width:570px;height:50px;" maxlength="200"></textarea>
    </div>
<?php } ?>
<?php if($model["commu_type"]==100858){ ?>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">编码</label>
        <input type="text" class="width-120" value="<?=$model["commu_code"]?>" disabled>
        <label class="width-80 ml-20 text-left" for="">载体<br />/所属群分类</label>
        <?=\yii\helpers\Html::dropDownList("",$model['cmt_id'],$options["publish_carrier"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">载体名称</label>
        <input type="text" class="width-120" value="<?=$model['cmt_intor']?>" disabled  >
    </div>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">社群营销方式</label>
        <?=\yii\helpers\Html::dropDownList("",$model['commu_type'],$options["commu_type"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">时间</label>
        <input type="text" class="width-120 select-date" value="<?=$model["commu_postime"]?>"  disabled >
        <label class="width-80 ml-20 text-left" for="">私聊账号/群号</label>
        <input type="text" class="width-120" value="<?=$model['private_commu_account']?>" maxlength="50">
    </div>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">主题</label>
        <input type="text"  style="width:350px;" value="<?=$model['commu_arttitle']?>" disabled >
        <label class="width-80 ml-20 text-left" for="">负责人</label>
        <input type="text" class="width-120" value="<?=$model['commu_man']?>" disabled>
    </div>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">状态</label>
        <?=\yii\helpers\Html::dropDownList("",$model['commu_status'],$options["commu_status"],["class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left" for="">发送数量</label>
        <input name="CrmCommunityChild[send_num]" type="text" class="width-120 easyui-validatebox" data-options="validType:'int'" maxlength="11">
        <label class="width-80 ml-20 text-left" for="">有效回复数</label>
        <input name="CrmCommunityChild[effect_reply_num]" type="text" class="width-120 easyui-validatebox" data-options="validType:'int'" maxlength="11">
    </div>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">统计时间</label>
        <input name="CrmCommunityChild[commuc_datetime]" type="text" class="width-120 select-date" readonly>
    </div>
    <div class="mb-20">
        <label style="vertical-align: top;" class="width-80 ml-20 text-left" for="">备注</label>
        <textarea name="CrmCommunityChild[commuc_remark]" id="" cols="30" rows="10" style="width:570px;height:50px;" maxlength="200"></textarea>
    </div>
<?php } ?>
<div class="mb-20 text-center">
    <button type="submit" class="button-blue">确定</button>
    <button type="button" class="button-white ml-10" onclick="parent.$.fancybox.close()">取消</button>
</div>
<?php ActiveForm::end() ?>
<script>
    $(function(){
        ajaxSubmitForm($("#count-form"),"",function(res){
            parent.$.fancybox.close();
            console.log(res);
            if(res.flag==1){
                parent.layer.alert(res.msg,{icon:1});
            }
            parent.layer.alert(res.msg,{icon:2});
        });
    });
</script>
