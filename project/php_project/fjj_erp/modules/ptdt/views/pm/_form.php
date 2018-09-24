<?php
/**
 * PM表单
 * F3858995
 *2016/10/24
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<div class="ml-50">
    <?php $form = ActiveForm::begin(
        [
            'id' => "form",
            'enableAjaxValidation' => true,
        ]
    ); ?>
    <div class="mb-20">
        <label class="width-100" for="pdproductmanager-staff_code">工号</label>
        <input type="text" maxlength="30" id="pdproductmanager-staff_code" class="width-200 easyui-validatebox"
               name="PdProductManager[staff_code]" data-options="required:'true',validType:'unique'" data-act="<?=Url::to(['validate'])?>" data-attr="staff_code" data-id="<?=$model->pm_id?>"
               placeholder="请输入工号"
               value="<?= isset($model->staff_code)?$model->staff_code : null ?>">
    </div>
    <div class="mb-20">
        <label class="width-100" for="pdproductmanager-staff_code" >商品经理人</label>
        <input type="text" maxlength="30" id="staffName" class="width-200 easyui-validatebox"
                data-options="required:'true'"
            <?=isset($model)?null:'validType="checkStaff"' ?>
               value="<?= isset($model->staff_code)?$model->staff_code : null ?>" disabled>
    </div>
    <div class="mb-20">
        <div class="inline-block field-pdproductmanager-pm_level">
            <label class="width-100" for="pdproductmanager-pm_level">资位</label>
            <?=Html::textInput("","",["id"=>"position","class"=>"width-200 easyui-validatebox","disabled"=>"disabled"])?>
        </div>
    </div>

    <div class="mb-20">
        <div class="inline-block field-pdproductmanager-parent_id">
            <label class="width-100" for="pdproductmanager-parent_id"> 商品分类</label>
            <div class="multi-select">
                <?php
                    $catArr=array_filter(explode(",",$model->category_id));
                    krsort($catArr);
                    $catNameArr=[];
                    if(count($catArr)>0){
                        foreach($catArr as $v){
                            $catNameArr[]=$options["category"][$v];
                        }
                    }
                    $catStr=implode(",",$catArr);
                    $catNameStr=implode(",",array_filter($catNameArr));
                ?>
                <input class="width-200 multi-select-title easyui-validatebox" data-options="required:'true'" placeholder="请选择" value="<?=$catNameStr?>" readonly>
                <input name="PdProductManager[category_id]" class="multi-select-hidden" type="hidden" value="<?=$catStr?>">
                <ul>
                <?php foreach($options["category"] as $cat_id=>$category){ ?>
                    <li><label><input <?=in_array($cat_id,$catArr)?"checked":""?> value="<?=$cat_id?>" class="multi-select-checkbox" type="checkbox" /> <span><?=$category?></span></label></li>
                <?php } ?>
                </ul>
            </div>
        </div>
    </div>


    <div class="mb-20">
        <div class="inline-block field-pdproductmanager-parent_id">
            <label class="width-100" for="pdproductmanager-parent_id">商品负责人</label>
            <?=Html::dropDownList("PdProductManager[parent_id]",$model->parent_id,$options['leader'],["prompt"=>"请选择","id"=>"pdproductmanager-parent_id","class"=>"width-200"])?>
        </div>
    </div>

    <div class="mt-20">
        <div class="inline-block field-pdproductmanager-pm_desc">
            <label class="width-100 vertical-top" for="pdproductmanager-pm_desc">备注</label>
            <textarea id="pdproductmanager-pm_desc" class="width-300" name="PdProductManager[pm_desc]"
                      rows="4"><?=isset($model)?$model->pm_desc:null ?></textarea>

        </div>
    </div>
    <div class="mt-40">
        <button type="submit" class="ml-100 button-blue-big">提交</button>
        <button type="button" class="button-white-big ml-20"
                onclick="parent.$.fancybox.close()">返回
        </button>
    </div>
</div>
<?php $form->end(); ?>
<script>
    $(function () {
        $("#pdproductmanager-staff_code").change(function(){
            $("#staffName").val("");
            $("#position").val("");
            var code=$(this).val();
            getStaffInfo(code);
        });

        <?php if( isset($model) && $model->pm_level ==1 ) {?>
         $("#parent-div").show();
        <?php } ?>
        ajaxSubmitForm($("#form"),'',function(data){
            parent.$.fancybox.close();
            parent.layer.alert(data.msg,{icon:1});
            parent.$("#data").datagrid("reload");
        });

        $("#pdproductmanager-pm_level").on("change", function () {
            var $parent = $("#parent-div");
            if ($(this).val() == "1") {
                $("#parent-div select").validatebox(
                    {
                        required: true
                    }
                )
            } else {
                $("#parent-div select").validatebox(
                    {
                        required: false
                    }
                )
                $("#parent-div select").find('option[value=""]').prop("selected", "selected");
            }
        });

            $(".multi-select li").bind("click",function(event){
                event.stopPropagation();
            });
        $(".multi-select-checkbox").bind("click",function(){
            var tmp=new Array();
            var tmp2=new Array();
            $(this).parents(".multi-select").find(".multi-select-checkbox:checked").each(function(){
                tmp.push($(this).val());
                tmp2.push($(this).next().text());
            });
            $(this).parents(".multi-select").find(".multi-select-hidden").val(tmp.join(","));
            $(this).parents(".multi-select").find(".multi-select-title").val(tmp2.join(",")).validatebox();
        });

        $(".multi-select-title").click(function(event){
            event.stopPropagation();
            $(this).parents(".multi-select").find("ul").toggle();
        });
        $("body").click(function(){
            $(".multi-select ul").hide();
        });
    })



    function getStaffInfo(code){
        $.ajax({
            type:"get",
            url: "<?=Url::to(['/hr/staff/get-staff-info']) ?>",
            dataType:"json",
            data:{
                code:code
            },
            success:function(data){
                var name = data.staff_name;
                var position = data.position;
                $("#staffName").val(name);
                $("#position").val(position);
            }
        });
    }
</script>


<style>
    .multi-select {
        position: relative;
        width: 200px;
        height: 25px;
        margin-left:100px;
        margin-top:-25px;
    }
    .multi-select-title{
        height: 25px;
        line-height: 25px;
        position: absolute;
        margin-left: 4px;
        display: block;
    }
    .multi-select ul{
        position: absolute;
        width:180px;
        padding:0px 10px;
        height: 250px;
        top:28px;
        border:#ccc 1px solid;
        overflow-x: hidden;
        overflow-y:auto;
        display: none;
        background:#f0f1f4;
    }
    .multi-select label{
        line-height: 25px;
    }
    .multi-select label:after,.multi-select label:before{
        content:""
    }
    .multi-select label span{
        vertical-align: top;
    }
</style>