<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCreditApply */
$this->title = '账信申请详情';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '账信申请列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
$ftp = new \app\widgets\upload\Ftp();
?>
<style>
    .width-80 {
        width: 80px;
    }
    .width-100 {
        width: 100px;
    }
    .width-150 {
        width: 150px;
    }
    .mt-10 {
        margin-top: 10px;
    }
    .mt-20 {
        margin-top: 20px;
    }
    .pb-10{
        padding-bottom:10px;
    }
    .credit-content th{
        height:30px;
        font-weight: 100;
        color:#333333;
        font-size:12px;
        background:#d9f0ff;
    }
    /*.img-border{*/
        /*width:300px;*/
        /*height:240px;*/
        /*margin:10px;*/
    /*}*/
    .img{
        width:300px;
        height:200px;
    }
    .img-title{
        height:30px;
    }
    .img-title a{
        height:30px;
        display: block;
        text-align: center;
        font-size:14px;
        line-height:40px;
    }
    .credit-content tr td p{
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        display: inline-block;
    }
</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
        <span class="head-code">申请单号：<?= !empty($model['credit_code']) ? $model['credit_code'] : ''?></span>
    </h1>
    <div class="border-bottom mb-10  pb-10">
        <?php if($model['credit_status'] == 11 || $model['credit_status'] == 14){ ?>
            <?= Menu::isAction('/crm/crm-credit-apply/update')?Html::button('修改', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $model['l_credit_id'] . '\'']):'' ?>
            <?= (Menu::isAction('/system/verify-record/reviewer') && $model['credit_status']=14 )?Html::button('送审', ['class' => 'button-blue width-80','id' => 'check']):'' ?>
        <?php } ?>

        <?= Html::button('切换列表', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <?= Html::button('打印', ['class' => 'button-blue width-80', 'onclick'=>'btnPrints()']) ?>
    </div>
    <div class="mb-30 create_content">
        <h2 class="head-second mt-20">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">客户信息</a>
        </h2>
        <div>
            <table width="100%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">客户代码<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['apply_code'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">客户全称<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_sname'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">客户简称<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_shortname'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">税籍编码<label>：</label></td>
                    <td class="no-border vertical-center" width="30%"><?= $model['cust_tax_code'] ?></td>

                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">公司电话<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_tel1'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">客户经理人<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['manager']['staff_name'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">交易法人<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['company_name'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">营销区域<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['csarea']['csarea_name'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">客户类型<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_type']['bsp_svalue'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">客户等级<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_level']['bsp_svalue'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">主要联系人<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_contacts'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">联系电话<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_tel2'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
            </table>
        </div>
        <h2 class="head-second">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">信用额度申请</a>
        </h2>
        <div>
            <table width="100%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">申请账信类型<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['creditType'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">起算日<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['initialDay'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">付款方式<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['paymentMethod'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">付款条件<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['paymentType'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">付款日<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['payDay'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">预估月交易额<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= !empty($model['volume_trade'])?'￥'.$model['volume_trade']:''; ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
                <?php if(!empty($model['limit'])){ ?>
                    <?php foreach ($model['limit'] as $kl=>$vl){ ?>
                        <tr class="no-border mb-10">
                            <td class="no-border vertical-center label-align" width="12%"><?= $vl['creditType'] ?>申请额度<label>：</label></td>
                            <td class="no-border vertical-center" width="20%"><?= !empty($vl['credit_limit'])?'￥'.$vl['credit_limit']:'' ?></td>
                            <td class="no-border vertical-center label-align" width="12%"><?= $vl['creditType'] ?>授予额度<label>：</label></td>
                            <td class="no-border vertical-center" width="20%"><?= !empty($vl['approval_limit'])?'￥'.$vl['approval_limit']:'' ?></td>
                            <td class="no-border vertical-center label-align" width="12%">有效期<label>：</label></td>
                            <td class="no-border vertical-center" width="20%"><?= $vl['validity_date'] ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">备注<label>：</label></td>
                    <td class="no-border vertical-center" width="20%" colspan="4"><?= $model['apply_remark'] ?></td>
                </tr>
            </table>
        </div>
        <h2 class="head-second mt-20">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">客户信用相关信息</a>
        </h2>
        <div>
            <table width="100%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">注册资金<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_regfunds'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">注册货币<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['regcurr'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">实收资本<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['official_receipts'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">主营项目<label>：</label></td>
                    <td class="no-border vertical-center" width="20%" colspan="4"><?= $model['member_businessarea'] ?></td>
                </tr>
            </table>
            <table class="credit-table mt-10">
                <tbody class="credit-content">
                <tr>
                    <th class="width-100" rowspan="2">母公司</th>
                    <th>公司名称</th>
                    <th colspan="2">投资总额(RMB)</th>
                    <th colspan="2">持股比率</th>
                    <th></th>
                </tr>
                <tr>
                    <td><p class="text-center width-150"><?= $model['cust_parentcomp'] ?></p></td>
                    <td colspan="2"><p class="text-center width-150"><?= !empty($model['total_investment'])?bcsub($model['total_investment'],0,2):'' ?></p></td>
                    <td colspan="2"><p class="text-center width-150"><?= !empty($model['shareholding_ratio']) ? bcsub($model['shareholding_ratio'],0,2).'%':'' ?></p></td>
                    <td></td>
                </tr>
                <tr>
                    <th class="width-100" rowspan="<?= count($model['contact'])+1 ?>">客户联系方式</th>
                    <th>姓名</th>
                    <th colspan="2">电子邮箱</th>
                    <th colspan="2">电话(手机)</th>
                    <th colspan="2">其他</th>
                </tr>
                <?php if(!empty($model['contact'])){ ?>
                    <?php foreach ($model['contact'] as $val){ ?>
                        <tr>
                            <td><p class="text-center width-150"><?= $val['ccper_name'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['ccper_mail'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['ccper_mobile'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['ccper_remark'] ?></p></td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="width-100" rowspan="<?= count($model['turnover'])==0?2:count($model['turnover'])+1; ?>">营业额</th>
                    <th>币别</th>
                    <th colspan="2"><?= date('Y',time())-1 ?></th>
                    <th colspan="2"><?= date('Y',time())-2 ?></th>
                    <th colspan="2"><?= date('Y',time())-3 ?></th>
                </tr>
                <?php if(!empty($model['turnover'])){ ?>
                    <?php foreach ($model['turnover'] as $key => $val){ ?>
                        <tr>
                            <td><?= $model['t_currency']['bsp_svalue'] ?></td>
                            <td colspan="2"><?= !empty($val[date('Y',time())-1]) ? bcsub($val[date('Y',time())-1],0,2):'' ?></td>
                            <td colspan="2"><?= !empty($val[date('Y',time())-2]) ? bcsub($val[date('Y',time())-2],0,2):'' ?></td>
                            <td colspan="2"><?= !empty($val[date('Y',time())-3]) ? bcsub($val[date('Y',time())-3],0,2):'' ?></td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="width-100" rowspan="<?= count($model['linkcomp'])+1 ?>">子公司及关联公司</th>
                    <th>公司名称</th>
                    <th colspan="2">关联性质</th>
                    <th colspan="2">投资金额</th>
                    <th colspan="2">持股比例(%)</th>
                </tr>
                <?php if(!empty($model['linkcomp'])){ ?>
                    <?php foreach ($model['linkcomp'] as $val){ ?>
                        <tr>
                            <td><p class="text-center width-150"><?= $val['linc_name'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['relational_character'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= !empty($val['total_investment']) ? bcsub($val['total_investment'],0,2):'' ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= !empty($val['shareholding_ratio'])?bcsub($val['shareholding_ratio'],0,2):'' ?></p></td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="width-100" rowspan="<?= count($model['custCustomer'])+1 ?>">主要客户</th>
                    <th>公司名称</th>
                    <th colspan="2">占营收比率(%)</th>
                    <th colspan="2">电话</th>
                    <th colspan="2">备注</th>
                </tr>
                <?php if(!empty($model['custCustomer'])){ ?>
                    <?php foreach ($model['custCustomer'] as $val){ ?>
                        <tr>
                            <td><p class="text-center width-150"><?= $val['cc_customer_name'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= !empty($val['cc_customer_ratio'])?bcsub($val['cc_customer_ratio'],0,2):'' ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['cc_customer_tel'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['cc_customer_remark'] ?></p></td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="width-100" rowspan="<?= count($model['supplier'])+1 ?>">主要供应商</th>
                    <th>公司名称</th>
                    <th colspan="2">付款条件</th>
                    <th colspan="2">电话</th>
                    <th colspan="2">备注</th>
                </tr>
                <?php if(!empty($model['supplier'])){ ?>
                    <?php foreach ($model['supplier'] as $val){ ?>
                        <tr>
                            <td><p class="text-center width-150"><?= $val['cc_customer_name'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['caluse'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['cc_customer_tel'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['cc_customer_remark'] ?></p></td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="width-100" rowspan="<?= count($model['bank'])+1 ?>">主要往来银行</th>
                    <th>银行名称</th>
                    <th colspan="2">账号</th>
                    <th colspan="2">往来项目</th>
                    <th colspan="2">备注</th>
                </tr>
                <?php if(!empty($model['supplier'])){ ?>
                    <?php foreach ($model['bank'] as $val){ ?>
                        <tr>
                            <td><p class="text-center width-150"><?= $val['bank_name'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['account_num'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['curremt_project'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['remark'] ?></p></td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <h2 class="head-second mt-20">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">公司三证信息</a>
        </h2>
        <div class="ftp">
            <?php if($crmcertf['crtf_type']==0){ ?>
                <?php if(!empty($crmcertf['bs_license'])){ ?>
                    <div class="inline-block img-border">
                        <img class="img" src="<?= Yii::$app->ftpPath['httpIP'] .'/cmp/bslcns/'.$newnName1.'/'. $crmcertf['bs_license'] ?>" alt="公司营业执照">
                        <div class="img-title">
                            <a target="_blank" href="<?=  Yii::$app->ftpPath['httpIP'] ?>/cmp/bslcns/<?= $newnName1 ?>/<?= $crmcertf['bs_license'] ?>">公司营业执照</a>
                        </div>
                    </div>
                <?php }?>
                <?php if(!empty($crmcertf['tx_reg'])){ ?>
                    <div class="inline-block img-border">
                        <img class="img" src="<?= Yii::$app->ftpPath['httpIP'] .'/cmp/txrg/'.$newnName2.'/'. $crmcertf['tx_reg'] ?>" alt="税务登记证">
                        <div class="img-title">
                            <a target="_blank" href="<?=  Yii::$app->ftpPath['httpIP'] ?>/cmp/txrg/<?= $newnName2 ?>/<?= $crmcertf['tx_reg'] ?>">税务登记证</a>
                        </div>
                    </div>
                <?php }?>
                <?php if(!empty($crmcertf['qlf_certf'])){ ?>
                    <div class="inline-block img-border">
                        <img class="img" src="<?= Yii::$app->ftpPath['httpIP'] .'/cmp/txqlf/'.$newnName3.'/'. $crmcertf['qlf_certf'] ?>" alt="一般纳税人资格证">
                        <div class="img-title">
                            <a target="_blank" href="<?=  Yii::$app->ftpPath['httpIP'] ?>/cmp/txqlf/<?= $newnName3 ?>/<?= $crmcertf['qlf_certf'] ?>">一般纳税人资格证</a>
                        </div>
                    </div>
                <?php }?>
            <?php }else{ ?>
                <div class="crmcertf">
                    <?php if(!empty($crmcertf['bs_license'])){ ?>
                        <div class="inline-block img-border">
                            <img class="img" src="<?= Yii::$app->ftpPath['httpIP'] .'/cmp/bslcns/'.$newnName1.'/'. $crmcertf['bs_license'] ?>" alt="公司三证合一">
                            <div class="img-title">
                                <a target="_blank" href="<?=  \Yii::$app->ftpPath['httpIP'] ?>/cmp/bslcns/<?= $newnName1 ?>/<?= $crmcertf['bs_license'] ?>">公司三证合一</a>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(!empty($crmcertf['qlf_certf'])){ ?>
                        <div class="inline-block img-border">
                            <img class="img" src="<?= Yii::$app->ftpPath['httpIP'] .'/cmp/txqlf/'.$newnName3.'/'. $crmcertf['qlf_certf'] ?>" alt="一般纳税人资格证">
                            <div class="img-title">
                                <a target="_blank" href="<?=  Yii::$app->ftpPath['httpIP'] ?>/cmp/txqlf/<?= $newnName3 ?>/<?= $crmcertf['qlf_certf'] ?>">一般纳税人资格证</a>
                            </div>
                        </div>
                    <?php }?>
                </div>
            <?php } ?>
            <h2 class="head-three">
                <span style="color:#1f7ed0;">附件</span>
            </h2>
            <div class="mb-10">
                <label class="width-100 label-align">客户信息签字档：</label>
                <?php if(!empty($model['file1'])){ ?>
                    <?php foreach ($model['file1'] as $key7 => $val7) { ?>
                        <span class="value-align"><a target="_blank" href="<?=  Yii::$app->ftpPath['httpIP'] ?>/cca/credit/<?= $val7['date_file'] ?>/<?= $val7['file_new'] ?>"><?= $val7['file_old'] ?></a></span>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="mb-10">
                <label class="width-100 label-align">附件：</label>
                <?php if(!empty($model['file2'])){ ?>
                    <?php foreach ($model['file2'] as $key8 => $val8) { ?>
                        <span class="value-align"><a target="_blank" href="<?=  Yii::$app->ftpPath['httpIP'] ?>/cca/credit/<?= $val8['date_file'] ?>/<?= $val8['file_new'] ?>"><?= $val8['file_old'] ?></a></span>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php if(!empty($verify)){ ?>
            <div class="credit_type">
                <div class="mb-30">
                    <h2 class="head-second">
                        <i class="icon-caret-down"></i>
                        <a href="javascript:void(0)">审核路径</a>
                    </h2>
                    <table class="product-list" style="width:990px;">
                        <thead>
                        <tr>
                            <th class="width-60">序号</th>
                            <th>签核节点</th>
                            <th>签核人员</th>
                            <th>签核日期</th>
                            <th>操作</th>
                            <th>签核意见</th>
                            <th>签核人IP</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($verify as $key=>$val){ ?>
                            <tr>
                                <th><?= $key+1 ?></th>
                                <th><?= $val['verifyOrg'] ?></th>
                                <th><?= $val['verifyName'] ?></th>
                                <th><?= $val['vcoc_datetime'] ?></th>
                                <th><?= $val['verifyStatus'] ?></th>
                                <th><?= $val['vcoc_remark'] ?></th>
                                <th><?= $val['vcoc_computeip'] ?></th>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>

    </div>
</div>
<script>
    $(function(){
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-right");
            $(this).prev().toggleClass("icon-caret-down");
        });
        var isApply = "<?= $isApply ?>";
        var id = '<?= $id ?>';
        var type='<?= $model["credit_type"] ?>';
        if (isApply == 1) {
            var url="<?=Url::to(['view'],true)?>?id="+id;
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        }
        $('#check').click(function(){
            var url = "<?=Url::to(['view'])?>"+"?id=" + id;
//            $.fancybox({
//                href:"<?//=Url::to(['/system/verify-record/reviewer'])?>//?type="+type+"&id="+id+"&url="+url,
//                type:"iframe",
//                padding:0,
//                autoSize:false,
//                width:750,
//                height:480
//            });
            $.ajax({
                type:"get",
                dataType:"json",
                async:false,
                data:{"id":id},
                url:"<?= Url::to(['verify']) ?>",
                success:function(msg){
                    if(msg == false){
                        layer.alert("送审失败!",{icon:2});return false;
                    }
                    $.fancybox({
                        href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                        type:"iframe",
                        padding:0,
                        autoSize:false,
                        width:750,
                        height:480
                    });
                }
            })
        })
        /*删除*/
        $('#delete').click(function(){
            var id = '<?= $model["aid"] ?>';
            layer.confirm("确定要删除这条申请吗?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"id":id},
                        url: "<?=Url::to(['delete'])?>",
                        success: function (msg) {
                            if( msg.flag === 1){
                                layer.alert(msg.msg,{icon:1,end:function(){window.location.href= '<?=Url::to(["index"]) ?>'}});
                            }else{
                                layer.alert(msg.msg,{icon:2})
                            }
                        },
                        error :function(msg){
                            layer.alert(msg.msg,{icon:2})
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
        })
    });

    /*表格打印*/
    function btnPrints(){
        $('.create_content').jqprint({
            debug: false,
            importCSS: true,
            printContainer: true,
            operaSupport: false
        })
    }
</script>