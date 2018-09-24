<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/29
 * Time: 下午 03:27
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = '编辑车辆信息';
$this->params['homeLike'] = ['label' => '倉儲物流管理', 'url' => ''];
$this->params['breadcrumbs'][] = ['label' => '倉儲物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '编辑车辆信息', 'url' => ""];
?>

<div class="content">
    <h1 class="head-first">
        编辑车辆信息
    </h1>
    <?php $form = ActiveForm::begin([
        'id' => 'update-form',
        'enableAjaxValidation' => true,
    ]); ?>
    <div>
        <div class="mb-20">
            <label class="width-150 ml-50"><span class="red">*</span>车牌号</label>
            <input type="hidden" value="<?= Yii::$app->user->identity->staff_id ?>" name="HrStaff[staff_code]">
            <input id="veh_number" class="width-200 easyui-validatebox" data-options="required:true,validType:'unique',delay:500" ] data-attr="veh_number" value="<?php echo $model['veh_number']?>" name="BsVeh[veh_number]" />
            <label class="width-150"><span class="red">*</span>车辆类型</label>
            <input id="veh_type" type="text" value="<?php echo $model['veh_type']?>" class="width-200 easyui-validatebox" data-options="required:true" data-attr="veh_type"  name="BsVeh[veh_type]" />
        </div>
        <div class="mb-20">
            <label class="width-150 ml-50"><span class="red">*</span>公司</label>
            <select id="bsveh-veh_company" class="width-200 easyui-validatebox" name="BsVeh[log_code]">
                <option value="">请选择...</option>
                <?php foreach ($companyname as $key => $val) { ?>
                    <option
                        value="<?= $val['log_code'] ?>" <?= $model['log_code'] == $val['log_code'] ? "selected" : null ?>><?= $val['log_cmp_name'] ?></option>
                <?php } ?>
            </select>
            <label class="width-150"><span class="red">*</span>车辆品牌</label>
            <input id="veh_brand" type="text" value="<?php echo $model['veh_brand']?>" class="width-200 easyui-validatebox" data-options="required:true" data-attr="veh_brand"  name="BsVeh[veh_brand]" />
        </div>
        <div class="mb-20">
            <label class="width-150 ml-50"><span class="red">*</span>负责人</label>
            <input id="person_charge" class="width-200 easyui-validatebox" data-options="required:true" data-attr="person_charge" value="<?php echo $model['person_charge']?>" name="BsVeh[person_charge]" />
            <label class="width-150"><span class="red">*</span>电话</label>
            <input id="person_phone" type="text"  class="width-200 easyui-validatebox" data-options="required:true" data-attr="person_phone" name="BsVeh[person_phone]" value="<?php echo $model['person_phone']?>" />
        </div>
        <div class="mb-20">
            <label class="width-150 ml-50"><span class="red">*</span>联系人</label>
            <input id="veh_contacts" class="width-200 easyui-validatebox" data-options="required:true" type="text" value="<?php echo $model['veh_contacts']?>" name="BsVeh[veh_contacts]">
            <label class="width-150"><span class="red">*</span>电话</label>
            <input id="contacts_phone" class="width-200 easyui-validatebox" data-options="required:true" type="text" value="<?php echo $model['contacts_phone']?>" name="BsVeh[contacts_phone]">
        </div>
        <div class="mb-20">
            <label class="width-150 ml-50"><span class="red">*</span>车辆颜色</label>
            <input id="veh_color" class="width-200 easyui-validatebox" data-options="required:true" type="text" value="<?php echo $model['veh_color']?>" name="BsVeh[veh_color]">
        </div>
    </div>
    <div class="space-40 ml-50"></div>
    <button class="button-blue-big ml-320" type="submit" id="sub">提交</button>
    <button class="button-white-big ml-20" type="button" onclick="window.location.href = '<?=Url::to(["index"])?>';">返回</button>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(document).ready(function() {
        ajaxSubmitForm($("#update-form"),function(){
            if($("#veh_number").val() == null||$("#veh_number").val()==""){
                layer.alert("请输入车牌号!",{icon:2});
                return false;
            }
            if($("#veh_type").val() == null||$("#veh_type").val()==""){
                layer.alert("请车牌类型!",{icon:2});
                return false;
            }
            if($("#bsveh-veh_company").val() == null||$("#bsveh-veh_company").val()==""){
                layer.alert("请选择公司名称!",{icon:2});
                return false;
            }
            if($("#veh_brand").val() == null||$("#veh_brand").val()==""){
                layer.alert("请输入车辆品牌!",{icon:2});
                return false;
            }
            if($("#person_charge").val() == null||$("#person_charge").val()==""){
                layer.alert("请输入负责人!",{icon:2});
                return false;
            }
            if($("#person_phone").val() == null||$("#person_phone").val()==""){
                layer.alert("请输入负责人电话!",{icon:2});
                return false;
            }
            else if(!checkNumber($("#person_phone").val()))
            {
                layer.alert("你输入的负责人电话有错，请重新输入!",{icon:2});
                return false;
            }
            if($("#veh_contacts").val() == null||$("#veh_contacts").val()==""){
                layer.alert("请输入联系人!",{icon:2});
                return false;
            }
            if($("#contacts_phone").val() == null||$("#contacts_phone").val()==""){
                layer.alert("请输入联系人电话!",{icon:2});
                return false;
            }
            else if(!checkNumber($("#contacts_phone").val()))
            {
                layer.alert("你输入的联系人电话有错，请重新输入!",{icon:2});
                return false;
            }
            if($("#veh_color").val() == null||$("#veh_color").val()==""){
                layer.alert("请输入车辆颜色!",{icon:2});
                return false;
            }
            return true;
        });
        //判断儲位码是否是纯数字或纯字母
        function checkNumber(theObj) {
            var phone = /^0?1[3|4|5|8][0-9]\d{8}$/;
            var tels = /^0\d{2,3}-[1-9]\d{6,7}$/;
            var telss = /^[\(|（]0\d{2,3}[\)|）]-[1-9]\d{6,7}$/;
            if (phone.test(theObj)||tels.test(theObj)||telss.test(theObj)) {
                return true;
            }
            return false;
        }
    });
</script>
