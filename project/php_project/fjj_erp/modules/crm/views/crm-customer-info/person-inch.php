<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/29
 * Time: 10:01
 */
use \yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
?>
<style>
    .select2-selection{
        width: 200px;/*分級分類輸入框寬度*/
        height: auto;/*分級分類輸入框高度樣式*/
    }
    .label-width{
        width:120px;
    }
    .value-width{
        width: 200px;
    }
</style>
<div>
    <?php $form = ActiveForm::begin([
        'id' => 'person-inch'
    ]) ?>
    <?php if(($status == 10 && $ml == 1) && !$isSuper){ ?>
        <h2 class="head-first">取消认领信息</h2>
    <?php }else{ ?>
        <h2 class="head-first">添加认领信息</h2>
    <?php } ?>

    <div class="ml-30">
        <div class="mb-20">
            <input type="hidden" id="customers" name="customers" value="<?=\Yii::$app->request->get('customers')?>">
            <div class="mb-10">
                <label class="label-width label-align">客户经理人：</label>
                <select name="CrmCustPersoninch[ccpich_personid]" id="personid" class="value-width value-align" disabled="disabled">
                    <option value="">请选择...</option>
                    <?php foreach($downList['manager'] as $key => $value){ ?>
                        <?php if($employee['staff']['id'] == $value['staff_id'] && !$isSuper){ ?>
                            <option selected value="<?= $value['staff_id'] ?>"><?= $value['staff_code'] ?>--<?= $value['staff_name'] ?></option>
                        <?php }else{ ?>
                            <option value="<?= $value['staff_id'] ?>"><?= $value['staff_code'] ?>--<?= $value['staff_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <label class="label-width label-align">所属军区：</label>
            <select class="value-width value-align salearea" name="CrmCustPersoninch[csarea_id]"  disabled="disabled">
                <option value="">请选择...</option>
                <?php foreach ($downList['salearea'] as $key => $val) { ?>
                    <option value="<?= $val['csarea_id'] ?>"<?= (!empty($model['csarea_id'])?$model['csarea_id']:$employee['sale_area']) == $val['csarea_id'] ? "selected" : null; ?>><?= $val['csarea_name'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-10">
            <label class="label-width label-align" for="">销售点：</label>
            <select name="CrmCustPersoninch[sts_id]" class="value-width value-align stsId" disabled="disabled">
                <option value="">请选择...</option>
                <?php foreach($downList['storeinfo'] as $key => $value){ ?>
                    <option value="<?= $value['sts_id'] ?>"<?= (!empty($model['sts_id'])?$model['sts_id']:$employee['sts_id']) == $value['sts_id']?"selected":null ?>><?= $value['sts_sname'] ?></option>
                <?php } ?>
            </select>
        </div>

        <?php if ($status == 0 ||($status == 10 && $ml == 0)) { ?>
            <div class="mb-10">
                <label class="label-width label-align">销售代表：</label>
                <select name="CrmCustPersoninch[ccpich_personid2]" id="personid2" class="value-width value-align">
                    <option value="">请选择...</option>
                    <?php foreach($sales as $key => $value){ ?>
                        <option value="<?= $value['staff']['id'] ?>" <?= $model['ccpich_personid2'] == $value['staff']['id'] ? "selected" : null;?>><?= $value['staff_code'] ?>--<?= $value['staff']['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        <?php } else if (($status == 10 && $ml == 1)) { ?>
            <div class="mb-10">
                <label class="label-width label-align">销售代表：</label>
                <select name="CrmCustPersoninch[ccpich_personid2]" id="personid2" class="value-width value-align" disabled="disabled">
                    <option value="<?= $sales['staff_id'] ?>" ><?= $sales['staff_code'] ?>--<?= $sales['staff_name'] ?></option>
                </select>
            </div>
        <?php } ?>
        <div class="space-20"></div>
        <?php if($isSuper){ ?>
            <div class="text-center">
                <button class="button-blue-big" type="button" id="personinch-add">确定</button>
                <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
            </div>
        <?php }else{ ?>
            <?php if ($status == 0 ||($status == 10 && $ml == 0)) { ?>
                <div class="text-center">
                    <button class="button-blue-big" type="button" id="personinch-add">确定</button>
                    <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
                </div>
            <?php } ?>
            <?php if ($status == 10 && $ml == 1) { ?>
                <div class="mb-20 text-center">
                    <button class="button-blue-big" type="button" id="personinch-cancle">取消认领</button>
                    <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
                </div>
            <?php } ?>
        <?php } ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
<script>

    $("#personinch-add").one("click", function () {
        $(".stsId,.salearea,#personid,#personid2").attr('disabled',false);
        $.ajax({
            type:'post',
            dataType:'json',
            data:$("#person-inch").serialize(),
            url:'<?= Url::to(['/crm/crm-customer-info/update-person-inch', 'id' => $id,'status'=>$status]) ?> ',
            success:function(data){
                if(data.flag == 1){
                    layer.alert(data.msg,{icon:1,end: function () {
                        parent.window.location.reload();
                        }
                    });
                }else {
                    layer.alert(data.msg, {icon: 2,time:5000})
                }
            },
            error: function (data) {
                layer.alert(data.msg, {icon: 2})
            }
        })
    });
    $("#personinch-cancle").one("click", function () {
        $("#personid").attr('disabled',false);
        $.ajax({
            type:'post',
            dataType:'json',
            url:'<?= Url::to(['/crm/crm-customer-info/cancle-person-inch']) ?> ',
            data:$("#person-inch").serialize(),
            success:function(data){
                if(data.flag == 1){
                    layer.alert(data.msg,{icon:1,end: function () {
                        parent.window.location.reload();
                    }
                    });
                }else {
                    layer.alert(data.msg, {icon: 2,time:5000})
                }
            },
            error: function (data) {
                layer.alert(data.msg, {icon: 2})
            }
        })
    });
    $(function(){
        <?php if($isSuper==1){ ?>
            $('#personid,#personid2').attr('disabled',false);
        <?php }else{ ?>
            loader();
        <?php } ?>
        $("#personid").on("change",function(){
            loader();
        });
    });

    function loader(){
        var id = $("#personid").val();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"id": id},
            url: "<?= Url::to(['/crm/crm-customer-info/get-manager-staff-info'])?>",
            success: function (data) {
//                    console.log(data);return false;
                $(".salearea").val(data.csarea_id);
                $(".stsId").val(data.sts_id);
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    data: {"leaderId": data.staff_id},
                    url: "<?= Url::to(['/crm/crm-customer-info/get-sale-staff-info'])?>",
                    success:function(msg){
                        $("#personid2").html("<option>请选择...</option><option selected value='"+ data.staff.id +"'>"+ data.staff_code + '--' +data.staff.name +"</option>");
                        for (var i=0;i<msg.length;i++) {
                            $("#personid2").append('<option value="' + msg[i].staffName.staff_id + '" >' + msg[i].staff_code + '--' + msg[i].staffName.staff_name + '</option>')
                        }
                    }
                })
            }
        });
    }
</script>