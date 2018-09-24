<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/1
 * Time: 11:45
 */
use yii\helpers\Url;

?>

<div class="table-head">
    <p class="head">退款列表</p>
    <div class="float-right">
        <a id='update' class="display-none">
            <div class='table-nav'>
                <p class='update-item-bgc float-left'></p>
                <p class='nav-font'>&nbsp;修改</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>
        <a id='check' class="display-none">
            <div class='table-nav'>
                <p class='submit-item-bgc float-left'></p>
                <p class='nav-font'>&nbsp;送审</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>
        <a id='cancle-quote' class="display-none">
            <div class='table-nav'>
                <p class='cancel-order-item-bgc float-left'></p>
                <p class='nav-font'>&nbsp;取消退款</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>
        <a id='confirm' class="display-none">
            <div class='table-nav'>
                <p class='setting10 float-left'></p>
                <p class='nav-font'>&nbsp;确认退款</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>
        <a id='export'>
            <div class='table-nav'>
                <p class='export-item-bgc float-left'></p>
                <p class='nav-font'>&nbsp;导出</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>
        <a href="<?= Url::home() ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>&nbsp;返回</p>
            </div>
        </a>
    </div>
</div>
<script>
    $(function(){
        /*修改*/
        $('#update').click(function(){
            var a = $("#data").datagrid("getSelected");
            if (a === null) {
                layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['refund_id'];
                window.location.href = "<?=Url::to(['update'])?>?id=" + id;
            }
        })
        /*送审*/
        $("#check").on("click", function () {
            var data = $("#data").datagrid("getChecked");
            if (data.length == 0) {
                layer.alert("请至少选择一条申请信息", {icon: 2, time: 5000});
                return false;
            }
            var id = '';
            var arr = [];
            var arrcheck = [];
            for (var i = 0; i < data.length; i++) {
//                id += data[i].price_id + '-';
                if(data[i].rfnd_status !== '13'){
                    arr.push(data[i].refund_id);
                }
                if(data[i].rfnd_status === '13'){
                    arrcheck.push(data[i].refund_id);
                }
            }
            if(arrcheck.length == 0){
                layer.alert("非驳回状态,不可送审", {icon: 2, time: 5000});return false;
            }else{
                for (var a = 0; a < arrcheck.length; a++) {
                    id += arrcheck[a] + '-';
                }
                layer.confirm(arrcheck.length+"条记录可以送审,"+arr.length+"条记录不可以送审" ,
                    {
                        btn: ['确定', '取消'],
                        icon: 2
                    },function () {
                        var url = "<?=Url::to(['index'], true)?>";
                        var type = '<?= $typeId ?>';
                        $.fancybox({
                            href: "<?=Url::to(['/system/verify-record/new-reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                            type: "iframe",
                            padding: 0,
                            autoSize: false,
                            width: 750,
                            height: 480
                        });
                        layer.closeAll();
                    }
                )
            }
        });
        /*取消退款*/
        $("#cancle-quote").on("click", function () {
            var data = $("#data").datagrid("getChecked");
            if (data.length == 0) {
                layer.alert("请至少选择一条申请信息", {icon: 2, time: 5000});
                return false;
            }
            var id = '';
            var arr = [];
            var arrcheck = [];
            for (var i = 0; i < data.length; i++) {
                if(data[i].rfnd_status !== '13' ){
                    arr.push(data[i].refund_id);
                }
                if(data[i].rfnd_status === '13'){
                    arrcheck.push(data[i].refund_id);
                }
            }
            if(arrcheck.length == 0){
                layer.alert("不可取消", {icon: 2, time: 5000});return false;
            }else{
                id = arrcheck.join(',');
                layer.confirm(arrcheck.length+"条记录可以取消,"+arr.length+"条记录不可以取消" ,
                    {
                        btn: ['确定', '取消'],
                        icon: 2
                    },function () {
                        $.fancybox({
                            href:"<?=Url::to(['cancle-quote'])?>?id="+id,
                            type:"iframe",
                            padding:0,
                            autoSize:false,
                            width:445,
                            height:240
                        });
                        layer.closeAll();
                    }
                )
            }
        });

        /*确认退款*/
        $("#confirm").on("click", function () {
            var data = $("#data").datagrid("getChecked");
            if (data.length == 0) {
                layer.alert("请至少选择一条信息", {icon: 2, time: 5000});
                return false;
            }
            var id = '';
            var arr = [];
            var arrcheck = [];
            for (var i = 0; i < data.length; i++) {
                if(data[i].rfnd_status != '12' ){
                    arr.push(data[i].refund_id);
                }
                if(data[i].rfnd_status == '12'){
                    arrcheck.push(data[i].refund_id);
                }
            }
            if(arrcheck.length == 0){
                layer.alert("不可退款", {icon: 2, time: 5000});return false;
            }else{
                id = arrcheck.join(',');
                layer.confirm(arrcheck.length+"条记录可以确认退款,"+arr.length+"条记录不可以确认退款" ,
                    {
                        btn: ['确定', '取消'],
                        icon: 2
                    },function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            async: false,
                            data: {"id": id},
                            url: "<?=Url::to(['confirm']) ?>",
                            success: function (msg) {
                                if (msg.status === 1) {
                                    layer.alert('退款成功', {
                                        icon: 1, end: function () {
                                            location.href = '<?= Url::to(['index']) ?>'
                                        }
                                    });
                                } else {
                                    layer.alert('退款失败', {icon: 2})
                                }
                            },
                            error: function (msg) {
                                layer.alert('退款失败', {icon: 2})
                            }
                        })
                    },function () {
                        layer.closeAll();
                    }
                )
            }
        });
        /*报价单导出 start*/
        $("#export").click(function () {
            layer.confirm("确定导出退款信息?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['export', 'PriceInfoSearch[price_no]' => !empty($queryParam) ? $queryParam['PriceInfoSearch']['price_no'] : null, 'PriceInfoSearch[audit_id]' => !empty($queryParam) ? $queryParam['PriceInfoSearch']['audit_id'] : null, 'PriceInfoSearch[saph_code]' => !empty($queryParam) ? $queryParam['PriceInfoSearch']['saph_code'] : null, 'PriceInfoSearch[price_type]' => !empty($queryParam) ? $queryParam['PriceInfoSearch']['price_type'] : null, 'PriceInfoSearch[cust_sname]' => !empty($queryParam) ? $queryParam['PriceInfoSearch']['cust_sname'] : null, 'PriceInfoSearch[applyno]' => !empty($queryParam) ? $queryParam['PriceInfoSearch']['applyno'] : null, 'PriceInfoSearch[corporate]' => !empty($queryParam) ? $queryParam['PriceInfoSearch']['corporate'] : null, 'PriceInfoSearch[pac_id]' => !empty($queryParam) ? $queryParam['PriceInfoSearch']['pac_id'] : null, 'PriceInfoSearch[start_date]' => !empty($queryParam) ? $queryParam['PriceInfoSearch']['start_date'] : null, 'PriceInfoSearch[end_date]' => !empty($queryParam) ? $queryParam['PriceInfoSearch']['end_date'] : null]) ?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出报价单错误!', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            );
        });
    })
</script>
