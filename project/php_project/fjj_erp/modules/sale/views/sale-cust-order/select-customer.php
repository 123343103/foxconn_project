<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;

JqueryUIAsset::register($this);
?>
<div class="head-first">选择客户</div>
<div style="margin: 0 20px 20px;">
    <div class="mb-10">
        <?php ActiveForm::begin(['id' => 'customer_form', 'method' => 'get', 'action' => Url::to(['select-customer'])]); ?>
        <label class="width-60">关键词</label>
        <input type="text" class="width-200" name="searchKeyword" value="<?= $params['searchKeyword'] ?>">
        <button type="submit" class="button-blue">查询</button>
        <button type="button" class="button-white" onclick="window.location.href='<?= Url::to(['select-customer']) ?>'">
            重置
        </button>
        <!--        <a href="-->
        <? //= Url::to(['/crm/crm-customer-info/create']) ?><!--" target="_blank" class="float-right text-center"-->
        <!--           style="width:80px;line-height:25px;background-color:#1f7ed0;color:#ffffff;">新增客户</a>-->
        <button type="button" class="button-blue float-right create-customer hiden" style="width:75px;">新增客户</button>
        <?php ActiveForm::end(); ?>
    </div>
    <div id="customer_data" style="width:100%;"></div>
</div>
<div class="text-center mb-20">
    <button type="button" class="button-blue mr-20" id="confirm_customer">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<script>
    $(function () {
        $("#customer_data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            singleSelect: true,
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "applyno", title: "客户代码", width: 190},
                {field: "cust_sname", title: "客户名称", width: 360},
                {field: "cust_shortname", title: "客户简称", width: 152},
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this), data.total, 0);
                datagridTip("#customer_data");
            },
            onDblClickRow: function () {
                $("#confirm_customer").click();
            }
        });

        //确定
        $("#confirm_customer").click(function () {
            var obj = $("#customer_data").datagrid('getSelected');
            if (obj == null) {
                parent.layer.alert('请选择客户！', {icon: 2, time: 5000});
                return false;
            }
            if (obj.applyno == null) {
                parent.layer.alert('请先申请客户代码！', {icon: 2, time: 5000});
                return false;
            }
            $("#confirm_customer").prop("disabled", true);
            parent.$(".old_credits").remove();
            parent.$("#cust_id").val(obj.cust_id).change();
            parent.$("#apply_code").val(obj.applyno).change();
            parent.$("#cust_sname").val($("<div>" + obj.cust_sname + "</div>").text()).change();
            parent.$("#invoice_title").val($("<div>" + obj.cust_sname + "</div>").text()).change();
            parent.$("#member_curr").val($("<div>" + obj.member_curr + "</div>").text()).change();
            parent.$("#customerType").val($("<div>" + ((obj.customerType != null) ? obj.customerType : "") + "</div>").text()).change();
            parent.$("#customerManager").val($("<div>" + ((obj.customerManager != null) ? obj.customerManager : "") + "</div>").text()).change();
            parent.$("#csarea_name").val($("<div>" + ((obj.csarea_name != null) ? obj.csarea_name : "") + "</div>").text()).change();
            parent.$("#cust_contacts").val($("<div>" + ((obj.cust_contacts != null) ? obj.cust_contacts : "") + "</div>").text()).change();
            parent.$("#cust_tel2").val($("<div>" + ((obj.cust_tel2 != null) ? obj.cust_tel2 : "") + "</div>").text()).change();
            parent.$("#cust_tel1").val($("<div>" + ((obj.cust_tel1 != null) ? obj.cust_tel1 : "") + "</div>").text()).change();
            parent.$("#customer_address").val($("<div>" + ((obj.cust_adress != null) ? obj.cust_adress : "") + "</div>").text()).change();
            parent.$("#invoice_title_address").val($("<div>" + ((obj.invoice_title_address != null) ? obj.invoice_title_address : "") + "</div>").text());
            parent.$("#invoice_mail_address").val($("<div>" + ((obj.invoice_mail_address != null) ? obj.invoice_mail_address : "") + "</div>").text());
            if (obj.invoice_title_district != null) {
                parent.changeAddress(obj.invoice_title_district, 1);
            } else {
                parent.$("#title_address_province").html('<option value>请选择...</option>');
                parent.$("#title_address_city").html('<option value>请选择...</option>');
                parent.$("#title_address_town").html('<option value>请选择...</option>');
            }
            if (obj.invoice_mail_district != null) {
                parent.changeAddress(obj.invoice_mail_district);
            } else {
                parent.$("#send_address_province").html('<option value>请选择...</option>');
                parent.$("#send_address_city").html('<option value>请选择...</option>');
                parent.$("#send_address_town").html('<option value>请选择...</option>');
            }
//            if (obj.bsp_id != null) {
//                for (i = 0; i < parent.$('#cur_id option').length; i++) {
//                    if (parent.$('#cur_id option')[i].value == obj.bsp_id) {
//                        parent.$('#cur_id option')[i].selected = "selected";
//                    }
//                }
//            }
            if (obj.invoice_type != null) {
                for (i = 0; i < parent.$('#invoice_type option').length; i++) {
                    if (parent.$('#invoice_type option')[i].value == obj.invoice_type) {
                        parent.$('#invoice_type option')[i].selected = "selected";
                        parent.invoiceChange = true;
                        parent.$('#invoice_type').change();
                    }
                }
            }
            parent.$("#edu").val($("<div>" + ((obj.cust_creditqty != null) ? obj.cust_creditqty : 0) + "</div>").text()).change();
            parent.$("#deliveryAddress").val(obj.delivery_address).change();
            parent.$.fancybox.close();
        });

        // 新增客户
        $(".create-customer").on('click', function () {
            $.fancybox({
                href: "<?= \yii\helpers\Url::to(['/crm/crm-plan-manage/create-customer?style=']) . 'padding-left:65px' ?>",
                padding: 0,
                margin: 0,
                autoSize: false,
                width: 800,
                height: 520,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
            });
        });
    })
</script>