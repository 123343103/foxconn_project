<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use \app\classes\Menu;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '收货方式';
$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '平台交易相关设置', 'url' => \yii\helpers\Url::to(['/system/transaction/index'])];
$this->params['breadcrumbs'][] = ['label' => '收货方式列表'];
//$this->params['breadcrumbs'][] = $this->title;

$search = Yii::$app->request->get();
if (!isset($search['ReceiptSearch'])) {
    $search['ReceiptSearch'] = null;
}
?>
<style>
    .width-60{
        width: 60px;
    }
    .width-150{
        width: 150px;
    }
    .ml-30{
        margin-left: 30px;
    }
    .ml-50{
        margin-left: 50px;
    }
</style>
<div class="content">
    <?php $form = ActiveForm::begin(['method' => "get","action"=>"index"]); ?>
    <div class="inline-block ">
        <label class="width-60" >代码<label>：</label></label>
        <input type="text"  class="width-150" name="ReceiptSearch[rec_code]" value="<?= $search['ReceiptSearch']['rec_code']?>">
    </div>
    <div class="inline-block ml-30">
        <label class="width-60" >收货方式<label>：</label></label>
        <input type="text"  class="width-150" name="ReceiptSearch[rec_sname]" value="<?= $search['ReceiptSearch']['rec_sname']?>">
    </div>
    <div class="inline-block">
        <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue ml-50', 'type' => 'submit']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="table-head" style="margin-top: 20px">
        <p class="head">
            收货方式列表
        </p>
        <div class="float-right">
            <a href="<?= Url::to(['/system/receipt/create']) ?>">
                <div class='table-nav'>
                    <p class='add-item-bgc float-left'></p>
                    <p class='nav-font'>新增</p>
                </div>
            </a>
            <span style='float: left;'>&nbsp;|&nbsp;</span>
            <?= Menu::isAction('/system/receipt/update') ?
                "<a id='edit' >
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
             </a>"
                : '' ?>
            <span  style='float: left;'>&nbsp;|&nbsp;</span>
            <?= Menu::isAction('/system/receipt/delete') ?
                "<a id='delete' >
                    <div class='table-nav'>
                        <p class='delete-item-bgc float-left'></p>
                        <p class='nav-font'>刪除</p>
                    </div>
             </a>"
                : '' ?>
            <span style='float: left;'>&nbsp;|&nbsp;</span>
            <a href="<?= Url::to(['/system/transaction/index']) ?>">
                <div class='table-nav'>
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>返回</p>
                </div>
            </a>
        </div>
    </div>
    <div class="space-10"></div>

    <div id="data">
    </div>
</div>
<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers :true,
            method: "get",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            idField: "rec_id",
            columns: [[

                {field: "rec_code", title: "代码",width:"240"},

                {field: "rec_sname", title: "收货方式",width:"240"},
                {field: "staffName", title: "创建人", width: 240, formatter: function (value, row, index) {
                    if (row.staffName) {
                        return row.staffName.staff_name;
                    } else {
                        return null;
                    }
                }
                },
                {field: "create_at", title: "创建时间",width:"240"},
                {field: "remarks", title: "备注",width:"240"}

            ]],
            onSelect: function (rowIndex, rowData) {    //选择触发事件
                var id = rowData['rec_id'];
            },
            onLoadSuccess : function(){
                setMenuHeight();
                datagridTip('#data');
            }

        });

        //修改操作
        $("#edit").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请选取一条信息",{icon:2,time:5000});
            } else {
                var id =$("#data").datagrid("getSelected")['rec_id'];
                window.location.href = "<?=Url::to(['update'])?>?id=" + id;
            }
        });
        //查看操作
        $("#view").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请选取一条信息",{icon:2,time:5000});
            } else {
                var id =$("#data").datagrid("getSelected")['rec_id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });
        /*删除操作*/
        $("#delete").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条信息",{icon:2,time:5000});
            } else {
                var id = $("#data").datagrid("getSelected")['rec_id'];
                layer.confirm("确定要删除这条信息吗?",
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            data: {"id": id},
                            url: "<?=Url::to(['/system/receipt/delete']) ?>",
                            success: function (msg) {
                                if( msg.flag === 1){
                                    layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
                                }else{
                                    layer.alert(msg.msg,{icon:2})
                                }
                            },
                            error :function(msg){
                                layer.alert(msg.msg,{icon:2})
                            }
                        })
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            }
        });
    });
</script>
