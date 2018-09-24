<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

/* @var $this yii\web\View */
/* @var $info app\modules\crm\models\CrmCreditApply */
$this->title = '额度使用明细';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '信用额度维护列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
//dumpE($info);
?>
<style>
    .width-80{
        width: 80px;
    }
    .width-120 {
        width: 120px;
    }
    .width-200 {
        width: 200px;
    }
    .ml-60 {
        margin-left: 60px;
    }
    /*.over-hidden {
        vertical-align: text-bottom;
        white-space:nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }*/
</style>

<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
    <div class="border-bottom mb-10  pb-10">
            <?= Html::button('切换列表', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <?= Html::button('打印', ['class' => 'button-blue width-80', 'onclick'=>'btnPrints()']) ?>
    </div>
    <div class="mb-10 create_content">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="7%">客户名称<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $info['cust_sname'] ?></td>
                <td class="no-border vertical-center label-align" width="7%">客户代码<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $info['cust_code'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="7%">交易法人<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $info['company_name'] ?></td>
                <td class="no-border vertical-center label-align" width="7%">客户经理人<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $info['customerManager'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="7%">信用额度类型<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $info['credit_name'] ?></td>
                <td class="no-border vertical-center label-align" width="7%">申请额度<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $info['credit_limit'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="7%">授信额度<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $info['approval_limit'] ?></td>
                <td class="no-border vertical-center label-align" width="7%">已使用额度<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $info['used_limit'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="7%">剩余额度<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $info['surplus_limit'] ?></td>
                <td class="no-border vertical-center label-align" width="7%">有效期至<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $info['validity'] ?></td>
            </tr>
        </table>
    </div>
    <div class="table-head">
        <p class="head">额度使用明细表</p>
    </div>
    <div class="data">
    </div>
</div>

<script>
    $(function () {
        $('.data').datagrid({
            url: "<?= Url::to(['limit-details','cust_id'=>Yii::$app->request->get('cust_id'),'laid'=>Yii::$app->request->get('laid')]);?>",
            rownumbers: true,
            method: "get",
            idField: "laid",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                {field: "saph_code", title: "订单编号", width: 150},
                {field: "bill_oamount", title: "订单总金额（含税）", width: 150},
                {field: "this_amount", title: "当次使用信用额度", width: 150},
                {field: "saph_date", title: "下单时间", width: 150},
                {field: "default", title: "开票时间", width: 150},
                {field: "pat_sname", title: "账期", width: 150},
                {field: "default", title: "应还款日", width: 150},
                {field: "yn_pay", title: "还款状态", width: 150},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('.data');
                showEmpty($(this),data.total,0);
                setMenuHeight();
            }
        })
    })
</script>
