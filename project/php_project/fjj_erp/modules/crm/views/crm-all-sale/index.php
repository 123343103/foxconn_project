<?php
use app\assets\MultiSelectAsset;
use yii\helpers\Url;
use app\classes\Menu;

MultiSelectAsset::register($this);

$this->title = '销售客户资料查询';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '销售客户资料查询', 'url' => Url::to(['index'])];
?>
<div class="content">
    <?php echo $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $queryParam,
        'result' => $result,
        'staff' => $staff,
        'employee' => $employee,
        'isSuper' => $isSuper,
    ]); ?>
    <div class="space-10"></div>
    <div class="table-head">
        <p class="head">客户列表</p>
        <div class="float-right">
            <?= Menu::isAction('/crm/crm-all-customer/index') ?
                "<a id='export'>
                <div class='table-nav'>
                    <p class='export-item-bgc float-left'></p>
                    <p class='nav-font'>导出</p>
                </div>
            </a>
            <p class='float-left'>&nbsp;|&nbsp;</p>"
                : '' ?>
            <a href="<?= Url::to(['/index/index']) ?>">
                <div class='table-nav'>
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>返回</p>
                </div>
            </a>
        </div>
    </div>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
    </div>
</div>
<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: '加载中...',
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: true,
            columns: [[
                {field: "cust_filernumber", title: "系统编号", width: 100},
                {field: "cust_id", title: "", width: 100, hidden: true},
                {field: "cust_code", title: "客户代码", width: 100},
                {field: "cust_sname", title: "客户名称", width: 100},
                {field: "cust_shortname", title: "客户简称", width: 100},
                {field: "cust_type", title: "类型", width: 100},
                {field: "cust_level", title: "等级", width: 100},
                {field: "cust_tel1", title: "公司电话", width: 100},
                {field: "cust_fax", title: "传真", width: 100},
                {field: "cust_inchargeperson", title: "公司法人", width: 100},
                {field: "member_regweb", title: "公司网址", width: 100},
                {field: "cust_contacts", title: "联系人", width: 100},
                {field: "cust_tel1", title: "联系电话", width: 100},
                {field: "cust_email", title: "邮箱", width: 100},
                {field: "custManager", title: "客户经理人", width: 100},
                {field: "cust_salearea", title: "营销区域", width: 100},
                {field: "customerAddress", title: "公司地址", width: 100},
                {field: "cust_compscale", title: "公司规模", width: 100},
                {field: "member_reqitemclass", title: "需求类目", width: 100},
                {field: "cust_businesstype", title: "经营类型", width: 100},
                {field: "cust_compvirtue", title: "公司属性", width: 100},
                {field: "cust_personqty", title: "员工数", width: 100},
                {field: "cust_regdate", title: "注册时间", width: 100},
                {field: "cust_regfunds", title: "注册资金", width: 100},
                {
                    field: "cust_islisted", title: "是否上市", width: 100, formatter: function (value, row, index) {
                    if (row.cust_islisted == 0) {
                        return '否';
                    } else if (row.cust_islisted == 1) {
                        return '是';
                    }
                }
                },
                {field: "member_compsum", title: "年营业额", width: 100},
                {field: "cust_tax_code", title: "税籍编码", width: 100},
                {field: "cust_t", title: "客户属性", width: 100},
                {
                    field: "cust_ismember", title: "是否会员", width: 100, formatter: function (value, row, index) {
                    if (row.cust_ismember == 0) {
                        return '否';
                    } else if (row.cust_ismember == 1) {
                        return '是';
                    }
                }
                },
                {field: "member_name", title: "会员名", width: 100},
                {field: "cust_email", title: "注册邮箱", width: 100},
                {field: "cust_tel2", title: "注册手机", width: 100}
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this), data.total, 1);
//                datagridTip('#data');
//                showEmpty($(this),data.total,1);
                setMenuHeight();
            }
        });
        $('#export').click(function () {
            $.fancybox({
                href: "<?= Url::to(['select-columns', 'export' => '1', 'CrmCustomerInfoSearch[cust_sname]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['cust_sname'] : null, 'CrmCustomerInfoSearch[cust_type]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['cust_type'] : null, 'CrmCustomerInfoSearch[cust_salearea]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['cust_salearea'] : null, 'CrmCustomerInfoSearch[cust_area]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['cust_area'] : null, 'CrmCustomerInfoSearch[custManager]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['custManager'] : null, 'CrmCustomerInfoSearch[property]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['property'] : null])?>",
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 750,
                height: 480
            });
        });
    })
</script>