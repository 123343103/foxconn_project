<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>

<style>
    .panel-body {
        width: 763px !important;
        margin-left: 20px;
    }

    .datagrid-view2 .datagrid-btable, .datagrid-view2 .datagrid-htable {
        width: 701px;
    }

    .datagrid {
        widht: 752px;
    }
</style>
<div class="pop-head">
    <p>选择厂商</p>
</div>
<div class="select-com">
    <div style="height:30px;">

        <?php $form = ActiveForm::begin([
            'action' => ['/ptdt/firm-report/select-com'],
            'method' => 'get',
        ]); ?>

        <p class="float-left">
            <input type="text" name="PdVisitPlanSearch[firmMessage]" class="width-200"
                   value="<?= $queryParam['PdVisitPlanSearch']['firmMessage'] ?>"><img id="img" src="<?= Url::to('@web/img/icon/search.png') ?>" title="搜索">
            <input type="submit" id="sub" style="display:none;">
        </p>
        <?= Html::resetButton('重置', ['class' => 'button-blue', 'style' => 'height:30px;margin-left:10px;', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["select-com"]) . '\'']) ?>
        <p class="float-right mt-5"><a><button type="button" class="button-blue text-center" style="width:80px;" id="add-firm">新增厂商</button></a></p>
    </div>
    <div class="space-10"></div>
    <?php ActiveForm::end(); ?>
</div>

<div id="data"></div>
<div class="text-center mt-10">
    <button class="button-blue-big" id="check">确定</button>
    <button class="button-white-big ml-20" onclick="close_select()">取消</button>
</div>
<div class="space-10"></div>

