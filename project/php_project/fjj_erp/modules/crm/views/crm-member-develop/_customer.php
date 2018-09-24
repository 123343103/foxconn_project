<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<h1 class="head-first">新增客户</h1>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-10">
        <label class="width-100">公司名称</label>
        <input class="width-120 easyui-validatebox" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_sname]" id="cust_sname" value="<?= $model['cust_sname'] ?>">
        <label class="width-100">公司简称</label>
        <input class="width-120 easyui-validatebox" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_shortname]" id="cust_shortname" value="<?= $model['cust_shortname'] ?>">

    </div>
    <div class="mb-10">
        <label class="width-80">联系人</label>
        <input class="width-120 easyui-validatebox" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_contacts]" value="<?= $model['cust_contacts'] ?>">
        <label class="width-100">手机号码</label>
        <input class="width-120 easyui-validatebox" data-options="required:'true',validType:'mobile'" type="text" name="CrmCustomerInfo[cust_tel2]" value="<?= $model['cust_tel2'] ?>">
        <label class="width-80">邮箱</label>
        <input class="width-120 easyui-validatebox" data-options="required:'true',validType:'email'" type="text" name="CrmCustomerInfo[cust_email]" value="<?= $model['cust_email'] ?>">
    </div>
    <div class="mb-10">
        <label class="width-80">详细地址</label>
        <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_1" >
            <option value="">请选择...</option>
            <?php foreach ($downList['country'] as $key => $val) { ?>
                <option value="<?= $val['district_id'] ?>"<?= $districtAll['oneLevelId']==$val['district_id']?"selected":null; ?>><?= $val['district_name'] ?></option>
            <?php } ?>
        </select>
        <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_2" >
            <option value="">请选择...</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['twoLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>"<?= $districtAll['twoLevelId']==$val['district_id']?"selected":null; ?>><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_3" >
            <option value="">请选择...</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['threeLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>"<?= $districtAll['threeLevelId']==$val['district_id']?"selected":null; ?>><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <select class="width-100 disName easyui-validatebox" data-options="required:'true'" id="disName_4"  name="CrmCustomerInfo[cust_district_2]">
            <option value="">请选择...</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['fourLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>"<?= $districtAll['fourLevelId']==$val['district_id']?"selected":null; ?>><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <input class="width-160 easyui-validatebox" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>">
    </div>

    <div class="text-center">
        <button type="button" class="button-blue-big" id="sub">确定</button>
        <button class="button-white-big" onclick="close_select()" type="button">返回</button>
    </div>

    <?php ActiveForm::end() ?>
</div>
<script>
    $(function(){
        $('#sub').click(function(){
            $.ajax({
                type:'post',
                dataType:'json',
                data:$("#add-form").serialize(),
                url:"<?= \yii\helpers\Url::to(['create-customer']) ?>",
                success:function(data){
                    if(data.flag == 1){
                        layer.alert("添加成功!",{icon:1,end: function () {
                            parent.window.location.reload();
                        }});
                    }
                }
            })
        })
        /**
         * 验证会员用户名唯一性
         */
        $("#member_name").blur(function(){
            $("#member_name").validatebox({
                required:true,
                delay:700,
                validType:"remote['<?=\yii\helpers\Url::to(['/crm/crm-member/cust-validation'])?>?name="+$("#member_name").val()+"','code']",
                invalidMessage:'用户名已存在',
                missingMessage: '用户名不能为空'
            })

        })
        /**
         * 验证客户名称唯一性
         */
        $("#cust_sname").blur(function(){
            $("#cust_sname").validatebox({
                required:true,
                delay:700,
                validType:"remote['<?=\yii\helpers\Url::to(['/crm/crm-member/cust-validation'])?>?name="+$("#cust_sname").val()+"','code']",
                invalidMessage:'客户已存在',
                missingMessage: '客户不能为空'
            })
        })
        $("#cust_shortname").blur(function(){
            $("#cust_shortname").validatebox({
                required:true,
                delay:700,
                validType:"remote['<?=\yii\helpers\Url::to(['/crm/crm-member/cust-validation'])?>?name="+$("#cust_shortname").val()+"','code']",
                invalidMessage:'客户简称已存在',
                missingMessage: '客户简称不能为空'
            })
        })
        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select,$url,"select");
        });
    });
</script>
