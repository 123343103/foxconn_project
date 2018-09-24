<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/29
 * Time: 上午 09:23
 */

/* @var $this yii\web\View */
/* @var $model app\modules\warehouse\models\OrdLogisticLog */
use app\assets\MultiSelectAsset;
use yii\widgets\ActiveForm;

\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);
MultiSelectAsset::register($this);
//\app\assets\JqueryUIAsset::register($this);
?>
<style>
    .label-width{
        width: 100px;
    }
    .width-200{
        width: 200px;
    }
</style>
<h1 class="head-first">添加物流进度</h1>

<?php $form = ActiveForm::begin([
    'id' => 'add',
    //'method'=>'get',
    //'enableAjaxValidation' => true,
    'action' => ['/warehouse/logistics/add']
    //'action'=>Url::to(['add'])
]) ?>
<!--<div style="margin-left: 100px">-->
<div class="mb-10" style="float: left;margin-top: 30px;margin-left: 30px;">
    <div class="inline-block  required">
        <label for="ordlogisticlog-orderno" class="label-align label-width">物流单号：</label>
        <input type="text" maxlength="50" name="OrdLogisticLog[orderno]" class="width-200 easyui-validatebox "
               data-options="required:'true'" id="orderno" readonly="true" value="<?= $param['id'] ?>">
        <input type="hidden" value="<?=$param['shipid']?>" name="ship_id">
    </div>
    <div class="inline-block  required">
        <label for="ordlogisticlog-forwardcode" class="label-align label-width">承运商代码：</label>
        <input type="text" maxlength="50" name="OrdLogisticLog[FORWARDCODE]" class="width-200 easyui-validatebox"
               data-options="required:'true'" id="forwardcode" value="<?= $model['FORWARDCODE'] ?>">
    </div>
</div>
<div class="mb-10 " style="float: left;margin-left: 30px;">
    <div class="inline-block  required">
        <p class="gang-shu">
            <label for="transmode" class="label-align label-width"
                   style="line-height:21px; float:left; margin-right:3px;">运输方式：</label>
            <input type="radio" name="OrdLogisticLog[TRANS_MODE]" data-options="required:'true'"
                   id="transmode" checked="checked" value="0">陆运
            <input type="radio" name="OrdLogisticLog[TRANS_MODE]" data-options="required:'true'"
                   id="transmodes" value="1">快递
            <!--                <input type="radio" style="margin-left: 20px" name="OrdLogisticLog[TRANS_MODE]"-->
            <!--                       data-options="required:'true'" id="transmode1" checked="checked" value="1">快递-->

        </p>
    </div>
    <div class="inline-block  required" style="margin-left: 123px;">
        <p class="gang-shu">
            <label for="station" class="label-align label-width"
                   style="line-height:21px; float:left; margin-right:3px;">站点：</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[STATION]" class="width-200 easyui-validatebox"
                   data-options="required:'true'" id="station" value="<?= $model['station'] ?>">
        </p>
    </div>
</div>
<div class="mb-10" style="float: left;margin-left: 30px;">
    <div class="inline-block  required">
        <p class="gang-shu">
            <label for="onwaystatus" class="label-align label-width"
                   style="line-height:21px; float:left; margin-right:3px;">在途状态：</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[ONWAYSTATUS]"
                   class="width-200 easyui-validatebox" data-options="required:'true'" id="onwaystatus"
                   value="<?= $model['onwaystatus'] ?>">
        </p>
    </div>
    <div class="inline-block  required">
        <p class="gang-shu">
            <label for="delivery_man" class="label-align label-width"
                   style="line-height:21px; float:left; margin-right:3px;">送货人员：</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[DELIVERY_MAN]"
                   class="width-200 easyui-validatebox" data-options="required:'true'" id="delivery_man"
                   value="<?= $model['delivery_man'] ?>">
        </p>
    </div>
</div>
<div class="mb-10" style="float: left;margin-left: 30px;">
    <div class="inline-block required">
        <p class="gang-shu">
            <label for="onwaystatus_date" class="label-align label-width  select-date"
                   style="line-height:21px; float:left; margin-right:3px;">状态发生时间：</label>
            <input class=" no-border  width-200 easyui-validatebox deldate text-center Wdate"
                   data-options="required:'true'" type="text" id="Deliverytime"
                   onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd H:m:s',minDate: '%y-%M-%d %H:%m:%s' })"
                   onfocus="this.blur()" name="OrdLogisticLog[ONWAYSTATUS_DATE]"
                   value="<?= $model['onwaystatus_date']==null ? '': $model['onwaystatus_date']?>" readonly="readonly">
