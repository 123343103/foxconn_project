<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/15
 * Time: 上午 10:34
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->params['homeLike'] = ['label' => '商品库管理'];
$this->params['breadcrumbs'][] = ['label' => '商品定价'];
$this->params['breadcrumbs'][] = ['label' => '定价申请列表'];
?>
<div class="content">
    <div class="mb-30">
        <h2 class="head-second">
            <p>商品定价申请详情</p>
        </h2>
        <div class="mb-20">
            <label class="width-130 ">状态</label>
            <span><?=$model['status'];?></span>
        </div>
        <div class="mb-20">
            <label class="width-130 ">申请单号</label>
            <span><?=$model['price_no']; ?></span>
        </div>
        <div class="mb-20">
            <label class="width-130 ">备注</label>
            <span><?=$model['remark']; ?></span>
        </div>

        <div class="mb-20 step-container">
            <div class="step-bar">
                <div class="step-bar-inner"></div>
            </div>
            <div class="step-circle">
                <div>
                    <span>1</span>
                    <p>新增申请</p>
                </div>
                <div>
                    <span>2</span>
                    <p>核价作业</p>
                    <p><?=$model['creatdate'];?></p>
                </div>
                <div>
                    <span>3</span>
                    <p>定价作业</p>
                </div>
                <div>
                    <span>4</span>
                    <p>完成</p>
                </div>
            </div>
        </div>


        <div class="space-10"
        ></div>
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
                <?=$model['price_from']==0?"自主開發":"CRD/PRD";?>
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
                <td><?=$model['part_no'];?></td>
                <td><?=$model['pdt_name'];?></td>
                <td><?=$model['packagespc'];?></td>
                <td><?=$model['type_1'];?></td>
                <td><?=$model['type_2'];?></td>
                <td><?=$model['type_3'];?></td>
                <td><?=$model['type_4'];?></td>
                <td><?=$model['type_5'];?></td>
                <td><?=$model['type_6'];?></td>
                <td><?=$model['brand'];?></td>
            </tr>
            </tbody>
        </table>


