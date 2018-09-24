<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/10
 * Time: 上午 08:48
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '物流公司信息';
$this->params['homeLike'] = ['label' => '倉儲物流管理', 'url' => ''];
$this->params['breadcrumbs'][] = ['label' => '倉儲物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '物流公司信息', 'url' => ""];
?>
<?php $get = Yii::$app->request->get();
if (!isset($get['LogCmpSearch'])) {
    $get['LogCmpSearch'] = null;
}
?>
<style>
    .search-div {
        width: 990px;
    }

    .back-100 {
        width: 100%;
        height: 30px;
        background: #438EB8;
    }

    .table-heads {
        /*width: 990px;*/
        height: 30px;
    }

    .table-heads p {
        font-size: 16px;
        float: left;
        color: #fff;
        font-weight: bold;
        text-indent: 1em;
    }
</style>
<div class="search-div">
    <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]
    ); ?>
    <div class="table-heads back-100 mt-10">
        <p class="head mt-5">物流公司信息</p>
    </div>
    <input type="hidden" value="<?= Yii::$app->user->identity->staff_id ?>" id="staff_id" name="HrStaff[staff_code]">
    <div class="content">
        <div class="mb-10">
            <div class="inline-block ">
                <label class="width-80 ml-5" for="BsLogCmp-log_cmp_name">物流公司名称</label>
                <select id="BsLogCmp-log_cmp_name" class="width-150" name="LogCmpSearch[log_cmp_name]">
                    <option value="">请选择...</option>
                    <?php foreach ($companyname as $key => $val) { ?>
                        <option
                            value="<?= $val['log_cmp_name'] ?>" <?= isset($get['LogCmpSearch']['log_cmp_name']) && $get['LogCmpSearch']['log_cmp_name'] == $val['log_cmp_name'] ? "selected" : null ?>><?= $val['log_cmp_name'] ?></option>
                    <?php } ?>
                </select>
                <div class="help-block"></div>
            </div>
            <div class="inline-block">
                <label class="width-80" for="BsLogCmp-log_type">公司类型</label>
                <select id="BsLogCmp-log_type" class="width-150" name="LogCmpSearch[log_type]">
                    <option value="">请选择...</option>
                    <?php foreach ($companytype as $key => $val) { ?>
                        <option
                            value="<?= $val['para_code'] ?>" <?= isset($get['LogCmpSearch']['log_type']) && $get['LogCmpSearch']['log_type'] == $val['para_code'] ? "selected" : null ?>><?= $val['para_name'] ?></option>
                    <?php } ?>
                </select>
                <div class="help-block"></div>
            </div>
            <div class="inline-block ">
                <label class="width-80 ml-5" for="BsLogCmp-log_code">承运代码</label>
                <input type="text" id="BsLogCmp-log_code" class="width-150" name="LogCmpSearch[log_code]"
                       value="<?= $get['LogCmpSearch']['log_code'] ?>"
                >
                <div class="help-block"></div>
            </div>
            <div class="inline-block ml-40">
                <?= Html::submitButton('查询', ['class' => 'search-btn-blue search-btn']) ?>
                <?= Html::resetButton('重置', ['class' => 'ml-20 reset-btn-yellow reset-btn', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
            </div>
        </div>
    </div>
    <div class="table-head">
        <div class="table-head">
            <p class="head"></p>
            <p class="float-right">
                <?= \app\classes\Menu::isAction("/warehouse/logistics-company/add") ? Html::a("<span class='text-center ml--5'>新增</span>", Url::to(['add'])) : "" ?>
                <?= \app\classes\Menu::isAction("/warehouse/logistics-company/views") ? Html::a("<span class='text-center ml--5'>详情</span>", "javascript:void(0)", ["id" => "views"]) : "" ?>
                <?= \app\classes\Menu::isAction("/warehouse/logistics-company/update") ? Html::a("<span class='text-center ml--5'>修改</span>", "javascript:void(0)", ["id" => "updates"]) : "" ?>
                <?= \app\classes\Menu::isAction("/warehouse/logistics-company/delete") ? Html::a("<span class='text-center ml--5'>删除</span>", "javascript:void(0)", ["id" => "delete"]) : "" ?>
            </p>
        </div>
    </div>
    <div class="mt-10">

        <div id="data">
        </div>
    </div>
    <div id="load-content" class="overflow-auto"></div>
    <?php ActiveForm::end(); ?>
</div>

<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 10,
            pageList: [10, 20, 30, 40, 50],
            columns: [[
                {field: "log_cmp_name", title: "公司中文名称", width: "100"},
                {field: "log_code", title: "承运代码", width: "100"},
                {field: "log_type_name", title: "公司类型", width: "100"},
                {field: "log_cont", title: "联络人", width: "100"},
                {field: "log_cont_pho", title: "联络人电话", width: "100"},
                {field: "para_name", title: "运输方式", width: "100"},
                {field: "log_scope", title: "经营范围", width: "150"},
                {field: "log_url", title: "公司网址", width: "150"},
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });
    })
    //详情
    $("#views").on("click", function () {
        var a = $("#data").datagrid("getSelected");
        if (a == null) {
            layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
        } else {
            var id = $("#data").datagrid("getSelected")['log_cmp_id'];
            window.location.href = "<?=Url::to(['views'])?>?id=" + id;
        }
    })
    //修改
    $("#updates").on("click", function () {
        var a = $("#data").datagrid("getSelected");
        if (a == null) {
            layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
        } else {
            var id = $("#data").datagrid("getSelected")['log_cmp_id'];
            window.location.href = "<?=Url::to(['update'])?>?id=" + id;
        }
    })
    //删除
    $("#delete").on("click", function () {
        var a = $("#data").datagrid("getSelected");
        if (a == null) {
            layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
        } else {
            var selectId = $("#data").datagrid("getSelected")['log_cmp_id'];
            layer.confirm("确定要删除这条信息吗?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {
                            "id": selectId,
                            "staff_id":$("#staff_id").val()
                        },
                        url: "<?=Url::to(['/warehouse/logistics-company/delete']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                //console.log(msg.result);
                                layer.alert("删除成功！", {
                                    icon: 1, end: function () {
                                        location.reload();
                                    }
                                });
                            } else {
                                //console.log(msg.result);
                                layer.alert("删除失败！", {icon: 2});

                            }
                        },
                        error: function (msg) {
                            layer.alert("删除失败！", {icon: 2})
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
        }
    })
</script>
