<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use app\widgets\ueditor\Ueditor;
?>
    <style>
        ._pic{width: 120px;height: 120px;background: #5bb75b;float: left;margin-top: 5px;margin-right: 10px;}
        ._ueditor{width: 635px;margin-left: 140px;}
        .head-three{background: #c9c9c9;line-height: 20px;}
        #img_dir{  float: left;  margin-left: 10px;  height:120px;   position:relative;  width: 120px;  }
        #uploadfile{  height:120px;  width: 120px;  font-size: 30px;  position:absolute;  right:0;  top:0;  opacity: 0;  filter:alpha(opacity=0);  cursor:pointer;  }
        #img_dir img{  width: 120px;height: 120px;margin-top: 4px;  }
        ._remove{float: right;font-size: 14px;cursor: pointer; }
        ._first{margin-top: 98px;font-size: 18px;cursor: pointer; }

        .label-width{
            width:140px;
        }
        .value-width{
            width:200px;
        }
        #product_table td{
            word-break: break-all;
            word-wrap: break-word;
        }
    </style>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<h2 class="head-three">
    <i class="icon-caret-down"></i>
    <a href="javascript:void(0)">基本信息</a>
</h2>
<div>
    <div class="mb-10">
        <label class="label-width label-align">商品類別：</label>
        <input type="hidden" name="catg_id" value="<?=$model['catg_id']?>">
        <span class="value-align"><?=$model["cat_three_level"];?></span>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">品名：</label>
        <input name="pdt_name" class="value-width value-align  add-require easyui-validatebox " readonly="true" data-options=""    type="text" value="<?=$model['pdt_name']?>" maxlength="100">
        <label class="label-width label-align">品牌：</label>
        <?=Html::dropDownList("brand_id",$model["brand_id"],$options["brands"],["prompt"=>"请选择","class"=>"value-width value-align"])?>
    </div>
    <div class="mb-10">
        <label class="label-width label-align" for="">商品标题：</label>
        <input class="value-width value-align" type="text" name="pdt_title" value="<?=$model['pdt_title']?>" maxlength="200">

        <label class="label-width label-align" for="">商品关键字：</label>
        <input class="value-width value-align" type="text" name="pdt_keyword" value="<?=$model['pdt_keyword']?>" maxlength="200">
        <span class="red">多个关键词用“，”字符间隔</span>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">商品标签：</label>
        <input class="value-width value-align" type="text" name="pdt_label" value="<?=$model['pdt_label']?>" maxlength="100">
        <label class="label-width label-align"><span class="red">*</span>商品属性：</label>
        <?=Html::dropDownList("pdt_attribute",$model["pdt_attribute"],$options["pdt_attribute"],["prompt"=>"请选择","class"=>"value-width value-align easyui-validatebox","data-options"=>"required:true"])?>
    </div>
    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>商品形态：</label>
        <?=Html::dropDownList("pdt_form",$model["pdt_form"],$options["pdt_form"],["prompt"=>"请选择","class"=>"value-width value-align easyui-validatebox","data-options"=>"required:true"])?>
        <label class="label-width label-align"><span class="red">*</span>计量单位：</label>
        <?=Html::dropDownList("unit",$model["unit"],$options["pdt_unit"],["prompt"=>"请选择","class"=>"value-width value-align easyui-validatebox","data-options"=>"required:true"])?>
    </div>
</div>
<div class="space-10"></div>
<div class="space-10"></div>

<h2 class="head-three">
    <i class="icon-caret-down"></i>
    <a href="javascript:void(0)">图片和详细说明</a>
</h2>
<div class="ml-10">
    <div class="mb-10">
        <label class="label-width label-align" style="float: left">商品图片：</label>
        <div style="margin-left:140px;">
            <?=\app\widgets\upload\PreviewUploadWidget::widget([
                    "name"=>"pdt_img[]",
                    "extensions"=>"png,jpg",
                    "items"=>$model['pdt_img']
            ])?>
        </div>
    </div>
    <div class="mb-10">
        <label class="label-width label-align" style="float: left">3D图片转动方式：</label>
        <select class="value-width value-align easyui-validatebox" name="">
            <option value>-请选择-</option>
            <option value="">全球</option>
            <option value="">半球</option>
            <option value="">360</option>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align" style="float: left">上传3D图片：</label>
        <input class="upBtn" data-target-type="text" data-server="<?=Url::to(['upload3d'])?>" data-target="#upload3D" style="outline: none;" type="button" value="选择文件">
        <input style="position: relative;left:-5px;top:-1px;width:300px;text-indent: 1em;" type="text" id="upload3D" name="upload3D" value="<?=$model['upload3D']?>" readonly />
    </div>
    <div class="mb-10" style="clear: both">
        <p style="margin-left: 140px;margin-top: -10px">只能上传压缩档(*.7z)</p>
    </div>
    <div class="mb-10">
        <label class="label-width label-align" style="float: left">商品视频：</label>
        <input type="button" id="_vedio" value="管理商品视频"/>
    </div>
    <label class="label-width label-align" style="float: left"><span class="red">*</span>详细说明：</label>
    <div class="_ueditor">
            <?=Ueditor::widget([
                    'width'=>'800',
                    'height'=>'300',
                    'id'=>'editor',
                    'name'=>'details',
                    'content'=>$model["details"]
            ])?>
    </div>
</div>
<div style="height: 30px"></div>
<h2 class="head-three">
    <i class="icon-caret-down"></i>
    <a href="javascript:void(0)">关联商品</a>
</h2>
<div class="mb-10">
    <table class="table">
        <thead>
        <tr>
            <th width="100"><input type='checkbox'></th>
            <th width="100">商品编号</th>
            <th>商品名称</th>
            <th width="100">类别名称</th>
        </tr>
        </thead>
        <?php if(count($model["related_product"])>0){ ?>
        <tbody id="product_table">
        <?php foreach ($model["related_product"] as $product){?>
                <tr>
                    <td><input type='checkbox' name="RPdtPdt[r_pdt_pkid][]" value="<?=$product['pdt_pkid']?>" <?=$product["selected"]?"checked":""?> ></td>
                    <td><?=$product["pdt_no"]?></td>
                    <td><?=$product["pdt_name"]?></td>
                    <td><?=$product["catg_name"]?></td>
                </tr>
        <?php } ?>
        </tbody>
        <?php }else{ ?>
            <tfoot>
            <tr>
                <td colspan="4">没有相关数据！</td>
            </tr>
            </tfoot>
        <?php } ?>
    </table>
</div>
<div class="text-center">
    <button type="submit" id="_save" class="button-blue-big save-form">保存进入下一步</button>
    <button class="button-white-big" onclick="window.location.href='<?=Url::to(["edit2",'id'=>\Yii::$app->request->get('id'),'status'=>\Yii::$app->request->get('status')])?>'" type="button">查看相关料号</button>
    <button class="button-white-big" onclick="window.location.href='<?= Url::to(["index"]) ?>'" type="button">返回</button>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        ajaxSubmitForm($("#add-form"),function(){
            var content=UE.getEditor('editor').getContent();
            if(!content){
                layer.alert("请填写详细说明！",{icon:2});
                return false;
            }
            return true;
        });


        //修改页面各部分收放
        $(".head-three").next("div:eq(0)").css("display", "block");
        $(".head-three>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-right");
            $(this).prev().toggleClass("icon-caret-down");
        });
        //复选框操作
        var $thr = $('table thead tr');
        var $tbr = $('table tbody tr');
        var $checkAllTh = $('<th><input type="checkbox" id="checkAll" name="checkAll" /></th>');
        var $checkAll = $thr.find('input');
        $checkAll.click(function(event){
            /*将所有行的选中状态设成全选框的选中状态*/
            $tbr.find('input').prop('checked',$(this).prop('checked'));
            /*阻止向上冒泡，以防再次触发点击操作*/
            event.stopPropagation();
        });
        /*点击全选框所在单元格时也触发全选框的点击操作*/
        $checkAllTh.click(function(){
            $(this).find('input').click();
        });
        $("#_edits").click(function () {
            $("input,select").prop("disabled",false);
            $("input,select").change(function(){$(this).prop("disabled",false)});
//            $("input").removeAttr("disabled");
//            $("select").removeAttr("disabled");
            $(this).css("display","none");
            $("#_save").removeAttr("style");
            $("#img_dir").removeAttr("style");
            $("._remove").removeAttr("style");
            $("._first").removeAttr("style");
            return false;
        })




        //上传图片自动提交
        $('input[name=uploadfile]').change(function() {
            $("#myform").submit();
            location.reload();
        });
        //"X"号删除图片
        $("._remove").click(function () {
            $(this).parent("li").remove();
        });
        //"v"号设为首图
        $("._first").click(function () {
            var showhtml = $(this).parent()[0].outerHTML;
            $(this).parent().remove();
            $('.firstpic').after(showhtml);
        })

    });

//    var ue = UE.getEditor('editor');
</script>