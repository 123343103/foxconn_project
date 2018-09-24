<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/12
 * Time: 上午 08:04
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>
<style>
    .width60{
        width: 60px;
    }
    .width150{
        width: 150px;
    }
</style>

<div class="search-div">
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width qlabel-align width60">销售员</label><label>：</label>
            <input type="text" class="width150" name="CrmEmployeeSearch[keyWord]"
                   value="<?= $queryParam['CrmEmployeeSearch']['keyWord'] ?>" placeholder="工号/姓名">
        </div>

        <div class="inline-block">
            <label class="label-width qlabel-align width60" style="margin-left: 40px">销售角色</label><label>：</label>
            <select name="CrmEmployeeSearch[sarole_id]" class="width150">
                <option value="">全部</option>
                <?php foreach ($downList['roles'] as $key => $val) { ?>
                    <option
                        value="<?= $val['sarole_id'] ?>" <?= isset($queryParam['CrmEmployeeSearch']['sarole_id']) && $queryParam['CrmEmployeeSearch']['sarole_id'] == $val['sarole_id'] ? "selected" : null ?>><?= $val['sarole_sname'] ?></option>
                <?php } ?>
            </select>
        </div>

    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width qlabel-align width60">所在销售点</label><label>：</label>
            <select name="CrmEmployeeSearch[sts_id]"class="width150">
                <option value="">全部</option>
                <?php foreach ($downList['store'] as $key => $val) { ?>
                    <option
                        value="<?= $val['sts_id'] ?>" <?= isset($queryParam['CrmEmployeeSearch']['sts_id']) && $queryParam['CrmEmployeeSearch']['sts_id'] == $val['sts_id'] ? "selected" : null ?>><?= $val['sts_sname'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block ">
            <label class="label-width qlabel-align width60"  style="margin-left: 40px">营销区域</label><label>：</label>
            <select name="CrmEmployeeSearch[sale_area]" class="width150">
                <option value="">全部</option>
                <?php foreach ($downList['saleArea'] as $key => $val) {?>
                    <option value="<?=$val['csarea_id'] ?>" <?= isset($queryParam['CrmEmployeeSearch']['sale_area'])&& $queryParam['CrmEmployeeSearch']['sale_area']==$val['csarea_id']?"selected":null ?>><?= $val['csarea_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-width qlabel-align width60" style="margin-left: 40px">状态</label><label>：</label>
            <select name="CrmEmployeeSearch[sale_status]" class="value-width qvalue-align width150 st" >
                <option value="30">全部</option>
                <option value="20" selected="selected" <?= isset( $queryParam['CrmEmployeeSearch']['sale_status'])&& $queryParam['CrmEmployeeSearch']['sale_status'] == '20' ? "selected":null; ?>>启用</option>
                <option value="10" <?= isset( $queryParam['CrmEmployeeSearch']['sale_status'])&& $queryParam['CrmEmployeeSearch']['sale_status'] == '10' ? "selected":null; ?>>禁用</option>
            </select>
        </div>
        <div class="inline-block">
            <?= Html::submitButton('查询', ['class' => 'button-blue ml-150 search-btn-blue','style'=>'margin-left:30px']) ?>
            <?= Html::button('重置', ['class' => 'button-blue reset-btn-yellow', 'onclick' => 'window.location.href="' . Url::to(['index']) . '"','style'=>'margin-left:12px']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
<script>
    $(function () {
        var st="<?=$queryParam['CrmEmployeeSearch']['sale_status']?>";
        if(st=="30"){
            $('.st').val(30);
        }
    })
</script>
