<?php
/**
 * 新增
 * F3859386
 * 2016/10/25
 */
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<style>
    /*.fancybox-skin{*/
        /*padding:0px !important;*/
    /*}*/
</style>
<div class="content">
    <h1 class="head-first">供应商申请</h1>
    <?php  $form = ActiveForm::begin([
        'id'=>'add-form'
    ]); ?>
    <label class="width-120" for="pdsupplier-supplier_category_id">Commodity</label>
    <input id="pdsupplier-supplier_category_id" class="width-170 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_category_id]" value="<?=$supplier['category_id'] ?>">
    <label class="width-120" for="pdsupplier-supplier_comptype">新增类别</label>
    <select id="pdsupplier-supplier_comptype" class="form-control easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_add_type]">
        <option value="">请选择...</option>
        <?php foreach ($downList['supplierComptype'] as $val){?>
        <option value="<?= $val['bsp_id'] ?>" <?= $supplier['add_type']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
        <?php }?>
    </select>
    <label class="width-150" for="pdsupplier-supplier_issupplier">供应商状态</label>
    <select id="pdsupplier-supplier_issupplier" class="form-control easyui-validatebox"  data-options="required:true"  name="PdSupplier[supplier_issupplier]">
        <option value="">请选择...</option>
        <option value=1 <?= $supplier['issupplier']===1 ? "selected":null ?>>是  </option>
        <option value=0 <?= $supplier['issupplier']=== 0 ? "selected":null ?>>否 </option>
    </select>
<div class="mt-20">
    <div class="inline-block ml-30 field-pdsupplier-supplier_sname">
        <label class="width-90" style="text-align: justify;" for="pdsupplier-supplier_sname">供应商</label>
        <input type="text" id="pdsupplier-supplier_sname" class="form-control" name="PdSupplier[supplier_sname]" value="<?=$supplier['sname'] ?>">
    </div> <span class="width-50"><a class='fancybox.ajax' id='select-com' href="<?= Url::to(['/ptdt/supplier/select-com'])?>">选择厂商</a></span>
    <div class="inline-block field-pdsupplier-firm_id">
        <input type="hidden" id="pdsupplier-firm_id" class="form-control" name="PdSupplier[firm_id]" value="<?= $supplier['firm_id']?>">
    </div>
    <div class="inline-block ml-150 field-pdsupplier-supplier_shortname">
        <label class="width-80" for="pdsupplier-supplier_shortname">供应商简称</label>
        <input type="text" id="pdsupplier-supplier_shortname" class="form-control" name="PdSupplier[supplier_shortname]" value="<?= $supplier['shortname']?>">
    </div>
</div>
<div class="mt-20">
    <div class="inline-block ml-20 field-pdsupplier-supplier_group_sname">
        <label class="width-100" for="pdsupplier-supplier_group_sname">供应商集团简称</label>
        <input type="text" id="pdsupplier-supplier_group_sname" class="form-control" name="PdSupplier[supplier_group_sname]" value="<?=$supplier['group_sname'] ?>">
    </div>
    <div class="inline-block ml-200 field-pdsupplier-supplier_brand">
        <label class="width-80" for="pdsupplier-supplier_brand">品牌</label>
        <input type="text" id="pdsupplier-supplier_brand" class="form-control" name="PdSupplier[supplier_brand]" value="<?=$supplier['brand']?>">
    </div>
</div>
<div class="mt-20">
    <div class="inline-block ml-30 field-pdsupplier-supplier_compaddress">
        <label class="width-90" for="pdsupplier-supplier_compaddress">供应商地址</label>
        <input type="text" id="pdsupplier-supplier_compaddress" class="width-650" name="PdSupplier[supplier_compaddress]" value="<?=$supplier['compaddress']?>">

    </div>
</div>
<div class="mt-20">
    <div class="inline-block ml-30 field-pdsupplier-supplier_main_product">
        <label class="width-90 vertical-top" for="pdsupplier-supplier_main_product">主营范围</label>
        <textarea id="pdsupplier-supplier_main_product" class="width-650" name="PdSupplier[supplier_main_product]" rows="4" ><?=$supplier['main_product'] ?></textarea>

    </div>
