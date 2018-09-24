<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/7/12
 * Time: 11:02
 */
use yii\helpers\Url;
use yii\helpers\Html;
use app\classes\Menu;
?>
<div class="table-head">
    <p class="head">信用额度查询</p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-credit-apply/export')?
            "<a id='export'>
                <div class='table-nav'>
                    <p class='export-item-bgc float-left'></p>
                    <p class='nav-font'>导出</p>
                </div>
            </a>"
        :'' ?>
        <p class="float-left">&nbsp;|&nbsp;</p>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
</div>
<script>
    $(function(){
        /*详情*/
        $('#view').click(function(){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请点击选择一条数据!",{icon:2,time:5000});
                return false;
            }else {
                window.location.href = "<?=Url::to(['view'])?>"+"?id=" + a.aid;
            }
        })
        /*导出*/
//        $("#export").click(function () {
//            layer.confirm("确定导出信息?",
//                {
//                    btn: ['确定', '取消'],
//                    icon: 2
//                },
//                function () {
//                    if (window.location.href = "<?//= Url::to(['index', 'export' => 1,
//                            'CrmCreditLimitSearch[credit_code]' => !empty($queryParam) ? $queryParam['CrmCreditLimitSearch']['credit_code'] : null,
//                            'CrmCreditLimitSearch[cust_code]' => !empty($queryParam) ? $queryParam['CrmCreditLimitSearch']['cust_code'] : null,
//                            'CrmCreditLimitSearch[cust_sname]' => !empty($queryParam) ? $queryParam['CrmCreditLimitSearch']['cust_sname'] : null,
//                            'CrmCreditLimitSearch[manager]' => !empty($queryParam) ? $queryParam['CrmCreditLimitSearch']['manager'] : null,
//                            'CrmCreditLimitSearch[company_name]' => !empty($queryParam) ? $queryParam['CrmCreditLimitSearch']['company_name'] : null,
//                            'CrmCreditLimitSearch[credit_status]' => !empty($queryParam) ? $queryParam['CrmCreditLimitSearch']['credit_status'] : null,
//                            'CrmCreditLimitSearch[apply_name]' => !empty($queryParam) ? $queryParam['CrmCreditLimitSearch']['apply_name'] : null]) ?>//") {
//                        layer.closeAll();
//                    } else {
//                        layer.alert('导出账信申请信息错误!', {icon: 0})
//                    }
//                },
//                function () {
//                    layer.closeAll();
//                }
//            );
//        });
    })
</script>
