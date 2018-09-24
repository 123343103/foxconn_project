<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/12/23
 * Time: 下午 03:35
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "select-stinfo"]); ?>
<style>
    .width-100{
        width:100px
    }
    .width-50{
        width:50px
    }
    .width-120{
        width:120px;
    }
    .width-70{
        width: 65px;
    }
    .space-20{
        height: 20px;
    }
</style>
<h3 class="head-first">储位信息</h3>
<div class="content" style="width: 600px;">
    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <input type="hidden" name="wh_code" value="<?=$params['wh_code']?>"/>
            <input type="hidden" name="part_no" value="<?=$params['part_no']?>"/>
            <input type="hidden" name="st_code" value="<?=$params['st_code']?>"/>
            <div class="inline-block">
                <label class="label-width qlabel-align width-50">仓库</label><label>：</label>
                <select name="wh_id" class="width-120" disabled="disabled">
                    <option value="">全部</option>
                    <?php foreach ($downList as $key => $val) { ?>
                        <option
                            value="<?= $val["wh_code"] ?>" <?= isset($params['wh_code']) && $params['wh_code'] == $val["wh_code"] ? "selected" : null ?>><?= $val["wh_name"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align width-100">分区码</label><label>：</label>
                <input type="text" name="part_code" class="width-120"
                       value="<?= $params['part_code'] ?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align width-50">货架码</label><label>：</label>
                <input type="text" name="rack_code" class="width-120"
                       value="<?= $params['rack_code'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align width-100">储位码</label><label>：</label>
                <input type="text" name="stcode" class="width-120"
                       value="<?= $params['stcode'] ?>">
            </div>
            <?= \yii\helpers\Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue', 'style' => 'margin-left:20px']) ?>
            <?= \yii\helpers\Html::button('重置', ['class' => 'button-blue reset-btn-yellow', 'onclick' => 'window.location.href="' . \yii\helpers\Url::to(['select-stinfo',"wh_code"=>$params['wh_code'],"part_no"=>$params["part_no"],"st_code"=>$params["st_code"]]) . '"']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="table-content">
        <div id="data"></div>
    </div>
    <div class="space-20"></div>
    <div class="mb-20 text-center">
        <button class="button-blue-big" id="check">确定</button>
        <button class="button-white-big ml-20 close" type="button" onclick="parent.$.fancybox.close()">取消</button>
    </div>
</div>

<script>
    //    $(document).ready(function(){
    //        var whid=$("#wh_id").val();
    //        window.location.href="<?//=Url::to(['numadd']).'?wh_id='?>//"+whid;
    //        return;
    //    });
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "<?=$params['wh_id']?>",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize:5,
            pageList:[5,10,15],
            columns: [[
                {field: 'ck',checkbox:true},
                {field: "wh_name", title: "仓库", width: 100},
                {field: "part_code", title: "分区码", width: 100},
                {field: "part_name", title: "区位名称", width: 100},
                {field: "rack_code", title: "货架码", width: 100},
                {field: "st_code", title: "储位码", width: 100},
                {field: "batch_no", title: "批次", width: 100},
            ]],
            onLoadSuccess: function (data) {
                datagridTip("#data");
                $("#data").datagrid("unselectAll");
                $("#data").datagrid("uncheckAll");
                $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color','white');
                showEmpty($(this),data.total,1);
            },

        });

        $("#check").click(function () {
            reload();
        });
        function reload(){
            var data = $("#data").datagrid("getChecked");
//            alert(data.length);
//            var i =300;
            var st_code=[];//储位码
            var st_id=[];//储位id
//            var L_invt_bach=[];//批次
            var invt_num=[];//数量
            var part_name=[];//区位
            var rack_code=[];//货架位
            $.each(data, function(index, item){
               st_code.push(item.st_code);
               st_id.push(item.st_id);
//               L_invt_bach.push(item.L_invt_bach);
                invt_num.push(item.invt_num)
                part_name.push(item.part_name);
                rack_code.push(item.rack_code);
            });
            parent.setstid(st_code,st_id,invt_num,part_name,rack_code);
//            window.parent.$("#st_id").text(st_code);
            parent.$.fancybox.close();
        }
    })

</script>
