<?php
use yii\widgets\ActiveForm;

\app\assets\JeDateAsset::register($this);
?>
<style>
    .width100 {
        width: 200px;
    }

    .ml-20 {
        margin-left: 20px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    .width80 {
        width: 80px;
    }
</style>
<div class="no-padding ">
    <div class="pop-head mb-10">
        <p>修改比例设置</p>
    </div>
    <?php $form = ActiveForm::begin(['id' => 'update-form']); ?>
    <div class="mb-10">
        <label class="label-width qlabel-align  ml-20 width80"><span style="color: red">&nbsp;*&nbsp;</span>单据类型<label>：</label></label>
        <select disabled class="value-width value-align easyui-validatebox width100" data-options="required:'true'"
                name="BsRatio[ratio_type]">
            <option value="">全部</option>
            <?php foreach ($downlist['type'] as $key => $val) { ?>
                <option value="<?= $val['bsp_id'] ?>" <?php if($sql["vl"][0]["ratio_type"]==$val['bsp_id']){ echo selected;}?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align ml-20 width80"><span style="color: red">&nbsp;*&nbsp;</span>比例下限<label>：</label></label>
        <input id="low" type="text" class="width100 qvalue-align value-width easyui-validatebox" data-options="required:'true'" value="<?php $num=$sql['vl'][0]['low_num'];if($num!=""){$num=$num*100;echo $num;}?>">
        <input id="lowt" type="hidden" class="width100 qvalue-align value-width" name="BsRatio[low_num]" value="<?=$sql['vl'][0]['low_num']?>">
        <label>%</label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align ml-20 width80"><span style="color: red">&nbsp;*&nbsp;</span>比例上限<label>：</label></label>
        <input id="upp" type="text" class="width100 qvalue-align value-width easyui-validatebox" data-options="required:'true'" value="<?php $num=$sql['vl'][0]['upp_num'];if($num!=""){$num=$num*100;echo $num;}?>">
        <input id="uppt" type="hidden" class="width100 qvalue-align value-width" name="BsRatio[upp_num]" value="<?=$sql['vl'][0]['upp_num']?>">
        <label>%</label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align ml-20 width80" style="float: left">备注<label>：</label></label>
        <textarea name="BsRatio[remark]" placeholder="最多输入200字" class="qvalue-align value-width" style="width: 350px;height: 60px;margin-left: 5px" maxlength="200"><?=$sql['vl'][0]['remark']?></textarea>
    </div>
    <input type="hidden" value="<?php date_default_timezone_set('prc');  echo date("y-m-d H:i:s",time());?>" name="BsRatio[opp_date]">
    <input type="hidden" value="<?=Yii::$app->ftpPath["ftpIP"]?>" name="BsRatio[opp_ip]">
    <div style="text-align: center;margin-top: 50px">
        <button  class="button-blue-big" type="submit">保存</button>
        <button id="cancel" class="button-white-big" type="button">返回</button>
    </div>
    <?php ActiveForm::end() ?>
    <script>
        $(function () {
            //表单提交
            ajaxSubmitForm($("#update-form"));
            //比例下限限制
            $('#low').numbervalid();
            $("#upp").numbervalid();
            $("#low").change(function () {
                var s=$("#low").val();
                if (s!="") {
                    if (s < 0 || s > 20) {
                        layer.alert("请输入0-20之间的数", {icon: 2});
                        $("#low").val("");
                    }
                    else $("#low").val((s - 0).toFixed(2));
                    $("#lowt").val(((s-0)/ 100).toFixed(4));
                }
            });
            //比例上限限制
            $("#upp").change(function () {
                var s=$("#upp").val();
                if(s!="") {
                    if (s < 0 || s > 30) {
                        layer.alert("请输入0-30之间的数", {icon: 2});
                        $("#upp").val("");
                    }
                    else $("#upp").val((s - 0).toFixed(2));
                    $("#uppt").val(((s-0) / 100).toFixed(4));
                }
            });
            //返回
            $("#cancel").click(function () {
                parent.$.fancybox.close();
            })
        })
    </script>
</div>
