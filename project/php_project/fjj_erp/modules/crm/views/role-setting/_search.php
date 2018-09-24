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
<style>
    .width72{
        width: 72px;
    }
    .width150{
        width: 150px;
    }
</style>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>

    <div class="search-div">
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align width72">销售角色</label><label>：</label>
                <input type="text" class="width150" name="CrmSaleRolesSearch[sarole_sname]"
                       value="<?= $queryParam['CrmSaleRolesSearch']['sarole_sname'] ?>" placeholder="角色编码/名称">
            </div>

            <div class="inline-block">
                <label class="label-width qlabel-align width72" style="margin-left: 40px">销售人力类型</label><label>：</label>
                <select name="CrmSaleRolesSearch[sarole_type]" class="width150">
                    <option value="">全部</option>
                    <?php if (!empty($employeeType)) { ?>
                        <?php foreach ($employeeType as $key => $val) { ?>
                            <option
                                value="<?= $val["bsp_id"] ?>" <?= isset($queryParam['CrmSaleRolesSearch']['sarole_type']) && $queryParam['CrmSaleRolesSearch']['sarole_type'] == $val["bsp_id"] ? "selected" : null ?>><?= $val["bsp_svalue"] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align" style="margin-left: 40px">状态</label><label>：</label>
                <select name="CrmSaleRolesSearch[sarole_status]"class="width150 st">
                    <option value="30">全部</option>
                    <option value="20" selected="selected" <?= isset( $queryParam['CrmSaleRolesSearch']['sarole_status'])&& $queryParam['CrmSaleRolesSearch']['sarole_status'] == '20' ? "selected":null; ?>>启用</option>
                    <option value="10" <?= isset( $queryParam['CrmSaleRolesSearch']['sarole_status'])&& $queryParam['CrmSaleRolesSearch']['sarole_status'] == '10' ? "selected":null; ?>>禁用</option>
                </select>
            </div>
            <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue','style'=>'margin-left:20px']) ?>
            <?= Html::button('重置', ['class' => 'button-blue reset-btn-yellow', 'onclick' => 'window.location.href="' . Url::to(['index']) . '"']) ?>
        </div>


    <div class="space-20"></div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        var st="<?=$queryParam['CrmSaleRolesSearch']['sarole_status']?>";
        if(st=="30"){
            $('.st').val(30);
        }
    })
</script>
