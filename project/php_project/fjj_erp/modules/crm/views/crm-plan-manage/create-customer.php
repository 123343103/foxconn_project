<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$get = Yii::$app->request->get();
?>
<style>
    .fancybox-wrap{
        top:  0px !important;
        left: 0px !important;
    }
</style>
<h1 class="head-first">新增客户</h1>
<div class="no-padding" style=<?= $get['style'] ?>>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-20 mt-40">
<!--        <label class="width-80"><span class="red">*</span>会员名</label>-->
<!--        <input class="width-120 easyui-validatebox add-require" type="text" id="member_name" name="CrmCustomerInfo[member_name]" data-options="required:'true'"  maxlength="15">-->
        <label class="width-120"><span class="red">*</span>客户全称</label>
        <input class="width-190 easyui-validatebox" data-options="required:'true',validType:'unique'" maxlength="40" data-attr="cust_sname" data-act="<?=Url::to(['validate'])?>" type="text" name="CrmCustomerInfo[cust_sname]" id="cust_sname">
        <label class="width-120"><span class="red">*</span>客户简称</label>
        <input class="width-190 easyui-validatebox" data-options="required:'true',validType:'unique'" data-attr="cust_shortname" data-act="<?=Url::to(['validate'])?>" maxlength="20" type="text" name="CrmCustomerInfo[cust_shortname]" id="cust_shortname">
    </div>
    <div class="mb-20">
        <label class="width-120" for="">公司电话</label>
        <input class="width-190 easyui-validatebox" data-options="validType:'telphone'" type="text" name="CrmCustomerInfo[cust_tel1]" maxlength="15">

        <label class="width-120" for="">传真</label>
        <input class="width-190 easyui-validatebox" data-options="validType:'telphone'" type="text" name="CrmCustomerInfo[cust_fax]" maxlength="15">
    </div>
    <div class="mb-20">
        <label class="width-120"><span class="red">*</span>客户类型</label>
        <select class="width-190 easyui-validatebox" name="CrmCustomerInfo[cust_type]"
                data-options="required:'true'">
            <option value>请选择...</option>
            <?php foreach ($downList['customerType'] as $key => $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>" <?= $model['cust_type'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
<!--        <label class="width-120">客户经理人</label>-->
<!--        <input type="text" value="--><?//= $downList['managerDefault']['staff_code'] ? $downList['managerDefault']['staff_code'] : null ?><!--"  class="width-100 easyui-validatebox staff_code_null" data-options="validType:'staffCode',delay:1000000" data-url="--><?//= Url::to(['/hr/staff/get-staff-info']) ?><!--"/>-->
<!--        <span class="width-80 staff_name"></span>-->
<!--        <input type="hidden" name="CrmCustomerInfo[cust_manager]" class="staff_id" value=""/>-->
        <label class="width-120">客户来源</label>
        <select class="width-190" name="CrmCustomerInfo[cust_type]">
            <option value>请选择...</option>
            <?php foreach ($downList['customerSource'] as $key => $val) { ?>
                <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-20">
        <label class="width-120"><span class="red">*</span>所在地区</label>
        <select class="width-190 easyui-validatebox" id="custArea" name="CrmCustomerInfo[cust_area]" data-options="required:'true'">
            <option value="">请选择...</option>
            <?php foreach ($downList['district'] as $key => $val) { ?>
                <option
                    value="<?= $val['district_id'] ?>" <?= $model['cust_area'] == $val['district_id'] ? "selected" : null; ?>><?= $val['district_name'] ?></option>
            <?php } ?>
        </select>
        <label class="width-120">营销区域</label>
        <select class="width-190 custSalearea" id="custSalearea" name="CrmCustomerInfo[cust_salearea]" style="color:#666;">
            <option value="">请选择...</option>
            <?php foreach ($downList['salearea'] as $key => $val) { ?>
                <option
                    value="<?= $val['csarea_id'] ?>" <?= $model['cust_salearea'] == $val['csarea_id'] ? "selected" : null; ?>><?= $val['csarea_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-20">
        <label class="width-120"><span class="red">*</span>联系人</label>
        <input class="width-190 easyui-validatebox" data-options="required:'true'" maxlength="10" type="text" name="CrmCustomerInfo[cust_contacts]">
        <label class="width-120"><span class="red">*</span>联系电话</label>
        <input class="width-190 easyui-validatebox" data-options="required:'true',validType:'mobile'" type="text" name="CrmCustomerInfo[cust_tel2]">
    </div>
    <div class="mb-20">
        <label class="width-120">邮箱</label>
        <input class="width-190 easyui-validatebox" data-options="validType:'email'" type="text" name="CrmCustomerInfo[cust_email]" maxlength="30" id="cust_email">
        <input type="hidden" name="CrmCustomerInfo[cust_manager]" class="staff_id">
        <label class="width-120">客户经理人</label>
        <input type="text" class="width-190 staff_code" name="code" readonly="readonly" placeholder="请点击选择客户经理人" id="select_manage">
    </div>
    <div class="mb-40">
        <label class="width-120"><span class="red">*</span>详细地址</label>
        <select class="width-80 disName easyui-validatebox" data-options="required:'true'" id="disName_1" >
            <option value="">请选择...</option>
            <?php foreach ($downList['country'] as $key => $val) { ?>
                <option value="<?= $val['district_id'] ?>" <?= count($downList['country']) ? 'selected' : null ?> ><?= $val['district_name'] ?></option>
            <?php } ?>
        </select>
        <select class="width-80 disName easyui-validatebox" data-options="required:'true'" id="disName_2" >
            <option value="">请选择...</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['twoLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>"><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <select class="width-80 disName easyui-validatebox" data-options="required:'true'" id="disName_3" >
            <option value="">请选择...</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['threeLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>"><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <select class="width-80 disName easyui-validatebox" data-options="required:'true'" id="disName_4"  name="CrmCustomerInfo[cust_district_2]">
            <option value="">请选择...</option>
            <?php if(!empty($districtAll)){?>
                <?php foreach($districtAll['fourLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>"><?=$val['district_name']?></option>
                <?php }?>
            <?php }?>
        </select>
        <input class="width-170 easyui-validatebox" data-options="required:'true'" maxlength="60" type="text" name="CrmCustomerInfo[cust_adress]">

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
            if ($("#add-form").form('validate')) {
//                $("#custSalearea").removeAttr('disabled');
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
            }
        })
        /**
         * 验证会员用户名唯一性
         */
//        $("#member_name").blur(function(){
//            $("#member_name").validatebox({
//                required:true,
//                delay:700,
//                validType:"remote['<?//=\yii\helpers\Url::to(['/crm/crm-member/cust-validation'])?>//?name="+$("#member_name").val()+"','code']",
//                invalidMessage:'用户名已存在',
//                missingMessage: '用户名不能为空'
//            })
//        })
        /**
         * 验证客户名称唯一性
         */
//        $("#cust_sname").blur(function(){
//            $("#cust_sname").validatebox({
//                required:true,
//                delay:700,
//                validType:"remote['<?//=\yii\helpers\Url::to(['/crm/crm-member/cust-validation'])?>//?name="+$("#cust_sname").val()+"','code']",
//                invalidMessage:'客户已存在',
//                missingMessage: '客户不能为空'
//            })
//        })
//        $("#cust_shortname").blur(function(){
//            $("#cust_shortname").validatebox({
//                required:true,
//                delay:700,
//                validType:"remote['<?//=\yii\helpers\Url::to(['/crm/crm-member/cust-validation'])?>//?name="+$("#cust_shortname").val()+"','code']",
//                invalidMessage:'客户简称已存在',
//                missingMessage: '客户简称不能为空'
//            })
//        })
        if ($("#disName_1").val() == 1) {
            getNextDistrict($("#disName_1"),"<?=Url::to(['/ptdt/firm/get-district']) ?>","select");
        }
        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select,$url,"select");
        });

        $("#custArea").on("change", function () {
            var id = $(this).val();
            $('.custSalearea').html('<option value="">请选择...</option>');
            $.ajax({
                type: "get",
                dataType: "json",
                data: {"id": id},
                url: "<?= Url::to(['/crm/crm-customer-info/get-district-salearea'])?>",
                success: function (msg) {
                    for(var $i=0;$i < msg.length;$i++){
                        $(".custSalearea").append('<option value="'+ msg[$i].csarea_id +'">'+ msg[$i].csarea_name +'</option>')
                    }
                    if (msg.length == 1) {
                        $(".custSalearea").val(msg[0].csarea_id)
                    }
                },
                error:function () {
                    $(".custSalearea").val('');
                }
            })
        })

        $(".no-padding").on("blur",".staff_code_null",function(){
            if(this.value==''){
                var $parentElem=$(this).parent();
                //职员id
                $parentElem.find(".staff_id").val('');
                //职员姓名
                $parentElem.find(".staff_name").text('');

            }
        });

        //选择客户弹出框
        $("#select_manage").fancybox({
            href:"<?=Url::to(['/crm/crm-customer-info/select-manage'])?>",
            padding: [],
            fitToView: false,
            width: 800,
            height: 570,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
    });
</script>
