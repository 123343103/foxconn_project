<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/13
 * Time: 上午 10:06
 */
use yii\helpers\Html;
use yii\helpers\Url;
use \yii\widgets\ActiveForm;
$action=\Yii::$app->controller->action->id;
?>
<?php ActiveForm::begin([
        "id"=>"community-form"
]) ?>
<div class="mb-20" <?=$action=="edit"?"style='display:none;'":""?> >
    <label class="width-120">社群营销方式</label>
    <?=Html::dropDownList("CrmCommunity[commu_type]",$model["commu_type"],$options["commu_type"],["id"=>"cmt_type","class"=>"width-200"])?>
</div>


<div id="dynamic-area">
    <div class="mb-20">
        <label class="width-120" for="">发布载体</label>
        <?=Html::dropDownList("CrmCommunity[cmt_id]",$model['cmt_id'],$options["publish_carrier"],["prompt"=>"请选择","class"=>"publish_carrier width-200 easyui-validatebox","data-options"=>"required:true"])?>
        <label class="width-120" for="">载体名称</label>
        <?=Html::dropDownList("CrmCommunity[cmt_intor]",$model['cmt_intor'],$options["carrier_names"],["prompt"=>"请选择","class"=>"carrier_name width-200 easyui-validatebox","data-options"=>"required:true"])?>
    </div>
    <div class="mb-20">
        <label class="width-120" for="">文案类型</label>
        <?=Html::dropDownList("CrmCommunity[commu_plantype]",$model["commu_plantype"],$options["plan_type"],["prompt"=>"请选择","class"=>"width-200 easyui-validatebox","data-options"=>"required:true"])?>
        <label class="width-120" for="">文案来源</label>
        <?=Html::dropDownList("CrmCommunity[commu_source]",$model["commu_source"],$options["plan_from"],["prompt"=>"请选择","class"=>"width-200"])?>
    </div>
    <div class="mb-20">
        <label class="width-120" for="">发布时间</label>
        <input name="CrmCommunity[commu_postime]" class="select-date width-200 easyui-validatebox" data-options="required:true" type="text" value="<?=$model['commu_postime']?>" readonly>
        <label class="width-120" for="">负责人</label>
        <input name="CrmCommunity[commu_man]" class="width-200 easyui-validatebox" data-options="required:true" type="text" value="<?=$model['commu_man'];?>" maxlength="20">
    </div>
    <div class="mb-20">
        <label class="width-120" for="">文章主题</label>
        <input name="CrmCommunity[commu_arttitle]" class="width-530" type="text" value="<?=$model['commu_arttitle']?>" maxlength="200">
    </div>
    <div class="mb-20">
        <label class="width-120" for="">发文链接</label>
        <input name="CrmCommunity[commu_link]" class="width-530 easyui-validatebox" data-options="required:true,validType:'url'" type="text" value="<?=$model['commu_link'];?>" maxlength="200">
    </div>
</div>
<div class="mb-20">
    <label style="vertical-align: top;" class="width-120" for="">备注</label>
    <textarea style="width: 530px;"  name="CrmCommunity[commu_remark]" id="" cols="30" rows="10" maxlength="200" ><?=$model['commu_remark']?></textarea>
</div>
<div style="width: 630px;" class="mb-20 text-center">
    <button type="submit" class="button-blue">保存</button>
    <button type="button" class="button-white" onclick="window.location.href='index'">返回</button>
