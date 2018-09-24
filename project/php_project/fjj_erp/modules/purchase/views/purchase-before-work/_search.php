<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/13
 * Time: 上午 08:17
 */
use yii\widgets\ActiveForm;

\app\assets\JeDateAsset::register($this);
$get = Yii::$app->request->get();
if (!isset($get['BsReqDtSearch'])) {
    $get['BsReqDtSearch'] = null;
}
?>
<style>
    .width-170 {
        width: 170px;
    }

    .label-width {
        width: 70px;
    }

    .select-width {
        width: 100px;
    }
</style>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div class="search-div">
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-align label-width">请购单号：</label>
            <input class="width-170" type="text" name="req_no" value="<?= $param['req_no'] ?>">
        </div>
        <div class="inline-block">
            <label class=" label-align label-width">单据类型：</label>
            <select name="req_dct" class="select-width" id="reqdct">
                <?php foreach ($ReqDcts as $key => $val) { ?>
                    <!--Hub料号需求单类型不需要-->
<!--                    --><?php //if ($val['bsp_id'] != '109018') { ?>
                        <option
                            value="<?= $val['bsp_id'] ?>" <?= $param['req_dct'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?>
                        </option>
<!--                    --><?php //} ?>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-align label-width">法人：</label>
            <select name="leg_id" class="select-width" id="legid">
                <?php foreach ($comman as $key => $val) { ?>
                    <option
                        value="<?= $val['company_id'] ?>" <?= $param['leg_id'] == $val['company_id'] ? "selected" : null ?>>
                        <?= $val['company_name'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-align label-width">采购区域：</label>
            <select name="area_id" id="reqarea" class="select-width">
                <?php foreach ($buyaddr as $key => $val) { ?>
                    <option
                        value="<?= $val['factory_id'] ?>" <?= $param['area_id'] == $val['factory_id'] ? "selected" : null ?>><?= $val['factory_name'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align">申请时间：</label>
            <input id="start" name="starttime" value="<?= $param['starttime'] ?>" type="text" class="Wdate"
                   style="width:75px;" readonly="readonly">
            <label>至</label>
            <input id="end" name="endtime" value="<?= $param['endtime'] ?>" type="text" class="Wdate"
                   style="width:75px;" readonly="readonly">
        </div>
        <div class="inline-block">
            <label class="label-align label-width">收货中心：</label>
            <select name="addr" class="select-width">
                <?php foreach ($receipt as $key => $val) { ?>
                    <option
                        value="<?= $val['rcp_no'] ?>" <?= $param['addr'] == $val['rcp_no'] ? "selected" : null ?>>
                        <?= $val['rcp_name'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-align label-width">请购部门：</label>
            <select name="spp_dpt_id" id="sppdpt" class="select-width">
                <option value="">全部</option>
                <?php foreach ($sppdpt as $key => $val) { ?>
                    <option
                        value="<?= $val['organization_id'] ?>" <?= $param['spp_dpt_id'] == $val['organization_id'] ? "selected" : null ?>>
                        <?= $val['organization_name'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-align label-width">请购形式：</label>
            <select name="req_rqf" id="reqrqf" class="select-width">
                <option value="">全部</option>
                <?php foreach ($downList['req_rqf'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>" <?= $param['req_rqf'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <button id="query_btn" type="submit" class="search-btn-blue" style="margin-left:5px;">查询</button>
        <button id="reset_btn" type="submit" class="reset-btn-yellow" style="margin-left:5px;">重置</button>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        $("#start").click(function () {
            WdatePicker({
                skin: "whyGreen",
                maxDate: "#F{$dp.$D('end',{d:-1})}"
            });
        });
        $("#end").click(function () {
            WdatePicker({
                skin: "whyGreen",
                minDate: "#F{$dp.$D('start',{d:1})}"
            });
        });
        $("#reset_btn").click(function () {
            $("input").val("");
            $("#sppdpt").val("");
            $("#reqrqf").val("");
            loadData1();
        });
    });
    function loadData1() {
        $("#data").datagrid('load', {
        }).datagrid('clearSelections').datagrid('clearChecked');
    }
</script>
