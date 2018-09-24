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
use \yii\helpers\Html;
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
    .mt-10{
        margin-top: 10px;
    }
</style>
<div class="head-first">选择客户</div>
<div class="ml-60">
    <div class="mb-10 overflow-auto" style="padding: 0 20px">
        <form action="<?= Url::to(['select-customer']) ?>" method="get" id="customer_form" class="float-left">
            <input type="text" name="CrmCustomerInfoSearch[searchKeyword]" style="width: 200px; height: 30px;"
                   value="<?= $queryParam['CrmCustomerInfoSearch']['searchKeyword'] ?>" placeholder="请输入客户名称">
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-20', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-20', 'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["select-customer"]).'\'']) ?>
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

//        /*新增厂商*/
//        $("#add-firm").on('click', function () {
//            $("#add-firm").fancybox({
//                padding: [],
//                fitToView: false,
//                width: 800,
//                height: 570,
//                autoSize: false,
//                closeClick: false,
//                openEffect: 'none',
//                closeEffect: 'none',
//                type: 'iframe',
//                href:"<?//= \yii\helpers\Url::to(['create-customer']) ?>//"
//            });
//        })

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
                {field: "cust_filernumber", title: "客户编码", width: 200},
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

        function reload() {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                parent.layer.alert('请选择一条信息', {icon: 2, time: 5000});
            } else {
                $(".customer", window.parent.document).show();
                $(".sih_id", window.parent.document).val(obj.sih_id);
                $(".cust_id", window.parent.document).val(obj.cust_id);
                $(".cust_sname", window.parent.document).text(obj.cust_sname);
                $(".cust_shortname", window.parent.document).text(obj.cust_shortname);
                $(".cust_contacts", window.parent.document).text(obj.cust_contacts);
                $(".cust_position", window.parent.document).text(obj.cust_position);
                $(".cust_tel1", window.parent.document).text(obj.cust_tel1);
                $(".cust_tel2", window.parent.document).text(obj.cust_tel2);
                $(".cust_email", window.parent.document).text(obj.cust_email);
                $(".is_member", window.parent.document).text(obj.cust_ismember);
                $(".member_regtime", window.parent.document).text(obj.member_regtime);
                $(".member_type", window.parent.document).text(obj.memberType);
                $(".member_name", window.parent.document).text(obj.member_name);
                $(".cust_businesstype", window.parent.document).text(obj.businessType);
                $(".member_businessarea", window.parent.document).text(obj.member_businessarea);
                $(".member_reqdesription", window.parent.document).text(obj.member_reqdesription);
                $(".member_reqitemclass", window.parent.document).text(obj.productType);
                $(".member_reqflag", window.parent.document).text(obj.latDemand);
                $(".member_reqcharacter", window.parent.document).text(obj.member_reqcharacter);
                if (obj.district[0] != null) {
                    $(".cust_adress", window.parent.document).text(obj.district[0]['district_name']+obj.district[1]['district_name']+obj.district[2]['district_name']+obj.district[3]['district_name']+obj.cust_adress);
                }
                parent.$.fancybox.close();
            }
        }


    })
</script>