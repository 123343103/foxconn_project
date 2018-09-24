<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/24
 * Time: 14:09
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;

JqueryUIAsset::register($this);
?>
<style>
    .panel {
        width: 620px;
        padding: 0 20px;
    }
    .fancybox-wrap{
      top:  0px !important;
      left: 0px !important;
    }
    .ml-60 {
        margin-left: 60px;
    }
</style>
<div class="head-first">选择客户</div>
<div class="ml-60">
    <div class="mb-10 overflow-auto" style="padding: 0 20px">
        <form action="<?= Url::to(['select-customer']) ?>" method="get" id="customer_form" class="float-left">
            <input type="text" name="CrmCustomerInfoSearch[searchKeyword]" class="width-200" style="height: 30px;"
                   value="<?= $queryParam['CrmCustomerInfoSearch']['searchKeyword'] ?>">
            <img id="img" src="<?= Url::to('@web/img/icon/search.png') ?>" alt="搜索"
                 style="cursor: pointer; vertical-align: bottom; margin-left: -4px;">
            <?= \yii\helpers\Html::button('重置', ['class' => 'button-blue', 'style' => 'height:30px;', 'onclick' => 'window.location.href="' . Url::to(['select-customer']) . '"']) ?>
        </form>
    </div>
    <div id="data"></div>
</div>
<div class="text-center mt-10">
    <button class="button-blue-big" id="confirm">确&nbsp;定</button>
    <button class="button-white-big ml-20" onclick="close_select()">返回</button>
</div>
<script>
    $(function () {
        //搜索
        $("#img").on("click", function () {
            $("#customer_form").submit();
        });


        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 10,
            pageList: [10, 20],
            columns: [[
                {field: "apply_code", title: "编码", width: 200},
                {field: "cust_sname", title: "客户名称", width: 200},
                {field: "cust_shortname", title: "客户简称", width: 180}
            ]],
            onDblClickRow: function () {
                reload();
            },
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
            }
        });

        //确定
        $("#confirm").on("click", function () {
            reload();
        });
//        function reload() {
//            var obj = $("#data").datagrid('getSelected');
//            if (obj == null) {
//                parent.layer.alert('请选择一条信息', {icon: 2, time: 5000});
//            }else {
//                $(".cust_id", window.parent.document).val(obj.cust_id);
//                $(".cust_name", window.parent.document).val(obj.cust_sname);
//                $(".cust_shortname", window.parent.document).val(obj.cust_shortname);
//                $(".apply_code", window.parent.document).val(obj.apply_code);
//                $(".cust_tel1", window.parent.document).val(obj.cust_tel1);
//                $(".cust_level", window.parent.document).val(obj.cust_level);
//                $(".cust_type", window.parent.document).val(obj.cust_type);
//                $(".cust_salearea", window.parent.document).val(obj.cust_salearea);
//                $(".staff_name", window.parent.document).val(obj.staff_name);
//                $(".staff_mobile", window.parent.document).val(obj.staff_mobile);
//                $(".cust_compvirtue", window.parent.document).val(obj.cust_compvirtue);
//                $(".cust_regfunds", window.parent.document).val(obj.cust_regfunds);
//                $(".member_businessarea", window.parent.document).val(obj.member_businessarea);
//                $(".cust_parentcomp", window.parent.document).val(obj.cust_parentcomp);
//                $(".total_investment", window.parent.document).val(obj.total_investment[0]);
//                $(".investment_currency", window.parent.document).val(obj.total_investment[1]);                                                     $(".receipts", window.parent.document).val(obj.official_receipts[0]);
//                $(".receipts_currency", window.parent.document).val(obj.official_receipts[1]);
//                $(".shareholding_ratio", window.parent.document).val(obj.shareholding_ratio);
//                $(".cust_tax_code", window.parent.document).val(obj.cust_tax_code);
//                $(".member_regcurr", window.parent.document).val(obj.regcurr);
//                $(".legal_person", window.parent.document).val(obj.company_name);
//                $(".cust_contacts", window.parent.document).val(obj.cust_contacts);
//                $(".cust_tel2", window.parent.document).val(obj.cust_tel2);
//                $(".apply_code",window.parent.document).validatebox();
//                parent.$.fancybox.close();
//            }
//        }

        function reload() {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                parent.layer.alert('请选择一条信息', {icon: 2, time: 5000});
            }else {
                $.ajax({
                    url: '<?= Url::to(["check-cust"]) ?>?id=' + obj.cust_id,
                    type: 'post',
                    dataType: 'json',
                    success: function (data) {
                        if(data === true){
                            $(".cust_id", window.parent.document).val(obj.cust_id);
                            $(".cust_name", window.parent.document).val(obj.cust_sname);
                            $(".cust_shortname", window.parent.document).val(obj.cust_shortname);
                            $(".apply_code", window.parent.document).val(obj.apply_code);
                            $(".cust_tel1", window.parent.document).val(obj.cust_tel1);
                            $(".cust_level", window.parent.document).val(obj.cust_level);
                            $(".cust_type", window.parent.document).val(obj.cust_type);
                            $(".cust_salearea", window.parent.document).val(obj.cust_salearea);
                            $(".staff_name", window.parent.document).val(obj.staff_name);
                            $(".staff_mobile", window.parent.document).val(obj.staff_mobile);
                            $(".cust_compvirtue", window.parent.document).val(obj.cust_compvirtue);
                            $(".cust_regfunds", window.parent.document).val(obj.cust_regfunds);
                            $(".member_businessarea", window.parent.document).val(obj.member_businessarea);
                            $(".cust_parentcomp", window.parent.document).val(obj.cust_parentcomp);
                            $(".total_investment", window.parent.document).val(obj.total_investment[0]);
                            $(".investment_currency", window.parent.document).val(obj.total_investment[1]);                                                     $(".receipts", window.parent.document).val(obj.official_receipts[0]);
                            $(".receipts_currency", window.parent.document).val(obj.official_receipts[1]);
                            $(".shareholding_ratio", window.parent.document).val(obj.shareholding_ratio);
                            $(".cust_tax_code", window.parent.document).val(obj.cust_tax_code);
                            $(".member_regcurr", window.parent.document).val(obj.regcurr);
                            $(".legal_person", window.parent.document).val(obj.company_name);
                            $(".cust_contacts", window.parent.document).val(obj.cust_contacts);
                            $(".cust_tel2", window.parent.document).val(obj.cust_tel2);
                            $(".apply_code",window.parent.document).validatebox();
                            parent.$.fancybox.close();
                        }else{
                            layer.alert('该客户已申请',{icon:2});
                        }
                    }
                })
            }
        }


    })
</script>