</div>
<div class="mt-20">
    <div class="inline-block field-pdsupplier-supplier_position">
        <label class="width-90 ml-30" for="pdsupplier-supplier_position">供应商地位</label>
        <select id="pdsupplier-supplier_position" class="inline-block width-170 easyui-validatebox"  data-options="required:true"  name="PdSupplier[supplier_position]">
        <option value="">请选择...</option>
        <?php foreach ($downList['firmLevel'] as $val){?>
            <option value="<?= $val['bsp_id'] ?>" <?= $supplier['position']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
        <?php }?>
        </select>

    </div>
    <div class="inline-block ml-30 field-pdsupplier-supplier_annual_turnover">
        <label class="width-120" for="pdsupplier-supplier_annual_turnover">年度营业额(USD)</label>
        <input type="text" id="pdsupplier-supplier_annual_turnover" class="width-150 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_annual_turnover]" value="<?=$supplier['annual_turnover'] ?>">
    </div>
    <div class="inline-block field-pdsupplier-supplier_trade_currency">
        <label class="width-90" for="pdsupplier-supplier_trade_currency">交易币别</label>
        <select id="pdsupplier-supplier_trade_currency" class="inline-block width-150 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_trade_currency]">
            <option value="">请选择...</option>
            <?php foreach ($downList['currency'] as $val){?>
                <option value="<?= $val['cur_id'] ?>" <?= $supplier['trace_currency']==$val['cur_id']?"selected":null ?>><?= $val['cur_sname'] ?></option>
            <?php }?>
        </select>
    </div>
</div>
<div class="mt-20">
    <div class="inline-block field-pdsupplier-supplier_trade_condition">
        <label class="width-100 ml-20" for="pdsupplier-supplier_trade_condition">交货条件</label>
        <select id="pdsupplier-supplier_trade_condition" class="inline-block width-170 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_trade_condition]">
            <option value="">请选择...</option>
            <?php foreach ($downList['devcon'] as $val){?>
                <option value="<?= $val['dec_id'] ?>" <?= $supplier['trace_condition']==$val['dec_id']?"selected":null ?>><?= $val['dec_sname'] ?></option>
            <?php }?>
        </select>

    </div>
    <div class="inline-block field-pdsupplier-supplier_pay_condition">
        <label class="width-100" for="pdsupplier-supplier_pay_condition">付款条件</label>
        <select id="pdsupplier-supplier_pay_condition" class="inline-block width-170 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_pay_condition]">
            <option value="">请选择...</option>
            <?php foreach ($downList['payment'] as $val){?>
                <option value="<?= $val['pac_id']?>" <?= $supplier['pay_condition']==$val['pac_id']?"selected":null ?>><?= $val['pac_sname'] ?></option>
            <?php }?>
        </select>

    </div>
</div>
<div class="mt-20">
    <div class="inline-block field-pdsupplier-supplier_type">
        <label class="width-100 ml-20" for="pdsupplier-supplier_type">供应商类型</label>
        <select id="pdsupplier-supplier_type" class="inline-block width-170 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_type]">
            <option value="">请选择...</option>
            <?php foreach ($downList['firmType'] as $val){?>
                <option value="<?=$val['bsp_id']?>" <?= $supplier['type']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
            <?php }?>
        </select>

    </div>
    <div class="inline-block field-pdsupplier-supplier_source">
        <label class="width-100" for="pdsupplier-supplier_source">供应商来源</label>
        <select id="pdsupplier-supplier_source" class="inline-block width-170 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_source]">
            <option value="">请选择...</option>
            <?php foreach ($downList['firmSource'] as $val){?>
                <option value="<?= $val['bsp_id'] ?>" <?= $supplier['source']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
            <?php }?>
        </select>

    </div>
</div>
<div class="mt-20">
    <span class="ml-50 ">拟采购商品</span>
</div>
<table class="table-small">
    <thead>
    <tr>
        <th>
            商品料号
        </th>
        <th>
            商品名称
        </th>
        <th>
            规格型号
        </th>
        <th>
            商品类型
        </th>
        <th>
            单位
        </th>
        <th>
            操作
        </th>
    </tr>
    </thead>
    <?php if(isset($materialList)){foreach($materialList as $kye=>$val){?>
        <tr>
            <input type="hidden" name="material[]" value="<?= $val['m_id'] ?>">
            <td><?= $val['material_code'] ?></td>
            <td><?= $val['pro_name'] ?></td>
            <td><?= $val['pro_size'] ?></td>
            <td><?= $val['pro_main_type_id'] ?></td>
            <td><?= $val['pro_sku'] ?></td>
            <td><a onclick="delTr(this)">删除</a></td>
        </tr>
    <?php }}?>
    <tbody id="material_body">
    <tr class="material_body_tr">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><a class='fancybox.ajax' id='select-product' href="<?= Url::to(['/ptdt/supplier/select-material'])?>">添加</a></td>
    </tr>
    </tbody>
