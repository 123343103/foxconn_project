<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<div class="crm-customer-info-search" >

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">查询人姓名</label>
                <label>:</label>
                <input type="text" readonly="readonly" value="<?= $staff['staff_name'] ?>" class="qvalue-align" style="width: 100px;">
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">销售角色</label>
                <label>:</label>
                <select name="CrmCustomerInfoSearch[sarole_id]" id="sarole" class="width-100 qvalue-align"  style="width: 115px;">
                    <option value="">请选择...</option>
                    <?php foreach ($result['role'] as $key => $val) {?>
                        <option value="<?=$val['sarole_id'] ?>" <?= (empty($queryParam['CrmCustomerInfoSearch']['sarole_id'])?$result['rr']['sarole_id']:$queryParam['CrmCustomerInfoSearch']['sarole_id'])==$val['sarole_id']?"selected":null ?>><?= $val['sarole_sname'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">人员</label>
                <label>:</label>
                <select name="CrmCustomerInfoSearch[custManager]" id="person" class="width-100 qvalue-align" style="width: 115px;">
                    <option value="">请选择...</option>
                    <?php foreach ($employee as $key => $val) {?>
                        <option value="<?=$val['staff']['id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['custManager'])&&$queryParam['CrmCustomerInfoSearch']['custManager']==$val['staff']['id']?"selected":null ?>><?= $val['staff']['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">营销区域</label>
                <label>:</label>
                <select name="CrmCustomerInfoSearch[cust_salearea]" id="area" class="width-100 qvalue-align" style="width: 110px;">
                    <option value="">请选择...</option>
                    <?php foreach ($result['salearea'] as $key => $val) {?>
                        <option value="<?=$val['csarea_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['cust_salearea'])&&$queryParam['CrmCustomerInfoSearch']['cust_salearea']==$val['csarea_id']?"selected":null ?>><?= $val['csarea_name'] ?></option>
                    <?php } ?>
                </select>
            </div>



         </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">销售点</label>
                <label>:</label>
                <select name="CrmCustomerInfoSearch[sts_id]" id="sts" class="width-100 qvalue-align" style="width: 100px;">
                    <option value="">请选择...</option>
                    <?php foreach ($result['store'] as $key => $val) {?>
                        <option value="<?=$val['sts_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['sts_id'])&&$queryParam['CrmCustomerInfoSearch']['sts_id']==$val['sts_id']?"selected":null ?>><?= $val['sts_sname'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px">所在地区</label>
                <label>:</label>
                <select  name="CrmCustomerInfoSearch[cust_area]" style="width: 115px;" class="qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['province'] as $key => $val) { ?>
                        <option value="<?= $val['district_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['cust_area'])&&$queryParam['CrmCustomerInfoSearch']['cust_area']==$val['district_id']?"selected":null ?> ><?= $val['district_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">客户类型</label>
                <label>:</label>
                <select name="CrmCustomerInfoSearch[cust_type]" class="width-100 qvalue-align" style="width: 115px;">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['customerType'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['cust_type'])&&$queryParam['CrmCustomerInfoSearch']['cust_type']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">认领状态</label>
                <label>:</label>
                <select name="CrmCustomerInfoSearch[personinch_status]" id="personinch" class="width-100 qvalue-align" style="width: 110px;">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['personinch'] as $key => $val) {?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-10 sub', 'type' => 'submit']) ?>
            <?= Html::button('重置', ['class' => 'reset-btn-yellow', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<script>
    $(function(){
        <?php if($queryParam['CrmCustomerInfoSearch']['personinch_status'] === '0'){ ?>
            $('#personinch').val('0');
        <?php }else if($queryParam['CrmCustomerInfoSearch']['personinch_status'] === '10'){ ?>
            $('#personinch').val('10');
        <?php }else{ ?>
            $('#personinch').val();
        <?php } ?>
        <?php if($result['rr']['vdef1'] !=1 && $isSuper == '0'){ ?>
            $('#sarole,#area,#sts').attr('disabled',true);
        <?php } ?>
        $('.sub').click(function(){
            $('#sarole,#area,#sts').attr('disabled',false);
        })

        $('#sarole').change(function(){
            var id = $('#sarole').val();
            $.ajax({
                type:'get',
                data:{id:id},
                dataType:'json',
                url:'<?= Url::to(["get-role-person"]) ?>',
                success:function(data){
                    $("#person").html('<option value="">请选择...</option>');
                    for(var i=0;i<data.length;i++){
                        $('#person').append('<option value="'+ data[i].staff.id +'">'+ data[i].staff.name +'</option>');
                    }
                }

            })
        })
        $('#person').change(function(){
            var id = $('#person').val();
            $.ajax({
                type:'get',
                data:{id:id},
                dataType:'json',
                url:'<?= Url::to(["get-sale-store"]) ?>',
                success:function(data){
                    $('#area').val(data.sale_area);
                    $('#sts').val(data.sts_id);
                }

            })
        })
    })
</script>