<!--        <div class="space-30"-->
<!--        ></div>-->
<!--        <h2 class="head-second">-->
<!--            <p>料号PAS核价信息</p>-->
<!--        </h2>-->
<!--        <div class="mb-20">-->
<!--            <label class="width-100">付款条件</label>-->
<!--            <span class="width-150">--><?//=$model['payment_terms'];?><!--</span>-->
<!--            <label class="width-100">交货条件</label>-->
<!--            <span class="width-150">--><?//=$model['trading_terms'];?><!--</span>-->
<!--            <label class="width-100">供应商代码</label>-->
<!--            <span class="width-150">--><?//=$model['supplier_code'];?><!--</span>-->
<!--        </div>-->
<!--        <div class="mb-20">-->
<!--            <label class="width-100">供应商简称</label>-->
<!--            <span class="width-150">--><?//=$model['supplier_name_shot'];?><!--</span>-->
<!--            <label class="width-100">交货地点</label>-->
<!--            <span class="width-150">--><?//=$model['delivery_address'];?><!--</span>-->
<!--            <label class="width-100">交易单位</label>-->
<!--            <span class="width-150">--><?//=$model['unit'];?><!--</span>-->
<!--        </div>-->
<!--        <div class="mb-20">-->
<!--            <label class="width-100">最小订购量</label>-->
<!--            <span class="width-150">--><?//=$model['min_order'];?><!--</span>-->
<!--            <label class="width-100">交易币别</label>-->
<!--            <span class="width-150">--><?//=$model['currency'];?><!--</span>-->
<!--            <label class="width-100">L/T(天)</label>-->
<!--            <span class="width-150">--><?//=$model['limit_day'];?><!--</span>-->
<!--        </div>-->
<!--        <div class="mb-20">-->
<!--            <label class="width-100">有效期</label>-->
<!--            <span class="width-150">--><?//=$model['valid_date'];?><!--</span>-->
<!--            <label class="width-100">数量区间</label>-->
<!--            <span class="width-150">--><?//=$model['num_area'];?><!--</span>-->
<!--            <label class="width-100">价格</label>-->
<!--            <span class="width-150">--><?//=$model['buy_price'];?><!--</span>-->
<!--        </div>-->
<!---->
<!--        <div class="mb-20">-->
<!--            <label class="width-100">上传附件</label>-->
<!--            <span class="width-150"></span>-->
<!--        </div>-->


        <div class="space-10"
        ></div>
        <h2 class="head-second">
            <p>料号PAS核价信息</p>
        </h2>
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
            <th>数量区间</th>
            <th>价格</th>
            <th>上传附件</th>
            </thead>
            <tbody>
            <?php foreach($model['pas'] as $k=>$v){ ?>
                <tr>
                    <td><?=$v['payment_terms'];?></td>
                    <td><?=$v['trading_terms'];?></td>
                    <td><?=$v['supplier_code'];?></td>
                    <td><?=$v['supplier_name_shot'];?></td>
                    <td><?=$v['delivery_address'];?></td>
                    <td><?=$v['unite'];?></td>
                    <td><?=$v['min_order'];?></td>
                    <td><?=$v['currency'];?></td>
                    <td><?=$v['limit_day'];?></td>
                    <td><?=$v['effective_date'];?></td>
                    <td><?=$v['num_area'];?></td>
                    <td><?=$v['buy_price'];?></td>
                    <td><?php if($v['filename']){echo "<a href='".$v['filename']."'>附件下载</a>";}?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>



        <div class="space-30"></div>
        <h2 class="head-second">
            <p>创建人信息</p>
        </h2>
        <div class="mb-20">
            <label class="width-100" for=""><span style="color:red;">*</span>工号</label>
            <span class="width-150"><?=$model['creatby'];?></span>
            <label class="width-100" for=""><span style="color:red;">*</span>创建人</label>
            <span class="width-150"><?=$model['creator']['staff_name'];?></span>
            <label class="width-100" for="">部门</label>
            <span class="width-150"><?=$model['creator']['organization_code'];?></span>
        </div>
        <div class="mb-20">
            <label class="width-100" for="">邮箱</label>
            <span class="width-150"><?=$model['creator']['staff_email'];?></span>
            <label name="creatdate" class="width-100" for="">创建时间</label>
            <span class="width-150"></span>
            <label class="width-100" for="">联系方式</label>
            <span class="width-150"><?=$model['creator']['staff_mobile'];?></span>
        </div>
        <div class="space-30"></div>
        <div class="text-center pt-20 pb-20">
            <button class="button-white-big" type="button" onclick="history.go(-1)">返回</button>
        </div>


        <div class="space-30"></div>
    </div>


    <style type="text/css">
        .step-container{
            font-size: 0;
            height: 100px;
            margin-left:120px;
            width:800px;
            position: relative;

        }
        .step-bar{
            position: absolute;
            width:100%;
            height:10px;
            border:#666 1px solid;
            display: inline-block;
        }
        .step-bar-inner{
            width:48%;
            height: 10px;
            background:#eee;
        }
        .step-circle{
            position: absolute;
            top:50%;
            width:100%;
            margin-top:-15px;
            font-size: 0;
        }

        .step-circle div{
            width:200px;
            text-align: center;
            font-size: 14px;
            color:#666;
            position:absolute;

        }
        .step-circle span{
            width:30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            background: #eee;
            border-radius: 50%;
            border:#666 1px solid;
            font-size: 14px;
            color:#666;
        }


        .step-circle div{
            top:-45px;
        }
        .step-circle p{
            margin-top:20px;
        }
        .step-circle div:nth-child(1){
            left:-120px;
        }
        .step-circle div:nth-child(2){
            left:166px;
        }
        .step-circle div:nth-child(3){
            left:433px;
        }
        .step-circle div:nth-child(4){
            right:-122px;
        }

    </style>
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