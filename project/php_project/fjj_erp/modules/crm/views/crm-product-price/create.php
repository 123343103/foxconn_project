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
    <div class="mt-20">
        <h2 class="head-second">报价基本信息</h2>
    </div>

    <div class="mb-20">
        <label class="width-100" for="">客户名称</label>
        <input id="custom_name" type="text" class="width-300 easyui-validatebox validatebox-text validatebox-invalid" data-options="required:'true'">
        <input id="custom_id" name="CrmSaleQuotedprice[cust_id]" type="hidden" class="width-300">
        <a class="width-20" href="javascript:void(0)" id="custom-selector">选择</a>
        <label class="width-100" for="">报价日期</label>
        <input name="CrmSaleQuotedprice[saph_date]" type="text" class="width-100 select-date" value="<?=date('Y-m-d')?>">
        <label class="width-100" for="">报价人</label>
        <input name="CrmSaleQuotedprice[applicant]" type="text" class="width-100" value="<?=\Yii::$app->user->identity->staff->staff_name?>">
        <input name="CrmSaleQuotedprice[creator]" type="hidden" class="width-100" value="<?=\Yii::$app->user->identity->staff->staff_id?>">
    </div>

    <div class="mb-20">
        <label class="width-100" for="">联系人</label>
        <input id="cust_contact" type="text" class="width-300">&nbsp;
        <input type="hidden" id="cust_sale" name="CrmSaleQuotedprice[branch_district]">
        <input type="hidden" id="cust_salearea" name="CrmSaleQuotedprice[branch_sale_area]">
        <label class="width-100 ml-20" for="">公司名称</label>
        <input id="corp_name" type="text" class="width-300 easyui-validatebox validatebox-text validatebox-invalid" data-options="required:'true'">
        <input id="corp_id" name="CrmSaleQuotedprice[corp_id]" type="hidden" class="width-300">
        <a class="width-20" href="javascript:void(0)" id="corp-selector">选择</a>
    </div>


    <div class="mb-20">
        <label class="width-100" for="">TEL</label>
        <input id="cust_tel" type="text" class="width-100">
        <label class="width-100" for="">FAX</label>
        <input id="cust_fax" type="text" class="width-100">
        <label class="width-100 ml-20" for="">TEL</label>
        <input type="text" class="width-100">
        <label class="width-100" for="">FAX</label>
        <input type="text" class="width-100">
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
        <select class="width-100" name="CrmSaleQuotedprice[payment_terms]" id="">
            <?php foreach($dropdown_list['pay_cond'] as $item){ ?>
            <option value="<?=$item->pat_id;?>"><?=$item->pat_sname;?></option>
            <?php } ?>
        </select>
        <label class="width-100" for="">交易条件</label>
        <select class="width-100" name="CrmSaleQuotedprice[trading_terms]" id="">
            <?php foreach($dropdown_list['dev_cond'] as $item){ ?>
            <option value="<?=$item->dec_id?>"><?=$item->dec_sname?></option>
            <?php } ?>
        </select>
    </div>


    <div class="mb-20">
        <label class="width-100" for="">交易模式</label>
        <input type="radio"  style="vertical-align:top;" /> 外购外销
        <input class="ml-20" type="radio" style="vertical-align: top;" /> 内购外销
    </div>

    <div class="mb-20">
        <label class="width-100" for="" style="vertical-align: top;">备注</label>
        <textarea name="CrmSaleQuotedprice[remark]" class="width-800"></textarea>
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

    <div style="overflow-x: scroll;">
        <table id="product-info" class="table" style="width:4000px;">
            <thead>
            <th class="width-200"></th>
            <th class="width-200">料号</th>
            <th class="width-200">商品名称</th>
            <th class="width-200">商品类别</th>
            <th class="width-200">商品库存</th>
            <th class="width-200">商品规格型号</th>
            <th class="width-200">品牌</th>
            <th class="width-200">下单数量</th>
            <th class="width-200">单位</th>
            <th class="width-200">还需采购</th>
            <th class="width-200">折扣率</th>
            <th class="width-200">单价(未税)</th>
            <th class="width-200">价税合计(未税)</th>
            <th class="width-200">税率</th>
            <th class="width-200">单价(已税)</th>
            <th class="width-200">价税合计(已税)</th>
            <th class="width-200">折扣后金额</th>
<!--            <th class="width-200">是否赠品</th>-->
<!--            <th class="width-200">需求交期</th>-->
            <th class="width-200">备注</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>






    <div class="mt-20 text-center">
        <button type="submit" class="button-blue">确定</button>
        <button class="button-white">取消</button>
    </div>
</div>
<?php $form=ActiveForm::end();?>

<div id="custom-select-box" style="display: none;width:630px;height: 600px;">
    <h2 class="head-three">选择客户</h2>
    <div class="mt-20 mb-20">
        <input type="text">
        <button class="button-blue">搜索</button>
        <a href="javascript:void(0)" class="float-right">新增客户</a>
    </div>
    <table id="custom-list"></table>
    <div class="mt-20 text-right">
        <button class="button button-blue ensure">确定</button>
        <button type="reset" class="button button-white cancel">取消</button>
    </div>
</div>





<div id="corp-select-box" style="display: none;width:600px;height: 440px;">
    <h2 class="head-three">选择公司</h2>
    <div class="mt-20 mb-20">
        <input type="text">
        <button class="button-blue" id="corp-search">搜索</button>
    </div>
    <table id="corp-list"></table>
    <div class="mt-20 text-right">
        <button class="button button-blue ensure">确定</button>
        <button class="button button-white cancel">取消</button>
    </div>
