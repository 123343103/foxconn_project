<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/13
 * Time: 上午 11:31
 */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = '销售出库单详情';
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '销售出库列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .width-120 {
        width: 120px;
    }

    .content {
        font-size: 12px;
    }

    .width-300 {
        width: 300px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    label:after {
        content: "：";
    }
</style>
<div class="content">
    <div class="mb-10">
        <?= Html::button("打印", ["id" => "print", "class" => "button-blue",'onclick'=>'btnPrints()']); ?>
        <?= Html::button("切换列表", ["id" => "list", "class" => "button-blue"]); ?>
    </div>
    <div style="height:2px;background:#9acfea;"></div>
    <div class="space-30"></div>

    <h3 class="head-second">出库单信息</h3>
    <div class="space-30"></div>


    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">出库单号：</td>
            <td class="no-border vertical-center width-300"><?= $model['o_whcode'] ?></td>
            <td class="no-border vertical-center label-width label-align width-120">出库状态：</td>
            <td class="no-border vertical-center"><td class="no-border vertical-center"><?php foreach ($options["o_whstatus"] as $key => $val){
                if (strval($key) == $model['o_whstatus']){ ?>
                <?= $val ?></td>
            <?php }
            } ?>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">出库单日期：</td>
            <td class="no-border vertical-center width-300"><?= date("Y/m/d", strtotime($model['o_date'])) ?></td>
            <td class="no-border vertical-center label-width label-align width-120">申请人：</td>
            <td class="no-border vertical-center"><?= $model['create_name'] ?></td>
        </tr>
    </table>
    <div class="space-30"></div>
    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">物流订单号：</td>
            <td class="no-border vertical-center width-300"><?= $model['logistics_no'] ?></td>
            <td class="no-border vertical-center label-width label-align width-120">申请部门：</td>
            <td class="no-border vertical-center "><?= $model['organization_name'] ?></td>
        </tr>
    </table>


    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">交易法人：</td>
            <td class="no-border vertical-center width-300"><?= $model["company_name"] ?></td>
            <td class="no-border vertical-center label-width label-align width-120">关联拣货单：</td>
            <td class="no-border vertical-center"><?= $model["relate_packno"] ?></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">客户名称：</td>
            <td class="no-border vertical-center width-300"><?=$model["cust_sname"] ?></td>
            <td class="no-border vertical-center label-width label-align width-120">法人代码：</td>
            <td class="no-border vertical-center"><?= $model["company_code"] ?></td>
        </tr>
    </table>


    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">客户订单号：</td>
            <td class="no-border vertical-center width-300"><?= $model["ord_no"] ?></td>
            <td class="no-border vertical-center label-width label-align width-120">客户代码：</td>
            <td class="no-border vertical-center"><?= $model["cust_code"] ?></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">出仓仓库：</td>
            <td class="no-border vertical-center width-300" id="wh_id" data-id=""><?= $model['wh_name'] ?></td>
            <td class="no-border vertical-center label-width label-align width-120">订单类型：</td>
            <td class="no-border vertical-center" id="whs_operator"><?= $model['business_type_desc'] ?></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">仓库属性：</td>
            <td class="no-border vertical-center width-300"><?= $model["bsp_svalue"] ?></td>
            <td class="no-border vertical-center label-width label-align width-120">仓库代码：</td>
            <td class="no-border vertical-center"><?= $model["wh_code"] ?></td>
        </tr>
    </table>


    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">收货人：</td>
            <td class="no-border vertical-center width-300"><?= $model["reciver"] ?></td>
            <td class="no-border vertical-center label-width label-align width-120">联系方式：</td>
            <td class="no-border vertical-center"><?= $model["reciver_tel"] ?></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">收货地址：</td>
            <td class="no-border vertical-center">
                <?= $model["all_address"] ?>
            </td>
        </tr>
    </table>

    <div class="space-30"></div>

<?php if($model['o_whstatus']=='4'){?>
    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">取消原因：</td>
            <td class="no-border vertical-center"><?= $model["can_reason"] ?></td>
        </tr>
    </table>
<?php }?>

    <div class="space-30"></div>

    <h3 class="head-second">出库商品信息</h3>
    <table class="table">
        <thead>
        <th width="100">序号</th>
        <th width="250">料号</th>
        <th width="100">需求出货数量</th>
        <th width="100">拣货数量</th>
        <th width="100">出库数量</th>
        <th width="100">交易单位</th>
        <th width="100">商品单价</th>
        <th width="100">商品总价</th>
        <th width="100">配送方式</th>
        <th width="100">批次</th>
        <th width="100">交期</th>
        <th width="100">出库日期</th>
        <th width="100">备注</th>
        </thead>
        <tbody id="data">
        <?php if (count($childs['rows']) > 0) { ?>
            <?php foreach ($childs['rows'] as $k => $child) { ?>
                <tr>
                    <td align="center"><?= $k + 1 ?></td>
                    <td align="left"><p style="float: left">料号：<?= $child['part_no'] ?></p>
                        <p style="float: left">品名：<?= $child['pdt_name'] ?></p>
                        <p style="float: left">规格：<?= $child['tp_spec'] ?></p>
                        <p style="float: left">品牌：<?= $child['brand_name_cn'] ?></p></td>
                    <td align="center"><?= $child['req_num'] ?></td>
                    <td align="center"><?= $child['pck_nums'] ?></td>
                    <td align="center"><?= $child['o_whnum'] ?></td>
                    <td align="center"><?= $child['unit_name'] ?></td>
                    <td align="center"><?= $child['price'] ?></td>
                    <td align="center"><?= $child['sum_price'] ?></td>
                    <td align="center"><?= $child['bdm_sname'] ?></td>
                    <td align="center"><?= $child['L_invt_bach'] ?></td>
                    <td align="center"><?= $child['consignment_date'] ?></td>
                    <td align="center"><?= $child['o_date'] ?></td>
                    <td align="center"><?= $child['remarks'] ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td align="center" colspan="13">列表为空</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="space-30"></div>
</div>
<script>
    $("#list").click(function () {
        window.location.href = "<?=Url::to(['index'])?>";
    });
    /*表格打印*/
    function btnPrints() {
        var bdhtml = window.document.body.innerHTML;
        var sprnstr = "<!--startprint-->";
        var eprnstr = "<!--endprint-->";
        var prnhtml = bdhtml.substr(bdhtml.indexOf(sprnstr) + 17);
        var prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr));
        window.document.body.innerHTML = prnhtml;
        window.print();
        // 还原界面
        window.document.body.innerHTML = bdhtml
        window.location.reload();
    }
</script>