<?php
use app\assets\JqueryUIAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\common\models\BsPubdata;
JqueryUIAsset::register($this);
?>


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
    <button class="button-white-big ml-20" onclick="close_select()">取消</button>
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
                {field: "firm_code", title: "供应商编码", width: 300},
                {field: "firm_sname", title: "供应商全称", width: 250},
                {field: "firm_shortname", title: "供应商简称", width: 150},
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
            $("#pdsupplier-supplier_sname",window.parent.document).val(data.firm_sname.decode());
            $("#pdsupplier-firm_id",window.parent.document).val(data.firm_id);
            $("#pdsupplier-supplier_shortname",window.parent.document).val(data.firm_shortname.decode());
            $("#pdsupplier-supplier_brand",window.parent.document).val(data.firm_brand.decode());
            $("#pdsupplier-supplier_compaddress",window.parent.document).val(data.firm_compaddress.decode());
            $("#pdsupplier-supplier_source",window.parent.document).val(data.firm_source);
            $("#pdsupplier-supplier_type",window.parent.document).val(data.firm_type);
            parent.$.fancybox.close();
        }

//        /*新增厂商*/
//        $("#add-firm").on('click',function(){
//            $("#add-firm").attr("href", "<?//= \yii\helpers\Url::to(['add-firm']) ?>//");
//            $("#add-firm").fancybox({
//                padding: [],
//                fitToView: false,
//                width: 700,
//                height: 500,
//                autoSize: false,
//                closeClick: false,
//                openEffect: 'none',
//                closeEffect: 'none',
//                type: 'iframe'
//            });
//        })
    });
</script>