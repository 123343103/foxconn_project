<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;
$this->title = '客户详情';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '客户列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '客戶详情'];
$regname = unserialize($model['cust_regname']);
$regnumber = unserialize($model['cust_regnumber']);
?>
<style>
    .label-width{
        width:120px;
    }
    .value-width{
        width: 200px;
    }
</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
        <span class="head-code">客户编号：<?= $model['cust_filernumber'] ?></span>
    </h1>
    <div class="border-bottom mb-10">
<!--        Menu::isAction('/crm/crm-customer-info/update')&&(($model['manager_id'] == $loginId || $model['personinch_status'] == 0 || $isSuper == 1) && $model['apply_status'] != 30)?Html::button('修改', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $model['cust_id'] . '\'']):''
Menu::isAction('/crm/crm-customer-info/delete')&&(($model['manager_id'] == $loginId || $model['personinch_status'] == 0 || $isSuper == 1) && $model['apply_status'] != 30)?Html::button('删除', ['class' => 'button-blue width-80','id' => 'delete']):''
-->
        <?php if((($model['apply_status'] != 20 && $model['apply_status'] != 30))){ ?>
            <?php if($model['apply_status'] == 40){ ?>
                <?= Menu::isAction('/crm/crm-customer-info/update')?Html::button('修改', ['class' => 'button-blue update width-80', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $model['cust_id'] . '&type=1' . '\'']):'' ?>
            <?php }else{ ?>
                <?= Menu::isAction('/crm/crm-customer-info/update')?Html::button('修改', ['class' => 'button-blue update width-80', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $model['cust_id'] . '\'']):'' ?>
            <?php } ?>
        <?php } ?>


        <?= Html::button('切换列表', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <?php if(!$model["cust_code"] && $model["apply_status"]!=20 && $model["apply_status"]!=30 && $model['saleStatus']=="10"){ ?>
            <?= Menu::isAction('/crm/crm-customer-info/customer-info')?Html::button('申请客户代码', ['style'=>'width:100px;','class' => 'button-blue apple_code', 'onclick' => 'window.location.href=\'' . Url::to(["/crm/crm-customer-info/customer-info"]) . '?id=' . $model['cust_id'] . '&status=10\'']):'' ?>
        <?php } ?>
        <?= Menu::isAction('/crm/crm-customer-info/person-inch') && ($model['apply_status'] != 20 && $model['apply_status'] != 30 && ($u || $isSuper) && $model['saleStatus']=="10" ) ?Html::button('认领', ['class' => 'button-blue width-80','id' => 'inch']):'' ?>
        <?= Menu::isAction('/crm/crm-customer-info/credit-create') && (($model['apply_status'] == '40' && $model['saleStatus']=="10" &&($model['credit_apply_status']=='10'||$model['credit_apply_status']==' '||$model['credit_apply_status']==null)))?Html::button('账信申请', ['style'=>'width:100px;','class' => 'button-blue','id'=>'credit_apply']):'' ?>
        <div class="space-10"></div>
    </div>
    <div class="space-10"></div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">客户基本信息</a>
    </h2>
    <div class="mb-10">
        <table width="90%" class="no-border vertical-center mb-10" style="border-collapse:separate; border-spacing:5px;">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户全称<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_sname'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">客户简称<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_shortname'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">客户代码<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['apply_code'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">公司电话<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_tel1'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">传真<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_fax'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">客户类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['custType'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户来源<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['custSource'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">所在地区<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['area'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">营销区域<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['saleArea'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">联系人<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_contacts'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">联系电话<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_tel2'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">邮箱<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_email'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户经理人<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['manager_code']?$model['manager_code']:""; ?></td>
                <td class="no-border vertical-center label-align" width="10%">详细地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3" width="20%"><?= $model['district'][0]['district_name'] . $model['district'][1]['district_name'] . $model['district'][2]['district_name'] . $model['district'][3]['district_name'] . $model['cust_adress'] ?></td>

            </tr>
        </table>
    </div>

    <div class="space-10"></div>

    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">客户详细信息</a>
    </h2>
    <div>
        <table width="90%" class="no-border vertical-center mb-10" style="border-collapse:separate; border-spacing:5px;">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">公司属性<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['compvirtue'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">公司规模<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['compscale'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">行业类别<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['industryType'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户等级<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['custLevel'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">经营类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['businessType'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">员工人数<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_personqty'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">注册时间<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_regdate'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">注册资金<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= empty($model['cust_regfunds']) ? '' : $model['cust_regfunds'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">注册货币<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['dealCurrency'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">是否上市公司<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_islisted']==1?"是":"否"; ?></td>
                <td class="no-border vertical-center label-align" width="10%">是否公司会员<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_ismember'] == 1?"是":"否" ?></td>
                <td class="no-border vertical-center label-align" width="10%">会员类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['memberType'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">潜在需求<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['latDemand'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">需求类目<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['category_name'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">旺季分布<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_bigseason'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">交易币别<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['bsPubdata']['memberCurr'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">年营业额<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= !empty($model['member_compsum'])?$model['member_compsum']:'' ?></td>
                <td class="no-border vertical-center label-align" width="10%">公司主页<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['member_compwebside'] ?></td>
            </tr>

            <?php foreach ($regname as $key => $val) { ?>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="10%">登记证名称<?php echo $key + 1; ?><label>：</label></td>
                    <td class="no-border vertical-center value-align" width="20%"><?= $val ?></td>
                    <td class="no-border vertical-center label-align" width="10%">登记证号码<?php echo $key + 1; ?><label>：</label></td>
                    <td class="no-border vertical-center value-align" colspan="3" width="20%"><?= $regnumber[$key] ?></td>
                </tr>
            <?php } ?>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">申请发票类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['invoiceType'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">具备开票类型<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3" width="20%"><?= $model['invoType'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">发票抬头<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5" width="20%"><?= $model['invoice_title'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">发票抬头地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5" width="20%"><?= $model['invoiceTitleDistrict'][0]['district_name'] . $model['invoiceTitleDistrict'][1]['district_name'] . $model['invoiceTitleDistrict'][2]['district_name'] . $model['invoiceTitleDistrict'][3]['district_name'] . $model['invoice_title_address'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">发票寄送地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5" width="20%"><?= $model['invoiceMailDistrict'][0]['district_name'] . $model['invoiceMailDistrict'][1]['district_name'] . $model['invoiceMailDistrict'][2]['district_name'] . $model['invoiceMailDistrict'][3]['district_name'] . $model['invoice_mail_address'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">经营范围<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5" width="20%"><?= $model['member_businessarea'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">总公司<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_parentcomp'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">公司负责人<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3" width="20%"><?= $model['cust_inchargeperson'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">总公司地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5" width="20%"><?= $model['districtCompany'][0]['district_name'] . $model['districtCompany'][1]['district_name'] . $model['districtCompany'][2]['district_name'] . $model['districtCompany'][3]['district_name'] . $model['cust_headquarters_address'] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">认证信息</a>
    </h2>
    <div>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="14%" class="no-border vertical-center label-align">是否供应商：</td>
                <td width="22%" class="no-border vertical-center value-align"><?=$crmcertf['yn_spp']?"是":"否"?></td>
                <td width="14%" class="no-border vertical-center label-align"><?=$crmcertf['yn_spp']?"供应商代码：":""?></td>
                <td width="52%" class="no-border vertical-center value-align"><?=$crmcertf['yn_spp']?$crmcertf['spp_no']:""?></td>
            </tr>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="14%" class="no-border vertical-center label-align">证件类型：</td>
                <td width="22%" class="no-border vertical-center value-align">
                    <?php
                    switch($crmcertf['crtf_type']){
                        case 0:
                            echo "旧版三证";
                            break;
                        case 1:
                            echo "新版三证合一";
                            break;
                        default:
                            break;
                    }
                    ?>
                </td>
                <td width="66%" class="no-border vertical-center value-align"><span style="margin-left: 48px;color: #989898">证件格式：JPG、PNG、TIF，PDF、BMP、压缩档7z、rar、zip等文件小于2M。</span></td>
            </tr>
            <td class="no-border vertical-center label-align" width="10%">税籍编码/统一社会信用代码<label>：</label></td>
            <td class="no-border vertical-center value-align" colspan="3" width="20%"><?= $model['cust_tax_code'] ?></td>
        </table>


        <?php if($crmcertf['crtf_type']==0){ ?>
            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td width="14%" class="no-border vertical-center label-align">公司营业执照：</td>
                    <td width="85%" class="no-border vertical-center value-align"><a href="<?=  \Yii::$app->ftpPath['httpIP'] ?>/cmp/bslcns/<?= $newnName1 ?>/<?= $crmcertf['bs_license'] ?>"><?= $crmcertf['o_license'] ?></a></td>
                </tr>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td width="14%" class="no-border vertical-center label-align">税务登记证：</td>
                    <td width="85%" class="no-border vertical-center value-align"><a href="<?=  Yii::$app->ftpPath['httpIP'] ?>/cmp/txrg/<?= $newnName2 ?>/<?= $crmcertf['tx_reg'] ?>"><?= $crmcertf['o_reg'] ?></a></td>
                </tr>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td width="14%" class="no-border vertical-center label-align">一般纳税人资格证：</td>
                    <td width="85%" class="no-border vertical-center value-align"><a href="<?=  Yii::$app->ftpPath['httpIP'] ?>/cmp/txqlf/<?= $newnName3 ?>/<?= $crmcertf['qlf_certf'] ?>"><?= $crmcertf['o_cerft'] ?></a></td>
                </tr>
            </table>
        <?php }else{ ?>
            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td width="14%" class="no-border vertical-center label-align">公 司 三 证 合 一：</td>
                    <td width="85%" class="no-border vertical-center value-align"><a href="<?=  \Yii::$app->ftpPath['httpIP'] ?>/cmp/bslcns/<?= $newnName1 ?>/<?= $crmcertf['bs_license'] ?>"><?= $crmcertf['o_license'] ?></a></td>
                </tr>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td width="14%" class="no-border vertical-center label-align">一般纳税人资格证：</td>
                    <td width="85%" class="no-border vertical-center value-align"><a href="<?=  Yii::$app->ftpPath['httpIP'] ?>/cmp/txqlf/<?= $newnName3 ?>/<?= $crmcertf['qlf_certf'] ?>"><?= $crmcertf['o_cerft'] ?></a></td>
                </tr>
            </table>
        <?php } ?>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="14%" class="no-border vertical-center label-align">备注：</td>
                <td width="85%" class="no-border vertical-center value-align"><?= $crmcertf['marks'] ?></td>
            </tr>
        </table>


    </div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">主要联系人</a>
    </h2>

    <div class="mb-30 display-none">
        <div id="contact" class="retable"></div>
    </div>

    <div class="space-10"></div>
    <div class="space-10"></div>

    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">设备信息</a>
    </h2>

    <div class="mb-30 display-none">
        <div id="device" class="retable"></div>
    </div>

    <div class="space-10"></div>
    <div class="space-10"></div>


    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">主营产品</a>
    </h2>
    <div class="mb-30 display-none">
        <div id="mainProduct" class="retable"></div>
    </div>

    <div class="space-10"></div>
    <div class="space-10"></div>

    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">主要客户</a>
    </h2>
    <div class="mb-30 display-none">
        <div id="mainCustomer" class="retable"></div>
    </div>
</div>
<script>
    $(function(){
        var ynspp = $("#ynspp").val();//获取是否供应商标记
        if (ynspp == 1) {
            $("#radyes").attr('checked', 'checked');
            $("#radno").attr('checked', false);
            $("#custcode").css('display', 'block');
        }
        else {
            $("#radyes").attr('checked', false);
            $("#radno").attr('checked', 'checked');
            $("#custcode").css('display', 'none');
        }
        var types = $("#crtftype").val();//获取证件类型
        if (types == 1) {
            $("#new").attr('checked', 'checked');
            $("#old").attr('checked', false);
            $("#tax").css('display', 'none');
            $("#business").text("公司三证合一证：");
        }
        else {
            $("#new").attr('checked', false);
            $("#old").attr('checked', 'checked');
            $("#tax").css('display', 'block');
            $("#business").text("公司营业执照证：");
        }


        $(".head-second").next("div:eq(0)").css("display", "block");
//        $(".head-three").next("div:eq(1),div:eq(2),div:eq(3)").css("display", "none");
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-down");
            $(this).prev().toggleClass("icon-caret-right");
            $(".retable").datagrid("resize");
        });
        $("#tabs").tabs({
            tabPosition:'top',
            height:'auto'
        });
        /*主要联系人*/
        $("#contact").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/contact-person']);?>?id=" + <?= $model['cust_id'] ?>,
            rownumbers: true,
            method: "get",
            idField: "ccper_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "ccper_name", title: "姓名", width: 100},
                {field:"ccper_sex",  title: "性别", width: 50,formatter:function(val,row){
                    return row.ccper_sex==1?"男":"女";
                }},
                {field: "ccper_post", title: "职务", width: 100},
                {field: "ccper_deparment", title: "部门", width: 100},
                {field: "ccper_tel", title: "座机", width: 100},
                {field: "ccper_mobile", title: "手机", width: 100},
                {field: "ccper_birthday", title: "生日", width: 100},
                {field: "ccper_fax", title: "传真", width: 100},
                {field: "ccper_mail", title: "邮箱", width: 120},
                {field: "ccper_wechat", title: "微信", width: 120},
                {field: "ccper_qq", title: "QQ", width: 100},
                {field:"ccper_ismain",  title: "是否主要联系人", width: 100,formatter:function(val,row){
                    return row.ccper_ismain==1?"是":"否";
                }},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#contact');
                showEmpty($(this),data.total,0);
            }
        });
        /*设备信息*/
        $("#device").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-device']);?>?id=" + <?= $model['cust_id'] ?>,
            rownumbers: true,
            method: "get",
            idField: "custd_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "type", title: "设备类型", width: 500},
                {field: "brand", title: "设备品牌", width: 450},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#device');
                showEmpty($(this),data.total,0);
            }
        });
        /*主营产品*/
        $("#mainProduct").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-main-product']);?>?id=" + <?= $model['cust_id'] ?>,
            rownumbers: true,
            method: "get",
            idField: "ccp_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "ccp_sname", title: "主营产品", width: 250},
                {field: "ccp_model", title: "规格/型号", width: 150},
                {field: "ccp_annual", title: "年产量", width: 150},
                {field: "ccp_brand", title: "品牌", width: 200},
                {field: "ccp_remark", title: "备注", width: 250},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#mainProduct');
                showEmpty($(this),data.total,0);
            }
        });
        /*主要客户*/
        $("#mainCustomer").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-main-customer']);?>?id=" + <?= $model['cust_id'] ?>,
            rownumbers: true,
            method: "get",
            idField: "cc_customerid",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "cc_customer_name", title: "公司名称", width: 200,"formatter":function(value,row,index){
                    return value;
                }},
                {field: "customer_type", title: "经营类型", width: 200},
                {field: "cc_customer_tel", title: "公司电话", width: 160},
                {field: "cc_customer_ratio", title: "占营收比率(%)", width: 150},
                {field: "cc_customer_person", title: "公司负责人", width: 200},
                {field: "cc_customer_mobile", title: "联系方式", width: 200},
                {field: "cc_customer_remark", title: "备注", width: 250},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#mainCustomer');
                showEmpty($(this),data.total,0);
            }
        });
        //删除
        $("#delete").on("click", function () {
            var index = layer.confirm("确定要删除这条记录吗?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"id": <?= $model['cust_id'] ?>},
                        url: "<?=Url::to(['/crm/crm-customer-info/delete']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
                                        location.href = '<?= Url::to(['/crm/crm-customer-info/index']) ?>'
                                    }
                                });
                            } else {
                                layer.alert(msg.msg, {icon: 2})
                            }
                        },
                        error: function (msg) {
                            layer.alert(msg.msg, {icon: 2})
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
        });
        var managers;
        var username = '<?=\Yii::$app->user->identity->staff->staff_name?>';
        var userid = '<?=\Yii::$app->user->identity->staff->staff_id?>';
        var isSuper = '<?= $isSuper ?>' == 0 ? false : true;
        var manager = '<?= $model["manager"] ?>';
        if (manager) {
            managers = manager.split(",");
            managers = managers.filter(function (i) {
                return i == username;
            });
        } else {
            managers = [];
        }
        if(isSuper || managers.length == 1){
            $('.update,.apple_code').show();
        }else{
            $('.update,.apple_code').hide();
        }
        //认领
        $("#inch").on('click',function(){
            status = '<?= $model['personinch_status'] ?>';
            if(status === '0' || (managers.length < 1 && status === '10')){
                $("#inch").attr("href", '<?= Url::to(['/crm/crm-customer-info/person-inch']) ?>?customers='+ <?= $model['cust_id'] ?> +"&status="+status +'&ml=' + managers.length);
            }else if(managers.length == 1 && status === '10'){
                $("#inch").attr("href", '<?= Url::to(['/crm/crm-customer-info/person-inch']) ?>?customers='+ <?= $model['cust_id'] ?> +'&staff_id='+ userid +'&status='+status +'&ml=' + managers.length);
            }
            $("#inch").fancybox({
                padding: [],
                fitToView: false,
                width: 450,
                height: 300,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe'
            });
        })
        /*账信申请*/
        $("#credit_apply").on("click",function(){
            var a = '<?= $model["credit_apply_status"] ?>';
            if(a == 10 || a == '' || a == null){
                window.location.href = "<?=Url::to(['/crm/crm-customer-info/credit-create'])?>"+"?id=" + <?= $model['cust_id'] ?>;

            }else{
                layer.alert('已在申请',{icon:2});return false;
            }
        });
    })
</script>
