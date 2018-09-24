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
$this->title = '修改类别信息';
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
<h1 class="head-first"> 修改类别信息</h1>
<div class="content">
        <?php $form = \yii\widgets\ActiveForm::begin([
            'id' => 'update',
        ]) ?>
        <div class="mt-20">

            <label class="width-120">类别编码：</label>
            <input type="text" class="catg_no width-230 ml-60 "
                   name="BsCategory[catg_no]" readonly="readonly" value="<?=$bscategoryInfo["catg_no"]?>"/>
        </div>
        <div class="mt-10">

            <label class="width-120">  <span class="red" title="*">*</span>类别名称：</label>
            <input type="text" class="catg_name width-230 ml-60  easyui-validatebox"
                   data-options="required:'true',validType:['length[0,100]']" name="BsCategory[catg_name]" value="<?=$bscategoryInfo["catg_name"]?>"/>
        </div>
        <div class="mt-10">

            <label class="width-120"><span class="red" title="*">*</span>类别层级：</label>
            <select name="BsCategory[catg_level]" class="width-230 ml-60  easyui-validatebox validateboxs" data-options="required:true" id="catg_level" disabled="disabled" >
                <option value="1"  <?= $bscategoryInfo['catg_level'] == '1' ? 'selected' : '' ?>>1</option>
                <option value="2"  <?= $bscategoryInfo['catg_level'] == '2' ? 'selected' : '' ?>>2</option>
                <option value="3"  <?= $bscategoryInfo['catg_level'] == '3' ? 'selected' : '' ?>>3</option>
            </select>
        </div>
        <div class="mt-10">

            <label class="width-120"><span class="red" title="*">*</span>上级类别：</label>
            <select name="BsCategory[p_catg_id]"  class="width-230 ml-60  easyui-validatebox validateboxs" id="p_catg_id" disabled="disabled" >
                <option value="<?=$bscategoryInfo['p_catg_id']?>"><?= $bscategoryInfo['p_catg_name']==null||""?"无":$bscategoryInfo['p_catg_name']?>
                </option>
            </select>
        </div>
        <div class="mt-10">
            <label class="width-120">排序：</label>
            <input type="text" class="orderby width-230 ml-60  "
                   name="BsCategory[orderby]" readonly="readonly" id="orderby" value="<?=$bscategoryInfo["orderby"]?>"/>
        </div>
        <div class="mt-10">
            <label class="width-120">是否有效：</label>
            <select name="BsCategory[isvalid]" class="width-230 ml-60  easyui-validatebox validateboxs"
                    data-options="required:true">
                <option value="1" <?= $bscategoryInfo['isvalid'] == '1' ? 'selected' : '' ?>>是</option>
                <option value="0" <?= $bscategoryInfo['isvalid'] == '0' ? 'selected' : '' ?>>否</option>
            </select>
        </div>
        <div class="space-30"></div>
        <div class="mb-10 text-center">
            <button class="button-blue-big" type="submit" id="create-warehouse-submit">保存</button>
            <button class="button-white-big ml-20 close" type="button">取消</button>
        </div>
        <?php $form->end(); ?>
        <div class="space-30"></div>
</div>
<script>
    $(function () {
        $(document).ready(function() {
            ajaxSubmitForm($("#update"));
        })
    })

    //根据类别层级获取上级类别
    $("#catg_level").on("change",function () {
        var $select= $(this);
        var $url = "<?=Url::to(['/ptdt/category-manage/get-pcatgname']) ?>";
        var catg_level=$("#catg_level").val();//类别层级
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
    //                if (data.length>0) {
    //                    //$(obj).val("");
    //                    return layer.alert("该类别编码已存在,   请重新输入！", {icon: 2, time: 5000});
    //                    //$(obj).focus();
    //                }
    //            }
    //        });
    //    }

    $(".close").click(function () {
        parent.$.fancybox.close();
    });

</script>
