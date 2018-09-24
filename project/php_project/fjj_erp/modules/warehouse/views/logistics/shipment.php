<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/30
 * Time: 上午 11:16
 */
use \yii\widgets\ActiveForm;
\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);

?>
    <h1 class="head-first">添加物流出货信息</h1>

<style>
    .width-100{
        width: 100px;
    }
    .width-200{
        width: 200px;
    }
    </style>
<?php $form = ActiveForm::begin([
    'id' => 'shipment',
    'action' => ['/warehouse/logistics/shipment']
]) ?>
    <div class="mb-10 " style="margin-top: 20px">
        <div class="inline-block  required">
            <label for="ordlogisticsshipment-orderno" class="label-align width-100">物流单号:</label>
            <input type="text" maxlength="50" name="OrdLogisticsShipment[orderno]" class="width-200 easyui-validatebox "
                   data-options="required:'true'" id="orderno" readonly="true" value="<?= $param['logistics_no'] ?>">
        </div>
    </div>
    <div class="mb-10 ml-50" style="margin-top: 20px">
<!--        <input type="hidden" name="OrdLogisticsShipment[pdt_id]" value="--><?//=$pdtid?><!--">-->
        <input type="hidden" name="OrdLogisticsShipment[o_whdtid]" value="<?=$param['o_whdtid']?>">
        <input type="hidden" name="OrdLogisticsShipment[o_whpkid]" value="<?=$param['o_whpkid']?>">
        <div class="inline-block  required">
            <label for="ordlogisticsshipment-pdtno" class="label-align width-100">料号:</label>
            <input type="text" maxlength="50" name="OrdLogisticsShipment[part_no]" class="width-200 easyui-validatebox "
                   data-options="required:'true'" id="pdt_no" readonly="true" value="<?= $param['part_no'] ?>">
        </div>
    </div>
    <div class="mb-10 ml-50" style="margin-top: 20px">
        <div class="inline-block  required">
            <label for="ordlogisticsshipment-qty" class="label-align width-100">出货数量:</label>
            <input type="text" maxlength="50" name="OrdLogisticsShipment[qty]" class="width-200 easyui-validatebox "
                   data-options="required:'true'" id="qty" readonly="true" value="<?= $param['qty'] ?>">
        </div>
    </div>
    <div class="mb-10 ml-50" style="margin-top: 20px">
        <div class="inline-block  required">
            <label class="width-100 label-align">出货时间:</label>
            <input type="hidden" id="start" value="<?= date('Y-m-d', time()) ?>">
            <input class=" no-border easyui-validatebox deldate text-center Wdate"
                   data-options="required:'true'" type="text" id="Deliverytime"
                   style="width: 150px;" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '%y-%M-#{%d}' })"
                   onfocus="this.blur()" name="OrdLogisticsShipment[update_date]"
                   value="<?= $model['update_date']==null ? '': $model['update_date']?>" readonly="readonly">
<!--            <input class="width-200 Wdate easyui-validatebox" data-options="required:true" type="text"-->
<!--                   name="OrdLogisticsShipment[update_date]"-->
<!--                   value="--><?//= $model['update_date'] ? '': $model['update_date']?><!--"-->
<!--                   readonly="readonly">-->
        </div>
    </div>
    <div class="mb-10" style="margin-top: 20px;margin-left: 100px;">
        <!--       <? //=Html::submitButton('确认',['class'=>'button-blue-small','type'=>'submit'])?> -->
        <!--        <? //=Html::Button('返回',['class'=>'button-white-small ml-10','type'=>'button','onclick'=>'history.go(-1)'])?>-->
        <button class="button-blue mr-20" type="submit" id="btn_add">确定</button>
        <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
    </div>

<?php $form->end(); ?>
<script>
    $(function () {
        $(document).ready(function () {
            ajaxSubmitForm($("#shipment")
                , '', function (data) {
                console.log(data);
                //alert(data);
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
    });
</script>
