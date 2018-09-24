<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/14
 * Time: 下午 03:37
 */
use yii\helpers\Url;

\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);

$this->title = '运费试算';
$this->params['homeLike'] = ['label' => '仓库物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '运费试算', 'url' => ''];
?>
<style>
    .text-no-text{
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    .width-150{
        width: 150px;
    }
</style>
<div class="content">
    <h2 class="head-first">
        <?= $this->title ?>
    </h2>
    <?php echo $this->render('_search', ['param' => $param, 'district' => $district]); ?>
    <?php echo $this->render('_result', ['data' => $data]) ?>
</div>
<script>
    $(function () {
        $("#partno").blur(function () {
            var partno = $("#partno").val();
            //当输入料号时执行
            if (partno != "") {
                $.ajax({
                    url: "<?=Url::to(['ship-info'])?>",
                    data: {"partno": partno},
                    dataType: "json",
                    type: "get",
                    async: true,
                    success: function (data) {
                        $("#shipinfo").children('option').remove();
                        if (data.length > 0) {
                            $("#shipinfo").append('<option value="">--</option>');
                            for (var i = 0; i < data.length; i++) {
                                $("#shipinfo").css('color', 'black');
                                $("#shipinfo").append('<option value="' + data[i].province_no + ',' + data[i].city_no + '">' + data[i].province_name + '--' + data[i].city_name + '</option>');
                            }
                        }
                        else {
                            $("#shipinfo").css('color', 'red');
                            $("#shipinfo").append('<option value="-1">该料号暂时没有发货地</option>')
                        }
                    }
                })
            }
            //没有输入料号时
            else {
                $("#shipinfo").css('color', 'black');//起运地字体为黑色
                $("#shipinfo").children('option').remove();//移除起运地的所有起运地
            }
        });

        $("#select").click(function () {
            var partno = $("#partno").val().trim();//料号
            var qty = $("#qty").val().trim();//数量
            var shipinfo = $("#shipinfo").val();//起运地
            var endaddress = $("#endaddress").val();//配送地
            if (partno != "" && qty != "" && shipinfo != "" && endaddress != "") {
                $.ajax({
                    url: "<?=Url::to(['part-info'])?>",
                    data: {"part_no": partno},
                    dataType: "json",
                    type: "get",
                    cache: true,
                    success: function (info) {
                        $(".pdttr").remove();
                        $(".prcktr").remove();
                        if (info != false) {
                            $("#pdtinfo").append('<tr class="pdttr">' +
                                '<td class="text-no-text width-150" title="'+info.part_no+'">' + info.part_no + '</td>' +
                                '<td class="text-no-text width-150" title="'+info.pdt_name+'">' + info.pdt_name + '</td>' +
                                '<td class="text-no-text width-150" title="'+info.brand_name_cn+'">' + info.brand_name_cn + '</td>' +
                                '<td class="text-no-text width-150" title="'+info.tp_spec+'">' + info.tp_spec + '</td>' +
                                '<td class="text-no-text width-150" title="'+info.unit+'">' + info.unit + '</td>' +
                                '</tr>');
                            $("#prckinfo").append('<tr class="prcktr">' +
                                '<td>' + info.pdt_length + '*' + info.pdt_width + '*' + info.pdt_height + '</td>' +
                                '<td>' + parseFloat((info.pdt_length / 100) * (info.pdt_width / 100) * (info.pdt_height / 100)).toFixed(2) + '</td>' +
                                '<td>' + info.pdt_weight + '</td>' +
                                '<td>' + info.net_weight + '</td>' +
                                '<td>' + info.pdt_qty + '</td>' +
                                '<td>' + info.unit + '</td>' +
                                '</tr>');
                        }
                        else {
                            $("#pdtinfo").append('<tr class="pdttr">' +
                                '<td colspan="5" style="color:red">没有相关数据</td>' +
                                '</tr>');
                            $("#prckinfo").append('<tr class="prcktr">' +
                                '<td colspan="6" style="color:red">没有相关数据</td>' +
                                '</tr>');
                        }
                    }
                });

                var ship = shipinfo.split(',');
                var FromProvince = ship[0];//起运地省ID
                var FromCity = ship[1];//起运地市ID
                var ToProvince = $("#ToProvince").val();//配送地省ID
                var ToCity = $("#ToCity").val();//配送地市ID
                var pdtnum = $("#qty").val();//采购数量
                var _feetr = $("#FeeResult").children('tr');
                _feetr.each(function () {
                    var _this = $(this);
                    var TransType = $(this).data('tanstype');//运输类型
                    _this.find(".Volume").find("span").remove();
                    _this.find(".Weight").find("span").remove();
                    _this.find(".calweight").find("span").remove();
                    _this.find(".path").find("span").remove();
                    _this.find(".calculationFun").find("span").remove();
                    _this.find(".NoTax").find("span").remove();
                    _this.find(".frontdesk").find("span").remove();
                    $.ajax({
                        url: "<?=Url::to(['freight'])?>",
                        data: {
                            "partno": partno, "FromProvince": FromProvince, "FromCity": FromCity,
                            "ToProvince": ToProvince, "ToCity": ToCity, "pdtnum": pdtnum, "TransType": TransType
                        },
                        dataType: "json",
                        type: "get",
                        cache: false,
                        success: function (data) {
                            _this.find(".Volume").append('<span>' + parseFloat(data.Volume).toFixed(2) + '</span>');
                            _this.find(".Weight").append('<span>' + parseFloat(data.Weight).toFixed(2) + '</span>');
                            _this.find(".calweight").append('<span>' + data.calweight + '</span>');
                            if (data.HasFee == true) {
                                _this.find(".path").append('<span>' + data.path + '</span>');
                                _this.find(".calculationFun").append('<span>' + data.calculationFun + '</span>');
                                _this.find(".NoTax").append('<span>' + data.NoTax + '</span>')
                                _this.find(".frontdesk").append('<span>' + data.frontdesk + '</span>')
                            }
                            else {
                                _this.find(".path").append('<span>' + data.path + '</span>');
                                _this.find(".calculationFun").append('<span>' + data.calculationFun + '</span>');
                                _this.find(".NoTax").append('<span>--</span>')
                                _this.find(".frontdesk").append('<span>--</span>')
                            }
                        }
                    });
                });
            }
            else if (partno != "" || qty != "" || shipinfo != "" || endaddress != "") {
                $.ajax({
                    url: "<?=Url::to(['part-info'])?>",
                    data: {"part_no": partno},
                    dataType: "json",
                    type: "get",
                    async: true,
                    success: function (info) {
                        $(".pdttr").remove();
                        $(".prcktr").remove();
                        if (info != false) {
                            $("#pdtinfo").append('<tr class="pdttr">' +
                                '<td class="text-no-text width-150" title="'+info.part_no+'">' + info.part_no + '</td>' +
                                '<td class="text-no-text width-150" title="'+info.pdt_name+'">' + info.pdt_name + '</td>' +
                                '<td class="text-no-text width-150" title="'+info.brand_name_cn+'">' + info.brand_name_cn + '</td>' +
                                '<td class="text-no-text width-150" title="'+info.tp_spec+'">' + info.tp_spec + '</td>' +
                                '<td class="text-no-text width-150" title="'+info.unit+'">' + info.unit + '</td>' +
                                '</tr>');
                            $("#prckinfo").append('<tr class="prcktr">' +
                                '<td>' + info.pdt_length + '*' + info.pdt_width + '*' + info.pdt_height + '</td>' +
                                '<td>' + parseFloat((info.pdt_length / 100) * (info.pdt_width / 100) * (info.pdt_height / 100)).toFixed(2) + '</td>' +
                                '<td>' + info.pdt_weight + '</td>' +
                                '<td>' + info.pdt_weight + '</td>' +
                                '<td>' + info.pdt_qty + '</td>' +
                                '<td>' + info.unit + '</td>' +
                                '</tr>');
                        }
                        else {
                            $("#pdtinfo").append('<tr class="pdttr">' +
                                '<td colspan="5" style="color:red">没有相关数据</td>' +
                                '</tr>');
                            $("#prckinfo").append('<tr class="prcktr">' +
                                '<td colspan="6" style="color:red">没有相关数据</td>' +
                                '</tr>');
                        }
                    }
                });
            }
        });
    })
</script>
