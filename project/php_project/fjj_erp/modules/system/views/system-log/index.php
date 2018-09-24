<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;

/**
 * F3858995
 * 2016/10/20
 */
$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '权限管理'];
$this->params['breadcrumbs'][] = ['label' => '操作日志'];
$this->title="操作日志";
?>
<div class="content">
    <?php  echo $this->render('_search',[ 'search' => $search]); ?>
    <div class="table-head">
    </div>
    <div class="space-10"></div>
    <div id="data"></div>

</div>
<script>
    $(function() {
        $("#data").datagrid({

            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "name",
            loadMsg: '加载中',
            pagination: true,
            singleSelect: true,
            fitToColumns:true,
            columns: [[
                {field: "user_name", title: "操作账号", width: 170},
                {field: "staff_code", title: "操作人", width: 170},
                {field: "description", title: "操作内容", width: 236},
                {field: "time", title: "时间", width: 170},
                {field: "user_ip", title: "IP地址", width: 170}
//                {field: "name", title: "操作", width: 150,formatter:function(val,row){
//                    var deleteRole="<span class='width-30'></span>";
//                    if(!row.isEnable){
//                        deleteRole = "<a onclick='deleteRole("+val+")' class='ml-20'><i class='icon-trash icon-l' title='删除'></i></a>";
//                    }
//                    return "<a class='ml-20' onclick='viewRole("+val+")' title='查看'><i class='icon-eye-open icon-l'></i></a><a class='ml-20' onclick='editRole("+val+")' title='编辑'><i class='icon-edit icon-l'></i></a>"+deleteRole
//                }},

            ]],
            onLoadSuccess: function (data) {
                $('.datagrid-header-row td').eq(0).css('width','38px');
                $('.datagrid-cell-rownumber').each(function () {
                    $(this).eq(0).css('width','38px');
//                    $(this).eq(0).css('text-align','center');
                });
//                $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color','white');
                datagridTip('#data');
                showEmpty($(this),data.total,0);
            }
        });

    })
</script>
