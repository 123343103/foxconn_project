<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/29
 * Time: 10:01
 */
use \yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div>
    <?php $form = ActiveForm::begin([
        'id' => 'person-inch',
    ]) ?>
    <h2 class="head-first">修改认领信息</h2>

    <div class="ml-30">
        <div class="mb-20">
            <input type="hidden" name="cust_id" value="<?= $id ?>">
            <div class="mb-20">
                <label class="width-100">客户经理人</label>
                <select name="CrmCustPersoninch[ccpich_personid]" id="personid" class="width-200">
                    <option value="">请选择...</option>
                    <?php foreach($downList['manager'] as $key => $value){ ?>
                        <option value="<?= $value['staffName']['staff_id'] ?>" <?= $model['ccpich_personid'] == $value['staffName']['staff_id'] ? "selected" : null;?>><?= $value['staffName']['staff_code'] ?>--<?= $value['staffName']['staff_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <label class="width-100">所属军区</label>
            <select class="width-200 salearea" name="CrmCustPersoninch[csarea_id]" >
                <option value="">请选择...</option>
                <?php foreach ($downList['salearea'] as $key => $val) { ?>
                    <option value="<?= $val['csarea_id'] ?>"<?= $model['csarea_id'] == $val['csarea_id'] ? "selected" : null; ?>><?= $val['csarea_name'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-20">
            <label class="width-100" for="">销售点</label>
            <select name="CrmCustPersoninch[sts_id]" class="width-200 stsId">
                <option value="">请选择...</option>
                <?php foreach($downList['storeinfo'] as $key => $value){ ?>
                    <option value="<?= $value['sts_id'] ?>"<?= $model['sts_id'] == $value['sts_id']?"selected":null ?>><?= $value['sts_sname'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-20">
            <label class="width-100">销售代表</label>
            <select name="CrmCustPersoninch[ccpich_personid2]" id="personid2" class="width-200">
                <option value="">请选择...</option>
                <?php foreach($sales as $key => $value){ ?>
                    <option value="<?= $value['staffName']['staff_id'] ?>" <?= $model['ccpich_personid2'] == $value['staffName']['staff_id'] ? "selected" : null;?>><?= $value['staffName']['staff_code'] ?>--<?= $value['staffName']['staff_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="space-20"></div>
        <?php if ($status == 0) { ?>
            <div class="text-center">
                <button class="button-blue-big" type="submit" id="personinch-add">确定</button>
                <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
            </div>
        <?php } ?>
        <?php if ($status == 10) { ?>
            <div class="mb-20 text-center">
                <button class="button-blue-big" type="submit" id="personinch-cancle">取消认领</button>
                <button class="button-blue-big" type="submit" id="personinch-edit">确定</button>
                <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
            </div>
        <?php } ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
<script>
    $("#personinch-add").one("click", function () {
        $("#person-inch").attr('action', '<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/update-person-inch', 'id' => $id,'status'=>$status]) ?>');
        return ajaxSubmitForm($("#person-inch"));
    });
    $("#personinch-edit").one("click", function () {
        $("#person-inch").attr('action', '<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/update-person-inch', 'id' => $id,'ccpichId'=>$model['ccpich_id'],'status'=>$status]) ?>');
        return ajaxSubmitForm($("#person-inch"));
    });
    $("#personinch-cancle").one("click", function () {
        $("#person-inch").attr('action', '<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/cancle-person-inch', 'id' => $id,'ccpichId'=>$model['ccpich_id']]) ?>');
        return ajaxSubmitForm($("#person-inch"));
    });
    $(function(){
        $("#personid").on("change",function(){
            var id = $("#personid").val();
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {"id": id},
                url: "<?= Url::to(['/crm/crm-customer-info/get-manager-staff-info'])?>",
                success: function (data) {
                    $(".salearea").val(data.salearea.csareaId);
                    $(".stsId").val(data.storeInfo.sts_id);
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        data: {"leaderId": data.staff_id},
                        url: "<?= Url::to(['/crm/crm-customer-info/get-sale-staff-info'])?>",
                        success:function(msg){
//                            console.log(msg);
                            $("#personid2").html("<option>请选择...</option>");
                            for (var i=0;i<msg.length;i++) {
//                                console.log(msg[i]);
                                $("#personid2").append('<option value="' + msg[i].staffName.staff_id + '" >' + msg[i].staff_code + '--' + msg[i].staffName.staff_name + '</option>')
                            }
                        }
                    })
                }
            });

        });
    });
</script>