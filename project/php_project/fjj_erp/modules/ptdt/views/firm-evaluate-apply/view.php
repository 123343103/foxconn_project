<?php
/**
 * User: F1677929
 * Date: 2016/11/9
 */
/* @var $applyModel app\modules\ptdt\models\PdFirmEvaluateApply */
use yii\helpers\Url;
use app\modules\ptdt\models\PdFirmEvaluateApply;
$this->params['homeLike'] = ['label'=>'供应商管理','url' => Url::to([''])];
$this->params['breadcrumbs'][] = ['label' => '厂商评鉴申请列表', 'url' => Url::to([''])];
$this->params['breadcrumbs'][] = ['label' => '查看厂商评鉴申请', 'url' => Url::to([''])];
?>
<div class="content">
    <h1 class="head-first">评鉴申请查看</h1>
    <div class="mb-20">
        <label class="width-100">部门</label>
        <span class="width-200"><?= $applyModel->staff->organization_code ?></span>
        <label class="width-100">课别</label>
        <span class="width-200"><?= $applyModel->staff->job_task ?></span>
        <label class="width-100">申请人</label>
        <span class="width-200"><?= $applyModel->staff->staff_name ?></span>
    </div>
    <div class="mb-20">
        <label class="width-100">分机</label>
        <span class="width-200"><?= $applyModel->staff->staff_tel ?></span>
        <label class="width-100">E-mail</label>
        <span class="width-200"><?= $applyModel->staff->staff_email ?></span>
        <label class="width-100">申请日期</label>
        <span class="width-200"><?= $applyModel->apply_date ?></span>
    </div>
    <div class="mb-20">
        <label class="width-100">厂商全称</label>
        <span class="width-200"><?= $applyModel->firm->firm_sname ?></span>
        <label class="width-100">简称</label>
        <span class="width-200"><?= $applyModel->firm->firm_shortname ?></span>
        <label class="width-100">品牌</label>
        <span class="width-200"><?= $applyModel->firm->firm_brand ?></span>
    </div>
    <div class="mb-20">
        <label class="width-100">厂商地址</label>
        <span class="width-800"><?= $applyModel->firm->firm_compaddress ?></span>
    </div>
    <div class="mb-20">
        <label class="width-100">厂商负责人</label>
        <span class="width-200"><?= $applyModel->firm->firm_compprincipal ?></span>
        <label class="width-100">联系电话</label>
        <span class="width-200"><?= $applyModel->firm->firm_comptel ?></span>
        <label class="width-100">邮箱</label>
        <span class="width-200"><?= $applyModel->firm->firm_compmail ?></span>
    </div>
    <div class="mb-20">
        <label class="width-100">厂商联络人</label>
        <span class="width-200"><?= $applyModel->firm->firm_contaperson ?></span>
        <label class="width-100">联系电话</label>
        <span class="width-200"><?= $applyModel->firm->firm_contaperson_tel ?></span>
        <label class="width-100">邮箱</label>
        <span class="width-200"><?= $applyModel->firm->firm_contaperson_mail ?></span>
    </div>
    <div class="mb-20">
        <label class="width-100">厂商类别</label>
        <span class="width-200"><?= $applyModel->firm->firmType->bsp_svalue ?></span>
        <label class="width-100">产品名称</label>
        <span class="width-200"><?php $productName = ''; foreach ($applyModel->firmReportProduct as $val) {$productName .= $val->product_name . ',';} echo rtrim($productName, ','); ?></span>
        <label class="width-100">商品定位</label>
        <span class="width-200"><?= isset($applyModel->goodsPosition) ? $applyModel->goodsPosition->bsp_svalue : '' ?></span>
    </div>
    <?php if ($applyModel->apply_type == PdFirmEvaluateApply::EVALUATE_APPLY) { ?>
        <div class="mb-20">
            <label class="width-100">服务客户</label>
            <span class="width-200"><?= $applyModel->server_customer ?></span>
            <label class="width-100">预评鉴日期</label>
            <span class="width-200"><?= $applyModel->predict_evaluate_date ?></span>
        </div>
    <?php } ?>
    <?php if ($applyModel->apply_type == PdFirmEvaluateApply::AVOID_EVALUATE_APPLY) { ?>
        <div class="mb-20">
            <label class="width-100">部门主管</label>
            <span class="width-200"><?= $applyModel->department_manager ?></span>
            <label class="width-100">交易商品</label>
            <span class="width-200"><?= $applyModel->trade_goods ?></span>
        </div>
        <div class="mb-20">
            <label class="width-100">免评鉴条件</label>
            <span class="width-800">
                <?php
                    if ($applyModel->avoid_evaluate_condition) {
                        foreach (unserialize($applyModel->avoid_evaluate_condition) as $val) {
                            switch ($val) {
                                case PdFirmEvaluateApply::FAMOUS_FIRM :
                                    echo "<span class='mr-20'>国际性知名厂商</span>";
                                    break;
                                case PdFirmEvaluateApply::MIGHTY_FIRM :
                                    echo "<span class='mr-20'>具有垄断地位之强势厂商</span>";
                                    break;
                                case PdFirmEvaluateApply::BRAND_AGENT_FIRM :
                                    echo "<span class='mr-20'>知名品牌代理商/经销商</span>";
                                    break;
                                case PdFirmEvaluateApply::OTHER_FIRM :
                                    echo "<span class='mr-20'>其它</span>";
                                    break;
                                default :
                                    echo "";
                            }
                        }
                    }
                ?>
            </span>
        </div>
        <div class="mb-20">
            <label class="width-100">免评鉴条件附件</label>
            <?php
                if ($applyModel->condition_annex && $applyModel->condition_annex_name) {
                    $annex = unserialize($applyModel->condition_annex);
                    $annexName = unserialize($applyModel->condition_annex_name);
                    $result = array_combine($annex,$annexName);
                    foreach ($result as $key=>$val) {
                        echo "<a class='mr-20' href=" . $key . ">" . $val . "</a>";
                    }
                }
            ?>
        </div>
    <?php } ?>
    <div class="mb-20">
        <label class="width-100">理由/原因</label>
        <?= $applyModel->apply_reason ?>
    </div>
    <div class="mb-40">
        <label class="width-100">附件</label>
        <?php
            if ($applyModel->evaluate_annex && $applyModel->evaluate_annex_name) {
                $annex = unserialize($applyModel->evaluate_annex);
                $annexName = unserialize($applyModel->evaluate_annex_name);
                $result = array_combine($annex,$annexName);
                foreach ($result as $key=>$val) {
                    echo "<a class='mr-20' href=" . $key . ">" . $val . "</a>";
                }
            }
        ?>
    </div>
    <div class="text-center">
        <button class="button-white-big" onclick="window.location.href = '<?= Url::to(["index"])?>'">返回</button>
    </div>
</div>
