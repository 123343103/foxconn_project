<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/29
 * Time: 下午 02:09
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '车辆信息管理';
$this->params['homeLike'] = ['label' => '倉儲物流管理', 'url' => ''];
$this->params['breadcrumbs'][] = ['label' => '倉儲物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '车辆信息管理', 'url' => ""];
?>
<?php $get = Yii::$app->request->get();
if (!isset($get['PartSearch'])) {
    $get['PartSearch'] = null;
}
?>
<style>
    .search-div {
        width: 990px;
    }
    .back-100{
        width: 100%;
        height:30px;
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
    }
</style>
<div class="search-div">
    <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]
    ); ?>
    <input type="hidden" value="<?= Yii::$app->user->identity->staff_id ?>" id="staff_id" name="HrStaff[staff_code]">
    <div class="table-heads back-100 mt-10">
        <p class="head mt-5">车辆信息</p>
    </div>
    <div class="content">
        <div class="mb-10">
            <div class="inline-block ">
                <label class="width-60 ml-5" for="bsveh-veh_company">公司</label>
                <select id="bsveh-veh_company" class="width-120" name="BsVehSearch[log_cmp_name]">
                    <option value="">请选择...</option>
                    <?php foreach ($companyname as $key => $val) { ?>
                        <option
                            value="<?= $val['log_cmp_name'] ?>" <?= isset($get['BsVehSearch']['log_cmp_name']) && $get['BsVehSearch']['log_cmp_name'] == $val['log_cmp_name'] ? "selected" : null ?>><?= $val['log_cmp_name'] ?></option>
                    <?php } ?>
                </select>
                <div class="help-block"></div>
            </div>
            <div class="inline-block">
                <label class="width-60" for="bsveh-veh_number">车牌号</label>
                <input type="text" id="bsveh-veh_number" class="width-120" name="BsVehSearch[veh_number]"
                       value="<?= $get['BsVehSearch']['veh_number'] ?>"
                >
                <div class="help-block"></div>
            </div>
            <div class="inline-block ">
                <label class="width-60 ml-5" for="bsveh-veh_brand">品牌</label>
                <input type="text" id="bsveh-veh_brand" class="width-120" name="BsVehSearch[veh_brand]"
                       value="<?= $get['BsVehSearch']['veh_brand'] ?>"
                >
                <div class="help-block"></div>
            </div>
            <div class="inline-block ">
                <label class="width-60 ml-5" for="bsveh-person_charge">负责人</label>
                <input type="text" id="bsveh-person_charge" class="width-120" name="BsVehSearch[person_charge]"
                       value="<?= $get['BsVehSearch']['person_charge'] ?>"
                >
                <div class="help-block"></div>
            </div>
            <div class="inline-block ml-40">
                <?= Html::submitButton('查询', ['class' => 'search-btn-blue search-btn']) ?>
                <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-20 reset-btn', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
            </div>
        </div>
    </div>
    <div class="table-head">
        <div class="table-head">
            <p class="head"></p>
            <p class="float-right">
                <?= \app\classes\Menu::isAction("/warehouse/vehicle-information/add") ? Html::a("<span class='text-center ml--5'>新增</span>", Url::to(['add'])) : "" ?>
                <?= \app\classes\Menu::isAction("/warehouse/vehicle-information/update") ? Html::a("<span class='text-center ml--5'>编辑</span>", "javascript:void(0)", ["id" => "updates"]) : "" ?>
                <?= \app\classes\Menu::isAction("/warehouse/vehicle-information/delete") ? Html::a("<span class='text-center ml--5'>删除</span>", "javascript:void(0)", ["id" => "delete"]) : "" ?>
            </p>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="mt-10">

        <div id="data">
        </div>
    </div>
    <div id="load-content" class="overflow-auto"></div>
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
                {field: "log_cmp_name", title: "公司", width: "100"},
                {field: "veh_number", title: "车牌号", width: "100"},
                {field: "veh_brand", title: "车辆品牌", width: "100"},
                {field: "veh_type", title: "车辆类型", width: "100"},
                {field: "veh_color", title: "车辆颜色", width: "80"},
                {field: "veh_contacts", title: "联系人", width: "100"},
                {field: "contacts_phone", title: "电话", width: "150"},
                {field: "person_charge", title: "负责人", width: "100"},
                {field: "person_phone", title: "电话", width: "150"}
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });
    })
    $("#updates").on("click", function () {
        var a = $("#data").datagrid("getSelected");
        if (a == null) {
            layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
        } else {
            var id = $("#data").datagrid("getSelected")['veh_id'];
            window.location.href = "<?=Url::to(['update'])?>?id=" + id;
        }
    })

    //删除
    $("#delete").on("click", function () {
        var a = $("#data").datagrid("getSelected");
        if (a == null) {
            layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
        } else {
            var selectId = $("#data").datagrid("getSelected")['veh_id'];
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
                        url: "<?=Url::to(['/warehouse/vehicle-information/delete']) ?>",
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
