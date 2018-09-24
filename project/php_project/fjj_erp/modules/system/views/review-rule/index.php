<?php
/**
 *  F3858995
 * 2016/10/27
 */
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '审核流程设置'];
$this->params['breadcrumbs'][] = ['label' => '单据审核流列表'];
?>
<div class="content">

    <?php  echo $this->render('_action'); ?>
    <div class="mt-10">
       <div id="data"></div>
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
            columns: [[
                {field: "business", title: "业务",width:"200",formatter: function (value, row, index) {
                    if (row.business) {
                        return row.business.business_desc;
                    } else {
                        return null;
                    }
                } },
                {field: "businessType", title: "业务类型",width:"175",formatter: function (value, row, index) {
                    if (row.businessType) {
                        return row.businessType.business_value;
                    } else {
                        return null;
                    }
                }},
                {field: "review_desc", title: "审核流程说明",width:"200"},
                {field: "lastUpdate", title: "最后更新人",width:"200",formatter: function (value, row, index) {
                    if (row.lastUpdate) {
                        return row.lastUpdate.staff_name;
                    } else {
                        return null;
                    }
                }},
                {field: "update_at", title: "最后更新时间",width:"140"}
            ]],
            onLoadSuccess : function(){
                setMenuHeight();
            }
        });
        $("#edit").on("click", function () {
            var id = $("#data").datagrid("getSelected")['review_rule_id'];
            if (id == null) {
                layer.alert("請點擊選擇一條审核流信息",{icon:2,time:5000});
            } else {
                window.location.href = "<?=Url::to(['/system/review-rule/edit'])?>?id=" + id;
            }
        });
        $("#createRule").on("click", function () {
                window.location.href = "<?=Url::to(['/system/review-rule/rule'])?>";
        })
    })
</script>