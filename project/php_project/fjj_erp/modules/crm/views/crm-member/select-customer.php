<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/24
 * Time: 14:09
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\assets\JqueryUIAsset;

JqueryUIAsset::register($this);
?>
<style>
    .ml-30{
        margin-left: 30px;
    }

    .mt-10{
        margin-top: 10px;
    }
</style>
<div class="head-first">选择客户</div>
<div class="ml-30">
    <div class="mb-10 overflow-auto" style="padding: 0 20px">
        <form action="<?= Url::to(['select-customer']) ?>" method="get" id="customer_form" class="float-left">
            <input type="text" name="CrmCustomerInfoSearch[searchKeyword]" class="width-200" style="height: 30px;"
                   value="<?= $queryParam['CrmCustomerInfoSearch']['searchKeyword'] ?>">
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-20', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-20', 'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["select-customer"]).'\'']) ?>
        </form>
    </div>
    <div id="data" style="width:600px;"></div>
</div>
<div class="text-center mt-10">
    <button class="button-blue-big" id="confirm">确&nbsp;定</button>
    <button class="button-white-big ml-20" onclick="close_select()">返回</button>
</div>
<script>
    $(function () {
        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "cust_filernumber", title: "客户编码", width: 200},
                {field: "cust_sname", title: "客户名称", width: 200},
                {field: "cust_shortname", title: "客户简称", width: 180}
            ]],
            onDblClickRow: function () {
                reload();
            },
            onLoadSuccess: function (data) {
                showEmpty($(this), data.total, 0);
            }
        });

        //确定
        $("#confirm").on("click", function () {
            reload();
        });
    })
    function reload() {
        var term = $("#data").datagrid('getSelected');
        if (term == null) {
            parent.layer.alert('请选择一条信息', {icon: 2, time: 5000});
        } else {
            parent.$('#cust_id').val(term.cust_id);
//                parent.$('#member_name').val(term.member_name);
            parent.$('#member_regweb').val(term.member_regweb);
            parent.$('#cust_sname').val(term.cust_sname);
            parent.$("#cust_shortname").val(term.cust_shortname);
            parent.$("#cust_tel1").val(term.cust_tel1);
            parent.$("#member_compzipcode").val(term.member_compzipcode);
            parent.$("#cust_contacts").val(term.cust_contacts);
            parent.$("#cust_position").val(term.cust_position);
            parent.$("#cust_inchargeperson").val(term.cust_inchargeperson);
            parent.$("#cust_regdate").val(term.cust_regdate);
            parent.$("#cust_regfunds").val(Math.round(term.cust_regfunds) || '');
            parent.$("#member_regcurr").val(term.member_regcurr === null ? '100091' : term.member_regcurr);
            parent.$("#cust_compvirtue").val(term.cust_compvirtue);
            parent.$("#member_source").val(term.member_source);
            parent.$("#cust_businesstype").val(term.cust_businesstype);
            parent.$("#member_curr").val(term.member_curr === null ? '100091' : term.member_curr);
            parent.$("#member_compsum").val(Math.round(term.member_compsum) || '');
            parent.$("#member_compsum_currency").val(term.compsum_cur === null ? '100091' : term.compsum_cur);
            parent.$("#cust_pruchaseqty").val(Math.round(term.cust_pruchaseqty) || '');
            parent.$("#pruchaseqty_cur").val(term.pruchaseqty_cur === null ? '100091' : term.pruchaseqty_cur);
            parent.$("#cust_personqty").val(term.cust_personqty);
            parent.$("#member_compreq").val(term.member_compreq);
            parent.$("#member_reqflag").val(term.member_reqflag);
            parent.$("#member_reqitemclass").val(term.member_reqitemclass);
            parent.$("#member_marketing").val(term.member_marketing);
            parent.$("#member_reqdesription").val(term.member_reqdesription);
            parent.$("#member_compcust").val(term.member_compcust);
            parent.$("#member_compwebside").val(term.member_compwebside);
            parent.$("#member_businessarea").html(term.member_businessarea);
            parent.$("#member_remark").html(term.member_remark);
            parent.$("#cust_tel2").val(term.cust_tel2);
            parent.$("#cust_email").val(term.cust_email);
            if (term.cust_district_2 != null) {
                parent.$("#disName_1").html('<option value="' + term.country_id + '" >' + term.country_name + '</option>');
                parent.$("#disName_2").html('<option value="' + term.provice_id + '" >' + term.provice_name + '</option>');
                parent.$("#disName_3").html('<option value="' + term.city_id + '" >' + term.city_name + '</option>');
                parent.$("#disName_4").html('<option value="' + term.cust_district_2 + '" >' + term.area_name + '</option>');
            } else {
                parent.$("#disName_1").val(term.country_id);
                parent.$("#disName_2").val(term.provice_id);
                parent.$("#disName_3").val(term.city_id);
                parent.$("#disName_4").val(term.cust_district_2);
            }

            parent.$("#cust_adress").val(term.cust_adress);
            parent.$(".remove").validatebox('validate');
//            console.log(term.aid);return false;
            /*会员类型判断 F1678086 10/30*/
//            if (term.crtf_pkid != null && term.yn == 1) {
//                parent.$('.member_type').val('100071');
//                parent.$("#memberType").text(term.memberType);
//            }
//            if (term.aid != null && term.crtf_pkid == null || term.aid != null && term.crtf_pkid != null) {
//                parent.$('.member_type').val('100072');
//                parent.$("#memberType").text(term.memberType);
//            }
//            if (!(term.crtf_pkid != null && term.yn == 1 || term.aid != null && term.crtf_pkid == null || term.aid != null && term.crtf_pkid != null)) {
//                parent.$('.member_type').val('100070');
//                parent.$("#memberType").text('普通会员');
//            }
            if (term.cust_code != null) {
                parent.$('.member_type').val('100071');
                parent.$("#memberType").text('认证会员');
            }
            if (term.credit_code != null) {
                parent.$('.member_type').val('100072');
                parent.$("#memberType").text('账信会员');
            }
//            if (!(term.crtf_pkid != null && term.yn == 1 || term.aid != null && term.crtf_pkid == null || term.aid != null && term.crtf_pkid != null)) {
//                parent.$('.member_type').val('100070');
//                parent.$("#memberType").text('普通会员');
//            }
            parent.$.fancybox.close();
        }
    }

</script>