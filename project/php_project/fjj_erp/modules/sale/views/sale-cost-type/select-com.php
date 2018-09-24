<?php
use app\assets\JqueryUIAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\common\models\BsPubdata;
JqueryUIAsset::register($this);
$get = Yii::$app->request->get();
if(!isset($get['PdFirmReportSearch'])){
    $get['PdFirmReportSearch']=null;
}
?>

<style>
    .panel-body{
        width:763px !important;
        margin-left:20px;
    }
    .datagrid-view2 .datagrid-btable,.datagrid-view2 .datagrid-htable{
        width:701px;
    }
    .datagrid{
        widht:752px;
    }
</style>
<div class="select-com">
    <div style="height:30px;">

        <?php $form = ActiveForm::begin([
            'action' => ['/ptdt/firm-report/select-com'],
            'method' => 'get',
        ]); ?>

        <p class="float-left">
            <input type="text" name="PdFirmReportSearch[firmMessage]" class="width-100" value="<?= $get['PdFirmReportSearch']['firmMessage'] ?>"><img id="img" src="<?= Url::to('@web/img/icon/search.png') ?>" title="搜索">
            <input type="submit" id="sub" style="display:none;">
        </p>
        <?= Html::resetButton('重置', ['class' => 'button-blue','style'=>'height:30px;margin-left:10px;' ,'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["select-com"]).'\'']) ?>
        <!--<p class="float-right mt-5"><a href="<?/*= Url::to(['/ptdt/firm/create']); */?>" target="_blank"><button type="button" class="button-blue text-center" style="width:80px;">新增厂商</button></a></p>-->
    </div>
    <div class="space-10"></div>
    <?php ActiveForm::end(); ?>
</div>

<div id="data"></div>
<div class="text-center mt-10">
    <button class="button-blue-big" id="check">确定</button>
    <button class="button-white-big ml-20" id="close">取消</button>
</div>
<div class="space-10"></div>

<script>
    $(function(){
        $("#img").click(function(){
            $("#sub").click();
        })
    })
    $(function () {
//        alert("<?//=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>//");
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "firm_id",
            loadMsg: false,
            pagination: true,
            pageSize: 5,
            pageList: [5,10,15],
            singleSelect: true,
            columns: [[
                {field: "firm_sname", title: "公司全称", width: 90},
                /*{field: "firm_shortname", title: "公司简称", width: 90},*/
//                {field: "firm_compaddress", title: "公司地址", width: 250},
//                {field:"firm_type", title: "类型", width: 100, formatter: function (value, row, index) {
//                    if (row.bsPubdata) {
//                        return row.bsPubdata.firmType;
//                    } else {
//                        return null;
//                    }
//                }
//                },
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });
        $("#check").click(function(){
            var id = $("#data").datagrid("getSelected")['firm_id'];
            $.ajax({
                type:"get",
                dataType:"json",
                data:{"id":id},
                url:"<?= Url::to(['/ptdt/firm/firm-info']) ?>",
                success:function(data){
                    $("#firm_id",window.parent.document).val(Number(data[0].firm_id));
                    $("#firm_sname",window.parent.document).val(data[0].firm_sname);
                    $("#firm_shortname",window.parent.document).val(data[0].firm_shortname);
                    $("#firm_ename",window.parent.document).val(data[0].firm_ename);
                    $("#firm_eshortname",window.parent.document).val(data[0].firm_eshortname);
                    $("#firm_brand",window.parent.document).val(data[0].firm_brand);
                    $("#firm_brand_english",window.parent.document).val(data[0].firm_brand_english);
                    $("#firm_compaddress",window.parent.document).val(data[0].firm_compaddress);
                    $("#firm_source",window.parent.document).val(data[1].bsp_svalue);
                    $("#firm_type",window.parent.document).val(data[2].bsp_svalue);
                    $("#firm_issupplier",window.parent.document).val(function () {
                        return data[0].firm_issupplier == 1 ? '是' :'否';
                    });
                    $("#firm_category_id",window.parent.document).val(data[3]);
                    parent.$.fancybox.close();
                }
            })
        })
        $("#close").click(function(){
            parent.$.fancybox.close();
        })
    })
</script>
