<?php
/**
 * F3858995
 *  2016/10/11
 */
use app\assets\MultiSelectAsset;
use yii\helpers\Url;
use yii\helpers\Html;
MultiSelectAsset::register($this);

$this->params['homeLike'] = ['label'=>'系统平台设置'];
$this->params['breadcrumbs'][] = ['label'=>'用户组(角色)列表'];
$this->title="操作角色管理";
?>
<style>
    .ml-20 {
        margin-left: 10px;
    }
    .width-30 {
        width: 30px;
    }
</style>
<div class="content">
    <?php  echo $this->render('_search',[ 'search' => $search]); ?>
    <div class="table-head">
        <p class="head">用户信息列表</p>
        <div class="float-right">
            <a href="<?= Url::to(['/system/authority/add']) ?>">
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增角色</p>
                    </div>
            </a>
            <span style='float: left;'>&nbsp;|&nbsp;</span>
            <a href="<?= Url::to(['/index/index']) ?>">
                <div class='table-nav'>
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>返回</p>
                </div>
             </a>
        </div>
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
            columns: [[
                {field: "code", title: "角色编码", width: 150},
                {field: "title", title: "用户角色名称", width: 158},
                {field: "description", title: "描述", width: 158},
                {field: "isEnable", title: "状态", width: 140,formatter:function(val){
                 if(val==true){
                        return '已启用'
                    }else{
                        return '未启用'
                    }
                }},
                {field: "created_at", title: "创建日期", width: 150},
                {field: "name", title: "操作", width: 150,formatter:function(val,row){
                    var deleteRole="<span class='width-30'></span>";
                    var authorization="<i><a title='数据权限设置'; href='<?=Url::to(['user/data-authorization']);?>?id="+row['name']+" ' class='ml-20 icon-bar-chart icon-large'>&nbsp;&nbsp;&nbsp;&nbsp;</a></i>"
                    if(!row.isEnable){
                        deleteRole = "<a onclick='deleteRole("+val+")' class='ml-20'><i class='icon-trash icon-l' title='删除'></i></a>";
                    }
                   return "<a class='ml-20' onclick='editRole("+val+")' title='编辑'><i class='icon-edit icon-l'></i></a><a class='ml-20'  href='<?=Url::to(['user/data-authorization']);?>?id="+row['name']+"&tid=0' title='数据权限设置'><i class='icon-bar-chart icon-large'></i></a>"+deleteRole;
                }},
            ]],
            onLoadSuccess: function (data) {
                $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color','white');
                showEmpty($(this),data.total,0);
                datagridTip('#data');
            },
        });

})
        function editRole(id){
            window.location.href ="<?=Url::to(['edit?name='])?>"+id;
        }
        function viewRole(id){
            window.location.href ="<?=Url::to(['view?name='])?>"+id;
        }
        function deleteRole(id){
            layer.confirm("确定要删除这个角色吗?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {"id": id},
                        url: "<?=Url::to(['/system/authority/delete'])?>",
                        success: function (data) {
                            if( data.flag === 1){
                                layer.alert(data.msg,{icon:1,end:function(){ location.reload();}});
                            }else{
                                layer.alert(data.msg,{icon:2})
                            }
                        }
                    });
                },
                function () {
                    layer.closeAll();
                }
            )
            return false;
        }
//    <a class='ml-20' onclick='viewRole("+val+")' title='查看'><i class='icon-eye-open icon-l'></i></a>
</script>
