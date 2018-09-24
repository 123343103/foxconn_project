<?php
/**
 * User: F1676624 Date: 2016/12/2
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->params['homeLike'] = ['label' => '商品库管理'];
$this->params['breadcrumbs'][] = ['label' => '定价列表'];
$this->params['breadcrumbs'][] = ['label' => '商品定价'];
?>
<div class="content">
    <div class="mb-30">
        <h2 class="head-second">
            <p>新增商品定价申请</p>
        </h2>
        <div class="mb-20">
            <label class="width-130 ">商品经理人</label>
            <span class="width-150 " id="pdt_manager"><?=$model['pdt_manager'];?></span>
            <label class="width-150">定价类型</label>
            <span class="width-150 " id="part_no">
                <?php
                    $priceArr=['新增','降价','涨价','定价不变，利润率变更','延期'];
                    if(!empty($model['price_type'])){
                        echo $priceArr[$model['price_type']];
                    }
                ?>
            </span>
            <label class="width-150 ">定价发起来源</label>
            <span class="width-150 " id="pdt_name">
                <?=$model['price_from']==0?"自主开发":"CRD/PRD";?>
            </span>
        </div>
        <div class="mb-20">
            <label class="width-130">主要竞争对手</label>
            <span class="width-150 " id="center"><?=$model['archrival'];?></span>
            <label class="width-150">市场均价</label>
            <span class="width-150 " id="applydep"><?=$model['market_price']; ?></span>
            <label class="width-150 ">适用产业</label>
            <span class="width-150 " id="applydep"><?= $model['usefor']; ?></span>
        </div>
        <div class="mb-20">
            <label class="width-130">是否客制化</label>
            <span class="width-150 " id="pdt_name">
                <?=$model['iskz']==1?"Y":"N";?>
            </span>
            <label class="width-150 ">是否取得代理</label>
            <span class="width-150 " id="pdt_name">
                <?=$model['isproxy']==1?"Y":"N";?>
            </span>
            <label class="width-150">是否线上销售</label>
            <span class="width-150 " id="tp_spec">
                <?=$model['isonlinesell']==1?"Y":"N";?>
            </span>
        </div>
        <div class="mb-20">
            <label class="width-130">法务风险等级</label>
            <span class="width-150 " id="usefor">
                <?php
                if($model['risk_level']==0){
                    echo "高";
                }else if($model['risk_level']==1){
                    echo "中";
                }elseif ($model['risk_level']==2){
                    echo "低";
                }
                ?>
            </span>
            <label class="width-150">是否拳头商品</label>
            <span class="width-150 " id="pdt_name">
                <?=$model['istitle']==1?"Y":"N";?>
            </span>
            <label class="width-150">商品定位</label>
            <span class="width-150 " id="pdt_name">
                <?
                if($model['pdt_level']==1){
                    echo "高";
                }else if($model['pdt_level']==2){
                    echo "中";
                }else if($model['pdt_level']==3){
                    echo "低";
                }
                ?>
            </span>
        </div>
        <div class="mb-20">
            <label class="width-130">价格有效日期</label>
            <span class="width-150 " id="pdt_name">
                <?=$model['valid_date'];?>
            </span>
            <label class="width-150">品牌</label>
            <span class="width-150 " id="iskz">
                <?=$model['brand'];?>
            </span>
            <label class="width-150">发到销售系统</label>
            <span class="width-150 " id="isonlinesell">
                <?=$model['isto_xs']==1?"Y":"N"; ?>
            </span>
        </div>
        <div class="mb-20">
            <label class="width-130">包装规格</label>
            <span class="width-150 " id="risk_level">
                <?=$model['packagespc'];?>
            </span>
            <label class="width-150">销售区域</label>
            <span class="width-150">
                <?=$model['salearea'];?>
            </span>
        </div>
        <div class="mb-20">
            <label class="width-130">是否关联料号定价</label>
            <span class="width-150">
                <?=empty($model['isrelation'])?"N":"Y";?>
            </span>
        </div>
        <div class="mb-20">
            <label class="width-130">补充说明</label>
            <span class="width-800" id="type_description">
                <?=$model['no_xs_cause'];?>
            </span>
        </div>
        <div class="spac"></div>
        <h2 class=" head-second">
            <p>料号基本信息</p>
        </h2>
        <table>
            <thead>
            <tr>
                <th class="width-150 height-30">料号</th>
                <th class="width-100">品名</th>
                <th class="width-100">规格型号</th>
                <th class="width-100">一阶</th>
                <th class="width-100">二阶</th>
                <th class="width-100">三阶</th>
                <th class="width-100">四阶</th>
                <th class="width-100">五阶</th>
                <th class="width-100">六阶</th>
                <th class="width-100">品牌</th>
            </tr>
            </thead>
            <tbody>
            <th class="width-150 height-30"><?=$model["part_no"];?></th>
            <th class="width-100"><?=$model['pdt_name'];?></th>
            <th class="width-100"><?=$model['tp_spec'];?></th>
            <th class="width-100"><?=$model['type_1'];?></th>
            <th class="width-100"><?=$model['type_2'];?></th>
            <th class="width-100"><?=$model['type_3'];?></th>
            <th class="width-100"><?=$model['type_4'];?></th>
            <th class="width-100"><?=$model['type_5'];?></th>
            <th class="width-100"><?=$model['type_6'];?></th>
            <th class="width-100"><?=$model['brand'];?></th>

            </tbody>
            </thead>
        </table>
        <div class="space-30"></div>
        <h2 class=" head-second">
            <p>定价信息</p>
        </h2>
        <?php $form=ActiveForm::begin([
                "method"=>"post",
                "id"=>"add-form"
        ]);?>
            <div class="mb-20">
                <label class="width-130 ">付款条件</label>
                <input name="payment_terms" class="width-150 " id="pdt_manager" value=" <?= $model['payment_terms']; ?>">
                <label class="width-150">交货条件</label>
                <input name="trading_terms" class="width-150 " id="part_no" value=" <?= $model['trading_terms']; ?>">
                <label class="width-150 ">供应商代码</label>
                <input name="supplier_code" class="width-150 " id="pdt_name" value=" <?=$model['supplier_code']; ?>">
            </div>
            <div class="mb-20">
                <label class="width-130">供应商简称</label>
                <input class="width-150 " id="center" value=" <?= "" ?>">
                <label class="width-150">交货地点</label>
                <input name="delivery_address" class="width-150 " id="applydep" value=" <?= $model['delivery_address']; ?>">
                <label class="width-150 ">价格区间</label>
                <input class="width-150 " id="applydep" value=" <?= "" ?>">
            </div>
            <div class="mb-20">
                <label class="width-130">原定价下限（未税）</label>
                <input class="width-150 " id="pdt_name" value=" <?= "" ?>">
                <label class="width-150 ">价格幅度</label>
                <input class="width-150 " id="pdt_name" value=" <?= "" ?>">
                <label class="width-150">原定价日期</label>
                <input class="width-150 " id="tp_spec" value=" <?= "" ?>">
            </div>
            <div class="mb-20">
                <label class="width-130">底价（未税）</label>
                <input name="min_price" class="width-150 " id="usefor" value=" <?= $model['min_price']; ?>">
                <label class="width-150">定价下限（未税）</label>
                <input name="ws_lower_price" class="width-150 " id="pdt_name" value=" <?= $model['ws_lower_price']; ?>">
                <label class="width-150">定价上限（未税）</label>
                <input name="ws_upper_price" class="width-150 " id="pdt_name" value=" <?= $model['ws_upper_price']; ?>">
            </div>
            <div class="mb-20">
                <label class="width-130">利润下限</label>
                <input name="lower_limit_profit" class="width-150 " id="pdt_name" value=" <?= $model['lower_limit_profit']; ?>">
                <label class="width-150">利润上线</label>
                <input name="upper_limit_profit" class="width-150 " id="iskz" value=" <?= $model['upper_limit_profit']; ?>">
                <label class="width-150">利润率下限（%）</label>
                <input name="lower_limit_profit_margin" class="width-150 " id="isonlinesell" value=" <?=$model['lower_limit_profit_margin']; ?>">
            </div>
            <div class="mb-20">
                <label class="width-130">利润率上线（%）</label>
                <input name="upper_limit_profit_margin" class="width-150 " id="pdt_name" value=" <?= $model['upper_limit_profit_margin']; ?>">
                <label class="width-150">毛利润</label>
                <input name="gross_profit" class="width-150 " id="iskz" value=" <?= $model['gross_profit']; ?>">
                <label class="width-150">毛利润率(%)</label>
                <input name="gross_profit_margin" class="width-150 " id="isonlinesell" value=" <?= $model['gross_profit_margin']; ?>">
            </div>
            <div class="mb-20">
                <label class="width-130">税前利润</label>
                <input name="pre_tax_profit" class="width-150 " id="pdt_name" value=" <?= $model['pre_tax_profit']; ?>">
                <label class="width-150">税前利润率（%）</label>
                <input name="pre_tax_profit_rate" class="width-150 " id="iskz" value=" <?= $model['pre_tax_profit_rate']; ?>">
                <label class="width-150">税后利润(%)</label>
                <input name="after_tax_profit" class="width-150 " id="isonlinesell" value=" <?= $model['after_tax_profit']; ?>">
            </div>
            <div class="mb-20">
                <label class="width-130">税后利润率(%)</label>
                <input name="after_tax_profit_margin" class="width-150 " id="isonlinesell" value=" <?= $model['after_tax_profit_margin']; ?>">
            </div>
            <div class="space-30"></div>
            <div class="text-center pt-20 pb-20">
                <button class="button-blue-big" type="submit">保存</button>
                <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">取消</button>
            </div>
        <?php $form->end();?>

        <div class="space-30"></div>
    </div>


    <script>
        window.onload=function(){
            ajaxSubmitForm($("#add-form"));
        }
    </script>
