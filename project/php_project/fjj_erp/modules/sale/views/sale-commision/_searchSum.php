<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/2/11
 * Time: 下午 02:12
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$get = Yii::$app->request->get();
if (!isset($get['SaleDetailsSumSearch'])) {
    $get['SaleDetailsSumSearch'] = null;
}
?>
<?php $form = ActiveForm::begin(['method' => 'get', 'action' => Yii::$app->controller->action->id]); ?>
<div>
    <label class="width-100">銷單日期</label>
    <input type="text" class="select-month" style="width:90px;" name="SaleDetailsSumSearch[month]" value="<?= $get['SaleDetailsSumSearch']['month'] ?>">
    <label class="width-100">业务员</label>
    <input type="text" class="width-80" name="SaleDetailsSumSearch[seller]" value="<?= $get['SaleDetailsSumSearch']['seller'] ?>">
<!--    <label class="width-100">销售点</label>-->
<!--    <input type="text" class="width-150" name="SaleDetailsSumSearch[store]" value="--><?//= $get['SaleDetailsSumSearch']['store'] ?><!--">-->
    <div class="inline-block">
        <label class="width-100">销售点</label>
        <select name="SaleDetailsSumSearch[store]" class="width-100">
            <option value="">---请选择---</option>
            <?php foreach ($stores as $key => $val) {?>
                <option value="<?=$val['sts_id'] ?>" <?= isset($get['SaleDetailsSumSearch']['store'])&&$get['SaleDetailsSumSearch']['store']==$val['sts_id']?"selected":null ?>><?= $val['sts_sname'] ?></option>
            <?php } ?>
        </select>
    </div>
    <?= Html::submitButton('查询', ['class' => 'button-blue ml-50']) ?>
    <?= Html::button('重置', ['class' => 'button-blue', 'onclick'=>'window.location.href="'.Url::to([Yii::$app->controller->action->id]).'"']) ?>
</div>
<?php ActiveForm::end(); ?>

<script>
    $(function () {
        // 初始化月份 默认上月
        var initMonth = "<?= $get['SaleDetailsSumSearch']['month'] ?>" || lastMonth();
        $(".select-month").val(initMonth);
        function lastMonth()
        {
            var date = new Date;
            var year = date.getFullYear();
            var month = date.getMonth();
            lastMonth = (month<10 ? "0"+month:month);
            lastMonth = year + '-' +  lastMonth;
            return lastMonth;
        }
    })
</script>
