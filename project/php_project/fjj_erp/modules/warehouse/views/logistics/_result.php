<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/21
 * Time: 下午 01:36
 */
use app\classes\Menu;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div>
    <!--    class="head-first"-->
    <div style="background-color: lightgrey;width: 990px;height:30px;"><span
            style="font-size: 14px;margin-left: 5px;margin-top: 5px;">商品信息</span></span></div>
    <!--    <div class="table-content">-->
    <!--        <table style="width:990px; text-align: center;">-->
    <!--            <thead>-->
    <!--            <tr style="height: 50px;">-->
    <!--                <td>商品名</td>-->
    <!--                <td style="width: 100px">数量</td>-->
    <!--                <td style="width: 100px">出货数量</td>-->
    <!--                <td style="width: 100px">重量</td>-->
    <!--                <td style="width: 100px">订单状态</td>-->
    <!--                <td style="width: 100px">运输方式</td>-->
    <!--                <td>操作</td>-->
    <!--            </tr>-->
    <!--            </thead>-->
    <!--            <tbody id="tab_data">-->
    <!---->
    <!--            </tbody>-->
    <!--        </table>-->
    <!--<!--        <div id="data"></div>--><!--    </div>-->
    <div id="data"></div>
    <div>
        <div
            style="font-size: 14px;padding-left: 5px;padding-top: 5px;margin-top:20px;background-color: lightgrey;width: 985px;height:30px;">
            物流进度
            <span class="float-right" style="margin-right: 50px;">
                <?= Menu::isAction('/warehouse/logistics/add') ? Html::a("<span class='text-center ml--5' style='color: #0044cc;text-decoration: underline'>添加物流进度</span>", null, ['id' => 'addloginfo']) : '' ?>
                <?= Menu::isAction('/warehouse/logistics/update') ? Html::a("<span class='text-center ml--5' style='color: #0044cc;text-decoration: underline;margin-left: 20px'>修改物流进度</span>", null, ['id' => 'updateloginfo']) : '' ?>
                <!-- Url::to(['add']),-->
            </span>
        </div>
        <div id="check_in_info_tab"></div><!--物流进度信息表-->
    </div>
</div>
<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            method: "get",
            idField: "invh_id",
            loadMsg: '加载数据请稍候...',
            pagination: true,
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            columns: [[
                {field: "pdt_name", title: "商品名", width: 230},
                {field: "part_no", title: "料号", width: 180},
                {field: "ord_no", title: "订单号", width: 130},
                {field: "sapl_quantity", title: "数量", width: 84},
                {field: "o_whnum", title: "出货数量", width: 84},
                {field: "gross_weight", title: "重量", width: 84},
                {field: "os_name", title: "订单状态", width: 100},
                {field: "transport", title: "运输方式", width: 154}
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this), data.total, 1);
            },
            onSelect: function (index, row) {
                if (row.logistics_no == "") {
                    layer.alert('该商品已出货但还没发货，请耐心等待！', {
                        icon: 2,
                        end: function () {
                        }
                    });
                }
                else {
                    $("#data").datagrid("uncheckAll").datagrid("checkRow", index);
                    console.log(row);
                    $("#check_in_info_tab").datagrid({
                        url: "<?=Yii::$app->request->getHostInfo() . Url::to(['log-info'])?>?partno=" + row.part_no + "&o_whdtid=" + row.o_whdtid,
                        //rownumbers:true,
                        method: "get",
                        idField: "orderno",
                        singleSelect: true,
                        pagination: true,
                        pageSize: 10,
                        pageList: [10, 20, 30],
                        selectOnCheck: false,
                        checkOnSelect: true,
                        columns: [[
                            {field: "orderno", title: "物流单号", width: 200},
                            {field: "forwardcode", title: "承运商代码", width: 84},
                            {field: "expressno", title: "快递单号", width: 84},
                            {field: "station", title: "站点", width: 84},
                            {field: "onwaystatus", title: "在途状态", width: 100},
                            {field: "onwaystatus_date", title: "状态发生时间", width: 154},
                            {field: "delivery_man", title: "送货人", width: 200},
                            {field: "remark", title: "状态详情", width: 84},
                            {field: "carrierno", title: "配送车编号", width: 84},
                            {field: "ord_no", title: "订单号", width: 100},
                            {field: "create_by", title: "创建人", width: 84},
                            {field: "createdate", title: "创建时间", width: 100}

                        ]],
                        onLoadSuccess: function (data) {
                            showEmpty($(this), data.total, 1);
                            setMenuHeight();
                            if (data.orderno == null) {
                                layer.alert('该商品未添加物流出货，请先处理物流出货', {
                                    icon: 1,
                                    end: function () {
                                        $.fancybox({
                                            href: "<?=Url::to(['shipment'])?>?o_whdtid=" + row.o_whdtid + "&logistics_no=" +
                                            row.logistics_no + "&part_no=" + row.part_no + "&qty=" + row.o_whnum + "&o_whpkid=" + row.o_whpkid,
                                            type: 'iframe',
                                            padding: 0,
                                            autoSize: false,
                                            width: 500,
                                            height: 300,
                                            fitToView: false
                                        });
                                    }
                                });
                            }
                            datagridTip("#check_in_info_tab");
                        }
                    });
                }
            }
        });
        //-----添加物流进度信息
        $('#addloginfo').click(function () {
            //var rows=$('#data').datagrid('getRows');//获取所有当前加载的数据行
            var rows = $("#data").datagrid("getSelected");//获取选中行的值
            var partno = rows.part_no;
            var owhdtid = rows.o_whdtid;
            var orderno = rows.logistics_no;
            //点击添加物流进度时，判断选择商品的物流单号是否在物流出货表中存在
            $.ajax({
                url: "<?=Url::to(['orderno']);?>",  //?pdtid=" + pdtid + "&invh_id=" + invhid,
                data: {"partno": partno, "o_whdtid": owhdtid},
                dataType: "json",
                type: "get",
                async: false,
                success: function (data) {
                    if (data.orderno != null)//如果存在就添加物流进度信息
                    {
                        $.fancybox({
                            href: "<?=Url::to(['add'])?>?id=" + orderno + "&shipid=" + data.orderno.ship_id,
                            type: 'iframe',
                            padding: 0,
                            autoSize: false,
                            width: 800,
                            height: 500,
                            fitToView: false
                        });
                    }
                    else  //否则添加物流出货信息
                    {
                        layer.alert('该商品未添加物流出货，请先处理物流出货', {
                            icon: 1,
                            end: function () {
                                $.fancybox({
                                    href: "<?=Url::to(['shipment'])?>?o_whdtid=" + rows.o_whdtid + "&logistics_no=" +
                                    rows.logistics_no + "&part_no=" + rows.part_no + "&qty=" + rows.o_whnum + "&o_whpkid=" + rows.o_whpkid,
                                    type: 'iframe',
                                    padding: 0,
                                    autoSize: false,
                                    width: 500,
                                    height: 300,
                                    fitToView: false
                                });
                            }
                        });
                    }
                }
            });
        });
        //-----end添加物流进度信息

        $('#updateloginfo').click(function () {
            //var rows=$('#data').datagrid('getRows');//获取所有当前加载的数据行
            var rows = $("#check_in_info_tab").datagrid("getSelected");//获取选中行的值
            if (rows == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            }
            else {
                var ship_iid = rows.ship_iid;
                $.fancybox({
                    href: "<?=Url::to(['update'])?>?id=" + ship_iid,
                    type: 'iframe',
                    padding: 0,
                    autoSize: false,
                    width: 800,
                    height: 500,
                    fitToView: false
                });
            }
        });

    });

</script>