</table>
<div class="mt-20">
    <div class="inline-block ml-20 field-pdsupplier-supplier_pre_annual_sales">
        <label class="width-100" for="pdsupplier-supplier_pre_annual_sales">预计年销售额</label>
        <input type="text" id="pdsupplier-supplier_pre_annual_sales" class="form-control easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_pre_annual_sales]" value="<?= $supplier['pre_annual_sales']?>">

    </div>
    <div class="inline-block ml-20 field-pdsupplier-supplier_pre_annual_profit">
        <label class="width-100" for="pdsupplier-supplier_pre_annual_profit">预计年利润</label>
        <input type="text" id="pdsupplier-supplier_pre_annual_profit" class="form-control easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_pre_annual_profit]" value="<?= $supplier['pre_annual_profit']?>">

    </div>
    <div class="inline-block field-pdsupplier-source_type">
        <label class="width-100" for="pdsupplier-source_type">来源类别</label>
        <select id="pdsupplier-source_type" class="inline-block width-170 easyui-validatebox"  data-options="required:true" name="PdSupplier[source_type]">
            <option value="">请选择...</option>
            <?php foreach ($downList['sourceType'] as $val){?>
                <option value="<?= $val['bsp_id'] ?>" <?= $supplier['source_type']==$val['bsp_id']?"selected":null ?>> <?= $val['bsp_svalue'] ?></option>
            <?php }?>
        </select>
    </div>
</div>
<div class="mt-20">
    <div class="inline-block ml-20 field-pdsupplier-outer_cus_object">
        <label class="width-100 vertical-top" for="pdsupplier-outer_cus_object">外部客户目标</label>
        <textarea id="pdsupplier-outer_cus_object" class="width-800 input-obj"  oninput="characterCount(200)" name="PdSupplier[outer_cus_object]" rows="4"><?= $supplier['outer_cus_object'] ?></textarea>
        <span class="count-obj red"><?= strlen($supplier['outer_cus_object']) ?>/200</span>
    </div>
</div>
<div class="mt-20">
    <div class="inline-block ml-20 field-pdsupplier-cus_quality_require">
        <label class="width-100 vertical-top" for="pdsupplier-cus_quality_require">客户品质等级要求</label>
        <textarea id="pdsupplier-cus_quality_require" class="width-800" name="PdSupplier[cus_quality_require]" rows="4"><?= $supplier['cus_quality_require'] ?></textarea>
    </div>
