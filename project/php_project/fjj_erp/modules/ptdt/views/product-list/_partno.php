<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/19
 * Time: 下午 02:18
 */
use yii\helpers\Html;
\app\assets\AppAsset::register($this);
?>
<style type="text/css">
    .label-width{
        width:140px;
    }
    .value-width{
        width:200px;
    }
</style>
    <div class="_basic">
        <h2 class="head-second mt-20" style="background-color: #d9f0ff;margin-top: 48px">料号:&nbsp;<?=$data["partno"]["part_no"];?></h2>
        <div id="content">
            <div id="tab_bar">
                <ul>
                    <li class="tab_li" id="tab1" onclick="myclick(1)" style="background-color: #1f7ed0">基本信息</li>
                    <li class="tab_li" id="tab2" onclick="myclick(2)">核价信息</li>
                    <li class="tab_li" id="tab3" onclick="myclick(3)">销售价格</li>
                    <li class="tab_li" id="tab4" onclick="myclick(4)">规格参数</li>
                    <li class="tab_li" id="tab5" onclick="myclick(5)">备货期</li>
                    <li class="tab_li" id="tab6" onclick="myclick(6)">发货地</li>
                    <li class="tab_li" id="tab7" onclick="myclick(7)">免运费收货地</li>
                    <li class="tab_li" id="tab8" onclick="myclick(8)">包装信息</li>
                    <?php if($data["pdt"]["isDevice"]){ ?><li class="tab_li" id="tab9" onclick="myclick(9)">设备用途</li><?php } ?>
                </ul>
            </div>
        </div>
        <div class="tab_css" id="tab1_content" style="display: block">

            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                    <tr class="no-border mb-10">
                        <td width="11%" class="no-border vertical-center label-align">原产地：</td>
                        <td width="22%" class="no-border vertical-center value-align"><?=$data['partno']['pdt_origin']?></td>
                        <td width="15%" class="no-border vertical-center label-align">型号：</td>
                        <td width="22%" class="no-border vertical-center value-align"><?=$data['partno']['tp_spec']?></td>
                        <td width="11%" class="no-border vertical-center label-align">保质期（月）：</td>
                        <td width="18%" class="no-border vertical-center value-align"><?=$data['partno']['warranty_period']?></td>
                    </tr>
                </tbody>
            </table>


            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                <tr class="no-border mb-10">
                    <td width="11%" class="no-border vertical-center label-align">最小起订量：</td>
                    <td width="22%" class="no-border vertical-center value-align"><?=substr($data['partno']['min_order'],0,strpos($data['partno']['min_order'],'.'))?></td>
                    <td width="15%" class="no-border vertical-center label-align">销售包装内商品数量：</td>
                    <td width="22%" class="no-border vertical-center value-align"><?=substr($data['pack'][1]['pdt_qty'],0,strpos($data['pack'][1]['pdt_qty'],'.'))?></td>
                    <td width="11%" class="no-border vertical-center label-align">供应商：</td>
                    <td width="18%" class="no-border vertical-center value-align"><?=$data["partno"]["supplier_sname"]?></td>
                </tr>
                </tbody>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                <tr class="no-border mb-10">
                    <td width="11%" class="no-border vertical-center label-align">商品定位：</td>
                    <td width="22%" class="no-border vertical-center value-align">
                        <?php
                        switch($data["partno"]["cm_pos"]){
                            case 0:
                                echo "高";
                                break;
                            case 1:
                                echo "中";
                                break;
                            case 2:
                                echo "低";
                                break;
                            default:
                                echo "/";
                                break;
                        }
                        ?>
                    </td>
                    <td width="15%" class="no-border vertical-center label-align">L / T（天）：</td>
                    <td width="22%" class="no-border vertical-center value-align"><?=$data['partno']['l/t']?$data['partno']['l/t']:"/"?></td>
                    <td width="11%" class="no-border vertical-center label-align">法务风险等级：</td>
                    <td width="18%" class="no-border vertical-center value-align">
                        <?php
                        switch($data["partno"]["leg_lv"]){
                            case 0:
                                echo "高";
                                break;
                            case 1:
                                echo "中";
                                break;
                            case 2:
                                echo "低";
                                break;
                            default:
                                echo "/";
                                break;
                        }
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                <tr class="no-border mb-10">
                    <td width="11%" class="no-border vertical-center label-align">是否可议价：</td>
                    <td width="22%" class="no-border vertical-center value-align"><?=$data["partno"]["yn_discuss"]==1?"是":"否"?></td>
                    <td width="15%" class="no-border vertical-center label-align">是否保税：</td>
                    <td width="22%" class="no-border vertical-center value-align"><?=$data["partno"]["yn_tax"]==1?"是":"否"?></td>
                    <td width="11%" class="no-border vertical-center label-align">是否自提：</td>
                    <td width="18%" class="no-border vertical-center value-align"><?=$data["partno"]["isselftake"]==1?"是":"否"?></td>
                </tr>
                </tbody>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                <tr class="no-border mb-10">
                    <td width="11%" class="no-border vertical-center label-align">是否代理：</td>
                    <td width="22%" class="no-border vertical-center value-align"><?=$data["partno"]["is_agent"]==1?"是":"否"?></td>
                    <td width="15%" class="no-border vertical-center label-align">是否批次管理：</td>
                    <td width="22%" class="no-border vertical-center value-align"><?=$data["partno"]["is_batch"]==1?"是":"否"?></td>
                    <td width="11%" class="no-border vertical-center label-align">是否拳头商品：</td>
                    <td width="18%" class="no-border vertical-center value-align"><?=$data["partno"]["is_first"]==1?"是":"否"?></td>
                </tr>
                </tbody>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                <tr class="no-border mb-10">
                    <td width="11%" class="no-border vertical-center label-align">备注：</td>
                    <td width="88%" class="no-border vertical-center value-align"><?=$data["partno"]["marks"]?></td>
                </tr>
                </tbody>
            </table>

            <?php if($data["partno"]["isselftake"]){ ?>
                <div class="mb-20">
                <label style="width: 80px;" for="">自提仓库：</label>
                <div class="_house">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>仓库名</th>
                            <th>仓库地址</th>
                            <th>联系人</th>
                            <th>联系电话</th>
                        </tr>
                        </thead>
                        <tbody id="product_table">
                        <?php if(count($data["wh"])>0){ ?>
                        <?php foreach($data["wh"] as $k=>$wh){ ?>
                            <tr>
                                <td><?=$k+1?></td>
                                <td><?=$wh["wh_name"]?></td>
                                <td><?=$wh["wh_address"]?></td>
                                <td><?=$wh["staff_name"]?></td>
                                <td><?=$wh["staff_mobile"]?></td>
                            </tr>
                        <?php } ?>
                        <?php }else{ ?>
                            <tr>
                                <td colspan="5">没有相关数据！</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>
        </div>
                <?php if(count($data["pas"])>0){ ?>
                    <div class="tab_css" id="tab2_content" style="overflow-x:auto;overflow-y:hidden;">
                    <table class="table" style="width:2400px;">
                <?php }else{ ?>
                    <div class="tab_css" id="tab2_content" style="overflow-x:hidden;overflow-y:hidden;">
                    <table class="table" style="width:2400px;">
                <?php } ?>
                <thead>
                <tr>
                    <th width="50">序号</th>
                    <th width="150">供应商编号</th>
                    <th width="200">供应商名称</th>
                    <th width="150">交易单位</th>
                    <th width="150">最小订购量</th>
                    <th width="150">交易币别</th>
                    <th width="150">报价单价</th>
                    <th width="150">采购价（未税）</th>
                    <th width="150">底价（未税）</th>
                    <th width="150">商品定价下限（未税）</th>
                    <th width="150">商品定价上限（未税）</th>
                    <th width="150">量价区间</th>
                    <th width="150">定价有效期</th>
                    <th width="150">毛利潤</th>
                    <th width="150">毛利潤率</th>
                    <th width="150">稅前利潤</th>
                    <th width="150">稅前利潤率（%）</th>
                    <th width="150">稅后利潤</th>
                    <th width="150">稅后利潤率（%）</th>
                </tr>
                </thead>
                <tbody id="product_table">
                <?php foreach($data["pas"] as $k=>$pas){ ?>
                    <tr>
                        <td><?=$k+1?></td>
                        <td><?=$pas["supplier_code"]?></td>
                        <td><?=$pas["supplier_name"]?></td>
                        <td><?=$pas["unite"]?></td>
                        <td><?=$pas["min_order"]?></td>
                        <td><?=$pas["currency"]?></td>
                        <td><?=$pas["quote_price"]?></td>
                        <td><?=$pas["buy_price"]?></td>
                        <td><?=$pas["min_price"]?></td>
                        <td><?=$pas["ws_upper_price"]?></td>
                        <td><?=$pas["ws_lower_price"]?></td>
                        <td><?=$pas["num_area"]?></td>
                        <td><?=$pas["effective_date"]?></td>
                        <td><?=$pas["gross_profit"]?></td>
                        <td><?=$pas["gross_profit_margin"]?></td>
                        <td><?=$pas["pre_tax_profit"]?></td>
                        <td><?=$pas["pre_tax_profit_rate"]?></td>
                        <td><?=$pas["after_tax_profit"]?></td>
                        <td><?=$pas["after_tax_profit_margin"]?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
                        <?php if(count($data["pas"])==0){ ?>
            <div style="text-align: center;height:30px;line-height: 30px;border: #eee 1px solid;">没有相关数据！</div>
                        <?php } ?>
        </div>
        <div class="tab_css" id="tab3_content">
            <table class="table">
                <thead>
                <tr>
                    <th>最小值</th>
                    <th>最大值</th>
                    <th>价格</th>
                    <th>币别</th>
                </tr>
                </thead>
                <tbody id="product_table">
                <?php if(count($data["price"])>0){ ?>
                <?php foreach ($data["price"] as $price){ ?>
                    <tr>
                        <?php if((int)$price["price"]==-1){ ?>
                            <td></td>
                            <td></td>
                            <td>面议</td>
                            <td></td>
                        <?php }else{ ?>
                            <td><?=$price["minqty"]?></td>
                            <td><?=$price["maxqty"]?></td>
                            <td><?=$price["price"]?></td>
                            <td><?=$price["currency"]?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td colspan="4">没有相关数据！</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="tab_css" id="tab4_content">
            <?php
            $html=Html::beginTag("table",["class"=>"no-border vertical-center mb-10"]);
            foreach ($data["attrs"] as $attr){
                $html.=Html::beginTag("tr",["class"=>"no-border mb-10"]);
                $html.=Html::tag("td",$attr["name"]."：",["width"=>"11%","class"=>"no-border vertical-center label-align"]);
                switch($attr["type"]){
                    case 0:
                        $html.=Html::beginTag("td",["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                        $html.=Html::beginTag("span");
                        $res=[];
                        foreach ($attr["items"] as $k=>$v){
                            if(in_array($k,$attr["sel"])){
                                $res[]=$v;
                            }
                        }
                        $html.=implode(",",$res);
                        $html.=Html::endTag("span");
                        $html.=Html::endTag("td");
                        break;
                    case 1:
                        $html.=Html::beginTag("td",["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                        $html.=Html::beginTag("span");
                        $res=[];
                        foreach ($attr["items"] as $k=>$v){
                            if(in_array($k,$attr["sel"])){
                                $res[]=$v;
                            }
                        }
                        $html.=implode(",",$res);
                        $html.=Html::endTag("span");
                        $html.=Html::endTag("td");
                        break;
                    case 2:
                        $html.=Html::beginTag("td",["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                        $html.=Html::beginTag("span");
                        $res=[];
                        foreach ($attr["items"] as $k=>$v){
                            if(in_array($k,$attr["sel"])){
                                $res[]=$v;
                            }
                        }
                        $html.=implode(",",$res);
                        $html.=Html::endTag("span");
                        $html.=Html::endTag("td");
                        break;
                    case 3:
                        $html.=Html::beginTag("td",["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                        $html.=Html::tag("span",$attr["val"]);
                        $html.=Html::endTag("td");
                        break;
                }
                $html.=Html::endTag("tr");
            }
            $html.=Html::endTag("table");
            echo $html;
            ?>
        </div>
        <div class="tab_css" id="tab5_content">
            <table class="table">
                <thead>
                <tr>
                    <th>最小值</th>
                    <th>最大值</th>
                    <th>备货时间（天）</th>
                </tr>
                </thead>
                <tbody id="product_table">
                <?php if(count($data["stock"])>0){ ?>
                <?php foreach($data["stock"] as $k=>$stock){ ?>
                    <tr>
                        <td><?=$stock["min_qty"]?></td>
                        <td><?=$stock["max_qty"]?></td>
                        <td><?=$stock["stock_time"]?></td>
                    </tr>
                <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td colspan="3">没有相关数据！</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="tab_css" id="tab6_content">
            <table class="table">
                <thead>
                <tr>
                    <th>序号</th>
                    <th>国家</th>
                    <th>省份</th>
                    <th>城市</th>
                </tr>
                </thead>
                <tbody id="product_table">
                <?php if(count($data["ship"])>0){ ?>
                <?php foreach ($data["ship"] as $k=>$ship){ ?>
                    <tr>
                        <td><?=$k+1?></td>
                        <td><?=$ship["country_name"]?></td>
                        <td><?=$ship["province_name"]?></td>
                        <td><?=$ship["city_name"]?></td>
                    </tr>
                <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td colspan="4">没有相关数据！</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="tab_css" id="tab7_content">
            <div class="mb-20">
                <label class="width-10" for=""><span class="red">*</span></label>
                <?=Html::radio("yn_free_delivery",$data["partno"]["yn_free_delivery"]==0,["disabled"=>true])?>&nbsp;全国免运费&nbsp;
                <?=Html::radio("yn_free_delivery",$data["partno"]["yn_free_delivery"]==1,["disabled"=>true])?>&nbsp;全国部分城市免运费&nbsp;
                <?=Html::radio("yn_free_delivery",$data["partno"]["yn_free_delivery"]==2,["disabled"=>true])?>&nbsp;全国不免运费&nbsp;
                <?=Html::radio("yn_free_delivery",$data["partno"]["yn_free_delivery"]==3,["disabled"=>true])?>&nbsp;全国部分城市不免运费&nbsp;
            </div>
            <div class="mb-20">
                <table class="table">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>国家</th>
                        <th>省份</th>
                        <th>城市</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <?php if(count($data["deliv"])>0){ ?>
                    <?php foreach ($data["deliv"] as $k=>$deliv){ ?>
                        <tr>
                            <td><?=$k+1?></td>
                            <td><?=$deliv["country_name"]?></td>
                            <td><?=$deliv["province_name"]?></td>
                            <td><?=$deliv["city_name"]?></td>
                        </tr>
                    <?php } ?>
                    <?php }else{ ?>
                        <tr>
                            <td colspan="4">没有相关数据！</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab_css" id="tab8_content">
            <div class="mb-10">
                <span style="color: #0000FF;font-size:14px">基本包装信息</span>
            </div>
            <div class="mb-10">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="100">商品长</th>
                        <th width="100">商品宽</th>
                        <th width="100">商品高</th>
                        <th width="100">商品重</th>
                        <th width="100">使用富金机包装</th>
                        <th width="100">使用线板</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <tr>
                        <td>1</td>
                        <td><?=$data["pack"][0]["pdt_length"]?></td>
                        <td><?=$data["pack"][0]["pdt_width"]?></td>
                        <td><?=$data["pack"][0]["pdt_height"]?></td>
                        <td><?=$data["pack"][0]["pdt_weight"]?></td>
                        <td><input type="checkbox" <?=$data['partno']['yn_pa_fjj']?'checked':''?> disabled></td>
                        <td><input type="checkbox" <?=$data['partno']['yn_pallet']?'checked':''?> disabled></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-10">
                <span style="color: #0000FF;font-size:14px">销售包装（内包装）</span>
            </div>
            <div class="mb-20">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="100">包装长</th>
                        <th width="100">包装宽</th>
                        <th width="100">包装高</th>
                        <th width="100">包装毛重</th>
                        <th width="100">包材名称</th>
                        <th width="100">销售包装内商品数量</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <tr>
                        <td>2</td>
                        <td><?=$data["pack"][1]["pdt_length"]?></td>
                        <td><?=$data["pack"][1]["pdt_width"]?></td>
                        <td><?=$data["pack"][1]["pdt_height"]?></td>
                        <td><?=$data["pack"][1]["pdt_weight"]?></td>
                        <td><?=$data["pack"][1]["pdt_mater"]?></td>
                        <td><?=$data["pack"][1]["pdt_qty"]?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-10">
                <span style="color: #0000FF;font-size:14px">外包装</span>
            </div>
            <div class="mb-10">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="100">包装长</th>
                        <th width="100">包装宽</th>
                        <th width="100">包装高</th>
                        <th width="100">包装毛重</th>
                        <th width="100">包材名称</th>
                        <th width="100">外包装数量</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <tr>
                        <td>3</td>
                        <td><?=$data["pack"][2]["pdt_length"]?></td>
                        <td><?=$data["pack"][2]["pdt_width"]?></td>
                        <td><?=$data["pack"][2]["pdt_height"]?></td>
                        <td><?=$data["pack"][2]["pdt_weight"]?></td>
                        <td><?=$data["pack"][2]["pdt_mater"]?></td>
                        <td><?=$data["pack"][2]["pdt_qty"]?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-10">
                <span style="color: #0000FF;font-size:14px">散货包装</span>
            </div>
            <div class="mb-10">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="100">包装长</th>
                        <th width="100">包装宽</th>
                        <th width="100">包装高</th>
                        <th width="100">包装毛重</th>
                        <th width="100">包材名称</th>
                        <th width="100">散货包装数量</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <tr>
                        <td>4</td>
                        <td><?=$data["pack"][3]["pdt_length"]?></td>
                        <td><?=$data["pack"][3]["pdt_width"]?></td>
                        <td><?=$data["pack"][3]["pdt_height"]?></td>
                        <td><?=$data["pack"][3]["pdt_weight"]?></td>
                        <td><?=$data["pack"][3]["pdt_mater"]?></td>
                        <td><?=$data["pack"][3]["pdt_qty"]?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-10">
                <span style="color: #0000FF;font-size:14px">板线包装</span>
            </div>
            <div class="mb-10">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="100">序号</th>
                        <th width="100">包装长</th>
                        <th width="100">包装宽</th>
                        <th width="100">包装高</th>
                        <th width="100">包装毛重</th>
                        <th width="100">板线数量</th>
                        <th width="100">包装件数量</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <tr>
                        <td>5</td>
                        <td><?=$data["pack"][4]["pdt_length"]?></td>
                        <td><?=$data["pack"][4]["pdt_width"]?></td>
                        <td><?=$data["pack"][4]["pdt_height"]?></td>
                        <td><?=$data["pack"][4]["pdt_weight"]?></td>
                        <td><?=$data["pack"][4]["plate_num"]?></td>
                        <td><?=$data["pack"][4]["pdt_qty"]?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
         <?php if($data["pdt"]["isDevice"]){ ?>
            <div class="tab_css" id="tab9_content">
            <table class="no-border vertical-center mb-10" width="90%">
                <tbody>
                    <tr class="no-border mb-10">
                        <td class="no-border vertical-center label-align" width="11%"><span class="red">*</span>延保方案：</td>
                        <td class="no-border vertical-center value-align" width="88%">
                            <?php
                            switch($data["partno"]["machine_type"]){
                                case 0:
                                    echo "新机";
                                    break;
                                case 1:
                                    echo "二手设备";
                                    break;
                                case 2:
                                    echo "设备租赁";
                                    break;
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
            switch($data["partno"]["machine_type"]){
                case 0:
                    $html=Html::beginTag("table",["width"=>"90%","class"=>"no-border vertical-center mb-10"]);
                    $html.=Html::beginTag("tr",["class"=>"no-border mb-10"]);
                    $html.=Html::tag("td","出厂年限：",["width"=>"11%","class"=>"no-border vertical-center label-align"]);
                    $html.=Html::tag("td",$data['machine']['out_year'],["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                    $html.=Html::endTag("tr");
                    echo $html.=Html::endTag("table");
                    break;
                case 1:
                    $html=Html::beginTag("table",["width"=>"90%","class"=>"no-border vertical-center mb-10"]);
                    $html.=Html::beginTag("tr",["class"=>"no-border mb-10"]);
                    $html.=Html::tag("td","出厂年限：",["width"=>"11%","class"=>"no-border vertical-center label-align"]);
                    $html.=Html::tag("td",$data['machine']['out_year'],["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                    $html.=Html::endTag("tr");
                    $html.=Html::endTag("table");

                    $html.=Html::beginTag("table",["width"=>"90%","class"=>"no-border vertical-center mb-10"]);
                    $html.=Html::beginTag("tr",["class"=>"no-border mb-10"]);
                    $html.=Html::tag("td","库存：",["width"=>"11%","class"=>"no-border vertical-center label-align"]);
                    $html.=Html::tag("td",$data['machine']['stock_num'],["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                    $html.=Html::endTag("tr");
                    $html.=Html::endTag("table");


                    $html.=Html::beginTag("table",["width"=>"90%","class"=>"no-border vertical-center mb-10"]);
                    $html.=Html::beginTag("tr",["class"=>"no-border mb-10"]);
                    $html.=Html::tag("td","新旧程度：",["width"=>"11%","class"=>"no-border vertical-center label-align"]);
                    $html.=Html::tag("td",$data['machine']['recency'],["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                    $html.=Html::endTag("tr");
                    $html.=Html::endTag("table");
                    echo $html;
                    break;
                case 2:
                    $html=Html::beginTag("table",["width"=>"90%","class"=>"no-border vertical-center mb-10"]);
                    $html.=Html::beginTag("tr",["class"=>"no-border mb-10"]);
                    $html.=Html::tag("td","出厂年限：",["width"=>"11%","class"=>"no-border vertical-center label-align"]);
                    $html.=Html::tag("td",$data['machine']['out_year'],["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                    $html.=Html::endTag("tr");
                    $html.=Html::endTag("table");


                    $html.=Html::beginTag("table",["width"=>"90%","class"=>"no-border vertical-center mb-10"]);
                    $html.=Html::beginTag("tr",["class"=>"no-border mb-10"]);
                    $html.=Html::tag("td","租期：",["width"=>"11%","class"=>"no-border vertical-center label-align"]);
                    $html.=Html::tag("td",$data['machine']['rentals']."  月",["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                    $html.=Html::endTag("tr");
                    $html.=Html::endTag("table");

                    $html.=Html::beginTag("table",["width"=>"90%","class"=>"no-border vertical-center mb-10"]);
                    $html.=Html::beginTag("tr",["class"=>"no-border mb-10"]);
                    $html.=Html::tag("td","租金：",["width"=>"11%","class"=>"no-border vertical-center label-align"]);
                    $html.=Html::tag("td",$data['machine']['rental_unit']."  RMB/月",["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                    $html.=Html::endTag("tr");
                    $html.=Html::endTag("table");

                    $html.=Html::beginTag("table",["width"=>"90%","class"=>"no-border vertical-center mb-10"]);
                    $html.=Html::beginTag("tr",["class"=>"no-border mb-10"]);
                    $html.=Html::tag("td","押金：",["width"=>"11%","class"=>"no-border vertical-center label-align"]);
                    $html.=Html::tag("td",$data['machine']['deposit']."  RMB",["width"=>"88%","class"=>"no-border vertical-center value-align"]);
                    $html.=Html::endTag("tr");
                    $html.=Html::endTag("table");

                    echo $html;
                    break;
            }
            ?>
            <div class="mb-20"style="margin-bottom: 10px;">
                <span style="font-size:14px;margin-left: 11px ">延保方案</span>
            </div>
            <div class="mb-20">
                <table class="table">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>延保时间(月)</th>
                        <th>延保费用(RMB)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(count($data["warr"])>0){ ?>
                    <?php foreach ($data["warr"] as $k=>$warr){ ?>
                        <tr>
                            <td><?=$k+1?></td>
                            <td><?=$warr["wrr_prd"]?></td>
                            <td><?=$warr["wrr_fee"]?></td>
                        </tr>
                    <?php } ?>
                    <?php }else{ ?>
                        <tr>
                            <td colspan="3">没有相关数据！</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>

            <div class="space-10"></div>
            <div class="space-10"></div>
            <div class="space-10"></div>

            <table class="no-border vertical-center mb-10" width="90%">
                <tbody>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-top label-align" width="11%">设备信息：</td>
                    <td class="details no-border vertical-center value-align" width="88%"><?=Html::decode($data["partno"]["details"])?></td>
                </tr>
                </tbody>
            </table>
        </div>
          <?php } ?>
        <div style="clear: both;"></div>
    </div>
<style>
    .head-second{background-color: #ffffff}
    #tab_bar {
        width: 950px;
        height: 20px;
        float: left;
    }
    #tab_bar ul {
        padding: 0px;
        margin: 0px;
        height: 23px;
        text-align: center;
    }

    #tab_bar li {
        list-style-type: none;
        float: left;
        width: 85px;
        height: 23px;
        background-color: #1f7ed0;
        margin-right: 5px;
        line-height: 23px;
        cursor: pointer;
        color: #ffffff;
    }

    .tab_css {
        width: 990px;
        /*height: 200px;*/
        /*background-color: darkgray;*/
        height: auto;
        display: none;
        float: left;
        margin-top: 20px;
    }
    ._house{height: 370px;width:950px;margin:0 auto;overflow: auto}
    ._ueditor{width: 635px;margin-left: 70px;}



    #tab4_content table{
        width: 100%;
    }
    #tab4_content label input{
        vertical-align: middle;
    }
    #tab4_content label:after{
        content: "";
    }
    #tab4_content tr{
        height: 50px;
        line-height: 50px;
    }

    #tab4_content table,#tab4_content tr,#tab4_content td{
        border: none;
    }
    .details img{
        max-width: 100%;
    }
</style>
<script>
    var myclick = function(v) {
        var llis = $(".tab_li");
        for(var i = 0; i < llis.length; i++) {
            var lli = llis[i];
            if(lli == document.getElementById("tab" + v)) {
                lli.style.backgroundColor = "#1f7ea0";
            } else {
                lli.style.backgroundColor = "#1f7ed0";
            }
        }

        var divs = document.getElementsByClassName("tab_css");
        for(var i = 0; i < divs.length; i++) {

            var divv = divs[i];

            if(divv == document.getElementById("tab" + v + "_content")) {
                divv.style.display = "block";
            } else {
                divv.style.display = "none";
            }
        }

    };

    $("#_downprod").click(function () {
        $.fancybox({
            autoSize: false,
            fitToView: false,
            height: 350,
            width: 510,
            closeClick: true,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= \yii\helpers\Url::to(['down-shelf'])?>"
        })
    })

</script>
