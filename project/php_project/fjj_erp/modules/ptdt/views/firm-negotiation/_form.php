<?php
/**
 * User: F3859386
 * Date: 2016/12/2
 * Time: 上午 09:06
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

<div class="content">

    <h1 class="head-first">
        <?= Html::encode($this->title) ?>
    </h1>
    <?php $form = ActiveForm::begin([
        'id' => 'add-form',
        "options" => ["enctype" => "multipart/form-data"],
//    'enableAjaxValidation' => true,
//    'validationUrl' => Url::to(['/ptdt/firm-negotiation/validate-form']),
        'fieldConfig' => [
            'template' => "{label}<div class='display-style'>\n{input}\n{error}\n</div>",
//                'options' => ['class' => 'div-row', false],
            'labelOptions' => ['class' => 'width-100'],
            'inputOptions' => ['class' => 'width-200'],
            'errorOptions' => ['class' => 'error-notice'],
        ],
    ]); ?>
    <div class="mb-30">
        <h2 class="head-second">
            厂商基本信息
        </h2>
        <div class="mb-20">
            <input id="firm_id" name="PdNegotiation[firm_id]" type="hidden" value="<?= $firmInfo['firm_id']?>">
            <label class="width-100"><span class="red">*</span>注册公司名称</label>
            <input class="width-200 easyui-validatebox"  data-options="required:true,tipPosition:'top',missingMessage:'请选择谈判厂商'"  id="firm_sname" onfocus=this.blur(); value="<?= $firmInfo['firm_sname']?>">
            <div class="error-notice  firm_error ml-100"></div>
            <span class="width-50"><a  id="select-com">选择厂商</a></span>
            <label style="width:146px;">简称</label>
            <input class="width-200" id="firm_shortname" onfocus=this.blur(); value="<?= $firmInfo['firm_shortname']?>">
        </div>

        <div class="mb-20">
            <label class="width-100">英文全称</label>
            <input class="width-200" id="firm_ename" onfocus=this.blur(); value="<?= $firmInfo['firm_ename']?>">
            <label class="width-200">简称</label>
            <input class="width-200" id="firm_eshortname" onfocus=this.blur(); value="<?= $firmInfo['firm_eshortname']?>">
        </div>
        <div class="mb-20">
            <label class="width-100">品牌(中文)</label>
            <input class="width-200" id="firm_brand" onfocus=this.blur(); value="<?= $firmInfo['firm_brand']?>">
            <label class="width-200">英文</label>
            <input class="width-200" id="firm_brand_english" onfocus=this.blur(); value="<?= $firmInfo['firm_brand_english']?>">
        </div>
        <div class="mb-20">
            <label class="width-100">厂商地址</label>
            <input class="width-800" id="firm_address" onfocus=this.blur(); value="<?=empty($firmInfo)?'':$firmInfo['firmAddress']['fullAddress']?>">
        </div>
        <div class="mb-20">
            <label class="width-100">厂商来源</label>
            <input class="width-200" id="firm_source" onfocus=this.blur(); value="<?=empty($firmInfo)?'':$firmInfo['firmSource']?>">
            <label class="width-70">厂商类型</label>
            <input class="width-200" id="firm_type" onfocus=this.blur(); value="<?=empty($firmInfo)?'':$firmInfo['firmType']?>">
            <label class="width-120">是否为集团供应商</label>
            <input class="width-200" id="firm_issupplier" onfocus=this.blur(); value="<?= $firmInfo['issupplier']?>">
        </div>
        <div>
            <label class="width-100">分级分类</label>
            <input class="width-800" id="firm_category_id" onfocus=this.blur(); value="<?= $firmInfo['category']?>">
        </div>
    </div>
    <div class="mb-30">
        <h2 class="head-second">
            谈判基本信息
        </h2>
        <div class="mb-20">
            <div class="inline-block field-pdnegotiationchild-pdnc_date required">
                <label class="width-100" for="pdnegotiationchild-pdnc_date">谈判日期</label>
                <div class="display-style">
                    <input type="text" id="pdnegotiationchild-pdnc_date" class="select-date  width-200 easyui-validatebox"  data-options="required:true" name="PdNegotiationChild[pdnc_date]" onfocus=this.blur(); value="<?=$child['pdnc_date']?>">
                </div>
            </div>
            <div class="inline-block field-pdnegotiationchild-pdnc_time required">
                <label class="width-100" for="pdnegotiationchild-pdnc_time">谈判时间</label>
                <div class="display-style">
                    <input type="text" id="pdnegotiationchild-pdnc_time" class="select-time  width-200 easyui-validatebox"  data-options="required:true" name="PdNegotiationChild[pdnc_time]" onfocus=this.blur(); value="<?= $child['pdnc_time']?>">

                </div>
            </div>
            <div class="inline-block field-pdnegotiationchild-pdnc_location required">
                <label class="width-120" for="pdnegotiationchild-pdnc_location">谈判地点</label>
                <div class="display-style">
                    <input type="text" id="pdnegotiationchild-pdnc_location" class="width-200 easyui-validatebox"  data-options="required:true" name="PdNegotiationChild[pdnc_location]" maxlength="200" value="<?= $child['pdnc_location']?>">

                </div>
            </div>
        </div>
        <div class="mb-20">
            <div class="inline-block field-pdreception-rece_sname required">
                <label class="width-100" for="pdreception-rece_sname">厂商主谈人</label>
                <div class="display-style">
                    <input type="text" id="pdreception-rece_sname" class="width-200 easyui-validatebox"  data-options="required:true" name="PdReception[rece_sname]" value="<?= $reception['rece_sname']?>">

                </div>
            </div>
            <div class="inline-block field-pdreception-rece_position required">
                <label class="width-100" for="pdreception-rece_position">职位</label>
                <div class="display-style">
                    <input type="text" id="pdreception-rece_position" class="width-200 easyui-validatebox"  data-options="required:true"
                           name="PdReception[rece_position]" value="<?= $reception['rece_position']?>">

                </div>
            </div>
            <div class="inline-block field-pdreception-rece_mobile required">
                <label class="width-120" for="pdreception-rece_mobile">联系电话</label>
                <div class="display-style">
                    <input type="text" id="pdreception-rece_mobile" class="width-200 easyui-validatebox"  data-options="required:true" name="PdReception[rece_mobile]" value="<?= $reception['rece_mobile']?>">

                </div>
            </div>
        </div>
        <div class="mb-20">
            <div class="inline-block field-pdnegotiationchild-pdnc_person required">
                <label class="width-100" for="pdnegotiationchild-pdnc_person">主谈人</label>
                <div class="display-style">
                    <input type="text" id="pdnegotiationchild-pdnc_person" class="blurOne width-200 easyui-validatebox"  data-options="required:true" name="PdNegotiationChild[pdnc_person]" maxlength="30" placeholder="  请输入工号" onblur="person_num(this)" value="<?=$child['pdnc_person']?>">
            <span class="width-50" id="person_name"></span>
                </div>
            </div>
            <label class="width-50 ">职位</label>
            <input class="width-200 ml--3" id="person_title" onfocus=this.blur();>
            <label class="width-120">联系电话</label>
            <input class="width-200" id="person_mobile" onfocus=this.blur();>
        </div>
        <div class="mb-20">
            <label class="width-100">关联拜访计划</label>
            <input type="hidden" id="plan_id" name="PdNegotiationChild[visit_planID]" value="<?= !empty($plan) ? $plan['pvp_planID'] : null ?>">
            <input type="text" id="plan_code" class="width-200" readonly="readonly" placeholder="选择关联拜访计划" value="<?= !empty($plan) ? $plan['pvp_plancode'] : null ?>">
            <a id="select_plan">选择关联拜访计划</a>
        </div>
        <div style="width:99%;">
            <p class="ml-50 width-100 float-left">陪同人员信息</p>
            <p class="float-right mr-50 " style="line-height: 25px;">
                <button type="button" onclick="vacc_add()" class="button-blue text-center">+ 添&nbsp;加</button>
            </p>
            <div class="space-10 clear"></div>
            <table class="table-small">
                <tbody id="vacc_body">
                <tr>
                    <th>工号</th>
                    <th>姓名</th>
                    <th>职位</th>
                    <th>联系电话</th>
                    <th>操作</th>
                </tr>
                    <?php  if(isset($accompany) && !empty($accompany)){ ?>
                    <?php  foreach ($accompany as $val){ ?>
                <tr>
                    <td>
                        <input onblur="job_num(this)" type="text" class="width-150  no-border text-center blurOne easyui-validatebox"  data-options="required:true" placeholder="请输入工号" name="vacc[]" value=<?= $val['staff_code'] ?>>
                    </td>
                    <td><input type="text" class="width-200 no-border text-center" onfocus=this.blur();></td>
                    <td><input type="text" class="width-200  no-border text-center"  onfocus=this.blur();></td>
                    <td><input type="text" class="width-200 no-border text-center"  onfocus=this.blur();></td>
                    <td><a onclick="vacc_del(this)">&nbsp;删除&nbsp;</a></td>
                </tr>
                <?php }?>
                <?php }else{?>
                    <td>
                        <input onblur="job_num(this)" type="text" class="width-150  no-border text-center"
                               placeholder="请输入工号" name="vacc[]">
                    </td>
                    <td><input type="text" class="width-200 no-border text-center"></td>
                    <td><input type="text" class="width-200  no-border text-center"></td>
                    <td><input type="text" class="width-200 no-border text-center"></td>
                    <td><a onclick="vacc_del(this)">&nbsp;删除&nbsp;</a></td>
                <?php }?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-30">
        <h2 class="head-second">
            谈判内容
        </h2>
        <div class="mb-30">
            <h3 class="head-three ml-20 mr-30">
                厂商实际评估
            </h3>
            <div class="mb-20">
                <div class="inline-block field-pdnegotiationanalysis-pdna_position required">
                    <label class="width-150" for="pdnegotiationanalysis-pdna_position">地位</label>
                    <div class="display-style">
                        <select id="pdnegotiationanalysis-pdna_position" class="width-300 easyui-validatebox"  data-options="required:true" name="PdNegotiationAnalysis[pdna_position]" >
                            <option value="">请选择...</option>
                            <?php foreach ($downList['firmLevel'] as $val) { ?>
                                <option value="<?= $val['bsp_id'] ?>" <?= $analysis['pdna_position']==$val['bsp_id']?'selected':''?>><?= $val['bsp_svalue'] ?></option>
                            <?php } ?>
                        </select>

                    </div>
                </div>
                <div class="inline-block field-pdnegotiationanalysis-pdna_influence required">
                    <label class="width-150" for="pdnegotiationanalysis-pdna_influence">业界影响力/排名</label>
                    <div class="display-style">
                        <input type="text" id="pdnegotiationanalysis-pdna_influence" class="width-300 easyui-validatebox"  data-options="required:true" name="PdNegotiationAnalysis[pdna_influence]" maxlength="100" value="<?= $analysis['pdna_influence']?>">

                    </div>
                </div>
            </div>
            <div class="mb-20">
                <div class="inline-block field-pdnegotiationanalysis-pdna_annual_sales required">
                    <label class="width-150" for="pdnegotiationanalysis-pdna_annual_sales">厂商年营业额</label>
                    <div class="display-style">
                        <input type="text" id="pdnegotiationanalysis-pdna_annual_sales" class="width-300 easyui-validatebox"  data-options="required:true" name="PdNegotiationAnalysis[pdna_annual_sales]" maxlength="15" value="<?= $analysis['pdna_annual_sales']?>">

                    </div>
                </div>
                <div class="inline-block field-pdnegotiationanalysis-pdna_cooperate_degree required">
                    <label class="width-150" for="pdnegotiationanalysis-pdna_cooperate_degree">厂商配合度</label>
                    <div class="display-style">
                        <select id="pdnegotiationanalysis-pdna_cooperate_degree" class="width-300 easyui-validatebox"  data-options="required:true" name="PdNegotiationAnalysis[pdna_cooperate_degree]">
                            <option value="">请选择...</option>
                            <?php foreach ($downList['firmCooperate'] as $val) { ?>
                                <option value="<?= $val['bsp_id'] ?>" <?= $analysis['pdna_cooperate_degree']==$val['bsp_id']?'selected':''?>><?= $val['bsp_svalue'] ?></option>
                            <?php } ?>
                        </select>

                    </div>
                </div>
            </div>
            <div class="mb-20">
                <div class="inline-block field-pdnegotiationanalysis-pdna_technology_service required">
                    <label class="width-150"  for="pdnegotiationanalysis-pdna_technology_service">技术实力/技术服务</label>
                    <div class="display-style">
                        <textarea id="pdnegotiationanalysis-pdna_technology_service" class="width-300 text-top easyui-validatebox"  data-options="required:true" name="PdNegotiationAnalysis[pdna_technology_service]" rows="3"><?= $analysis['pdna_technology_service']?></textarea>

                    </div>
                </div>
                <div class="inline-block field-pdnegotiationanalysis-pdna_others">
                    <label class="width-150" for="pdnegotiationanalysis-pdna_others">其他</label> <div class="display-style">
                        <textarea id="pdnegotiationanalysis-pdna_others" class="width-300 text-top" name="PdNegotiationAnalysis[pdna_others]" rows="3"><?= $analysis['pdna_others']?></textarea>

                    </div>
                </div>
            </div>
        </div>
        <div class="mb-30">
            <h3 class="head-three ml-20 mr-30">
                商品与市场
            </h3>
            <div class="mb-20">
                <div class="inline-block field-pdnegotiationanalysis-pdna_pdtype required">
                    <label class="width-150" for="pdnegotiationanalysis-pdna_pdtype">商品类别</label> <div class="display-style">
                        <select id="pdnegotiationanalysis-pdna_pdtype" class="width-300 easyui-validatebox"  data-options="required:true" name="PdNegotiationAnalysis[pdna_pdtype]">
                            <option value="">请选择...</option>
                            <?php foreach ($downList['productTypes'] as $key => $val) { ?>
                                <option value="<?= $key ?>"<?=$analysis['pdna_pdtype']==$key?'selected':''?>><?= $val ?></option>
                            <?php } ?>

                        </select>

                    </div>
                </div>
                <div class="inline-block field-pdnegotiationanalysis-pdna_customer_base required"> <label class="width-150" for="pdnegotiationanalysis-pdna_customer_base">客户群（by 产业）</label>
                    <div class="display-style">
                        <input type="text" id="pdnegotiationanalysis-pdna_customer_base" class="width-300 easyui-validatebox"  data-options="required:true" name="PdNegotiationAnalysis[pdna_customer_base]" maxlength="30" value="<?= $analysis['pdna_customer_base']?>">
                    </div>
                </div>
            </div>
            <div class="mb-20">
                <div class="inline-block field-pdnegotiationanalysis-pdna_loction required">
                    <label class="width-150" for="pdnegotiationanalysis-pdna_loction">商品定位</label>  <div class="display-style">
                        <select id="pdnegotiationanalysis-pdna_loction" class="width-300 easyui-validatebox"  data-options="required:true" name="PdNegotiationAnalysis[pdna_loction]">
                            <option value="">请选择...</option>
                            <?php foreach ($downList['productLevel'] as $val) { ?>
                                <option value="<?= $val['bsp_id'] ?>" <?= $analysis['pdna_loction']==$val['bsp_id']?'selected':''?>><?= $val['bsp_svalue'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="inline-block field-pdnegotiationanalysis-pdna_goods_certificate required">
                    <label class="width-150" for="pdnegotiationanalysis-pdna_goods_certificate">商品认证/厂商认证</label>
                    <div class="display-style">
                        <input type="text" id="pdnegotiationanalysis-pdna_goods_certificate" class="width-300 easyui-validatebox"  data-options="required:true"  name="PdNegotiationAnalysis[pdna_goods_certificate]" maxlength="30" value="<?= $analysis['pdna_goods_certificate']?>">

                    </div>
                </div>
            </div>
            <div class="mb-20">
                <div class="inline-block field-pdnegotiationanalysis-pdna_demand_trends required">
                    <label class="width-150" for="pdnegotiationanalysis-pdna_demand_trends">市场需求趋势</label>
                    <div class="display-style">
                        <input type="text" id="pdnegotiationanalysis-pdna_demand_trends" class="width-300 easyui-validatebox"  data-options="required:true" name="PdNegotiationAnalysis[pdna_demand_trends]" maxlength="100" value="<?= $analysis['pdna_demand_trends']?>">

                    </div>
                </div>
                <div class="inline-block field-pdnegotiationanalysis-pdna_market_share required">
                    <label class="width-150" for="pdnegotiationanalysis-pdna_market_share">销量/市占率</label>
                    <div class="display-style">
                        <input type="text" id="pdnegotiationanalysis-pdna_market_share" class="width-300 easyui-validatebox"  data-options="required:true" name="PdNegotiationAnalysis[pdna_market_share]" maxlength="30" value="<?= $analysis['pdna_market_share']?>">
                    </div>
                </div>
            </div>
            <div class="mb-20">
                <div class="inline-block field-pdnegotiationanalysis-sales_advantage required">
                    <label class="width-150" for="pdnegotiationanalysis-sales_advantage">价格优势</label>
                    <div class="display-style">
                        <input type="text" id="pdnegotiationanalysis-sales_advantage" class="width-300 easyui-validatebox"  data-options="required:true"  name="PdNegotiationAnalysis[sales_advantage]" maxlength="30" value="<?= $analysis['sales_advantage']?>">

                    </div>
                </div>
                <div class="inline-block field-pdnegotiationanalysis-profit_analysis required">
                    <label class="width-150" for="pdnegotiationanalysis-profit_analysis">利润分析</label>
                    <div class="display-style">
                        <input type="text" id="pdnegotiationanalysis-profit_analysis" class="width-300 easyui-validatebox"  data-options="required:true"  name="PdNegotiationAnalysis[profit_analysis]" maxlength="30" value="<?= $analysis['profit_analysis']?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-30 ml-20">
            <div class="easyui-tabs" style="width:930px;">
                <div title="谈判信息" style="padding:10px">
                    <div class="table-content  clear negotiation-message">
                        <div class="space-20"></div>
                        <div class="mb-20">
                            <div class="inline-block field-pdnegotiationchild-process_descript required">
                                <label class="width-150" for="pdnegotiationchild-process_descript">过程描述</label>
                                <div class="display-style">
                            <textarea id="pdnegotiationchild-process_descript" class="width-665 text-top easyui-validatebox"  data-options="required:true"  name="PdNegotiationChild[process_descript]" rows="3"><?= $child['process_descript']?></textarea>

                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <div class="inline-block field-pdnegotiationchild-trace_matter required">
                                <label class="width-150" for="pdnegotiationchild-trace_matter">追踪事项</label>
                                <div class="display-style">
                            <textarea id="pdnegotiationchild-trace_matter" class="width-665 text-top easyui-validatebox"  data-options="required:true"  name="PdNegotiationChild[trace_matter]" rows="3"><?= $child['trace_matter']?></textarea>

                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <div class="inline-block field-pdnegotiationchild-negotiate_concluse required">
                                <label class="width-150" for="pdnegotiationchild-negotiate_concluse">谈判结论</label>
                                <div class="display-style">
                                    <select id="pdnegotiationchild-negotiate_concluse" class="negotiate_concluse easyui-validatebox"  data-options="required:true" name="PdNegotiationChild[negotiate_concluse]">
                                        <option value="">请选择...</option>
                                        <?php foreach ($downList['negotiationResult'] as $val) { ?>
                                            <option value="<?= $val['bsp_id'] ?>" <?= $child['negotiate_concluse']==$val['bsp_id']?'selected':''?>><?= $val['bsp_svalue'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <div class="inline-block field-pdnegotiationchild-next_notice required">
                                <label class="width-150" for="pdnegotiationchild-next_notice">下次访谈注意事项</label>
                                <div class="display-style">
                            <textarea id="pdnegotiationchild-next_notice" class="width-665 text-top easyui-validatebox"  data-options="required:true"   name="PdNegotiationChild[next_notice]" rows="3"><?=$child['next_notice']?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <div class="inline-block">
                                <label class="width-150" for="negotiate_others">其他(谈判事项)</label>
                                <div class="display-style">
                            <textarea id="negotiate_others" class="width-665 text-top"  name="PdNegotiationChild[negotiate_others]" rows="3"><?= $child['negotiate_others']?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mb-20">
                            <div class="inline-block">
                                <label class="width-150" for="pdnegotiationchild-attachment">附件</label>
                                <div class="display-style">
                                    <?php if(isset($child['attachment_name']) && !empty($child['attachment'])){?>
                                        <a  href="<?= Url::to(['/']).$child['attachment']?>"><?= $child['attachment_name']?></a>
                                        <a class="ml-10" id="del-file"><i class="icon-remove  icon-l" title="刪除" ></i></a>
                                    <?php }else{?>
                                    <input type="file" id="pdnegotiationchild-attachment" class="width-200" name="attachment">
                                    <?php }?>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div title="商品信息" style="padding:10px">
                        <div class="space-20"></div>
                            <div class="head">商品信息
                                <div class="float-right">
                                    <a href="#inline" id="addPd">新增商品信息</a>
                                    <span><a href="<?= Url::to(['/ptdt/firm-negotiation/load-dvlp']) ?>" id="load-dvlp" class="fancybox.ajax">需求单增加</a></span>
                                </div>
                            </div>

                    <div class="overflow-auto">
                        <div class="width-900  margin-auto pb-20">
                            <div class="space-10"></div>
                            <table class="product-list">
                                <input type="hidden" id="product_del" name="product_del">
                                <tbody>
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
                                    <th>操作</th>
                                </tr>
                                </tbody>
                                <tbody id="table_body">
                                <?php if(isset($productInfo) && !empty($productInfo)){?>
                                    <?php foreach($productInfo as $key => $val){?>
                                        <tr data-key="<?= $val['pdnp_id'] ?>">
                                            <td><?= $val['product_name'] ?></td>
                                            <td><?= $val['product_size'] ?></td>
                                            <td><?= $val['product_brand'] ?></td>
                                            <td><?= $val['delivery_terms'] ?></td>
                                            <td><?= $val['payment_terms'] ?></td>
                                            <td><?= $val['unit'] ?></td>
                                            <td><?= $val['currency'] ?></td>
                                            <td><?= $val['price_max'] ?></td>
                                            <td><?= $val['price_min'] ?></td>
                                            <td><?= $val['price_range'] ?></td>
                                            <td><?= $val['price_average'] ?></td>
                                            <td><?= $val['levelName'] ?></td>
                                            <td><?= $val['profit_margin'] ?></td>
                                            <td><?= $val['typeName'][0] ?></td>
                                            <td><?= $val['typeName'][1] ?></td>
                                            <td><?= $val['typeName'][2] ?></td>
                                            <td><?= $val['typeName'][3] ?></td>
                                            <td><?= $val['typeName'][4] ?></td>
                                            <td><?= $val['typeName'][5] ?></td>
                                            <td><a class="product_del">删除</a></td>
                                        </tr>
                                    <?php }?>
                                <?php }else{?>
                                    <tr>
                                        <td colspan="20">无数据</td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="space-40"></div>
                </div>

            </div>
            <div class="mb-30" id="load-authorize" style="display: none;">
                <h3 class="head-three ml-20 mr-30">
                    授权项目
                </h3>
                <div class="mb-20">
                    <div class="inline-block ">
                        <label class="width-150">代理等级</label>
                        <div class="display-style">
                            <select id="pdaa_agents_grade" class="width-300"
                                    name="PdAgentsAuthorize[pdaa_agents_grade]">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['agentsLevel'] as $val) { ?>
                                    <option value="<?= $val['bsp_id'] ?>" <?= $authorize['pdaa_agents_grade']==$val['bsp_id']?'selected':''?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                    <div class="inline-block field-pdagentsauthorize-pdaa_authorize_area">
                        <label class="width-200" for="pdagentsauthorize-pdaa_authorize_area">授权区域范围</label>
                        <div class="display-style">
                            <select id="pdagentsauthorize-pdaa_authorize_area" class="width-300"
                                    name="PdAgentsAuthorize[pdaa_authorize_area]">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['authorizeArea'] as $val) { ?>
                                    <option value="<?= $val['bsp_id'] ?>" <?= $authorize['pdaa_authorize_area']==$val['bsp_id']?'selected':''?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-20">
                    <div class="inline-block field-pdagentsauthorize-pdaa_sale_area">
                        <label class="width-150" for="pdagentsauthorize-pdaa_sale_area">销售范围</label>
                        <div class="display-style">
                            <select id="pdagentsauthorize-pdaa_sale_area" class="width-300"
                                    name="PdAgentsAuthorize[pdaa_sale_area]">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['saleArea'] as $val) { ?>
                                    <option value="<?= $val['bsp_id'] ?>" <?= $authorize['pdaa_sale_area']==$val['bsp_id']?'selected':''?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                    <div class="inline-block field-pdagentsauthorize-pdaa_bdate">
                        <label class="width-200" for="pdagentsauthorize-pdaa_bdate">授权开始日期</label>
                        <div class="display-style">
                            <input type="text" id="pdagentsauthorize-pdaa_bdate" class="width-130 select-date" name="PdAgentsAuthorize[pdaa_bdate]" value="<?= $authorize['pdaa_bdate']?>">

                        </div>
                    </div>
                    至
                    <div class="inline-block field-pdagentsauthorize-pdaa_edate">
                        <div class="display-style">
                            <input type="text" id="pdagentsauthorize-pdaa_edate" class="width-130 select-date" name="PdAgentsAuthorize[pdaa_edate]" value="<?= $authorize['pdaa_edate']?>">

                        </div>
                    </div>
                </div>
                <div class="mb-20">
                    <div class="inline-block field-pdagentsauthorize-pdaa_settlement">
                        <label class="width-150" for="pdagentsauthorize-pdaa_settlement">结算方式</label>
                        <div class="display-style">
                            <select id="pdagentsauthorize-pdaa_settlement" class="width-300"
                                    name="PdAgentsAuthorize[pdaa_settlement]">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['settlement'] as $val) { ?>
                                    <option value="<?= $val['bnt_id'] ?>" <?= $authorize['pdaa_settlement']==$val['bnt_id']?'selected':''?>><?= $val['bnt_sname'] ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                    <div class="inline-block field-pdagentsauthorize-pdaa_delivery_day">
                        <label class="width-200" for="pdagentsauthorize-pdaa_delivery_day">交期</label>
                        <div class="display-style">
                            <input type="text" id="pdagentsauthorize-pdaa_delivery_day" class="width-250"
                                   name="PdAgentsAuthorize[pdaa_delivery_day]" value="<?= $authorize['pdaa_delivery_day']?>">

                        </div>
                    </div>                        <span class="width-50 text-center">天
                    </span></div>
                <div class="mb-20">
                    <div class="inline-block field-pdagentsauthorize-pdaa_delivery_way">
                        <label class="width-150" for="pdagentsauthorize-pdaa_delivery_way">物流配送</label>
                        <div class="display-style">
                            <select id="pdagentsauthorize-pdaa_delivery_way" class="width-300"
                                    name="PdAgentsAuthorize[pdaa_delivery_way]">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['deliveryWay'] as $val) { ?>
                                    <option value="<?= $val['bsp_id'] ?>" <?= $authorize['pdaa_delivery_way']==$val['bsp_id']?'selected':''?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                    <div class="inline-block field-pdagentsauthorize-pdaa_service">
                        <label class="width-200" for="pdagentsauthorize-pdaa_service">售后服务</label>
                        <div class="display-style">
                            <select id="pdagentsauthorize-pdaa_service" class="width-300"
                                    name="PdAgentsAuthorize[pdaa_service]">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['service'] as $val) { ?>
                                    <option value="<?= $val['bsp_id'] ?>" <?= $authorize['pdaa_service']==$val['bsp_id']?'selected':''?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>

                        </div>
                    </div>
                </div>
                <div class="mb-20">
                    <div class="inline-block field-pdagentsauthorize-pdaa_sample">
                        <label class="width-150" for="pdagentsauthorize-pdaa_sample">样品提供</label>
                        <div class="display-style">
                            <input type="text" id="pdagentsauthorize-pdaa_sample" class="width-800"
                                   name="PdAgentsAuthorize[pdaa_sample]" value="<?= $authorize['pdaa_sample']?>">

                        </div>
                    </div>
                </div>
                <div class="mb-20">
                    <div class="inline-block field-pdagentsauthorize-pdaa_train_description">
                        <label class="width-150" for="pdagentsauthorize-pdaa_train_description">培训方式</label>
                        <div class="display-style">
                            <input type="text" id="pdagentsauthorize-pdaa_train_description" class="width-800" name="PdAgentsAuthorize[pdaa_train_description]" value="<?= $authorize['pdaa_train_description']?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-40"></div>
                <div class="text-center mb-20">
                    <button class="button-blue-big" type="submit">提交</button>
                    <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">返回</button>
                </div>
        <div class="space-40"></div>
        <!--添加商品信息弹出层start-->
        <div id="inline" class="display-none">
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
<!--                        <option value="">请选择...</option>-->
                        <?php foreach ($downList['tradingUnit'] as $val) { ?>
                            <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-20">
                    <label class="width-100">交易币别</label>
                    <select class="width-100" id="currency_type">
<!--                        <option value="">请选择...</option>-->
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
        <!--添加商品信息弹出层end-->
    </div>

    <?php $form->end(); ?>
</div>
<script>
    $(function () {
        "use strict";

        var id;
        var product_del=[];
        var product_name=$("#product_name");
        var product_size=$("#product_size");
        var product_brand=$("#product_brand");
        var delivery_terms=$("#delivery_terms");
        var payment_terms=$("#payment_terms");
        var product_unit=$("#product_unit");
        var currency_type=$("#currency_type");
        var price_max=$("#price_max");
        var price_min=$("#price_min");
        var price_range=$("#price_range");
        var price_average=$("#price_average");
        var product_level=$("#product_level");
        var profit_margin_min =$("#profit_margin_min");
        var profit_margin_max =$("#profit_margin_max");
        var profit_margin =$("#profit_margin");
        var $typeOne = $('#type_1');
        var $typeTwo = $('#type_2');
        var $typeThree = $('#type_3');
        var $typeFour = $('#type_4');
        var $typeFive = $('#type_5');
        var $typeSix = $('#type_6');
        var i = 1000;
        var x = 0;

        ajaxSubmitForm($("#add-form"));

        //文件
        $("#del-file").click(function(){
            layer.confirm("确定要删除文件?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },
                function () {
                    var del = $("#del-file");
                    del.parent().html('<input type="file" id="pdnegotiationchild-attachment" class="width-200" name="attachment">');
                    layer.closeAll();
                },
                function () {
                    layer.closeAll();
                })
        });

        //选择厂商
        $("#select-com").fancybox({
            padding     : [],
            width		: 800,
            height		: 570,
            autoSize	: false,
            type        : 'iframe',
            href        : '<?= Url::to(['/ptdt/visit-resume/select-firm']) ?>'
        });
        //选中计划
        $("#select_plan").click(function(){
            var firmId=$("#firm_id").val();
            if(firmId==''){
                layer.alert("请先选择厂商信息！",{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['select-plan'])?>?firmId="+firmId,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:520,
            })
        });
        //加载
        $('#load-dvlp').fancybox(
            {
                padding : [],
                fitToView	: false,
                width		: 795,
                height		: 550,
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none',
                type : 'iframe'
            }
        );
        //弹出添加商品窗口
        $("#addPd").fancybox({
            'centerOnScroll':true,
            'title':false
        });

        //添加商品
        $('#save-button').on('click', function () {
            if ($typeSix.val() == '' ||
                product_name.val()== '' ||
                product_size.val()== '' ||
                product_brand.val()== '' ||
                delivery_terms.val()== '' ||
                payment_terms.val()== '' ||
                product_unit.val()== '' ||
                currency_type.val()== '' ||
                price_max.val()== '' ||
                price_min.val()== '' ||
                price_range.val()== '' ||
                price_average.val()== '' ||
                product_level.val()== '' ||
                profit_margin_min.val()== '' ||
                profit_margin_max.val()== ''
            ) {
                layer.alert('商品信息输入不完整', {icon: 2, time: 5000});
                return;
            }
            profit_margin.val(profit_margin_min.val()+'-'+ profit_margin_max.val());
            var tdStr = "<tr>";
            tdStr += "<td>" + product_name.val() + "</td>";
            tdStr += "<td>" + product_size.val() + "</td>";
            tdStr += "<td>" + product_brand.val() + "</td>";
            tdStr += "<td>" + delivery_terms.find("option:selected").text() + "</td>";
            tdStr += "<td>" + payment_terms.find("option:selected").text() + "</td>";
            tdStr += "<td>" + product_unit.find("option:selected").text() + "</td>";
            tdStr += "<td>" + currency_type.find("option:selected").text() + "</td>";
            tdStr += "<td>" + price_max.val() + "</td>";
            tdStr += "<td>" + price_min.val() + "</td>";
            tdStr += "<td>" + price_range.val() + "</td>";
            tdStr += "<td>" + price_average.val() + "</td>";
            tdStr += "<td>" + product_level.find("option:selected").text() + "</td>";
            tdStr += "<td>" + profit_margin.val() +  "</td>";
            tdStr += "<td>" + $typeOne.find("option:selected").text() + "</td>";
            tdStr += "<td>" + $typeTwo.find("option:selected").text() + "</td>";
            tdStr += "<td>" + $typeThree.find("option:selected").text() + "</td>";
            tdStr += "<td>" + $typeFour.find("option:selected").text() + "</td>";
            tdStr += "<td>" + $typeFive.find("option:selected").text() + "</td>";
            tdStr += "<td>" + $typeSix.find("option:selected").text() + "</td>";
            tdStr += "<td><a onclick='product_del(this)'>删除</a></td>";

            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_requirement]" value="' + $("#product_requirement").val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_name]" value="' + product_name.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_size]" value="' + product_size.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_brand]" value="' + product_brand.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][delivery_terms]" value="' + delivery_terms.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][payment_terms]" value="' + payment_terms.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_unit]" value="' + product_unit.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][currency_type]" value="' + currency_type.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][price_max]" value="' + price_max.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][price_min]" value="' + price_min.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][price_range]" value="' + price_range.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][price_average]" value="' + price_average.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_level]" value="' + product_level.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][profit_margin]" value="' + profit_margin.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_type_1]" value="' + $typeOne.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_type_2]" value="' + $typeTwo.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_type_3]" value="' + $typeThree.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_type_4]" value="' + $typeFour.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_type_5]" value="' + $typeFive.val() + '">';
            tdStr += '<input type="hidden" name="PdNegotiationProduct[' + i + '][product_type_6]" value="' + $typeSix.val() + '">';
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
            $('#table_body').append(tdStr);
            if(i==1000){
                window.parent.$("#table_body>tr>td[colspan='20']").parent().remove();
            }
            i++;
            parent.$.fancybox.close();
        });
        //取消添加商品
        $(".close").click(function(){
            parent.$.fancybox.close();
        });
        //加载取得授权证书页面
        var concluse = $(".negotiate_concluse");
        authorizeRequired(concluse.val())
        concluse.on("change", function () {
            authorizeRequired(concluse.val())
            if(100018 ==concluse.val()){
                $("#negotiate_others").validatebox({
                    required:true
                });
            } else {
                $("#negotiate_others").validatebox({
                    required:false
                });
            }
        });
        //页面加载后触发事件 获取staff_Code信息
        $('.blurOne').trigger("blur");

        //编辑时的删除
        $(".product_del").on("click",function(){
            product_del[x] = $(this).parent().parent().attr("data-key");
            $("#product_del").val(product_del);
            $(this).parent().parent().remove();
            x++;
        });

        //商品分类联动菜单
        $(function () {
            //商品分类联动菜单
            $('.type').on("change", function () {
                var $select = $(this);
                var $url = "<?= Url::to(['/ptdt/product-dvlp/get-product-type']) ?>";
                getNextTypeClass($select,$url);
            });
        })
    });

    //添加陪同人员
    function vacc_add(){
        $("#vacc_body").append(
            '<tr>' +
            '<td>' +
            "<input type='text' class='text-center width-150 easyui-validatebox' data-options='validType:[\"accompanySame\"],delay:10000000,validateOnBlur:true' placeholder='请点击输入工号' onblur='job_num(this)' name='vacc[]'>" +
            "</td>" +
            '<td>' +
            '<input type="text" class="width-200 no-border text-center" onfocus=this.blur(); >' +
            '</td>' +
            '<td>' +
            '<input type="text" class="width-200  no-border text-center" onfocus=this.blur(); >' +
            '</td>' +
            '<td>' +
            '<input type="text" class="width-200 no-border text-center" onfocus=this.blur(); >' +
            '</td>' +
            '<td><a onclick="vacc_del(this)">&nbsp;删除&nbsp;</a></td>' +
            '</tr>'
        );
        $.parser.parse($("#vacc_body").find("tr:last"));//easyui解析
    }
    //删除陪同人员
    function vacc_del(obj){
        var tr= $("#vacc_body tr").length;
        if(tr>2){
            $(obj).parents("tr").remove();
        }
    }
//    $(".product_del").on("click",function(){
//        product_del[x] = $(this).parent().parent().attr("data-key");
//        console.log(product_del);
//        $("#product_del").val(product_del);
//        $(this).parent().parent().remove();
//        x++;
//    })
//        陪同人员信息
    function job_num(obj){
        var type = $(obj).parents("tr").find("input");
        var code = $(obj).val();
        if(!code){
            return
        }
        get_staff_info(code,type);
    }
    //加载主谈人信息
    function person_num(obj) {
        var code = $(obj).val();
        if (!code) {
            return
        }
        get_staff_info(code);
    }
    function get_staff_info(code,type){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"code": code},
            url: "<?=Url::to(['/hr/staff/get-staff-info']); ?>",
            success: function (data) {
                if (!data) {
                   return layer.alert("未找到该工号！", {icon: 2, time: 5000});
                }
                if(type != undefined){
                    type.eq(1).val(data.staff_name);
                    type.eq(2).val(data.job_task);
                    type.eq(3).val(data.staff_mobile);
                }else{
                    $("#person_name").html(data.staff_name);
                    $("#person_title").val(data.job_task);
                    $("#person_mobile").val(data.staff_mobile);
                }
            }
        })
    }

    function authorizeRequired(val){
        var $select=$("#pdaa_agents_grade,#pdagentsauthorize-pdaa_authorize_area,#pdagentsauthorize-pdaa_sale_area,#pdagentsauthorize-pdaa_settlement, #pdagentsauthorize-pdaa_delivery_day,#pdagentsauthorize-pdaa_delivery_way, #pdagentsauthorize-pdaa_service,#pdagentsauthorize-pdaa_sample,#pdagentsauthorize-pdaa_sample,#pdagentsauthorize-pdaa_bdate,#pdagentsauthorize-pdaa_edate,#pdagentsauthorize-pdaa_service,#pdagentsauthorize-pdaa_train_description");
        if (val == 100019) {
            $select.validatebox({
                required:true
            });
            $("#load-authorize").show();
        }else{
            $select.validatebox({
                required:false
            });
            $("#load-authorize").hide();
        }

    }
    //删除行
    function product_del(index){
        $(index).parent().parent().remove();
    }
    $(function(){
        $("#table_body").on('mouseover','td',function(){
            this.title=$(this).text();
        })
    })
</script>