</div>
<div class="mt-20 ml-30">
    <div class="easyui-tabs" style="width:900px;">
        <div title="供应商主营商品" style="padding:10px">
            <input type="hidden" name="delMain" id="delMain">
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
                    <th>
                        操作
                    </th>
                </tr>
                </thead>
                <?php if(isset($mainList)){foreach($mainList as $val){?>
                    <tr>
                        <td class="display-none"><?= $val['vmainl_id'] ?></td>
                        <td><?= $val['vmainl_product'] ?></td>
                        <td><?= $val['vmainl_superiority'] ?></td>
                        <td><?= $val['vmainl_salesway'] ?></td>
                        <td><?= $val['vmainl_yqty'] ?></td>
                        <td><?= $val['vmainl_marketshare'] ?></td>
                        <td><?= $val['vmainl_isopensale']==1?'Y':'N' ?></td>
                        <td><?= $val['vmainl_isagent']==1?'Y':'N' ?></td>
                        <td><a onclick="delMainTr(this)">删除</a></td>
                    </tr>
                <?php }}?>
                <tbody id="main_body">
                <tr class="main_body_tr">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><a id="addPd" href="#inline">添加</a></td>
                </tr>
                </tbody>
            </table>

            <div id="inline" class="display-none">
                <div class="pop-head">
                    <p>主营商品</p>
                </div>
                <div class="content">
                    <div class="mb-20">
                        <label class="width-150" >主营商品</label>
                        <input type="text" id="vmainl_product" maxlength="60" class="width-200">
                    </div>
                    <div class="mb-20">
                        <label class="width-150" >商品优势与不足</label>
                        <input type="text" id="vmainl_superiority" class="width-150">
                        <label class="width-150" >销售渠道与区域</label>
                        <input type="text" id="vmainl_salesway" class="width-150">
                    </div>
                    <div class="mb-20">
                        <label class="width-150" >年销售量</label>
                        <input type="text" id="vmainl_yqty" class="width-150">
                        <label class="width-150" >市场份额</label>
                        <input type="text" id="vmainl_marketshare" class="width-150">
                    </div>
                    <div class="mb-20">
                        <label class="width-150" >是否公开销售(Y/N)</label>
                        <select class="width-150" id="vmainl_isopensale">
                            <option value="1">Y</option>
                            <option value="0">N</option>
                        </select>
                        <label class="width-150" >是否代理(Y/N)</label>
                        <select class="width-150" id="vmainl_isagent">
                            <option value="1">Y</option>
                            <option value="0">N</option>
                        </select>
                    </div>
                    <div class="mb-20 text-center" >
                        <button class="button-blue-big" type="button" id="save-button">提交</button>
                        <button class="button-white-big ml-20 close" type="button" >取消</button>
                    </div>
                </div>
            </div>
        </div>
        <div title="供应商联系人" style="padding:10px">
            <input type="hidden" name="delPersion" id="delPersion">
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
                    <th>
                        操作
                    </th>
                </tr>
                </thead>

                <?php if(isset($persionList)){foreach($persionList as $val){?>
                    <tr>
                        <td class="display-none"><?= $val['vcper_id'] ?></td>
                        <td><?= $val['vcper_name'] ?></td>
                        <td><?= $val['vcper_sex'] ?></td>
                        <td><?= $val['vcper_post'] ?></td>
                        <td><?= $val['vcper_tel'] ?></td>
                        <td><?= $val['vcper_mobile'] ?></td>
                        <td><?= $val['vcper_mail'] ?></td>
                        <td><a onclick="delPersionTr(this)">删除</a></td>
                    </tr>
                <?php }}?>
                <tbody id="persion_body">
                <tr class="persion_tr">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><a id="addPersion" href="#persion">添加</a></td>
                </tr>
                </tbody>
            </table>
            <div id="persion" class="display-none">
                <div class="pop-head">
                    <p>联系人</p>
                </div>
                <div class="content">
                    <div class="mb-20">
                        <label class="width-100" >姓名</label>
                        <input type="text" id="vcper_name" class="width-150">
                        <label class="width-100" >性别</label>
                        <input type="text" id="vcper_sex" class="width-150">
                    </div>
                    <div class="mb-20">
                        <label class="width-100" >职务</label>
                        <input type="text" id="vcper_post" class="width-150">
                        <label class="width-100" >电话</label>
                        <input type="text" id="vcper_tel" class="width-150">
                    </div>
                    <div class="mb-20">
                        <label class="width-100" >手机</label>
                        <input type="text" id="vcper_mobile" class="width-150">
                        <label class="width-100" >邮箱</label>
                        <input type="text" id="vcper_mail" class="width-150">
                    </div>
                    <div class="mb-20 text-center" >
                        <button class="button-blue-big" type="button" id="save-persion">提交</button>
                        <button class="button-white-big ml-20 close" type="button" >取消</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<h2 class="head-second mt-20">代理事项</h2>
