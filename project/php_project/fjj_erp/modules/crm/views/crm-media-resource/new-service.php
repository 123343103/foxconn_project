<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/4
 * Time: 上午 10:59
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<h3 class="head-first">新增媒体服务内容</h3>
<?php ActiveForm::begin() ?>
<div class="content">
    <h3 class="mb-20">基本信息</h3>
    <div class="mb-20">
        <label class="width-80 ml-20 text-left">媒体类型</label>
        <?=Html::dropDownList("CrmMediaCount[cmt_id]",$model['cmt_type'],$options["mediaType"],["prompt"=>"请选择","class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left">公司名称</label>
        <input type="text" class="width-120" value="<?=$model['medic_compname']?>" disabled>
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left">合作联系人</label>
        <input type="text" class="width-120" value="<?=$model['medic_parner']?>" disabled>
        <label class="width-80 ml-20 text-left">电话号码</label>
        <input type="text" class="width-120" value="<?=$model['medic_tel']?>" disabled>
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left">是否供应商</label>
        <?=Html::dropDownList("CrmMediaCount[medic_issupilse]",$model["medic_issupilse"],$options["isSupplier"],["prompt"=>"请选择","class"=>"width-120","disabled"=>"disabled"])?>
        <label class="width-80 ml-20 text-left">合作次数</label>
        <input type="text" class="width-120" value="<?=$model['medic_times']?>" disabled>
        <label class="width-80 ml-20 text-left">服务评级</label>
        <?=Html::dropDownList("service_level",$model["medic_level"],$options["serviceLevel"],["prompt"=>"请选择","class"=>"width-120","disabled"=>"disabled"])?>
    </div>

    <h3 class="mb-20">详细信息</h3>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">服务项目</label>
        <input name="CrmMediaCountChild[medicc_porjects]" type="text" class="width-120" maxlength="200">
        <label class="width-80 ml-20 text-left" for="">服务时间</label>
        <input name="CrmMediaCountChild[medicc_srdate]" type="text" class="width-120 select-date" readonly>
        <label class="width-80 ml-20 text-left" for="">服务形式</label>
        <input name="CrmMediaCountChild[medicc_srtype]" type="text" class="width-120" value="" maxlength="200">
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left" for="">服务周期</label>
        <input name="CrmMediaCountChild[medicc_srcycle]" type="text" class="width-120" maxlength="200">
        <label class="width-80 ml-20 text-left">服务费用</label>
        <input name="CrmMediaCountChild[medicc_srcost]" type="text" class="width-120" maxlength="19">
        <label class="width-80 ml-20 text-left">货币单位</label>
        <?=Html::dropDownList("CrmMediaCountChild[medicc_currid]","",$options["currency"],["prompt"=>"请选择","class"=>"width-120"])?>
    </div>

    <h4 class="mb-20">预期效果</h4>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left">展现量</label>
        <input name="CrmMediaCountChild[medicc_expectqty]" type="text" class="width-120" maxlength="19">
        <label class="width-80 ml-20 text-left" for="">点击率</label>
        <input name="CrmMediaCountChild[medicc_clickrate]" type="text" class="width-120" maxlength="19">
        <label class="width-80 ml-20 text-left" for="">UV</label>
        <input name="CrmMediaCountChild[medicc_uv]" type="text" class="width-120" maxlength="19">
    </div>

    <h4 class="mb-20">实际效果</h4>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left">展现量</label>
        <input name="CrmMediaCountChild[medicc_realqty]" type="text" class="width-120" maxlength="19">
        <label class="width-80 ml-20 text-left">点击率</label>
        <input name="CrmMediaCountChild[medicc_realclickrate]" type="text" class="width-120" maxlength="19">
        <label class="width-80 ml-20 text-left">UV</label>
        <input name="CrmMediaCountChild[medicc_realuv]" type="text" class="width-120" maxlength="19">
    </div>


    <div class="mb-20">
        <label class="width-80 ml-20 text-left">平均点击价</label>
        <input name="CrmMediaCountChild[medicc_avgclickrate]" type="text" class="width-120" maxlength="19">
        <label class="width-80 ml-20 text-left">400电话</label>
        <input name="CrmMediaCountChild[medicc_400tel]" type="text" class="width-120 easyui-validatebox" data-options="validType:'tel_fzz'" maxlength="11">
        <label class="width-80 ml-20 text-left">TQ咨询</label>
        <input name="CrmMediaCountChild[medicc_tq]" type="text" class="width-120" maxlength="11">
    </div>

    <div class="mb-20">
        <label class="width-80 ml-20 text-left">注册会员</label>
        <input name="CrmMediaCountChild[medicc_rememqty]" type="text" class="width-120" maxlength="11">
        <label class="width-80 ml-20 text-left">CPA</label>
        <input name="CrmMediaCountChild[medicc_cpa]" type="text" class="width-120" maxlength="19">
    </div>


    <div class="mb-20">
        <label style="vertical-align: top;" class="width-80 ml-20 text-left">备注</label>
        <textarea name="CrmMediaCountChild[medicc_remark]" style="width:540px;height:100px;" maxlength="200"></textarea>
    </div>

    <div class="mb-20 text-center">
        <button type="submit" class="button-blue">确定</button>
        <button type="button" onclick="parent.$.fancybox.close()" class="button-white">取消</button>
    </div>
</div>
<?php ActiveForm::end() ?>


<script>
    $(function(){
        ajaxSubmitForm($("form"),"",function(data){
            parent.$.fancybox.close();
            if(data.flag==1){
                parent.layer.alert(data.msg,{icon:1});
            }
            parent.layer.alert(data.msg,{icon:2});
        });
    });
</script>
