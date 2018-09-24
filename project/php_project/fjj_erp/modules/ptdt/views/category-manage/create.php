<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/9/11
 * Time: 下午 02:35
 */
use yii\helpers\Url;
use app\assets\MultiSelectAsset;
MultiSelectAsset::register($this);
$this->title = '添加类别信息';
?>
<style>
    .width-120{
        width: 120px;
        text-align: center;
    }
    .fancybox-wrap {
        top: 0px !important;
        left: 0px !important;
    }
    .width-230{
        width: 230px;
    }
    .mt-10{
        margin-top: 10px;
    }
    .space-30{
        width: 100%;
        height: 30px;
    }
    .mt-20{
        margin-top: -20px;
    }
</style>
<div class="no-padding width-600">
    <div class="create-plan">
        <h1 class="head-first"> 添加类别信息</h1>
        <?php $form = \yii\widgets\ActiveForm::begin([
            'id' => 'create',
        ]) ?>
<!--        ,validType:'catgNo',delay:10000000-->
<!--        data-url="--><?//= \yii\helpers\Url::to(['/ptdt/category-manage/get-catgno-info']) ?><!--"-->
        <div class="mt-10 ml-30">
            <label class="width-120"> <span class="red" title="*">*</span>类别编码：</label>
            <input type="text" class="catg_no width-230 ml-60  easyui-validatebox"
                   data-options="required:'true'" name="BsCategory[catg_no]"  maxlength="20"  id="catg_no"/>
        </div>
        <div class="mt-10 ml-30">
            <label class="width-120"> <span class="red" title="*">*</span>类别名称：</label>
            <input type="text" class="catg_name width-230 ml-60  easyui-validatebox"
                   data-options="required:'true'" name="BsCategory[catg_name]" maxlength="100"/>
        </div>
        <div class="mt-10 ml-30">
            <label class="width-120"> <span class="red" title="*">*</span>类别层级：</label>
            <select name="BsCategory[catg_level]" class="width-230 ml-60  easyui-validatebox validateboxs" data-options="required:true" id="catg_level">
                <option value="">--请选择--</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>
        <div class="mt-10 ml-30">
            <label class="width-120"> <span class="red" title="*">*</span>上级类别：</label>
            <select name="BsCategory[p_catg_id]"  class="width-230 ml-60  easyui-validatebox validateboxs" id="p_catg_id">
<!--                <option value="">--请选择--</option>-->
<!--                <option value="1">1</option>-->
<!--                <option value="2">2</option>-->
<!--                <option value="3">3</option>-->

            </select>
        </div>
        <div class="mt-10 ml-30">
            <label class="width-120">排序：</label>
            <input type="text" class="orderby width-230 ml-60  "
                    name="BsCategory[orderby]" readonly="readonly" id="orderby"/>
        </div>
        <div class="mt-10 ml-30">
            <label class="width-120">是否有效：</label>
            <select name="BsCategory[isvalid]" class="width-230 ml-60  easyui-validatebox validateboxs"
                    data-options="required:true">
                <option value="1">是</option>
                <option value="0">否</option>
            </select>
        </div>
<!--        <div class="mt-10 ml-30">-->
<!--            <label class="width-120">是否属于设备专区：</label>-->
<!--            <select name="BsCategory[yn_machine]" class="width-230 ml-20 easyui-validatebox validateboxs"-->
<!--                    data-options="required:true" id="yn_machine">-->
<!--                <option value="1">是</option>-->
<!--                <option value="0">否</option>-->
<!--            </select>-->
<!--        </div>-->
        <div class="space-30"></div>
        <div class="mb-10 text-center">
            <button class="button-blue-big" type="submit" id="create-warehouse-submit">保存</button>
            <button class="button-white-big ml-20 close" type="button">取消</button>
        </div>
        <?php $form->end(); ?>
        <div class="space-30"></div>
    </div>
