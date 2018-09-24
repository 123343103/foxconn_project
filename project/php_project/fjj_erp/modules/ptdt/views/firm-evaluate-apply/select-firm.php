<?php
/**
 * User: F1677929
 * Date: 2016/11/3
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
$get = Yii::$app->request->get();
if (!isset($get['PdFirmEvaluateApplySearch'])){
    $get['PdFirmEvaluateApplySearch'] = null;
}
?>
<div class="head-first">选择厂商</div>
<div style="margin: 0 20px 10px;">
    <div class="mb-10 overflow-auto">
        <form action="<?= Url::to(['select-firm']) ?>" method="get" id="firm_form" class="float-left">
            <input type="text" name="PdFirmEvaluateApplySearch[searchKeyword]" class="width-200" style="height: 30px;" value="<?= $get['PdFirmEvaluateApplySearch']['searchKeyword'] ?>">
            <img id="img" src="<?= Url::to('@web/img/icon/search.png') ?>" alt="搜索" style="cursor: pointer; vertical-align: bottom; margin-left: -4px;">
            <button id="reset" type="button" class="button-blue" style="height: 30px;">重&nbsp;置</button>
        </form>
        <p class="float-right mt-5"><a href="<?= Url::to(['/ptdt/firm/create']); ?>" target="_blank"><button type="button" class="button-blue text-center" style="width:80px;">新增厂商</button></a></p>
    </div>
    <div id="data" style="width: 100%;"></div>
</div>
<div class="text-center">
    <button type="button" class="button-blue-big" id="confirm">确&nbsp;定</button>
    <button type="button" class="button-white-big ml-20" id="cancel">取&nbsp;消</button>
</div>
<script>
    $(function () {
        //搜索
        $("#img").on("click", function () {
            $("#firm_form").submit();
        });

        //重置
        $("#reset").on("click", function () {
            window.location.href = "<?= Url::to(['select-firm']) ?>";
        });

        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "pfr_id",
            pagination: true,
            singleSelect: true,
            pageSize: 8,
            pageList: [8],
            columns: [[
                {field:"name",title:"厂商全称",width:200,formatter:function(value,row){
                    return row.firm.name;
                }},
                {field:"shortname",title:"厂商简称",width:100,formatter:function(value,row){
                    return row.firm.shortname;
                }},
                {field:"compaddress",title:"厂商地址",width:300,formatter:function(value,row){
                    return row.firm.compaddress;
                }},
                {field:"firmType",title:"厂商类型",width:94,formatter:function(value,row){
                    return row.bsPubdata.firmType;
                }}
            ]]
        });

        //确定
        $("#confirm").on("click",function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                parent.layer.alert('请选择一条信息',{icon:2,time:5000});
            } else {
                $("#firm_id",window.parent.document).val(obj.firm_id);
                $("#firm_sname",window.parent.document).val(obj.firm.name).change();
                $("#firm_shortname",window.parent.document).val(obj.firm.shortname);
                $("#firm_source",window.parent.document).val(obj.firm.firmSource);
                $("#firm_type",window.parent.document).val(obj.firm.firmType);
                $("#firm_position",window.parent.document).val(obj.firm.firmPosition);
                $("#firm_issupplier",window.parent.document).val(obj.firm.issupplier);
                $("#firm_compaddress",window.parent.document).val(obj.firm.compaddress);
                $("#firm_compprincipal",window.parent.document).val(obj.firm.firmDutyPerson);
                $("#firm_comptel",window.parent.document).val(obj.firm.firmDutyPersonTel);
                $("#firm_compmail",window.parent.document).val(obj.firm.firmDutyPersonEmail);
                $("#firm_contaperson",window.parent.document).val(obj.firm.firmContacts);
                $("#firm_contaperson_tel",window.parent.document).val(obj.firm.firmContactsTel);
                $("#firm_contaperson_mail",window.parent.document).val(obj.firm.firmContactsEmail);
                parent.$.fancybox.close();
            }
        });

        //取消
        $("#cancel").on("click", function () {
            parent.$.fancybox.close();
        });
    })
</script>