<div class="mt-20">
    <div class="inline-block ml-20 field-pdsupplier-supplier_is_agents">
        <label class="width-120" for="pdsupplier-supplier_is_agents">是否取得代理授权</label>
        <select id="pdsupplier-supplier_is_agents" name="PdSupplier[supplier_is_agents]" style="width:83px" class="easyui-validatebox"  data-options="required:true">
            <option value="" >请选择...</option>
            <option value="1" <?= $supplier['is_agents']===1 ? "selected":null ?>>是</option>
            <option value="0" <?= $supplier['is_agents']===0 ? "selected":null ?>>否</option>
        </select>

    </div>
    <div class="inline-block field-pdsupplier-supplier_authorize_bdate">
        <label class="width-120" for="pdsupplier-supplier_authorize_bdate">授权开始日期</label>
        <input type="text" id="pdsupplier-supplier_authorize_bdate" class="width-120 select-date easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_authorize_bdate]" value="<?= $supplier['authorize_bdate']?>">
    </div>
    <div class="inline-block field-pdsupplier-supplier_authorize_edate">
        <label class="no-after" for="pdsupplier-supplier_authorize_edate">~</label>
        <input type="text" id="pdsupplier-supplier_authorize_edate" class="width-120 select-date select-date easyui-validatebox" data-options="required:true"  name="PdSupplier[supplier_authorize_edate]" value="<?= $supplier['authorize_edate']?>">
    </div>
    <div class="inline-block field-pdsupplier-supplier_agentslevel">
        <label class="width-100" for="pdsupplier-supplier_agentslevel">代理等级</label>
        <select id="pdsupplier-supplier_agentslevel" class="inline-block width-150 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_agentslevel]">
            <option value="">请选择...</option>
            <?php foreach ($downList['agentsLevel'] as $val){?>
                <option value="<?= $val['bsp_id'] ?>" <?= $supplier['agentslevel']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
            <?php }?>
        </select>

    </div>
</div>
<div class="mt-20">
    <div class="inline-block ml-20 field-pdsupplier-supplier_transacttype">
        <label class="width-120" for="pdsupplier-supplier_transacttype">授权商品类别</label>
        <select id="pdsupplier-supplier_transacttype" class="inline-block width-170 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_transacttype]">
            <option value="">请选择...</option>
            <?php foreach ($downList['productType'] as $val){?>
                <option value="<?= $val['type_id'] ?>" <?= $supplier['transacttype']==$val['type_id']?"selected":null ?>><?= $val['type_name'] ?></option>
            <?php }?>
        </select>

    </div>
    <div class="inline-block ml-20 field-pdsupplier-supplier_authorize_area">
        <label class="width-120" for="pdsupplier-supplier_authorize_area">授权区域</label>
        <select id="pdsupplier-supplier_authorize_area" class="inline-block width-150 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_authorize_area]">
            <option value="">请选择...</option>
            <?php foreach ($downList['saleArea'] as $val){?>
                <option value="<?= $val['bsp_id'] ?>" <?= $supplier['authorize_area']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
            <?php }?>
        </select>

    </div>
    <div class="inline-block field-pdsupplier-supplier_salarea">
        <label class="width-100" for="pdsupplier-supplier_salarea">授权范围</label>
        <select id="pdsupplier-supplier_salarea" class="inline-block width-150 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_salarea]">
            <option value="">请选择...</option>
            <?php foreach ($downList['authorizeArea'] as $val){?>
                <option value="<?= $val['bsp_id'] ?>" <?= $supplier['salarea']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
            <?php }?>
        </select>

    </div>
</div>
<div class="mt-20">
    <div class="inline-block ml-20 field-pdsupplier-supplier_chief_negotiator">
        <label class="width-120" for="pdsupplier-supplier_chief_negotiator">供应商主谈人</label>
        <input type="text" id="pdsupplier-supplier_chief_negotiator" class="form-control easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_chief_negotiator]" value="<?= $supplier['chief_negotiator']?>">

    </div>
    <div class="inline-block ml-20 field-pdsupplier-fjj_chief_negotiator">
        <label class="width-120" for="pdsupplier-fjj_chief_negotiator">富金机主谈人</label>
        <input type="text" id="pdsupplier-fjj_chief_negotiator" class="form-control easyui-validatebox"  data-options="required:true" name="PdSupplier[fjj_chief_negotiator]"
               value="<?= $supplier['fjj_chief_negotiator']?>">

    </div>
</div>
<div class="mt-20">
    <div class="inline-block ml-20 field-pdsupplier-supplier_chief_post">
        <label class="width-120" for="pdsupplier-supplier_chief_post">供应商主谈人职务</label>
        <input type="text" id="pdsupplier-supplier_chief_post" class="form-control easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_chief_post]"
               value="<?= $supplier['chief_post']?>">

    </div>
    <div class="inline-block ml-20 field-pdsupplier-fjj_chief_extension">
        <label class="width-120" for="pdsupplier-fjj_chief_extension">富金机主谈人分机</label>
        <input type="text" id="pdsupplier-fjj_chief_extension" class="form-control easyui-validatebox"  data-options="required:true" name="PdSupplier[fjj_chief_extension]"
               value="<?= $supplier['fjj_chief_extension']?>">

    </div>
