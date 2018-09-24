<?php
use app\assets\JqueryUIAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\common\models\BsPubdata;
JqueryUIAsset::register($this);
$get = Yii::$app->request->get();
if(!isset($get['SellerSearch'])){
    $get['SellerSearch']=null;
}
$param = Yii::$app->request->queryParams;
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
<div class="pop-head">
    <p>选择销售人员</p>
</div>
<div class="select-com">
    <div style="height:30px;">

        <?php $form = ActiveForm::begin([
            'action' => ['/sale/store-setting/select-seller'],
            'method' => 'get',
        ]); ?>

        <p class="float-left">
            <input type="text" name="SellerSearch[staff_code]" class="width-200" value="<?= $get['SellerSearch']['staff_code'] ?>"><img id="img" src="<?= Url::to('@web/img/icon/search.png') ?>" title="搜索">
            <input type="submit" id="sub" style="display:none;">
        </p>
        <?= Html::resetButton('重置', ['class' => 'button-blue','style'=>'height:30px;margin-left:10px;' ,'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["select-seller"]).'\'']) ?>
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
        });
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "staff_id",
            loadMsg: false,
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            singleSelect: true,
            columns: [[
                {field: "staff_code", title: "销售人员工号", width: 200},
                {field: "staff_id", title: "销售人员名称", width: 200, formatter: function (value, row, index) {
                        if (row.sellerInfo) {
                            return row.sellerInfo.staff_name;
                        } else {
                            return null;
                        }
                    }
                },
                {field: "sale_area", title: "营销区域", width: 150, formatter: function (value, row, index) {
                        if (row.saleArea) {
                            return row.saleArea.csarea_name;
                        } else {
                            return null;
                        }
                    }
                },
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });
        $("#check").click(function(){
            var selected = $("#data").datagrid("getSelected");
            var obj = "<?= $param['obj'] ?>";
//            $("#shengzhang",window.parent.document).val(selected.staff_id);
            $.ajax({
                type:"GET",
                dataType:"json",
                data:{"code":selected.staff_code},
                url:"<?= Url::to(['/sale/store-setting/get-seller']) ?>",
                success:function(data){
//                    console.log(obj); return false;
                    if (obj == 'sz') {
                        $("#sz_id",window.parent.document).val(data.staff_id);
                        $("#sz_name",window.parent.document).val(data.staff_name);
                    } else if (obj == 'dz') {
                        $("#dz_id",window.parent.document).val(data.staff_id);
                        $("#dz_name",window.parent.document).val(data.staff_name);
                    }
                    parent.$.fancybox.close();
                }
            })
        })
        $("#close").click(function(){
            parent.$.fancybox.close();
        });
    });
</script>