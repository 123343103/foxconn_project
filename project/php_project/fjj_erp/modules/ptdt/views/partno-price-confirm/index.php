<?php
/**
 * User: F1676624
 * Date: 2016/10/24
 */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\widgets\upload\UploadAsset;
use app\classes\Menu;
UploadAsset::register($this);

$this->params['homeLike'] = ['label' => '商品开发与管理', 'url' =>['/']];
$this->params['breadcrumbs'][] = ['label' => '商品定价','url'=>['index']];
$this->params['breadcrumbs'][] = ['label' => '商品定价表'];
?>
<div class="content">
    <?php echo $this->render('_search',[
            'productTypeIdToValue'=>$productTypeIdToValue,
            'statusType'=>$statusType
    ]); ?>
    <div class="table-content">
        <div class="table-head">
            <p class="float-right">
                <?= Menu::isAction('/ptdt/partno-price-confirm/batch-price')?'<a id="batch_price"><span>批量定价</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-confirm/partno-relation')?'<a id="relation"><span>关联料号</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-confirm/create')?'<a id="create"><span>新增定价</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-confirm/edit')?'<a id="price"><span>去定价</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-confirm/view')?'<a id="view"><span>查看</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-confirm/edit')?'<a id="edit"><span>修改</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-confirm/index')?'<a id="export"><span>导出</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/partno-price-confirm/delete')?'<a id="delete"><span>删除</a>':'' ?>
            </p>
        </div>
        <div class="space-30"></div>
        <div id="data"></div>
        <div id="load-content" class="overflow-auto"></div>


        <div id="batch-price-dialog" style="width:1000px;display: none;">
            <?php $form=ActiveForm::begin([
                "method"=>"post",
                "id"=>"batch-price-form",
                "action"=>["batch-price"]
            ]); ?>
            <h2 class="head-three">
                <span>批量发起定价</span>
            </h2>
            <p class="mb-20">
                <label for="">定价方式</label>
                <input type="radio" style="vertical-align:bottom;"> <span>标准定价</span>
                <button type="button" class="button-blue ml-50">导入</button>
                <button type="button" class="button-blue">导出</button>
            </p>
            <div style="width:100%;overflow: scroll;">
                <table class="table" style="width:8000px;">
                    <thead>
                        <th class="width-150">操作</th>
                        <th class="width-150">序号</th>
                        <th class="width-150">料号</th>
                        <th class="width-150">品名</th>
                        <th class="width-150">PAS单号</th>
                        <th class="width-150">价格区间</th>
                        <th class="width-150">采购价(未税)</th>
                        <th class="width-150">采购价有效期</th>
                        <th class="width-150">交货条件</th>
                        <th class="width-150">交货地点</th>
                        <th class="width-150">付款条件</th>
                        <th class="width-150">供应商代码</th>
                        <th class="width-150">供应商简称</th>
                        <th class="width-150">最小定购量</th>
                        <th class="width-150">交易单位</th>
                        <th class="width-150">交易币别</th>
                        <th class="width-150">L/T(天)</th>
                        <th class="width-150">状态</th>
                        <th class="width-150">一阶</th>
                        <th class="width-150">二阶</th>
                        <th class="width-150">三阶</th>
                        <th class="width-150">四阶</th>
                        <th class="width-150">五阶</th>
                        <th class="width-150">六阶</th>
                        <th class="width-150">规格型号</th>
                        <th class="width-150">品牌</th>
                        <th class="width-150">底价(未税)</th>
                        <th class="width-150">商品定价下限</th>
                        <th class="width-150">商品定价上限</th>
                        <th class="width-150">市场均价</th>
                        <th class="width-150">价格有效期</th>
                        <th class="width-150">毛利润</th>
                        <th class="width-150">毛利润率(%)</th>
                        <th class="width-150">税前利润</th>
                        <th class="width-150">税前利润率(%)</th>
                        <th class="width-150">税后利润</th>
                        <th class="width-150">税后利润率(%)</th>
                        <th class="width-150">商品经理人</th>
                        <th class="width-150">定价类型</th>
                        <th class="width-150">定价发起来源</th>
                        <th class="width-150">利润上限</th>
                        <th class="width-150">利润下限</th>
                        <th class="width-150">商品定位</th>
                        <th class="width-150">是否克制化</th>
                        <th class="width-150">法律风险等级</th>
                        <th class="width-150">是否拳头产品</th>
                        <th class="width-150">主要竞争对手</th>
                        <th class="width-150">发部到销售系统</th>
                        <th class="width-150">是否取得代理</th>
                        <th class="width-150">原商品定价下限(未税)</th>
                        <th class="width-150">价格幅度</th>
                        <th class="width-150">原定价日期</th>
                        <th class="width-150">是否采用关联料号定价</th>
                        <th class="width-150">关联料号</th>
                        <th class="width-150">补充说明</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <p class="mt-20">
                <label for="">文件上传</label>
                <input class="upBtn" type="button" data-target-name="filename"  data-server="<?=Url::to(['/base/upload'])?>" value="选择文件">
            </p>
            <p class="mt-20 text-center">
                <button type="submit" class="button-blue">确定</button>
                <button type="button" class="button-white" onclick="$.fancybox.close()">返回</button>
            </p>
            <?php $form->end();?>
        </div>





        <div id="partNoBox" style="display: none;height:320px;overflow: hidden;">
            <input type="text" id="keywords">
            <button class="button-blue" id="partNoSearch">搜索</button>
            <button class="button-blue float-right" id="partNoEnsure">确定</button>
            <div class="space-10"></div>
            <table id="part-table" style="width:600px;">
            </table>
        </div>

    </div>
    <div class="space-30"></div>