</div>
    <div class="mt-20">
    <div class="inline-block ml-20 field-pdsupplier-requirement_description">
        <label class="width-120 vertical-top" for="pdsupplier-requirement_description">新增需求说明</label>
        <textarea id="pdsupplier-requirement_description" class="width-800 easyui-validatebox"  data-options="required:true" name="PdSupplier[requirement_description]" rows="4"><?= $supplier['requirement_description']?></textarea>

    </div>
    </div>
    <div class="mt-20">
        <div class="inline-block ml-20 field-pdsupplier-supplier_advantage">
            <label class="width-120 vertical-top" for="pdsupplier-supplier_advantage">优势</label>
            <textarea id="pdsupplier-supplier_advantage" class="width-800 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_advantage]" rows="4"><?= $supplier['advantage']?></textarea>
    
        </div>
    </div>
    <div class="mt-20">
        <div class="inline-block ml-20 field-pdsupplier-supplier_business">
            <label class="width-120 vertical-top" for="pdsupplier-supplier_business">商机</label>
            <textarea id="pdsupplier-supplier_business" class="width-800 easyui-validatebox"  data-options="required:true" name="PdSupplier[supplier_business]" rows="4"><?= $supplier['business']?></textarea>
        </div>
    </div>
    <div class="mt-20">
        <div class="inline-block ml-20 field-pdsupplier-supplier_not_accepted">
            <label class="width-120 vertical-top" for="pdsupplier-supplier_not_accepted">未取得受理原因</label>
            <textarea id="pdsupplier-supplier_not_accepted" class="width-800" name="PdSupplier[supplier_not_accepted]" rows="4"><?= $supplier['not_accepted']?></textarea>
        </div>
    </div>
    <div class="space-40"></div>
    <div class="text-center mb-20">
        <button class="button-blue-big" type="submit">提交</button>
        <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">返回</button>
    </div>
    <?php $form->end(); ?>
