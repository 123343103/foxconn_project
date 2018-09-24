<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/7
 * Time: 上午 09:16
 */
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
use yii\widgets\ActiveForm;
JqueryUIAsset::register($this);
?>
<?php $form=ActiveForm::begin([
    "method"=>"post",
    "id"=>"relation-price-form"
]);?>
<div id="relation-partno" style="width:800px;">
    <h2 class="head-first">
        <p>关联料号定价</p>
    </h2>
    <div class="mb-20 text-center">
        <label for="">输入母料号</label>
        <input name="parent-partno" id="parent-partno" class="width-150 easyui-validatebox validatebox-text validatebox-invalid" data-options="required:true" type="text">
        <button type="button" class="button-blue" id="relation-info">关联</button>

        <label class="ml-30" for="">添加子料号</label>
        <input name="sub-partno" id="partno-text" class="width-150 easyui-validatebox validatebox-text validatebox-invalid" data-options="required:true" type="text">
        <button type="button" class="button-blue" id="relation-add">添加</button>
    </div>
    <div class="relation-tag-list" class="mb-20" style="font-size: 0px;">
    </div>
    <br />
    <div class="mb-20">
            <table id="relation-table"></table>
    </div>
    <div class="mb-20 text-center">
        <button class="button-blue ensure">确定</button>
        <button class="button-white ml-30 cancel">取消</button>
    </div>
</div>
<?php $form->end();?>


<div id="select-partno" style="width:800px;">
    <h2 class="head-first">
        <p>选择料号</p>
    </h2>
    <div id="partno-data"></div>
    <br />
    <div class="mb-20 text-center">
        <button class="button-blue ensure">确定</button>
        <button class="button-white ml-30 cancel" onclick="$.fancybox.close()">取消</button>
    </div>
