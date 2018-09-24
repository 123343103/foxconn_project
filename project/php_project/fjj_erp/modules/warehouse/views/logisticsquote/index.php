<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/7/18
 * Time: 上午 09:24
 */
use yii\helpers\Url;

$this->title = '物流报价查询';
$this->params['homeLike'] = ['label' => '仓库物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '物流报价查询', 'url' => ''];
?>
<div class="content" id="content">
    <?php echo $this->render('_search', ['district' => $district, 'msg' => $msg, 'param' => $param, 'trans' => $trans, 'transport' => $transport]); ?>
    <div style="display: none" id="result">
        <?php echo $this->render('_result', ['param' => $param]); ?>
    </div>

</div>

<script>
    $(function () {
        $("#select").click(function () {
            if (($("#end").val() == "" && $("#start").val() == "") && $("#salesquotationno").val() == "") {
                $("#ProvinceFrameend").css('display', 'none');//隐藏目的地地址panl
                $("#ProvinceFrame").css('display', 'none');//显示起始地地址panl
                $("#CityFrameend").css('display', 'none');
                $("#CityFrame").css('display', 'none');
                $("#start").addClass('red-border');
                $("#end").addClass('red-border');
                $("#start").focus();
                // alert("起始地与目的地不能为空！");
            }
            else if (($("#end").val() != "" && $("#start").val() == "") && $("#salesquotationno").val() == "") {
                $("#start").addClass('red-border');
                $("#start").focus();
            }
            else if (($("#start").val() != "" && $("#end").val() == "") && $("#salesquotationno").val() == "") {
                $("#end").addClass('red-border');
                $("#end").focus();
            }
            else {
                $("#result").css("display", 'block');
                var startproviceid = $("#startproviceid").val();//起始地省id
                var startcityid = $("#startcityid").val();//起始地市ID
                var endproviceid = $("#endproviceid").val();//目的地省ID
                var endcityid = $("#endcityid").val();//目的地市ID
                var salesquotationno = $("#salesquotationno").val();//报价单号
                var transtype = $("#transtype").val();//运输类型
                $("#trans").val(transtype);
                if (transtype == '201' || transtype == '202' || transtype == '203') {
                    $("#Land").hide();
                    $("#Exdetails").hide();
                    $("#Express").show();
                    $.ajax({
                        url: "<?=Url::to(['lqt-express-head'])?>",
                        data: {
                            "startproviceid": startproviceid, "startcityid": startcityid,
                            "endproviceid": endproviceid, "endcityid": endcityid, "salesquotationno": salesquotationno,
                            "transtype": transtype
                        },
                        dataType: "json",
                        type: "get",
                        async: false,
                        success: function (data) {
                            $(".Exheadinfo").remove();//先清除原来的数据
                            $("#TransModel")[0].innerHTML=data[0].transMethod;//计费路径
                            if (data.length > 0) {
                                for (var i = 0; i < data.length; i++) {
                                    var QUOTATIONDATE = data[i].QUOTATIONDATE.substr(0, 10);
                                    QUOTATIONDATE = QUOTATIONDATE.replace(/-/g, "/");//报价日期
                                    var EFFECTDATE = data[i].EFFECTDATE.substr(0, 10);
                                    EFFECTDATE = EFFECTDATE.replace(/-/g, "/");//生效日期
                                    var EXPIREDATE = data[i].EXPIREDATE.substr(0, 10);
                                    EXPIREDATE = EXPIREDATE.replace(/-/g, "/");//截止日期
                                    var COSTCONFIRMEDDATE = data[i].COSTCONFIRMEDDATE.substr(0, 10);
                                    COSTCONFIRMEDDATE.replace(/-/g, "/");//经管确认日期
                                    $("#expresshead").append('<tr class="Exheadinfo">' +
                                        '<td>' + (i + 1) + '</td>' +
                                        '<td class="quotationno">' + data[i].QUOTATIONNO + '</td>' +
                                        '<td>' + QUOTATIONDATE + '</td>' +
                                        '<td>' + data[i].CNCY + '</td>' +
                                        '<td>' + EFFECTDATE + '</td>' +
                                        '<td>' + EXPIREDATE + '</td>' +
                                        '<td>' + COSTCONFIRMEDDATE + '</td>' +
                                        '<td>' + data[i].REMARK + '</td>' +
                                        '<td>'+data[i].ORIGIN__DISTRICT_name+data[i].ORIGIN_CITYID_name+'</td>' +
                                        '<td>'+data[i].DISTRICT_ID_name+data[i].CITY_ID_name+'</td>' +
                                        '<td>' + data[i].STATUS + '</td>' +
                                        '<td>' + data[i].TRANSTYPE + '</td>' +
                                        '</tr>');
                                }
                            }
                            else {
                                $("#expresshead").append('<tr class="Exheadinfo">' +
                                    '<td colspan="12" style="color: red;text-align: left;padding-left: 500px;">没有相关记录</td>' +
                                    '</tr>');
                            }
                        }
                    });
                }
                if (transtype == '301' || transtype == '302') {
                    $("#Express").hide();
                    $("#Ladetails").hide();
                    $("#Land").show();
                    $.ajax({
                        url: "<?=Url::to(['land-head'])?>",
                        data: {
                            "startproviceid": startproviceid, "startcityid": startcityid,
                            "endproviceid": endproviceid, "endcityid": endcityid, "salesquotationno": salesquotationno,
                            "transtype": transtype
                        },
                        dataType: "json",
                        type: "get",
                        async: false,
                        success: function (data) {
                            $(".Laheadinfo").remove();//先清除原来的数据
                            if (data.length > 0) {
                                for (var i = 0; i < data.length; i++) {
                                    var SALESQUOTATIONDATE = data[i].SALESQUOTATIONDATE.substr(0, 10);
                                    SALESQUOTATIONDATE = SALESQUOTATIONDATE.replace(/-/g, "/");//报价日期
                                    var EFFECTDATE = data[i].EFFECTDATE.substr(0, 10);
                                    EFFECTDATE = EFFECTDATE.replace(/-/g, "/");//生效日期
                                    var EXPIREDATE = data[i].EXPIREDATE.substr(0, 10);
                                    EXPIREDATE = EXPIREDATE.replace(/-/g, "/");//截止日期
                                    $("#Landhead").append('<tr class="Laheadinfo">' +
                                        '<td>' + (i + 1) + '</td>' +
                                        '<td class="salesquotationno">' + data[i].SALESQUOTATIONNO + '</td>' +
                                        '<td>' + data[i].CARGOTYPE + '</td>' +
                                        '<td>' + data[i].STATUS + '</td>' +
                                        '<td>' + data[i].TIMEREQUIRE + data[i].TIMEREQUIREUNIT + '</td>' +
                                        '<td>' + data[i].QUOTATIONCURRENCY + '</td>' +
                                        '<td>' + data[i].BUTYPE + '</td>' +
                                        '<td>' + data[i].FROM_DISTRICT_ID + '--' + data[i].FROM_CITY_ID + '</td>' +
                                        '<td>' + data[i].TO_DISTRICT_ID + '' + data[i].TO_CITY_ID + '</td>' +
                                        '<td>' +SALESQUOTATIONDATE + '</td>' +
                                        '<td>' + EFFECTDATE + '</td>' +
                                        '<td>' + EXPIREDATE + '</td>' +
                                        '<td>' + data[i].ORIGIN + '</td>' +
                                        '<td>' + data[i].DESTINATION + '</td>' +
                                        '<td>' + data[i].QUTATIONCLASS + '</td>' +
                                        '</tr>');
                                }
                            }
                            else {
                                $("#Landhead").append('<tr class="Laheadinfo">' +
                                    '<td colspan="15" style="color: red;text-align: left;padding-left: 500px;">没有相关记录</td>' +
                                    '</tr>');
                            }
                        }
                    })
                }
            }
        });
        $("#expresshead").delegate('.Exheadinfo', 'click', function () {
            $(this).css('background-color', '#87CEFF');
            $("#Exdetails").css('display', 'block');
            var quotationno = $(this).children('.quotationno')[0].innerHTML;
            $.ajax({
                url: "<?=Url::to(['lqt-express-detail'])?>",
                data: {"quotationno": quotationno},
                dataType: "json",
                type: "get",
                ascny: false,
                success: function (data) {
                    $(".exdetailinfo").remove();//先清除原来的数据
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            var EFFECTDATE = data[i].EFFECTDATE.substr(0, 10);
                            EFFECTDATE = EFFECTDATE.replace(/-/g, "/");//生效日期
                            var EXPIREDATE = data[i].EXPIREDATE.substr(0, 10);
                            EXPIREDATE = EXPIREDATE.replace(/-/g, "/");//截止日期
                            $("#expressdetail").append('<tr class="exdetailinfo">' +
                                '<td>' + data[i].ITEMNO + '</td>' +
                                '<td>' + data[i].UOM + '</td>' +
                                '<td>' + data[i].WEIGHTMIN + '</td>' +
                                '<td>' + data[i].FIRSTPRICE + '</td>' +
                                '<td>' + data[i].NEXTWEIGHT + '</td>' +
                                '<td>' + data[i].MIN_VALUE + '</td>' +
                                '<td>' + data[i].MAX_VALUE + '</td>' +
                                '<td>' + data[i].NEXT_RATE + '</td>' +
                                '<td>' + data[i].CHARGEMIN + '</td>' +
                                '<td>' + data[i].CHARGEMAX + '</td>' +
                                '<td>' + data[i].TRANSITTIME + '</td>' +
                                '<td>' + EFFECTDATE + '</td>' +
                                '<td>' + EXPIREDATE + '</td>' +
                                '<td>' + data[i].STATUS + '</td>' +
                                '</tr>');
                        }
                    }
                    else {
                        $("#expressdetail").append('<tr class="exdetailinfo">' +
                            '<td colspan="14" style="color: red;text-align: left;padding-left: 500px;">没有相关记录</td>'+
                            '</tr>');
                    }
                }
            })
        });

        $("#Landhead").delegate('.Laheadinfo', 'click', function () {
            $(this).css('background-color', '#87CEFF');
            $("#Ladetails").css('display', 'block');
            var quotationno = $(this).children('.salesquotationno')[0].innerHTML;
            $.ajax({
                url: "<?=Url::to(['land-detail'])?>",
                data: {"quotationno": quotationno},
                dataType: "json",
                type: "get",
                ascny: false,
                success: function (data) {
                console.log(data);
                    $(".ladetailinfo").remove();//先清除原来的数据
                    if(data.length>0)
                    {
                        for(var i=0;i<data.length;i++)
                        {
                            $("#landdetail").append('<tr class="ladetailinfo">' +
                                '<td>'+data[i].SALESQUOTATIONID+'</td>' +
                                '<td>'+data[i].ITEMCNAME+'</td>' +
                                '<td>'+data[i].UOM+'</td>' +
                                '<td>'+data[i].RATE+'</td>' +
                                '<td>'+data[i].TAXRATE+'</td>' +
                                '<td>'+data[i].TAXTYPE+'</td>' +
                                '<td>'+data[i].TRUCKGROUP+'</td>' +
                                '<td>'+data[i].COSTTYPE+'</td>' +
                                '<td>'+data[i].MINICHARGE+'</td>' +
                                '<td>'+data[i].MAXCHARGE+'</td>' +
                                '<td>'+data[i].CURRENCY+'</td>' +
                                '</tr>');
                        }
                    }
                    else {
                        $("#landdetail").append('<tr class="exdetailinfo">' +
                            '<td colspan="11" style="color: red;text-align: left;padding-left: 500px;">没有相关记录</td>'+
                            '</tr>');
                    }
                }
            })
        })
    });
</script>
