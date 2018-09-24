<?php
/**
 * User: F1676624
 * Date: 2016/10/24
 */

use yii\helpers\Url;
use app\classes\Menu;

$this->params['homeLike'] = ['label' => '商品开发与管理', 'url' => ['/']];
$this->params['breadcrumbs'][] = ['label' => '商品库','url'=>['index']];
$this->params['breadcrumbs'][] = ['label' => '商品列表'];
$this->title = '商品库列表';/*BUG修正 增加title*/
?>
<div class="content">
    <?php echo $this->render('_search', [
        'get'=>\Yii::$app->request->get(),
        'type1' => $type1
        , 'type2' => $type2
        , 'type3' => $type3
        , 'type4' => $type4
        , 'type5' => $type5
        , 'type6' => $type6
        ,'statusType'=>$statusType
    ]); ?>
    <div class="table-content">
        <div class="table-head">
            <p class="float-right">
                <?= Menu::isAction('/ptdt/product-library/index')?'<a ><span>生成套餐</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/product-library/edit')?'<a id="edit"><span>修改</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/product-library/view')?'<a id="view"><span>详情</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/product-library/index')?'<a><span>下架</span></a>':'' ?>
                <?= Menu::isAction('/ptdt/product-library/index')?'<a id="export"><span>导出</a>':'' ?>
                <?= Menu::isAction('/ptdt/product-library/index')?'<a id="return"><span>返回</span></a>':'' ?>
            </p>
        </div>
        <div class="space-30"></div>
        <div id="data"></div>
        <div id="load-content" class="overflow-auto"></div>
    </div>
    <div class="space-30"></div>
</div>
<script>
    $(function () {
        "use strict";
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "part_no",//多选时要注销否则只得到第一个
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            //设置复选框和行的选择状态不同步
            checkOnSelect: false,
            selectOnCheck: false,
//            frozenColumns: [[
//                {field: 'pdt_no', title: '产品编号'}
//                , {field: 'pdt_name', title: '商品名称'}
//                , {field: 'unit', title: '单位'}
//                , {
//                    field: 'status',
//                    title: '状态',
//                    formatter:function(value,row,index){
//                        var statusArr=["未定价","发起定价","商品开发维护","审核中","已定价","被驳回","已逾期","重新定价"];
//                        return statusArr[row.status];
//                    }
//                }
//            ]],
            columns: [[
                <?=$columns;?>
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this),data.total,0);
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
                var id = rowData['pdt_no'];
                //设置选中事件，清除之前多行选择
                $("#data").datagrid("uncheckAll");
                $('#load-content').load("<?=Url::to(['/ptdt/product-library/load-price']) ?>?id=" + id, function () {
                    setMenuHeight();
                });
            }
        });
        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录'
        });

        //产品详情
        $("#view").on("click", function () {
            var row = $("#data").datagrid("getSelected");

            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row['pdt_no'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });

        //产品修改
        $("#edit").on("click", function () {
            var row = $("#data").datagrid("getSelected");

            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row['pdt_no'];
                window.location.href = "<?=Url::to(['edit'])?>?id=" + id;
            }
        });

        $("#return").on("click",function(){
            window.location.href="<?=Url::home()?>";
        });

        //产品导出
        $('#export').click(function() {
            var page = $("#data" ).datagrid("getPager" ).data("pagination" ).options.pageNumber;
            var rows = $("#data" ).datagrid("getPager" ).data("pagination" ).options.pageSize;
            var index = layer.confirm("确定导出商品信息?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },
                function () {
                    if(window.location.href="<?= Url::to(['index', 'export' => '1'])?>&page="+page+"&rows="+rows){
                        layer.closeAll();
                    }else{
                        layer.alert('导出商品信息发生错误',{icon:0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        });
    });
</script>