<script>
    $(function () {
        $("#img").click(function () {
            $("#sub").click();
        });
        /*新增厂商*/
        $("#add-firm").on('click',function(){
            $("#add-firm").attr("href", "<?= \yii\helpers\Url::to(['/ptdt/visit-plan/add-firm']) ?>");
            $("#add-firm").fancybox({
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
            idField: "firm_id",
            loadMsg: false,
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            singleSelect: true,
            columns: [[
                {field: "firm_sname", title: "公司全称", width: 200},
                {field: "firm_shortname", title: "公司简称", width: 150},
                {field: "firm_compaddress", title: "公司地址", width: 250},
                {
                    field: "firm_type", title: "类型", width: 100, formatter: function (value, row, index) {
                    if (row.firmType) {
                        return row.firmType;
                    } else {
                        return null;
                    }
                }
                },
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
            },
            onDblClickRow: function(){
                reload();
            }
        });
        $("#check").click(function () {
            reload();
        });
        /*加载厂商信息*/
        function reload(){
            var id = $("#data").datagrid("getSelected")['firm_id'];
            $("#table_body", window.parent.document).html("");
            $("#clearAll input", window.parent.document).val("");
            $("#clearAll select", window.parent.document).val("");
            $("#clearAll textarea", window.parent.document).val("");
            $("#person_name", window.parent.document).text("");
            $("#vacc_body span", window.parent.document).text("");
            $(".tbm", window.parent.document).remove();
            $(".remove", window.parent.document).remove();
            $(".negotiate", window.parent.document).show();
            $(".analyze", window.parent.document).hide();
            $(".product-table", window.parent.document).hide();
            $(".product-detail", window.parent.document).hide();
            $("#negotiate-abstract", window.parent.document).addClass('click-span').removeClass('unclick-span');
            $("#product-analyze,#data-detail", window.parent.document).addClass('unclick-span').removeClass('click-span');
            $.ajax({
                type: "get",
                dataType: "json",
                data: {"id": id},
                url: "<?= Url::to(['/ptdt/firm-report/firm-info']) ?>",
                success: function (data) {
                    if(data.report !== null){
                        if(data.report.reportStatus !== '呈报完成'){
                            layer.alert("此厂商已在呈报中,无法呈报", {icon: 2});
                        }else{
                            $("#firm_id", window.parent.document).val(data['firm'].firm_id);
                            $("#firm_sname", window.parent.document).val(data['firm'].firm_sname);
                            $("#firm_shortname", window.parent.document).val(data['firm'].firm_shortname);
                            $("#firm_ename", window.parent.document).val(data['firm'].firm_ename);
                            $("#firm_eshortname", window.parent.document).val(data['firm'].firm_eshortname);
                            $("#firm_brand", window.parent.document).val(data['firm'].firm_brand);
                            $("#firm_brand_english", window.parent.document).val(data['firm'].firm_brand_english);
                            $("#firm_compaddress", window.parent.document).val(data['firm'].firm_compaddress);
                            $("#firm_source", window.parent.document).val(data['firm'].firmSource);
                            $("#firm_type", window.parent.document).val(data['firm'].firmType);
                            $(".easyui-validatebox",window.parent.document).validatebox();
                            $("#firm_issupplier", window.parent.document).val(function () {
                                return data['firm'].firm_issupplier == 1 ? '是' : '否';
                            });
                            $("#firm_category_id", window.parent.document).val(data['firm'].category);
                            if (data['report'] !== null) {
                                if (data['child'] !== '') {
                                    $("#pfrc_date", window.parent.document).val(data['child'].pfrc_date);
                                    $("#pfrc_time", window.parent.document).val(data['child'].pfrc_time);
                                    $("#pfrc_location", window.parent.document).val(data['child'].pfrc_location);
                                    if(data['child']['reception'] !== null) {
                                        $("#rece_sname", window.parent.document).val(data['child']['reception'].receSname);
                                        $("#rece_position", window.parent.document).val(data['child']['reception'].recePosition);
                                        $("#rece_mobile", window.parent.document).val(data['child']['reception'].receMobile);
                                        $(".easyui-validatebox",window.parent.document).validatebox();
                                    }
                                    $("#pfrc_person", window.parent.document).val(data['child']['productPerson'].code);
                                    $(".staff_name", window.parent.document).text(data['child']['productPerson'].name);
                                    $(".job_task", window.parent.document).val(data['child']['productPerson'].job);
                                    $(".staff_mobile", window.parent.document).val(data['child']['productPerson'].mobile);
                                    $(".easyui-validatebox",window.parent.document).validatebox();
                                    if (data['child']['staffPerson'].length != 0) {
                                        $("#vacc_body tr", window.parent.document).remove();
                                        for (var i = 0; i < data['child']['staffPerson'].length; i++) {
                                            var accompany = data['child']['staffPerson'][i];
                                            //$("#accompany_code",window.parent.document).val(a.staff_code);
                                            var editTdStr = '<tr>';
                                            editTdStr += '<td>' + '<input type="text" onblur="job_num(this)" class="width-200 no-border text-center" name="vacc[]" value="' + accompany.staff_code + '">' + '</td>';
                                            editTdStr += '<td>' + '<span class="width-200">' + accompany.staff_name + '</span>' + '</td>';
                                            editTdStr += '<td>' + '<span class="width-200">' + accompany.job_task + '</span>' + '</td>';
                                            editTdStr += '<td>' + '<span class="width-200">' + accompany.staff_mobile + '</span>' + '</td>';
                                            editTdStr += '<td>' + '<a onclick="reset(this)">重置</a>  <a onclick="del_table(this)">删除</a>' + '</td>';
                                            $('#vacc_body', window.parent.document).append(editTdStr);
                                        }
                                    }
                                    if(data['child']['analysis'] !== null) {
                                        $("#pdna_annual_sales", window.parent.document).val(data['child']['analysis'].pdna_annual_sales);
                                        $("#pdna_position", window.parent.document).val(data['child']['analysis'].pdna_position);
                                        $("#pdna_loction", window.parent.document).val(data['child']['analysis'].pdna_loction);
                                        $("#pdna_influence", window.parent.document).val(data['child']['analysis'].pdna_influence);
                                        $("#pdna_cooperate_degree", window.parent.document).val(data['child']['analysis'].pdna_cooperate_degree);
                                        $("#pdna_technology_service", window.parent.document).val(data['child']['analysis'].pdna_technology_service);
                                        $("#pdna_others", window.parent.document).val(data['child']['analysis'].pdna_others);
                                        $("#pdna_customer_base", window.parent.document).val(data['child']['analysis'].pdna_customer_base);
                                        $("#pdna_market_share", window.parent.document).val(data['child']['analysis'].pdna_market_share);
                                        $("#pdna_demand_trends", window.parent.document).val(data['child']['analysis'].pdna_demand_trends);
                                        $("#pdna_goods_certificate", window.parent.document).val(data['child']['analysis'].pdna_goods_certificate);
                                        $("#profit_analysis", window.parent.document).val(data['child']['analysis'].profit_analysis);
                                        $("#sales_advantage", window.parent.document).val(data['child']['analysis'].sales_advantage);
                                        $("#value_fjj", window.parent.document).val(data['child']['analysis'].value_fjj);
                                        $("#value_frim", window.parent.document).val(data['child']['analysis'].value_frim);
                                    }
                                    if(data['child']['authorize'] !== null) {
                                        $("#pdaa_agents_grade", window.parent.document).val(data['child']['authorize'].pdaa_agents_grade);
                                        $("#pdaa_authorize_area", window.parent.document).val(data['child']['authorize'].pdaa_authorize_area);
                                        $("#pdaa_sale_area", window.parent.document).val(data['child']['authorize'].pdaa_sale_area);
                                        $("#pdaa_bdate", window.parent.document).val(data['child']['authorize'].pdaa_bdate);
                                        $("#pdaa_edate", window.parent.document).val(data['child']['authorize'].pdaa_edate);
                                        $("#pdaa_settlement", window.parent.document).val(data['child']['authorize'].pdaa_settlement);
                                        $("#pdaa_delivery_day", window.parent.document).val(data['child']['authorize'].pdaa_delivery_day);
                                        $("#pdaa_service", window.parent.document).val(data['child']['authorize'].pdaa_service);
                                        $("#pdaa_delivery_way", window.parent.document).val(data['child']['authorize'].pdaa_delivery_way);
                                    }
                                    if (data['child']['products'].length != 0) {
                                        for (var i = 0; i < data['child']['products'].length; i++) {
                                            var reportChild = data['child']['products'][i];
                                            //添加商品
                                            var tdStr = "<tr data-key='" + i + "'>";
                                            tdStr += "<td>" + reportChild.product_name + "</td>";
                                            tdStr += "<td>" + reportChild.product_size + "</td>";
                                            tdStr += "<td>" + reportChild.product_brand + "</td>";
                                            tdStr += "<td>" + reportChild.delivery_terms + "</td>";
                                            tdStr += "<td>" + reportChild.payment_terms + "</td>";
                                            tdStr += "<td>" + reportChild.product_unit + "</td>";
                                            tdStr += "<td>" + reportChild.currency_type + "</td>";
                                            tdStr += "<td>" + reportChild.price_max + "</td>";
                                            tdStr += "<td>" + reportChild.price_min + "</td>";
                                            tdStr += "<td>" + reportChild.price_range + "</td>";
                                            tdStr += "<td>" + reportChild.price_average + "</td>";
                                            tdStr += "<td>" + reportChild.levelName + "</td>";
                                            tdStr += "<td>" + reportChild.profit_margin + "</td>";
                                            if(reportChild.typeName == null){
                                                tdStr += "<td></td>";
                                                tdStr += "<td></td>";
                                                tdStr += "<td></td>";
                                                tdStr += "<td></td>";
                                                tdStr += "<td></td>";
                                                tdStr += "<td></td>";
                                            }else{
                                                tdStr += "<td>" + reportChild.typeName[0] + "</td>";
                                                tdStr += "<td>" + reportChild.typeName[1] + "</td>";
                                                tdStr += "<td>" + reportChild.typeName[2] + "</td>";
                                                tdStr += "<td>" + reportChild.typeName[3] + "</td>";
                                                tdStr += "<td>" + reportChild.typeName[4] + "</td>";
                                                tdStr += "<td>" + reportChild.typeName[5] + "</td>";
                                            }
                                            tdStr += "<td><a onclick='product_del(this)'>删除</a></td>";
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_name]" value="' + reportChild.product_name + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_size]" value="' + reportChild.product_size + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_brand]" value="' + reportChild.product_brand + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][delivery_terms]" value="' + reportChild.delivery_terms + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][payment_terms]" value="' + reportChild.product_unit + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_unit]" value="' + reportChild.currency_type + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][currency_type]" value="' + reportChild.product_name + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_max]" value="' + reportChild.price_max + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_min]" value="' + reportChild.price_min + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_range]" value="' + reportChild.price_range + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_average]" value="' + reportChild.price_average + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_level]" value="' + reportChild.product_level + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][profit_margin]" value="' + reportChild.profit_margin + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_1]" value="' + reportChild.product_type_1 + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_2]" value="' + reportChild.product_type_2 + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_3]" value="' + reportChild.product_type_3 + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_4]" value="' + reportChild.product_type_4 + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_5]" value="' + reportChild.product_type_5 + '">';
                                            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_6]" value="' + reportChild.product_type_6 + '">';
                                            tdStr += "</tr>";
                                            $('#table_body', window.parent.document).append(tdStr);
                                            $('#table_id', window.parent.document).val(i);
                                        }
                                    }
                                    if (data['report']['firmCompared'] !== null) {
                                        var a = data['report']['firmCompared'].length;
                                        $("#analysis_id", window.parent.document).val(a);
                                        if (data['lists'].length > 1) {
                                            $('.tbm',window.parent.document).remove();
                                            for (var i = 0; i < data['lists'].length; i++) {
                                                if(i == 0){
                                                    $(".pdna_firm", window.parent.document).append("<td class='remove'><input type='hidden' name=PdFirmReportCompared[] value='" + data['lists'][i]['firm']['firm_id'] + "'><span>" + data['lists'][i]['firm']['firm_shortname'] + "</span> </td>");
                                                    $(".pdna_influence", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_influence'] + "</span> </td>");
                                                    $(".pdna_technology_service", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_technology_service'] + "</span> </td>");
                                                    $(".pdna_cooperate_degree", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['bsPubdata']['cooperateDegree'] + "</span> </td>");
                                                    $(".pdna_others", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_others'] + "</span> </td>");
                                                    $(".pdna_demand_trends", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_demand_trends'] + "</span> </td>");
                                                    $(".pdna_goods_certificate", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_goods_certificate'] + "</span> </td>");
                                                    $(".pdna_customer_base", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_customer_base'] + "</span> </td>");
                                                    $(".pdna_market_share", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_market_share'] + "</span> </td>");
                                                    $(".profit_analysis", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['profit_analysis'] + "</span> </td>");
                                                    $(".sales_advantage", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['sales_advantage'] + "</span> </td>");
                                                    $(".value_fjj", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['value_fjj'] + "</span> </td>");
                                                    $(".value_frim", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['value_frim'] + "</span> </td>");
                                                }else if(i>=1){
                                                    $(".pdna_firm", window.parent.document).append("<td class='tbm'><input type='hidden' name=PdFirmReportCompared[] value='" + data['lists'][i]['firm']['firm_id'] + "'><span>" + data['lists'][i]['firm']['firm_shortname'] + "</span> </td>");
                                                    $(".pdna_influence", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_influence'] + "</span> </td>");
                                                    $(".pdna_technology_service", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_technology_service'] + "</span> </td>");
                                                    $(".pdna_cooperate_degree", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['bsPubdata']['cooperateDegree'] + "</span> </td>");
                                                    $(".pdna_others", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_others'] + "</span> </td>");
                                                    $(".pdna_demand_trends", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_demand_trends'] + "</span> </td>");
                                                    $(".pdna_goods_certificate", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_goods_certificate'] + "</span> </td>");
                                                    $(".pdna_customer_base", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_customer_base'] + "</span> </td>");
                                                    $(".pdna_market_share", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_market_share'] + "</span> </td>");
                                                    $(".profit_analysis", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['profit_analysis'] + "</span> </td>");
                                                    $(".sales_advantage", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['sales_advantage'] + "</span> </td>");
                                                    $(".value_fjj", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['value_fjj'] + "</span> </td>");
                                                    $(".value_frim", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['value_frim'] + "</span> </td>");
                                                }
                                            }
                                        } else {

                                            $(".pdna_firm", window.parent.document).append("<td class='remove'><input type='hidden' name=PdFirmReportCompared[] value='" + data['lists']['firm']['firm_id'] + "'><span>" + data['lists']['firm']['firm_shortname'] + "</span> </td>");
                                            if(data['lists']['analysis'] !== null) {
                                                $(".pdna_influence", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_influence'] + "</span> </td>");
                                                $(".pdna_technology_service", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_technology_service'] + "</span> </td>");
                                                $(".pdna_cooperate_degree", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['bsPubdata']['cooperateDegree'] + "</span> </td>");
                                                $(".pdna_others", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_others'] + "</span> </td>");
                                                $(".pdna_demand_trends", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_demand_trends'] + "</span> </td>");
                                                $(".pdna_goods_certificate", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_goods_certificate'] + "</span> </td>");
                                                $(".pdna_customer_base", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_customer_base'] + "</span> </td>");
                                                $(".pdna_market_share", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_market_share'] + "</span> </td>");
                                                $(".profit_analysis", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['profit_analysis'] + "</span> </td>");
                                                $(".sales_advantage", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['sales_advantage'] + "</span> </td>");
                                                $(".value_fjj", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['value_fjj'] + "</span> </td>");
                                                $(".value_frim", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['value_frim'] + "</span> </td>");
                                            }
                                        }
                                    }
                                }
                            }
                            parent.$.fancybox.close();
                        }
                    }else{
                        $("#firm_id", window.parent.document).val(data['firm'].firm_id);
                        $("#firm_sname", window.parent.document).val(data['firm'].firm_sname.decode());
                        $("#firm_shortname", window.parent.document).val(data['firm'].firm_shortname.decode());
                        $("#firm_ename", window.parent.document).val(data['firm'].firm_ename.decode());
                        $("#firm_eshortname", window.parent.document).val(data['firm'].firm_eshortname.decode());
                        $("#firm_brand", window.parent.document).val(data['firm'].firm_brand.decode());
                        $("#firm_brand_english", window.parent.document).val(data['firm'].firm_brand_english.decode());
                        $("#firm_compaddress", window.parent.document).val(data['firm'].firm_compaddress.decode());
                        $("#firm_source", window.parent.document).val(data['firm'].firmSource.decode());
                        $("#firm_type", window.parent.document).val(data['firm'].firmType.decode());
                        $(".easyui-validatebox",window.parent.document).validatebox();
                        $("#firm_issupplier", window.parent.document).val(function () {
                            return data['firm'].firm_issupplier == 1 ? '是' : '否';
                        });
                        $("#firm_category_id", window.parent.document).val(data['firm'].category.decode());
                        if (data['report'] !== null) {
                            if (data['child'] !== '') {
                                $("#pfrc_date", window.parent.document).val(data['child'].pfrc_date);
                                $("#pfrc_time", window.parent.document).val(data['child'].pfrc_time);
                                $("#pfrc_location", window.parent.document).val(data['child'].pfrc_location);
                                if(data['child']['reception'] !== null) {
                                    $("#rece_sname", window.parent.document).val(data['child']['reception'].receSname);
                                    $("#rece_position", window.parent.document).val(data['child']['reception'].recePosition);
                                    $("#rece_mobile", window.parent.document).val(data['child']['reception'].receMobile);
                                    $(".easyui-validatebox",window.parent.document).validatebox();
                                }
                                $("#pfrc_person", window.parent.document).val(data['child']['productPerson'].code);
                                $(".staff_name", window.parent.document).text(data['child']['productPerson'].name);
                                $(".job_task", window.parent.document).val(data['child']['productPerson'].job);
                                $(".staff_mobile", window.parent.document).val(data['child']['productPerson'].mobile);
                                $(".easyui-validatebox",window.parent.document).validatebox();
                                if (data['child']['staffPerson'].length != 0) {
                                    $("#vacc_body tr", window.parent.document).remove();
                                    for (var i = 0; i < data['child']['staffPerson'].length; i++) {
                                        var accompany = data['child']['staffPerson'][i];
                                        //$("#accompany_code",window.parent.document).val(a.staff_code);
                                        var editTdStr = '<tr>';
                                        editTdStr += '<td>' + '<input type="text" onblur="job_num(this)" class="width-200 no-border text-center" name="vacc[]" value="' + accompany.staff_code + '">' + '</td>';
                                        editTdStr += '<td>' + '<span class="width-200">' + accompany.staff_name + '</span>' + '</td>';
                                        editTdStr += '<td>' + '<span class="width-200">' + accompany.job_task + '</span>' + '</td>';
                                        editTdStr += '<td>' + '<span class="width-200">' + accompany.staff_mobile + '</span>' + '</td>';
                                        editTdStr += '<td>' + '<a onclick="reset(this)">重置</a>  <a onclick="del_table(this)">删除</a>' + '</td>';
                                        $('#vacc_body', window.parent.document).append(editTdStr);
                                    }
                                }
                                if(data['child']['analysis'] !== null) {
                                    $("#pdna_annual_sales", window.parent.document).val(data['child']['analysis'].pdna_annual_sales);
                                    $("#pdna_position", window.parent.document).val(data['child']['analysis'].pdna_position);
                                    $("#pdna_loction", window.parent.document).val(data['child']['analysis'].pdna_loction);
                                    $("#pdna_influence", window.parent.document).val(data['child']['analysis'].pdna_influence);
                                    $("#pdna_cooperate_degree", window.parent.document).val(data['child']['analysis'].pdna_cooperate_degree);
                                    $("#pdna_technology_service", window.parent.document).val(data['child']['analysis'].pdna_technology_service);
                                    $("#pdna_others", window.parent.document).val(data['child']['analysis'].pdna_others);
                                    $("#pdna_customer_base", window.parent.document).val(data['child']['analysis'].pdna_customer_base);
                                    $("#pdna_market_share", window.parent.document).val(data['child']['analysis'].pdna_market_share);
                                    $("#pdna_demand_trends", window.parent.document).val(data['child']['analysis'].pdna_demand_trends);
                                    $("#pdna_goods_certificate", window.parent.document).val(data['child']['analysis'].pdna_goods_certificate);
                                    $("#profit_analysis", window.parent.document).val(data['child']['analysis'].profit_analysis);
                                    $("#sales_advantage", window.parent.document).val(data['child']['analysis'].sales_advantage);
                                    $("#value_fjj", window.parent.document).val(data['child']['analysis'].value_fjj);
                                    $("#value_frim", window.parent.document).val(data['child']['analysis'].value_frim);
                                }
                                if(data['child']['authorize'] !== null) {
                                    $("#pdaa_agents_grade", window.parent.document).val(data['child']['authorize'].pdaa_agents_grade);
                                    $("#pdaa_authorize_area", window.parent.document).val(data['child']['authorize'].pdaa_authorize_area);
                                    $("#pdaa_sale_area", window.parent.document).val(data['child']['authorize'].pdaa_sale_area);
                                    $("#pdaa_bdate", window.parent.document).val(data['child']['authorize'].pdaa_bdate);
                                    $("#pdaa_edate", window.parent.document).val(data['child']['authorize'].pdaa_edate);
                                    $("#pdaa_settlement", window.parent.document).val(data['child']['authorize'].pdaa_settlement);
                                    $("#pdaa_delivery_day", window.parent.document).val(data['child']['authorize'].pdaa_delivery_day);
                                    $("#pdaa_service", window.parent.document).val(data['child']['authorize'].pdaa_service);
                                    $("#pdaa_delivery_way", window.parent.document).val(data['child']['authorize'].pdaa_delivery_way);
                                }
                                if (data['child']['products'].length != 0) {
                                    for (var i = 0; i < data['child']['products'].length; i++) {
                                        var reportChild = data['child']['products'][i];
                                        //添加商品
                                        var tdStr = "<tr data-key='" + i + "'>";
                                        tdStr += "<td>" + reportChild.product_name + "</td>";
                                        tdStr += "<td>" + reportChild.product_size + "</td>";
                                        tdStr += "<td>" + reportChild.product_brand + "</td>";
                                        tdStr += "<td>" + reportChild.delivery_terms + "</td>";
                                        tdStr += "<td>" + reportChild.payment_terms + "</td>";
                                        tdStr += "<td>" + reportChild.product_unit + "</td>";
                                        tdStr += "<td>" + reportChild.currency_type + "</td>";
                                        tdStr += "<td>" + reportChild.price_max + "</td>";
                                        tdStr += "<td>" + reportChild.price_min + "</td>";
                                        tdStr += "<td>" + reportChild.price_range + "</td>";
                                        tdStr += "<td>" + reportChild.price_average + "</td>";
                                        tdStr += "<td>" + reportChild.levelName + "</td>";
                                        tdStr += "<td>" + reportChild.profit_margin + "</td>";
                                        if(reportChild.typeName = null){
                                            tdStr += "<td></td>";
                                            tdStr += "<td></td>";
                                            tdStr += "<td></td>";
                                            tdStr += "<td></td>";
                                            tdStr += "<td></td>";
                                            tdStr += "<td></td>";
                                        }else{
                                            tdStr += "<td>" + reportChild.typeName[0] + "</td>";
                                            tdStr += "<td>" + reportChild.typeName[1] + "</td>";
                                            tdStr += "<td>" + reportChild.typeName[2] + "</td>";
                                            tdStr += "<td>" + reportChild.typeName[3] + "</td>";
                                            tdStr += "<td>" + reportChild.typeName[4] + "</td>";
                                            tdStr += "<td>" + reportChild.typeName[5] + "</td>";
                                        }
                                        tdStr += "<td><a onclick='product_del(this)'>删除</a></td>";
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_name]" value="' + reportChild.product_name + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_size]" value="' + reportChild.product_size + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_brand]" value="' + reportChild.product_brand + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][delivery_terms]" value="' + reportChild.delivery_terms + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][payment_terms]" value="' + reportChild.product_unit + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_unit]" value="' + reportChild.currency_type + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][currency_type]" value="' + reportChild.product_name + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_max]" value="' + reportChild.price_max + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_min]" value="' + reportChild.price_min + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_range]" value="' + reportChild.price_range + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_average]" value="' + reportChild.price_average + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_level]" value="' + reportChild.product_level + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][profit_margin]" value="' + reportChild.profit_margin + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_1]" value="' + reportChild.product_type_1 + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_2]" value="' + reportChild.product_type_2 + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_3]" value="' + reportChild.product_type_3 + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_4]" value="' + reportChild.product_type_4 + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_5]" value="' + reportChild.product_type_5 + '">';
                                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_6]" value="' + reportChild.product_type_6 + '">';
                                        tdStr += "</tr>";
                                        $('#table_body', window.parent.document).append(tdStr);
                                        $('#table_id', window.parent.document).val(i);
                                    }
                                }
                                if (data['report']['firmCompared'] !== null) {
                                    var a = data['report']['firmCompared'].length;
                                    $("#analysis_id", window.parent.document).val(a);
                                    if (data['lists'].length > 1) {
                                        $('.tbm',window.parent.document).remove();
                                        for (var i = 0; i < data['lists'].length; i++) {
                                            if(i == 0){
                                                $(".pdna_firm", window.parent.document).append("<td class='remove'><input type='hidden' name=PdFirmReportCompared[] value='" + data['lists'][i]['firm']['firm_id'] + "'><span>" + data['lists'][i]['firm']['firm_shortname'] + "</span> </td>");
                                                $(".pdna_influence", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_influence'] + "</span> </td>");
                                                $(".pdna_technology_service", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_technology_service'] + "</span> </td>");
                                                $(".pdna_cooperate_degree", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['bsPubdata']['cooperateDegree'] + "</span> </td>");
                                                $(".pdna_others", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_others'] + "</span> </td>");
                                                $(".pdna_demand_trends", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_demand_trends'] + "</span> </td>");
                                                $(".pdna_goods_certificate", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_goods_certificate'] + "</span> </td>");
                                                $(".pdna_customer_base", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_customer_base'] + "</span> </td>");
                                                $(".pdna_market_share", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['pdna_market_share'] + "</span> </td>");
                                                $(".profit_analysis", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['profit_analysis'] + "</span> </td>");
                                                $(".sales_advantage", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['sales_advantage'] + "</span> </td>");
                                                $(".value_fjj", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['value_fjj'] + "</span> </td>");
                                                $(".value_frim", window.parent.document).append("<td class='remove'><span>" + data['lists'][i]['analysis']['value_frim'] + "</span> </td>");
                                            }else if(i>=1){
                                                $(".pdna_firm", window.parent.document).append("<td class='tbm'><input type='hidden' name=PdFirmReportCompared[] value='" + data['lists'][i]['firm']['firm_id'] + "'><span>" + data['lists'][i]['firm']['firm_shortname'] + "</span> </td>");
                                                $(".pdna_influence", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_influence'] + "</span> </td>");
                                                $(".pdna_technology_service", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_technology_service'] + "</span> </td>");
                                                $(".pdna_cooperate_degree", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['bsPubdata']['cooperateDegree'] + "</span> </td>");
                                                $(".pdna_others", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_others'] + "</span> </td>");
                                                $(".pdna_demand_trends", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_demand_trends'] + "</span> </td>");
                                                $(".pdna_goods_certificate", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_goods_certificate'] + "</span> </td>");
                                                $(".pdna_customer_base", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_customer_base'] + "</span> </td>");
                                                $(".pdna_market_share", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['pdna_market_share'] + "</span> </td>");
                                                $(".profit_analysis", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['profit_analysis'] + "</span> </td>");
                                                $(".sales_advantage", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['sales_advantage'] + "</span> </td>");
                                                $(".value_fjj", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['value_fjj'] + "</span> </td>");
                                                $(".value_frim", window.parent.document).append("<td class='tbm'><span>" + data['lists'][i]['analysis']['value_frim'] + "</span> </td>");
                                            }
                                        }
                                    } else {
                                        $(".pdna_firm", window.parent.document).append("<td class='remove'><input type='hidden' name=PdFirmReportCompared[] value='" + data['lists']['firm']['firm_id'] + "'><span>" + data['lists']['firm']['firm_shortname'] + "</span> </td>");
                                        if(data['lists']['analysis'] !== null) {
                                            $(".pdna_influence", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_influence'] + "</span> </td>");
                                            $(".pdna_technology_service", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_technology_service'] + "</span> </td>");
                                            $(".pdna_cooperate_degree", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['bsPubdata']['cooperateDegree'] + "</span> </td>");
                                            $(".pdna_others", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_others'] + "</span> </td>");
                                            $(".pdna_demand_trends", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_demand_trends'] + "</span> </td>");
                                            $(".pdna_goods_certificate", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_goods_certificate'] + "</span> </td>");
                                            $(".pdna_customer_base", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_customer_base'] + "</span> </td>");
                                            $(".pdna_market_share", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['pdna_market_share'] + "</span> </td>");
                                            $(".profit_analysis", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['profit_analysis'] + "</span> </td>");
                                            $(".sales_advantage", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['sales_advantage'] + "</span> </td>");
                                            $(".value_fjj", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['value_fjj'] + "</span> </td>");
                                            $(".value_frim", window.parent.document).append("<td class='remove'><span>" + data['lists']['analysis']['value_frim'] + "</span> </td>");
                                        }
                                    }
                                }
                            }
                        }
                        parent.$.fancybox.close();
                    }
                }
            })
        }
    })
</script>
