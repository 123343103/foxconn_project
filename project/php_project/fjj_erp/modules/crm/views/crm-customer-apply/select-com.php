<?php
use app\assets\JqueryUIAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\common\models\BsPubdata;
JqueryUIAsset::register($this);
$get = Yii::$app->request->get();
if(!isset($get['CrmCustomerInfoSearch'])){
    $get['CrmCustomerInfoSearch']=null;
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
<div class="pop-head">
    <p>选择客戶</p>
</div>
<div class="select-com">
    <div style="height:30px;">

        <?php $form = ActiveForm::begin([
            'action' => ['/crm/crm-customer-apply/select-com'],
            'method' => 'get',
        ]); ?>

        <p class="float-left">
            <input type="text" name="searchKeyword" class="width-200" value="<?= $get['CrmCustomerInfoSearch']['cusMessage'] ?>">
            <img id="img" src="<?= Url::to('@web/img/icon/search.png') ?>" title="搜索">
            <input type="submit" id="sub" style="display:none;">
        </p>
        <?= Html::resetButton('重置', ['class' => 'button-blue','style'=>'height:30px;margin-left:10px;' ,'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["select-com"]).'\'']) ?>
        <!--<p class="float-right mt-5"><a href="<?/*= Url::to(['/crm/crm-customer-info/create']); */?>" target="_blank"><button type="button" class="button-blue text-center" style="width:80px;">新增客户</button></a></p>-->
        <!--<p class="float-right mt-5"><a><button type="button" class="button-blue text-center" style="width:80px;" id="add-customer">新增客户</button></a></p>-->
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
        $("#add-customer").on('click',function(){
            $("#add-customer").attr("href", "<?= \yii\helpers\Url::to(['/crm/crm-customer-apply/add-create']) ?>");
            $("#add-customer").fancybox({
                padding: [],
                fitToView: false,
                width: 700,
                height: 540,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe'
            });
        })
    })
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: false,
            pagination: true,
            pageSize: 5,
            pageList: [5,10,15],
            singleSelect: true,
            columns: [[
                {field: "cust_filernumber", title: "档案编号", width: 250},
                {field: "cust_shortname", title: "客户简称", width: 200},
                {field: "cust_sname", title: "客户名称", width: 260},
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });
        $("#check").on("click",function () {
            var obj = $("#data").datagrid('getSelected');
            //console.log(obj.cust_regname);
            if (obj == null) {
                parent.layer.alert('请选择一条信息',{icon:2,time:5000});
            } else {
                parent.location.href="<?=Url::to(['customer-info'])?>?id="+obj.cust_id;
                }
                parent.$.fancybox.close();

        });
        $("#close").click(function(){
            parent.$.fancybox.close();
        })
    })
</script>