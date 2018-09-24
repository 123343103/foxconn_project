<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/1
 * Time: 11:45
 */
use yii\helpers\Url;
use app\classes\Menu;
?>

<div class="table-head">
    <p class="head">报价单列表</p>
    <div class="float-right">
        <?= Menu::isAllow('/sale/sale-quoted-order/index','btn_add') ?
        '<a id=\'order-quotation\' class="display-none">
            <div class=\'table-nav\'>
                <p class=\'to-price-item-bgc float-left\'></p>
                <p class=\'nav-font\'>&nbsp;订单报价</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>':''?>
        <?= Menu::isAllow('/sale/sale-quoted-order/index','btn_mody') ?
        '<a id=\'update\' class="display-none">
            <div class=\'table-nav\'>
                <p class=\'update-item-bgc float-left\'></p>
                <p class=\'nav-font\'>&nbsp;修改</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>':'' ?>
        <?= Menu::isAllow('/sale/sale-quoted-order/index','btn_trial') ?
        '<a id=\'check\' class="display-none">
            <div class=\'table-nav\'>
                <p class=\'submit-item-bgc float-left\'></p>
                <p class=\'nav-font\'>&nbsp;送审</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>':'' ?>
        <?= Menu::isAllow('/sale/sale-quoted-order/index','btn_cancle') ?
        '<a id=\'cancle-quote\' class="display-none">
            <div class=\'table-nav\'>
                <p class=\'cancel-order-item-bgc float-left\'></p>
                <p class=\'nav-font\'>&nbsp;取消报价</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>':''?>
        <?= Menu::isAllow('/sale/sale-quoted-order/index','btn_detail') ?
        '<a id=\'detail\' href="'.Url::to(['detail-list']) .'">
            <div class=\'table-nav\'>
                <p class=\'setting10 float-left\'></p>
                <p class=\'nav-font\'>&nbsp;明细列表</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>':'' ?>
        <?= Menu::isAllow('/sale/sale-quoted-order/index','btn_export') ?
        '<a id=\'export\'>
            <div class=\'table-nav\'>
                <p class=\'export-item-bgc float-left\'></p>
                <p class=\'nav-font\'>&nbsp;导出</p>
            </div>
            <p class="float-left">&nbsp;|&nbsp;</p>
        </a>':'' ?>
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

        /*订单报价*/
        $('#order-quotation').click(function(){
            var a = $("#data").datagrid("getSelected");
            if (a === null) {
                layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['price_id'];
                window.location.href = "<?=Url::to(['create'])?>?id=" + id;
            }
        })
        /*修改*/
        $('#update').click(function(){
            var a = $("#data").datagrid("getSelected");
            if (a === null) {
                layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['price_id'];
                window.location.href = "<?=Url::to(['update'])?>?id=" + id;
            }
        })
        /*送审*/
        $('#check').click(function(){
            var a = $("#data").datagrid("getSelected");
//            if (a === null) {
//                layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});return false;
//            }
//            if(a.audit_id !== '13' && a.audit_id !== '10'){
//                layer.alert("审核中或者审核完成,无法送审!",{icon:2,time:5000});
//                return false;
//            }
            var id = a.price_id;
            var url = "<?=Url::to(['index'])?>";
            var type=a["price_type"];
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        });
//        $("#check").on("click", function () {
//            var data = $("#data").datagrid("getChecked");
//            if (data.length == 0) {
//                layer.alert("请至少选择一条申请信息", {icon: 2, time: 5000});
//                return false;
//            }
//            var id = '';
//            var arr = [];
//            var arrcheck = [];
//            for (var i = 0; i < data.length; i++) {
////                id += data[i].price_id + '-';
//                if(data[i].audit_id !== '13' && data[i].audit_id !== '10'){
//                    arr.push(data[i].price_id);
//                }
//                if(data[i].audit_id === '13' || data[i].audit_id === '10'){
//                    arrcheck.push(data[i].price_id);
//                }
//            }
//            if(arrcheck.length == 0){
//                layer.alert("非驳回状态,不可送审", {icon: 2, time: 5000});return false;
//            }else{
//                for (var a = 0; a < arrcheck.length; a++) {
//                    id += arrcheck[a] + '-';
//                }
//                layer.confirm(arrcheck.length+"条记录可以送审,"+arr.length+"条记录不可以送审" ,
//                    {
//                        btn: ['确定', '取消'],
//                        icon: 2
//                    },function () {
//                        var url = "<?//=Url::to(['index'], true)?>//";
//                        var type = '<?//= $typeId ?>//';
//                        $.fancybox({
//                            href: "<?//=Url::to(['/system/verify-record/new-reviewer'])?>//?type=" + type + "&id=" + id + "&url=" + url,
//                            type: "iframe",
//                            padding: 0,
//                            autoSize: false,
//                            width: 750,
//                            height: 480
//                        });
//                        layer.closeAll();
//                    }
//                )
//            }
//        });
        /*取消报价*/
//        $('#cancle-quote').click(function(){
//            var a = $("#data").datagrid("getSelected");
//            if (a === null) {
//                layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});return false;
//            }
//            if(a.audit_id == 20 || a.audit_id == 30){
//                layer.alert("审核中或者审核完成,无法取消报价!",{icon:2,time:5000});
//                return false;
//            }
//            var id = a.price_id;
//            $.fancybox({
//                href:"<?//=Url::to(['cancle-quote'])?>//?id="+id,
//                type:"iframe",
//                padding:0,
//                autoSize:false,
//                width:445,
//                height:240
//            });
//        })
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
//                id += data[i].price_id + '-';
                if(data[i].audit_id == '11' && data[i].audit_id == '12'){
                    arr.push(data[i].price_id);
                }
                if(data[i].audit_id == '10' || data[i].audit_id == '13'){
                    arrcheck.push(data[i].price_id);
                }
            }
            if(arrcheck.length == 0){
                layer.alert("不可取消", {icon: 2, time: 5000});return false;
            }else{
                id = arrcheck.join(',');
                $.fancybox({
                    href:"<?=Url::to(['cancle-quote'])?>?id="+id,
                    type:"iframe",
                    padding:0,
                    autoSize:false,
                    width:445,
                    height:240
                });
//                layer.confirm(arrcheck.length+"条记录可以取消,"+arr.length+"条记录不可以取消" ,
//                    {
//                        btn: ['确定', '取消'],
//                        icon: 2
//                    },function () {
//                        $.fancybox({
//                            href:"<?//=Url::to(['cancle-quote'])?>//?id="+id,
//                            type:"iframe",
//                            padding:0,
//                            autoSize:false,
//                            width:445,
//                            height:240
//                        });
//                        layer.closeAll();
//                    }
//                )
            }
        });
        /*报价单导出 start*/
        $("#export").click(function () {
            layer.confirm("确定导出报价单信息?",
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
