<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/11/8
 * Time: 11:39
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title = '呈报审核';
?>
<style>
    .font-size {
        font-size: 14px;
    }
</style>
<?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
<h1 class="head-first">
    <?= $this->title ?>
    <span class="head-code">编号：<?= $result['vco_code'] ?></span>
</h1>
<div class="mb-10 ml-30 lable-p">
    <label class="width-110 forbidden font-size text-left">代理等级</label>
    <span class="width-150  text-next"><?= $model['bsPubdata']['agentsType']; ?></span>
    <label class="width-80 forbidden font-size text-left">开发类型</label>
    <span class="width-150  text-next"><?= $model['bsPubdata']['developType']; ?></span>
    <label class="width-80 forbidden font-size text-left">紧急程度</label>
    <span class="width-150  text-next"><?= $model['bsPubdata']['urgencyDegree']; ?></span>
</div>
<div class="mb-30">
    <h2 class="head-second">
        <p>厂商基本信息</p>
    </h2>
    <div class="mb-10 ml-30 lable-p">
        <label class="width-110 text-left">注册公司名称</label>
        <span class="width-150  text-next"><?= $model['firmName'] ?></span>
        <label class="width-80 text-left">简称</label>
        <span class="width-150  text-next"><?= $model['firm_shortname'] ?></span>
        <label class="width-80 text-left">品牌</label>
        <span class="width-150  text-next"><?= $model['firm_brand'] ?></span>
    </div>
    <div class="mb-10 ml-30 lable-p">
        <label class="width-110 text-left">是否为集团供应商</label>
        <span class="width-150  text-next"><?= $model['firmSupplier'] ?></span>
        <label class="width-80 text-left">英文全称</label>
        <span class="width-150  text-next"><?= $model['firm_message']['firm_ename'] ?></span>
        <label class="width-80 text-left">厂商地址</label>
        <span class="width-150  text-next"><?= $model['firm_message']['firmAddress']['fullAddress'] ?></span>
    </div>
    <div class="mb-10 ml-30 lable-p">
        <label class="width-110 text-left">厂商来源</label>
        <span class="width-150  text-next"><?= $model['firm_source'] ?></span>
        <label class="width-80 text-left">厂商类型</label>
        <span class="width-150  text-next"><?= $model['firm_type'] ?></span>
        <label class="width-80 text-left">分级分类</label>
        <span class="width-150  text-next"><?= $model['firmCategory'] ?></span>
    </div>
</div>
<div class="mb-10">
    <h2 class="head-second">
        <p>代理授权谈判呈报</p>
    </h2>
    <div class="mb-10 ml-10">
        <span class="width-100 border-span text-center click-span" id="negotiate-abstract">谈判信息</span>
        <span class="width-100 height-30 border-span text-center line-height-30" id="product-analyze">产品优劣势分析</span>
    </div>
