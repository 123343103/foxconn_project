<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/29
 * Time: 上午 09:57
 */

/* @var $this yii\web\View */
/* @var $model app\modules\warehouse\models\OrdLogisticLog*/
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php ActiveForm::begin([
    //'method'=>'get',
    'id'=>'add-form',
    'enableAjaxValidation' => true,
    'action'=>Url::to(['add'])
])?>
<div style="margin-left: 100px">
<div class="mb-20">
    <div class="inline-block  required">
        <label for="ordlogisticlog-orderno" class="width-100">物流单号</label>
        <input type="text" maxlength="50" name="OrdLogisticLog[orderno]" class="width-200 easyui-validatebox " data-options="required:'true'" id="orderno" value="<?= $model['orderno'] ?>">
    </div>
</div>
<div class="mb-20">
    <div class="inline-block  required">
        <label for="ordlogisticlog-forwardcode" class="width-100">承运商代码</label>
        <input type="text" maxlength="50" name="OrdLogisticLog[FORWARDCODE]" class="width-200 easyui-validatebox" data-options="required:'true'" id="forwardcode" value="<?= $model['FORWARDCODE'] ?>">
    </div>
</div>
<div class="mb-20">
    <div class="inline-block  required">
        <p class="gang-shu">
            <label for="transmode" class="width-100 " style="line-height:21px; float:left; margin-right:3px;">运输方式</label>
            <input type="radio"  name="OrdLogisticLog[TRANS_MODE]" checked="checked" data-options="required:'true'" id="transmode" value="0">陆运
            <input type="radio" style="margin-left: 20px"  name="OrdLogisticLog[TRANS_MODE]"  data-options="required:'true'" id="transmode1" value="1">快递

        </p>
    </div>
</div>
<div class="mb-20">
    <div class="inline-block  required">
        <p class="gang-shu">
            <label for="station" class="width-100" style="line-height:21px; float:left; margin-right:3px;">站点</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[STATION]" class="width-200 easyui-validatebox" data-options="required:'true'" id="station" value="<?= $model['station'] ?>">
        </p>
    </div>
</div>
<div class="mb-20">
    <div class="inline-block  required">
        <p class="gang-shu">
            <label for="onwaystatus" class="width-100" style="line-height:21px; float:left; margin-right:3px;">在途状态</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[ONWAYSTATUS]" class="width-200 easyui-validatebox" data-options="required:'true'" id="onwaystatus" value="<?= $model['onwaystatus'] ?>">
        </p>
    </div>
</div>
<div class="mb-20">
    <div class="inline-block  required">
        <p class="gang-shu">
            <label for="delivery_man" class="width-100" style="line-height:21px; float:left; margin-right:3px;">送货人员</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[DELIVERY_MAN]" class="width-200 easyui-validatebox" data-options="required:'true'" id="delivery_man" value="<?= $model['delivery_man'] ?>">
        </p>
    </div>
</div>
<div class="mb-20">
    <div class="inline-block required">
        <p class="gang-shu">
            <label for="onwaystatus_date" class="width-100  select-date" style="line-height:21px; float:left; margin-right:3px;">状态发生时间</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[ONWAYSTATUS_DATE]" class="width-200 easyui-validatebox" data-options="required:'true'" id="onwaystatus_date" value="<?= $model['onwaystatus_date'] ?>">
        </p>
    </div>
</div>
<div class="mb-20">
    <div class="inline-block required">
        <p class="gang-shu">
            <label for="remark" class="width-100  select-date" style="line-height:21px; float:left; margin-right:3px;">状态详情信息</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[REMARK]" class="width-200 easyui-validatebox" data-options="required:'true'" id="remark" value="<?= $model['remark'] ?>">
        </p>
    </div>
</div>
<div class="mb-20" id="express" style="display: none">
    <div class="inline-block required">
        <p class="gang-shu">
            <label for="expressno" class="width-100  select-date" style="line-height:21px; float:left; margin-right:3px;">快递单号</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[EXPRESSNO]" class="width-200 easyui-validatebox" data-options="required:'true'" id="expressno" value="<?= $model['expressno'] ?>">
        </p>
    </div>
</div>
<div class="mb-20" id="carrier">
    <div class="inline-block required">
        <p class="gang-shu">
            <label for="carrierno" class="width-100  select-date" style="line-height:21px; float:left; margin-right:3px;">配送车牌</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[CARRIERNO]" class="width-200 easyui-validatebox" data-options="required:'true'" id="carrierno" value="<?= $model['carrierno'] ?>">
        </p>
    </div>
</div>
<div class="mb-20">
    <div class="inline-block required">
        <p class="gang-shu">
            <label for="customer_shop" class="width-100  select-date" style="line-height:21px; float:left; margin-right:3px;">渠道代码</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[CUSTOMER_SHOP]" class="width-200 easyui-validatebox" data-options="required:'true'" id="customer_shop" value="<?= $model['customer_shop'] ?>">
        </p>
    </div>
</div>
<div class="ml-100 mt-50">
<!--    --><?//=Html::submitButton('确认',['class'=>'button-blue-small','type'=>'submit'])?>
<!--    --><?//=Html::Button('返回',['class'=>'button-white-small ml-10','type'=>'button','onclick'=>'history.go(-1)'])?>
    <button class="button-blue mr-20" type="submit">确定</button>
    <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
</div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(document).ready(function() {
       // ajaxSubmitForm($("#add-form"));
        ajaxSubmitForm($("#add-form"),'',
            function(data){
                parent.layer.alert(data.msg, {
                    icon: 1,
                    end: function () {
                        //parent.$("#apply_table").datagrid('reload');
                        parent.$("#check_in_info_tab").datagrid('reload');
                        parent.$.fancybox.close();
                    }
                })
            }
        );
    });
    $(function () {
        $("#transmode").on('click',function () {
            $("#express").css('display','none');
            $("#carrier").css('display','block');
        })
        $("#transmode1").on('click',function () {
            $("#express").css('display','block');
            $("#carrier").css('display','none');
        })
    })
    
</script>