</div>
<script>
    $(function () {
        $(document).ready(function() {
            ajaxSubmitForm($("#create"));
        })
        var catgno="<?=$catgno?>";
        if(catgno=="EQ"){
            $("#yn_machine").val("1");
        }else {
            $("#yn_machine").val("0");
        }
    })

    //根据类别层级获取上级类别
    $("#catg_level").on("change",function () {
        var $select= $(this);
        var $url = "<?=Url::to(['/ptdt/category-manage/get-pcatgname']) ?>";
        var catg_level=$("#catg_level").val();//类别层级
        //console.log(catg_level);
        if(catg_level!=1){
            $("#p_catg_id").attr("data-options","required:'true'").addClass("validatebox-invalid");
            getpcatgname($select, $url);//获取上级类别
        } else {
            $("#p_catg_id").removeAttr("data-options").removeClass("validatebox-invalid");
            $("#p_catg_id").html('<option value="0">无</option>');
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {"pcatgid":0},
                url: "<?=Url::to(['/ptdt/category-manage/get-orderbyno']) ?>",
                success:function (data) {
                    document.getElementById('orderby').value=data[0].orderby+1;
                }
            })
        }
    })
    function getpcatgname($select, url) {
        var catg_level = $select.val()-1;
//        if (catg_level == "") {
//            clearOption($select);
//            return;
//        }
        $.ajax({
            type: "get",
            dataType: "json",
            async: false,
            //data: {"catglevel": catg_level,"catgno":catgno},
            data: {"catglevel": catg_level},
            url: url,
            success: function (data) {
                var $nextSelect = $("#p_catg_id");
                clearCatgname($nextSelect);
                $nextSelect.html('<option value>--请选择--</option>');
                if ($nextSelect.length != 0)
                    for (var x in data) {
                        $nextSelect.append('<option value="' + data[x].catg_id + '" data-id="'+data[x].p_catg_id+'">' + data[x].catg_name + '</option>')
                    }
            }
        })
    }
    //级联清除上级类别选项
    function clearCatgname($select) {
        if ($select == null) {
            $select = $("#catg_level")
        }
        $tagNmae = $select.parent().next().children("select").prop("tagName");
        if ($select.parent().next().children("select").length != 0 && $tagNmae == 'SELECT') {
            $select.parent().next().children("select").html('<option value=>请选择...</option>');
        }
    }
    //根据上级类别获取排序编号
    $("#p_catg_id").on("change",function () {
        var catgid=$(this).val();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"pcatgid":catgid},
            url: "<?=Url::to(['/ptdt/category-manage/get-orderbyno']) ?>",
            success:function (data) {
                document.getElementById('orderby').value=data[0].orderby+1;
            }
        })
    })

//    function getcatgnoinfo(obj) {
//        var no=$(obj).val();
//        if(!no){
//            return;
//        }
//        $.ajax({
//            type: 'GET',
//            dataType: 'json',
//            data: {"no": no},
//            url: "<?//=Url::to(['/ptdt/category-manage/get-catgno-info']) ?>//",
//            success: function (data) {
//                if (data) {
//                    //$(obj).val("");
//                    $("#catg_no").attr("data-options","required:'true'").addClass("validatebox-invalid");
//                    //$("#catg_no").focus();
//                     layer.alert("该类别编码已存在,   请重新输入！", {icon: 2, time: 5000});
////                     $("#catg_no").focus();
//                    //$(obj).focus();
//                }
//            }
//        });
//    }

    $(".close").click(function () {
        parent.$.fancybox.close();
    });
//    //--类别编码验证--add by lx
//    catgNo:{
//        validator:function(value){
//            var str=$.ajax({
//                url:$(this).data().url,
//                data:{"id":value},
//                async:false,//必须同步
//                cache:false
//            }).responseText;
//            str=$.trim(str);
//            var $parentElem=$(this).parent();
//            if($parentElem[0].tagName=='TD'){
//                $parentElem=$parentElem.parent();
//            }
//            if(str==''){
//                return true;
//            }
//            return false;
//        },
//        message:'该类别编码已经存在！'
//    },
//    //--类别编码验证完---

</script>
