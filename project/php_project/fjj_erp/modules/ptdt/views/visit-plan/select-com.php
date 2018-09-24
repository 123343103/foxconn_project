<?php
use app\assets\JqueryUIAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\common\models\BsPubdata;
JqueryUIAsset::register($this);
?>
<style>
    .fancybox-wrap{
        top:  0px !important;
        left: 0px !important;
    }
</style>

<div class="pop-head">
    <p>选择厂商</p>
</div>
<div class="select-com">
    <div style="height:30px;">

        <?php $form = ActiveForm::begin([
            'action' => ['/ptdt/visit-plan/select-com'],
            'method' => 'get',
        ]); ?>

        <p class="float-left">
            <input type="text" name="PdVisitPlanSearch[firmMessage]" class="width-200" value="<?= $queryParam['PdVisitPlanSearch']['firmMessage'] ?>"><img id="img" src="<?= Url::to('@web/img/icon/search.png') ?>" title="搜索">
            <input type="submit" id="sub" style="display:none;">
        </p>
        <?= Html::button('重置', ['class' => 'button-blue','style' => 'height:30px;margin-left:10px;', 'onclick'=>'window.location.href="'.Url::to(['select-com']).'"']) ?>
        <p class="float-right mt-5"><a><button type="button" class="button-blue text-center" style="width:80px;" id="add-firm">新增厂商</button></a></p>
    </div>
    <div class="space-10"></div>
    <?php ActiveForm::end(); ?>
</div>
<div class="ml-20">
    <div id="data" style="width:752px;"></div>
</div>
<div class="text-center mt-10">
    <button class="button-blue-big" id="check">确定</button>
    <button class="button-white-big ml-20" onclick="close_select()">返回</button>
</div>
<div class="space-10"></div>

<script>
    $(function(){
        $("#img").click(function(){
            $("#sub").click();
        });
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "firm_id",
            loadMsg: false,
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            singleSelect: true,
            columns: [[
                {field: "firm_sname", title: "公司全称", width: 200},
                {field: "firm_shortname", title: "公司简称", width: 150},
                {field: "firm_compaddress", title: "公司地址", width: 250,formatter: function(value,row,index){
                    return row.firmAddress?row.firmAddress.fullAddress:'';
                }},
                {field: "firm_type", title: "类型", width: 100, formatter: function (value, row, index) {
                    return row.firmType?row.firmType:'';
            }},
            ]],
            onDblClickRow: function(){
                reload();
            },
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
            }
        });
        $("#check").click(function () {
            reload();
        });
        function reload(){
            var data = $("#data").datagrid("getSelected");
            $("#firm_id",window.parent.document).val(data.firm_id.decode());
            $("#firm_sname",window.parent.document).val(data.firm_sname.decode());
            $("#firm_shortname",window.parent.document).val(data.firm_shortname.decode());
            $("#firm_compaddress",window.parent.document).val(data.firmAddress.fullAddress.decode());
            $("#firm_type",window.parent.document).val(data.firmType.decode());
            $("#firm_sname",window.parent.document).validatebox();
            parent.$.fancybox.close();
        }

        /*新增厂商*/
        $("#add-firm").on('click',function(){
            $("#add-firm").attr("href", "<?= \yii\helpers\Url::to(['add-firm']) ?>");
            $("#add-firm").fancybox({
                padding: [],
                fitToView: false,
                width: 800,
                height: 570,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
            });
        })
    });
</script>