</div>
<script>
    $(function () {
        //批量定价ajax表单
        $("#batch-price-form").ajaxForm(function(res){
            $.fancybox.close();
            var res=JSON.parse(res);
            if(res.status==1){
                parent.layer.alert("批量定价成功", {icon: 2, time: 5000});
            }else{
                parent.layer.alert("批量定价失败", {icon: 2, time: 5000});
            }
        });
        "use strict";
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "part_no",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            //设置复选框和行的选择状态不同步
            checkOnSelect: false,
            selectOnCheck: false,
            columns: [[
                {field: 'ck', checkbox: true, align: 'left'},
                <?=$columns;?>
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this),data.total,1);
            },
            onCheckAll: function (rowIndex, rowData) {  //checkbox 全选中事件
                //设置选中事件，清除之前单行选择
                $("#data").datagrid("unselectAll");
                $('#load-content').empty();
            },
            onCheck: function (rowIndex, rowData) {  //checkbox 选中事件
                //设置选中事件，清除之前单行选择
                $("#data").datagrid("unselectAll");
                $('#load-content').empty();
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var id = rowData['part_no'];
                //设置选中事件，清除之前多行选择
                $("#data").datagrid("uncheckAll");
            }
        });

        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录'
        });

        //定价详情
        $("#view").on("click", function () {
            var row = $("#data").datagrid("getSelected");

            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row['id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });

        //批量定价
        $("#batch_price").on("click", function () {
            var rows=$("#data").datagrid("getChecked");
            if(rows.length==0){
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
                return false;
            }
            for(var m=1;m<rows.length;m++){
                if(rows[m].type_3!=rows[m-1].type_3){
                    layer.alert("前三级分类必须相同",{icon: 2, time: 5000});
                    return false;
                }
            }
            var str="";
            for(var x=0;x<rows.length;x++){
                str+="<tr>\
                <input type='hidden' name='PartnoPrice[id][]' value='"+rows[x].id+"' />\
                <input type='hidden' name='PartnoPrice[part_no][]' value='"+rows[x].part_no+"' />\
                    <td><span style='cursor:pointer;' onclick='$(this).parent().parent().remove();'>&times;</span></td>\
                    <td>"+rows[x].id+"</td>\
                    <td>"+rows[x].part_no+"</td>\
                    <td>"+rows[x].pdt_name+"</td>\
                    <td>"+rows[x].pasid+"</td>\
                    <td>"+rows[x].num_area+"</td>\
                    <td><input name='PartnoPrice[buy_price][]'  type='text' value='"+rows[x].buy_price+"' /></td>\
                    <td><input name=''  type='date' value='"+rows[x].valid_date+"' /></td>\
                    <td><input name='PartnoPrice[trading_terms][]' type='text' value='"+rows[x].trading_terms+"' ></td>\
                    <td><input name='PartnoPrice[delivery_address][]' type='text' value='"+rows[x].delivery_address+"' ></td>\
                    <td><input name='PartnoPrice[payment_terms][]' type='text' value='"+rows[x].payment_terms+"' ></td>\
                    <td><input name='PartnoPrice[supplier_code][]' type='text' value='"+rows[x].supplier_code+"' ></td>\
                    <td><input name='PartnoPrice[supplier_name_shot][]' type='text' value='"+rows[x].supplier_name_shot+"' ></td>\
                    <td><input name='PartnoPrice[min_order][]' type='text' value='"+rows[x].min_order+"' ></td>\
                    <td>\
                        <select name='PartnoPrice[unit][]' class='width-120'>\
                            <option>T</option>\
                            <option>kg</option>\
                        </select>\
                    </td>\
                    <td>\
                        <select name='PartnoPrice[currency][]' class='width-120' name='' id=''>\
                            <option>RMB</option>\
                        </select>\
                    </td>\
                    <td>"+rows[x].limit_day+"</td>\
                    <td>"+rows[x].status+"</td>\
                    <td>"+rows[x].type_1+"</td>\
                    <td>"+rows[x].type_2+"</td>\
                    <td>"+rows[x].type_3+"</td>\
                    <td>"+rows[x].type_4+"</td>\
                    <td>"+rows[x].type_5+"</td>\
                    <td>"+rows[x].type_6+"</td>\
                    <td>"+rows[x].tp_spec+"</td>\
                    <td>"+rows[x].brand+"</td>\
                    <td>"+rows[x].ws_lower_price+"</td>\
                    <td><input name='PartnoPrice[ws_lower_price][]' type='text' value='"+rows[x].ws_lower_price+"' ></td>\
                    <td><input name='PartnoPrice[ws_upper_price][]' type='text' value='"+rows[x].ws_upper_price+"' ></td>\
                    <td><input name='PartnoPrice[market_price][]' type='text' value='"+rows[x].market_price+"' ></td>\
                    <td><input name='PartnoPrice[valid_date][]' type='date' value='"+rows[x].valid_date+"' ></td>\
                    <td>"+rows[x].gross_profit+"</td>\
                    <td>"+rows[x].gross_profit_margin+"</td>\
                    <td>"+rows[x].pre_tax_profit+"</td>\
                    <td>"+rows[x].pre_tax_profit_rate+"</td>\
                    <td>"+rows[x].after_tax_profit+"</td>\
                    <td>"+rows[x].after_tax_profit_margin+"</td>\
                    <td><input ></td>\
                    <td>\
                        <select name='PartnoPrice[price_type][]' class='width-120'>\
                            <option>新增</option>\
                        </select>\
                    </td>\
                    <td>\
                        <select name='PartnoPrice[price_from][]' class='width-120'>\
                            <option value=''>CRD/PRD</option>\
                        </select>\
                    </td>\
                    <td>"+rows[x].upper_limit_profit+"</td>\
                    <td>"+rows[x].lower_limit_profit+"</td>\
                    <td>\
                        <select name='PartnoPrice[pdt_level][]' class='width-120'>\
                            <option>高</option>\
                            <option>中</option>\
                            <option>低</option>\
                        </select>\
                    </td>\
                    <td>\
                        <select name='PartnoPrice[iskz][]' class='width-120'>\
                            <option>是</option>\
                            <option>否</option>\
                        </select>\
                    </td>\
                    <td>\
                        <select name='PartnoPrice[risk_level][]' class='width-120'>\
                            <option>高</option>\
                            <option>中</option>\
                            <option>低</option>\
                        </select>\
                    </td>\
                    <td>\
                    <select name='PartnoPrice[istitle][]' class='width-120'>\
                            <option>是</option>\
                            <option>否</option>\
                        </select>\
                    </td>\
                    <td><input ></td>\
                    <td>\
                        <select class='width-120'>\
                            <option value=''>高</option>\
                        </select>\
                    </td>\
                    <td>\
                        <select class='width-120' name='' id=''>\
                            <option value=''>是</option>\
                        </select>\
                    </td>\
                    <td>"+rows[x].after_tax_profit_margin+"</td>\
                    <td>"+rows[x].price_fd+"</td>\
                    <td>"+rows[x].after_tax_profit_margin+"</td>\
                    <td>\
                        <select class='width-120' name='' id=''>\
                            <option value=''>是</option>\
                            \<option value=''>否</option>\
                        </select>\
                    </td>\
                    <td><input name='PartnoPrice[isrelation][]' type='text' value='"+rows[x].isrelation+"' /></td>\
                    <td><input name='PartnoPrice[no_xs_cause][]' type='text' value='"+rows[x].no_xs_cause+"' /></td>\
                    </tr>";
            }
            $("#batch-price-dialog tbody").html(str);
            $("[type=date]").datebox();
            $.fancybox({
                href:"#batch-price-dialog"
            });
        });


        //料号关联
        $("#relation").on("click",function(){
            $.fancybox({
                type:"iframe",
                width:800,
                height:600,
                padding:0,
                autoSize: false,
                scrolling:false,
                href:"<?=Url::to(['partno-relation'])?>"
            });
        });



        //新增料号
        $("#create").on("click", function () {
                window.location.href = "<?=Url::to(['create'])?>";
        });

        //修改料号
        $("#edit").on("click", function () {
            var row = $("#data").datagrid("getSelected");

            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row['id'];
                window.location.href = "<?=Url::to(['edit'])?>?id=" + id;
            }
        });
        //去定价
        $("#price").on("click", function () {
            var row = $("#data").datagrid("getSelected");
            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row.id;
                window.location.href = "<?=Url::to(['edit'])?>?id=" + id;
            }
        });


        //导出数据
        $('#export').click(function() {
            var page = $("#data" ).datagrid("getPager" ).data("pagination" ).options.pageNumber;
            var rows = $("#data" ).datagrid("getPager" ).data("pagination" ).options.pageSize;
            var index = layer.confirm("确定导出定价信息?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },
                function () {
                    if(window.location.href="<?= Url::to(['index', 'export' => '1'])?>&page="+page+"&rows="+rows){
                        layer.closeAll();
                    }else{
                        layer.alert('导出定价信息发生错误',{icon:0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        });



        //删除定价
        $("#delete").on("click",function(){
            var rows = $("#data").datagrid("getChecked");
            var ids=new Array();
            for(var x=0;x<rows.length;x++){
                ids.push(rows[x].id);
            }
            if(ids.length<1){
                layer.alert("请选择一条记录",{icon: 2});
            }else{
                layer.confirm("确定要删除选中的记录吗？",{
                    btn: ['确定', '取消'],
                    icon: 2
                },function(){
                    $.ajax({
                        type:"get",
                        dataType:"json",
                        data:{id:ids.join()},
                        url: "<?=Url::to(['delete']) ?>",
                        success:function(res){
                            if(res.flag==1){
                                layer.closeAll();
                                $("#data").datagrid("reload",{
                                    url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                                    onLoadSuccess:function(){
                                        $("#data").datagrid("clearChecked");
                                    }
                                });
                            }else{
                                layer.alert(res.msg, {icon: 2})
                            }
                        },
                        error:function(res){
                            layer.alert(res.msg, {icon: 2});
                        }
                    });
                },function(){
                    layer.closeAll();
                });
            }

        });


    });
</script>




