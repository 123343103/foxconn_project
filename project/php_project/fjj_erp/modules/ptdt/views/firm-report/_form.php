<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<?php $form = ActiveForm::begin(['id' => "add-form"]) ?>
<div class="mb-20">
    <div class="inline-block">
        <label class="width-100">代理类型</label>
        <div class="inline-block radio-div" id="pdfirmreport-report_agents_type">
            <label><input type="radio" value="100049" name="PdFirmReport[report_agents_type]"
                          checked="checked" <?= isset($result['report']['report_agents_type']) == '100049' ? "checked" : null; ?>>新增</label>
            <label><input type="radio" value="100050"
                          name="PdFirmReport[report_agents_type]"<?= isset($result['report']['report_agents_type']) == '100050' ? "checked" : null; ?>>绩签</label>
        </div>
    </div>
    <div class="inline-block">
        <label class="width-150">开发类型</label>
        <input type="hidden" value="" name="PdFirmReport[report_develop_type]">
        <div class="inline-block radio-div" id="pdfirmreport-report_develop_type">
            <label><input type="radio" value="100023" name="PdFirmReport[report_develop_type]"
                          checked="checked" <?= isset($result['report']['report_agents_type']) == '100023' ? "checked" : null; ?>>
                品牌</label>
            <label><input type="radio" value="100024"
                          name="PdFirmReport[report_develop_type]" <?= isset($result['report']['report_agents_type']) == '100024' ? "checked" : null; ?>>
                制造</label>
        </div>
    </div>
    <div class="inline-block">
        <label class="width-150">紧急程度</label>
        <input type="hidden" value="" name="PdFirmReport[report_urgency_degree]">
        <div class="inline-block radio-div" id="pdfirmreport-report_urgency_degree">
            <label><input type="radio" value="100051" name="PdFirmReport[report_urgency_degree]"
                          checked="checked" <?= isset($result['report']['report_agents_type']) == '100051' ? "checked" : null; ?>>
                特急</label>
            <label><input type="radio" value="100052"
                          name="PdFirmReport[report_urgency_degree]" <?= isset($result['report']['report_agents_type']) == '100052' ? "checked" : null; ?>>
                一般</label>
        </div>
    </div>
</div>


<div class="mb-20">
    <h2 class="head-second">
        厂商基本信息
    </h2>
    <div class="mb-10">
        <input type="hidden" name="PdFirmReport[firm_id]" id="firm_id" value="<?= $result['report']['firm_id']; ?>">
        <label class="width-100"><span class="red">*</span>注册公司名称</label>
        <input id="firm_sname" class="width-200 easyui-validatebox" data-options="required:'true'"
               value="<?= $result['report']['firmName']; ?>" readonly="readonly">
        <span class="width-50 ml-10">
            <a href="<?= Url::to(['/ptdt/firm-report/select-com']) ?>" id="select-com" class="fancybox.ajax">选择厂商</a>
        </span>
        <label style="width:136px;">简称</label>
        <input readonly="readonly" id="firm_shortname" class="width-200"
               value="<?= $result['report']['firm_shortname']; ?>">
    </div>

    <div class="mb-10">
        <label class="width-100">英文全称</label>
        <input readonly="readonly" id="firm_ename" class="width-200"
               value="<?= $result['report']['firm_message']['firm_ename']; ?>">
        <label class="width-200">简称</label>
        <input readonly="readonly" id="firm_eshortname" class="width-200"
               value="<?= $result['report']['firm_message']['firm_eshortname']; ?>">
    </div>
    <div class="mb-10">
        <label class="width-100">品牌&nbsp;&nbsp;&nbsp;&nbsp;中文</label>
        <input readonly="readonly" id="firm_brand" class="width-200" value="<?= $result['report']['firm_brand']; ?>">
        <label class="width-200">英文</label>
        <input readonly="readonly" id="firm_brand_english" class="width-200"
               value="<?= $result['report']['firm_message']['firm_brand_english']; ?>">
    </div>
    <div class="mb-10">
        <label class="width-100">厂商地址</label>
        <input readonly="readonly" id="firm_compaddress" class="width-800"
               value="<?= $result['report']['firm_message']['firmAddress']['fullAddress']; ?>">
    </div>
    <div class="mb-10">
        <label class="width-100">厂商来源</label>
        <input readonly="readonly" id="firm_source" class="width-200" value="<?= $result['report']['firm_source']; ?>">
        <label class="width-70">厂商类型</label>
        <input readonly="readonly" id="firm_type" class="width-200" value="<?= $result['report']['firm_type']; ?>">
        <label class="width-120">是否为集团供应商</label>
        <input readonly="readonly" id="firm_issupplier" class="width-200"
               value="<?= $result['report']['firmSupplier'] ?>">
    </div>
    <div>
        <label class="width-100">分级分类</label>
        <input readonly="readonly" id="firm_category_id" class="width-800"
               value="<?= $result['report']['firmCategory']; ?>">
    </div>
