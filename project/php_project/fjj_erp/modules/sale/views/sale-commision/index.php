<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->params['homeLike'] = ['label'=>'销售管理','url' => Url::to([''])];
$this->params['breadcrumbs'][] = ['label' => '费用与提成', 'url' => Url::to([''])];
$queryParam = Yii::$app->request->queryParams;
$disabled = ($queryParam['import']=='y') ? true : false;
?>
<div class="content">
    <?= $this->render('_search', [
        'stores' => $stores,
    ]) ?>
    </br>
    <div id="data"></div>
    </br>
    <?= Html::button('导入销单明细', ['class' => 'button-blue-big ml-50', 'id' => 'showDiv', 'href'=>'#inline']) ?>
    <?= Html::button('data完整性检查', ['class' => 'button-blue-big ml-50 check-data', 'id' => 'showDiv2', 'href'=>'#inline2']) ?>
    <?= Html::button('下一步', ['class' => 'button-blue-big ml-50 next-step', 'disabled'=>$disabled, 'onclick'=>'window.location.href="'.Url::to(['detail-to-sum']).'"']) ?>
</div>

<!--导入弹窗-->
<div style="display:none">
    <div id="inline" style="width:500px; height:260px; overflow:auto">
        <div class="pop-head">
            <p>导入销单明细</p>
        </div>
        <div class="mt-40">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
                'action'=>['import-sale-details'],
                'id'=>'fileForm',
                'fieldConfig' => [
                    'errorOptions'=>['class'=>'error-notice mt-10'],
                    'labelOptions'=>['class'=>'width-100'],
                    'inputOptions'=>['class' => 'width-200']
                ]
            ]); ?>
            <div class="ml-40">
                <div class="inline-block field-uploadForm">
                    <input type="hidden" name="UploadForm[file]" value=""><input type="file" id="uploadForm" class="width-200" name="UploadForm[file]">
                    <div class="error-notice mt-10"></div>
                </div>
                <?= Html::submitButton('确认',['class' =>'button-blue ml-20']) ?>&nbsp;
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div id="inline2" style="width:500px; height:120px; overflow:auto">
        <div class="pop-head title">
            <p>正在进行数据完整性检查...</p>
        </div>
        <div class="bd"></div>
        <div class="space-10"></div>
    </div>
    </div>
</div>
<script>
    $(function () {
        //严格模式
        "use strict";

        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "sdl_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 10,
            pageList: [10,20,30],
            columns: [[
                {field: "sdl_type", title: "来源", width: 50},
                {field: "sdl_comp", title: "法人资料", width: 60},
                {field: "part_no", title: "料号", width: 135},
                {field: "cust_code", title: "客户代码", width: 85},
                {field: "cust_shortname", title: "客户简称"},
                {field: "sdl_sacode", title: "业务人员", width: 80},
                {field: "sdl_saname", title: "姓名", width: 60},
                {field: "produce_org", title: "制造部门", width: 80},
                {field: "sale_date", title: "销货日期", width: 80},
                {field: "sale_code", title: "销单/内交单号", width: 100},
                {field: "sdl_year", title: "销退单号", width: 50},
                {field: "saleTypeName", title: "销售类型", width: 50},
                {field: "sdl_qty", title: "销货数", width: 50},
                {field: "sdl_unit", title: "单位", width: 50},
                {field: "unit_cvs", title: "单位换算率", width: 60},
                {field: "unit_price", title: "单价", width: 60},
                {field: "cur_code", title: "币别", width: 60},
                {field: "bill_oamount", title: "金额", width: 60},
                {field: "bill_camount", title: "本币金额", width: 60},
                {field: "stan_cost", title: "标准成本", width: 60},
                {field: "sale_cost", title: "成本", width: 60}
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });

        ajaxSubmitForm($("#fileForm"));
        $("#showDiv").fancybox({
            padding : [],
            centerOnScroll:true,
            titlePosition:'over',
            title:'数据导入导出',
        });

        $("#showDiv2").click(function () {
            $("#showDiv2").fancybox({
                padding : [],
                closeBtn:false,
            });
            $.ajax({ // check-data 数据完整性检查
                type: "get",
                dataType: "json",
                url: "<?= Url::to(['check-data']) ?>",
                error: function (data) {
                    alert('ajax请求出错，请刷新重试！')
                },
                success: function (data) {
                    setTimeout(function () {
                        $('.title p').html(data.msg);
                        $('.bd').after('</br><button type="submit" class="button-blue ml-200 mybt">确认</button>');
                        if (data.flag==1) {
                            var transfer = 0;
                            $.ajax({ // check-data之后，将临时表数据转移到销单明细表中
                                type:"get",
                                dataType:"json",
                                url:"<?= Url::to(['temp-to-detail']) ?>",
                                success: function (data) {
                                    transfer = 1;
                                },
                                error:function (data) {
                                    transfer = 0;
                                }
                            })
                            $('.mybt').click(function () {
                                if (transfer == 1){
                                    $('.next-step').attr('disabled',false);
                                    $('.check-data').attr('disabled',true);
                                    parent.$.fancybox.close();
                                } else {
                                    alert('数据转移出错,请刷新重试！');
                                };
                            })
                        } else {
                            $('.check-data').attr('disabled',true);
                            $('.next-step').attr('disabled',true);
                            $('.mybt').click(function () {
                                parent.$.fancybox.close();
                            });
                        }
                    },1000);
                }
            });
        });
    })
</script>
