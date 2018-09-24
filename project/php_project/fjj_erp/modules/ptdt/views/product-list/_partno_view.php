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
        <h2 class="head-second mt-20" style="background-color: #d9f0ff;margin-top: 48px">料号:<span id="part_no"></span></h2>
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
                    <li class="tab_li" id="tab9" onclick="myclick(9)" style="display: none;">设备用途</li>
                </ul>
            </div>
        </div>
        <div class="tab_css" id="tab1_content" style="display: block">

            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                    <tr class="no-border mb-10">
                        <td width="11%" class="no-border vertical-center label-align">原产地：</td>
                        <td width="22%" class="no-border vertical-center value-align" id="pdt_origin"></td>
                        <td width="15%" class="no-border vertical-center label-align">型号：</td>
                        <td width="22%" class="no-border vertical-center value-align" id="tp_spec"></td>
                        <td width="11%" class="no-border vertical-center label-align">保质期（月）：</td>
                        <td width="18%" class="no-border vertical-center value-align" id="warranty_period"></td>
                    </tr>
                </tbody>
            </table>


            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                <tr class="no-border mb-10">
                    <td width="11%" class="no-border vertical-center label-align">销售包装数量：</td>
                    <td width="22%" class="no-border vertical-center value-align" id="pack_num"></td>
                    <td width="15%" class="no-border vertical-center label-align">最小起订量：</td>
                    <td width="22%" class="no-border vertical-center value-align" id="min_order"></td>
                    <td width="11%" class="no-border vertical-center label-align">供应商：</td>
                    <td width="18%" class="no-border vertical-center value-align" id="spp"></td>
                </tr>
                </tbody>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                <tr class="no-border mb-10">
                    <td width="11%" class="no-border vertical-center label-align">商品定位：</td>
                    <td width="22%" class="no-border vertical-center value-align" id="cm_pos"></td>
                    <td width="15%" class="no-border vertical-center label-align">L / T（天）：</td>
                    <td width="22%" class="no-border vertical-center value-align" id="l_t"></td>
                    <td width="11%" class="no-border vertical-center label-align">法务风险等级：</td>
                    <td width="18%" class="no-border vertical-center value-align" id="leg_lv"></td>
                </tr>
                </tbody>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                <tr class="no-border mb-10">
                    <td width="11%" class="no-border vertical-center label-align">是否可议价：</td>
                    <td width="22%" class="no-border vertical-center value-align" id="yn_inquiry"></td>
                    <td width="15%" class="no-border vertical-center label-align">是否保税：</td>
                    <td width="22%" class="no-border vertical-center value-align" id="yn_tax"></td>
                    <td width="11%" class="no-border vertical-center label-align">是否自提：</td>
                    <td width="18%" class="no-border vertical-center value-align" id="isselftake"></td>
                </tr>
                </tbody>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                <tr class="no-border mb-10">
                    <td width="11%" class="no-border vertical-center label-align">是否代理：</td>
                    <td width="22%" class="no-border vertical-center value-align" id="is_agent"></td>
                    <td width="15%" class="no-border vertical-center label-align">是否批次管理：</td>
                    <td width="22%" class="no-border vertical-center value-align" id="is_batch"></td>
                    <td width="11%" class="no-border vertical-center label-align">是否拳头商品：</td>
                    <td width="18%" class="no-border vertical-center value-align" id="is_first"></td>
                </tr>
                </tbody>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tbody>
                <tr class="no-border mb-10">
                    <td width="11%" class="no-border vertical-center label-align">备注：</td>
                    <td width="88%" class="no-border vertical-center value-align" id="marks"></td>
                </tr>
                </tbody>
            </table>

                <div class="mb-20 _house_box" style="display: none">
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
                        <tbody id="product_table" class="wh-box"><tr>
                            <td colspan="5">没有相关数据！</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
             <div class="tab_css pas-wrap" id="tab2_content" style="overflow-x:auto;overflow-y:hidden;">
                <table class="table" style="width:2400px;">
                <thead>
                <tr>
                    <th width="50">序号</th>
                    <th width="150">付款条件</th>
                    <th width="200">交货条件</th>
                    <th width="150">供应商编码</th>
                    <th width="150">供应商简称</th>
                    <th width="150">交货地点</th>
                    <th width="150">交易单位</th>
                    <th width="150">最小起订量</th>
                    <th width="150">交易币别</th>
                    <th width="150">L/T(天)</th>
                    <th width="150">量价区间</th>
                    <th width="150">采购价格</th>
                    <th width="150">有效期</th>
                </tr>
                </thead>
                <tbody id="product_table" class="pas-box"></tbody>
            </table>
        </div>
        <div class="tab_css" id="tab3_content">
            <p style="color: red;margin-bottom:10px;">提示：销售价格的“最小值”等于基本信息的“最小起订量”！</p>
            <table class="table">
                <thead>
                <tr>
                    <th>最小值</th>
                    <th>最大值</th>
                    <th>价格</th>
                    <th>币别</th>
                </tr>
                </thead>
                <tbody id="product_table" class="price-box"></tbody>
            </table>
        </div>
        <div class="tab_css attr-box" id="tab4_content"></div>
        <div class="tab_css" id="tab5_content">
            <p style="color: red;margin-bottom:10px;">提示：备货期的“最小值”等于基本信息的“最小起订量”！</p>
            <table class="table">
                <thead>
                <tr>
                    <th>最小值</th>
                    <th>最大值</th>
                    <th>备货时间（天）</th>
                </tr>
                </thead>
                <tbody id="product_table" class="stock-box"></tbody>
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
                <tbody id="product_table" class="ship-box"></tbody>
            </table>
        </div>
        <div class="tab_css" id="tab7_content">
            <div class="mb-20">
                <label class="width-10" for=""><span class="red">*</span></label>
                <?=Html::radio("yn_free_delivery","",["disabled"=>true])?>&nbsp;全国免运费&nbsp;
                <?=Html::radio("yn_free_delivery","",["disabled"=>true])?>&nbsp;全国部分城市免运费&nbsp;
                <?=Html::radio("yn_free_delivery","",["disabled"=>true])?>&nbsp;全国不免运费&nbsp;
                <?=Html::radio("yn_free_delivery","",["disabled"=>true])?>&nbsp;全国部分城市不免运费&nbsp;
            </div>
            <div class="mb-20 deliv-wrap" style="display: none">
                <table class="table">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>国家</th>
                        <th>省份</th>
                        <th>城市</th>
                    </tr>
                    </thead>
                    <tbody id="product_table" class="deliv-box"></tbody>
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
                    <tbody id="product_table" class="pack-box">
                    <tr>
                        <td>1</td>
                        <td class="pdt_length"></td>
                        <td class="pdt_width"></td>
                        <td class="pdt_height"></td>
                        <td class="pdt_weight"></td>
                        <td><input type="checkbox" id="yn_pa_fjj"  disabled></td>
                        <td><input type="checkbox" id="yn_pallet"  disabled></td>
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
                        <th width="100">净重</th>
                        <th width="100">包材名称</th>
                        <th width="100">销售包装内商品数量</th>
                    </tr>
                    </thead>
                    <tbody id="product_table" class="pack-box">
                    <tr>
                        <td>2</td>
                        <td class="pdt_length"></td>
                        <td class="pdt_width"></td>
                        <td class="pdt_height"></td>
                        <td class="pdt_weight"></td>
                        <td class="net_weight"></td>
                        <td class="pdt_mater"></td>
                        <td class="pdt_qty"></td>
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
                    <tbody id="product_table" class="pack-box">
                    <tr>
                        <td>3</td>
                        <td class="pdt_length"></td>
                        <td class="pdt_width"></td>
                        <td class="pdt_height"></td>
                        <td class="pdt_weight"></td>
                        <td class="pdt_mater"></td>
                        <td class="pdt_qty"></td>
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
                    <tbody id="product_table" class="pack-box">
                    <tr>
                        <td>4</td>
                        <td class="pdt_length"></td>
                        <td class="pdt_width"></td>
                        <td class="pdt_height"></td>
                        <td class="pdt_weight"></td>
                        <td class="pdt_mater"></td>
                        <td class="pdt_qty"></td>
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
                    <tbody id="product_table" class="pack-box">
                    <tr>
                        <td>5</td>
                        <td class="pdt_length"></td>
                        <td class="pdt_width"></td>
                        <td class="pdt_height"></td>
                        <td class="pdt_weight"></td>
                        <td class="plate_num"></td>
                        <td class="pdt_qty"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
            <div class="tab_css" id="tab9_content" style="display: none;">
            <table class="no-border vertical-center mb-10" width="90%">
                <tbody>
                    <tr class="no-border mb-10">
                        <td class="no-border vertical-center label-align" width="11%"><span class="red">*</span>延保方案：</td>
                        <td class="no-border vertical-center value-align" width="88%" id="machine_type"></td>
                    </tr>
                </tbody>
            </table>

            <div class="machine-data"></div>

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
                    <tbody class="warr-box"></tbody>
                </table>

            </div>

            <div class="space-10"></div>
            <div class="space-10"></div>
            <div class="space-10"></div>

            <table class="no-border vertical-center mb-10" width="90%">
                <tbody>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-middle label-align" width="11%">设备信息：</td>
                    <td class="details no-border vertical-center value-align" width="88%" id="details"></td>
                </tr>
                </tbody>
            </table>
        </div>
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

    #tab8_content table{
        table-layout: fixed;
        word-wrap: break-word;
    }
