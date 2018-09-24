<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
?>
<style>
    .select2-selection {
        width: 578px; /*分級分類輸入框寬度*/
        height: auto; /*分級分類輸入框高度樣式*/
        overflow: hidden;
    }
    .width50{
        width: 50px;
    }
    .width200{
        width: 200px;
    }
    .width180{
        width: 180px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .width100{
        width: 100px;
    }
    .width150{
        width: 150px;
    }
    .width220{
        width: 220px;
    }
    .width250{
        width: 250px;
    }
</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
    <div class="space-20"></div>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <h2 class="head-second">
        销售员基本信息
    </h2>
    <div class="mb-10">
        <label class="label-width qlabel-align width50 ml-20"><span class="red">*</span>工号<label>：</label></label>
        <input name="CrmEmployee[staff_code]" class="width200  easyui-validatebox staff_code"
               data-options="required:true,validType:['unique','check_staff'],delay:10000,validateOnBlur:'true'" data-act="<?= Url::to(['validate']) ?>" data-url="<?= Url::to(['get-staff-info']) ?>"
               data-attr="staff_code" data-id="<?= $model['staff_id']; ?>" data-scenario="create" type="text"
               value="<?= $model['staff_code'] ?>" maxlength="50">
        <lable class="label-width text-left width200 code" style="display:none" ><?= $model['staff_code'] ?></lable>
        <label class="label-width qlabel-align width250">姓名<label>：</label></label>
        <lable class="label-width text-left width200 staff_name" style="display: inline-block"><?= $model['staff']['name'] ?></lable>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width50 ml-20">资位<label>：</label></label>
        <lable class="label-width text-left width200 job_level" style="display: inline-block"><?= $model['staff']['job_level'] ?></lable>
        <label class="label-width qlabel-align width250">联系电话<label>：</label></label>
        <lable class="label-width text-left width200 staff_tel" style="display: inline-block"><?= $model['staff']['tel'] ?></lable>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width50 ml-20">邮箱<label>：</label></label>
        <lable class="label-width text-left width200 staff_email" style="display: inline-block"><?= $model['staff']['email'] ?></lable>
        <label class="label-width qlabel-align width250">所在部门<label>：</label></label>
        <lable class="label-width text-left width200 organization_code" style="display: inline-block"><?= $model['staff']['organization_name'] ?></lable>
    </div>
    <h2 class="head-second">
        销售员详细资料
    </h2>
    <div class="mb-10">
        <label class="width100"><span class="red">*</span>销售角色<label>：</label></label>
        <select class="width200 easyui-validatebox sale_role" type="text" name="CrmEmployee[sarole_id]"
                data-options="required:'true'">
            <option value="">请选择...</option>
            <?php foreach ($downList['roles'] as $key => $val) { ?>
                <option
                    value="<?= $val['sarole_id'] ?>" <?= $model['sarole_id'] == $val['sarole_id'] ? "selected" : null ?>><?= $val['sarole_sname'] ?></option>
            <?php } ?>
        </select>
        <label class="width220">人力类型<label>：</label></label>
        <label class="hr_type"></label>
    </div>
    <div class="mb-10">
        <label class="width100">所在营销区域<label>：</label></label>
        <select class="width200" id="saleArea" type="text" name="CrmEmployee[sale_area]">
            <option value="">请选择...</option>
            <?php foreach ($downList['saleArea'] as $key => $val) { ?>
                <option
                    value="<?= $val['csarea_id'] ?>" <?= isset($model['sale_area']) && $model['sale_area'] == $val['csarea_id'] ? "selected" : null ?>><?= $val['csarea_name'] ?></option>
            <?php } ?>
        </select>
        <label class="width220">所在销售点<label>：</label></label>
        <select class="width200" id="saleStore" type="text" name="CrmEmployee[sts_id]">
            <option value="">请选择...</option>
                <?php foreach ($downList['store']  as $key => $val) { ?>
                    <option
                        value="<?= $val['sts_id'] ?>" <?= isset($model['sts_id']) && $model['sts_id'] == $val['sts_id'] ? "selected" : null ?>><?= $val['sts_sname'] ?></option>
                <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="width100">对应店长<label>：</label></label>
        <input class="width200 super_id" type="hidden" name="CrmEmployee[sts_superior]" style="display: none"
               value="<?= $model['sts_superior'] ?>"/>
        <lable class="label-width text-left width200 super_code" style="display: inline-block;"><?= $model['dz_name'] ?></lable>
<!--        <input class="width200 super_code" type="text" value="--><?//= $model['dz_name'] ?><!--" readonly="readonly" style="display: none">-->
<!--        <lable class="label-width text-left width200 super_code" name="CrmEmployee[sts_superior]" style="display: inline-block">--><?//= $model['dz_name'] ?><!--</lable>-->
        <label class="width220">对应省长<label>：</label></label>
<!--        <lable class="label-width text-left width200 boss_id"  name="CrmEmployee[sts_boss]" style="display: inline-block;display: none">--><?//= $model['sts_boss'] ?><!--</lable>-->
        <input class="width200 boss_id" type="hidden" name="CrmEmployee[sts_boss]" value="<?= $model['sts_boss'] ?>">
<!--        <input class="width200 boss_code" type="text" value="--><?//= $model['sz_name'] ?><!--" readonly="readonly" style="border: none;background-color: white">-->
        <lable class="label-width text-left width200 boss_code"  style="display: inline-block"><?= $model['sz_name'] ?></lable>

    </div>
    <div class="mb-10">
        <label class="width100">直属上司<label>：</label></label>
        <select class="width200 leader" type="text" name="CrmEmployee[leader_id]">
            <option value="">请选择...</option>
            <?php foreach ($downList['leader'] as $key => $val) { ?>
                <option
                    value="<?= $val['staff_id'] ?>" <?= isset($model['leader_id']) && $model['leader_id'] == $val['staff_id'] ? "selected" : null ?>><?= $val['staff_name'] ?></option>
            <?php } ?>
        </select>
        <label class="width220">上司角色<label>：</label></label>
<!--        <lable class="label-width text-left width200 leaderrole_id"  name="CrmEmployee[leaderrole_id]" style="display: inline-block;display: none">--><?//= $model['leaderrole_id'] ?><!--</lable>-->
        <input class="width200 leaderrole_id" type="hidden" name="CrmEmployee[leaderrole_id]"
               value="<?= $model['leaderrole_id'] ?>">
<!--        <input class="width200 leader_role" type="text" readonly="readonly" value="--><?//= $model['lerole_sname'] ?><!--" style="border: none;background-color: white">-->
        <lable class="label-width text-left width200 leader_role"  style="display: inline-block"><?= $model['lerole_sname'] ?></lable>
    </div>
    <div class="mb-10">
        <label class="width150">个人销售提成系数<label>：</label></label>
        <input class="width150 easyui-validatebox sale_qty" type="text" name="CrmEmployee[sale_qty]"
               value="<?= $model['sale_qty'] ?>" data-options="validType:'two_percent'" maxlength="9"><span class="pl-10">%</span>
        <label style="width: 209px">个人销售目标指数<label>：</label></label>
        <input class="width200 easyui-validatebox sale_quota" type="text" name="CrmEmployee[sale_quota]"
               value="<?= $model['sale_quota'] ?>" data-options="validType:'two_decimal'"   maxlength="9"></span>
    </div>
    <div class="mb-10">
        <label class="width150 vertical-top">允许销售商品类别<label>：</label></label>
        <?php echo Select2::widget([
            'name' => 'CrmEmployee[category_id]',
            'id' => 'category_id',
            'value' => unserialize($model['category_id']),
            'data' => ArrayHelper::map($category, 'catg_no', 'catg_name'),
            'options' => ['placeholder' => '请选择..', 'multiple' => true],
            'pluginOptions' => [
                'tags' => true,
                'maximumInputLength' => 20,
                'allowClear' => true,
            ],
        ]); ?>
    </div>

    <div class="mb-10 overflow-auto">
        <label class="width150 float-left"><span class="red">*</span>是否为客户经理人<label>：</label></label>
        <div class="width200 float-left pl-8">
            <input type="radio" value="1" class="easyui-validatebox" data-options="required:'true'"
                   name="CrmEmployee[isrule]" <?= $model['isrule'] == 1 ? "checked=checked" : null; ?>>
            <span class="vertical-middle">是</span>
            <input type="radio" value="0" class="easyui-validatebox" data-options="required:'true'"
                   name="CrmEmployee[isrule]" <?= $model['isrule'] == 0 ? "checked=checked" : null; ?>>
            <span class="vertical-middle">否</span>
        </div>

        <label style="width: 178px"><span class="red">*</span>销售员状态<label>：</label></label>
        <select class="width200 easyui-validatebox" type="text" name="CrmEmployee[sale_status]"
                data-options="required:'true'">
<!--            --><?php //foreach ($status as $key => $val) { ?>
<!--                <option-->
<!--                        value="--><?//= $key ?><!--" --><?//= isset($model['sale_status']) && $model['sale_status'] == $key ? "selected" : null ?><!-->--><?//= $val ?><!--</option>-->
<!--            --><?php //} ?>
            <option value="20" <?= isset( $model['sale_status'])&& $model['sale_status'] == '20' ? "selected":null; ?>>启用</option>
            <option value="10" <?= isset( $model['sale_status'])&&$model['sale_status'] == '10' ? "selected":null; ?>>禁用</option>
        </select>
    </div>
    <div style="margin-top: 40px"></div>
    <div class="text-center">
        <button type="submit" class="button-blue-big">保存</button>
        <button class="button-white-big" onclick="history.go(-1);" type="button">返回</button>
    </div>
    <?php ActiveForm::end() ?>
</div>

<script>
    $(function () {
        ajaxSubmitForm($("#add-form"));

        $(".staff_code").on("change", function () {
            var code = $(this).val();
            var url = "<?= Url::to(['get-staff-info'])?>";
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {"code": code},
                url: url,
                success: function (data) {
                    if (data[0] == null) {
                        $('.staff_code').addClass('validatebox-invalid');
                        //$(".staff_name").val("");
                        $(".staff_name").text("");
                        $(".staff_tel").text("");
                        $(".organization_code").text("");
                        $(".job_level").text("");
                        $(".staff_id").text("");
                    } else {
                        $(".staff_name").text(data[0].staff_name);
                        $(".staff_tel").text(data[0].staff_tel);
                        $(".staff_email").text(data[0].staff_email);
                        $(".organization_code").text(data[0].organization_name);
                        $(".job_level").text(data[0].job_level);
                        $(".staff_id").text(data[0].staff_id);
                    }
                },
                error: function (data) {
                    $('.staff_code').addClass('validatebox-invalid');
                }
            })
        });
        $('#saleArea').on("change", function () {
            var id = $(this).val();
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {"id": id},
                url: "<?= Url::to(['store'])?>",
                success: function (data) {
                    if ($("#saleStore").length != 0) {
                        $("#saleStore").html("<option value=''>请选择...</option>");
                        for (var i = 0; i < data.length; i++) {
                            $("#saleStore").append('<option value="' + data[i].sts_id + '" <?= isset($model['sts_id']) && $model['sts_id'] == $val['sts_id'] ? "selected" : null ?> >' + data[i].sts_sname + '</option>')
                        }
                    }
                }
            })
        });
        //销售点带出上司省长
        $("#saleStore").on('change', function () {
            var id = $(this).val();
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {"id": id},
                url: "<?= Url::to(['store-info'])?>",
                success: function (data) {
//                    console.log(data);
                    $('.super_id').val(data.dz_staff_id);
                    $('.super_code').text(data.dz);
                    $('.boss_id').val(data.sz_staff_id);
                    $('.boss_code').text(data.sz);
                },
            })
        });
        var id=$('.sale_role').val();
        if(id!=""){
            getbspvalue(id);//加载人力类型
        }
        function getbspvalue(id){
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {"id": id},
                url: "<?= Url::to(['sale-role'])?>",
                success: function (data) {
                    $('.hr_type').text(data.bsp_svalue);
                },
            })
        }
        //销售角色
        $('.sale_role').on("change", function () {
            var id = $(this).val();
            getbspvalue(id);//加载人力类型
        });

        //上司角色
        $('.leader').on('change', function () {
            var id = $(this).val();
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {"id": id},
                url: "<?= Url::to(['leader-role'])?>",
                success: function (data) {
//                    console.log(data);
                    $('.leaderrole_id').val(data.sarole_id);
                    $('.leader_role').text(data.sarole_sname);
                },
            })
        })
        $(".sale_qty").numbervalid(7);
        $(".sale_quota").numbervalid(2);
        var code=$(".staff_code").val();
        if(code!=""&&code!=null){
            $(".staff_code").css("display","none");
            $(".code").css("display","inline-block");
        }
//        $(".sale_quota").bind("keyup",function(){
//            $(".sale_quota").val($(".sale_quota").val().replace(/[^\-?\d.]/g,''));
//        });
    });
</script>