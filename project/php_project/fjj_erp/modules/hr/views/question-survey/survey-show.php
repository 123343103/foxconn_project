<?php
/**
 * G0007903
 * 2017/11/07
 */
use app\assets\JqueryUIAsset;  //ajax引用jQuery样式
JqueryUIAsset::register($this);
$this->title = "待填写问卷提醒";
?>
<style>
    .space-30 {
        width: 100%;
        height: 30px;
    }

    .text-center {
        text-align: center;
    }
</style>
<div class="content">
    <h2 class="head-first"><?= $this->title ?></h2>
    <div class="width-460 ml-20">
        <div id="data" style="width:460px;"></div>
    </div>
    <div class="space-30"></div>
    <div class="text-center">
        <button class="button-white-big" onclick="close_refresh()">关闭</button>
    </div>
    <a id="aaa" class="display-none">click here</a>
    <input type="hidden" id="ccc">
    <input type="hidden" id="ddd">
</div>
<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "invst_id",
            loadMsg: "加载中...",
            pagination: true,
            pageSize: 6,
            pageList: [6, 12, 18],
            singleSelect: true,
            columns: [[
                {
                    field: "content_all", title: "内容", width: 420, formatter: function (value, row, index) {
                    if (row.invst_subj) {
                        return (row.invst_subj);
                    } else {
                        return '<span class="red">暂无' +
                            '问卷信息!</span>'
                    }
                }
                },
                {field: "invst_id", title: "问卷ID", width: 0, hidden: true}
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this), data.total, 0);
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件

                var staff_id = encodeURIComponent("<?=$userinfo['staff_id']?>");//登录人主键id
                var staff_name = encodeURIComponent("<?=$userinfo['staff_name']?>");//登录人姓名
                var staff_code = encodeURIComponent("<?=$userinfo['staff_code']?>");//登录人工号
                var org_id = encodeURIComponent("<?=$org['organization_id']?>");//登录人部门主键id
                var org_code = encodeURIComponent("<?=$org['organization_code']?>");//登录人部门代码
                var org_name = encodeURIComponent("<?=$org['organization_name']?>");//登录人部门名称
                var invst_id = rowData['invst_id'];//问卷主键
                var subj = rowData['invst_subj'];//问卷主题
                var path = rowData['invst_path'];//问卷路径
                var index = "<?=$index?>";//返回主页
                if (path != null && path != "") {
                    //window.location.href="../HTMLPage1.html?staff_name="+name+"&staff_code="+code;
                    var url = "<?=\Yii::$app->ftpPath['httpIP'] . Yii::$app->ftpPath['QST']['father'] ?>" + path + "?" + window.btoa("staff_name=" + staff_name + "&staff_code=" + staff_code + "&staff_id=" + staff_id + "&org_id=" + org_id + "&org_code=" + org_code + "&org_name=" + org_name + "&yn_log=1" + "&invst_id=" + invst_id + "&index=" + index);
                    window.open(url);
                } else {
                    layer.alert("问卷还未生成,暂不可填写 !", {icon: 2});
                }


            }
        })
    });
    function close_refresh() {
        parent.$.fancybox.close();
    }
</script>
