<?php
/**
 * User: F1676624
 * Date: 2017/7/26
 */
/* @var $this yii\web\View */
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
<div class="head-first">选择商品</div>
<div style="margin: 0 20px 20px;">
    <div class="mb-10">
        <?php ActiveForm::begin(['id' => 'product_form', 'method' => 'get', 'action' => Url::to(['select-product'])]); ?>
        <label class="width-60" for="whId">仓库名称</label>
        <select name="whId" class="width-130 output_wh" <?= empty($params['whId']) ? null : "disabled" ?>>
            <option value="">---请选择---</option>
            <?php foreach ($downList['warehouse'] as $key => $val) { ?>
                <option data-id="<?= $val['wh_code'] ?>" data-value="<?= $val['wh_attrw'] == "Y" ? "自有" : "外租" ?>"
                        value="<?= $val['wh_id'] ?>" <?= $params['whId'] == $val['wh_code'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
            <?php } ?>
        </select>
<!--        <label class="width-60">储位</label>-->
<!--        <input type="text" class="width-100" id="searchText" name="store" value="--><?//= $params['store'] ?><!--">-->
<!--        <input type="text" class="width-150 hiden" id="searchText" name="whId" value="--><?//= $params['whId'] ?><!--">-->
<!--        <input type="text" class="width-150 hiden" id="searchText" name="row" value="--><?//= $params['row'] ?><!--">-->
<!--        <input type="text" class="width-150 hiden" id="searchText" name="type" value="--><?//= $params['type'] ?><!--">-->
<!--        <label class="width-60">关键词</label>-->
        <input type="text" class="width-150" style="width: 250px;" id="searchText" name="kwd" placeholder="储位/料号/商品名称/规格型号"
               value="<?= $params['kwd'] ?>">
        <button type="submit" class="button-blue ml-50" id="search">查询</button>
        <button type="button" class="button-white"
                onclick="window.location.href='<?= Url::to(['select-product']) . "?whId=" . $params['whId'] . "&row=" . $params['row'] . "&type=" . $params['type'] ?>'">
            重置
        </button>
        <!--        <a href="-->
        <? //= Url::to(['/crm/crm-customer-info/create']) ?><!--" target="_blank" class="float-right text-center"-->
        <!--           style="width:80px;line-height:25px;background-color:#1f7ed0;color:#ffffff;">新增客户</a>-->
    </div>
    <table id="product_data" style="width:100%;"></table>
    <div class="space-20"></div>
    <?php ActiveForm::end(); ?>
</div>
<div class="text-center mb-20">
    <button type="button" class="button-blue mr-20" id="confirm_product">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<script>
    $(function () {
        var $dg = $("#product_data");
        var row = "<?= $params['row'] ?>";
        var type = "<?= $params['type']?>";
        var ischeckbox = "<?= $params['whId']?>";
        //添加到parent
        var xunhao = parent.xun;

        $dg.datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            check: false,
            <?= !empty($params['whId']) ? "" : "singleSelect:true," ?>
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                <?= !empty($params['whId']) ? "{field: '', checkbox: true, width: 28}," : "" ?>
                {field: "wh_name", title: "仓库", width: 100},
                {field: "part_no", title: "料号", width: 150},
                {field: "pdt_name", title: "商品名称", width: 150},
                {field: "tp_spec", title: "规格型号", width: 80},
                {field: "brand", title: "品牌", width: 80,hidden:'true'},
                {field: "batch_no", title: "批次", width: 80},
                {field: "st_code", title: "储位", width: 80},
                {field: "invt_num", title: "现有库存", width: 90}
            ]],
            onLoadSuccess: function (data) {
                if (ischeckbox == "") {
                    $(".datagrid-cell-c1-invt_num").css("width", 116);
                }
                $dg.datagrid('clearSelections');
                datagridTip("#product_data");
                showEmpty($(this), data.total, 1);
            }
        });
        $("#confirm_product").click(function () {
            //删除table下面的空行
            if(type!=42){
                parent.$("#product_table").find("tr").each(function(){
                    var _pro=$(this).find(".pdt_no").val();
                    if(_pro==""){
                        $(this).remove();
                    }
                });
            }
            var tr = "";
            var obj;
            if (type == 41) {     //储位异动
                obj = $dg.datagrid('getChecked');
//                         console.log(obj);
                if (obj.length == 0) {
                    parent.layer.alert('请选择商品！', {icon: 2, time: 5000});
                    return false;
                }
//                alert(obj.length);
                for (i = 0; i < obj.length; i++) {
                    tr = tr + '<tr>' +
                        '<td><span class="xunhao">' + xunhao + '</span></td>' +
                        '<td>' + '<span class="wd100"><input type="checkbox"></span>' + '</td>' +
                        '<td><input class="height-30 wd100  text-center  pdt_no" name="changeL[' + xunhao + '][pdt_no]" type="text" ' +
                        'data-options="required:true"  maxlength="20" placeholder="请输入" ' +
                        'value="' + obj[i].part_no + '"><input class="hiden pdt_id" value="' + obj[i].pdt_id + '"/></td>' +
                        '<td class=""><p class="wd100">' + obj[i].pdt_name + '</p><input type="hidden" ' +
                        'name="changeL[' + xunhao + '][pdtname]" value="' + obj[i].pdt_name + '"></td>' +
                        '<td class=""><p class="wd100">' + obj[i].tp_spec + '</p></td>' +
                        '<td class=""><p class="wd100">' + obj[i].batch_no + '</p><input class="" type="hidden" ' +
                        'name="changeL[' + xunhao + '][chl_bach]" data-options="required:true" value="' + obj[i].batch_no + '"></td>' +
                        '<td class=""><p class="wd100">' + obj[i].st_code + '</p><input class="hiden  st_id" type="text" ' +
                        'name="changeL[' + xunhao + '][st_id]" data-options="required:true" value="' + obj[i].st_id + '"></td>' +
                        '   <td><input class="height-30 wd100  text-center  store" type="text" ' +
                        'data-options="required:true" maxlength="20" readonly="readonly" placeholder="请输入" value="">' +
                        '<input class="hiden st_id2" name="changeL[' + xunhao + '][st_id2]" value="' + obj[i].pdt_id + '"/></td>' +
                        '   <td><input class="height-30 wd100  text-center" type="text" ' +
                        'data-options="required:true" name="changeL[' + xunhao + '][chl_num]" ' +
                        'maxlength="20" placeholder="请输入" value=""></td>' +
                        '<td class=""><p class="wd100">' + obj[i].unit + '</p><input type="hidden" ' +
                        'name="changeL[' + xunhao + '][unit]" value="' + obj[i].unit + '"></td>' +
                        '<td><a class="wd100" onclick="reset(this)">重置</a>  ' +
                        '<a onclick="vacc_del(this,' + "'product_table'" + ')">删除</a></td>' +
                        '</tr>';
                    xunhao++;
                }
                parent.xun = xunhao;
                //追加到指定行
                if (row != "" && row != null) {
                    $(tr).insertAfter(parent.$("#product_table tr")[(row - 1)]);
                    parent.$("#product_table tr")[(row - 1)].remove();
                } else {
                    row = parent.$("#product_table").children().length;
                    parent.$("#product_table").append($(tr));
                }
            } else if (type == 42) {    //料号异动
                obj = $dg.datagrid('getChecked')[0];
                if(row=="" || row==null){
                parent.$("#product_table").find("tr").each(function () {
                   var aa=parent.$(this).find(".pdt_no").val();
                    if(aa=="") {
                        parent.$(this).find("._invtiid").val(obj['invt_iid']);
                        parent.$(this).find(".pdt_no").val(obj['part_no']);
                        parent.$(this).find(".wh_id").val(obj['wh_id']);
                        parent.$(this).find(".cal_pc").text(obj['batch_no']);
                        parent.$(this).find("._calpc").val(obj['batch_no']);
                        parent.$(this).find(".st_id").val(obj['st_id']);
                        parent.$(this).find(".pdt_name").find('p').html(obj['pdt_name']);
                        parent.$(this).find(".tp_spec").find('p').html(obj['tp_spec']);
                        parent.$(this).find(".whhouse").find('p').html(obj['wh_name']);
                        parent.$(this).find(".store1").find('p').html(obj['st_code']);
                        parent.$(this).find(".L_invt_num").find('p').html(obj['invt_num']);
                        parent.$(this).find("._beforenum").val(obj['invt_num']);
                        parent.$(this).find(".unit").find('p').html(obj['unit']);
                        return false;
                        }
                    });
                }else{
                        $(parent.$("#product_table tr")[row - 1]).find(".pdt_no").val(obj['part_no']);
                        $(parent.$("#product_table tr")[row - 1]).find(".pdt_id").val(obj.pdt_id);
                        $(parent.$("#product_table tr")[row - 1]).find(".wh_id").val(obj.wh_id);
                        $(parent.$("#product_table tr")[row - 1]).find(".cal_pc").text(obj.batch_no);
                        $(parent.$("#product_table tr")[row - 1]).find("._calpc").val(obj.batch_no);
                        $(parent.$("#product_table tr")[row - 1]).find(".st_id").val(obj.st_id);
                        $(parent.$("#product_table tr")[row - 1]).find(".pdt_name").find('p').html(obj.pdt_name);
                        $(parent.$("#product_table tr")[row - 1]).find(".tp_spec").find('p').html(obj.tp_spec);
                        $(parent.$("#product_table tr")[row - 1]).find(".whhouse").find('p').html(obj.wh_name);
                        $(parent.$("#product_table tr")[row - 1]).find(".store1").find('p').html(obj.st_code);
                        $(parent.$("#product_table tr")[row - 1]).find(".L_invt_num").find('p').html(obj.invt_num);
                        $(parent.$("#product_table tr")[row - 1]).find(".unit").find('p').html(obj.unit);
                }
            } else if (type == 43) {     //移仓异动

                obj = $dg.datagrid('getChecked');
                //         console.log(obj);
                if (obj.length == 0) {
                    parent.layer.alert('请选择商品！', {icon: 2, time: 5000});
                    return false;
                }
                for (i = 0; i < obj.length; i++) {
                    tr = tr + '<tr>' +
                        '<td><span class="xunhao">' + xunhao + '</span></td>' +
                        '<td>' + '<span class="wd100"><input type="checkbox"></span>' + '</td>' +
                        '<td><input class="height-30 wd100  text-center  pdt_no" ' +
                        'name="changeL[' + xunhao + '][pdt_no]" type="text" ' +
                        'data-options="required:true"  maxlength="20" placeholder="请输入" value="' + obj[i].part_no + '">' +
                        '<input class="hiden pdt_id"  value="' + obj[i].pdt_id + '"/></td>' +
                        '<td class=""><p class="wd100">' + obj[i].pdt_name + '</p></td>' +
                        '<td class=""><p class="wd100">' + obj[i].brand + '</p></td>' +
                        '<td class=""><p class="wd100">' + obj[i].tp_spec + '</p></td>' +
                        '<td class=""><p class="wd100">' + obj[i].batch_no + '</p><input class="hiden " type="text" ' +
                        'name="changeL[' + xunhao + '][chl_bach]" data-options="required:true" value="' + obj[i].batch_no + '"></td>' +
                        '<td class=""><p class="wd100">' + obj[i].st_code + '</p><input class="hiden  st_id2" type="text" ' +
                        'name="changeL[' + xunhao + '][st_id]" data-options="required:true" value="' + obj[i].st_id + '"> </td>' +
//                        '   <td><input class="height-30 wd100  text-center  store" type="text" data-options="required:true" maxlength="20" readonly="readonly" placeholder="请点击选择" value=""><input class="hiden  st_id2" type="text" name="changeL[' + xunhao + '][st_id]" data-options="required:true" value="' + obj[i].st_id + '"></td>' +
                        '   <td><input class="height-30 wd100  text-center" type="text" data-options="required:true" name="changeL[' + xunhao + '][chl_num]" maxlength="20" placeholder="请输入" value=""></td>' +
                        '<td class=""><p class="wd100">' + obj[i].unit + '</p></td>' +
                        '<td><a class="wd100" onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'product_table'" + ')">删除</a></td>' +
                        '</tr>';
                    xunhao++;
                }
                parent.xun = xunhao;
                //追加到指定行
                if (row != "" && type!=42) {
                    $(tr).insertAfter(parent.$("#product_table tr")[(row - 1)]);
                    parent.$("#product_table tr")[(row - 1)].remove();
                } else {
                    $(tr).appendTo(parent.$("#product_table"));
                }
            }

            parent.$.fancybox.close();
        });
    });
</script>