</div>
<div id="clearAll">
    <div class="mb-20">
        <h2 class="head-second">
            代理授权谈判呈报
        </h2>
        <div class="mb-10">
            <span id="negotiate-abstract" class="width-100 border-span text-center click-span">谈判摘要</span>
            <span id="product-analyze" class="width-100 height-30 border-span text-center line-height-30">产品优劣势分析</span>
            <span id="data-detail" class="width-100 height-30 border-span text-center line-height-30">商品资料明细</span>
        </div>

        <div class="negotiate">
            <div class="clear">
                <div class="space-10"></div>
                <div class="mb-20">
                    <div class="inline-block required">
                        <label class="width-70 ml-60">谈判日期</label>
                        <input type="text" name="PdFirmReportChild[pfrc_date]"
                               class="select-date  width-200  easyui-validatebox" id="pfrc_date" readonly="readonly"
                               data-options="required:'true'" value="<?= $result['child']['pfrc_date'] ?>">
                    </div>
                    <div class="inline-block required">
                        <label class="width-70 ml-30">谈判時間</label>
                        <input type="text" name="PdFirmReportChild[pfrc_time]"
                               class="select-time  width-200 easyui-validatebox" id="pfrc_time" readonly="readonly"
                               data-options="required:'true'" value="<?= $result['child']['pfrc_time'] ?>">
                    </div>
                    <div class="inline-block required">
                        <label class="width-70 ml-30">谈判地点</label>
                        <input type="text" maxlength="200" name="PdFirmReportChild[pfrc_location]"
                               class="width-200 easyui-validatebox" id="pfrc_location" data-options="required:'true'"
                               value="<?= $result['child']['pfrc_location'] ?>">
                        <div style="margin-left:105px" class="error-notice"></div>
                    </div>
                </div>
                <div class="mb-20">
                    <div class="inline-block required">
                        <label class="width-80 ml-50">厂商主谈人</label>
                        <input type="text" name="PdReception[rece_sname]" class="width-200 easyui-validatebox" data-options="required:'true'" id="rece_sname" maxlength="20" value="<?= $result['child']['reception']['receSname']; ?>">
                    </div>
                    <div class="inline-block required">
                        <label class="width-50 ml-50">职位</label>
                        <input type="text" name="PdReception[rece_position]" class="width-200 easyui-validatebox"
                               id="rece_position" data-options="required:'true'" maxlength="20"
                               value="<?= $result['child']['reception']['recePosition']; ?>">
                    </div>
                    <div class="inline-block required">
                        <label class="width-70 ml-30">联系电话</label>
                        <input type="text" name="PdReception[rece_mobile]" class="width-200 easyui-validatebox"
                               id="rece_mobile" data-options="required:'true',validType:'mobile'" maxlength="20"
                               value="<?= $result['child']['reception']['receMobile']; ?>">
                    </div>
                </div>
                <div class="mb-20">
                    <div class="inline-block required">
                        <label class="width-70 ml-60">主谈人</label>
                        <input type="text" onblur="setStaffInfo(this)" placeholder=" 请输入工号" maxlength="30"
                               name="PdFirmReportChild[pfrc_person]" class="blurOne width-150  easyui-validatebox"
                               id="pfrc_person" data-options="required:'true'"
                               value="<?= $result['child']['productPerson']['code'] ?>">
                    </div>
                    <span class="width-100 staff_name"><?= $result['child']['productPerson']['name'] ?></span>
                    <label class="width-50">职位</label>
                    <input readonly="readonly" class="width-200 ml--3 job_task"
                           value="<?= $result['child']['productPerson']['job'] ?>">
                    <label class="width-70 ml-30">联系电话</label>
                    <input readonly="readonly" class="width-200 staff_mobile"
                           value="<?= $result['child']['productPerson']['mobile'] ?>">
                </div>
                <div class="mb-20" style="width:99%;">
                    <p class="ml-50 width-100 float-left">陪同人员信息</p>
                    <p style="line-height: 25px;" class="float-right mr-50 ">
                        <button class="button-blue text-center" onclick="vacc_add()" type="button">+ 添&nbsp;加</button>
                    </p>
                    <div class="space-10 clear"></div>
                    <table class="table-small">
                        <thead>
                        <tr>
                            <th class="width-200">工号</th>
                            <th class="width-200">姓名</th>
                            <th class="width-200">职位</th>
                            <th class="width-200">联系电话</th>
                            <th class="width-200">操作</th>
                        </tr>
                        </thead>
                        <tbody id="vacc_body">
                        <?php if (!empty($result['child']['staffPerson'])) { ?>
                            <?php foreach ($result['child']['staffPerson'] as $key => $val) { ?>
                                <tr>
                                    <td>
                                        <input type="text" name="vacc[]" placeholder="请输入工号"
                                               class="width-150 text-center easyui-validatebox" data-options='validType:["accompanySame"],delay:10000000,validateOnBlur:true' onblur="job_num(this)"
                                               value="<?= $val['staff_code'] ?>">
                                    </td>
                                    <td><?= $val['staff_name'] ?></td>
                                    <td><?= $val['job_task'] ?></td>
                                    <td><?= $val['staff_mobile'] ?></td>
                                    <td><a onclick="reset(this)">重置</a> <a onclick="vacc_del(this)">删除</a></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td>
                                    <input type="text" name="vacc[]" placeholder="请输入工号"
                                           class="width-150 text-center easyui-validatebox" data-options='validType:["accompanySame"],delay:10000000,validateOnBlur:true' onblur="job_num(this)" value="">
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a onclick="reset(this)">重置</a> <a onclick="vacc_del(this)">删除</a></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="mb-30">
                    <h2 class="head-another text-center">谈判内容</h2>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label class="width-110">代理等级</label>
                            <select name="PdAgentsAuthorize[pdaa_agents_grade]" class="width-250"
                                    id="pdaa_agents_grade">
                                <option value="">请选择</option>
                                <?php foreach ($downList['agentsLevel'] as $key => $val) { ?>
                                    <option
                                            value="<?= $val['bsp_id'] ?>" <?= isset($result['child']['authorize']['pdaa_agents_grade']) && $result['child']['authorize']['pdaa_agents_grade'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>

                            <div class="help-block"></div>
                        </div>
                        <div class="inline-block">
                            <label class="width-300">授权区域范围</label>
                            <select name="PdAgentsAuthorize[pdaa_authorize_area]" class="width-250"
                                    id="pdaa_authorize_area">
                                <option value="">请选择</option>
                                <?php foreach ($downList['authorizeArea'] as $key => $val) { ?>
                                    <option
                                            value="<?= $val['bsp_id'] ?>" <?= isset($result['child']['authorize']['pdaa_authorize_area']) && $result['child']['authorize']['pdaa_authorize_area'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>

                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label for="pdagentsauthorize-pdaa_sale_area" class="width-110">销售范围</label>
                            <select name="PdAgentsAuthorize[pdaa_sale_area]" class="width-250"
                                    id="pdaa_sale_area">
                                <option value="">请选择</option>
                                <?php foreach ($downList['saleArea'] as $key => $val) { ?>
                                    <option
                                            value="<?= $val['bsp_id'] ?>" <?= isset($result['child']['authorize']['pdaa_sale_area']) && $result['child']['authorize']['pdaa_sale_area'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                            <div class="help-block"></div>
                        </div>
                        <div class="inline-block">
                            <label class="width-300">授权开始日期</label>
                            <input type="text" readonly="readonly" name="PdAgentsAuthorize[pdaa_bdate]"
                                   class="width-100 select-date" id="pdaa_bdate"
                                   value="<?= $result['child']['authorize']['pdaa_bdate'] ?>">
                        </div>
                        至
                        <div class="inline-block">
                            <input type="text" readonly="readonly" name="PdAgentsAuthorize[pdaa_edate]"
                                   class="width-130 select-date" id="pdaa_edate"
                                   value="<?= $result['child']['authorize']['pdaa_edate']; ?>">
                        </div>
                    </div>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label class="width-110">结算方式</label>
                            <select name="PdAgentsAuthorize[pdaa_settlement]" class="width-250"
                                    id="pdaa_settlement">
                                <option value="">请选择</option>
                                <?php foreach ($downList['settlement'] as $key => $val) { ?>
                                    <option
                                            value="<?= $val['bnt_id'] ?>" <?= isset($result['child']['authorize']['pdaa_settlement']) && $result['child']['authorize']['pdaa_settlement'] == $val['bnt_id'] ? "selected" : null ?>><?= $val['bnt_sname'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="inline-block">
                            <label class="width-300">交期</label>
                            <input type="text" name="PdAgentsAuthorize[pdaa_delivery_day]" class="width-200"
                                   id="pdaa_delivery_day"
                                   value="<?= $result['child']['authorize']['pdaa_delivery_day']; ?>">
                        </div>
                        <span>天</span>
                    </div>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label class="width-110">售后服务</label>
                            <select name="PdAgentsAuthorize[pdaa_service]" class="width-250"
                                    id="pdaa_service">
                                <option value="">请选择</option>
                                <?php foreach ($downList['service'] as $key => $val) { ?>
                                    <option
                                            value="<?= $val['bsp_id'] ?>" <?= isset($result['child']['authorize']['pdaa_service']) && $result['child']['authorize']['pdaa_service'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                            <div class="help-block"></div>
                        </div>
                        <div class="inline-block">
                            <label class="width-300">物流配送</label>
                            <select name="PdAgentsAuthorize[pdaa_delivery_way]" class="width-250"
                                    id="pdaa_delivery_way">
                                <option value="">请选择</option>
                                <?php foreach ($downList['deliveryWay'] as $key => $val) { ?>
                                    <option
                                            value="<?= $val['bsp_id'] ?>" <?= isset($result['child']['authorize']['pdaa_delivery_way']) && $result['child']['authorize']['pdaa_delivery_way'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="analyze display-none">
            <div class="clear">
                <div class="mb-20">
                    <h2 class="head-another text-center">供应商面</h2>
                    <div class="mb-10">
                        <input type="hidden" id="analysis_id" value="<?= count($result['firmCompared']); ?>">
                        <div class="inline-block">
                            <label class="width-150">厂商年营业额</label>
                            <input type="text" maxlength="15" name="PdNegotiationAnalysis[pdna_annual_sales]"
                                   class="width-150 easyui-validatebox" data-options="required:'true'" id="pdna_annual_sales"
                                   value="<?= $result['child']['analysis']['pdna_annual_sales'] ?>">
                        </div>
                        <div class="inline-block">
                            <label class="width-150">地位</label>
                            <select name="PdNegotiationAnalysis[pdna_position]" class="width-150 easyui-validatebox" data-options="required:'true'" id="pdna_position">
                                <option value="">请选择</option>
                                <?php foreach ($downList['position'] as $key => $val) { ?>
                                    <option
                                            value="<?= $val['bsp_id'] ?>" <?= isset($result['child']['analysis']['pdna_position']) && $result['child']['analysis']['pdna_position'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="inline-block">
                            <label class="width-140">商品定位</label>
                            <select name="PdNegotiationAnalysis[pdna_loction]" class="width-150 easyui-validatebox" data-options="required:'true'" id="pdna_loction">
                                <option value="">请选择</option>
                                <?php foreach ($downList['productLevel'] as $key => $val) { ?>
                                    <option
                                            value="<?= $val['bsp_id'] ?>" <?= isset($result['child']['analysis']['pdna_loction']) && $result['child']['analysis']['pdna_loction'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label class="width-150">业界影响力/排名</label>
                            <input type="text" maxlength="100" name="PdNegotiationAnalysis[pdna_influence]"
                                   class="width-300 easyui-validatebox" data-options="required:'true'" id="pdna_influence"
                                   value="<?= $result['child']['analysis']['pdna_influence'] ?>">
                        </div>
                        <div class="inline-block">
                            <label class="width-150">厂商配合度</label>
                            <select name="PdNegotiationAnalysis[pdna_cooperate_degree]" class="width-300 easyui-validatebox" data-options="required:'true'"
                                    id="pdna_cooperate_degree">
                                <option value="">请选择</option>
                                <?php foreach ($downList['degree'] as $key => $val) { ?>
                                    <option
                                            value="<?= $val['bsp_id'] ?>" <?= isset($result['child']['analysis']['pdna_cooperate_degree']) && $result['child']['analysis']['pdna_cooperate_degree'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label class="width-150">技术实力/技术服务</label>
                            <textarea rows="3" name="PdNegotiationAnalysis[pdna_technology_service]"
                                      class="width-750 text-top easyui-validatebox" data-options="required:'true'" maxlength="200"
                                      id="pdna_technology_service"><?= $result['child']['analysis']['pdna_technology_service'] ?></textarea>
                        </div>
                    </div>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label class="width-150">其他</label>
                            <textarea rows="3" name="PdNegotiationAnalysis[pdna_others]" class="width-750 text-top"
                                      maxlength="200"
                                      id="pdna_others"><?= $result['child']['analysis']['pdna_others']; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="mb-20">
                    <h2 class="head-another text-center">商品面</h2>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label class="width-150">客户群（by 产业）</label>
                            <input type="text" maxlength="30" name="PdNegotiationAnalysis[pdna_customer_base]"
                                   class="width-300 easyui-validatebox" data-options="required:'true'" id="pdna_customer_base"
                                   value="<?= $result['child']['analysis']['pdna_customer_base']; ?>">

                        </div>
                        <div class="inline-block">
                            <label class="width-150">销量/市占率</label>
                            <input type="text" maxlength="30" name="PdNegotiationAnalysis[pdna_market_share]"
                                   class="width-300 easyui-validatebox" data-options="required:'true'" id="pdna_market_share"
                                   value="<?= $result['child']['analysis']['pdna_market_share']; ?>">
                        </div>
                    </div>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label for="pdnegotiationanalysis-pdna_demand_trends" class="width-150">市场需求趋势</label>
                            <textarea rows="3" name="PdNegotiationAnalysis[pdna_demand_trends]"
                                      class="width-750 text-top easyui-validatebox" data-options="required:'true'" id="pdna_demand_trends"
                                      maxlength="100"><?= $result['child']['analysis']['pdna_demand_trends']; ?></textarea>
                        </div>
                    </div>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label class="width-150">商品认证/厂商认证</label>
                            <textarea rows="3" name="PdNegotiationAnalysis[pdna_goods_certificate]"
                                      class="width-750 text-top easyui-validatebox" data-options="required:'true'" id="pdna_goods_certificate"
                                      maxlength="30"><?= $result['child']['analysis']['pdna_goods_certificate']; ?></textarea>
                        </div>
                    </div>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label class="width-150">利润分析</label>
                            <textarea rows="3" name="PdNegotiationAnalysis[profit_analysis]" class="width-750 text-top easyui-validatebox" data-options="required:'true'"
                                      id="profit_analysis"
                                      maxlength="30"><?= $result['child']['analysis']['profit_analysis']; ?></textarea>
                        </div>
                    </div>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label class="width-150">价格优势</label>
                            <textarea rows="3" name="PdNegotiationAnalysis[sales_advantage]" class="width-750 text-top easyui-validatebox" data-options="required:'true'"
                                      id="sales_advantage"
                                      maxlength="30"><?= $result['child']['analysis']['sales_advantage']; ?></textarea>

                        </div>
                    </div>
                </div>
                <div class="mb-20">
                    <h2 class="head-another text-center">代理价值</h2>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label for="pdnegotiationanalysis-value_fjj" class="width-150">富金机</label>
                            <textarea rows="3" name="PdNegotiationAnalysis[value_fjj]" maxlength="100"
                                      class="width-750 text-top"
                                      id="value_fjj"><?= $result['child']['analysis']['value_fjj']; ?></textarea>
                        </div>
                    </div>
                    <div class="mb-10">
                        <div class="inline-block">
                            <label class="width-150">厂商</label>
                            <textarea rows="3" name="PdNegotiationAnalysis[value_frim]" maxlength="100"
                                      class="width-750 text-top"
                                      id="value_frim"><?= $result['child']['analysis']['value_frim']; ?></textarea>
                        </div>
                    </div>
                    <div class="width-400 margin-auto">
                        <button id="next-step" class="button-blue ml-110" type="button">下一步</button>
                        <button id="next-step-2" class="button-blue ml-110 display-none" type="button">下一步</button>
                        <button onclick="window.location.reload();" class="button-white ml-40" type="button">取&nbsp;消
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-table display-none overflow-auto width-900  margin-auto pb-20">
            <div class="clear">
                <table class="analyze-table">
                    <tbody>
                    <tr>
                        <th colspan="20" class="colspan">
                            <span id="product-analyze-table">产品优势分析表</span>
                            <div id="change-firm" class="width-150 float-right">
                                <button id="last-step" class="button-blue" type="button">上一步</button>
                                <a class="add-tr">
                                    <button style="width:80px;" class="button-blue" type="button"><span id="span_add" style="color:#fff;">添加厂商</span>
                                    </button>
                                </a>

                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td rowspan="2">分类</td>
                        <td rowspan="2">项目</td>
                        <td rowspan="2">内容</td>
                        <td colspan="14" class="colspan1">具体描述</td>
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
        </div>
        <div class="product-detail display-none">
            <div class="overflow-auto">
                <div class="space-20"></div>
                <div class="pd_action">
                    <p class="head">商品信息</p>
                    <p class="float-right">
                        <a href="#inline" id="addPd">新增商品信息</a>
                        <!--                        <a id="deletePd">删除商品信息</a>-->
                        <span><a href="<?= Url::to(['/ptdt/firm-report/load-dvlp']) ?>" id="load-dvlp"
                                 class="fancybox.ajax">需求单增加</a></span>
                    </p>
                </div>
                <div class="overflow-auto width-900  margin-auto pb-20">
                    <div class="space-10"></div>
                    <input type="hidden"
                           value="<?= !empty(count($result['child']['products'])) ? count($result['child']['products']) : '0'; ?>"
                           id="table_id">
                    <table class="product-list table-auto" id="requirement-index">
                        <thead>
                        <tr>
                            <!--                            <th>序号</th>-->
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
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="table_body"></tbody>
                    </table>
                </div>
            </div>
            <div id="good-manager" class="display-none">
                <div class="space-40"></div>
                <div style="border-bottom:1px dashed #000;">商品经理人</div>
                <div class="text-center">
                    <label class="width-100" for="">开发中心:</label>
                    <span id="centerName"></span>
                    <label class="width-100" for="">开发部:</label>
                    <span id="departmentName"></span>
                    <label class="width-100" for="">Commodity:</label>
                    <span id="commodity"></span>
                    <label class="width-100" for="">姓名/工号:</label>
                    <span id="manager"></span>
                </div>
                <div class="space-40"></div>
            </div>
        </div>
    </div>
    <div class="space-40"></div>
    <div class="display-none">
        <input type="text" name="PdFirmReport[report_status]" class="report_status">
    </div>
    <div class="width-400 margin-auto">
        <button id="sub" class="button-blue-big">保&nbsp;存</button>
        <button id="refer" class="button-white-big ml-40">提&nbsp;交</button>
        <button onclick="history.go(-1);" type="button" class="button-white-big ml-40">取&nbsp;消</button>
    </div>
</div>

<div style="display: none" id="inline">
    <div class="content">
        <div class="mb-20">
            <input type="hidden" id="product_requirement">
            <label class="width-100">供应商商品名称</label>
            <input type="text" class="width-100" maxlength="60" id="product_name">
            <label class="width-100">规格型号</label>
            <input type="text" class="width-100" id="product_size">
            <label class="width-100">品牌</label>
            <input type="text" class="width-100" id="product_brand">
            <span id="centerName"></span>
        </div>
        <div class="mb-20">
            <label class="width-100">交货条件</label>
            <select id="delivery_terms" class="width-100">
                <?php foreach ($downList['devcon'] as $val) { ?>
                    <option value="<?= $val['dec_id'] ?>"><?= $val['dec_sname'] ?></option>
                <?php } ?>
            </select>
            <label class="width-100">付款条件</label>
            <select id="payment_terms" class="width-100">
                <?php foreach ($downList['payment'] as $val) { ?>
                    <option value="<?= $val['pat_code'] ?>"><?= $val['pat_sname'] ?></option>
                <?php } ?>
            </select>
            <label class="width-100">交易单位</label>
            <select class="width-100" id="product_unit">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradingUnit'] as $val) { ?>
                    <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-20">
            <label class="width-100">交易币别</label>
            <select class="width-100" id="currency_type">
                <option value="">请选择...</option>
                <?php foreach ($downList['currency'] as $val) { ?>
                    <option value="<?= $val['cur_id'] ?>"><?= $val['cur_sname'] ?></option>
                <?php } ?>
            </select>
            <label class="width-100">定价上限</label>
            <input type="text" class="width-100" id="price_max">
            <label class="width-100">定价下限</label>
            <input type="text" class="width-100" id="price_min">
        </div>
        <div class="mb-20">
            <label class="width-100">量价区间</label>
            <input type="text" class="width-100" maxlength="60" id="price_range">
            <label class="width-100">市场均价</label>
            <input type="text" class="width-100" id="price_average">
            <label class="width-100">代理商品定位</label>
            <select id="product_level" class="width-100">
                <?php foreach ($downList['productLevel'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>" <?= isset($get['PdFirmReportSearch']['product_level']) && $get['PdFirmReportSearch']['product_level'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-20">
            <label class="width-100">利润率</label>
            <input type="hidden" id="profit_margin">
            <input type="text" class="width-150" maxlength="60" id="profit_margin_min">
            <span>至</span>
            <input type="text" class="width-150" id="profit_margin_max">
        </div>
        <div class="mb-20">
            <label class="width-100">一阶</label>
            <select id="type_1" class="width-130 type">
                <option value="">请选择</option>
                <?php foreach ($downList['productTypes'] as $key => $val) { ?>
                    <option value="<?= $key ?>"><?= $val ?></option>
                <?php } ?>
            </select>
            <label class="width-100">二阶</label>
            <select id="type_2" class="width-130 type">
                <option value="">请选择</option>
            </select>
            <label class="width-100">三阶</label>
            <select id="type_3" class="width-130 type">
                <option value="">请选择</option>
            </select>
        </div>
        <div class="mb-20">
            <label class="width-100">四阶</label>
            <select id="type_4" class="width-130 type">
                <option value="">请选择</option>
            </select>
            <label class="width-100">五阶</label>
            <select id="type_5" class="width-130 type">
                <option value="">请选择</option>
            </select>
            <label class="width-100">六阶</label>
            <select id="type_6" class="width-130 type">
                <option value="">请选择</option>
            </select>
        </div>
        <div class="mb-20 text-center">
            <button id="save-button" type="button" class="button-blue-big">提交</button>
            <button type="button" class="button-white-big ml-20" onclick="close_select()">取消</button>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        $("#sub").on('click',function () {
            if ($("#pdna_annual_sales").val() == '' ||
                $("#pdna_position").val() == '' ||
                $("#pdna_loction").val() == '' ||
                $("#pdna_influence").val() == '' ||
                $("#pdna_cooperate_degree").val() == '' ||
                $("#pdna_technology_service").val() == '' ||
                $("#pdna_customer_base").val() == '' ||
                $("#sales_advantage").val() == '' ||
                $("#pdna_market_share").val() == '' ||
                $("#pdna_demand_trends").val() == '' ||
                $("#pdna_goods_certificate").val() == '' ||
                $("#profit_analysis").val() == ''
            ) {
                layer.alert('产品分析输入不完整', {icon: 2, time: 5000});
                return false;
            }else{
                $(".report_status").val("20");
                ajaxSubmitForm($("#add-form"));
            }
        });
        $("#refer").on('click',function () {
            if ($("#pdna_annual_sales").val() == '' ||
                $("#pdna_position").val() == '' ||
                $("#pdna_loction").val() == '' ||
                $("#pdna_influence").val() == '' ||
                $("#pdna_cooperate_degree").val() == '' ||
                $("#pdna_technology_service").val() == '' ||
                $("#pdna_customer_base").val() == '' ||
                $("#sales_advantage").val() == '' ||
                $("#pdna_market_share").val() == '' ||
                $("#pdna_demand_trends").val() == '' ||
                $("#pdna_goods_certificate").val() == '' ||
                $("#profit_analysis").val() == ''
            ) {
                layer.alert('产品分析输入不完整', {icon: 2, time: 5000});
                return false;
            }else{
                $(".report_status").val("40");
                return ajaxSubmitForm($("#add-form"));
            }
        })
    })

    <?php if(\Yii::$app->controller->action->id == "update"){ ?>

    var i = 0;
    <?php foreach($result['child']["products"] as $key=>$val){?>
    var tdStr = "<tr data-key = '" + i + "'>"
    tdStr += "<td>" + "<?= $val['product_name']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['product_size']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['product_brand']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['delivery_terms']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['payment_terms']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['unit']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['currency_type']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['price_max']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['price_min']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['price_range']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['price_average']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['levelName']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['profit_margin']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][0]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][1]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][2]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][3]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][4]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][5]; ?>" + "</td>";
    tdStr += "<td><a onclick='product_del(this)'>删除</a></td>";
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][demand_id]" value="<?= $val['demand_id']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_name]" value="<?= $val['product_name']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_size]" value="<?= $val['product_size']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_brand]" value="<?= $val['product_brand']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][delivery_terms]" value="<?= $val['delivery_terms']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][payment_terms]" value="<?= $val['payment_terms']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_unit]" value="<?= $val['product_unit']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][currency_type]" value="<?= $val['currency_type']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_max]" value="<?= $val['price_max']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_min]" value="<?= $val['price_min']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_range]" value="<?= $val['price_range']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_average]" value="<?= $val['price_average']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_level]" value="<?= $val['product_level']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][profit_margin]" value="<?= $val['profit_margin']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_1]" value="<?= $val['product_type_1']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_2]" value="<?= $val['product_type_2']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_3]" value="<?= $val['product_type_3']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_4]" value="<?= $val['product_type_4']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_5]" value="<?= $val['product_type_5']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_6]" value="<?= $val['product_type_6']; ?>">';
    tdStr += "</tr>";
    $("#table_body").append(tdStr);
    i++;
    <?php } ?>


    <?php if(!empty($result['firmCompared'])){ ?>
        <?php foreach($result['lists'] as $key => $val){ ?>
            <?php if($key == 0){ ?>
            $(".pdna_firm").append("<td class='remove'><input type='hidden' id='compared' name='PdFirmReportCompared[]' value='<?php echo $val['firm']['firm_id']; ?>'><span> <?php echo $val['firm']['firm_shortname'] ? $val['firm']['firm_shortname'] : $val['firm']['firm_sname']; ?></span> </td>");
            $(".pdna_influence").append("<td class='remove'> <?php echo $val['analysis']['pdna_influence'] ? $val['analysis']['pdna_influence'] : "/"; ?> </td>");
            $(".pdna_technology_service").append("<td class='remove'> <?php echo $val['analysis']['pdna_technology_service'] ? $val['analysis']['pdna_technology_service'] : "/"; ?> </td>");
            $(".pdna_cooperate_degree").append("<td class='remove'> <?php echo $val['analysis']['bsPubdata']['cooperateDegree'] ? $val['analysis']['bsPubdata']['cooperateDegree'] : "/"; ?> </td>");
            $(".pdna_others").append("<td class='remove'> <?php echo $val['analysis']['pdna_others'] ? $val['analysis']['pdna_others'] : "/"; ?> </td>");
            $(".pdna_demand_trends").append("<td class='remove'> <?php echo $val['analysis']['pdna_demand_trends'] ? $val['analysis']['pdna_demand_trends'] : "/"; ?> </td>");
            $(".pdna_goods_certificate").append("<td class='remove'> <?php echo $val['analysis']['pdna_goods_certificate'] ? $val['analysis']['pdna_goods_certificate'] : "/"; ?> </td>");
            $(".pdna_customer_base").append("<td class='remove'> <?php echo $val['analysis']['pdna_customer_base'] ? $val['analysis']['pdna_customer_base'] : "/"; ?> </td>");
            $(".pdna_market_share").append("<td class='remove'> <?php echo $val['analysis']['pdna_market_share'] ? $val['analysis']['pdna_market_share'] : "/"; ?> </td>");
            $(".profit_analysis").append("<td class='remove'> <?php echo $val['analysis']['profit_analysis'] ? $val['analysis']['profit_analysis'] : "/"; ?> </td>");
            $(".sales_advantage").append("<td class='remove'> <?php echo $val['analysis']['sales_advantage'] ? $val['analysis']['sales_advantage'] : "/"; ?> </td>");
            $(".value_fjj").append("<td class='remove'> <?php echo $val['analysis']['value_fjj'] ? $val['analysis']['value_fjj'] : "/"; ?> </td>");
            $(".value_frim").append("<td class='remove'> <?php echo $val['analysis']['value_frim'] ? $val['analysis']['value_frim'] : '/'; ?> </td>");
            <?php }elseif($key >= 1){ ?>
            $(".pdna_firm").append("<td class='tbm'><input type='hidden' id='compared' name='PdFirmReportCompared[]' value='<?php echo $val['firm']['firm_id']; ?>'><span> <?php echo $val['firm']['firm_shortname'] ? $val['firm']['firm_shortname'] : $val['firm']['firm_sname']; ?></span> </td>");
            $(".pdna_influence").append("<td class='tbm'> <?php echo $val['analysis']['pdna_influence'] ? $val['analysis']['pdna_influence'] : "/"; ?> </td>");
            $(".pdna_technology_service").append("<td class='tbm'> <?php echo $val['analysis']['pdna_technology_service'] ? $val['analysis']['pdna_technology_service'] : "/"; ?> </td>");
            $(".pdna_cooperate_degree").append("<td class='tbm'> <?php echo $val['analysis']['bsPubdata']['cooperateDegree'] ? $val['analysis']['bsPubdata']['cooperateDegree'] : "/"; ?> </td>");
            $(".pdna_others").append("<td class='tbm'> <?php echo $val['analysis']['pdna_others'] ? $val['analysis']['pdna_others'] : "/"; ?> </td>");
            $(".pdna_demand_trends").append("<td class='tbm'> <?php echo $val['analysis']['pdna_demand_trends'] ? $val['analysis']['pdna_demand_trends'] : "/"; ?> </td>");
            $(".pdna_goods_certificate").append("<td class='tbm'> <?php echo $val['analysis']['pdna_goods_certificate'] ? $val['analysis']['pdna_goods_certificate'] : "/"; ?> </td>");
            $(".pdna_customer_base").append("<td class='tbm'> <?php echo $val['analysis']['pdna_customer_base'] ? $val['analysis']['pdna_customer_base'] : "/"; ?> </td>");
            $(".pdna_market_share").append("<td class='tbm'> <?php echo $val['analysis']['pdna_market_share'] ? $val['analysis']['pdna_market_share'] : "/"; ?> </td>");
            $(".profit_analysis").append("<td class='tbm'> <?php echo $val['analysis']['profit_analysis'] ? $val['analysis']['profit_analysis'] : "/"; ?> </td>");
            $(".sales_advantage").append("<td class='tbm'> <?php echo $val['analysis']['sales_advantage'] ? $val['analysis']['sales_advantage'] : "/"; ?> </td>");
            $(".value_fjj").append("<td class='tbm'> <?php echo $val['analysis']['value_fjj'] ? $val['analysis']['value_fjj'] : "/"; ?> </td>");
            $(".value_frim").append("<td class='tbm'> <?php echo $val['analysis']['value_frim'] ? $val['analysis']['value_frim'] : '/'; ?> </td>");
            <?php } ?>

        <?php } ?>
    <?php }else{ ?>
        $(".pdna_firm").append("<td class='remove'><input type='hidden' id='compared' name='PdFirmReportCompared[]' value='<?php echo $result['lists']['firm']['firm_id']; ?>'><span> <?php echo $result['lists']['firm']['firm_shortname'] ? $result['lists']['firm']['firm_shortname'] : $result['lists']['firm']['firm_sname']; ?></span> </td>");
        $(".pdna_influence").append("<td class='remove'> <?php echo $result['lists']['analysis']['pdna_influence'] ? $result['lists']['analysis']['pdna_influence'] : "/"; ?> </td>");
        $(".pdna_technology_service").append("<td class='remove'> <?php echo $result['lists']['analysis']['pdna_technology_service'] ? $result['lists']['analysis']['pdna_technology_service'] : "/"; ?> </td>");
        $(".pdna_cooperate_degree").append("<td class='remove'> <?php echo $result['lists']['analysis']['bsPubdata']['cooperateDegree'] ? $result['lists']['analysis']['bsPubdata']['cooperateDegree'] : "/"; ?> </td>");
        $(".pdna_others").append("<td class='remove'> <?php echo $result['lists']['analysis']['pdna_others'] ? $result['lists']['analysis']['pdna_others'] : "/"; ?> </td>");
        $(".pdna_demand_trends").append("<td class='remove'> <?php echo $result['lists']['analysis']['pdna_demand_trends'] ? $result['lists']['analysis']['pdna_demand_trends'] : "/"; ?> </td>");
        $(".pdna_goods_certificate").append("<td class='remove'> <?php echo $result['lists']['analysis']['pdna_goods_certificate'] ? $result['lists']['analysis']['pdna_goods_certificate'] : "/"; ?> </td>");
        $(".pdna_customer_base").append("<td class='remove'> <?php echo $result['lists']['analysis']['pdna_customer_base'] ? $result['lists']['analysis']['pdna_customer_base'] : "/"; ?> </td>");
        $(".pdna_market_share").append("<td class='remove'> <?php echo $result['lists']['analysis']['pdna_market_share'] ? $result['lists']['analysis']['pdna_market_share'] : "/"; ?> </td>");
        $(".profit_analysis").append("<td class='remove'> <?php echo $result['lists']['analysis']['profit_analysis'] ? $result['lists']['analysis']['profit_analysis'] : "/"; ?> </td>");
        $(".sales_advantage").append("<td class='remove'> <?php echo $result['lists']['analysis']['sales_advantage'] ? $result['lists']['analysis']['sales_advantage'] : "/"; ?> </td>");
        $(".value_fjj").append("<td class='remove'> <?php echo $result['lists']['analysis']['value_fjj'] ? $result['lists']['analysis']['value_fjj'] : "/"; ?> </td>");
        $(".value_frim").append("<td class='remove'> <?php echo $result['lists']['analysis']['value_frim'] ? $result['lists']['analysis']['value_frim'] : '/'; ?> </td>");
    <?php } ?>
    <?php } ?>

    <?php if(\Yii::$app->controller->action->id == "add"){ ?>
    $("#firm_id").val('<?= $result['lists']['firm']['firm_id'] ?>');
    $("#firm_sname").val('<?= $result['lists']['firm']['firm_sname'] ?>');
    $("#firm_shortname").val('<?= $result['lists']['firm']['firm_shortname'] ?>');
    $("#firm_ename").val('<?= $result['lists']['firm']['firm_ename'] ?>');
    $("#firm_eshortname").val('<?= $result['lists']['firm']['firm_eshortname'] ?>');
    $("#firm_compaddress").val('<?= $result['lists']['firm']['firmAddress']['fullAddress'] ?>');
    $("#firm_source").val('<?= $result['lists']['firm']['firmSource'] ?>');
    $("#firm_type").val('<?= $result['lists']['firm']['firmType'] ?>');
    $("#firm_issupplier").val('<?= $result['lists']['firm']['issupplier'] ?>');
    $("#firm_category_id").val('<?= $result['lists']['firm']['category'] ?>');
    $("#pfrc_date").val('<?= $result['lists']['child']['pdnc_date'] ?>');
    $("#pfrc_time").val('<?= $result['lists']['child']['pdnc_time'] ?>');
    $("#pfrc_location").val('<?= $result['lists']['child']['pdnc_location'] ?>');
    $("#rece_sname").val('<?= $result['lists']['reception']['rece_sname'] ?>');
    $("#rece_position").val('<?= $result['lists']['reception']['rece_position'] ?>');
    $("#rece_mobile").val('<?= $result['lists']['reception']['rece_mobile'] ?>');
    $("#pfrc_person").val('<?= $result['lists']['child']['pdnc_person'] ?>');
    $(".staff_name").text('<?= $result['lists']['child']['productPerson']['name'] ?>');
    $(".job_task").val('<?= $result['lists']['child']['productPerson']['title'] ?>');
    $(".staff_mobile").val('<?= $result['lists']['child']['productPerson']['mobile'] ?>');
    $("#pdaa_agents_grade").val('<?= $result['lists']['authorize']['pdaa_agents_grade'] ?>');
    $("#pdaa_authorize_area").val('<?= $result['lists']['authorize']['pdaa_authorize_area'] ?>');
    $("#pdaa_sale_area").val('<?= $result['lists']['authorize']['pdaa_sale_area'] ?>');
    $("#pdaa_bdate").val('<?= $result['lists']['authorize']['pdaa_bdate'] ?>');
    $("#pdaa_edate").val('<?= $result['lists']['authorize']['pdaa_edate'] ?>');
    $("#pdaa_settlement").val('<?= $result['lists']['authorize']['pdaa_settlement'] ?>');
    $("#pdaa_delivery_day").val('<?= $result['lists']['authorize']['pdaa_delivery_day'] ?>');
    $("#pdaa_service").val('<?= $result['lists']['authorize']['pdaa_service'] ?>');
    $("#pdaa_delivery_way").val('<?= $result['lists']['authorize']['pdaa_delivery_way'] ?>');
    $("#pdna_annual_sales").val('<?= $result['lists']['analysis']['pdna_annual_sales'] ?>');
    $("#pdna_position").val('<?= $result['lists']['analysis']['pdna_position'] ?>');
    $("#pdna_loction").val('<?= $result['lists']['analysis']['pdna_loction'] ?>');
    $("#pdna_influence").val('<?= $result['lists']['analysis']['pdna_influence'] ?>');
    $("#pdna_technology_service").val('<?= $result['lists']['analysis']['pdna_technology_service'] ?>');
    $("#pdna_others").val('<?= $result['lists']['analysis']['pdna_others'] ?>');
    $("#pdna_customer_base").val('<?= $result['lists']['analysis']['pdna_customer_base'] ?>');
    $("#pdna_market_share").val('<?= $result['lists']['analysis']['pdna_market_share'] ?>');
    $("#pdna_demand_trends").val('<?= $result['lists']['analysis']['pdna_demand_trends'] ?>');
    $("#pdna_goods_certificate").val('<?= $result['lists']['analysis']['pdna_goods_certificate'] ?>');
    $("#profit_analysis").val('<?= $result['lists']['analysis']['profit_analysis'] ?>');
    $("#sales_advantage").val('<?= $result['lists']['analysis']['sales_advantage'] ?>');
    $("#value_fjj").val('<?= $result['lists']['analysis']['value_fjj'] ?>');
    $("#value_frim").val('<?= $result['lists']['analysis']['value_frim'] ?>');
    $("#analysis_id").val("");

    <?php if(!empty($result['lists']['accompany'])){ ?>
    $("#vacc_body>tr").remove();
    <?php foreach($result['lists']['accompany'] as $key => $val){ ?>
    $("#vacc_body").append(
        '<tr>' +
        '<td>' +
        '<input onblur="job_num(this)" type="text" class="width-150  no-border text-center" placeholder="请输入工号" name="vacc[]" value="<?= $val['staff_code'] ?>">' +
        "</td>" +
        '<td><?= $val['staffInfo']['staffName'] ?></td>' +
        '<td><?= $val['staffInfo']['staffTitle'] ?></td>' +
        '<td><?= $val['staffInfo']['staffMobile'] ?></td>' +
        '<td><a onclick="reset(this)">重置</a> <a onclick="vacc_del(this)">删除</a></td>' +
        '</tr>'
    );
    <?php } ?>
    <?php } ?>
    var i = 0;
    <?php foreach($result['lists']["product"] as $key=>$val){?>
    var tdStr = "<tr data-key = '" + i + "'>"
    tdStr += "<td>" + "<?= $val['product_name']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['product_size']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['product_brand']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['delivery_terms']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['payment_terms']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['unit']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['currency']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['price_max']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['price_min']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['price_range']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['price_average']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['levelName']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['profit_margin']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][0]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][1]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][2]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][3]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][4]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $val['typeName'][5]; ?>" + "</td>";
    tdStr += "<td><a onclick='product_del(this)'>删除</a></td>";
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][demand_id]" value="<?= $val['demand_id']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_name]" value="<?= $val['product_name']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_size]" value="<?= $val['product_size']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_brand]" value="<?= $val['product_brand']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][delivery_terms]" value="<?= $val['delivery_terms']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][payment_terms]" value="<?= $val['payment_terms']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_unit]" value="<?= $val['product_unit']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][currency_type]" value="<?= $val['currency_type']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_max]" value="<?= $val['price_max']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_min]" value="<?= $val['price_min']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_range]" value="<?= $val['price_range']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_average]" value="<?= $val['price_average']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_level]" value="<?= $val['product_level']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][profit_margin]" value="<?= $val['profit_margin']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_1]" value="<?= $val['product_type_1']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_2]" value="<?= $val['product_type_2']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_3]" value="<?= $val['product_type_3']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_4]" value="<?= $val['product_type_4']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_5]" value="<?= $val['product_type_5']; ?>">';
    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_6]" value="<?= $val['product_type_6']; ?>">';
    tdStr += "</tr>";
    $("#table_body").append(tdStr);
    i++;
    <?php } ?>

    <?php } ?>

    $(document).ready(function () {

        $('.add-tr').click(function () {
            var str = '';
            var els = document.getElementsByName("PdFirmReportCompared[]");
            for (var i = 1, j = els.length; i < j; i++) {
                str += els[i].value + ",";
            }
            if (str.length > 0) {
                str = str.substr(0, str.length - 1);
            }
            if (str == null) {
                $('.add-tr').attr('href', '<?= Url::to(['/ptdt/firm-report/analysis-com']) ?>');
            }
            $('.add-tr').attr('href', '<?= Url::to(['/ptdt/firm-report/analysis-com']) ?>?id=' + str);
            $('.add-tr').fancybox(      //选择厂商弹框
                {
                    padding: [],
                    fitToView: false,
                    width: 800,
                    height: 530,
                    autoSize: false,
                    closeClick: false,
                    openEffect: 'none',
                    closeEffect: 'none',
                    type: 'iframe'
                }
            );
        })

        $('.edit-tr,#load-dvlp,#select-com').fancybox(      //选择厂商弹框
            {
                padding: [],
                fitToView: false,
                width: 800,
                height: 530,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe'
            }
        );
        $("#addPd").fancybox({
            'centerOnScroll': true,
            'title': false
        });
        //添加商品
        var product_requirement = $("#product_requirement");
        var product_name = $("#product_name");
        var product_size = $("#product_size");
        var product_brand = $("#product_brand");
        var delivery_terms = $("#delivery_terms");
        var payment_terms = $("#payment_terms");
        var product_unit = $("#product_unit");
        var currency_type = $("#currency_type");
        var price_max = $("#price_max");
        var price_min = $("#price_min");
        var price_range = $("#price_range");
        var price_average = $("#price_average");
        var product_level = $("#product_level");
        var profit_margin_min = $("#profit_margin_min");
        var profit_margin_max = $("#profit_margin_max");
        var profit_margin = $("#profit_margin");
        var $typeOne = $('#type_1');
        var $typeTwo = $('#type_2');
        var $typeThree = $('#type_3');
        var $typeFour = $('#type_4');
        var $typeFive = $('#type_5');
        var $typeSix = $('#type_6');
        $('#save-button').on('click', function () {
            var a = $("#table_id").val();
            if (a == 0) {
                a = 0;
            } else {
                a = a * 1 + 1;
            }
            if ($typeSix.val() == '' ||
                product_name.val() == '' ||
                product_size.val() == '' ||
                product_brand.val() == '' ||
                delivery_terms.val() == '' ||
                payment_terms.val() == '' ||
                product_unit.val() == '' ||
                currency_type.val() == '' ||
                price_max.val() == '' ||
                price_min.val() == '' ||
                price_range.val() == '' ||
                price_average.val() == '' ||
                product_level.val() == '' ||
                profit_margin_min.val() == '' ||
                profit_margin_max.val() == ''
            ) {
                layer.alert('商品信息输入不完整', {icon: 2, time: 5000});
                return;
            }
            profit_margin.val(profit_margin_min.val() + '-' + profit_margin_max.val());
            var tdStr = "<tr data-key = '" + a + "'>";
            tdStr += "<td>" + htmlEncodeJQ(product_name.val()) + "</td>";
            tdStr += "<td>" + htmlEncodeJQ(product_size.val()) + "</td>";
            tdStr += "<td>" + htmlEncodeJQ(product_brand.val()) + "</td>";
            tdStr += "<td>" + delivery_terms.find("option:selected").text() + "</td>";
            tdStr += "<td>" + payment_terms.find("option:selected").text() + "</td>";
            tdStr += "<td>" + product_unit.find("option:selected").text() + "</td>";
            tdStr += "<td>" + currency_type.find("option:selected").text() + "</td>";
            tdStr += "<td>" + htmlEncodeJQ(price_max.val()) + "</td>";
            tdStr += "<td>" + htmlEncodeJQ(price_min.val()) + "</td>";
            tdStr += "<td>" + htmlEncodeJQ(price_range.val()) + "</td>";
            tdStr += "<td>" + htmlEncodeJQ(price_average.val()) + "</td>";
            tdStr += "<td>" + product_level.find("option:selected").text() + "</td>";
            tdStr += "<td>" + htmlEncodeJQ(profit_margin.val()) + "</td>";
            tdStr += "<td>" + $typeOne.find("option:selected").text() + "</td>";
            tdStr += "<td>" + $typeTwo.find("option:selected").text() + "</td>";
            tdStr += "<td>" + $typeThree.find("option:selected").text() + "</td>";
            tdStr += "<td>" + $typeFour.find("option:selected").text() + "</td>";
            tdStr += "<td>" + $typeFive.find("option:selected").text() + "</td>";
            tdStr += "<td>" + $typeSix.find("option:selected").text() + "</td>";
            tdStr += "<td><a onclick='product_del(this)'>删除</a></td>";
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][demand_id]" value="' + product_requirement.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][product_name]" value="' + product_name.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][product_size]" value="' + product_size.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][product_brand]" value="' + product_brand.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][delivery_terms]" value="' + delivery_terms.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][payment_terms]" value="' + payment_terms.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][product_unit]" value="' + product_unit.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][currency_type]" value="' + currency_type.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][price_max]" value="' + price_max.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][price_min]" value="' + price_min.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][price_range]" value="' + price_range.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][price_average]" value="' + price_average.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][product_level]" value="' + product_level.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][profit_margin]" value="' + profit_margin.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][product_type_1]" value="' + $typeOne.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][product_type_2]" value="' + $typeTwo.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][product_type_3]" value="' + $typeThree.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][product_type_4]" value="' + $typeFour.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][product_type_5]" value="' + $typeFive.val() + '">';
            tdStr += '<input type="hidden" name="PdFirmReportProduct[' + a + '][product_type_6]" value="' + $typeSix.val() + '">';
            tdStr += "</tr>";
            $typeOne.find('option[value=""]').prop("selected", "selected");
            $typeTwo.find('option[value=""]').prop("selected", "selected");
            $typeThree.find('option[value=""]').prop("selected", "selected");
            $typeFour.find('option[value=""]').prop("selected", "selected");
            $typeFive.find('option[value=""]').prop("selected", "selected");
            $typeSix.find('option[value=""]').prop("selected", "selected");
            delivery_terms.find('option[value=""]').prop("selected", "selected");
            payment_terms.find('option[value=""]').prop("selected", "selected");
            product_unit.find('option[value=""]').prop("selected", "selected");
            currency_type.find('option[value=""]').prop("selected", "selected");
            product_level.find('option[value=""]').prop("selected", "selected");
            product_requirement.val("");
            product_name.val("");
            product_size.val("");
            product_brand.val("");
            price_max.val("");
            price_min.val("");
            price_range.val("");
            price_average.val("");
            profit_margin.val("");
            profit_margin_max.val("");
            profit_margin_min.val("");
            $('.product-list').append(tdStr);
            a++;
            $("#table_id").val(a);
            parent.$.fancybox.close();
        });

        $("#negotiate-abstract").click(function () {
            $(".negotiate").show();
            $(".analyze").hide();
            $(".product-table").hide();
            $(".product-detail").hide();
            $("#negotiate-abstract").addClass('click-span').removeClass('unclick-span');
            $("#product-analyze,#data-detail").addClass('unclick-span').removeClass('click-span');
        });
        $("#product-analyze").click(function () {
            $(".product-table").hide();
            $(".negotiate").hide();
            $(".product-detail").hide();
            $(".analyze").show();
            $("#product-analyze").addClass('click-span').removeClass('unclick-span');
            $("#negotiate-abstract,#data-detail").addClass('unclick-span').removeClass('click-span');
        });
        $("#last-step").click(function () {
            $(".analyze").show();
            $(".product-table").hide();
            $(".negotiate").hide();
            $(".product-detail").hide();
            $("#analysis_id").val("n");
        });

        $("#next-step").click(function () {
            if ($("#firm_id").val() == '') {
                layer.alert("请选择厂商!", {icon: 2, time: 5000});
                $("#next-step-2").attr('disabled', true);
            } else {
                $("#next-step-2").click();
            }
        })
        $("#next-step-2").click(function () {
            $(".analyze").hide();
            $(".product-table").show();
            $(".negotiate").hide();
            $(".product-detail").hide();
            var a = $("#analysis_id").val();
            <?php if(\Yii::$app->controller->action->id == "add"){ ?>
            if (a.length == 0) {
                $(".pdna_firm").append("<td class='remove'><input type='hidden' id='compared' name=PdFirmReportCompared[] value=''><span></span> </td>");
                $(".pdna_influence").append("<td class='remove'> </td>");
                $(".pdna_technology_service").append("<td class='remove'></td>");
                $(".pdna_cooperate_degree").append("<td class='remove'></td>");
                $(".pdna_others").append("<td class='remove'></td>");
                $(".pdna_demand_trends").append("<td class='remove'></td>");
                $(".pdna_goods_certificate").append("<td class='remove'></td>");
                $(".pdna_customer_base").append("<td class='remove'></td>");
                $(".pdna_market_share").append("<td class='remove'></td>");
                $(".profit_analysis").append("<td class='remove'></td>");
                $(".sales_advantage").append("<td class='remove'></td>");
                $(".value_fjj").append("<td class='remove'></td>");
                $(".value_frim").append("<td class='remove'></td>");
            }
            <?php } ?>
            $(".pdna_firm td:first input").val($("#firm_id").val());
            $(".pdna_firm td:first span").text($("#firm_shortname").val() ? $("#firm_shortname").val() : $("#firm_sname").val());
            $(".pdna_influence td:eq(3)").text($("#pdna_influence").val());
            $(".pdna_cooperate_degree td:eq(1)").text($("#pdna_cooperate_degree").find("option:selected").text());
            $(".pdna_technology_service td:eq(1)").text($("#pdna_technology_service").val());
            $(".pdna_others td:eq(1)").text($("#pdna_others").val());
            $(".pdna_customer_base td:eq(1)").text($("#pdna_customer_base").val());
            $(".pdna_market_share td:eq(1)").text($("#pdna_market_share").val());
            $(".pdna_demand_trends td:eq(3)").text($("#pdna_demand_trends").val());
            $(".pdna_goods_certificate td:eq(1)").text($("#pdna_goods_certificate").val());
            $(".profit_analysis td:eq(2)").text($("#profit_analysis").val());
            $(".sales_advantage td:eq(1)").text($("#sales_advantage").val());
            $(".value_fjj td:eq(2)").text($("#value_fjj").val());
            $(".value_frim td:eq(1)").text($("#value_frim").val());
        });

        $("#data-detail").click(function () {
            $(".product-detail").show();
            $(".product-table").hide();
            $(".negotiate").hide();
            $(".analyze").hide();
            $("#data-detail").addClass('click-span').removeClass('unclick-span');
            $("#product-analyze,#negotiate-abstract").addClass('unclick-span').removeClass('click-span');
        });
    })

    //删除陪同人员
    function vacc_del(obj) {
        var tr = $("#vacc_body tr").length;
        if (tr > 1) {
            $(obj).parents("tr").remove();
        }
    }
    $(function () {
        //商品分类联动菜单
        $('.type').on("change", function () {
            var $select = $(this);
            var $url = "<?= Url::to(['/ptdt/product-dvlp/get-product-type']) ?>";
            getNextTypeClass($select, $url);
        });
    })

    function reset(obj) {
        var td = $(obj).parents("tr").find("td");
        $(obj).parents("tr").find(td.eq(1)).text("");
        $(obj).parents("tr").find(td.eq(2)).text("");
        $(obj).parents("tr").find(td.eq(3)).text("");
        $(obj).parents("tr").find("input").val("");
    }
    ;

    function setStaffInfo(obj) {
        var url = "<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>";
        staffInfo(obj, url);
    }
    /*添加陪同人员*/
    function vacc_add() {
        $("#vacc_body").append(
            '<tr>' +
            '<td>' +
            "<input type='text' class='text-center width-150 easyui-validatebox' data-options='validType:[\"accompanySame\"],delay:10000000,validateOnBlur:true' placeholder='请点击输入工号' onblur='job_num(this)' name='vacc[]'>" +
            "</td>" +
            '<td>' +
            '</td>' +
            '<td>' +
            '</td>' +
            '<td>' +
            '</td>' +
            '<td><a onclick="reset(this)">重置</a> <a onclick="vacc_del(this)">删除</a></td>' +
            '</tr>'
        );
        $.parser.parse($("#vacc_body").find("tr:last"));//easyui解析
    }
    //陪同人员信息
    function job_num(obj) {
        var td = $(obj).parents("tr").find("td");
        var code = $(obj).val();
        if (!code) {
            return
        }
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"code": code},
            url: "<?= Url::to(['/ptdt/firm-report/get-visit-manager'])?>",
            success: function (data) {
                $(obj).val($(obj).val().toUpperCase());
                td.eq(1).text(data.staff_name);
                td.eq(2).text(data.job_task);
                td.eq(3).text(data.staff_mobile);
            },
            error: function (data) {
                layer.alert("未找到该工号!", {icon: 2})
            }
        })
    }
    //删除商品
    function product_del(obj) {
        $(obj).parents("tr").remove();
    }
    $(function () {
        $("#load-dvlp").on('click', function () {
            $("#table_body>tr").remove();
        })
    })
    $(function(){
        $("#table_body").on('mouseover','td',function(){
            this.title=$(this).text();
        })
    })
</script>