<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/3/22
 * Time: 10:50
 * 新增厂商弹出层
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
//use app\assets\MultiSelectAsset;
//MultiSelectAsset::register($this);
?>

<style>
    .select2-selection{
        width: 550px;/*分級分類輸入框寬度*/
        height: auto;/*分級分類輸入框高度樣式*/
        overflow:hidden;
    }
    .kv-plugin-loading,.select2-selection__clear{
        display:none;
    }
    .select2-selection__clear{
        top:-150px;
    }

</style>
    <h1 class="head-first">
        新增厂商信息
    </h1>
    <div class="mb-30">
        <?php $form = ActiveForm::begin([
            'id' => 'add-form',
        ]); ?>
        <div class="inline-block">
            <label for="pdfirm-firm_sname" class="width-100"><span class="red">*</span>厂商注册名称</label>
            <input class="width-150 easyui-validatebox" maxlength="30" data-options="required:'true',validType:'maxLength[10]'" value="" name="PdFirm[firm_sname]" id="firm_sname">
            <label for="pdfirm-firm_shortname" class="width-170">简称</label>
            <input class="width-150"  value="" name="PdFirm[firm_shortname]" maxlength="20">
        </div>
        <div class="space-10"></div>
        <div class="inline-block">
            <label for="pdfirm-firm_ename" class="width-100">英文名称</label>
            <input class="width-150 easyui-validatebox" maxlength="60" value="" name="PdFirm[firm_ename]">
            <label for="pdfirm-firm_eshortname" class="width-170">英文简称</label>
            <input class="width-150"  value="" maxlength="60" name="PdFirm[firm_eshortname]">
        </div>
        <div class="space-10"></div>
        <div class="inline-block">
            <label for="pdfirm-firm_brand" class="width-100 ">品牌</label>
            <input class="width-150 easyui-validatebox" maxlength="60" id="firm_brand1" data-options="required:'true'" value="" name="PdFirm[firm_brand]">
            <label for="pdfirm-firm_brand_english" class="width-170">品牌英文名</label>
            <input class="width-150" id="firm_brand2" maxlength="60"  value="" name="PdFirm[firm_brand_english]">
            <p class="red float-right">必填项,二选一填写</p>
        </div>
        <div class="space-10"></div>
        <div class="inline-block ">
            <label for="pdfirm-firm_source" class="width-100"><span class="red">*</span>来源</label>
            <select name="PdFirm[firm_source]" class="width-150 easyui-validatebox" data-options="required:'true'" id="pdfirm-firm_source">
                <option value="">请选择</option>
                <?php foreach ($downList['firmSource'] as $key => $val) {?>
                    <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label for="pdfirm-firm_type" class="width-170">类型</label>
            <select name="PdFirm[firm_type]" class="width-150 easyui-validatebox"  id="pdfirm-firm_type">
                <option value="">请选择</option>
                <?php foreach ($downList['firmType'] as $key => $val) {?>
                    <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>

            <div class="help-block"></div>
        </div>
        <div class="space-10"></div>

        <div class="inline-block ">
            <label for="pdfirm-firm_position" class="width-100">地位</label>
            <select name="PdFirm[firm_position]" class="width-150 easyui-validatebox"  id="pdfirm-firm_position">
                <option value="">请选择</option>
                <?php foreach ($downList['firmLevel'] as $key => $val) {?>
                    <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>

            <label for="pdfirm-firm_issupplier" class="width-170"><span class="red">*</span>是否为集团供应商</label>
            <select name="PdFirm[firm_issupplier]" class="width-150 easyui-validatebox" data-options="required:'true'" id="pdfirm-firm_issupplier">
                <option value="">请选择</option>
                <option value="1">是</option>
                <option value="0">否</option>
            </select>

            <div class="help-block"></div>
        </div>
        <div class="space-10"></div>
        <label for="pdfirm-firm_category_id" class="width-100 vertical-top"><span class="red">*</span>分级分类</label>
        <?php echo Select2::widget([
            'name' => 'firm_category_id',
            'id'=>'firmCate',
            'data' => ArrayHelper::map($downList['category'],'category_id','category_sname'),
            'maintainOrder' => true,
            'options' => ['placeholder'=>'请选择..','multiple'=>true],
            'pluginOptions' => [
                'tags' => true,
                'maximumInputLength' => 10,
                'allowClear' => true ,
            ],
        ]);?>
        <input class="width-150" type="hidden" id="pdfirm-firm_category_id" value="" name="PdFirm[firm_category_id]">
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-100"><span class="red">*</span>公司地址</label>
            <select class="width-80 disName easyui-validatebox" data-options="required:'true'" id="disName_1">
                <option value="">请选择...</option>
                <?php foreach ($downList['country'] as $key => $val) { ?>
                    <option
                        value="<?= $val['district_id'] ?>"><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select class="width-80 disName easyui-validatebox"  data-options="required:'true'" id="disName_2">
                <option value="">请选择...</option>
            </select>
            <select class="width-80 disName easyui-validatebox" data-options="required:'true'" id="disName_3">
                <option value="">请选择...</option>
            </select>
            <select class="width-80 disName easyui-validatebox" data-options="required:'true'" id="disName_4" name="PdFirm[firm_district_id]">
                <option value="">请选择...</option>
            </select>

            <input class=" ml-10 easyui-validatebox width-200" maxlength="120" data-options="required:'true'" id="pdfirm-firm_compaddress" name="PdFirm[firm_compaddress]">
        </div>
        <div class="space-10"></div>
        <div class="inline-block">
            <label for="pdfirm-firm_compprincipal" class="width-100">厂商负责人</label>
            <input class="width-120"  value="" maxlength="20" name="PdFirm[firm_compprincipal]">
            <label for="pdfirm-firm_comptel" class="width-100">公司联系电话</label>
            <input class="width-120"  value="" maxlength="20" name="PdFirm[firm_comptel]">
            <label for="pdfirm-firm_compmail"  class="width-100">邮箱</label>
            <input class="width-120"  value="" maxlength="20" name="PdFirm[firm_compmail]">
        </div>
        <div class="space-10"></div>
        <div class="inline-block">
            <label for="pdfirm-firm_contaperson" class="width-100">厂商联系人</label>
            <input class="width-120"  value="" maxlength="20" name="PdFirm[firm_contaperson]">
            <label for="pdfirm-firm_contaperson_tel" class="width-100">联系人电话</label>
            <input class="width-120"  value="" maxlength="20" name="PdFirm[firm_contaperson_tel]">
            <label for="pdfirm-firm_contaperson_mail" class="width-100">邮箱</label>
            <input class="width-120"  value="" maxlength="20" name="PdFirm[firm_contaperson_mail]" id="pdfirm-firm_contaperson_mail">
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-100 vertical-top">备注</label>
            <textarea class="width-500" rows="3" maxlength="120" id="pdfirm-firm_remark1" name="PdFirm[firm_remark1]"></textarea>
        </div>
    </div>
    <div class="text-center mb-20">
        <button class="button-blue-big" type="button" id="sub">确定</button>
        <button class="button-white-big ml-20" type="button" onclick="close_select();">返回</button>
    </div>
    <?php ActiveForm::end(); ?>
    <script>
        $(function () {
//            ajaxSubmitForm($("#add-form"));//ajax提交
            $("#sub").click(function(){
                if($("#firmCate").val() == null){
                    layer.alert("请选择分级分类!",{icon:2,time:5000});
                    return false;
                }else{
                    if (!$('#add-form').form('validate')) {
                        return false;
                    }
                    $.ajax({
                        type:'post',
                        dataType:'json',
                        data:$("#add-form").serialize(),
                        url:"<?= \yii\helpers\Url::to(['/ptdt/firm/create']) ?>?type=1",
                        success:function(msg){
                            if(msg.status == 1){
                                layer.alert("添加成功!",{icon:1,end: function () {
                                    parent.window.location.reload();
                                }});
                            }
                        },
                        error:function(){
                                layer.alert("添加失败",{icon:1,end: function () {
                                    parent.window.location.reload();
                                }});
                        }
                    })
                }
            });
            $('#firmCate').change(function () {
                $('#pdfirm-firm_category_id').val($('#firmCate').val())
                //console.log($('#pdfirm-firm_category_id').val());
            });
            $('#goback').click(function () {
                history.back(-1);
            });
            //設置品牌名兩項必填一項
            $("#firm_brand2").change(function () {
                var div1 = document.getElementById('firm_brand1');//兼容所有瀏覽器
                var div2 = document.getElementById('firm_brand2');
                if (div2.value!=''){
                    div1.className = 'width-150';
                }else if(div1.value==''&&div2.value=='') {
                    div1.className = 'width-150 easyui-validatebox validatebox-text validatebox-invalid';
                }else {
                    div1.className = 'width-150';
                }
            });
            $("#firm_brand1").change(function () {
                var brand1 = document.getElementById("firm_brand1");
                var brand2 = document.getElementById("firm_brand2");
                if (brand1.value !=''){
                    brand1.className = 'width-150';
                }else if (brand1.value=='' && brand2.value==''){
                    brand1.className = 'width-150 easyui-validatebox validatebox-text validatebox-invalid';
                }else {
                    brand1.className = "width-150"
                }
            });

            /**
             *地址联动查询
             */

            $('.disName').on("change", function () {
                var $select = $(this);
                var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
                getNextDistrict($select,$url,"select");
            });
            $("#disName_4").on('change',function(){
                var str = $("#disName_4").find("option:selected").text();
                var val = $("#disName_4").find("option:selected").val();
                if(str == '其它区'){
                    $("#disName_4").next().append('<option value="'+ val +'">其它</option>');
                }
            })
            $("#firm_sname").blur(function(){
                $("#firm_sname").validatebox({
                    required:true,
                    delay:700,
                    validType:"remote['<?=\yii\helpers\Url::to(['firm-sname'])?>?name="+$("#firm_sname").val()+"','code']",
                    invalidMessage:'厂商已存在',
                    missingMessage: '厂商名称不能为空'
                })
            })
        });
    </script>