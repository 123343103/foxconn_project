<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/9/11
 * Time: 14:38
 */
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '商品开发管理'];
$this->params['breadcrumbs'][] = ['label' => '发布新商品'];
$this->title = '新商品列表';
?>
<div class="content">
    <?= $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $queryParam,
        'levelTwo' => $levelTwo,
        'levelThree' => $levelThree
    ]) ?>
    <div class="space-40"></div>
    <?= $this->render('_action', [
        'queryParam' => $queryParam,
    ]) ?>
    <div class="space-10"></div>
    <div id="data"></div>
    <div class="space-10"></div>
    <div class="space-10"></div>
    <div class="load-content display-none">
        <span class="part-no"></span>
        <div class="space-10"></div>
        <div id="fp-pas"></div>
    </div>
</div>
<script>
    $(function () {
        'use strict';
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            queryParams:params,
            rownumbers: true,
            method: "get",
            idField: "part_no",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            nowrap: false,
            pageSize: 10,
            pageList: [5, 10, 15],
            columns: [[
                <?= $columns ?>
                {
                    field: 'price', title: '核价档', width: 50, formatter: function (val, row, index) {
                    return '<a href="javascript:void(0);" onclick="checkPrice(event,\'' + row.part_no + '\')">核价档</a>'
                }
                },
                {
                    field: 'action', title: '操作', width: 100, formatter: function (val, row, index) {
//                    if (row.part_status == '1' || (row.part_status == '3' && row.check_status == 3) || row.part_status == null) {
                    if (row.part_status == '1' || row.part_status == null) {
                        return '<a href="javascript:void(0);" onclick="deletePrice(\'' + row.part_no + '\')">删除</a>&nbsp;' + '&nbsp;<a href="javascript:void(0);" onclick="putaway(\'' + row.part_no + '\')">发起上架</a>'
                    } else {
                        return '<a href="javascript:void(0);" style="color:darkgray;">已发起上架</a>'
                    }
                }
                },
            ]],
            onSelect:function(){
                $(".load-content").hide();
            },
            onLoadSuccess: function (data) {
                setMenuHeight();
                showEmpty($(this), data.total, 0);
                // datagrid 加省略号提示信息
                datagridTip('#data');
                $(this).datagrid("autoMergeCells1", ['pdt_name']);
                $(".datagrid-view2 tr").each(function(index){
                    var h=$(this).outerHeight();
                    $(".datagrid-view1 tr").eq(index).css("height",h+"px");
                });
            }
        });

        $.extend($.fn.datagrid.methods, {
            autoMergeCells1: function (jq, fields) {
                return jq.each(function () {
                    var target = $(this);
                    if (!fields) {
                        fields = target.datagrid("getColumnFields");
                    }
                    var rows = target.datagrid("getRows");
                    var i = 0,
                        j = 0,
                        temp = {};
                    for (i; i < rows.length; i++) {
                        var row = rows[i];
                        j = 0;
                        for (j; j < fields.length; j++) {
                            var field = fields[j];
                            var tf = temp[field];
                            if (row[field]) {
                                if (!tf) {
                                    tf = temp[field] = {};
                                    tf[row[field]] = [i];
                                } else {
                                    var tfv = tf[row[field]];
                                    if (tfv) {
                                        tfv.push(i);
                                    } else {
                                        tfv = tf[row[field]] = [i];
                                    }
                                }
                            } else {
                                continue;
                            }
                        }
                    }
                    $.each(temp,
                        function (field, colunm) {
                            $.each(colunm,
                                function () {
                                    var group = this;

                                    if (group.length > 1) {
                                        var before, after, megerIndex = group[0];
                                        for (var i = 0; i < group.length; i++) {
                                            before = group[i];
                                            after = group[i + 1];
                                            if (after && (after - before) == 1) {
                                                continue;
                                            }
                                            var rowspan = before - megerIndex + 1;
                                            if (rowspan > 1) {
                                                //品名相同合并品名，品牌，类别 ，计量单位 四列
                                                target.datagrid('mergeCells', {
                                                    index: megerIndex,
                                                    field: field,
                                                    rowspan: rowspan
                                                });
                                                target.datagrid('mergeCells', {
                                                    index: megerIndex,
                                                    field: 'brand',
                                                    rowspan: rowspan
                                                });
                                                target.datagrid('mergeCells', {
                                                    index: megerIndex,
                                                    field: 'category_name',
                                                    rowspan: rowspan
                                                });
                                                target.datagrid('mergeCells', {
                                                    index: megerIndex,
                                                    field: 'unit',
                                                    rowspan: rowspan
                                                });
                                            }
                                            if (after && (after - before) != 1) {
                                                megerIndex = after;
                                            }
                                        }
                                    }
                                });
                        });
                });
            }
        });
    })

    /**
     *
     *  商品核价信息
     */
    function checkPrice(event,$id) {
        event.stopPropagation();
        $('.load-content').show();
        $('.part-no').html('料号：' + $id);
        $("#fp-pas").datagrid({
            url: "<?= Url::to(['load-content']) ?>?id=" + $id,
            rownumbers: true,
            method: "get",
            idField: "part_no",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: 'payment_terms', title: '付款条件', width: 150},
                {field: 'trading_terms', title: '交货条件', width: 150},
                {field: 'supplier_code', title: '供应商编码', width: 150},
                {field: 'supplier_name_shot', title: '供应商简称', width: 150},
                {field: 'delivery_address', title: '交货地点', width: 150},
                {field: 'unite', title: '交易单位', width: 150},
                {field: 'min_order', title: '最小起订量', width: 150},
                {field: 'currency', title: '交易币别', width: 150},
                {field: 'limit_day', title: 'L/T(天)', width: 150},
                {field: 'num_area', title: '量价区间', width: 150},
                {field: 'buy_price', title: '采购价格', width: 150},
                {field: 'expiration_date', title: '有效期', width: 200},
            ]],
            onLoadSuccess: function (data) {
                setMenuHeight();
                showEmpty($(this), data.total, 0);
                // datagrid 加省略号提示信息
                datagridTip('#data');
            },
        });
    }

    /**
     * 删除
     * @param $id
     */
    function deletePrice($id) {
        layer.confirm("确定要删除这条记录吗?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {"id": $id},
                    url: "<?=Url::to(['delete']) ?>",
                    success: function (msg) {
                        if (msg.flag === 1) {
//                            layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
                            layer.alert(msg.msg, {
                                icon: 1, end: function () {
                                    $('#data').datagrid('reload')
                                }
                            });
                        } else {
                            layer.alert(msg.msg, {icon: 2})
                        }
                    },
                    error: function (msg) {
                        layer.alert(msg.msg, {icon: 2})
                    }
                })
            },
            function () {
                layer.closeAll();
            }
        )
    }

    function putaway($id) {
        window.location.href = '<?= Url::to(["up-shelf"]) ?>?partno=' + $id;
//        $.ajax({
//            type: "get",
//            dataType: "json",
//            async: false,
//            data: {"id": $id},
//            url: "<?//=Url::to(['count']) ?>//",
//            success:function(data){
//                if(data != 'error'){
//                    window.location.href = '<?//= Url::to(["/ptdt/product-list/edit2"]) ?>//?id='+$id;
//                }else{
//                    window.location.href = '<?//= Url::to(["/ptdt/partno-price-confirm/create"]) ?>//';
//                }
//            }
//        })
    }

</script>