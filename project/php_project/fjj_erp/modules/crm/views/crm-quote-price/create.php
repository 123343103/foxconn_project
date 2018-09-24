<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/27
 * Time: 上午 11:49
 */
use yii\widgets\ActiveForm;
$this->title="新增报价";
$this->params['homeLike'] = ['label'=>'客户关系系统'];
$this->params['breadcrumbs'][] = ['label' => '报价单'];
$this->params['breadcrumbs'][] = ['label' => '新增报价'];
?>

<?php $form=ActiveForm::begin([
    "id"=>"add-form",
    "method"=>"post"
]);?>
<div class="content">
    <div class="easyui-tabs">
        <div title="报价">
            <div class="mt-20">
                <h2 class="head-second">报价基本信息</h2>
            </div>

            <div class="mb-20">
                <label class="width-100" for="">客户名称</label>
                <input id="custom_name" type="text" class="width-300 easyui-validatebox" data-options="required:true" readonly>
                <input id="custom_id" name="CrmSaleQuotedprice[cust_id]" type="hidden" class="width-300">
                <a class="width-20" href="javascript:void(0)" id="custom-selector">选择</a>
                <label class="width-100" for="">报价日期</label>
                <input name="CrmSaleQuotedprice[saph_date]" type="text" class="width-100 select-date" value="<?=date('Y-m-d')?>" readonly>
                <label class="width-100" for="">报价人</label>
                <input name="CrmSaleQuotedprice[applicant]" type="text" class="width-100" value="<?=\Yii::$app->user->identity->staff->staff_name?>" readonly>
                <input name="CrmSaleQuotedprice[creator]" type="hidden" class="width-100" value="<?=\Yii::$app->user->identity->staff->staff_id?>">
            </div>

            <div class="mb-20">
                <label class="width-100" for="">联系人</label>
                <input id="cust_contacts" type="text" class="width-300" readonly>&nbsp;
                <input type="hidden" id="cust_sale" name="CrmSaleQuotedprice[branch_district]">
                <input type="hidden" id="cust_salearea" name="CrmSaleQuotedprice[branch_sale_area]">
                <label class="width-100 ml-20" for="">公司名称</label>
                <input id="corp_name" type="text" class="width-300 easyui-validatebox" readonly data-options="required:true">
                <input id="corp_id" name="CrmSaleQuotedprice[corp_id]" type="hidden" class="width-300">
                <a class="width-20" href="javascript:void(0)" id="corp-selector">选择</a>
            </div>


            <div class="mb-20">
                <label class="width-100" for="">TEL</label>
                <input id="cust_tel1" type="text" class="width-100" readonly>
                <label class="width-100" for="">FAX</label>
                <input id="cust_fax" type="text" class="width-100" readonly>
                <label class="width-100 ml-20" for="">TEL</label>
                <input type="text" class="width-100" readonly>
                <label class="width-100" for="">FAX</label>
                <input type="text" class="width-100" readonly>
            </div>

            <div class="mb-20">
                <label class="width-100" for="">送货地址</label>
                <input id="cust_readress" class="width-800" type="text">
            </div>

            <div class="mb-20">
                <label class="width-100" for="">付款币别</label>
                <select class="width-100" name="CrmSaleQuotedprice[currency]" id="">
                    <?php foreach($dropdown_list['currency'] as $item){ ?>
                        <option value="<?=$item->cur_id?>"> <?=$item->cur_sname?></option>
                    <?php } ?>
                </select>
                <label class="width-100" for="">付款方式</label>
                <select class="width-100" name="CrmSaleQuotedprice[pay_type]" id="">
                    <?php foreach($dropdown_list['pay_type'] as $item){ ?>
                        <option value="<?=$item->pac_id;?>"><?=$item->pac_sname;?></option>
                    <?php } ?>
                </select>
                <label class="width-100" for="">付款条件</label>
                <select class="width-100" name="CrmSaleQuotedprice[payment_terms]" id="payment_terms">
                    <?php foreach($dropdown_list['pay_cond'] as $item){ ?>
                        <option value="<?=$item->pat_id;?>"><?=$item->pat_sname;?></option>
                    <?php } ?>
                </select>
                <label class="width-100" for="">交易条件</label>
                <select class="width-100" name="CrmSaleQuotedprice[trading_terms]" id="trading_terms">
                    <?php foreach($dropdown_list['dev_cond'] as $item){ ?>
                        <option value="<?=$item->dec_id?>"><?=$item->dec_sname?></option>
                    <?php } ?>
                </select>
            </div>


            <div class="mb-20">
                <label class="width-100" for="">交易模式</label>
                <span>
            <input name="mode" type="radio" /> 外购外销
            <input name="mode" type="radio" class="ml-10" /> 内购外销
        </span>
            </div>

            <div class="mb-20">
                <label class="width-100" for="" style="vertical-align: top;">备注</label>
                <textarea name="CrmSaleQuotedprice[remark]" style="width:800px;height: 100px;outline: none;"></textarea>
            </div>


            <div class="mt-20">
                <h2 class="head-second">
                    商品信息
                    <span class="float-right mr-20">
            <a href="javascript:void(0)" id="product-selector">新增商品</a>
            <a href="javascript:void(0)" id="product-remove">删除商品</a>
            <a href="javascript:void(0)" id="price-info">商品定价信息</a>
        </span>
                </h2>
            </div>

            <div id="product-info"></div>






            <div class="mt-20 text-center">
                <button type="submit" class="button-blue">确定</button>
                <button class="button-white">取消</button>
            </div>
        </div>
        <div title="试算">
            <div class="mt-20">
                <h2 class="head-second">试算基本信息</h2>
            </div>

            <div class="mb-20">
                <label class="width-100" for="">客户名称</label>
                <span id="testcount_cust_sname" class="width-100"></span>
                <input id="custom_id" name="SaleTestcount[cust_id]" type="hidden" class="width-300">
                <label class="width-100" for="">试算日期</label>
                <span id="testcount_date" class="width-100"><?=date('Y-m-d');?><input name="SaleTestcount[quotedprice_date]" type="hidden" value="<?=date('Y-m-d');?>"></span>
                <label class="width-100" for="">交易法人</label>
                <span class="width-100">xxx</span>
                <label class="width-100" for="">交易模式</label>
                <span class="width-100">xxx</span>
            </div>

            <div class="mb-20">
                <label class="width-100" style="vertical-align: top;" for="">交易条款</label>
                <span>
                <table class="table" style="width:800px;">
                    <tr>
                        <td width="150"><label for="">客户</label></td>
                        <td>
                            <label class="width-120" for="">客戶付款條件</label>
                            <span id="testcount_cust_payment_terms" class="width-150"></span>
                            <label class="width-120" for="">客戶交易條件</label>
                            <span id="testcount_cust_trading_terms" class="width-150"></span>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="">供应商</label></td>
                        <td>
                            <label class="width-120" for="">供應商付款條件</label>
                            <input name="SaleTestcount[supplier_payment_terms]" type="text" class="width-150">
                            <label class="width-120" for="">供應商交易條件</label>
                            <input name="SaleTestcount[supplier_trading_terms]" type="text" class="width-150">
                        </td>
                    </tr>
                </table>
                </span>
            </div>


            <div class="mb-20">
                <label class="width-100" style="vertical-align: top;" for="">客户风险</label>
                <span>
                <table id="is_risk" class="table" style="width:800px;">
                    <input type="hidden" id="cust_risk" name="SaleTestcount[cust_risk]">
                    <tr id="has_risk">
                        <td width="150"><label for="">无风险</label></td>
                        <td>
                            <input type="radio" name="SaleTestcount[is_pre_pay]"> 预收款
                            <input type="radio" class="ml-20" name="SaleTestcount[is_ensure]"> 银行/保险公司担保
                            <input type="radio" class="ml-20"> 其他
                            <input type="text" class="width-120 ml-20" name="SaleTestcount[others_risk]">
                        </td>
                    </tr>
                    <tr id="not_risk">
                        <td><label for="">有风险</label></td>
                        <td>
                            <input type="radio" name="SaleTestcount[is_group_auth]" value="1"> 集团授信
                            <label class="width-100" for="">额度</label>
                            <span>
                                RMB <input name="SaleTestcount[group_auth_limit]" type="text" class="width-120"> 元
                            </span>
                            <label class="width-100">帐期</label>
                            <span>
                                <input type="text" class="width-120" name="SaleTestcount[group_auth_period]"> 天
                            </span>
                        </td>
                    </tr>
                </table>
                </span>
            </div>


            <div class="mt-20">
                <h2 class="head-second">
                    试算商品信息
                    <span class="float-right mr-20">币别：（RMB/元）</span>
                </h2>

                <div style="overflow-x: scroll;">
                    <table id="testcount_table" class="table" style="width:2000px;">
                        <thead>
                        <tr>
                            <th colspan="4">商品基本信息</th>
                            <th colspan="2">商品定价</th>
                            <th rowspan="2">物流费用(B)</th>
                            <th colspan="2">销售单价范围</th>
                            <th rowspan="2">销售报价（未税）</th>
                            <th rowspan="2">销售报价（含税）</th>
                            <th rowspan="2">利润率</th>
                            <th rowspan="2">稅率</th>
                            <th rowspan="2">备注</th>
                        </tr>
                        <tr>
                            <th>品名</th>
                            <th>规格型号</th>
                            <th>单位</th>
                            <th>数量</th>
                            <th>下限未税</th>
                            <th>上限未税</th>
                            <th>下限含税</th>
                            <th>上限含税</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>






            <div class="mt-20 text-center">
                <button type="submit" class="button-blue">确定</button>
                <button class="button-white">取消</button>
            </div>
        </div>
    </div>
