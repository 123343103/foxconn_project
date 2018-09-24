<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/11/12
 * Time: 下午 03:32
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\select2\Select2;

$this->params['homeLike'] = ['label' => '料号管理'];
$this->params['breadcrumbs'][] = ['label' => '料号管理列表'];
$this->params['breadcrumbs'][] = ['label' => '新增料号申请']
?>
<div class="content">
    <h1 class="head-first">
        新增料号申请
    </h1>
    <h2 class="head-second">
        料号基本信息
    </h2>
    <div>
        <?php $form = ActiveForm::begin(['id'=>"add-form"]);?>

        <!--一阶-->
        <div class="mb-20">
            <?= $form->field($planModel,'pro_main_type_id',['labelOptions' => ['class' => 'width-100'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->dropDownList($planModel->getFenLeiList(0),
                [
                    'id'=>'type_1',
                    'class'=>'width-180 height-21 type',
                    'prompt'=>'--请选择--',
                    'autoPostBack'=>'true',
                ]) ?>
            <?=$form->field($planModel,'pro_other_name',['labelOptions' => ['class' => 'width-132'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->textInput(['class'=>'width-180 height-21','placeholder'=>' 前项自动带出','id'=>'dafentou'])?>
            <?=$form->field($planModel,'material_code',['labelOptions' => ['class' => 'width-180'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->textInput(['class'=>'width-200 height-21','placeholder'=>'由前面几项自动带出','id'=>'material'])?>

        </div>
        <!--二阶-->
        <div class="mb-20">
            <?= $form->field($planModel,'pro_second_type_id',['labelOptions' => ['class' => 'width-100'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->dropDownList($planModel->getFenLeiList($planModel->pro_main_type_id),
                [
                    'id'=>'type_2',
                    'class'=>'width-180 height-21 type',
                    'prompt'=>'--请选择--',
                ]) ?>
            <span style="width:11px;"></span><a class='fancybox.ajax' id='select-com' href="<?=Url::to(['/ptdt/product-type-detail/add-category']) ?>"><input type="button" class="tanchuang" value="类别" id="select-com" ></a>
            <?= $form->field($planModel, 'pro_brand',['inputOptions'=>['class'=>'select2 height-21'],'labelOptions'=>['class'=>'width-450']])
                ->widget(Select2::classname(), [ 'data' => ArrayHelper::map($brand,'c_name','c_name'), 'language' => 'zh-CN', 'options' => ['placeholder' => '請選擇...'], 'pluginOptions' => [ 'allowClear' => true ], ]); ?>
        </div>
        <!--三阶-->
        <div class="mb-20">
            <?= $form->field($planModel,'pro_third_type_id',['labelOptions' => ['class'=>'width-100'],'inputOptions'=>['class'=>'select2'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->dropDownList($planModel->getFenLeiList($planModel->pro_second_type_id),
                [
                    'id'=>'type_3',
                    'class'=>'width-180 height-21 type',
                    'prompt'=>'--请选择--',
                ]) ?>
            <span style="width:11px;"></span><input type="button" class="tanchuang" value="类别" onClick="window.open('','blank_','scrollbars=yes,resizable=yes,width=700,height=422')" >
            <?=$form->field($planModel,'pro_name',['labelOptions' => ['class' => 'width-450'],'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->textInput(['class'=>'width-200 height-21','id'=>'pro_name'])?>
        </div>
        <!--四阶-->
        <div class="mb-20">
            <?= $form->field($planModel,'pro_fourth_type_id',['labelOptions' => ['class' => 'width-100'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->dropDownList($planModel->getFenLeiList($planModel->pro_third_type_id),
                [
                    'id'=>'type_4',
                    'class'=>'width-180 height-21 type',
                    'prompt'=>'--请选择--',
                ]) ?>
            <span style="width:11px;"></span><input type="button" class="tanchuang" value="类别" onClick="window.open('','blank_','scrollbars=yes,resizable=yes,width=700,height=422')" >
            <?= $form->field($planModel,'pro_size',['labelOptions' => ['class' => 'width-450'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->textInput(['class'=>'width-200 height-21','id'=>'pro_size'])?>
        </div>
        <!--五阶-->
        <div class="mb-20">
            <?= $form->field($planModel,'pro_fifth_type_id',['labelOptions' => ['class' => 'width-100'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->dropDownList($planModel->getFenLeiList($planModel->pro_fourth_type_id),
                [
                    'id'=>'type_5',
                    'class'=>'width-180 height-21 type',
                    'prompt'=>'--请选择--',
                ]) ?>
            <span style="width:11px;"></span><input type="button" class="tanchuang" value="类别" onClick="window.open('','blank_','scrollbars=yes,resizable=yes,width=700,height=422')" >
            <?= $form->field($planModel,'pro_sku',['labelOptions' => ['class' => 'width-450'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->textInput(['class'=>'width-200 height-21','id'=>'pro_sku'])?>
        </div>
        <!--六阶-->
        <div class="mb-20">
            <?= $form->field($planModel,'pro_sixth_type_id',['labelOptions' => ['class' => 'width-100'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->dropDownList($planModel->getFenLeiList($planModel->pro_fifth_type_id),
                [
                    'id'=>'type_6',
                    'class'=>'width-180 height-21 type',
                    'prompt'=>'--请选择--',
                ]) ?>
            <span style="width:11px;"></span><input type="button" class="tanchuang" value="类别" onClick="window.open('','blank_','scrollbars=yes,resizable=yes,width=700,height=422')" >
            <?= $form->field($planModel,'source_code',['labelOptions' => ['class' => 'width-450'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->textInput(['class'=>'width-130 height-21','id'=>'pro_sku'])?>
        </div>
        <div class="mb-20">
            <?/*=$form->field($model,'pro_serial_number',['labelOptions' => ['class' => 'width-100'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->textInput(['class'=>'width-180 height-21','value'=>'<? echo rand(); ?> ']) */?>
            <label class="width-100">流水号</label>
            <input type="text" id="text" placeholder="点击按钮生成随机流水号" class="width-180 height-21"/>
            <span style="width:11px;"></span><input type="button" class="tanchuang" value="流水号" onclick="my_ran(1,1000,9999)" id="cre" />

        </div>

        <div class="space-10"></div>
        <hr size="1px">
        <div class="space-30"></div>
        <div class="mb-20">
            <?=$form->field($planModel,'pro_level',['labelOptions' => ['class' => 'width-100'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->textInput(['class'=>'width-250 height-21','placeholder'=>' 由六阶自动生成','id'=>'fenjifenlei'])?>
            <?= $form->field($planModel,'other_group_code',['labelOptions' => ['class' => 'width-429'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->textInput(['class'=>'width-130 height-21'])?>
        </div>
        <div class="mb-20">
            <?=$form->field($planModel,'group_code',['labelOptions' => ['class' => 'width-100'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->textInput(['class'=>'width-130 height-21'])?>
            <?= $form->field($planModel, 'birth_year', [
                'labelOptions'=>['class'=>'width-549'],
                'errorOptions'=>['class'=>'error-notice','style'=>"margin-left:85px;"],
            ])->textInput(['class'=>'width-130 height-21 select-date']) ?>

        </div>
        <div class="mb-20">
            <?=$form->field($planModel,'status',['labelOptions' => ['class' => 'width-100'], 'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:85px"]])->textInput(['class'=>'width-130 height-21'])?>
            <?php if(empty($planModel->pro_pic)){?>
                <?= $form->field($planModel, 'pro_pic',['labelOptions' => ['class' => 'width-439']])->fileInput() ?>
            <?php }else{?>
                <?= $form->field($planModel, 'pro_pic',['inputOptions'=>['style'=>['display'=>'none']],'labelOptions' => ['class' => 'width-150']])->fileInput() ?>
                <input type="hidden" id="is_attachment" name="is_attachment">
                <span class="width-1000" ><a href="<?= $planModel->pro_pic ?>" id="attachment_view" class="width-200"><?= $planModel->pro_pic_name ?></a>
                <a  class="ml-20 red" id="del-file" title="删除文件"><i class="icon-remove icon-l icon-only"></i></a>
                </span>
            <?php } ?>
        </div>
        <div class="space-20"></div>
    </div>
    <h2 class="head-second">
        请选择要发布的资料库</h2>
    <div class="mb-20" style="height: 15px;">
        <?php foreach($res as $value){?>
            <div style="width:160px;height:34px;" class="float-left">
                &emsp;&thinsp; <input type="checkbox" name="database" value="shenzhen" class="vertical-middle" />&emsp;<?=$value['company_name']?>
            </div>
        <?php }?>
    </div>
    <div class="space-20"></div>
    <div class="text-center mb-20 mt-30">
        <?= Html::submitButton('确&nbsp认' ,['class' =>'button-blue-big','id'=>'submit']) ?>&nbsp;
        <?= Html::resetButton('取&nbsp消', ['class' => 'button-white-big ml-20','id'=>'goback' ]) ?>
    </div>
    <?php ActiveForm::end();?>
</div>
<script>
    $(function(){
        ajaxSubmitForm($("#add-form"));//ajax提交

        var $typeOne = $('#type_1');
        var $typeTwo = $('#type_2');
        var $typeThree = $('#type_3');
        var $typeFour = $('#type_4');
        var $typeFive = $('#type_5');
        var $typeSix = $('#type_6');
        var $material = $('#material');
        var $proName = $('#pro_name');

        /*联动*/
        $('#type_1').on("change", function () {
            var $select = $(this);
            var $type = $typeTwo
            getFenLei($select,$type);
        });
        $('#type_2').on("change", function () {
            var $select = $(this);
            var $type = $typeThree
            getFenLei($select,$type);
        });
        $('#type_3').on("change", function () {
            var $select = $(this);
            var $type = $typeFour
            getFenLei($select,$type);
        });
        $('#type_4').on("change", function () {
            var $select = $(this);
            var $type = $typeFive
            getFenLei($select,$type);
        });
        $('#type_5').on("change", function () {
            var $select = $(this);
            var $type = $typeSix;
            getFenLei($select,$type);
        });
        //    大分头自动带出项
        $(document).ready(function(){
            $("#dafentou").click(function(){
                $("#dafentou").val(($("#type_1 option:selected").text()));
            })
        });

        //分级分类带出
        $(document).ready(function(){
            $("#fenjifenlei").click(function(){
                $("#fenjifenlei").val(($("#type_1 option:selected").text())+'->'+($("#type_2 option:selected").text())
                    +'->'+($("#type_3 option:selected").text())+'->'+($("#type_4 option:selected").text())
                    +'->'+($("#type_5 option:selected").text())+'->'+($("#type_6 option:selected").text()));
            })
        });

        /*$(document).ready(function(){
         $("#material").click(function(){
         var $select =$typeOne;
         var $liao = $("#material");
         var $e =String(getTypeNo($select,$liao));
         $("#material").val($e);

         })
         });*/

        /*$(document).ready(function(){
         $("#material").click(function(){
         $("#material").text(getTypeNo($typeOne)+getTypeNo($typeTwo)+getTypeNo($typeThree)+getTypeNo($typeFour)
         +getTypeNo($typeFive)+getTypeNo($typeSix)+$("#text").val());
         })
         });*/
        $(document).ready(function(){
            $("#material").click(function(){
                $("#material").val(($("#type_1 option:selected").val())+($("#type_2 option:selected").val())
                    +($("#type_3 option:selected").val())+($("#type_4 option:selected").val())
                    +($("#type_5 option:selected").val())+($("#type_6 option:selected").val()));
            })
        });


        $('#select-com').fancybox(      //分级分类彈框
            {
                padding : [],
                fitToView	: false,
                width		: 700,
                height		: 422,
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none',
                type : 'iframe'
            }
        )

        //文件
        $("#del-file").click(function(){
            layer.confirm("确定要删除文件?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },
                function () {
                    var del = $("#del-file");
                    del.hide();
                    del.parent().hide();
                    $("#is_attachment").val('1');
                    $("#pdmaterialcode-pro_pic").show();
                    layer.closeAll();
                },
                function () {
                    layer.closeAll();
                })
        });

        $('#goback').click(function () {
            history.back(-1);
        });

    });
    /*流水号*/
    function my_ran(n,min,max){
        var arr=[];
        for(i=0;i<n;i++){
            arr[i]=parseInt(Math.random()*(max-min+1)+min);
        }
        for(i=0;i<n;i++){
            for(j=i+1;j<n;j++){
                if(arr[i]==arr[j]){
                    my_ran(n,l,min,max);
                    return fault;
                }
            }
        }
        document.getElementById("text").value=arr;
    }
    function getFenLei($select,$type){
        var id = $select.val();
        if( id ==""){
            clearOption($select);
            return;
        }
        $.ajax({
            type: "get",
            dataType:"json",
            async:false,
            data:{"id":id},
            url:"<?=Url::to(['/ptdt/material-code/get-product-type'])?>",
            success:function(data){
                $type.html("<option value>--请选择--</option>");
                for (var x in data){
                    $type.append('<option value="' + data[x].type_id + '">' + data[x].type_name + '</option>')
                }
            }
        })
    }

    function getTypeNo($select,$liao){
        var id = $select.val();
        $.ajax({
            type:"get",
            dataType:"json",
            async:false,
            data:{"id":id},
            url:"<?=Url::to(['/ptdt/material-code/get-type-no'])?>",
            success:function(data){
                /*$liao.append(data.type_no);*/
                var $a = data.type_no;
                var $aToString = JSON.stringify($a);
                $liao.append($aToString);
            }
        })
    }
</script>

