<?php

use app\assets\MultiSelectAsset;

MultiSelectAsset::register($this);
?>
<style>
    .search-input{
        margin-bottom: 15px;
    }
    .ms-selection{
        margin-top:40px;
    }
</style>
<h1 class="head-first">添加厂商</h1>
<div class="mb-20">
    <select multiple="multiple" id="my-select" name="firm[]">
        <?php foreach ($data as $k => $v) { ?>
            <option value="<?= $v['firm_id'] ?>"><?= $v['firmName'] ?></option>
        <?php } ?>
    </select>
</div>
<div class="text-center mt-10">
    <button class="button-blue-big" id="check">确定</button>
    <button class="button-white-big ml-20" onclick="close_select()">取消</button>
</div>
<script>
    $(function () {
        $("#my-select").multiSelect({
            'selectableOptgroup': true,
            selectableHeader: "<input type='text' class='search-input' autocomplete='off' style='width:270px;' placeholder='搜索'>",
//            selectionHeader: "<input type='text' class='search-input' autocomplete='off' style='width:270px;'>",
            afterInit: function (ms) {
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function (e) {
                        if (e.which === 40) {
                            that.$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function (e) {
                        if (e.which == 40) {
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function () {
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function () {
                this.qs1.cache();
                this.qs2.cache();
            }
        });
        <?php if(!empty($str)){ ?>
        <?php foreach ( $str as $key => $val ){ ?>
        $("#my-select").multiSelect("select", "<?= $val ?>");
        <?php } ?>
        $("#check").click(function () {
            var str = $('#my-select').val();
            $('.tbm', window.parent.document).remove();
            if (str != null) {
                var id = str.join(',');
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {"id": id},
                    url: "<?= \yii\helpers\Url::to(['/ptdt/firm-report/analysis-report']) ?>",
                    success: function (data) {
                        for (var i = 0; i < data.firm.length; i++) {
                            $(".analyze-table tr", window.parent.document).eq(2).append("<td class='tbm'><input class='afirm' type='hidden' name='PdFirmReportCompared[]'><span></span></td>");
                            $(".analyze-table tr", window.parent.document).eq(3).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(4).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(5).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(6).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(7).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(8).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(9).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(10).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(11).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(12).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(13).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(14).append('<td class="tbm"></td>');
                            $(".pdna_firm td input", window.parent.document).eq(i * 1 + 1).val(data['firm'][i].firm_id);
                            $(".pdna_firm td span", window.parent.document).eq(i * 1 + 1).text(data['firm'][i].firm_sname);
                            if (data['analysis'][i] != null) {
                                $(".pdna_influence td:last ", window.parent.document).text(data['analysis'][i].pdna_influence);
                                $(".pdna_technology_service td:last ", window.parent.document).text(data['analysis'][i].pdna_technology_service);
                                $(".pdna_cooperate_degree td:last ", window.parent.document).text(data['analysis'][i]['bsPubdata'].cooperateDegree);
                                $(".pdna_others td:last ", window.parent.document).text(data['analysis'][i].pdna_others);
                                $(".pdna_demand_trends td:last ", window.parent.document).text(data['analysis'][i].pdna_demand_trends);
                                $(".pdna_goods_certificate td:last ", window.parent.document).text(data['analysis'][i].pdna_goods_certificate);
                                $(".pdna_customer_base td:last ", window.parent.document).text(data['analysis'][i].pdna_customer_base);
                                $(".pdna_market_share td:last ", window.parent.document).text(data['analysis'][i].pdna_market_share);
                                $(".profit_analysis td:last ", window.parent.document).text(data['analysis'][i].profit_analysis);
                                $(".sales_advantage td:last ", window.parent.document).text(data['analysis'][i].sales_advantage);
                                $(".value_fjj td:last ", window.parent.document).text(data['analysis'][i].value_fjj);
                                $(".value_frim td:last ", window.parent.document).text(data['analysis'][i].value_frim);
                            }
                        }
                        parent.$.fancybox.close();
                    }
                })
            } else {
                parent.$.fancybox.close();
            }
        })
        <?php }else{ ?>
        $("#check").click(function () {
            var str = $('#my-select').val();
            $('.tbm', window.parent.document).remove();
            if (str != null) {
                var id = str.join(',');
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {"id": id},
                    url: "<?= \yii\helpers\Url::to(['/ptdt/firm-report/analysis-report']) ?>",
                    success: function (data) {
//                    console.log(data.firm.length);
                        for (var i = 0; i < data.firm.length; i++) {
                            $(".analyze-table tr", window.parent.document).eq(2).append("<td class='tbm'><input class='afirm' type='hidden' name='PdFirmReportCompared[]'><span></span></td>");
                            $(".analyze-table tr", window.parent.document).eq(3).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(4).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(5).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(6).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(7).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(8).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(9).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(10).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(11).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(12).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(13).append('<td class="tbm"></td>');
                            $(".analyze-table tr", window.parent.document).eq(14).append('<td class="tbm"></td>');
                            $(".pdna_firm td input", window.parent.document).eq(i * 1 + 1).val(data['firm'][i].firm_id);
                            $(".pdna_firm td span", window.parent.document).eq(i * 1 + 1).text(data['firm'][i].firm_sname);
                            if (data['analysis'][i] != null) {
                                $(".pdna_influence td:last ", window.parent.document).text(data['analysis'][i].pdna_influence);
                                $(".pdna_technology_service td:last ", window.parent.document).text(data['analysis'][i].pdna_technology_service);
                                $(".pdna_cooperate_degree td:last ", window.parent.document).text(data['analysis'][i]['bsPubdata'].cooperateDegree);
                                $(".pdna_others td:last ", window.parent.document).text(data['analysis'][i].pdna_others);
                                $(".pdna_demand_trends td:last ", window.parent.document).text(data['analysis'][i].pdna_demand_trends);
                                $(".pdna_goods_certificate td:last ", window.parent.document).text(data['analysis'][i].pdna_goods_certificate);
                                $(".pdna_customer_base td:last ", window.parent.document).text(data['analysis'][i].pdna_customer_base);
                                $(".pdna_market_share td:last ", window.parent.document).text(data['analysis'][i].pdna_market_share);
                                $(".profit_analysis td:last ", window.parent.document).text(data['analysis'][i].profit_analysis);
                                $(".sales_advantage td:last ", window.parent.document).text(data['analysis'][i].sales_advantage);
                                $(".value_fjj td:last ", window.parent.document).text(data['analysis'][i].value_fjj);
                                $(".value_frim td:last ", window.parent.document).text(data['analysis'][i].value_frim);
                            }
                        }
                        parent.$.fancybox.close();
                    }
                })
            } else {
                parent.$.fancybox.close();
            }


        });
        <?php } ?>
    })
</script>