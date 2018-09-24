<?php

use yii\helpers\Url;
$this->params['homeLike'] = ['label'=>'系统平台设置'];
$this->params['breadcrumbs'][] = ['label'=>'权限管理','url'=>'index'];
$this->params['breadcrumbs'][] = ['label'=>'用户信息列表'];
$this->title = '用户信息列表';
?>
<div class="content">
    <?php  echo $this->render('_search', [
        'search' => $search,
    ]); ?>

    <div class="table-content">

        <?php  echo $this->render('_action'); ?>

        <div id="data"></div>
    </div>
</div>

<script>
    $(function() {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "user_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                {field: "user_account", title: "账号", width: 120},
                {field: "staff_code", title: "工号", width: 120,formatter:function(val,row){
                   return row.staffInfo.staff_code
                }},
                {field: "staff_name", title: "姓名", width: 120,formatter:function(val,row){
                    return row.staffInfo.staff_name
                }},
                {field: "companyName", title: "公司", width: 120},
                {field: "user_status", title: "是否封存", width: 120,formatter:function(val,row){

                    if(val==20){
                        return '是'
                    }
                    return '否'
                }},
                {field: "username_describe", title: "用户描述", width: 120},
                {field: "roles", title: "权限角色", width: 200,formatter:function(val,row){
                    if(row.is_supper==1){
                        return ''
                    }
                    return row.roles;
                }},

                {field: "is_supper", title: "超级管理员", width: 120,formatter:function(val,row){

                    if(val==1){
                        return '是'
                    }
                    return '否'
                }},

                {field: "start_at", title: "有效期", width: 150,formatter:function(val,row){
                    return val+'~'+row.end_at
                }},
            ]],
            onLoadSuccess: function (data) {
                $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color','white');
                datagridTip("#data");
            }
        });
    })
</script>
