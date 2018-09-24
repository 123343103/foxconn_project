<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/22
 * Time: 上午 09:41
 */
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<div class="easyui-tabs">
    <div title="数据统计">
        <div id="count-data"></div>
    </div>
</div>
<script>
    $(function(){
        $("#count-data").datagrid({
            url: "<?=Url::current()?>",
            rownumbers: true,
            method: "get",
            idField: "commu_iid",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize:3,
            pageList:[3,6,9],
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            columns:[[
                <?=$columns?>
                {field:'action',title:'操作',formatter:function(value,row,index){
                    return "&nbsp;&nbsp;<a class='icon-edit' href='javascript:void(0)' onclick='editRow("+row.commu_ID+")'></a>&nbsp;&nbsp;<a class='icon-trash' href='javascript:void(0)' onclick='delRow("+row.commu_ID+")'></a>&nbsp;&nbsp;";
                }}
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#count-data"));
                showEmpty($(this),data.total,0);
            },
            onSelect:function(index,row){
                parent.childRow=row;
            }
        });
    });
</script>
