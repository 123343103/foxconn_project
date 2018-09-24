<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/6/27
 * Time: 15:27
 */
use app\assets\MultiSelectAsset;
use yii\widgets\ActiveForm;
MultiSelectAsset::register($this);
//echo "<pre>";print_r($result);die();

?>
<style>
    .ms-container{
        margin-left: 50px !important;
    }
</style>
<?php $form = ActiveForm::begin([
    'id' => 'add-form',
//        'action'=>['/crm/crm-customer-manage/create-visit','id'=>$id]
]) ?>
<h1 class="head-first"><?= $str ?>栏位编辑</h1>
<div class="mb-10 mt-30">
    <select multiple="multiple" class="my-select" name="my-select[]">
        <?php foreach ($result as $key => $value){ ?>
            <option value="<?=$value['field_id'] ?>"><?= $value['field_title'] ?></option>
        <?php } ?>
    </select>
    <div style="background: red;position: absolute;left:50%;top:200px;margin-left:-15px;width:30px;height: 30px;background: #f0f1f4;">
        <i id="unSelectAll" class="icon-chevron-left" style="cursor: pointer;"></i>
        &nbsp;&nbsp;
        <i id="selectAll"  class="icon-chevron-right" style="cursor: pointer;"></i>
    </div>
</div>
<div class="space-40"></div>
<div class="mb-20 text-center">
    <button class="button-blue-big" type="button" id="sub">确定</button>
    <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
</div>
<?php ActiveForm::end() ?>
<script>
    $(function(){
//        ajaxSubmitForm($("#add-form"));
        $('#sub').on('click',function () {
            $.ajax({
                type:'post',
                dataType:'json',
                data:$('#add-form').serialize(),
                url:'<?= \yii\helpers\Url::to(["sys-list"]) ?>?id='+<?= $id ?>,
                success:function(data){
                    if(data.flag == 1){
                        layer.alert(data.msg,{icon:1,end: function () {
                            parent.window.location.reload();
                        }});
//                        parent.$("#data").datagrid("reload");
//                        parent.$.fancybox.close();
                    }
                },
                error:function(data){
                    layer.alert(data.msg,{icon:2});
                }
            })
        });
        $("#selectAll").click(function(){
            $(".my-select").multiSelect("select_all");
        });
        $("#unSelectAll").click(function(){
            $(".my-select").multiSelect("deselect_all");
        });
        $(".my-select").multiSelect({
            'selectableOptgroup': true,
            selectableHeader: "<div class='custom-header text-center'>显示列</div>",
            selectionHeader: "<div class='custom-header text-center'>隐藏列</div>",
        });
        <?php foreach ( $select as $key => $val ){ ?>
        $(".my-select").multiSelect("select","<?= $val['field_id'] ?>");
        <?php } ?>
    })
</script>

