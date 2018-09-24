<?php
/**
 * User: F3859386
 * Date: 2016/11/24
 * Time: 下午 03:39
 */
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;


$this->title = '供应商申请';
$this->params['homeLike'] = ['label'=>'供应商管理','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'供应商申请列表'];

?>
<div class="content">
    <?php echo $this->render('_search', [
        'model' =>$model['downList']
    ]); ?>
    

    <div class="space-30"></div>
    <div class="table-content">
        <?php  echo $this->render('_action'); ?>
        <div class="space-10"></div>
        <div id="data">
        </div>
        <div id="load-content" class="overflow-auto"></div>
    </div>
</div>
<script>

    $(function () {
        "use strict";
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers :true,
            method: "get",
            idField: "supplier_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                {field: "supplier_code", title: "供应商编号", width: 100},
                {field: "supplier_sname", title: "供应商名称", width: 100},
                {field: "supplierType", title: "供应商分类", width: 100},
                {field: "supplierSource", title: "厂商来源", width: 100},
                {field: "supplier_issupplier", title: "集团供应商", width: 100, formatter: function (value, row) {
                    if (value==1) {
                        return '是';
                    } else {
                        return '否';
                    }
                }
                },
                {field: "transactType", title: "商品类别", width: 100},
                {field: "createBy", title: "创建人", width: 100, formatter: function (value, row) {
                    if (row.createBy) {
                        return row.createBy.name
                    } else {
                        return null;
                    }
                }
                },
                {field: "create_at", title: "新增时间", width: 100},
                {field: "supplierStatus", title: "状态", width: 100}
            ]],
            onSelect: function (rowIndex, rowData) {    //选择触发事件
                var id  = rowData['supplier_id'];

                $('#load-content').load("<?=Url::to(['/ptdt/supplier/load-info']) ?>?id=" + id, function () {
                    setMenuHeight();
                });

            },
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });
        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录',
        });
        $("#data").datagrid({
            onClickRow: function (index, row) {
              var  ids = row['supplier_id'];
                createButton($("#create"),"<?=Url::to(['create'])?>");
                deleteById($("#delete"),"<?=Url::to(['delete'])?>",ids);
                updateById($("#update"),"<?=Url::to(['update'])?>",ids);
                viewById($("#view"),"<?=Url::to(['view'])?>",ids);
            }
        });


    })

</script>