</div>
<?php ActiveForm::end() ?>
<div id="switch-box">

    <div class="item">
        <div class="mb-20">
            <label class="width-120" for="">发布载体</label>
            <?=Html::dropDownList("CrmCommunity[cmt_id]",$model['cmt_id'],$options["publish_carrier"],["prompt"=>"请选择","class"=>"publish_carrier width-200 easyui-validatebox","data-options"=>"required:true"])?>
            <label class="width-120" for="">载体名称</label>
            <?=Html::dropDownList("CrmCommunity[cmt_intor]",$model['cmt_intor'],$options["carrier_names"],["prompt"=>"请选择","class"=>"carrier_name width-200 easyui-validatebox","data-options"=>"required:true"])?>
        </div>
        <div class="mb-20">
            <label class="width-120" for="">文案类型</label>
            <?=Html::dropDownList("CrmCommunity[commu_plantype]",$model["commu_plantype"],$options["plan_type"],["prompt"=>"请选择","class"=>"width-200 easyui-validatebox","data-options"=>"required:true"])?>
            <label class="width-120" for="">文案来源</label>
            <?=Html::dropDownList("CrmCommunity[commu_source]",$model["commu_source"],$options["plan_from"],["prompt"=>"请选择","class"=>"width-200"])?>
        </div>
        <div class="mb-20">
            <label class="width-120" for="">发布时间</label>
            <input name="CrmCommunity[commu_postime]" class="select-date width-200 easyui-validatebox" data-options="required:true" type="text" value="<?=$model['commu_postime']?>" readonly>
            <label class="width-120" for="">负责人</label>
            <input name="CrmCommunity[commu_man]" class="width-200 easyui-validatebox" data-options="required:true" type="text" value="<?=$model['commu_man'];?>" maxlength="20">
        </div>
        <div class="mb-20">
            <label class="width-120" for="">文章主题</label>
            <input name="CrmCommunity[commu_arttitle]" class="width-530" type="text" value="<?=$model['commu_arttitle']?>" maxlength="200">
        </div>
        <div class="mb-20">
            <label class="width-120" for="">发文链接</label>
            <input name="CrmCommunity[commu_link]" class="width-530 easyui-validatebox" data-options="required:true,validType:'url'" type="text" value="<?=$model['commu_link'];?>" maxlength="200">
        </div>
    </div>

    <div class="item">
        <div class="mb-20">
            <label class="width-120" for="">发布载体</label>
            <?=Html::dropDownList("CrmCommunity[cmt_id]",$model["cmt_id"],$options["publish_carrier"],["prompt"=>"请选择","class"=>"publish_carrier width-200 easyui-validatebox","data-options"=>"required:true"])?>
            <label class="width-120" for="">载体名称</label>
            <?=Html::dropDownList("CrmCommunity[cmt_intor]",$model["cmt_intor"],$options["carrier_names"],["prompt"=>"请选择","class"=>"carrier_name width-200 easyui-validatebox","data-options"=>"required:true"])?>
        </div>
        <div class="mb-20">
            <label class="width-120" for="">活动开始时间</label>
            <input name="CrmCommunity[act_start_time]" id="start-time" class="width-200 select-date easyui-validatebox" data-options="required:true,validType:'timeCompare'" data-type="le" data-target="#end-time" type="text" value="<?=$model['act_start_time']?>" readonly>
            <label class="width-120" for="">活动结束时间</label>
            <input name="CrmCommunity[act_end_time]" id="end-time" class="width-200 select-date easyui-validatebox" data-options="required:true,validType:'timeCompare'" data-type="ge" data-target="#start-time" type="text" value="<?=$model['act_end_time']?>" readonly>
        </div>
        <div class="mb-20">
            <label class="width-120" for="">活动类型</label>
            <?=Html::dropDownList("CrmCommunity[act_type]",$model["act_type"],$options["act_type"],["prompt"=>"请选择","class"=>"width-200 easyui-validatebox","data-options"=>"required:true"])?>
            <label class="width-120" for="">负责人</label>
            <input name="CrmCommunity[commu_man]" class="width-200 easyui-validatebox" data-options="required:true" type="text" maxlength="20" value="<?=$model['commu_man']?>">
        </div>
        <div class="mb-20">
            <label class="width-120" for="">活动主题</label>
            <input type="text" class="width-530 easyui-validatebox" data-options="required:true" name="CrmCommunity[commu_arttitle]" value="<?=$model['commu_arttitle']?>" maxlength="200">
        </div>
        <div class="mb-20">
            <label class="width-120" for="">发文链接</label>
            <input name="CrmCommunity[commu_link]" class="width-530 easyui-validatebox" data-options="required:true,validType:'url'" type="text" maxlength="200" value="<?=$model["commu_link"]?>">
        </div>
    </div>

    <div class="item">
        <div class="mb-20">
            <label class="width-120" for="">私聊载体</label>
            <?=Html::dropDownList("CrmCommunity[cmt_id]",$model["cmt_id"],$options["publish_carrier"],["prompt"=>"请选择","class"=>"publish_carrier width-200 easyui-validatebox","data-options"=>"required:true"])?>
            <label class="width-120" for="">载体名称</label>
            <?=Html::dropDownList("CrmCommunity[cmt_intor]",$model['cmt_intor'],$options["carrier_names"],["prompt"=>"请选择","class"=>"carrier_name width-200 easyui-validatebox","data-options"=>"required:true"])?>
        </div>
        <div class="mb-20">
            <label class="width-120" for="">私聊账号</label>
            <input name="CrmCommunity[private_commu_account]" type="text" class="width-200 easyui-validatebox" data-options="required:true" value="<?=$model['private_commu_account']?>" maxlength="50">
            <label class="width-120" for="">客户姓名</label>
            <input name="CrmCommunity[cust_name]" type="text" class="width-200" value="<?=$model['cust_name']?>" maxlength="50">
        </div>
        <div class="mb-20">
            <label class="width-120" for="">客户账号</label>
            <input name="CrmCommunity[cust_account]" type="text" class="width-200 easyui-validatebox" data-options="required:true" value="<?=$model['cust_account']?>" maxlength="50">
            <label class="width-120" for="">客户联系方式</label>
            <input name="CrmCommunity[cust_contcats]" type="text" class="width-200" value="<?=$model['cust_contcats']?>" maxlength="50">
        </div>

        <div class="mb-20">
            <label class="width-120" for="">公司名称</label>
            <input name="CrmCommunity[cust_cmp_name]" type="text" class="width-200" value="<?=$model['cust_cmp_name']?>" maxlength="50">
            <label class="width-120" for="">负责人</label>
            <input name="CrmCommunity[commu_man]" class="width-200 easyui-validatebox" data-options="required:true" type="text" value="<?=$model['commu_man']?>" maxlength="20">
        </div>

        <div class="mb-20">
            <label class="width-120" for="">公司地址</label>
            <?=\app\widgets\district\District::widget([
                "district_id"=>$model["cust_cmp_district"],
                "name"=>"CrmCommunity[cust_cmp_district]",
                "options"=>[
                    'country'=>["prompt"=>"请选择","class"=>"district-level easyui-validatebox","style"=>"width:130px;"],
                    'province'=>["prompt"=>"请选择","class"=>"district-level easyui-validatebox","style"=>"width:130px;"],
                    'city'=>["prompt"=>"请选择","class"=>"district-level easyui-validatebox","style"=>"width:130px;"],
                    'district'=>["prompt"=>"请选择","class"=>"district-level easyui-validatebox","style"=>"width:130px;"]
                ]
            ]);?>
            <div>
                <input name="CrmCommunity[cust_cmp_address]" type="text" style="margin-left: 125px;margin-top:20px;width:530px;" value="<?=$model['cust_cmp_address']?>" maxlength="50">
            </div>
        </div>
    </div>

    <div class="item">
        <div class="mb-20">
            <label class="width-120" for="">所属群分类</label>
            <?=Html::dropDownList("CrmCommunity[cmt_id]",$model["cmt_id"],$options["publish_carrier"],["prompt"=>"请选择","class"=>"publish_carrier width-200 easyui-validatebox","data-options"=>"required:true"])?>
            <label for="" class="width-120">群号</label>
            <input name="CrmCommunity[private_commu_account]" type="text" class="width-200 easyui-validatebox" data-options="required:true" value="<?=$model['private_commu_account']?>" maxlength="11">
        </div>
        <div class="mb-20">
            <label for="" class="width-120">邮件主题</label>
            <input name="CrmCommunity[commu_arttitle]" class="width-530" type="text" value="<?=$model['commu_arttitle']?>" maxlength="200">
        </div>
        <div class="mb-20">
            <label for="" class="width-120">发送时间</label>
            <input name="CrmCommunity[email_send_time]" type="text" class="width-200 select-date easyui-validatebox" data-options="required:true" value="<?=$model['email_send_time']?>" readonly>
            <label for="" class="width-120">发送数量</label>
            <input name="CrmCommunity[email_send_num]" type="text" class="width-200 easyui-validatebox" data-options="required:true" value="<?=$model['email_send_num']?>">
        </div>
        <div class="mb-20">
            <label for="" class="width-120">负责人</label>
            <input name="CrmCommunity[commu_man]" class="width-200 easyui-validatebox" data-options="required:true" type="text" value="<?=$model['commu_man']?>" maxlength="20">
        </div>
    </div>
</div>

<style>
    #switch-box{
        display: none;
    }
</style>
