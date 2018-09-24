<?php

use yii\helpers\Url;

$this->title = '供应商详情';
$this->params['homeLike'] = ['label' => '供应商管理'];
$this->params['breadcrumbs'][] = ['label' => '供应商资料'];
$this->params['breadcrumbs'][] = ['label' => '供应商列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '供应商详情'];
?>
<div class="content">
    <h1 class="head-first">
        供应商详情
    </h1>
    <div class="space-10"></div>
    <div class="mb-30">
        <div class="mb-10">
            <label class="width-150">Commodity</label>
            <span class="width-300"><?= $model->supplier_category_id ?></span>
            <label class="width-150 ">新增类型</label>
            <span class="width-300"><?= $model->supplierAddType ?></span>
        </div>
        <div class="mb-10">
            <label class="width-150">供应商全称</label>
            <span class="width-300"><?= $model->supplier_sname ?></span>
            <label class="width-150">供应商简称</label>
            <span class="width-300"><?= $model->supplier_sname ?></span>
        </div>
        <div class="mb-10">
            <label class="width-150 ">供应商集团简称</label>
            <span class="width-300"><?= $model->supplier_group_sname ?></span>
            <label class="width-150 ">品牌</label>
            <span class="width-300"><?= $model->supplier_brand ?></span>
        </div>
        <div class="mb-10">
            <label class="width-150 ">供应商地址</label>
            <span class="width-300"><?= $model->supplier_compaddress ?></span>
            <label class="width-150">主营范围</label>
            <span class="width-100"><?= $model->supplier_main_product ?></span>
            <label class="width-100 ">地位</label>
            <span class="width-100"><?= $model->supplierPosition ?></span>

        </div>
        <div class="mb-10">
            <label class="width-150">年度营业额(USD)</label>
            <span class="width-300"><?= $model->supplier_annual_turnover ?></span>
            <label class="width-150 ">交易币别</label>
            <span class="width-300"><?= $model->traceCurrency ?></span>

        </div>
        <div class="mb-10">
            <label class="width-150">交货条件</label>
            <span class="width-300"><?= $model->devcon ?></span>
            <label class="width-150">付款条件</label>
            <span class="width-300"><?= $model->payCondition ?></span>

        </div>
        <div class="mb-10">
            <label class="width-150 ">供应商类型</label>
            <span class="width-300"><?= $model->supplierType ?></span>
            <label class="width-150 ">供应商来源</label>
            <span class="width-300"><?= $model->supplierSource ?></span>
        </div>
        <div class="mb-10">
            <label class="width-120 no-after">拟采购商品</label>
            <table class="ml-50 text-center">
                <tr class="height-30">
                    <th class="width-140">序号</th>
                    <th class="width-140">商品料号</th>
                    <th class="width-140">商品名称</th>
                    <th class="width-140">规格型号</th>
                    <th class="width-140">商品类型</th>
                    <th class="width-140">单位</th>
                </tr>
                <tbody>
<!--                --><?// dumpE($model->material->materialList)?>
                <?php foreach ($model->material as $key=>$val){ ?>
                    <tr class="height-30">
                        <td><?= $key+=1 ?></td>
                        <td><?= $val->material_code ?></td>
                        <td><?= $val->pro_name ?></td>
                        <td><?= $val->pro_size ?></td>
                        <td><?= $val->pro_level ?></td>
                        <td><?= $val->pro_sku ?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        <div class="mb-10">
            <label class="width-150 ">预计年销售额</label>
            <span class="width-200"><?= $model->supplier_pre_annual_sales ?></span>
            <label class="width-130 ">预计年销售利润(USD)</label>
            <span class="width-150"><?= $model->supplier_pre_annual_profit ?></span>
            <label class="width-100 ">来源类别</label>
            <span class="width-200"><?= $model->sourceType ?></span>
        </div>
        <div class="mb-10">
            <label class="width-150">外部目标客户</label>
            <span class="width-750 text-top"><?= $model->outer_cus_object ?></span>
        </div>
        <div class="mb-10">
            <label class="width-150 ">客户品质等级要求(USD)</label>
            <span class="width-750 text-top"><?= $model->cus_quality_require ?></span>
        </div>
        <div class="mt-20 ml-30">
            <div class="easyui-tabs" style="width:900px;">
                <div title="供应商主营商品" style="padding:10px">
                    <table class="table-small" style="width:880px;">
                        <thead>
                        <tr>
                            <th>
                                主营商品
                            </th>
                            <th>
                                商品优势与不足
                            </th>
                            <th>
                                销售渠道与区域
                            </th>
                            <th>
                                年销售量
                            </th>
                            <th>
                                市场份额
                            </th>
                            <th>
                                是否公开销售(Y/N)
                            </th>
                            <th>
                                是否代理(Y/N)
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($mainList as $key=>$val){?>
                            <tr>
                                <td><?= $val->vmainl_product ?></td>
                                <td><?= $val->vmainl_superiority ?></td>
                                <td><?= $val->vmainl_salesway ?></td>
                                <td><?= $val->vmainl_yqty ?></td>
                                <td><?= $val->vmainl_marketshare ?></td>
                                <td><?= $val->vmainl_isopensale ?></td>
                                <td><?= $val->vmainl_isagent ?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
                <div title="供应商联系信息" style="padding:10px">
                    <table class="table-small" style="width:880px;">
                        <thead>
                        <tr>
                            <th>
                                姓名
                            </th>
                            <th>
                                性别
                            </th>
                            <th>
                                职务
                            </th>
                            <th>
                                电话
                            </th>
                            <th>
                                手机
                            </th>
                            <th>
                                邮箱
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($persionList as $key=>$val){?>
                            <tr>
                                <td><?= $val->vcper_name ?></td>
                                <td><?= $val->vcper_sex ?></td>
                                <td><?= $val->vcper_post ?></td>
                                <td><?= $val->vcper_tel ?></td>
                                <td><?= $val->vcper_mobile ?></td>
                                <td><?= $val->vcper_mail ?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mb-10">
            <h2 class="head-second">
                代理事项
            </h2>

            <div class="mb-10">
                <label class="width-150">是否取得代理授权</label>
                <span class="width-150"><?= $model->supplier_is_agents==1?"Y":"N" ?></span>
                <label class="width-150">授权期限</label>
                <span class="width-150"><?= $model->supplier_authorize_bdate ?>~<?= $model->supplier_authorize_edate ?></span>
                <label class="width-150 ">代理等级</label>
                <span class="width-150"><?= $model->agentsLevel ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150">授权商品类别</label>
                <span class="width-150"><?= $model->transactType ?></span>
                <label class="width-150">授权区域</label>
                <span class="width-150"><?= $model->authorizeArea ?></span>
                <label class="width-150 ">销售范围</label>
                <span class="width-150"><?= $model->supplierSalarea ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150">供应商主谈人</label>
                <span class="width-150"><?= $model->supplier_chief_negotiator ?></span>
                <label class="width-150">富金机主谈人</label>
                <span class="width-300"><?= $model->fjj_chief_negotiator ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150">供应商主谈人职务</label>
                <span class="width-150"><?= $model->supplier_chief_post ?></span>
                <label class="width-150">富金机主谈人分机</label>
                <span class="width-300"><?= $model->fjj_chief_extension ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150">新增需求说明</label>
                <span class="width-750 text-top"><?= $model->requirement_description ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150 ">优势</label>
                <span class="width-750 text-top"><?= $model->supplier_advantage ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150 ">商机</label>
                <span class="width-750 text-top"><?= $model->supplier_business ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150 ">未取得受理原因</label>
                <span class="width-750 text-top"><?= $model->supplier_not_accepted ?></span>
            </div>
        </div>
        <div class="space-40 "></div>
        <div class="mb-10 text-center">
            <button type="button" class="button-blue ml-350"  onClick="javascript :history.back(-1);">
                返回
            </button>
        </div>
    </div>
</div>

<script>
    $(function () {
//        $(".the-obj").bind('input propertychange', function() {
//            //进行相关操作
//            console.log($(".the-obj").val());
//            test11($(".the-obj"),$(".this-obj"));
//        });
    })
</script>