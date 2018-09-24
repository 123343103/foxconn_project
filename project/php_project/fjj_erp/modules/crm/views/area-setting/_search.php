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
    .width72{
        width: 50px;
    }
    .width150{
        width: 150px;
    }
</style>
<div class="search-div">
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width qlabel-align width72">区域代码</label><label>：</label>
            <input type="text" class="width150" name="SaleAreaSearch[csarea_code]"
                   value="<?= $queryParam['SaleAreaSearch']['csarea_code'] ?>">
        </div>

        <div class="inline-block">
            <label class="label-width qlabel-align width72" style="margin-left: 40px">区域名称</label><label>：</label>
            <input type="text" class="width150"  name="SaleAreaSearch[csarea_name]"
                   value="<?= $queryParam['SaleAreaSearch']['csarea_name'] ?>">
        </div>
        <div class="inline-block">
            <label class="label-width qlabel-align" style="margin-left: 40px">状态</label><label>：</label>
            <select name="SaleAreaSearch[csarea_status]" class="width150 st">
<!--                --><?php //foreach ($Status as $key => $val) { ?>
<!--                    <option-->
<!--                        value="--><?//= $key ?><!--" --><?//= isset($queryParam['SaleAreaSearch']['csarea_status']) && $queryParam['SaleAreaSearch']['csarea_status'] == $key ? "selected" : null ?><!-->--><?//= $val ?><!--</option>-->
<!--                --><?php //} ?>
                <option value="30" >全部</option>
                <option value="20" selected="selected" <?= isset( $queryParam['SaleAreaSearch']['csarea_status'])&& $queryParam['SaleAreaSearch']['csarea_status'] == '20' ? "selected":null; ?>>启用</option>
                <option value="10" <?= isset( $queryParam['SaleAreaSearch']['csarea_status'])&& $queryParam['SaleAreaSearch']['csarea_status'] == '10' ? "selected":null; ?>>禁用</option>
            </select>
        </div>
        <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue','style'=>'margin-left:20px']) ?>
        <?= Html::button('重置', ['class' => 'reset-btn-yellow', 'onclick' => 'window.location.href="' . Url::to(['index']) . '"']) ?>
    </div>
    <div class="space-20" style="margin-bottom: 20px"></div>
<?php ActiveForm::end(); ?>
    <script>
        $(function () {
            var st="<?=$queryParam['SaleAreaSearch']['csarea_status']?>";
            if(st=="30"){
                $('.st').val(30);
            }
        })
    </script>
