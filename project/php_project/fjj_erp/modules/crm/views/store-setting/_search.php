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

$get = Yii::$app->request->get();
if (!isset($get['StoreSettingSearch'])) {
    $get['StoreSettingSearch'] = null;
}
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
            <label class="label-width qlabel-align width72">销售点</label><label>：</label>
            <input type="text" class="width150" name="StoreSettingSearch[sts_code]"
                   value="<?= $get['StoreSettingSearch']['sts_code'] ?>" placeholder="销售点代码/名称">
        </div>

        <div class="inline-block">
            <label class="label-width qlabel-align width72" style="margin-left: 40px">营销区域</label><label>：</label>
            <select name="StoreSettingSearch[csarea_id]" class="width150">
                <option value="">全部</option>
                <?php foreach ($saleArea as $key => $val) { ?>
                    <option
                        value="<?= $val['csarea_id'] ?>" <?= isset($get['StoreSettingSearch']['csarea_id']) && $get['StoreSettingSearch']['csarea_id'] == $val['csarea_id'] ? "selected" : null ?>><?= $val['csarea_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-width qlabel-align" style="margin-left: 40px">状态</label><label>：</label>
            <select name="StoreSettingSearch[sts_status]" class="width150 st">
                <option value="30">全部</option>
                <option value="10" selected="selected" <?= isset( $get['StoreSettingSearch']['sts_status'])&& $get['StoreSettingSearch']['sts_status']== '10' ? "selected":null; ?>>营业中</option>
                <option value="11" <?= isset( $get['StoreSettingSearch']['sts_status'])&& $get['StoreSettingSearch']['sts_status'] == '11' ? "selected":null; ?>>筹备中</option>
                <option value="14" <?= isset( $get['StoreSettingSearch']['sts_status'])&& $get['StoreSettingSearch']['sts_status'] == '14' ? "selected":null; ?>>已暂停</option>
                <option value="13" <?= isset( $get['StoreSettingSearch']['sts_status'])&& $get['StoreSettingSearch']['sts_status'] == '13' ? "selected":null; ?>>已歇业</option>
                <option value="15" <?= isset( $get['StoreSettingSearch']['sts_status'])&&$get['StoreSettingSearch']['sts_status'] == '15' ? "selected":null; ?>>已关闭</option>

<!--                --><?php //if (!empty($status["storeStatus"])) { ?>
<!--                    --><?php //foreach ($status["storeStatus"] as $key => $val) { ?>
<!--                        <option-->
<!--                            value="--><?//= $key ?><!--" --><?//= isset($get['StoreSettingSearch']['sts_status']) && $get['StoreSettingSearch']['sts_status'] == $key ? "selected" : null ?><!-->--><?//= $val ?><!--</option>-->
<!--                    --><?php //} ?>
<!--                --><?php //} ?>
            </select>
        </div>
        <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue','style'=>'margin-left:20px']) ?>
        <?= Html::button('重置', ['class' => 'button-blue reset-btn-yellow', 'onclick' => 'window.location.href="' . Url::to(['index']) . '"']) ?>
    </div>

<?php ActiveForm::end(); ?>
    <script>
        $(function () {
            var st="<?=$get['StoreSettingSearch']['sts_status']?>";
            if(st=="30"){
                $('.st').val(30);
            }
        })
    </script>

