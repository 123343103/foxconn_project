<?php
/**
 * User: F1676624 Date: 2016/12/2
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\widgets\upload\UploadAsset;
UploadAsset::register($this);

$this->params['homeLike'] = ['label' => '商品库管理'];
$this->params['breadcrumbs'][] = ['label' => '核价列表'];
$this->params['breadcrumbs'][] = ['label' => '核价修改'];
?>
<div class="content">
    <div class="mb-30">
        <h2 class="head-second">
            <p>料号其他信息维护</p>
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
            <span class="width-300">
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
        <?php $form = ActiveForm::begin(['id' => 'add-form','method'=>"POST"]); ?>
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
                    <td><?=$model['product']['brand_name'];?></td>
                </tr>
                </tbody>
            </table>

<!--        <div class="space-30"></div>-->
<!--        <h2 class="head-second">-->
<!--            <p>料号PAS核价信息</p>-->
<!--        </h2>-->
<!--        <div class="mb-20">-->
<!--            <label for="" class="width-130">付款条件</label>-->
<!--            <input name="payment_terms" type="text" class="width-150" value="--><?//=$model['payment_terms'];?><!--">-->
<!--            <label for="" class="width-130">交货条件</label>-->
<!--            <input NAME="trading_terms" type="text" class="width-150" value="--><?//=$model['trading_terms'];?><!--">-->
<!--            <label for="" class="width-130">供应商代码</label>-->
<!--            <input name="supplier_code" type="text" class="width-150" value="--><?//=$model['supplier_code'];?><!--">-->
<!--        </div>-->
<!--        <div class="mb-20">-->
<!--            <label for="" class="width-130">供应商简称</label>-->
<!--            <input mame="supplier_name_shot" type="text" class="width-150" value="--><?//=$model['supplier_name_shot'];?><!--">-->
<!--            <label for="" class="width-130">交货地点</label>-->
<!--            <input name="delivery_address" type="text" class="width-150" value="--><?//=$model['delivery_address'];?><!--">-->
<!--            <label for="" class="width-130">交货单位</label>-->
<!--            <select name="unit" class="width-150">-->
<!--                <option value="吨">吨</option>-->
<!--            </select>-->
<!--        </div>-->
<!---->
<!--        <div class="mb-20">-->
<!--            <label for="" class="width-130">最小订购量</label>-->
<!--            <input name="min_order" type="text" class="width-150" value="--><?//=$model['min_order']?><!--">-->
<!--            <label for="" class="width-130">交易币别</label>-->
<!--            <select name="currency" class="width-150">-->
<!--                <option value="">请选择</option>-->
<!--                <option --><?//=$model['currency']=="RMB"?"selected":"";?><!-- value="RMB">RMB</option>-->
<!--                <option --><?//=$model['currency']=="USD"?"selected":"";?><!-- value="USD">USD</option>-->
<!--            </select>-->
<!--            <label for="" class="width-130">LT/（天）</label>-->
<!--            <input name="limit_day" type="text" class="width-150" value="--><?//=$model['limit_day'];?><!--">-->
<!--        </div>-->
<!---->
<!--        <div class="mb-20">-->
<!--            <label for="" class="width-130">有效期</label>-->
<!--            <input name="valid_date" type="text" class="width-150 select-date" value="--><?//=$model['valid_date']?><!--">-->
<!--            <label for="" class="width-130">数量区间</label>-->
<!--            <input name="num_area" type="text" class="width-150" value="--><?//=$model['num_area']?><!--">-->
<!--            <label for="" class="width-130">价格</label>-->
<!--            <input name="buy_price" type="text" class="width-150" value="--><?//=$model['buy_price'];?><!--">-->
<!--        </div>-->
<!---->
<!--        <div class="mb-20">-->
<!--            <label for="" class="width-130">附件</label>-->
<!--            <input id="filename" name="buy_price" type="file" class="layui-upload-file" value="--><?//=$model['filename'];?><!--">-->
<!---->
<!--        </div>-->








        <div class="space-30"
        ></div>
        <h2 class="head-second">
            <p>料号PAS核价信息</p>
        </h2>
        <div style="width:100%;overflow-x: auto;">
            <input name="FpPas[part_no]" type="hidden" value="<?=$model['part_no'];?>">
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
                    <td class="datagrid-cell"><input name="FpPas[payment_terms]" type="text" value="<?=$model['pas'][0]['payment_terms'];?>"></td>
                    <td class="datagrid-cell"><input name="FpPas[trading_terms]" type="text" value="<?=$model['pas'][0]['trading_terms'];?>"></td>
                    <td class="datagrid-cell"><input name="FpPas[supplier_code]" type="text" value="<?=$model['pas'][0]['supplier_code'];?>"></td>
                    <td class="datagrid-cell"><input name="FpPas[supplier_name_shot]" type="text" value="<?=$model['pas'][0]['supplier_name_shot'];?>"></td>
                    <td class="datagrid-cell"><input name="FpPas[delivery_address]" type="text" value="<?=$model['pas'][0]['delivery_address'];?>"></td>
                    <td class="datagrid-cell"><input name="FpPas[unite]" type="text" value="<?=$model['pas'][0]['unite'];?>"></td>
                    <td class="datagrid-cell"><input name="FpPas[min_order]" type="text" value="<?=$model['pas'][0]['min_order'];?>"></td>
                    <td class="datagrid-cell"><input name="FpPas[currency]" type="text" value="<?=$model['pas'][0]['currency'];?>"></td>
                    <td class="datagrid-cell"><input name="FpPas[limit_day]" type="text" value="<?=$model['pas'][0]['limit_day'];?>"></td>
                    <td class="datagrid-cell"><input name="FpPas[effective_date]" class="select-date" type="text" value="<?=$model['pas'][0]['effective_date'];?>"></td>
                    <td>
                        <table id="num_area_table">
                            <?php
                            if(count($model['pas'])>0){
                            foreach($model['pas'] as $k=>$v){ ?>
                            <tr>
                                <td class="datagrid-cell">
                                    数量区间 <input name="num_area[]" class="width-30" type="text" value="<?=$v['num_area'];?>">
                                    价格 <input name="buy_price[]" class="width-80" type="text" value="<?=$v['buy_price'];?>">
                                    <span <?=$k==0?'id="num_area_add"':'onclick="$(this).parent().parent().remove()"';?> style="color:#1b6d85;cursor: pointer;"><?=$k==0?"+":"-";?></span>
                                </td>
                            </tr>
                            <?php }
                            }else{
                            ?>
                                <tr>
                                    <td class="datagrid-cell">
                                        数量区间 <input name="num_area[]" class="width-30" type="text" value="">
                                        价格 <input name="buy_price[]" class="width-80" type="text" value="">
                                        <span id="num_area_add" style="color:#1b6d85;cursor: pointer;">+</span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </td>
                    <td class="datagrid-cell"><input id="attachment" name="FpPas[filename]" type="text" value="<?=$model["pas"][0]['filename'];?>"> <input class="upBtn" type="button" data-target='#attachment' data-target-type='text'  data-server="<?=Url::to(['/base/upload'])?>" value="选择文件"></td>
                </tr>
                </tbody>
            </table>
        </div>




















            <div class="space-30"></div>
            <h2 class="head-second">
                <p>创建人信息</p>
            </h2>
            <div class="mb-20">
                <label class="width-100" for=""><span style="color:red;">*</span>工号</label>
                <input name="creatby" class="width-150 easyui-validatebox validatebox-text validatebox-invalid" type="text" data-options="required:'true'" value="<?=$model['creatby'];?>">
                <label class="width-100" for=""><span style="color:red;">*</span>创建人</label>
                <input class="width-150" type="text">
                <label class="width-100" for="">部门</label>
                <input class="width-150" type="text">
            </div>
        <div class="mb-20">
            <label class="width-100" for="">邮箱</label>
            <input class="width-150" type="text" >
            <label name="creatdate" class="width-100" for="">创建时间</label>
            <input name="creatdate" class="width-150 select-date" value="<?=$model['creatdate'];?>">
            <label class="width-100" for="">联系方式</label>
            <input class="width-150" type="text">
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

            $("[name=salearea]").click(function(){
                if($(this).index("[name=salearea]")==2){
                    $("#area-selector").css("display","inline-block");
                }else{
                    $("#area-selector").css("display","none");
                }
            });



            $("#num_area_add").on("click",function(){
                $("#num_area_table").append('<tr><td class="datagrid-cell">数量区间 <input name="num_area[]" class="width-30" type="text" value=""> 价格 <input name="buy_price[]"  class="width-80" type="text" value=""> <span onclick="$(this).parent().parent().remove()" style="color:#1b6d85;cursor: pointer;">-</span></td></tr>');
            });


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