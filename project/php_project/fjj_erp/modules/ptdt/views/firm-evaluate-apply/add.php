<?php
/**
 * User: F1677929
 * Date: 2016/11/2
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = '新增厂商评鉴申请';
$this->params['homeLike'] = ['label'=>'供应商管理','url' => Url::to(['/index/index'])];
$this->params['breadcrumbs'][] = ['label' => '厂商评鉴申请列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .select2-selection{
        width: 834px;
        height: auto;
        overflow:hidden;
        position:relative;
    }
    .select2-selection__clear{
        position:relative;
        top:4px;
    }
</style>
<div class="content">
    <h1 class="head-first"><?= Html::encode($this->title) ?></h1>
    <div class="overflow-auto">
        <div id="evaluate_apply_button" style="line-height:30px;width:100px;text-align:center;cursor:pointer;border:1px solid #1f7ed0;border-bottom:none;float:left;color:#1f7ed0;">评鉴申请信息</div>
        <div id="supplier_button" style="line-height:30px;width:100px;text-align:center;cursor:pointer;border-bottom:1px solid #1f7ed0;float:left;">供应商资料</div>
        <div style="height:30px;width:788px;border-bottom:1px solid #1f7ed0;float:left;"></div>
    </div>
    <div style="border:1px solid #1f7ed0;border-top:none;padding:10px 10px 20px;">
        <?php ActiveForm::begin([
            'id' => 'add_form',
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>
        <!--    厂商评鉴申请开始-->
        <div id="add_evaluate_apply">
            <h2 class="head-second">厂商基本信息</h2>
            <div class="mb-20">
                <label class="width-100"><span class="red">*</span>厂商全称</label>
                <input type="hidden" id="firm_id" name="PdFirmEvaluateApply[firm_id]" value="">
                <input class="width-200" id="firm_sname" readonly="readonly" placeholder="请点击选择厂商" value="">
                <span style="width: 60px;"><a id="select_firm">选择厂商</a></span>
                <label>简称</label>
                <input class="width-200" id="firm_shortname" readonly="readonly" value="">
                <label class="width-110">来源</label>
                <input class="width-200" id="firm_source" readonly="readonly" value="">
            </div>
            <div class="mb-20">
                <label class="width-100">类型</label>
                <input class="width-200" id="firm_type" readonly="readonly" value="">
                <label class="width-100">地位</label>
                <input class="width-200" id="firm_position" readonly="readonly" value="">
                <label class="width-110">是否为集团供应商</label>
                <input class="width-200" id="firm_issupplier" readonly="readonly" value="">
            </div>
            <div class="mb-20">
                <label class="width-100">地址</label>
                <input id="firm_compaddress" style="width: 824px;" readonly="readonly" value="">
            </div>
            <div class="mb-20">
                <label class="width-100">厂商负责人</label>
                <input class="width-200" id="firm_compprincipal" readonly="readonly" value="">
                <label class="width-100">联系电话</label>
                <input class="width-200" id="firm_comptel" readonly="readonly" value="">
                <label class="width-110">邮箱</label>
                <input class="width-200" id="firm_compmail" readonly="readonly" value="">
            </div>
            <div class="mb-30">
                <label class="width-100">厂商联络人</label>
                <input class="width-200" id="firm_contaperson" readonly="readonly" value="">
                <label class="width-100">联系电话</label>
                <input class="width-200" id="firm_contaperson_tel" readonly="readonly" value="">
                <label class="width-110">邮箱</label>
                <input class="width-200" id="firm_contaperson_mail" readonly="readonly" value="">
            </div>
            <h2 class="head-second">申请信息</h2>
            <div class="mb-20">
                <label class="width-100">评鉴类型</label>
                <?php foreach ($evaluateApplyData['evaluateApplyType'] as $key => $val) { ?>
                    <input type="radio" name="PdFirmEvaluateApply[apply_type]" value="<?= $key ?>" style="vertical-align: top;">
                    <span class="mr-10"><?= $val ?></span>
                <?php } ?>
            </div>
            <div class="mb-20">
                <label class="width-100">申请人</label>
                <input type="hidden" name="PdFirmEvaluateApply[applicant_id]" value="<?= $evaluateApplyData['applicantInfo']['staff_id'] ?>">
                <input class="width-200" readonly="readonly" value="<?= $evaluateApplyData['applicantInfo']['staff_name'] ?>">
                <label class="width-100">课别</label>
                <input class="width-200" name="PdFirmEvaluateApply[applicant_class]" value="">
            </div>
            <div class="mb-20">
                <label class="width-100">部门</label>
                <input class="width-200" readonly="readonly" value="<?= $evaluateApplyData['applicantInfo']['organization'] ?>">
                <label class="width-100">分机</label>
                <input class="width-200" readonly="readonly" value="<?= $evaluateApplyData['applicantInfo']['staff_tel'] ?>">
            </div>
            <div class="mb-20">
                <label class="width-100">E-mail</label>
                <input class="width-200" readonly="readonly" value="<?= $evaluateApplyData['applicantInfo']['staff_email'] ?>">
                <label class="width-100">申请日期</label>
                <input class="width-200 select-date" name="PdFirmEvaluateApply[apply_date]" readonly="readonly" placeholder="请点击选择日期" value="">
            </div>
            <div class="mb-20 evaluate-apply">
                <label class="width-100">服务客户</label>
                <input class="width-200" name="PdFirmEvaluateApply[server_customer]" value="">
                <label class="width-100">预评鉴日期</label>
                <input class="width-200 select-date" name="PdFirmEvaluateApply[predict_evaluate_date]" readonly="readonly" placeholder="请点击选择日期" value="">
            </div>
            <div class="mb-20 avoid-evaluate-apply">
                <label class="width-100">部门主管</label>
                <input class="width-200" name="PdFirmEvaluateApply[department_manager]" value="">
            </div>
            <div class="mb-20 avoid-evaluate-apply">
                <label class="width-100">免评鉴条件</label>
                <?php foreach ($evaluateApplyData['avoidEvaluateCondition'] as $key => $val) { ?>
                    <input type="checkbox" name="PdFirmEvaluateApply[avoid_evaluate_condition]" value="<?= $key ?>" style="vertical-align: top;">
                    <span class="mr-10"><?= $val ?></span>
                <?php } ?>
            </div>
            <div class="mb-20 avoid-evaluate-apply">
                <label class="width-100">免评鉴条件附件</label>

            </div>
            <div class="mb-20">
                <label class="width-100 vertical-top">理由/原因</label>
                <textarea name="PdFirmEvaluateApply[apply_reason]" style="width: 824px; height: 50px;"></textarea>
            </div>
            <div class="mb-20">
                <label class="width-100">附件</label>

            </div>
            <div class="text-center">
                <button type="button" class="button-white-big" id="next_step">下一步</button>
            </div>
        </div>
        <!--    厂商评鉴申请结束-->
        <!--    供应商申请开始-->
        <div id="add_supplier" style="display: none;">
            <h2 class="head-second">供应商基本信息</h2>
            <div class="mb-20">
                <label class="width-110">供应商全称</label>
                <input type="text" id="supplier_sname" class="width-200" name="PdSupplier[supplier_sname]">
                <label class="width-110">供应商简称</label>
                <input type="text" id="supplier_shortname" class="width-200" name="PdSupplier[supplier_shortname]">
                <label class="width-110">新增类型</label>
                <select name="PdSupplier[supplier_add_type]" id="supplier_add_type" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['supplierAddType'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-110">供应商集团简称</label>
                <input type="text" id="supplier_group_sname" class="width-200" name="PdSupplier[supplier_group_sname]">
                <label class="width-110">品牌</label>
                <input type="text" id="supplier_brand" class="width-200" name="PdSupplier[supplier_brand]">
                <label class="width-110">是否为集团供应商</label>
                <select name="PdSupplier[supplier_issupplier]" id="supplier_issupplier" class="width-200">
                    <option value="">请选择</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-110 vertical-top">Commodity</label>
                <?= \kartik\select2\Select2::widget([
                    'name' => 'PdSupplier[supplier_category_id]',
                    'data' => $supplierData['supplierGradeCategory'],
                    'options' => [
                        'placeholder' => '请选择',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); ?>
            </div>
            <div class="mb-20">
                <label class="width-110 vertical-top">主营范围</label>
                <textarea name="PdSupplier[supplier_main_product]" id="supplier_main_product" style="width: 834px; height: 50px;"></textarea>
            </div>
            <div class="mb-20">
                <label class="width-110">供应商地位</label>
                <select name="PdSupplier[supplier_position]" id="supplier_position" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['supplierPosition'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
                <label class="width-110">年度营业额(USD)</label>
                <input type="text" id="supplier_annual_turnover" class="width-200" name="PdSupplier[supplier_annual_turnover]">
                <label class="width-110">交易币别</label>
                <select name="PdSupplier[supplier_trade_currency]" id="supplier_trade_currency" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['tradeCurrency'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-110">交货条件</label>
                <select name="PdSupplier[supplier_trade_condition]" id="supplier_trade_condition" class="width-200">
                    <option value="">请选择</option>
                </select>
                <label class="width-110">付款条件</label>
                <select name="PdSupplier[supplier_pay_condition]" id="supplier_pay_condition" class="width-200">
                    <option value="">请选择</option>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-110">供应商类型</label>
                <select name="PdSupplier[supplier_type]" id="supplier_type" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['supplierType'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
                <label class="width-110">供应商来源</label>
                <select name="PdSupplier[supplier_source]" id="supplier_source" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['supplierSource'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-110">供应商地址</label>
                <select name="" id="disName_1" class="width-100 disName">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['country'] as $val) { ?>
                        <option value="<?= $val['district_id'] ?>"><?= $val['district_name'] ?></option>
                    <?php } ?>
                </select>
                <select name="" id="disName_2" class="width-100 disName">
                    <option value="">请选择</option>
                </select>
                <select name="" id="disName_3" class="width-100 disName">
                    <option value="">请选择</option>
                </select>
                <select name="PdSupplier[district_id]" class="width-100">
                    <option value="">请选择</option>
                </select>
                <input type="text" name="PdSupplier[supplier_compaddress]" style="width: 420px;">
            </div>
            <div style="padding:0 20px 5px;">拟采购商品</div>
            <div style="padding:0 20px 20px;">
                <table class="table-small" style="width:100%;">
                    <thead>
                    <tr>
                        <th style="width:30px;">序号</th>
                        <th style="width:160px;">商品料号</th>
                        <th style="width:160px;">商品名称</th>
                        <th style="width:160px;">规格型号</th>
                        <th style="width:160px;">商品类型</th>
                        <th style="width:160px;">单位</th>
                        <th><a id="add_product">+添加</a></th>
                    </tr>
                    </thead>
                    <tbody id="product_tbody"></tbody>
                </table>
            </div>
            <div class="mb-20">
                <label class="width-110">预计年销售额</label>
                <input type="text" class="width-200" name="PdSupplier[supplier_pre_annual_sales]">
                <label class="width-110">预计年销售利润</label>
                <input type="text" class="width-200" name="PdSupplier[supplier_pre_annual_profit]">
                <label class="width-110">Source类别</label>
                <select name="PdSupplier[source_type]" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['sourceType'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-110 vertical-top">外部目标客户</label>
                <textarea name="PdSupplier[outer_cus_object]" style="width: 834px;height: 50px;"></textarea>
            </div>
            <div class="mb-20">
                <label class="width-110 vertical-top">客户等级品质要求</label>
                <textarea name="PdSupplier[cus_quality_require]" style="width: 834px;height: 50px;"></textarea>
            </div>
            <div style="padding:0 10px 20px;">
                <div class="overflow-auto">
                    <div id="supplier_main_goods" style="line-height:30px;width:100px;text-align:center;cursor:pointer;border:1px solid #1f7ed0;border-bottom:none;float:left;color:#1f7ed0;">供应商主营商品</div>
                    <div id="supplier_contact_person" style="line-height:30px;width:100px;text-align:center;cursor:pointer;border-bottom:1px solid #1f7ed0;float:left;">供应商联系信息</div>
                    <div style="height:30px;width:746px;border-bottom:1px solid #1f7ed0;float:left;"></div>
                </div>
                <div style="border:1px solid #1f7ed0;border-top:none;padding:10px;">
                    <table id="supplier_main_goods_tab" class="table-small" style="width:100%;">
                        <thead>
                        <tr>
                            <th style="width:30px;">序号</th>
                            <th style="width:115px;">主营项目</th>
                            <th style="width:115px;">商品优势与不足</th>
                            <th style="width:115px;">销售渠道与区域</th>
                            <th style="width:115px;">年销售量(单位)</th>
                            <th style="width:115px;">市场份额(%)</th>
                            <th style="width:115px;">是否公开销售</th>
                            <th style="width:115px;">是否代理</th>
                            <th><a id="add_supplier_main_goods">+添加</a></th>
                        </tr>
                        </thead>
                        <tbody id="supplier_main_goods_tbody"></tbody>
                    </table>
                    <div class="display-none" id="supplier_main_goods_popup">
                        <h1 class="head-first">新增主营商品</h1>
                        <div class="mb-20">
                            <label class="width-100">主营商品</label>
                            <input id="main_goods" type="text" class="width-150">
                        </div>
                        <div class="mb-20">
                            <label class="width-100 vertical-top">商品的优势与不足</label>
                            <textarea id="goods_advantage" style="width:407px;height:50px;"></textarea>
                        </div>
                        <div class="mb-20">
                            <label class="width-100 vertical-top">销售渠道与区域</label>
                            <textarea id="sale_channel" style="width:407px;height:50px;"></textarea>
                        </div>
                        <div class="mb-20">
                            <label class="width-100">年销售量(单位)</label>
                            <input id="sale_number" type="text" class="width-150">
                            <label class="width-100">市场份额(%)</label>
                            <input id="market_quotient" type="text" class="width-150">
                        </div>
                        <div class="mb-20">
                            <label class="width-100">是否公开销售</label>
                            <select id="is_sale" class="width-150">
                                <option value="">请选择</option>
                                <option value="1">是</option>
                                <option value="2">否</option>
                            </select>
                            <label class="width-100">能否代理</label>
                            <select id="is_agency" class="width-150">
                                <option value="">请选择</option>
                                <option value="3">是</option>
                                <option value="4">否</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button id="supplier_main_goods_confirm" type="button" class="button-blue">确定</button>
                            <button id="supplier_main_goods_cancel" type="button" class="button-blue ml-10" style="background-color:white;border:1px solid #1f7ed0;color:#1f7ed0;">取消</button>
                        </div>
                    </div>
                    <table id="supplier_contact_person_tab" class="table-small" style="width:100%;display:none;">
                        <thead>
                        <tr>
                            <th style="width:30px;">序号</th>
                            <th style="width:115px;">联系人姓名</th>
                            <th style="width:115px;">性别</th>
                            <th style="width:115px;">年龄</th>
                            <th style="width:115px;">职务</th>
                            <th style="width:115px;">电话</th>
                            <th style="width:115px;">手机</th>
                            <th style="width:115px;">邮箱</th>
                            <th><a id="add_supplier_contact_person">+添加</a></th>
                        </tr>
                        </thead>
                        <tbody id="supplier_contact_person_tbody"></tbody>
                    </table>
                </div>
            </div>
            <h2 class="head-second">代理事项</h2>
            <div class="mb-20">
                <label class="width-110"><span class="red">*</span>谈判日期</label>
                <input type="text" class="width-200 select-date" name="" readonly="readonly" placeholder="请点击选择日期">
                <label class="width-110"><span class="red">*</span>谈判地点</label>
                <input type="text" class="width-200" name="">
            </div>
            <div class="mb-20">
                <label class="width-110"><span class="red">*</span>厂商主谈人</label>
                <input type="text" class="width-200" name="">
                <label class="width-110"><span class="red">*</span>职位</label>
                <input type="text" class="width-200" name="">
                <label class="width-110"><span class="red">*</span>厂商联系电话</label>
                <input type="text" class="width-200" name="">
            </div>
            <div class="mb-20">
                <label class="width-110"><span class="red">*</span>商品经理人</label>
                <input type="text" class="width-200" readonly="readonly">
                <label class="width-110">职位</label>
                <input type="text" class="width-200" readonly="readonly">
                <label class="width-110">联系电话</label>
                <input type="text" class="width-200" readonly="readonly">
            </div>
            <div style="padding:0 30px 20px;">
                <div style="margin:0 0 5px;">陪同人信息</div>
                <table class="table-small" style="width:100%;">
                    <thead>
                    <tr>
                        <th>工号</th>
                        <th>姓名</th>
                        <th>职位</th>
                        <th>联系电话</th>
                        <th><a id="add_accompany_person">+添加</a></th>
                    </tr>
                    </thead>
                    <tbody id="accompany_person_tbody">
                    <tr>
                        <td class="width-200"><input type="text" class="no-border text-center accompany_person_code" style="width:100%;" placeholder="请点击输入工号" onblur="loadAccompanyPerson(this)"></td>
                        <td class="width-200"></td>
                        <td class="width-200"></td>
                        <td class="width-200"></td>
                        <td><a onclick="resetAccompanyPerson(this)">重置</a>&nbsp;<a onclick="deleteAccompanyPerson(this)">删除</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-20">
                <label class="width-110">代理等级</label>
                <select name="" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['agentLevel'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
                <label class="width-200">授权区域范围</label>
                <select name="" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['authorizationAreaScope'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-110">销售范围</label>
                <select name="" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['saleScope'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
                <label class="width-200">授权日期</label>
                <input type="text" class="select-date" readonly="readonly" style="width:90px;">
                <label class="no-after">至</label>
                <input type="text" class="select-date" readonly="readonly" style="width:91px;">
            </div>
            <div class="mb-20">
                <label class="width-110">结算方式</label>
                <select name="" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['agentLevel'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
                <label class="width-200">交期</label>
                <input type="text" class="width-200 easyui-validatebox" data-options="validType:'length[0,2]'">
            </div>
            <div class="mb-20">
                <label class="width-110">售后服务</label>
                <select name="" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['saleServer'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
                <label class="width-200">物流配送</label>
                <select name="" class="width-200">
                    <option value="">请选择</option>
                    <?php foreach ($supplierData['logisticsDelivery'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="text-center">
                <button type="button" class="button-white-big" id="prev_step">上一步</button>
                <button type="submit" class="button-blue-big ml-10" id="save">保存</button>
                <button type="button" class="button-blue-big ml-10">提交</button>
                <button type="button" class="button-white-big ml-10">取消</button>
            </div>
        </div>
        <!--    供应商申请结束-->
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    var $proTr=null;
    var aa=0;
    var m=100;
    //删除tr行,此方法在add-product页面中使用
    function deleteTr(obj){
        $(obj).parents("tr").remove();
        $("#product_tbody tr").each(function(i){
            $(this).children("td:first").text(i+1);
        });
    }
    //删除陪同人
    function deleteAccompanyPerson(obj){
        var b = $("#accompany_person_tbody tr").length;
        if(b>1){
            $(obj).parents("tr").remove();
        }
    }

    //重置陪同人
    function resetAccompanyPerson(obj){
        $(obj).parents("tr").find("input").val("");
        $(obj).parents("tr").find("td:eq(1)").text("");
        $(obj).parents("tr").find("td:eq(2)").text("");
        $(obj).parents("tr").find("td:eq(3)").text("");
    }

    //加载陪同人
    function loadAccompanyPerson(obj){
        var code = $(obj).val();
        if (code == '') {
            return false;
        }
        $.ajax({
            type: 'get',
            dataType: 'json',
            data: {"id": code},
            url: "<?= Url::to(['/ptdt/visit-resume/load-accompany-person']) ?>",
            success:function(data){
                if (data == false) {
                    layer.alert("请输入正确的工号！",{icon:2,time:5000});
                } else {
                    $(obj).parents("tr").find("td:eq(1)").text(data.staff_name);
                    $(obj).parents("tr").find("td:eq(2)").text(data.job_task);
                    $(obj).parents("tr").find("td:eq(3)").text(data.staff_mobile);
                }
            }
        })
    }
    $(function () {
//        ajaxSubmitForm($("#add_form"));
        //厂商评鉴申请和供应商申请切换
        $(document).on("click","#evaluate_apply_button,#prev_step",function () {
            $("#evaluate_apply_button").css({"border":"1px solid #1f7ed0","border-bottom":"none","color":"#1f7ed0"}).siblings().css({"border":"none","border-bottom":"1px solid #1f7ed0","color":"black"});
            $("#add_supplier").hide();
            $("#add_evaluate_apply").show();
            setMenuHeight();
        });
        $(document).on("click","#supplier_button,#next_step",function () {
            $("#supplier_button").css({"border":"1px solid #1f7ed0","border-bottom":"none","color":"#1f7ed0"}).siblings().css({"border":"none","border-bottom":"1px solid #1f7ed0","color":"black"});
            $("#add_evaluate_apply").hide();
            $("#add_supplier").show();
            setMenuHeight();
        });
        //验证
        $("#firm_sname").validatebox({
            required: true
        });

        //评鉴申请
        var $evaluateApply = $("input[name='PdFirmEvaluateApply[apply_type]']:first");
        var $avoidEvaluateApply = $("input[name='PdFirmEvaluateApply[apply_type]']:last");
        var $evaluateApplyDiv = $(".evaluate-apply");
        var $avoidEvaluateApplyDiv = $(".avoid-evaluate-apply");
        //默认选择评鉴申请隐藏免评鉴申请
        $evaluateApply.attr("checked","checked");
        $avoidEvaluateApplyDiv.hide();
        //点击切换评鉴类型
        $evaluateApply.click(function () {
            $avoidEvaluateApplyDiv.hide().find("input").val("").attr("checked",false);
            $evaluateApplyDiv.show();
        });
        $avoidEvaluateApply.click(function () {
            $evaluateApplyDiv.hide().find("input").val("");
            $avoidEvaluateApplyDiv.show();
        });
        //选择厂商
        $("#select_firm").fancybox({
            padding     : [],
            width		: 800,
            height		: 530,
            autoSize	: false,
            type        : 'iframe',
            href        : '<?= Url::to(['select-firm']) ?>'
        });

        //供应商申请
        function clearOption($select) {
            if ($select == null) {
                $select = $("#disName_1")
            }
            $tagNmae = $select.next().prop("tagName");
            if ($select.next().length != 0 && $tagNmae =='SELECT') {
                $select.next().html('<option value=>请选择</option>');
                clearOption($select.next());
            }
        }
        function getNextDistrict($select) {
            var id = $select.val();
            if (id == "") {
                clearOption($select);
                return;
            }
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"id": id},
                url: "<?=Url::to(['/ptdt/firm/get-district']) ?>",
                success: function (data) {
                    var $nextSelect = $select.next("select");
                    clearOption($nextSelect);
                    $nextSelect.html('<option value>请选择</option>')
                    if ($nextSelect.length != 0)
                        for (var x in data) {
                            $nextSelect.append('<option value="' + data[x].district_id + '" >' + data[x].district_name + '</option>')
                        }
                }
            })
        }
        //地址联动查询
        $(".disName").change(function () {
            var $select = $(this);
            getNextDistrict($select);
        });
        //添加采购商品
        $("#add_product").fancybox({
            padding     : [],
            width		: 800,
            height		: 530,
            autoSize	: false,
            type        : 'iframe',
            href        : '<?= Url::to(['add-product']) ?>',
            fitToView	: false
        }).click(function(){
            $proTr=null;
        });
        $(".edit_get_product,.edit_add_product").fancybox({
            padding     : [],
            width		: 800,
            height		: 530,
            autoSize	: false,
            type        : 'iframe',
            href        : '<?= Url::to(['add-product']) ?>',
            fitToView	: false
        });
        $("#product_tbody").on("click",".edit_get_product",function(){
            $proTr=$(this).parents("tr");
        });
        $("#product_tbody").on("click",".edit_add_product",function(){
            $proTr=$(this).parents("tr");
            aa=1;
        });
        //供应商主营商品/供应商联系信息
        $("#supplier_main_goods").click(function () {
            $(this).css({"border":"1px solid #1f7ed0","border-bottom":"none","color":"#1f7ed0"}).siblings().css({"border":"none","border-bottom":"1px solid #1f7ed0","color":"black"});
            $("#supplier_contact_person_tab").hide();
            $("#supplier_main_goods_tab").show();
        });
        $("#supplier_contact_person").click(function () {
            $(this).css({"border":"1px solid #1f7ed0","border-bottom":"none","color":"#1f7ed0"}).siblings().css({"border":"none","border-bottom":"1px solid #1f7ed0","color":"black"});
            $("#supplier_main_goods_tab").hide();
            $("#supplier_contact_person_tab").show();
        });
        //供应商主营商品弹窗
        var $currentTr=null;
        $("#add_supplier_main_goods").fancybox({
            padding     : [],
            width		: 520,
            height		: 370,
            autoSize	: false,
            href        : '#supplier_main_goods_popup'
        }).click(function(){
            $("#main_goods,#goods_advantage,#sale_channel,#sale_number,#market_quotient,#is_sale,#is_agency").val("");
        });
        $("#supplier_main_goods_cancel").hover(
            function () {
                $(this).css("border","2px solid black");
            },
            function () {
                $(this).css("border","1px solid #1f7ed0");
            }
        ).click(function () {
            parent.$.fancybox.close();
        });
        $("#supplier_main_goods_confirm").click(function () {
            var a=$("#is_sale").val()==""?"":$("#is_sale").children(":selected").text();
            var b=$("#is_agency").val()==""?"":$("#is_agency").children(":selected").text();
            var goodsTr = "<tr>";
            goodsTr += "<td></td>";
            goodsTr += "<td>" + $("#main_goods").val() + "</td>";
            goodsTr += "<td>" + $("#goods_advantage").val() + "</td>";
            goodsTr += "<td>" + $("#sale_channel").val() + "</td>";
            goodsTr += "<td>" + $("#sale_number").val() + "</td>";
            goodsTr += "<td>" + $("#market_quotient").val() + "</td>";
            goodsTr += "<td>" +a+ "</td>";
            goodsTr += "<td>" +b+ "</td>";
            goodsTr += "<td><a class='edit_main_goods'>修改</a>&nbsp;<a class='delete_main_goods'>删除</a></td>";
            goodsTr += "</tr>";
            parent.$.fancybox.close();
            if($currentTr==null){
                $("#supplier_main_goods_tbody").append(goodsTr);
            }else{
                $currentTr.replaceWith(goodsTr);
                $currentTr=null;
            }
            $("#supplier_main_goods_tbody tr").each(function(i){
                $(this).children("td:first").text(i+1);
            });
            $(".delete_main_goods").on("click",function () {
                $(this).parents("tr").remove();
                $("#supplier_main_goods_tbody tr").each(function(i){
                    $(this).children("td:first").text(i+1);
                });
            });
            $(".edit_main_goods").fancybox({
                padding     : [],
                width		: 520,
                height		: 370,
                autoSize	: false,
                href        : '#supplier_main_goods_popup'
            }).on("click",function(){
                $currentTr=$(this).parents("tr");
                $("#main_goods").val($currentTr.children("td:eq(1)").text());
                $("#goods_advantage").val($currentTr.children("td:eq(2)").text());
                $("#sale_channel").val($currentTr.children("td:eq(3)").text());
                $("#sale_number").val($currentTr.children("td:eq(4)").text());
                $("#market_quotient").val($currentTr.children("td:eq(5)").text());
                $("#is_sale option").each(function(){
                    if($(this).text()==$currentTr.children("td:eq(6)").text()){
                        $(this).prop("selected",true);
                    }
                });
                $("#is_agency option").each(function(){
                    if($(this).text()==$currentTr.children("td:eq(7)").text()){
                        $(this).prop("selected",true);
                    }
                });
            });
        });
        //供应商联系人
        var k=100;
        $("#add_supplier_contact_person").click(function(){
            var contactTr="<tr>";
            contactTr+="<td></td>";
            contactTr+="<td><input type='text' name='PdVendorconetionPersion["+k+"][vcper_name]' style='width:100%;' class='no-border text-center easyui-validatebox' data-options='validType:\"length[0,4]\"' placeholder='请点击输入姓名'></td>";
            contactTr+="<td><select name='PdVendorconetionPersion["+k+"][vcper_sex]'><option value=''>请选择</option><option value=''>男</option><option value=''>女</option></select></td>";
            contactTr+="<td><input type='text' name='PdVendorconetionPersion["+k+"][vcper_age]' style='width:100%;' class='no-border text-center' placeholder='请点击输入年龄'></td>";
            contactTr+="<td><input type='text' name='PdVendorconetionPersion["+k+"][vcper_post]' style='width:100%;' class='no-border text-center' placeholder='请点击输入职务'></td>";
            contactTr+="<td><input type='text' name='PdVendorconetionPersion["+k+"][vcper_tel]' style='width:100%;' class='no-border text-center' placeholder='请点击输入电话'></td>";
            contactTr+="<td><input type='text' name='PdVendorconetionPersion["+k+"][vcper_mobile]' style='width:100%;' class='no-border text-center' placeholder='请点击输入手机'></td>";
            contactTr+="<td><input type='text' name='PdVendorconetionPersion["+k+"][vcper_mail]' style='width:100%;' class='no-border text-center' placeholder='请点击输入邮箱'></td>";
            contactTr+="<td><a class='reset_contact_person'>重置</a>&nbsp;<a class='delete_contact_person'>删除</a></td>";
            contactTr+="</tr>";
            var $contactPersonTr=$("#supplier_contact_person_tbody").append(contactTr);
            $.parser.parse($contactPersonTr);//重要，js动态添加easyui-validatebox需要单独解析
            k++;
            $("#supplier_contact_person_tbody tr").each(function(i){
                $(this).children("td:first").text(i+1);
            });
            $(".reset_contact_person").on("click",function(){
                $(this).parents("tr").find("input,select").val("");
            });
            $(".delete_contact_person").on("click",function () {
                $(this).parents("tr").remove();
                $("#supplier_contact_person_tbody tr").each(function(i){
                    $(this).children("td:first").text(i+1);
                });
            });

        });
        //陪同人
        var j = 100;
        $("#add_accompany_person").click(function() {
            var accompanyTr = "<tr>";
            accompanyTr += '<td class="width-200">';
            accompanyTr += '<input onblur="loadAccompanyPerson(this)" type="text" class="width-200  no-border text-center" name="PdAccompany[' + j + '][staff_code]" placeholder="请点击输入工号">';
            accompanyTr += "</td>";
            accompanyTr += '<td class="width-200">&nbsp;</td>';
            accompanyTr += '<td class="width-200">&nbsp;</td>';
            accompanyTr += '<td class="width-200">&nbsp;</td>';
            accompanyTr += "<td>";
            accompanyTr += '<a onclick="resetAccompanyPerson(this)" style="cursor: pointer;">重置</a>&nbsp;';
            accompanyTr += '<a onclick="deleteAccompanyPerson(this)" style="cursor: pointer;">删除</a>';
            accompanyTr += "</td>";
            accompanyTr += "</tr>";
            $('#accompany_person_tbody').append(accompanyTr);
            j++;
        });
    });
</script>