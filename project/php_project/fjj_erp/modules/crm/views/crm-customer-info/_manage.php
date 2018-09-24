<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/5/25
 * Time: 14:35
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<style>
    .panel {
        width: 620px;
        padding: 0 20px;
    }

    .fancybox-wrap {
        top: 0px !important;
        left: 0px !important;
    }
</style>
<div class="head-first">选择客户经理人</div>
<div>
    <div class="mb-10 overflow-auto" style="padding: 0 20px">
        <form action="<?= Url::to(['select-manage']) ?>" method="get" id="customer_form" class="float-left">
            <input type="text" name="keyWord" placeholder="模糊查询"
                   value="<?= $queryParam['keyWord'] ?>"
                   id="seacher">
            <?= \yii\helpers\Html::button('查询', ['class' => 'search-btn-blue', 'style' => 'margin-left:10px;', 'id' => 'img']) ?>
            <?= \yii\helpers\Html::button('重置', ['class' => 'reset-btn-yellow', 'style' => 'margin-left-10', 'onclick' => 'window.location.href="' . Url::to(['select-manage']) . '"']) ?>
        </form>
    </div>
    <div id="data" style="width: 800px;"></div>
</div>

<div class="space-10"></div>

<div class="text-center">
    <button class="button-blue-big" id="confirm">确&nbsp;定</button>
    <button class="button-white-big" onclick="close_select()">返回</button>
</div>
<script>
    $(function () {
        //搜索
        $("#img").on("click", function () {
            $("#customer_form").submit();
        });

        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "staff_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            checkOnSelect:true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "", checkbox: true},
                {field: "staff_code", title: "工号", width: 140},
                {field: "staff_name", title: "姓名", width: 150},
                {field: "sts_sname", title: "销售点", width: 200},
                {field: "csarea_name", title: "所在军区", width: 170}
            ]],
            onDblClickRow: function () {
                reload();
            },
            onLoadSuccess: function (data) {
                showEmpty($(this), data.total, 0);
            }
        });

        //确定
        $("#confirm").on("click", function () {
            reload();
        });

        function reload() {
            var radio = $("#data").datagrid("getSelected");
            var obj = $("#data").datagrid('getSelected');
            if (radio.length < 1) {
                parent.layer.alert("请选择一条信息", {icon: 2, time: 5000});
            }
            else {
                <?php if(!empty($id)){ ?>
                var str = parent.$(".staff_id").val();
                if(str.indexOf(obj.staff_id)>=0){
                    layer.alert("该客户经理人已存在,请重新选择", {icon: 2, time: 5000});return false;
                }
                parent.$(".staff_id").val(obj.staff_id + ',' + '<?= $id ?>');
                parent.$(".staff_code").val(obj.staff_code + ' ' + obj.staff_name + ',' + '<?= $code ?>');
                parent.$(".staff_name").text(obj.staff_name);
//                parent.$(".staff_code").validatebox('validate');
                parent.$.fancybox.close();
                <?php }else{ ?>
                var str = parent.$(".staff_id").val();
                if(str.indexOf(obj.staff_id)>=0){
                    layer.alert("该客户经理人已存在,请重新选择", {icon: 2, time: 5000});return false;
                }
                parent.$(".staff_id").val(obj.staff_id);
                parent.$(".staff_code").val(obj.staff_code + ' ' + obj.staff_name);
                parent.$(".staff_name").text(obj.staff_name);
//                parent.$(".staff_code").validatebox('validate');
                parent.$.fancybox.close();
                <?php } ?>
            }
//            var obj = $("#data").datagrid('getSelected');
//            if (obj == null) {
//                parent.layer.alert('请选择一条信息', {icon: 2, time: 5000});
//            } else {
//                $(".staff_id", window.parent.document).val(obj.staff_id);
//                $(".staff_code", window.parent.document).val(obj.staff_code + '(' + obj.staff_name + ')');
//                $(".staff_name", window.parent.document).text(obj.staff_name);
//                parent.$.fancybox.close();
//            }
        }

        $("#seacher").focus(function () {
            $("#seacher").attr("placeholder", "");
        })
        $("#seacher").blur(function () {
            $("#seacher").attr("placeholder", "模糊查询");
        })

    })
</script>