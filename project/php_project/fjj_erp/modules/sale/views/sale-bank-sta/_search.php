<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);

$get = Yii::$app->request->get();
if(!isset($get['BsBankInfoSearch'])){
    $get['BsBankInfoSearch']=null;
}
?>
<style>
    .label-width{
        width: 80px;
    }
    .value-width{
        width: 180px;
    }
</style>
<?php $form = ActiveForm::begin([
    'action' => [Yii::$app->controller->action->id],
    'method' => 'get',
]); ?>
    <div class="search-div">
              <div class="mb-10">
                <label class="label-width">收款状态：</label>
                <select class="value-width" name="BsBankInfoSearch[state]">
                    <option value="">全部</option>
                    <option value="10" <?=$get['BsBankInfoSearch']['state']==10?"selected":null?>>审核中</option>
                    <option value="20" <?=$get['BsBankInfoSearch']['state']==20?"selected":null?>>已审核</option>
                    <option value="30" <?=$get['BsBankInfoSearch']['state']==30?"selected":null?>>已驳回</option>
                    <option value="40" <?=$get['BsBankInfoSearch']['state']==40?"selected":null?>>自动匹配</option>
                </select>
                <label class="label-width">收款账号：</label>
                <select name="BsBankInfoSearch[ACCOUNTS]" class="value-width">
                    <option value="">全部</option>
                    <?php foreach ($downList["accounts"] as $key => $val) { ?>
                        <option
                            value="<?= $val["ACCOUNTS"] ?>" <?= isset($queryParam['ACCOUNTS']) && $queryParam['ACCOUNTS'] == $val["ACCOUNTS"] ? "selected" : null ?>><?= $val["ACCOUNTS"] ?></option>
                    <?php } ?>
                </select>
                <label class="label-width">流水账号：</label>
                <input type="text" name="BsBankInfoSearch[TRANSID]" class="value-width"
                       value="<?= $get['BsBankInfoSearch']['TRANSID']?>"/>
                <label style="margin-left: 10px;"><input type="checkbox" style="vertical-align:-8px;" name="BsBankInfoSearch[checkorder]" id="checkorder" <?= isset($queryParam['checkorder']) && $queryParam['checkorder'] == 'on' ? "checked" : null ?>></label><label>未绑定订单</label>
            </div>
            <div class="mb-10">
                <label class="label-width">订单编号：</label>
                <input type="text" class="value-width" name="BsBankInfoSearch[order_no]" value="<?=$get['BsBankInfoSearch']['order_no']?>">
                <label class="label-width">收款法人：</label>
                <select name="BsBankInfoSearch[CORP_DESC]" class="value-width">
                    <option value="">全部</option>
                    <?php foreach ($downList["corpDesc"] as $key => $val) { ?>
                        <option value="<?= $val["CORP_DESC"] ?>" <?= isset($queryParam['CORP_DESC']) && $queryParam['CORP_DESC'] == $val["CORP_DESC"] ? "selected" : null ?>><?= $val["CORP_DESC"] ?></option>
                    <?php } ?>
                </select>
                <label class="label-width">收款日期：</label>
                <input type="text" id="startDate" class="Wdate value-align " style="width: 80px;"
                       onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd', maxDate: '%y-%M-%d %H:%m' })"
                       name="BsBankInfoSearch[startDate]"
                       readonly="readonly"
                       value="<?= $get['BsBankInfoSearch']['startDate'] ?>"
                >
                 <label>至</label>
                <input type="text" id="endDate" class="Wdate" style="width: 80px;"
                       name="BsBankInfoSearch[endDate]"
                       readonly="readonly"
                       onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startDate\');}',maxDate:'%y-%M-%d %H:%m' })"
                       value="<?= $get['BsBankInfoSearch']['endDate'] ?>"
                >
                <button type="submit" class="search-btn-blue" style="margin-left: 10px;">查询</button>
                <button type="reset" class="reset-btn-yellow" id="reset">重置</button>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
    <div class="space-10"></div>
    <div class="space-10"></div>