</div>
<script>
    $(function(){
        $("#relation-price-form").submit(function(){
            if($("#parent-partno").val()=="" || $("#sub-partno").val()==""){
                return false;
            }
        });

        //关联定价ajax表单
        $("#relation-price-form").ajaxForm(function(res){
            parent.$.fancybox.close();
            res=JSON.parse(res);
            if(res.status==1){
                parent.layer.alert("关联定价成功", {icon: 2, time: 5000});
            }else{
                parent.layer.alert("关联定价失败", {icon: 2, time: 5000});
            }
        });

        //删除一个关联
        $(".partno-tag").on("click",".icon-remove",function(){
            $(this).parent().remove();
        });

        //添加一个关联
        $("#relation-add").on("click",function(){
            if($(".partno-tag").size()>=10){
                parent.layer.alert("子料号最多10个",{icon: 2, time: 5000});
            }else{
                $("#relation-partno").slideUp();
                $("#select-partno").slideDown(500,function(){
                    $("#partno-data").datagrid({
                        url:"<?=Url::to(['partno-price-apply/partno-select'])?>",
                        method:"get",
                        idField: "id",
                        pagination:true,
                        fitColumns:true,
                        pageSize:5,
                        pageList:[5,10,15],
                        singleSelect: true,
                        columns:[[
                            {
                                field:"pdt_no",
                                title:"料号",
                                width:300
                            },
                            {
                                field:"pdt_name",
                                title:"品名",
                                width:300
                            }
                        ]]
                    });
                });
            }
        });

        $("#select-partno .ensure").click(function(){
            $("#relation-partno").slideDown(500,function(){
                var row=$("#partno-data").datagrid("getSelected")
                $(".relation-tag-list").prepend('<span class="partno-tag">'+row.pdt_no+'<i class="icon-remove"></i></span>');
                $("#partno-text").val(partnoStr());
                $(".partno-tag").on("click",".icon-remove",function(){
                    $(this).parent().remove();
                    $("#partno-text").val(partnoStr());
                });
            });
            $("#select-partno").slideUp(500);
        });

        $("#select-partno .cancel").click(function(){
            $("#relation-partno").slideDown(500);
            $("#select-partno").slideUp(500);
        });

        //关联料号信息
        $("#relation-info").click(function(){
            if($("#parent-partno").val()!="" && $(".partno-tag").size()>0){
                var str=partnoStr();
                $("#relation-table").datagrid({
                    url: "<?=Url::to(['index']);?>",
                    rownumbers: true,
                    method: "get",
                    idField: "part_no",
                    loadMsg: "加载数据请稍候。。。",
                    queryParams:{
                        part_no:str
                    },
                    pagination: true,
                    singleSelect: true,
                    //设置复选框和行的选择状态不同步
                    checkOnSelect: false,
                    selectOnCheck: false,
                    fitColumns: true,
                    columns: [[
                        {field: 'ck', checkbox: true, align: 'left'}
                        , {field: 'part_no', title: '料号'}
                        , {field: 'pdt_name', title: '品名',width:100}
                        , {field: 'pasid', title: 'PAS单号',width:100}
                        , {field: 'num_area', title: '数量区间',width:100}
                        , {field: 'type_1', title: '一阶'}
                        , {field: 'type_2', title: '二阶'}
                        , {field: 'type_3', title: '三阶'}
                        , {field: 'type_4', title: '四阶'}
                        , {field: 'type_5', title: '五阶'}
                        , {field: 'type_6', title: '六阶'}
                        , {field: 'tp_spec', title: '规格型号'}
                        , {field: 'brand', title: '品牌'}

                        , {field: 'min_price', title: '底价（未税）'}
                        , {field: 'ws_lower_price', title: '商品定价下限'}
                        , {field: 'ws_upper_price', title: '商品定价上限'}
                        , {field: 'market_price', title: '市场均价'}
                        , {field: 'valid_date', title: '价格有效期'}
                        , {field: 'gross_profit', title: '毛利润'}
                        , {field: 'gross_profit_margin', title: '毛利润率（%）'}
                        , {field: 'pre_tax_profit', title: '税前利率'}
                        , {field: 'pre_tax_profit_rate', title: '税前利润率（%）'}
                        , {field: 'after_tax_profit', title: '税后利率'}
                        , {field: 'after_tax_profit_margin', title: '税后利润率（%）'}
                        , {field: 'pdt_manager', title: '商品经理人'}
                        , {field: 'price_type', title: '定价类型'}
                        , {field: 'price_from', title: '定价发起来源'}
                        , {field: 'upper_limit_profit', title: '利润上限'}
                        , {field: 'lower_limit_profit', title: '利润下限'}



                        , {field: 'pdt_level', title: '商品定位'}
                        , {field: 'iskz', title: '是否客制化'}
                        , {field: 'risk_level', title: '法务风险等级'}
                        , {field: 'istitle', title: '是否拳头商品'}
                        , {field: 'archrival', title: '主要竞争对手'}
                        , {field: 'isto_xs', title: '发布到销售系统'}
                        , {field: 'isproxy', title: '是否取得代理'}
                        , {field: 'price_fd', title: '价格幅度'}
                        , {field: 'pre_ws_lower_price', title: '原商品定价下限(未税)'}
                        , {field: 'pre_verifydate', title: '原定价日期'}
                        , {
                            field: 'isrelation',
                            title: '是否采用关联料号定价',
                            formatter:function(value,row,index){
                                return "Y";
                            }
                        }
                        , {
                            field: ' ',
                            title: '关联料号',
                            formatter:function(value,row,index){
                                return $("#parent-partno").val();
                            }
                        }
                        ,{
                            field: 'status',
                            title: '状态',
                            formatter:function(value,row,index){
                                var statusArr=["未定价","发起定价","商品开发维护","审核中","已定价","被驳回","已逾期","重新定价"];
                                return statusArr[row.status];
                            }
                        }
                        , {field: 'no_xs_cause', title: '补充说明'}
                    ]]
                });
            }
        });
        $("#relation-partno .ensure").click(function(){
//            parent.$.fancybox.close();
        });
        $("#relation-partno .cancel").click(function(){
            parent.$.fancybox.close();
        });


        //多个料号拼接为字符串
        function partnoStr(){
            var num=$(".partno-tag").size();
            var tmpArr=new Array();
            for(var x=0;x<num;x++){
                tmpArr.push($(".partno-tag").eq(x).text());
            }
            str=tmpArr.join(",");
            return str;
        }
    });
</script>


<style type="text/css">
    #select-partno{
        display: none;
    }
    .partno-tag{
        width:150px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        display: inline-block;
        margin:5px 0px;
        font-size: 10px;
        margin-left:40px;
        background: #ddd;
        cursor: pointer;
        position: relative;
    }


    .partno-tag .icon-remove{
        position: absolute;
        right:0px;
        top:10px;
    }
    .partno-tag::after{
        position: absolute;
        content:"";
        display:inline-block;
        width:0px;
        height: 0px;
        border-left:15px solid #ddd;
        border-right:15px solid transparent;
        border-top:15px solid transparent;
        border-bottom:15px solid transparent;
        top:0px;
        right:-30px;
    }
</style>