</div>
    <script>
        $(function(){
        ajaxSubmitForm($("#add-form"));
            //弹出添加商品窗口
            $("#addPd").fancybox({
                'centerOnScroll':true,
                'title':false
            });
            $("#addPersion").fancybox({
                'centerOnScroll':true,
                'title':false
            });

            //选择厂商窗口
            $('#select-com').fancybox(
                {
                    padding : [],
                    fitToView	: false,
                    width		: 800,
                    height		: 500,
                    autoSize	: false,
                    closeClick	: false,
                    openEffect	: 'none',
                    closeEffect	: 'none',
                    type : 'iframe',
                }
            );
            //拟采购商品
            $('#select-product').fancybox(
                {
                    padding : [],
                    fitToView	: false,
                    width		: 800,
                    height		: 500,
                    autoSize	: false,
                    closeClick	: false,
                    openEffect	: 'none',
                    closeEffect	: 'none',
                    type : 'iframe',
                }
            );
            //取消添加商品
            $(".close").click(function(){
                parent.$.fancybox.close();
            });
        });


        //保存主营商品
        var vmainl=<?= isset($mainList)?count($mainList):0 ?>;
        $('#save-button').on('click', function () {
            var vmainl_product=$("#vmainl_product");
            var vmainl_superiority=$("#vmainl_superiority");
            var vmainl_salesway=$("#vmainl_salesway");
            var vmainl_yqty=$("#vmainl_yqty");
            var vmainl_marketshare=$("#vmainl_marketshare");
            var vmainl_isopensale=$("#vmainl_isopensale");
            var vmainl_isagent=$("#vmainl_isagent");
            var tdStr = "<tr>";
            tdStr += "<td>" + vmainl_product.val() + "</td>";
            tdStr += "<td>" + vmainl_superiority.val() + "</td>";
            tdStr += "<td>" + vmainl_salesway.val() +  "</td>";
            tdStr += "<td>" + vmainl_yqty.val() +  "</td>";
            tdStr += "<td>" + vmainl_marketshare.val() +  "</td>";
            tdStr += "<td>" + vmainl_isopensale.find("option:selected").text() + "</td>";
            tdStr += "<td>" + vmainl_isagent.find("option:selected").text() + "</td>";
            tdStr += "<td>" + '<a onclick="delTr(this)">删除</a>' + "</td>";
            tdStr += '<input type="hidden" name="BsVendorMainlist[' + vmainl + '][vmainl_product]" value="' + vmainl_product.val() + '">';
            tdStr += '<input type="hidden" name="BsVendorMainlist[' + vmainl + '][vmainl_superiority]" value="' + vmainl_superiority.val() + '">';
            tdStr += '<input type="hidden" name="BsVendorMainlist[' + vmainl + '][vmainl_salesway]" value="' + vmainl_salesway.val() + '">';
            tdStr += '<input type="hidden" name="BsVendorMainlist[' + vmainl + '][vmainl_yqty]" value="' + vmainl_yqty.val() + '">';
            tdStr += '<input type="hidden" name="BsVendorMainlist[' + vmainl + '][vmainl_marketshare]" value="' + vmainl_marketshare.val() + '">';
            tdStr += '<input type="hidden" name="BsVendorMainlist[' + vmainl + '][vmainl_isopensale]" value="' + vmainl_isopensale.val() + '">';
            tdStr += '<input type="hidden" name="BsVendorMainlist[' + vmainl + '][vmainl_isagent]" value="' + vmainl_isagent.val() + '">';
            tdStr += "</tr>";
            vmainl_product.val("");
            vmainl_superiority.val("");
            vmainl_salesway.val("");
            vmainl_yqty.val("");
            vmainl_marketshare.val("");
            vmainl_isopensale.val("");
            vmainl_isagent.val("");
            $(".main_body_tr").insertAfter($('#main_body'));
            $('#main_body').append(tdStr);
            vmainl++;
            parent.$.fancybox.close();
        });

        //添加联系人信息
        var main=<?= isset($mainList)?count($mainList):0 ?>;
        $('#save-persion').on('click', function () {
            var vcper_name=$("#vcper_name");
            var vcper_sex=$("#vcper_sex");
            var vcper_post=$("#vcper_post");
            var vcper_tel=$("#vcper_tel");
            var vcper_mobile=$("#vcper_mobile");
            var vcper_mail=$("#vcper_mail");
            var tdStr = "<tr>";
            tdStr += "<td>" + vcper_name.val() + "</td>";
            tdStr += "<td>" + vcper_sex.val() + "</td>";
            tdStr += "<td>" + vcper_post.val() +  "</td>";
            tdStr += "<td>" + vcper_tel.val() +  "</td>";
            tdStr += "<td>" + vcper_mobile.val() +  "</td>";
            tdStr += "<td>" + vcper_mail.val() + "</td>";
            tdStr += "<td>" + '<a onclick="delTr(this)">删除</a>' + "</td>";
            tdStr += '<input type="hidden" name="PdVendorconetionPersion[' + main + '][vcper_name]" value="' + vcper_name.val() + '">';
            tdStr += '<input type="hidden" name="PdVendorconetionPersion[' + main + '][vcper_sex]" value="' + vcper_sex.val() + '">';
            tdStr += '<input type="hidden" name="PdVendorconetionPersion[' + main + '][vcper_post]" value="' + vcper_post.val() + '">';
            tdStr += '<input type="hidden" name="PdVendorconetionPersion[' + main + '][vcper_tel]" value="' + vcper_tel.val() + '">';
            tdStr += '<input type="hidden" name="PdVendorconetionPersion[' + main + '][vcper_mobile]" value="' + vcper_mobile.val() + '">';
            tdStr += '<input type="hidden" name="PdVendorconetionPersion[' + main + '][vcper_mail]" value="' + vcper_mail.val() + '">';
            tdStr += "</tr>";
            main++;
            vcper_name.val("");
            vcper_sex.val("");
            vcper_post.val("");
            vcper_tel.val("");
            vcper_mobile.val("");
            vcper_mail.val("");
            $(".persion_tr").insertAfter($('#persion_body'));
            $('#persion_body').append(tdStr);
            parent.$.fancybox.close();
        });

        //删除tr
        var delMain=[];
        function delMainTr(thas){
            delMain.push($(thas).parent().parent().find('.display-none').html());
            $("#delMain").val(delMain);
            $(thas).parent().parent().remove();
        }
        var delPersion=[];
        function delPersionTr(thas){
            delPersion.push($(thas).parent().parent().find('.display-none').html());
            $("#delPersion").val(delPersion);
            $(thas).parent().parent().remove();
        }
        function delTr(thas){
            $(thas).parent().parent().remove();
        }
    </script>