</div>
<div class="negotiate">
    <div class="">
        <div class="mb-10 text-center view-title">基本信息</div>
        <div class="mb-10 ml-30 lable-p">
            <label class="width-130 text-left">主谈人(商品经理人)</label>
            <span class="width-150  text-next">
                    <?= $childModel['productPerson']['code']; ?>
                    <?= $childModel['productPerson']['name']; ?>
                </span>
            <label class="width-80 text-left">职位</label>
            <span class="width-150  text-next"><?= $childModel['productPerson']['job']; ?></span>
            <label class="width-80 text-left">联系电话</label>
            <span class="width-150  text-next"><?= $childModel['productPerson']['mobile'] ?></span>
        </div>
        <?php foreach ($childModel['staffPerson'] as $item) { ?>
            <div class="mb-10 ml-30 lable-p">
                <label class="width-130 text-left">陪同人员</label>
                <span class="width-150  text-next"><?php echo $item['staff_name'] ?></span>
                <label class="width-80 text-left">职位</label>
                <span class="width-150  text-next"><?php echo $item['job_task'] ?></span>
                <label class="width-80 text-left">联系方式</label>
                <span class="width-150  text-next"><?php echo $item['staff_mobile'] ?></span>
            </div>
        <?php } ?>
        <div class="mb-10 ml-30 lable-p">
            <label class="width-130 text-left">拜访日期</label>
            <span class="width-150  text-next"><?= $childModel['pfrc_date']; ?></span>
            <label class="width-80 text-left">拜访时间</label>
            <span class="width-150  text-next"><?= $childModel['pfrc_time']; ?></span>
            <label class="width-80 text-left">拜访地点</label>
            <span class="width-150  text-next"><?= $childModel['pfrc_location']; ?></span>
        </div>
        <div class="mb-10 ml-30 lable-p">
            <label class="width-130 text-left">厂商主谈人</label>
            <span class="width-150  text-next"><?= $childModel['reception']['receSname']; ?></span>
            <label class="width-80 text-left">职位</label>
            <span class="width-150  text-next"><?= $childModel['reception']['recePosition']; ?></span>
            <label class="width-80 text-left">联系方式</label>
            <span class="width-150  text-next"><?= $childModel['reception']['receMobile']; ?></span>
        </div>
        <div class="mb-30">
            <div class="mb-10 text-center view-title">谈判内容</div>
            <div class="mb-10 ml-30 lable-p">
                <label class="width-130 text-left">授权区域范围</label>
                <span class="width-150  text-next"><?= $childModel['bsPubdata']['authorizeArea']; ?></span>
                <label class="width-80 text-left">代理等级</label>
                <span class="width-150  text-next"><?= $childModel['bsPubdata']['agentsGrade']; ?></span>
                <label class="width-80 text-left">销售范围</label>
                <span class="width-150  text-next"><?= $childModel['bsPubdata']['saleArea']; ?></span>
            </div>
            <div class="mb-10 ml-30 lable-p">
                <label class="text-left" style="width:105px;">授权日期</label>
                <span class="width-80 text-next"><?= $childModel['authorize']['pdaa_bdate']; ?></span>
                <span>至</span>
                <span class="width-80 text-next"
                      style="text-align: right;"><?= $childModel['authorize']['pdaa_edate']; ?></span>
                <label class="width-80 text-left">结算方式</label>
                <span class="width-150  text-next"><?= $childModel['firmSettlement']; ?></span>
                <label class="width-80 text-left">交期</label>
                <span class="width-150  text-next"><?= $childModel['authorize']['pdaa_delivery_day']; ?></span>
            </div>
            <div class="mb-10 ml-30 lable-p">
                <label class="text-left" style="width:115px;">售后服务</label>
                <span class="width-150  text-next"><?= $childModel['bsPubdata']['firmService']; ?></span>
                <label class="width-80 text-left">物流配送</label>
                <span class="width-150  text-next"><?= $childModel['bsPubdata']['firmDeliveryWay']; ?></span>
            </div>
        </div>
        <div class="mb-30 ml-10">
            <div class="overflow-auto">
                <div class="mb-10 text-center view-title">商品详细信息</div>
                <p style="font-size: 14px;">商品信息</p>
                <div class="overflow-auto  margin-auto pb-20">
                    <div class="space-10"></div>
                    <table class="product-list table-auto">
                        <tbody id="table_body">
                        <tr>
                            <th>商品品名</th>
                            <th>规格型号</th>
                            <th>品牌</th>
                            <th>交货条件</th>
                            <th>付款条件</th>
                            <th>交易单位</th>
                            <th>交易币别</th>
                            <th>定价上限</th>
                            <th>定价下限</th>
                            <th>量价区间</th>
                            <th>市场均价</th>
                            <th>代理商品定位</th>
                            <th>利润率</th>
                            <th>一阶</th>
                            <th>二阶</th>
                            <th>三阶</th>
                            <th>四阶</th>
                            <th>五阶</th>
                            <th>六阶</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="product-table display-none overflow-auto mb-20">
    <table class="analyze-table" style="width:660px;">
        <tbody>
        <tr>
            <th colspan="<?= count($lists) + 5 ?>">
                <span id="product-analyze-table">产品优势分析表</span>
            </th>
        </tr>
        <tr>
            <td rowspan="2">分类</td>
            <td rowspan="2">项目</td>
            <td rowspan="2">内容</td>
            <td colspan="<?= count($lists) + 5 ?>">具体描述</td>
        </tr>
        <tr class="pdna_firm">

        </tr>
        <tr class="pdna_influence">
            <td rowspan="4">供应商面</td>
            <td rowspan="4">厂商实力评估</td>
            <td>业界影响力/排名</td>

        </tr>
        <tr class="pdna_technology_service">
            <td>技术实力、技术服务</td>
        </tr>
        <tr class="pdna_cooperate_degree">
            <td>厂商配合度</td>
        </tr>
        <tr class="pdna_others">
            <td>其他</td>
        </tr>
        <tr class="pdna_demand_trends">
            <td rowspan="6">商品面</td>
            <td rowspan="4">商品与市场</td>
            <td>市场需求趋势</td>
        </tr>
        <tr class="pdna_goods_certificate">
            <td>商品认证/厂商认证</td>
        </tr>
        <tr class="pdna_customer_base">
            <td>客户群(By产业)</td>
        </tr>
        <tr class="pdna_market_share">
            <td>销量/市占率</td>
        </tr>
        <tr class="profit_analysis">
            <td rowspan="2">利润与价格</td>
            <td>利润分析</td>
        </tr>
        <tr class="sales_advantage">
            <td>价格优势</td>
        </tr>
        <tr class="value_fjj">
            <td rowspan="2">代理价值</td>
            <td colspan="2">富金机</td>
        </tr>
        <tr class="value_frim">
            <td colspan="2">厂商</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="mb-20">
    <label class="vertical-top">签核意见</label>
    <textarea style="width:616px;" name="remark" id="" cols="3" rows="3"></textarea>