<!--            <input type="text" maxlength="50" name="OrdLogisticLog[ONWAYSTATUS_DATE]"-->
<!--                   class="width-200 easyui-validatebox select-date-time" data-options="required:'true'"-->
<!--                   id="onwaystatus_date"-->
<!--                   value="--><?//= $model['onwaystatus_date'] ?><!--">-->
        </p>
    </div>
    <div class="inline-block required">
        <p class="gang-shu">
            <label for="remark" class="label-align label-width  select-date"
                   style="line-height:21px; float:left; margin-right:3px;">状态详情信息：</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[REMARK]" class="width-200 easyui-validatebox"
                   data-options="required:'true'" id="remark" value="正常">
        </p>
    </div>
</div>
<div class="mb-10" id="carrier" style="float: left;margin-left: 30px; ">
    <div class="inline-block required">
        <p class="gang-shu">
            <label for="carrierno" class="label-align label-width  select-date"
                   style="line-height:21px; float:left; margin-right:3px;">配送车牌：</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[CARRIERNO]" class="width-200"
                   data-options="required:'true'" id="carrierno" value="<?= $model['carrierno'] ?>">
        </p>
    </div>
</div>
<div class="inline-block required mb-10">
    <p class="gang-shu">
        <label for="customer_shop" class="label-align label-width select-date"
               style="line-height:21px; float:left; margin-right:3px;">渠道代码：</label>
        <input type="text" maxlength="50" name="OrdLogisticLog[CUSTOMER_SHOP]"
               class="width-200 easyui-validatebox" data-options="required:'true'" id="customer_shop"
               value="<?= $model['customer_shop'] ?>">
    </p>
</div>
<div class="mb-10" id="express" style="float: left; display: none;margin-left: 30px;">
    <div class="inline-block required">
        <p class="gang-shu">
            <label for="expressno" class="label-align label-width  select-date"
                   style="line-height:21px; float:left; margin-right:3px;">快递单号：</label>
            <input type="text" maxlength="50" name="OrdLogisticLog[EXPRESSNO]" class="width-200"
                   data-options="required:'true'" id="expressno" value="<?= $model['expressno'] ?>">
        </p>
    </div>
</div>
<div class="mb-10" style="float: left; margin-left: 260px;margin-top: 30px;">
    <!--       <? //=Html::submitButton('确认',['class'=>'button-blue-small','type'=>'submit'])?> -->
    <!--        <? //=Html::Button('返回',['class'=>'button-white-small ml-10','type'=>'button','onclick'=>'history.go(-1)'])?>-->
    <button class="button-blue" type="submit" id="btn_add">确定</button>
    <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
</div>
<!--</div>-->
<?php $form->end(); ?>
<script>
    $(function () {
        $(document).ready(function () {
            ajaxSubmitForm($("#add")
                , '', function (data) {
                    parent.layer.alert(data.msg, {
                        icon: 1,
                        end: function () {
                            if (data.flag == 1) {
                                parent.$("#check_in_info_tab").datagrid('reload');
                                parent.$.fancybox.close();
                            }
                            else {
                                $("button[type=submit]").removeAttr('disabled');
                            }
                        }
                    })
                });
        });
        //陆运
        $("#transmode").on('click', function () {
            $("#transmode").attr('checked', 'checked');//陆运按钮选中
            $("#transmodes").attr('checked', false);//快递按钮取消
//            if(document.getElementById("transmode").checked)
//            {
            //alert("陆运");
            $("#express").css('display', 'none');//快递单号隐藏
            $("#carrier").css('display', 'block');//车牌号显示
            //}
        });
        //快递
        $("#transmodes").on('click', function () {
            $("#transmode").attr('checked', false);//快递按钮选中
            $("#transmodes").attr('checked', 'checked');//陆运按钮取消
//            if(document.getElementById("transmodes").checked)
//            {
            //alert("快递");
            $("#express").css('display', 'block');//快递单号显示
            $("#carrier").css('display', 'none');//车牌号隐藏
//            }
        });
    });
    //        ajaxSubmitForm($("#add")
    //            ,'',function (data) {
    //                layer.alert(data.msg,{
    //                    icon:1,
    //                    end:function () {
    //                        parent.$("#check_in_info_tab").datagrid('reload');
    //                        parent.$.fancybox.close();
    //                    }
    //                })
    //            });
</script>
