<?php
/**
 * Created by PhpStorm.
 * User: F1669561
 * Date: 2018/1/4
 * Time: 下午 04:33
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;

JqueryUIAsset::register($this);
?>
<style>
    .table span {
        background-color: #1f7ed0;
        font-weight: 100;
        color: #fff;
    }
</style>
<div class="head-first">选择储位</div>
<?php ActiveForm::begin(['id' => 'product_form', 'method' => 'get', 'action' => Url::to(['select-store'])]); ?>
<div style="margin: 0 20px 20px;">
    <input type="hidden" class="_types" value="<?= $params['types'] ?>">
    <input type="hidden" class="_rows" value="<?= $params['rows'] ?>">
    <div class="mt-20 mb-10">
        <label class="width-60" for="wh_id" style="display: none">仓库名称</label>
        <select name="whId" class="width-130 output_wh" disabled style="display: none">
            <option value="">---请选择---</option>
            <?php foreach ($downList['warehouse'] as $key => $val) { ?>
                <option data-id="<?= $val['wh_code'] ?>" data-value="<?= $val['wh_attrw'] == "Y" ? "自有" : "外租" ?>"
                        value="<?= $val['wh_id'] ?>" <?= $params['whId'] == $val['wh_id'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
            <?php } ?>
        </select>
        <label class="width-100">分区码</label>
        <input type="text" class="width-150" id="searchText" name="partCode" value="<?= $params['partCode'] ?>">
        <input type="text" class="width-150 hiden" id="searchText" name="whId" value="<?= $params['whId'] ?>">
        <input type="text" class="width-150 hiden" id="searchText" name="row" value="<?= $params['row'] ?>">
        <input type="text" class="width-150 hiden" id="searchText" name="type" value="<?= $params['type'] ?>">
        <label class="width-100">货架码</label>
        <input type="text" class="width-150" id="searchText" name="rackCode" value="<?= $params['rackCode'] ?>">
        <!--    </div>-->
        <!--    <div class="mb-20">-->
        <label class="width-60">储位</label>
        <input type="text" class="width-130" id="searchText" name="storeCode" value="<?= $params['storeCode'] ?>">
        <button type="submit" class="button-blue ml-390" id="search">查询</button>
        <button type="button" class="button-white"
                onclick="window.location.href='<?= Url::to(['select-store']) . "?whId=" . $params['whId'] . "&row=" . $params['row'] . "&type=" . $params['type'] ?>'">
            重置
        </button>
        <!--    </div>-->
        <div style="height: 20px;"></div>
        <table id="store_data" style="width:100%;"></table>
        <div class="space-20" ></div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="text-center mb-20">
        <button type="button" class="button-blue mr-20" id="confirm_product">确定</button>
        <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
    </div>
    <script>
        $(function () {
            var $dg = $("#store_data");
            var row = "<?= $params['row'] ?>";
            console.log(row);
            $dg.datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                rownumbers: true,
                method: "get",
                idField: "cust_id",
//            check: true,
                singleSelect: true,
                pagination: true,
                pageSize: 5,
                pageList: [5, 10, 15],
                columns: [[
                    {field: "wh_name", title: "仓库", width: 150},
                    {field: "part_code", title: "分区码", width: 150},
                    {field: "part_name", title: "区位名称", width: 150},
                    {field: "rack_code", title: "货架码", width: 150},
                    {field: "st_code", title: "储位", width: 86}
                ]],
                onLoadSuccess: function (data) {
                    $dg.datagrid('clearSelections');
                    datagridTip("#store_data");
                    showEmpty($(this), data.total, 1);
                }
            });

            //添加到parent

            $("#confirm_product").click(function () {
                var aa=$("._types").val();
                var bb=$("._rows").val();
//                alert(bb);
                obj = $dg.datagrid('getChecked')[0];
                if (obj == null) {
                    layer.alert("请选择储位!", {icon: 2, time: 5000});
                } else {
                    if(aa==1){
                        parent.$("#product_table tr").eq(bb-1).find(".store").val(obj.st_code);
                    }else {
                        parent.$("#product_table tr").eq(bb-1).find(".store2").val(obj.st_code);
                        parent.$("#product_table tr").eq(bb-1).find("._store2id").val(obj.st_id);
                    }
//                    $(parent.$("#product_table tr")).find(".st_id2").val(obj.st_id);
                    parent.$.fancybox.close();
                }
            });
        });
    </script>