</div>
<div class="width-400 margin-auto">
    <button class="button-blue-big" type="button" id="pass">通&nbsp过</button>
    <button class="button-blue-big" type="button" id="reject">驳&nbsp回</button>
    <button class="button-white-big" type="button" onclick="close_select()">取&nbsp消</button>
</div>
<div class="space-20"></div>
<input type="hidden" name="id" value="<?= $id ?>">
<?php ActiveForm::end(); ?>
<script>
    $(function () {
//        ajaxSubmitForm($("#check-form"));     //ajax 提交
        $("#negotiate-abstract").click(function () {
            $(".negotiate").show();
            $(".product-table").hide();
            $("#negotiate-abstract").addClass('click-span').removeClass('unclick-span');
            $("#product-analyze").addClass('unclick-span').removeClass('click-span');
        });
        $("#product-analyze").click(function () {
            $(".product-table").show();
            $(".negotiate").hide();
            $("#product-analyze").addClass('click-span').removeClass('unclick-span');
            $("#negotiate-abstract").addClass('unclick-span').removeClass('click-span');
        });
    })
    <?php
    $i = 1;
    foreach($childModel['products'] as $key){
    ?>
    var tdStr = "<tr>";
    tdStr += "<td>" + "<?= $key['product_name']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['product_size']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['product_brand']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['delivery_terms']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['payment_terms']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['unit']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['currency_type']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['price_max']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['price_min']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['price_range']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['price_average']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['levelName']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['profit_margin']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][0]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][1]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][2]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][3]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][4]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][5]; ?>" + "</td>";
    tdStr += "</tr>";
    $("#table_body").append(tdStr);
    <?php $i++;} ?>
    <?php if(count($firmCompared) != 0){ ?>
    <?php foreach($lists as $key => $val){ ?>
    $(".pdna_firm").append("<td> <?php echo isset($val[$key]['firm']['firm_shortname']) ? $val[$key]['firm']['firm_shortname'] : $val[$key]['firm']['firm_sname']; ?> </td>");
    $(".pdna_influence").append("<td> <?php echo $val[$key]['analysis']['pdna_influence'] ? $val[$key]['analysis']['pdna_influence'] : "/"; ?> </td>");
    $(".pdna_technology_service").append("<td> <?php echo $val[$key]['analysis']['pdna_technology_service'] ? $val[$key]['analysis']['pdna_technology_service'] : "/"; ?> </td>");
    $(".pdna_cooperate_degree").append("<td> <?php echo $val[$key]['analysis']['bsPubdata']['cooperateDegree'] ? $val[$key]['analysis']['bsPubdata']['cooperateDegree'] : "/"; ?> </td>");
    $(".pdna_others").append("<td> <?php echo $val[$key]['analysis']['pdna_others'] ? $val[$key]['analysis']['pdna_others'] : "/"; ?> </td>");
    $(".pdna_demand_trends").append("<td> <?php echo $val[$key]['analysis']['pdna_demand_trends'] ? $val[$key]['analysis']['pdna_demand_trends'] : "/"; ?> </td>");
    $(".pdna_goods_certificate").append("<td> <?php echo $val[$key]['analysis']['pdna_goods_certificate'] ? $val[$key]['analysis']['pdna_goods_certificate'] : "/"; ?> </td>");
    $(".pdna_customer_base").append("<td> <?php echo $val[$key]['analysis']['pdna_customer_base'] ? $val[$key]['analysis']['pdna_customer_base'] : "/"; ?> </td>");
    $(".pdna_market_share").append("<td> <?php echo $val[$key]['analysis']['pdna_market_share'] ? $val[$key]['analysis']['pdna_market_share'] : "/"; ?> </td>");
    $(".profit_analysis").append("<td> <?php echo $val[$key]['analysis']['profit_analysis'] ? $val[$key]['analysis']['profit_analysis'] : "/"; ?> </td>");
    $(".sales_advantage").append("<td> <?php echo $val[$key]['analysis']['sales_advantage'] ? $val[$key]['analysis']['sales_advantage'] : "/"; ?> </td>");
    $(".value_fjj").append("<td> <?php echo $val[$key]['analysis']['value_fjj'] ? $val[$key]['analysis']['value_fjj'] : "/"; ?> </td>");
    $(".value_frim").append("<td> <?php echo $val[$key]['analysis']['value_frim'] ? $val[$key]['analysis']['value_frim'] : '/'; ?> </td>");
    <?php } ?>
    <?php }else{ ?>
    $(".pdna_firm").append("<td> <?php echo $lists['firm']['firm_shortname'] ? $lists['firm']['firm_shortname'] : $lists['firm']['firm_sname']; ?> </td>");
    $(".pdna_influence").append("<td> <?php echo $lists['analysis']['pdna_influence'] ? $lists['analysis']['pdna_influence'] : "/"; ?> </td>");
    $(".pdna_technology_service").append("<td> <?php echo $lists['analysis']['pdna_technology_service'] ? $lists['analysis']['pdna_technology_service'] : "/"; ?> </td>");
    $(".pdna_cooperate_degree").append("<td> <?php echo $lists['analysis']['bsPubdata']['cooperateDegree'] ? $lists['analysis']['bsPubdata']['cooperateDegree'] : "/"; ?> </td>");
    $(".pdna_others").append("<td> <?php echo $lists['analysis']['pdna_others'] ? $lists['analysis']['pdna_others'] : "/"; ?> </td>");
    $(".pdna_demand_trends").append("<td> <?php echo $lists['analysis']['pdna_demand_trends'] ? $lists['analysis']['pdna_demand_trends'] : "/"; ?> </td>");
    $(".pdna_goods_certificate").append("<td> <?php echo $lists['analysis']['pdna_goods_certificate'] ? $lists['analysis']['pdna_goods_certificate'] : "/"; ?> </td>");
    $(".pdna_customer_base").append("<td> <?php echo $lists['analysis']['pdna_customer_base'] ? $lists['analysis']['pdna_customer_base'] : "/"; ?> </td>");
    $(".pdna_market_share").append("<td> <?php echo $lists['analysis']['pdna_market_share'] ? $lists['analysis']['pdna_market_share'] : "/"; ?> </td>");
    $(".profit_analysis").append("<td> <?php echo $lists['analysis']['profit_analysis'] ? $lists['analysis']['profit_analysis'] : "/"; ?> </td>");
    $(".sales_advantage").append("<td> <?php echo $lists['analysis']['sales_advantage'] ? $lists['analysis']['sales_advantage'] : "/"; ?> </td>");
    $(".value_fjj").append("<td> <?php echo $lists['analysis']['value_fjj'] ? $lists['analysis']['value_fjj'] : "/"; ?> </td>");
    $(".value_frim").append("<td> <?php echo $lists['analysis']['value_frim'] ? $lists['analysis']['value_frim'] : '/'; ?> </td>");
    <?php } ?>
    //        $("#pass").one("click", function () {
    //            $("#check-form").attr('action','<?//= \yii\helpers\Url::to(['/system/verify-record/audit-pass']) ?>//');
    //            return ajaxSubmitForm($("#check-form"));
    //        });
    $("#pass").on("click", function () {
        layer.confirm("是否通过?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                $.ajax({
                    type: "post",
                    dataType: "json",
                    data: $("#check-form").serialize(),
                    url: "<?= Url::to(['/system/verify-record/audit-pass']) ?>",
                    success: function (msg) {
                        if (msg.flag == 1) {
                            layer.alert(msg.msg, {icon: 1}, function () {
                                parent.window.location.href = '<?= Url::to(['index']) ?>'
                            });
                        } else {
                            layer.alert(msg.msg, {icon: 2})
                        }
                    }
                })
            },
            function () {
                layer.closeAll();
            }
        )
    });
    //        $("#reject").one("click", function () {
    //            $("#check-form").attr('action','<?//= Url::to(['/system/verify-record/audit-reject']) ?>//');
    //            return ajaxSubmitForm($("#check-form"));
    //        });
    $("#reject").on("click", function () {
        layer.confirm("是否驳回?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                $.ajax({
                    type: "post",
                    dataType: "json",
                    data: $("#check-form").serialize(),
                    url: "<?= Url::to(['/system/verify-record/audit-reject']) ?>",
                    success: function (msg) {
                        if (msg.flag == 1) {
                            layer.alert(msg.msg, {icon: 1}, function () {
                                parent.window.location.href = '<?= Url::to(['index']) ?>'
                            });
                        } else {
                            layer.alert(msg.msg, {icon: 2})
                        }
                    }
                })
            },
            function () {
                layer.closeAll();
            }
        )
    });
</script>
