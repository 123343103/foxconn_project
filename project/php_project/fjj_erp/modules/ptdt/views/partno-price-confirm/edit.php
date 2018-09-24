<?php
/**
 * User: F1676624 Date: 2016/12/2
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\widgets\upload\UploadAsset;
UploadAsset::register($this);

$this->params['homeLike'] = ['label' => '商品库管理', 'url' =>['/']];
$this->params['breadcrumbs'][] = ['label' => '定价列表','url'=>['index']];
$this->params['breadcrumbs'][] = ['label' => '定价修改'];
?>
<div class="content">
    <div class="mb-30">
        <h2 class="head-second">
            <p>新增商品定价申请修改</p>
        </h2>
        <?php $form = ActiveForm::begin([
                'id' => 'add-form',
                'method'=>"POST"
        ]); ?>
            <div class="mb-20">
                <label class="width-130 ">商品经理人</label>
                <span class="width-150"><?=$model['pdt_manager'];?></span>
<!--                <input name="PartnoPrice[pdt_manager]" class="width-150 " id="pdt_manager" value="--><?//=$model['pdt_manager']; ?><!--">-->
                <label class="width-150">定价类型</label>
                <span class="width-150"><?=$model['price_type'];?></span>
<!--                <select class="width-120" name="PartnoPrice[price_type]" id="">-->
<!--                    <option value="">请选择</option>-->
<!--                    <option --><?//=$model['price_type']==0?"selected":""?><!-- value="0">新增</option>-->
<!--                        <option --><?//=$model['price_type']==1?"selected":""?><!-- value="1">降价</option>-->
<!--                    <option --><?//=$model['price_type']==2?"selected":""?><!-- value="2">涨价</option>-->
<!--                    <option --><?//=$model['price_type']==3?"selected":""?><!-- value="3">定价不变，利润率变更</option>-->
<!--                    <option --><?//=$model['price_type']==4?"selected":""?><!-- value="4">延期</option>-->
<!--                </select>-->
                <label class="width-150 ">定价发起来源</label>
                <span class="width-150"><?=$model['price_from'];?></span>
<!--                <select class="width-150" name="PartnoPrice[price_from]" id="">-->
<!--                    <option --><?//=$model['price_from']==0?"selected":"";?><!-- value="0">自主开发</option>-->
<!--                    <option --><?//=$model['price_from']==1?"selected":"";?><!-- value="1">CRD/PRD</option>-->
<!--                </select>-->
            </div>
            <div class="mb-20">
                <label class="width-130">主要竞争对手</label>
                <span class="width-150"><?=$model['archrival'];?></span>
<!--                <input name="PartnoPrice[archrival]" class="width-150 " id="center" value="--><?//=$model['archrival'];?><!--">-->
                <label class="width-150">市场均价</label>
                <span class="width-150"><?=$model['market_price'];?></span>
<!--                <input name="PartnoPrice[market_price]" class="width-150 " id="applydep" value="--><?//=$model['market_price']; ?><!--">-->
                <label class="width-150 ">适用产业</label>
                <span class="width-150"><?=$model['usefor'];?></span>
<!--                <input name="PartnoPrice[usefor]" class="width-150 " id="applydep" value="--><?//= $model['usefor']; ?><!--">-->
            </div>
            <div class="mb-20">
                <label class="width-130">是否客制化</label>
                <span class="width-150"><?=$model['iskz']?"Y":"N";?></span>
<!--                <select name="PartnoPrice[iskz]" class="width-150" id="">-->
<!--                    <option --><?//=$model['iskz']==1?"selected":"";?><!-- value="1">是</option>-->
<!--                    <option --><?//=$model['iskz']==0?"selected":"";?><!-- value="0">否</option>-->
<!--                </select>-->

                <label class="width-150">是否取得代理</label>
                <span class="width-150"><?=$model['isproxy']?"Y":"N";?></span>
<!--                <select class="width-150" name="PartnoPrice[isproxy]" id="">-->
<!--                    <option --><?//=$model['isproxy']==1?"selected":"";?><!-- value="1">是</option>-->
<!--                    <option --><?//=$model['isproxy']==0?"selected":"";?><!-- value="0">否</option>-->
<!--                </select>-->

                <label class="width-150">是否线上销售</label>
                <span class="width-150"><?=$model['isonlinesell']?"Y":"N";?></span>
<!--                <select class="width-150" name="PartnoPrice[isonlinesell]" id="">-->
<!--                    <option --><?//=$model['isonlinesell']==1?"selected":"";?><!-- value="1">是</option>-->
<!--                    <option --><?//=$model['isonlinesell']==0?"selected":"";?><!-- value="0">否</option>-->
<!--                </select>-->
            </div>
            <div class="mb-20">
                <label class="width-130">法务风险等级</label>
                <span class="width-150"><?=$model['risk_level'];?></span>
<!--                <select class="width-150" name="PartnoPrice[risk_level]" id="">-->
<!--                    <option --><?//=$model['risk_level']==0?"selected":"";?><!-- value="0">高</option>-->
<!--                    <option --><?//=$model['risk_level']==1?"selected":"";?><!-- value="1">中</option>-->
<!--                    <option --><?//=$model['risk_level']==2?"selected":"";?><!-- value="2">低</option>-->
<!--                </select>-->
                <label class="width-150">是否拳头商品</label>
                <span class="width-150"><?=$model['istitle']?"Y":"N";?></span>
<!--                <select class="width-150" name="PartnoPrice[istitle]" id="">-->
<!--                    <option --><?//=$model['istitle']==1?"selected":"";?><!-- value="1">是</option>-->
<!--                    <option --><?//=$model['istitle']==0?"selected":"";?><!-- value="0">否</option>-->
<!--                </select>-->
                <label class="width-150">商品定位</label>
                <span class="width-150"><?=$model['pdt_level'];?></span>
<!--                <select class="width-150" name="PartnoPrice[pdt_level]" id="">-->
<!--                    <option --><?//=$model['pdt_level']==1?"selected":"";?><!-- value="1">高</option>-->
<!--                    <option --><?//=$model['pdt_level']==2?"selected":"";?><!-- value="2">中</option>-->
<!--                    <option --><?//=$model['pdt_level']==3?"selected":"";?><!-- value="3">低</option>-->
<!--                </select>-->
            </div>
            <div class="mb-20">
                <label class="width-130">价格有效日期</label>
                <span class="width-150"><?=$model['valid_date'];?></span>
<!--                <input name="PartnoPrice[valid_date]" class="width-150 select-date" type="text" id="pdt_name" value="--><?//=$model['valid_date']; ?><!--">-->
                <label class="width-150">品牌</label>
                <span class="width-150"><?=$model['brand'];?></span>
<!--                <input name="PartnoPrice[brand]" class="width-150 " id="iskz" value="--><?//=$model['brand']; ?><!--">-->
                <label class="width-150">发到销售系统</label>
                <span class="width-150"><?=$model['isto_xs']?"Y":"N";?></span>
<!--                <select class="width-150" name="PartnoPrice[isto_xs]" id="">-->
<!--                    <option --><?//=$model['isto_xs']==1?"selected":"";?><!-- value="1">是</option>-->
<!--                    <option --><?//=$model['isto_xs']==0?"selected":"";?><!-- value="0">否</option>-->
<!--                </select>-->
            </div>
            <div class="mb-20">
                <label class="width-130">包装规格</label>
                <span class="width-150"><?=$model['packagespc'];?></span>
<!--                <input name="PartnoPrice[packagespc]" class="width-150 " id="packagespc" value="--><?//=$model['packagespc']; ?><!--">-->
            </div>
            <div class="mb-20">
                <label class="width-130">补充说明</label>
                <span class="width-150"><?=$model['remark'];?></span>
<!--                <textarea class="width-800" rows="3" id="type_description"-->
<!--                          name="PartnoPrice[remark]">--><?//=$model['remark']; ?><!--</textarea>-->
            </div>
        <div id="salearea" class="mb-20">
            <label class="width-120">销售区域</label>
            <span class="width-150"><?=$model['salearea'];?></span>
<!--            <input name="PartnoPrice[salearea]"  type="radio" value="全国"><span> 全国</span>-->
<!--            <input name="PartnoPrice[salearea]" type="radio" value="全国(不含港澳台)"><span> 全国(不含港澳台)</span>-->
<!--            <input id="cur-addr-input"  name="PartnoPrice[salearea]" type="radio" value="--><?//=$model['salearea'];?><!--"><span> 省</span>-->
<!--            <div id="area-selector" class="ml-100" style="display: none;">-->
<!--                <select class=" ml-30 width-130 disName" name="" id="disName_1">-->
<!--                    <option value="">请选择</option>-->
<!--                    <option value="1">中国</option>-->
<!--                </select>-->
<!--                <select class="width-130 disName" name="" id="disName_2">-->
<!--                    <option value="">请选择</option>-->
<!--                </select>-->
<!--                <select class="width-130 disName" name="" id="disName_3">-->
<!--                    <option value="">请选择</option>-->
<!--                </select>-->
<!--                <select class="width-130 disName" name="" id="disName_4">-->
<!--                    <option value="">请选择</option>-->
<!--                </select>-->
<!--                <span id="cur-addr-text" class="ml-30" style="vertical-align: middle;">--><?//=$model['salearea'];?><!--</span>-->
<!--            </div>-->
        </div>
            <div class="space-10"
            ></div>
            <h2 class="head-second">
                <p>料号基本信息</p>
            </h2>
            <table class="table">
                <thead>
                <th>料号</th>
                <th>品名</th>
                <th>规格型号</th>
                <th>一阶</th>
                <th>二阶</th>
                <th>三阶</th>
                <th>四阶</th>
                <th>五阶</th>
                <th>六阶</th>
                <th>品牌</th>
                </thead>
                <tbody>
                <tr>
                    <td><?=$model['product']['pdt_no'];?></td>
                    <td><?=$model['product']['pdt_name'];?></td>
                    <td><?=$model['product']['tp_spec'];?></td>
                    <td><?=$model['type_1'];?></td>
                    <td><?=$model['type_2'];?></td>
                    <td><?=$model['type_3'];?></td>
                    <td><?=$model['type_4'];?></td>
                    <td><?=$model['type_5'];?></td>
                    <td><?=$model['type_6'];?></td>
                    <td><?=$model['brand_name'];?></td>
                </tr>
                </tbody>
            </table>













<!--        <div class="space-30"-->
<!--        ></div>-->
<!--        <h2 class="head-second">-->
<!--            <p>料号PAS核价信息</p>-->
<!--        </h2>-->
<!--        <div style="width:100%;overflow-x: auto;">-->
<!--            <table class="table">-->
<!--                <thead>-->
<!--                <th>付款条件</th>-->
<!--                <th>交货条件</th>-->
<!--                <th>供应商代码</th>-->
<!--                <th>供应商简称</th>-->
<!--                <th>交货地点</th>-->
<!--                <th>交易单位</th>-->
<!--                <th>最小订购量</th>-->
<!--                <th>交易币别</th>-->
<!--                <th>L/T(天)</th>-->
<!--                <th>有效期</th>-->
<!--                <th>数量价格区间</th>-->
<!--                <th>上传附件</th>-->
<!--                </thead>-->
<!--                <tbody>-->
<!--                <tr>-->
<!--                    <td class="datagrid-cell"><input name="payment_terms" type="text" value="--><?//=$model['payment_terms'];?><!--"></td>-->
<!--                    <td class="datagrid-cell"><input name="trading_terms" type="text" value="--><?//=$model['trading_terms'];?><!--"></td>-->
<!--                    <td class="datagrid-cell"><input name="supplier_code" type="text" value="--><?//=$model['supplier_code'];?><!--"></td>-->
<!--                    <td class="datagrid-cell"><input name="supplier_name_shot" type="text" value="--><?//=$model['supplier_name_shot'];?><!--"></td>-->
<!--                    <td class="datagrid-cell"><input name="delivery_address" type="text" value="--><?//=$model['delivery_address'];?><!--"></td>-->
<!--                    <td class="datagrid-cell"><input name="unit" type="text" value="--><?//=$model['unit'];?><!--"></td>-->
<!--                    <td class="datagrid-cell"><input name="min_order" type="text" value="--><?//=$model['min_order'];?><!--"></td>-->
<!--                    <td class="datagrid-cell"><input name="currency" type="text" value="--><?//=$model['currency'];?><!--"></td>-->
<!--                    <td class="datagrid-cell"><input name="limit_day" type="text" value="--><?//=$model['limit_day'];?><!--"></td>-->
<!--                    <td class="datagrid-cell"><input name="valid_date" type="text" value="--><?//=$model['valid_date'];?><!--"></td>-->
<!--                    <td>-->
<!--                        <table id="num_area_table">-->
<!--                            <tr>-->
<!--                                <td class="datagrid-cell">-->
<!--                                    数量区间 <input class="width-30" type="text" value="--><?//=$model['num_area'];?><!--"> ~ <input class="width-30" type="text" value="--><?//=$model['num_area'];?><!--">-->
<!--                                    价格 <input class="width-80" type="text" value="--><?//=$model['num_area'];?><!--">-->
<!--                                    <span id="num_area_add" style="color:#1b6d85;cursor: pointer;">+</span>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                        </table>-->
<!--                    </td>-->
<!--                    <td class="datagrid-cell"><input type="text" value="--><?//=$model['filename'];?><!--"><span style="color:#1b6d85;cursor: pointer;">上传附件</span></td>-->
<!--                </tr>-->
<!--                </tbody>-->
<!--            </table>-->
<!--        </div>-->
















        <div class="space-30"
        ></div>
        <h2 class="head-second">
            <p>料号PAS核价信息</p>
        </h2>
        <div style="width:100%;overflow-x: auto;">
            <input name="part_no" type="hidden" value="<?=$model['part_no'];?>">
            <table class="table">
                <thead>
                <th>付款条件</th>
                <th>交货条件</th>
                <th>供应商代码</th>
                <th>供应商简称</th>
                <th>交货地点</th>
                <th>交易单位</th>
                <th>最小订购量</th>
                <th>交易币别</th>
                <th>L/T(天)</th>
                <th>有效期</th>
                <th>数量价格区间</th>
                <th>上传附件</th>
                </thead>
                <tbody>
                <tr>
                    <td class="datagrid-cell">
                        <select class="width-150" name="payment_terms">
                            <?php foreach($downlist['payment_terms'] as $k=>$v){ ?>
                                <option <?=$model[payment_terms]==$v[pat_sname]?"selected":""?> value="<?=$v[pat_id];?>"><?=$v[pat_sname];?></option>
                            <?php } ?>
                        </select>
<!--                        <input name="payment_terms" type="text" value="--><?//=$model["pas"][0]['payment_terms'];?><!--">-->
                    </td>
                    <td class="datagrid-cell">
                        <select class="width-150" name="trading_terms">
                            <?php foreach($downlist['trading_terms'] as $k=>$v){ ?>
                                <option <?=$model[trading_terms]==$v[tcc_sname]?"selected":""?> value="<?=$v[tcc_id];?>"><?=$v[tcc_sname];?></option>
                            <?php } ?>
                        </select>
<!--                        <input name="trading_terms" type="text" value="--><?//=$model["pas"][0]['trading_terms'];?><!--">-->
                    </td>
                    <td class="datagrid-cell"><input name="supplier_code" type="text" value="<?=$model["pas"][0]['supplier_code'];?>"></td>
                    <td class="datagrid-cell"><input name="supplier_name_shot" type="text" value="<?=$model["pas"][0]['supplier_name_shot'];?>"></td>
                    <td class="datagrid-cell"><input name="delivery_address" type="text" value="<?=$model["pas"][0]['delivery_address'];?>"></td>
                    <td class="datagrid-cell">
                        <select class="width-150" name="unit">
                            <?php foreach($downlist['unit'] as $k=>$v){ ?>
                                <option <?=$model[unit]==$v[bsp_svalue]?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
                            <?php } ?>
                        </select>
<!--                        <input name="unite" type="text" value="--><?//=$model["pas"][0]['unite'];?><!--">-->
                    </td>
                    <td class="datagrid-cell"><input name="min_order" type="text" value="<?=$model["pas"][0]['min_order'];?>"></td>
                    <td class="datagrid-cell">
                        <select class="width-150" name="currency">
                            <?php foreach($downlist['currency'] as $k=>$v){ ?>
                                <option <?=$model[currency]==$v[bsp_svalue]?"selected":""?> value="<?=$v[bsp_id];?>"><?=$v[bsp_svalue];?></option>
                            <?php } ?>
                        </select>
<!--                        <input name="currency" type="text" value="--><?//=$model["pas"][0]['currency'];?><!--">-->
                    </td>
                    <td class="datagrid-cell"><input name="limit_day" type="text" value="<?=$model["pas"][0]['limit_day'];?>"></td>
                    <td class="datagrid-cell"><input name="effective_date" class="select-date" type="text" value="<?=$model["pas"][0]['effective_date'];?>"></td>
                    <td>
                        <table id="num_area_table">
                            <?php
                            if(count($model['pas'])>0){
                            foreach($model['pas'] as $k=>$v){ ?>
                                    <tr>
                                        <td class="datagrid-cell">
<!--                                            数量区间 <input name="FpPas[min_num][]" class="width-30" type="text" value="--><?//=$v['min_num'];?><!--"> ~ <input name="FpPas[max_num][]" class="width-30" type="text" value="--><?//=$v['max_num'];?><!--">-->
                                            数量区间 <input name="num_area[]" class="width-30" type="text" value="<?=$v['num_area'];?>">
                                            价格 <input name="price[]" class="width-80" type="text" value="<?=$v['buy_price'];?>">
                                            <span <?=$k==0?'id="num_area_add"':'onclick="$(this).parent().parent().remove()"';?> style="color:#1b6d85;cursor: pointer;"><?=$k==0?"+":"-";?></span>
                                        </td>
                                    </tr>
                            <?php }} else{ ?>
                                <tr>
                                    <td class="datagrid-cell">
                                        数量区间 <input name="num_area[]" class="width-30" type="text" value="">
                                        价格 <input name="price[]" class="width-80" type="text" value="">
                                        <span id="num_area_add" style="color:#1b6d85;cursor: pointer;">+</span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </td>
                    <td class="datagrid-cell">
                        <input id="attachment" type="text" name="filename" value="<?=$model["pas"][0]['filename'];?>"> <input class="upBtn" type="button" data-target='#attachment' data-target-type='text'  data-server="<?=Url::to(['/base/upload'])?>" value="选择文件">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>







        <div class="space-30"></div>
        <h2 class="head-second">
            <p>定价信息</p>
        </h2>
        <div class="mb-20">
            <label class="width-150" for="">付款条件</label>
            <span class="width-150"><?=$model['payment_terms'];?></span>
            <label class="width-150" for="">交货条件</label>
            <span class="width-150"><?=$model['payment_terms'];?></span>
            <label class="width-150" for="">供应商代码</label>
            <span class="width-150"><?=$model['supplier_code'];?></span>
        </div>
        <div class="mb-20">
            <label class="width-150" for="">供应商简称</label>
            <span class="width-150"><?=$model['supplier_name_shot'];?></span>
            <label class="width-150" for="">交货地点</label>
            <span class="width-150"><?=$model['delivery_address'];?></span>
            <label class="width-150" for="">价格区间</label>
            <span class="width-150"></span>
        </div>
        <div class="mb-20">
            <label class="width-150" for="">原商品定价下限（未税）</label>
            <span id="pre_ws_lower_price" class="width-150"><?=$model['pre_ws_lower_price'];?></span>
            <input name="PartnoPrice[pre_ws_lower_price]" class="width-150" type="hidden" value="<?=$model['pre_ws_lower_price'];?>" />
            <label class="width-150" for="">价格幅度</label>
            <span id="price_fd" class="width-150"><?=$model['price_fd'];?></span>
            <input name="PartnoPrice[price_fd]" class="width-150" type="hidden" value="<?=$model['price_fd'];?>" />
            <label class="width-150" for="">原定价日期</label>
            <span id="pre_verifydate" class="width-150"><?=$model['pre_verifydate'];?></span>
        </div>
        <div class="mb-20">
            <label class="width-150" for="">底价（未税）</label>
            <span id="min_price" class="width-150"><?=$model['min_price'];?></span>
            <input name="min_price" class="width-150" type="hidden" value="<?=$model['min_price'];?>" />
            <label class="width-150" for="">采购价（未税）</label>
            <input id="buy_price" name="buy_price" class="width-150 easyui-validatebox" data-options="required:true" value="<?=$model['buy_price'];?>" />
            <label class="width-150" for="">商品定价下限（未税）</label>
            <span id="ws_lower_price" class="width-150"><?=$model['ws_lower_price'];?></span>
            <input name="ws_lower_price" type="hidden" class="width-150" value="<?=$model['ws_lower_price'];?>">
        </div>
        <div class="mb-20">
            <label class="width-150" for="">商品定价上限 （未税）</label>
            <span id="ws_upper_price" class="width-150"><?=$model['ws_upper_price'];?></span>
            <input name="ws_upper_price" type="hidden" class="width-150" value="<?=$model['ws_upper_price'];?>">
            <label class="width-150" for="">利润下限</label>
            <input id="lower_limit_profit" name="lower_limit_profit" type="text" class="width-150" value="<?=$model['lower_limit_profit'];?>">
            <label class="width-150" for="">利润上限</label>
            <input id="upper_limit_profit" name="upper_limit_profit" type="text" class="width-150" value="<?=$model['upper_limit_profit'];?>">
        </div>
        <div class="mb-20">
            <label class="width-150" for="">利润率下限（%）</label>
<!--            <span id="lower_limit_profit_margin" class="width-150">--><?//=$model['lower_limit_profit_margin'];?><!--</span>-->
            <input name="lower_limit_profit_margin" class="width-150" value="<?=$model['lower_limit_profit_margin'];?>">
            <label class="width-150" for="">利润率上限（%）</label>
<!--            <span id="upper_limit_profit_margin" class="width-150">--><?//=$model['upper_limit_profit_margin'];?><!--</span>-->
            <input name="upper_limit_profit_margin" class="width-150" value="<?=$model['upper_limit_profit_margin'];?>">
            <label class="width-150" for="">毛利润</label>
            <span id="gross_profit" class="width-150"><?=$model['gross_profit'];?></span>
            <input name="gross_profit" class="width-150" type="hidden" value="<?=$model['gross_profit'];?>">
        </div>
        <div class="mb-20">
            <label class="width-150" for="">毛利润率(%)</label>
            <span id="gross_profit_margin" class="width-150"><?=$model['gross_profit_margin'];?></span>
            <input name="gross_profit_margin" class="width-150" type="hidden" value="<?=$model['gross_profit_margin'];?>">
            <label class="width-150" for="">税前利润</label>
            <span id="pre_tax_profit" class="width-150"><?=$model['pre_tax_profit'];?></span>
            <input name="pre_tax_profit" class="width-150" type="hidden" value="<?=$model['pre_tax_profit'];?>">
            <label class="width-150" for="">税前利润率（%）</label>
            <span id="pre_tax_profit_rate" class="width-150"><?=$model['pre_tax_profit_rate'];?></span>
            <input name="pre_tax_profit_rate" class="width-150" type="hidden" value="<?=$model['pre_tax_profit_rate'];?>">
        </div>
        <div class="mb-20">
            <label class="width-150" for="">税后利润</label>
            <span id="after_tax_profit" class="width-150"><?=$model['after_tax_profit'];?></span>
            <input name="after_tax_profit" class="width-150" type="hidden" value="<?=$model['after_tax_profit'];?>">
            <label class="width-150" for="">税后利润率（%）</label>
            <span id="after_tax_profit_margin" class="width-150"><?=$model['after_tax_profit_margin'];?></span>
            <input name="after_tax_profit_margin" class="width-150" type="hidden" value="<?=$model['after_tax_profit_margin'];?>">
        </div>




            <div class="space-30"></div>
            <h2 class="head-second">
                <p>创建人信息</p>
            </h2>
            <div class="mb-20">
                <label class="width-100" for=""><span style="color:red;">*</span>工号</label>
                <input name="creatby" class="width-150 easyui-validatebox validatebox-text validatebox-invalid" type="text" data-options="required:'true'" value="<?=$model['creator']['staff_code'];?>">
                <label class="width-100" for=""><span style="color:red;">*</span>创建人</label>
                <input class="width-150" type="text" value="<?=$model['creator']['staff_name']?>">
                <label class="width-100" for="">部门</label>
                <input class="width-150" type="text" value="<?=$model['creator']['organization_code']?>">
            </div>
        <div class="mb-20">
            <label class="width-100" for="">邮箱</label>
            <input class="width-150" type="text" value="<?=$model['creator']['staff_email']?>">
            <label name="creatdate" class="width-100" for="">创建时间</label>
            <input class="width-150 select-date" value="<?=$model['creatdate'];?>">
            <label class="width-100" for="">联系方式</label>
            <input class="width-150" type="text" value="<?=$model['creator']['staff_mobile']?>">
        </div>
            <div class="space-30"></div>
            <div class="text-center pt-20 pb-20">
                <button class="button-blue-big" type="submit">保存</button>
                <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">取消</button>
            </div>

    <?php $form->end(); ?>

        <div class="space-30"></div>
    </div>

    <script>
        $(function(){
            ajaxSubmitForm($("#add-form"));

            $("#salearea input").click(function(){
                if($(this).index("#salearea input")==2){
                    $("#area-selector").css("display","inline-block");
                }else{
                    $("#area-selector").css("display","none");
                }
            });

            //价格计算
            $("#lower_limit_profit,#upper_limit_profit,#buy_price").change(function(){
                var logistics_rate=0.28 //物流费率
                var buy_price=$("#buy_price");//采购价
                var min_price=$("#min_price");//底价
                var ws_lower_price=$("#ws_lower_price");
                var ws_upper_price=$("#ws_upper_price");
                var lower_limit_profit=$("#lower_limit_profit");
                var upper_limit_profit=$("#upper_limit_profit");
                var lower_limit_profit_margin=$("#lower_limit_profit_margin");
                var upper_limit_profit_margin=$("#upper_limit_profit_margin");
                var gross_profit=$("#gross_profit");//毛利润
                var gross_profit_margin=$("#gross_profit_margin");//毛利润率
                var pre_tax_profit=$("#pre_tax_profit");//税前利润
                var after_tax_profit=$("#after_tax_profit");//税后利润
                var pre_tax_profit_rate=$("#pre_tax_profit_rate");//税前利润率
                var after_tax_profit_margin=$("#after_tax_profit_margin");//税后利润率

                var min_price_val=parseInt(buy_price.val()*(1+logistics_rate+0.01));
                min_price.text(min_price_val);
                min_price.next("input").val(min_price_val);
                var lower_limit_profit_val=lower_limit_profit.val()?lower_limit_profit.val():0;
                var ws_lower_price_val=parseInt(min_price_val+parseInt(lower_limit_profit_val));
                ws_lower_price.text(ws_lower_price_val);
                ws_lower_price.next("input").val(ws_lower_price_val);
                var upper_limit_profit_val=upper_limit_profit.val()?upper_limit_profit.val():0;
                var ws_upper_price_val=parseInt(min_price_val+parseInt(upper_limit_profit_val));
                ws_upper_price.text(ws_upper_price_val);
                ws_upper_price.next("input").val(ws_upper_price_val);
                var gross_profit_val=ws_lower_price_val-buy_price.val();
                gross_profit.text(gross_profit_val);
                gross_profit.next("input").val(gross_profit_val);
                var gross_profit_margin_val=Math.round(gross_profit_val/ws_lower_price_val*100);
                gross_profit_margin.text(gross_profit_margin_val);
                gross_profit_margin.next("input").val(gross_profit_margin_val);
                var pre_tax_profit_val=ws_lower_price_val-buy_price.val();
                pre_tax_profit.text(pre_tax_profit_val);
                pre_tax_profit.next("input").val(pre_tax_profit_val);
                var after_tax_profit_val=pre_tax_profit_val*0.75;
                after_tax_profit.text(after_tax_profit_val);
                after_tax_profit.next("input").val(after_tax_profit_val);
                var pre_tax_profit_rate_val=Math.round(pre_tax_profit_val/ws_lower_price_val*100);
                pre_tax_profit_rate.text(pre_tax_profit_rate_val);
                pre_tax_profit_rate.next("input").val(pre_tax_profit_rate_val);
                var after_tax_profit_margin_val=Math.round(after_tax_profit_val/ws_lower_price_val*100);
                after_tax_profit_margin.text(after_tax_profit_margin_val);
                after_tax_profit_margin.next("input").val(after_tax_profit_margin_val);
                if(row.price_info.ws_lower_price){
                    var price_fd=(ws_lower_price_val-"<?=$model['price_info']['ws_lower_price']?>")/"<?=$model['price_info']['ws_lower_price']?>";
                }else{
                    var price_fd=0;
                }
                $("#price_fd").text(Math.round(price_fd*100));
                $("#price_fd").next("input").val(Math.round(price_fd*100));
                $("#pre_ws_lower_price").text("<?=$model['price_info']['ws_lower_price']?>");
                $("#pre_ws_lower_price").next("input").val("<?=$model['price_info']['ws_lower_price']?>");
            });

            //表格新增行
            $("#num_area_add").on("click",function(){
                $("#num_area_table").append('<tr><td class="datagrid-cell">数量区间 <input name="num_area[]" class="width-30" type="text" value=""> 价格 <input name="price[]"  class="width-80" type="text" value=""> <span onclick="$(this).parent().parent().remove()" style="color:#1b6d85;cursor: pointer;">-</span></td></tr>');
            });

            //新增地区
            $('.disName').on("change", function () {
                var $select = $(this);
                //console.log($select);
                getNextDistrict($select);

                var distArr=[];
                $(this).prevAll(".disName").andSelf().each(function(){
                    distArr.push($(this).children(":selected").html());
                });
                $("#cur-addr-input").val(distArr.join());
                $("#cur-addr-text").text(distArr.join());
            });
            //遞歸清除級聯選項
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


            //获取下级分类
            function getNextDistrict($select) {
                var id = $select.val();
                //console.log(id);
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
//                        console.log(data);
                        var $nextSelect = $select.next("select");
//                        console.log();
                        clearOption($nextSelect);
                        $nextSelect.html('<option value>请选择</option>')
                        if ($nextSelect.length != 0)
                            for (var x in data) {
                                $nextSelect.append('<option value="' + data[x].district_id + '" >' + data[x].district_name + '</option>')
                            }
                    }

                })
            }
        });
    </script>