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
if (!isset($get['SaleDetailsSearch'])) {
    $get['SaleDetailsSearch'] = null;
}
?>
<?php $form = ActiveForm::begin(['method' => 'get', 'action'=>['index']]); ?>
<div>
    <label class="width-100">銷單日期</label>
    <input type="text" class="select-date startDate" style="width:90px;" name="SaleDetailsSearch[saleStartDate]" value="<?= $get['SaleDetailsSearch']['saleStartDate'] ?>">
    <label class="no-after">至</label>
    <input type="text" class="select-date endDate" style="width:91px;" name="SaleDetailsSearch[saleEndDate]" value="<?= $get['SaleDetailsSearch']['saleEndDate'] ?>">
    <label class="width-100">业务员</label>
    <input type="text" class="width-80" name="SaleDetailsSearch[seller]" value="<?= $get['SaleDetailsSearch']['seller'] ?>">
<!--    <label class="width-100">销售点</label>-->
<!--    <input type="text" class="width-150" name="SaleDetailsSearch[store]" value="--><?//= $get['SaleDetailsSearch']['store'] ?><!--">-->
    <div class="inline-block">
        <label class="width-100">销售点</label>
        <select name="SaleDetailsSearch[store]" class="width-100">
            <option value="">---请选择---</option>
            <?php foreach ($stores as $key => $val) {?>
                <option value="<?=$val['sts_id'] ?>" <?= isset($get['SaleDetailsSearch']['store'])&&$get['SaleDetailsSearch']['store']==$val['sts_id']?"selected":null ?>><?= $val['sts_sname'] ?></option>
            <?php } ?>
        </select>
    </div>
    <?= Html::submitButton('查询', ['class' => 'button-blue ml-50']) ?>
    <?= Html::button('重置', ['class' => 'button-blue', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>

</div>
<?php ActiveForm::end(); ?>

<script>
    $(function () {
        // 初始化月份 默认上月
        var start = "<?= $get['SaleDetailsSearch']['saleStartDate'] ?>" || startDate();
        var end = "<?= $get['SaleDetailsSearch']['saleEndDate'] ?>" || endDate();
        $(".startDate").val(start);
        $(".endDate").val(end);
        function startDate() // 取上月第一天
        {
            var date = new Date;
            var year = date.getFullYear();
            var month = date.getMonth();
            if (month==0) { // 到上一年12月
                year = year-1;
                month = 12;
            } else if (month<10) {
                month = "0"+month;
            }
            startDate = year + '-' + month + '-' + '01';
            return startDate;
        }
        function endDate() // 取上月最后一天
        {
            var date = new Date;
            var year = date.getFullYear();
            var month = date.getMonth();
            if (month==0) { // 到上一年12月
                year = year-1;
                month = 12;
            } else if (month<10) {
                month = "0"+month;
            }
            var new_date = new Date(year,month,1);                //取当年当月中的第一天
            var day = (new Date(new_date.getTime()-1000*60*60*24)).getDate(); //上月第一天减去一天 获取上月最后一天
            endDate = year + '-' + month + '-' + day;
            return endDate;
        }
    })
</script>