</div>



<div id="product-select-box" style="display: none;width:630px;height: 600px;">
    <h2 class="head-three">选择商品</h2>
    <div class="mt-20 mb-20">
        <input type="text">
        <button class="button-blue" id="product-search">搜索</button>
        <a href="javascript:void(0)" class="float-right">新增商品</a>
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

        //选择客户
        $("#custom-selector").click(function(){
            $.fancybox({
                href:"#custom-select-box",
                scrolling:false
            });
            $("#custom-list").datagrid({
                url:"<?=\yii\helpers\Url::to(['customer-list'])?>",
                pagination:true,
                singleSelect:true,
                columns:[[
                    {field:"ck",checkbox:true},
                    {field:"cust_code",title:"客户编号",width:200},
                    {field:"cust_sname",title:"客户简称",width:200},
                    {field:"cust_shortname",title:"客户名称",width:200}
                ]],
                onLoadSuccess: function () {
                    setMenuHeight();
                }
            });
        });


        //选择公司
        $("#corp-selector").click(function(){
            $.fancybox({
                href:"#corp-select-box"
            });
            $("#corp-list").datagrid({
                url:"<?=\yii\helpers\Url::to(['corp-list'])?>",
                pagination:true,
                pageSize:5,
                pageList:[5],
                singleSelect:true,
                columns:[[
                    {field:"company_id",title:"公司ID",width:300},
                    {field:"company_name",title:"公司名称",width:300}
                ]]
            });
        });

        $("#corp-search").click(function(){
            $("#corp-list").datagrid("reload",{
                company_name:$("#corp-search").prev("input").val()
            });
        });


        $("#corp-select-box .ensure").click(function(){
            var row=$("#corp-list").datagrid("getSelected");
            $("#corp_name").val(row.company_name);
            $("#corp_id").val(row.company_id);
            $.fancybox.close();
        });

        $("#corp-select-box .cancel").click(function(){
            $.fancybox.close();
        });



        $("#custom-select-box .ensure").click(function(){
            var row=$("#custom-list").datagrid("getSelected");
            $("#custom_name").val(row.cust_sname);
            $("#custom_id").val(row.cust_id);
            $("#cust_tel").val(row.cust_tel2);
            $("#cust_fax").val(row.cust_fax);
            $("#cust_sale").val(row.cust_area);
            $("#cust_readress").val(row.cust_readress);
            $("#cust_salearea").val(row.cust_salearea);
            $.fancybox.close();
        });
        $("#custom-select-box .cancel").click(function(){
            $.fancybox.close();
        });

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
                    {field:"part_no",title:"料号",width:200},
                    {field:"pdt_name",title:"商品名称",width:200},
                    {field:"category_id",title:"商品类别",width:200}
                ]],
                onLoadSuccess: function () {
                    setMenuHeight();
                }
            });
        });



        $("#product-search").click(function(){
            $("#product-list").datagrid("reload",{
                pdt_name:$("#product-search").prev("input").val()
            });
        });


        $("#product-select-box .ensure").click(function(){
            var rows=$("#product-list").datagrid("getChecked");
            var str="";
            for(var x=0;x<rows.length;x++){
                str+="<tr>\
                    <td><input class='sel-row' type='checkbox' value='"+rows[x].part_no+"'></td>\
                    <td>"+rows[x].part_no+"<input name='CrmSaleQuotedpriceChild[part_no][]' type='hidden' value='"+rows[x].part_no+"'></td>\
                    <td>"+rows[x].pdt_name+"</td>\
                    <td>"+rows[x].category_id+"</td>\
                    <td></td>\
                    <td>"+rows[x].tp_spec+"</td>\
                    <td>"+rows[x].brand+"<input name='CrmSaleQuotedpriceChild[brand][]' type='hidden' value='"+rows[x].brand+"'></td>\
                    <td><input class='num' name='CrmSaleQuotedpriceChild[order_num][]' type='' value=''></td>\
                    <td>"+rows[x].unit+"</td>\
                    <td><input name='' type='text'></td>\
                    <td><input class='discount_rate' type='text'></td>\
                    <td><input class='ws_local_unit_price' name='CrmSaleQuotedpriceChild[ws_local_unit_price][]' type='text'></td>\
                    <td><input class='ws_local_total_price' name='CrmSaleQuotedpriceChild[ws_local_total_price][]' type='text'></td>\
                    <td><input class='tax_rate' type='text'></td>\
                    <td><input class='local_unit_price' name='CrmSaleQuotedpriceChild[local_unit_price][]' type='text'></td>\
                    <td><input class='local_total_price' name='CrmSaleQuotedpriceChild[local_total_price][]' type='text'></td>\
                    <td><span class='discount_local_total_price'></span></td>\
                    <td><input name='CrmSaleQuotedpriceChild[remark][]' type=''></td>\
                    </tr>";
            }



            $("#product-info").append(str);
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



        $("#product-remove").click(function(){
            if($(".sel-row:checked").size()<1){
                layer.alert("请选择商品",{icon:2});
            }else{
                $(".sel-row:checked").parents("tr").remove();
            }
        });

        $("#price-info").click(function(){
            if($(".sel-row:checked").size()==1){
                $.fancybox({
                    type:"ajax",
                    href:"<?=\yii\helpers\Url::to(['price-info'])?>?id="+$(".sel-row:checked").val(),
                    autoDimensions:true,
                    padding:0
                });
            }else{
                layer.alert("请选择一条商品",{icon:2});
            }
        });

        $("#product-select-box .cancel").click(function(){
            $.fancybox.close();
        });

    }
</script>


