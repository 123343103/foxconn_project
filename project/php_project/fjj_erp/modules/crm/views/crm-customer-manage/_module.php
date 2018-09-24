<?php
use yii\widgets\ActiveForm;
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/6/28
 * Time: 16:05
 */

?>
<div id="module">
    <h2 class="head-first">模块设置</h2>
    <?php $form = ActiveForm::begin([
        'id' => 'add-form',
    ]) ?>
    <style>
        .value-width{
            width:150px;
        }
    </style>
    <div id="search-table" style="padding:10px;">
        <div id="theader" class="mb-10">
            <label><input class="vertical-center" type="checkbox" id="select" name="crmAll">&nbsp;全选</label>
        </div>

        <div class="border-bottom"></div>

        <div style="text-align: center;">
            <div class="inline-block">
                <input class="vertical-center aa" type="checkbox" id="crd" value="crd" name="crm[]">
                <label class="value-width value-align" for="crd">&nbsp;CRD\PRD</label>
            </div>
            <div class="inline-block">
                <input class="vertical-center aa" type="checkbox" id="crmVisitPlan" value="crmVisitPlan" name="crm[]">
                <label class="value-width value-align" for="crmVisitPlan">&nbsp;拜访计划</label>
            </div>
            <div class="inline-block">
                <input class="vertical-center aa" type="checkbox" id="tradeOrder" value="tradeOrder" name="crm[]">
                <label class="value-width value-align" for="tradeOrder" >&nbsp;最近交易订单</label>
            </div>
            <div class="inline-block">
                <input class="vertical-center aa" type="checkbox" id="crmVisitRecord" value="crmVisitRecord" name="crm[]">
                <label for="crmVisitRecord" class="value-width value-align">&nbsp;拜访记录</label>
            </div>
            <div class="inline-block">
                <input class="vertical-center aa" type="checkbox" id="quote" value="quote" name="crm[]">
                <label class="value-width value-align" for="quote">&nbsp;最近报价</label>
            </div>
            <div class="inline-block">
                <input class="vertical-center aa" type="checkbox" id="myApply" value="myApply" name="crm[]">
                <label class="value-width value-align" for="myApply">&nbsp;我的申请</label>
            </div>
        </div>

    </div>
    <div class="text-center mt-10 mb-20 clear">
        <button class="button-blue-big" type="button" id="module-check">确定</button>
        <button class="button-white-big ml-20" onclick="close_select()">取消</button>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $(function(){
        <?php if(!empty($module)){ ?>
            <?php foreach ($module as $k => $v){ ?>
                $('#<?php echo $v['module'] ?>').attr('checked',true);
            <?php } ?>
        <?php } ?>
        $('#module-check').on('click',function () {
//            if ($("input:checkbox[name='crm[]']:checked").length < 1) {
//                layer.alert('请选择要显示的模块',{icon:2});
//            }
            $.ajax({
                type:'post',
                dataType:'json',
                data:$('#add-form').serialize(),
                url:'<?= \yii\helpers\Url::to(["module"]) ?>?id='+<?= $id ?>,
                success:function(data){
                    if(data.flag == 1){
                        layer.alert(data.msg,{icon:1,end: function () {
                            parent.window.location.reload();
                        }});
                    }
                },
                error:function(data){
                    layer.alert(data.msg,{icon:2});
                }
            })
        });
//        $("#module-serach").click(function () {
//            var name = $("#module-message").val();
//            if ($.trim(name) != '') {
//                $("#search-table>div:not('#theader')").hide().filter(":contains('" + name + "')").show();
//                $("#search-table .space-10").show();
//            } else {
//                $("#search-table>div:not('#theader')").show();
//            }
//        })
//        $("#clear-search").click(function () {
//            $("#module-message").val("");
//            $("#search-table>div:not('#theader')").show();
//        })
        /*全选*/
        $("#select").click(function () {
            if ($(this).is(":checked")) {
                $("[name='crm[]']:checkbox").prop("checked", true);
            } else if ($(this).attr("checked", false)) {
                $("[name='crm[]']:checkbox").prop("checked", false);
            }
        });
    })
</script>
