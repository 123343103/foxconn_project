<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use app\assets\MultiSelectAsset;
MultiSelectAsset::register($this);

$this->title = '修改厂商信息';
$this->params['homeLike'] = ['label' => '商品开发管理'];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '厂商信息列表'];
$this->params['breadcrumbs'][] = ['label' => '修改厂商信息'];
$get = Yii::$app->request->get();
if(!isset($get['PdFirmQuery'])){
    $get['PdFirmQuery']=null;
}
?>
<style>
    .select2-selection{
        width: 578px;/*分級分類輸入框寬度*/
        height: auto;
        overflow:hidden;
    }
    .select2-selection{ /*分級分類輸入框高度樣式*/

    }
</style>
<div class="content">

    <h1 class="head-first">
        修改厂商信息
        <span class="head-code">编号:<?= $model->firm_code ?></span>
    </h1>
    <?php $form = ActiveForm::begin([
        'id' => 'add-form',
        /*'fieldConfig' => [
            'options'=>['class'=>'form-row'],
            'labelOptions' => ['class' => 'width-100'],
            'inputOptions' => ['class' => 'width-200'],
        ],*/
    ]); ?>
    <div class="mb-30">
        <h2 class="head-second">
            厂商信息
        </h2>
        <div class="inline-block">
            <label for="pdfirm-firm_sname" class="width-100"><span class="red">*</span>厂商注册名称</label>
            <input class="width-200 easyui-validatebox" maxlength="60" data-options="required:true,validType:'unique',delay:500" data-act="<?=Url::to(['validate'])?>" data-attr="firm_sname" data-id="<?=$model->firm_id;?>" value="<?= $model->firm_sname ?>" name="PdFirm[firm_sname]">
            <label for="pdfirm-firm_shortname" class="width-170">简称</label>
            <input class="width-200" maxlength="60"  value="<?=$model->firm_shortname?>" name="PdFirm[firm_shortname]">
        </div>
        <div class="space-10"></div>
        <div class="inline-block">
            <label for="pdfirm-firm_ename" class="width-100">英文名称</label>
            <input class="width-200" maxlength="60"  value="<?=$model->firm_ename?>" name="PdFirm[firm_ename]">
            <label for="pdfirm-firm_eshortname" class="width-170">英文简称</label>
            <input class="width-200" maxlength="60"  value="<?=$model->firm_eshortname?>" name="PdFirm[firm_eshortname]">
        </div>
        <div class="space-10"></div>
        <!--<div class="mb-10">
            <?/*= $form->field($model, 'firm_brand',['labelOptions' => ['class' => 'width-100'],'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:105px"],'inputOptions'=>['class' => 'width-200']])->textInput(['maxlength' => true]) */?>
            <?/*= $form->field($model, 'firm_brand_english',['labelOptions' => ['class' => 'width-170',],'inputOptions'=>['class' => 'width-200']])->textInput(['maxlength' => true]) */?>
            <p class="red float-right mr-200">必填项,二选一填写</p>
        </div>-->
        <div class="inline-block">
            <label for="pdfirm-firm_brand" class="width-100">品牌</label>
            <input class="width-200 " id="firm_brand1" maxlength="60" data-options="required:'true'" value="<?=$model->firm_brand?>" name="PdFirm[firm_brand]">
            <label for="pdfirm-firm_brand_english" class="width-170">品牌英文名</label>
            <input class="width-200" id="firm_brand2" maxlength="60" value="<?=$model->firm_brand_english?>" name="PdFirm[firm_brand_english]">
            <p class="red float-right mr-200">必填项,二选一填写</p>
        </div>
        <div class="space-10"></div>
        <!--<div class="mb-10">
            <?/*= $form->field($model, 'firm_source', ['labelOptions' => ['class' => 'width-100'],'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:105px"],])
                ->dropDownList($firmSource, ['class' => 'width-200', 'prompt' => "請選擇"]); */?>
            <?/*= $form->field($model, 'firm_type', ['labelOptions' => ['class' => 'width-170'],'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:175px"],])
                ->dropDownList($firmType, ['class' => 'width-200', 'prompt' => "請選擇"]); */?>
        </div>-->
        <div class="inline-block ">
            <label for="pdfirm-firm_source" class="width-100"><span class="red">*</span>来源</label>
            <select name="PdFirm[firm_source]" class="width-200 easyui-validatebox" data-options="required:'true'" id="pdfirm-firm_source">
                <option value="">请选择</option>
                <?php foreach ($firmSource as $key => $val) { ?>
                    <?php if (isset($model)) { ?>
                        <option
                            value="<?= $key ?>" <?= $model->firm_source == $key ? "selected" : null ?>><?= $val ?> </option>
                    <?php } else { ?>
                        <option value="<?= $key ?>"><?= $val ?> </option>
                    <?php } ?>
                <?php } ?>
            </select>
            <label for="pdfirm-firm_type" class="width-170">类型</label>
            <select name="PdFirm[firm_type]" class="width-200" id="pdfirm-firm_type">
                <option value="">请选择</option>
                <?php foreach ($firmType as $key => $val) { ?>
                    <?php if (isset($model)) { ?>
                        <option
                            value="<?= $key ?>" <?= $model->firm_type == $key ? "selected" : null ?>><?= $val ?> </option>
                    <?php } else { ?>
                        <option value="<?= $key ?>"><?= $val ?> </option>
                    <?php } ?>
                <?php } ?>
            </select>

            <div class="help-block"></div>
        </div>
        <div class="space-10"></div>
        <!--<div class="mb-10">
            <?/*= $form->field($model, 'firm_position', ['labelOptions' => ['class' => 'width-100'],'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:105px"],])
                ->dropDownList($firmPosition, ['class' => 'width-200', 'prompt' => "請選擇"]); */?>
            <?/*= $form->field($model, 'firm_issupplier',['labelOptions' => ['class' => 'width-170'],'errorOptions' => ['class' => 'error-notice', 'style' => "margin-left:175px"],])
                ->dropDownList([ 1 => '是', 0 => '否', ] ,['class' => 'width-200','prompt'=>'请選擇...']) */?>
        </div>-->
        <div class="inline-block ">
            <label for="pdfirm-firm_position" class="width-100">地位</label>
            <select name="PdFirm[firm_position]" class="width-200" id="pdfirm-firm_position">
                <option value="">请选择</option>
                <?php foreach ($firmPosition as $key => $val) { ?>
                    <?php if (isset($model)) { ?>
                        <option
                            value="<?= $key ?>" <?= $model->firm_position == $key ? "selected" : null ?>><?= $val ?> </option>
                    <?php } else { ?>
                        <option value="<?= $key ?>"><?= $val ?> </option>
                    <?php } ?>
                <?php } ?>
            </select>

            <label for="pdfirm-firm_issupplier" class="width-170"><span class="red">*</span>是否为集团供应商</label>
            <select name="PdFirm[firm_issupplier]" class="width-200 easyui-validatebox" data-options="required:'true'" id="pdfirm-firm_issupplier">
                <?php foreach ($firmIssupplier as $key => $val) { ?>
                    <?php if (isset($model)) { ?>
                        <option
                            value="<?= $key ?>" <?= $model->firm_issupplier == $key ? "selected" : null ?>><?= $val ?> </option>
                    <?php } else { ?>
                        <option value="<?= $key ?>"><?= $val ?> </option>
                    <?php } ?>
                <?php } ?>
            </select>

            <div class="help-block"></div>
        </div>
        <div class="space-10"></div>
        <!--<div class="mb-10" id="cateName">
            <label class="width-100">分級分類</label>
            <span class="ml-10 cursor-pointer color-1f7ed0" id="upCategory">修改</span>
        </div>-->
        <!--        <span class="width-500" id='upCateVal'  value="--><?//= $upCateValue  ?><!--"></span>-->
        <!--<div class="mb-10" id="cate">
                ->widget(Select2::classname(),
                    [   'data' => ArrayHelper::map($firmCategory,'type_id','type_name'),
                        'name'=>'category',
                        'model'=>$model,
                        //'value' =>[$typeName],
                        'language' => 'zh-CN',
                        'options' => ['placeholder' => '請選擇...','multiple' => true],
                        'pluginOptions' => [ 'allowClear' => true ], ]); */?>
        </div>-->
        <label for="pdfirm-firm_category_id" class="width-100 vertical-top"><span class="red">*</span>分级分类</label>
        <?php echo Select2::widget([
            'name' => 'firm_category_id',
            'id'=>'firmCate',
            //'value' => ['red', 'green'], // initial value
            'data' => ArrayHelper::map($firmCategory,'category_id','category_sname'),
            'options' => ['placeholder' => '请选择..', 'multiple' => true, ],
            'pluginOptions' => [
                'tags' => true,
                'maximumInputLength' => 10,
                'allowClear' => true ,
            ],

        ]);?>
        <input class="width-200"  type="hidden" id="pdfirm-firm_category_id" value="" name="PdFirm[firm_category_id]">
        <div class="space-10"></div>
        <div class="mb-10" id="address">
            <label class="width-100">公司地址</label>
            <select class="width-120 disName easyui-validatebox" data-options="required:'true'" id="disName_1">
                <option value="">请选择...</option>
                <?php foreach($districtAll['oneLevel'] as $val){?>
                    <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['oneLevelId']?'selected':null;?>><?=$val['district_name']?></option>
                <?php }?>
            </select>
            <select class="width-120 disName easyui-validatebox" data-options="required:'true'"  id="disName_2">
                <option value="">请选择...</option>
                <?php if(!empty($districtAll)){?>
                    <?php foreach($districtAll['twoLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['twoLevelId']?'selected':null;?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <select class="width-120 disName easyui-validatebox" data-options="required:'true'" id="disName_3">
                <option value="">请选择...</option>
                <?php if(!empty($districtAll)){?>
                    <?php foreach($districtAll['threeLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['threeLevelId']?'selected':null;?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <select class="width-120 disName easyui-validatebox" data-options="required:'true'" id="disName_4" name="PdFirm[firm_district_id]">
                <option value="">请选择...</option>
                <?php if(!empty($districtAll)){?>
                    <?php foreach($districtAll['fourLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['fourLevelId']?'selected':null;?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <input class=" ml-10 easyui-validatebox" maxlength="120" value="<?= $model->firm_compaddress?>" data-options="required:'true'" style="width: 236px;" id="pdfirm-firm_compaddress" name="PdFirm[firm_compaddress]">
            <span class="ml-10 cursor-pointer color-1f7ed0" id="cancel">取消</span>
        </div>
        <div class="mb-10" id="address2">
            <label class="width-100">公司地址</label>
            <input class="" style="width: 663px" readonly value="<?= $model->firmAddress->fullAddress ?>">
            <span class="ml-10 cursor-pointer color-1f7ed0" id="upAddress">修改</span>
        </div>
        <div class="space-10"></div>
        <div class="inline-block">
            <label for="pdfirm-firm_compprincipal" class="width-100">厂商负责人</label>
            <input class="width-150" maxlength="20" value="<?=$model->firm_compprincipal?>" name="PdFirm[firm_compprincipal]">
            <label for="pdfirm-firm_comptel" class="width-100">公司联系电话</label>
            <input class="width-150" maxlength="20" value="<?=$model->firm_comptel?>" name="PdFirm[firm_comptel]">
            <label for="pdfirm-firm_compmail" class="width-100">邮箱</label>
            <input class="width-150" maxlength="20" value="<?=$model->firm_compmail?>" name="PdFirm[firm_compmail]">
        </div>
        <div class="space-10"></div>
        <div class="inline-block">
            <label for="pdfirm-firm_contaperson" class="width-100">厂商联系人</label>
            <input class="width-150" maxlength="20" value="<?=$model->firm_contaperson?>" name="PdFirm[firm_contaperson]">
            <label for="pdfirm-firm_contaperson_tel" class="width-100">联系人电话</label>
            <input class="width-150" maxlength="20" value="<?=$model->firm_contaperson_tel?>" name="PdFirm[firm_contaperson_tel]">
            <label for="pdfirm-firm_contaperson_mail" class="width-100">邮箱</label>
            <input class="width-150" maxlength="20" value="<?=$model->firm_contaperson_mail?>" name="PdFirm[firm_contaperson_mail]" id="pdfirm-firm_contaperson_mail">
        </div>
        <div class="space-10"></div>
        <!--<div class="mb-10">
            <?/*= $form->field($model, 'firm_remark1',['labelOptions' => ['class' => 'width-100 vertical-top','label'=>'备注'],'inputOptions'=>['class' => 'width-665','rows'=>"5"]])->textarea(['maxlength' => true]) */?>
        </div>-->
        <div class="mb-10">
            <label class="width-100 vertical-top">备注</label>
            <textarea class="width-665" maxlength="120" rows="5" id="pdfirm-firm_remark1" name="PdFirm[firm_remark1]"><?=$model->firm_remark1?></textarea>
        </div>
    </div>
    <div>
        <h2 class="head-second">
            创建人信息
        </h2>
        <div class="mb-10">
            <label class="width-100"><span class="red">*</span>工号</label>
            <input class="width-150" readonly="readonly" value="<?php echo $staff['staff_code']?>">
            <label class="width-100 ml-260">创建人</label>
            <input type="text" value="<?php echo $staff['staff_name'] ?>" class="width-150" readonly="readonly" >
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-100">部门</label>
            <input class="width-150" value="<?php echo $staff['organization_code'] ?>-<?php echo $codeName['organization_name']?>" readonly="readonly">
            <label class="width-100 ml-260">邮箱</label>
            <input class="width-150" id="" name="" value="<?php echo yii::$app->user->identity->staff->staff_email?>" readonly="readonly">
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-100">联系方式</label>
            <input class="width-150" value="<?php echo yii::$app->user->identity->staff->staff_mobile?>" readonly="readonly">
        </div>
    </div>
    <div class="space-40 ml-50"></div>
    <button class="button-blue-big ml-320" type="submit" id="sub">确认</button>
    <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">取消</button>
    <?php ActiveForm::end(); ?>
    <script>
        $(function () {
            ajaxSubmitForm($("#add-form"),function(){
                if($("#firmCate").val() == null){
                    layer.alert("请选择分级分类!",{icon:2,time:5000});
                    return false;
                }
                return true;
            });
            $('#firmCate').change(function () {
                $('#pdfirm-firm_category_id').val($('#firmCate').val())
                //console.log($('#pdfirm-firm_category_id').val());
            });
            $('#address').hide();
            //修改地址
            $('#upAddress').click(function () {
                $('#address').show();
                $('#upAddress').hide();
                $('#address2').hide()
            });
            $('#cancel').click(function () {
                $('#address').hide();
                $('#upAddress').show();
                $('#address2').show();
            });
            $('#goback').click(function () {
                history.back(-1);
            });
            //設置品牌名兩項必填一項
            $("#firm_brand2").change(function () {
                var div1 = document.getElementById('firm_brand1');//兼容所有瀏覽器
                var div2 = document.getElementById('firm_brand2');
                if (div2.value!=''){
                    div1.className = 'width-200';
                }else if(div1.value==''&&div2.value=='') {
                    div1.className = 'width-200 easyui-validatebox validatebox-text validatebox-invalid';
                }else {
                    div1.className = 'width-200';
                }
            });
            $("#firm_brand1").change(function () {
                var brand1 = document.getElementById("firm_brand1");
                var brand2 = document.getElementById("firm_brand2");
                if (brand1.value !=''){
                    brand1.className = 'width-200';
                }else if (brand1.value=='' && brand2.value==''){
                    brand1.className = 'width-200 easyui-validatebox validatebox-text validatebox-invalid';
                }else {
                    brand1.className = "width-200"
                }
            });
            /**
             * 修改分級分類
             */
            var $ddd = $("#firmCate").select2();
            var $upCateVal = <?= $upCateValue ?>;
            console.log($upCateVal);
            $ddd.val($upCateVal).trigger('change');

            /**
             *地址联动查询
             */
            $('.disName').on("change", function () {
                var $select = $(this);
                getNextDistrict($select);
            });
            //遞歸清除級聯選項
            function clearOption($select) {
                if ($select == null) {
                    $select = $("#disName_1")
                }
                $tagNmae = $select.next().prop("tagName");
                if ($select.next().length != 0 && $tagNmae =='SELECT') {
                    $select.next().html('<option value=>请选择</option>');
                    clearOption($select.next());
                }
            }
            function getNextDistrict($select) {
                var id = $select.val();
                if (id == "") {
                    clearOption($select);
                    return;
                }
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {"id": id},
                    url: "<?=Url::to(['/ptdt/firm/get-district']) ?>",
                    success: function (data) {
                        var $nextSelect = $select.next("select");
                        clearOption($nextSelect);
                        $nextSelect.html('<option value>请选择</option>')
                        if ($nextSelect.length != 0)
                            for (var x in data) {
                                $nextSelect.append('<option value="' + data[x].district_id + '" >' + data[x].district_name + '</option>')
                            }
                    }
                })
            }
        });
    </script>