</style>
<script>
    var requestUrl="<?=\yii\helpers\Url::to(['partno-info','id'=>\Yii::$app->request->get('id')])?>";
    $.ajax({
        type:"get",
        url:requestUrl,
        data:{name:"base"},
        async:true,
        dataType:"json",
        success:function(data){
            for(x in data){
                if(x=="details" || x=="marks"){
                    $("#"+x) && $("#"+x).html(data[x]);
                    continue;
                }
                var text="/";
                if(x=="cm_pos"){
                    if(data[x]==1) text="高";
                    if(data[x]==2) text="中";
                    if(data[x]==3) text="低";
                }else if(x=="leg_lv"){
                    if(data[x]==0) text="高";
                    if(data[x]==1) text="中";
                    if(data[x]==2) text="低";
                }else if(x=="is_agent" || x=="is_batch" || x=="is_first" || x=="yn_discuss" || x=="isselftake" || x=="yn_inquiry" || x=="yn_tax"){
                    if(data[x]==0) text="否";
                    if(data[x]==1) text="是";
                }else if(x=="machine_type"){
                    if(data[x]==0) text="新机";
                    if(data[x]==1) text="二手设备";
                    if(data[x]==2) text="设备租赁";
                }else{
                    text=data[x];
                }
                $("#"+x) && $("#"+x).html(text);
            }

            $("#yn_pa_fjj").prop("checked",data.yn_pa_fjj);
            $("#yn_pallet").prop("checked",data.yn_pallet);

            if(data.isselftake=="1"){
                $("._house_box").show();
                $(".wh-box").empty();
                $.ajax({
                    type:"get",
                    url:requestUrl,
                    data:{name:"wh"},
                    async:true,
                    dataType:"json",
                    success:function(res){
                        res=res.filter(function(row){
                            return row.selected;
                        });
                        if(res.length>0){
                            res.forEach(function(row,index){
                                var tr=$("<tr></tr>");
                                $("<td></td>").text(index+1).appendTo(tr);
                                $("<td></td>").text(row.wh_name).appendTo(tr);
                                $("<td></td>").text(row.wh_address).appendTo(tr);
                                $("<td></td>").text(row.staff_name).appendTo(tr);
                                $("<td></td>").text(row.staff_mobile).appendTo(tr);
                                tr.appendTo(".wh-box");
                            });
                        }else{
                            var tr=$("<tr></tr>");
                            $("<td colspan='5'></td>").text("没有相关数据！").appendTo(tr);
                            tr.appendTo(".wh-box");
                        }
                    }
                });
            }


            $("[name='yn_free_delivery']").eq(data.yn_free_delivery).prop("checked",true);
            if(data.yn_free_delivery==1 || data.yn_free_delivery==3){
                $(".deliv-wrap").show();
                $.ajax({
                    type:"get",
                    url:requestUrl,
                    data:{name:"deliv"},
                    async:true,
                    dataType:"json",
                    success:function(data){
                        if(data.length>0){
                            data.forEach(function(row,index){
                                var tr=$("<tr></tr>");
                                $("<td></td>").text(index+1).appendTo(tr);
                                $("<td></td>").text(row.country_name).appendTo(tr);
                                $("<td></td>").text(row.province_name).appendTo(tr);
                                $("<td></td>").text(row.city_name).appendTo(tr);
                                tr.appendTo($(".deliv-box"));
                            });
                        }else{
                            $("<tr><td colspan='4'>没有相关数据！</td></tr>").appendTo($(".deliv-box"));
                        }
                    }
                });
            }

            if(data.pdt.isDevice=="1"){
                $("#tab9").show();
                $.ajax({
                    type:"get",
                    url:requestUrl,
                    data:{name:"machine"},
                    async:true,
                    dataType:"json",
                    success:function(res){
                        var table=$("<table width='90%' class='no-border vertical-center mb-10'></table>");
                        var tr=$("<tr class='no-border mb-10'></tr>");
                        $("<td width='11%' class='no-border vertical-center label-align'></td>").text("出厂年限：").appendTo(tr);
                        $("<td width='88%' class='no-border vertical-center value-align'></td>").text(res.out_year).appendTo(tr);
                        tr.appendTo(table);
                        switch(data.machine_type){
                            case "1":
                                var tr=$("<tr class='no-border mb-10'></tr>");
                                $("<td width='11%' class='no-border vertical-center label-align'></td>").text("库存：").appendTo(tr);
                                $("<td width='88%' class='no-border vertical-center value-align'></td>").text(res.stock_num).appendTo(tr);
                                tr.appendTo(table);
                                var tr=$("<tr class='no-border mb-10'></tr>");
                                $("<td width='11%' class='no-border vertical-center label-align'></td>").text("新旧程度：").appendTo(tr);
                                $("<td width='88%' class='no-border vertical-center value-align'></td>").html(res.recency).appendTo(tr);
                                tr.appendTo(table);
                                break;
                            case "2":
                                var tr=$("<tr class='no-border mb-10'></tr>");
                                $("<td width='11%' class='no-border vertical-center label-align'></td>").text("租期：").appendTo(tr);
                                $("<td width='88%' class='no-border vertical-center value-align'></td>").text(res.rentals+" 月").appendTo(tr);
                                tr.appendTo(table);
                                var tr=$("<tr class='no-border mb-10'></tr>");
                                $("<td width='11%' class='no-border vertical-center label-align'></td>").text("租金：").appendTo(tr);
                                $("<td width='88%' class='no-border vertical-center value-align'></td>").text(res.rental_unit+"  RMB/月").appendTo(tr);
                                tr.appendTo(table);
                                var tr=$("<tr class='no-border mb-10'></tr>");
                                $("<td width='11%' class='no-border vertical-center label-align'></td>").text("押金：").appendTo(tr);
                                $("<td width='88%' class='no-border vertical-center value-align'></td>").text(res.deposit+" RMB").appendTo(tr);
                                tr.appendTo(table);
                                break;
                        }
                        $(".machine-data").append(table);


                        if(res.warr.length>0){
                            res.warr.forEach(function(row,index){
                                var tr=$("<tr></tr>");
                                $("<td></td>").text(index+1).appendTo(tr);
                                $("<td></td>").text(row.wrr_prd).appendTo(tr);
                                $("<td></td>").text(row.wrr_fee).appendTo(tr);
                                $(".warr-box").append(tr);
                            });
                        }else{
                            var tr=$("<tr></tr>");
                            $("<td colspan='3'></td>").text("没有相关数据！").appendTo(tr);
                            $(".warr-box").append(tr);
                        }
                    }
                });

            }
        }
    });



    $.ajax({
        type:"get",
        url:requestUrl,
        data:{name:"pas"},
        async:true,
        dataType:"json",
        success:function(data){
            if(data.length>0){
                data.forEach(function(row,index){
                    var tr=$("<tr></tr>");
                    $("<td></td>").text(index+1).appendTo(tr);
                    $("<td></td>").text(row.payment_terms).appendTo(tr);
                    $("<td></td>").text(row.trading_terms).appendTo(tr);
                    $("<td></td>").text(row.supplier_code).appendTo(tr);
                    $("<td></td>").text(row.supplier_name_shot).appendTo(tr);
                    $("<td></td>").text(row.delivery_address).appendTo(tr);
                    $("<td></td>").text(row.unite).appendTo(tr);
                    $("<td></td>").text(row.min_order).appendTo(tr);
                    $("<td></td>").text(row.currency).appendTo(tr);
                    $("<td></td>").text(row.limit_day).appendTo(tr);
                    $("<td></td>").text(row.num_area).appendTo(tr);
                    $("<td></td>").text(row.buy_price).appendTo(tr);
                    $("<td></td>").text(row.expiration_date).appendTo(tr);
                    tr.appendTo($(".pas-box"));
                });
            }else{
                $(".pas-wrap").css("overflow","hidden").append('<div style="text-align: center;height:30px;line-height: 30px;border: #eee 1px solid;">没有相关数据！</div>');
            }
        }
    });


    $.ajax({
        type:"get",
        url:requestUrl,
        data:{name:"price"},
        async:true,
        dataType:"json",
        success:function(data){
            if(data.length>0){
                data.forEach(function(row,index){
                    var tr=$("<tr></tr>");
                    $("<td></td>").text(row.minqty).appendTo(tr);
                    $("<td></td>").text(row.maxqty).appendTo(tr);
                    $("<td></td>").text(row.price==-1?"面议":row.price).appendTo(tr);
                    $("<td></td>").text(row.currency_name).appendTo(tr);
                    tr.appendTo($(".price-box"));
                });
            }else{
                $('<tr><td colspan="4">没有相关数据！</td></tr>').appendTo($(".price-box"));
            }
        }
    });






    $.ajax({
        type:"get",
        url:requestUrl,
        data:{name:"attrs"},
        async:true,
        dataType:"json",
        success:function(data){
            var table=$("<table class='no-border vertical-center mb-10'></table>");
            for(x in data){
                var tr=$("<tr class='no-border mb-10'></tr>");
                $("<td class='no-border vertical-center label-align' style='white-space: nowrap;'></td>").html(data[x].name+"：").appendTo(tr);
                var td=$("<td class='no-border vertical-center value-align' style='word-wrap: break-word;'></td>");
                switch(data[x].type){
                    case "0":
                            var r=[];
                                for(var m in data[x].selected){
                                    r.push(data[x].selected[m]);
                                }
                            td.html(r.join(","));
                        break;
                    case "1":
                            var r=[];
                                for(var m in data[x].selected){
                                    r.push(data[x].selected[m]);
                                }
                            td.html(r.join(","));
                        break;
                    case "2":
                            var r=[];
                                for(var m in data[x].selected){
                                    r.push(data[x].selected[m]);
                                }
                            td.html(r.join(","));
                        break;
                    case "3":
                        td.text(data[x].val);
                        break;
                }
                td.appendTo(tr);
                tr.appendTo(table);
            };
            table.appendTo($(".attr-box"));
        }
    });






    $.ajax({
        type:"get",
        url:requestUrl,
        data:{name:"stock"},
        async:true,
        dataType:"json",
        success:function(data){
            if(data.length>0){
                data.forEach(function(row,index){
                    var tr=$("<tr></tr>");
                    $("<td></td>").text(row.min_qty).appendTo(tr);
                    $("<td></td>").text(row.max_qty).appendTo(tr);
                    $("<td></td>").text(row.stock_time).appendTo(tr);
                    tr.appendTo($(".stock-box"));
                });
            }else{
                $("<tr><td colspan='3'>没有相关数据！</td></tr>").appendTo($(".stock-box"));
            }
        }
    });



    $.ajax({
        type:"get",
        url:requestUrl,
        data:{name:"ship"},
        async:true,
        dataType:"json",
        success:function(data){
            if(data.length>0){
                data.forEach(function(row,index){
                    var tr=$("<tr></tr>");
                    $("<td></td>").text(index+1).appendTo(tr);
                    $("<td></td>").text(row.country_name).appendTo(tr);
                    $("<td></td>").text(row.province_name).appendTo(tr);
                    $("<td></td>").text(row.city_name).appendTo(tr);
                    tr.appendTo($(".ship-box"));
                });
            }else{
                $("<tr><td colspan='4'>没有相关数据！</td></tr>").appendTo($(".ship-box"));
            }
        }
    });


    $.ajax({
        type:"get",
        url:requestUrl,
        data:{name:"pack"},
        async:true,
        dataType:"json",
        success:function(data){
            $("#pack_num").text(data[1].pdt_qty);
            data.forEach(function(row,index){
                $(".pack-box").eq(index).find(".pdt_width").html(data[index].pdt_width);
                $(".pack-box").eq(index).find(".pdt_height").html(data[index].pdt_height);
                $(".pack-box").eq(index).find(".pdt_length").html(data[index].pdt_length);
                $(".pack-box").eq(index).find(".pdt_weight").html(data[index].pdt_weight);
                $(".pack-box").eq(index).find(".net_weight").html(data[index].net_weight);
                $(".pack-box").eq(index).find(".pdt_mater").html(data[index].pdt_mater);
                $(".pack-box").eq(index).find(".pdt_qty").html(data[index].pdt_qty);
                $(".pack-box").eq(index).find(".plate_num").html(data[index].plate_num);
            });
        }
    });
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

</script>