</div>
<?php $form=ActiveForm::end();?>


<div id="product-select-box" style="display: none;width:630px;height: 600px;">
    <h2 class="head-three">选择商品</h2>
    <div class="mt-20 mb-20">
        <input type="text" placeholder="请输入商品名称">
        <button class="button-blue" id="product-search">搜索</button>
        <a href="<?=\yii\helpers\Url::to(['/ptdt/product-dvlp/add'])?>" class="float-right">新增商品</a>
    </div>
    <table id="product-list"></table>
    <div class="mt-20 text-right">
        <button class="button button-blue ensure">确定</button>
        <button class="button button-white cancel">取消</button>
    </div>
</div>

<div id="price-info-box" style="display: none;">
    <table id="price-info-table" style="width:2000px;"></table>
</div>

<script>
    window.onload=function(){



        ajaxSubmitForm($("#add-form"));

        $(".easyui-tabs").tabs({
            onSelect:function(title,index){
                if(index==1){
                    $("#testcount_cust_sname").html($("#custom_name").val());
                    $("#testcount_cust_payment_terms").html($("#payment_terms option:selected").html()+"<input name='SaleTestcount[cust_payment_terms]' type='hidden' value='"+$("#payment_terms option:selected").html()+"'>");
                    $("#testcount_cust_trading_terms").html($("#trading_terms option:selected").html()+"<input name='SaleTestcount[cust_trading_terms]' type='hidden' value='"+$("#trading_terms option:selected").html()+"'>");
                    var rows=$("#product-info").datagrid("getData").rows;

                    str="";
                    for(var x in rows){
                        str+="<tr><input type='hidden' name='SaleTestcountChild[pdt_id][]' value='"+rows[x].pdt_id+"'>";
                        str+="<td>"+rows[x].pdt_name+"</td>";
                        str+="<td>"+rows[x].tp_spec+"</td>";
                        str+="<td>"+rows[x].unit+"</td>";
                        str+="<td>"+$(".num").eq(x).val()+"<input type='hidden' name='SaleTestcountChild[num][]' value='"+$(".num").eq(x).val()+"'></td>";
                        str+="<td><input type='text' name='SaleTestcountChild[ws_local_lower_price][]'></td>";
                        str+="<td><input type='text' name='SaleTestcountChild[ws_local_upper_price][]'></td>";
                        str+="<td><input type='text' name='SaleTestcountChild[local_logistics_cost][]'></td>";
                        str+="<td><input type='text'></td>";
                        str+="<td><input type='text'></td>";
                        str+="<td><input type='text' name='SaleTestcountChild[ws_local_quoted_price][]'></td>";
                        str+="<td><input type='text' name='SaleTestcountChild[local_quoted_price][]'></td>";
                        str+="<td><input type='text' name='SaleTestcountChild[profit_margin][]'></td>";
                        str+="<td><input type='text'></td>";
                        str+="<td>"+$(".remark").eq(x).val()+"</td>";
                        str+="</tr>";
                    }
                    $("#testcount_table tbody").html(str);
                }
            }
        });

        $("#is_risk").find(":radio").on("click",function(){
            $("#is_risk").find(":radio").prop("checked",false);
            $(this).prop("checked",true);
            if($(this).parents("tr").attr("id")=="has_risk"){
                $("#cust_risk").val(1);
            }else{
                $("#cust_risk").val(0);
            }
        });

        //选择客户
        $("#custom-selector").click(function(){
            $.fancybox({
                href:"<?=\yii\helpers\Url::to(['select-customer'])?>",
                width:850,
                height:600,
                padding:0,
                autoSize: false,
                scrolling:false,
                type : 'iframe'
            });
        });


        //选择公司
        $("#corp-selector").click(function() {
            $.fancybox({
                href: "<?=\yii\helpers\Url::to(['select-corp'])?>",
                width: 640,
                height: 600,
                padding: 0,
                autoSize: false,
                scrolling: false,
                type: 'iframe'
            });
        });




        //选择产品
        $("#product-selector").click(function(){
            $.fancybox({
                href:"#product-select-box"
            });
            $("#product-list").datagrid({
                url:"<?=\yii\helpers\Url::to(['product-list'])?>",
                method:"get",
                pagination:true,
                pageSize:5,
                pageList:["5"],
                checkOnSelect:true,
                columns:[[
                    {field:"ck",checkbox:true},
                    {field:"pdt_no",title:"料号",width:200},
                    {field:"pdt_name",title:"商品名称",width:200},
                    {field:"type_1",title:"商品类别",width:200}
                ]],
                onLoadSuccess: function () {
                    setMenuHeight();
                }
            });
        });


        //产品搜索
        $("#product-search").click(function(){
            $("#product-list").datagrid("load",{
                pdt_name:$("#product-search").prev("input").val()
            });
        });

        $("#product-info").datagrid({
            singleSelect:true,
            checkOnSelect:true,
            columns:[[
                {field:"ck",checkbox:true},
                {field:"pdt_no",title:"料号",width:200,formatter:function(value,row,index){
                    return row.pdt_no+"<input name='CrmSaleQuotedpriceChild[part_no][]' type='hidden' value='"+row.pdt_no+"'>";
                }},
                {field:"pdt_name",title:"商品名称",width:200},
                {field:"type_1",title:"商品类别",width:200},
                {field:"s",title:"商品库存",width:200,formatter:function(value,row,index){
                    return "<input id='num' class='num' name='CrmSaleQuotedpriceChild[num][]' type='text' value=''>";
                }},
                {field:"tp_spec",title:"商品规格型号",width:200},
                {field:"brand",title:"品牌",width:200,formatter:function(value,row,index){
                    return row.brand_name+"<input name='CrmSaleQuotedpriceChild[brand][]' type='hidden' value='"+row.brand_name+"'>";
                }},
                {field:"unit",title:"单位",width:200},
                {field:"s2",title:"还需采购",width:200,formatter:function(){
                    return "";
                }},
                {field:"s3",title:"折扣率(%)",width:200,formatter:function(){
                    return "<input name='tax_rate' class='tax_rate' type='hidden' value='10'><input class='discount_rate' type='text'>";
                }},
                {field:"s6",title:"单价(未税)",width:200,formatter:function(){
                    var str="<input class='ws_local_unit_price' name='CrmSaleQuotedpriceChild[ws_local_unit_price][]' type='text'>";
                    str+="<input class='local_unit_price' name='CrmSaleQuotedpriceChild[local_unit_price][]' type='hidden'>";
                    return str;
                }},
                {field:"s7",title:"价税合计(未税)",width:200,formatter:function(){
                    var str="<input class='ws_local_total_price' name='CrmSaleQuotedpriceChild[ws_local_total_price][]' type='text'>";
                    str+="<input class='local_total_price' name='CrmSaleQuotedpriceChild[local_total_price][]' type='hidden'>";
                    return str;
                }},
                {field:"s8",title:"折扣后金额",width:200,formatter:function(){
                    return "<span class='discount_local_total_price'></span>";
                }},
                {field:"s5",title:"是否赠品",width:200,formatter:function(){
                    return "<input type='checkbox' >";
                }},
                {field:"s4",title:"需求交期",width:200,formatter:function(){
                    return "<input type='text' class='select-date' >";
                }},
                {field:"remark",title:"备注",width:200,formatter:function(){
                    return "<input class='remark' name='CrmSaleQuotedpriceChild[remark][]' type='text'>";
                }}
            ]]
        });
        showEmpty($("#product-info"));
        $("#product-select-box .ensure").click(function(){

            var existsRows=$("#product-info").datagrid("getRows");

            var newRows=$("#product-list").datagrid("getChecked");
            for(var x=0;x<newRows.length;x++){
                var isExists=0;
                for(var y=0;y<existsRows.length;y++){
                    if(newRows[x].pdt_id==existsRows[y].pdt_id){
                        isExists=1;
                        break;
                    }
                }
                if(!isExists){
                    $("#product-info").datagrid("insertRow",{index:1,row:newRows[x]});
                    $('.select-date').click(function () {
                        jeDate({
                            dateCell: this,
                            zIndex:8831,
                            format: "YYYY-MM-DD",
                            skinCell: "jedatedeep",
                            isTime: false,
                            okfun:function(elem) {
                            },
                            //点击日期后的回调, elem当前输入框ID, val当前选择的值
                            choosefun:function(elem) {
                            }
                        })
                    });
                }
            }

//            var str="";
//            for(var x=0;x<rows.length;x++){
//                str+="<tr>\
//                    <td><input class='sel-row' type='checkbox' value='"+rows[x].pdt_no+"'></td>\
//                    <td>"+rows[x].pdt_no+"<input name='CrmSaleQuotedpriceChild[part_no][]' type='hidden' value='"+rows[x].pdt_no+"'></td>\
//                    <td>"+rows[x].pdt_name+"</td>\
//                    <td>"+rows[x].type_1+"</td>\
//                    <td></td>\
//                    <td>"+rows[x].tp_spec+"</td>\
//                    <td>"+rows[x].brand_name+"<input name='CrmSaleQuotedpriceChild[brand][]' type='hidden' value='"+rows[x].brand+"'></td>\
//                    <td><input class='num' name='CrmSaleQuotedpriceChild[order_num][]' type='' value=''></td>\
//                    <td>"+rows[x].unit+"</td>\
//                    <td><input name='' type='text'></td>\
//                    <td><input class='discount_rate' type='text'></td>\
//                    <td><input class='ws_local_unit_price' name='CrmSaleQuotedpriceChild[ws_local_unit_price][]' type='text'></td>\
//                    <td><input class='ws_local_total_price' name='CrmSaleQuotedpriceChild[ws_local_total_price][]' type='text'></td>\
//                    <td><input class='tax_rate' type='text'></td>\
//                    <td><input class='local_unit_price' name='CrmSaleQuotedpriceChild[local_unit_price][]' type='text'></td>\
//                    <td><input class='local_total_price' name='CrmSaleQuotedpriceChild[local_total_price][]' type='text'></td>\
//                    <td><span class='discount_local_total_price'></span></td>\
//                    <td></td>\
//                    <td></td>\
//                    <td><input name='CrmSaleQuotedpriceChild[remark][]' type=''></td>\
//                    </tr>";
//            }
//
//
//
//            $("#product-info").append(str);
            $(".num,.tax_rate,.discount_rate,.ws_local_unit_price").change(function(){
                var num=$(this).parents("tr").find(".num").val()==""?1:parseInt($(this).parents("tr").find(".num").val());
                var tax_rate=$(this).parents("tr").find(".tax_rate").val()==""?0:parseFloat($(this).parents("tr").find(".tax_rate").val());
                var discount_rate=$(this).parents("tr").find(".discount_rate").val()==""?100:parseFloat($(this).parents("tr").find(".discount_rate").val());
                var ws_local_unit_price=parseFloat($(this).parents("tr").find(".ws_local_unit_price").val());

                var local_unit_price=$(this).parents("tr").find(".local_unit_price");
                var ws_local_total_price=$(this).parents("tr").find(".ws_local_total_price");
                var local_total_price=$(this).parents("tr").find(".local_total_price");
                var discount_local_total_price=$(this).parents("tr").find(".discount_local_total_price");

                if(ws_local_unit_price){
                    local_unit_price.val(ws_local_unit_price*(100+tax_rate)/100);
                    ws_local_total_price.val(ws_local_unit_price*num);
                    local_total_price.val(ws_local_unit_price*(100+tax_rate)/100*num);
                    discount_local_total_price.text(ws_local_unit_price*(100+tax_rate)/100*num*discount_rate/100);
                }

            });
            $("#product-info .easyui-datebox").datebox();
            $.fancybox.close();
        });


        //删除产品
        $("#product-remove").click(function(){
            var row=$("#product-info").datagrid("getSelected");
            if(!row){
                layer.alert("请选择商品",{icon:2});
            }else{
                var index=$("#product-info").datagrid("getRowIndex",row);
                $("#product-info").datagrid("deleteRow",index)
            }
        });
        //产品价格信息
        $("#price-info").click(function(){
            var row=$("#product-info").datagrid("getSelected");
            if(!row){
                layer.alert("请选择一条商品",{icon:2});
            }
                $.fancybox({
                    type:"ajax",
                    href:"<?=\yii\helpers\Url::to(['price-info'])?>?id="+row.pdt_no,
                    autoDimensions:true,
                    padding:0
                });
        });

        $("#product-select-box .cancel").click(function(){
            $.fancybox.close();
        });

    }
</script>


