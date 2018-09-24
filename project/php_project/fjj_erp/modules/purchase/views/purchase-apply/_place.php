<?php
/**
 * Created by PhpStorm.
 * User: F1669561
 * Date: 2017/11/18
 * Time: 下午 02:39
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);

?>
<div class="head-first">配送地点选择</div>
<div>
    <div class="mb-10 overflow-auto" style="padding: 0 20px">
        <form action="<?= Url::to(['select-place']) ?>" method="get" id="place_form" class="float-left">
            <input type="text" name="keyWord" placeholder="请输入地点代码/名称"
                   value="<?= $queryParam['keyWord'] ?>"
                   id="seacher">
            <?= \yii\helpers\Html::button('查询', ['class' => 'search-btn-blue', 'style' => 'margin-left:10px;', 'id' => 'img']) ?>
            <?= \yii\helpers\Html::button('重置', ['class' => 'reset-btn-yellow', 'style' => 'margin-left-10', 'onclick' => 'window.location.href="' . Url::to(['select-place']) . '"']) ?>
        </form>
    </div>
    <div id="data" style="width:100%;"></div>
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
            $("#place_form").submit();
        });

        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            fitColumns:true,
            method: "get",
            idField: "rcp_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            checkOnSelect:true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "rcp_no", title: "地点代码",width: 120},
                {field: "rcp_name", title: "地点名称",width: 120},
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
//            var radio = $("#data").datagrid("getSelected");
            var obj = $("#data").datagrid('getSelected');
            if (obj==null) {
                parent.layer.alert("请选择配送地点信息", {icon: 2, time: 5000});
            }
            else {
                var aa=obj.rcp_name;
                var cc=obj.rcp_no;
                var bb=HTMLDecode(aa);
                parent.$("#_wh_name").val(bb);
                parent.$("._wh_code").val(cc);
                parent.$("#_wh_name").attr("data-options","false");
                parent.$("#_wh_name").removeClass("validatebox-invalid");
//                parent.$("#_wh_name").removeClass("easyui-validatebox");
//                parent.$("#_wh_name").removeClass("validatebox-text");
                parent.$.fancybox.close();
            }
        }

        function HTMLDecode(text)
            {    var temp = document.createElement("div");
                temp.innerHTML = text;
                var output = temp.innerText || temp.textContent;
                temp = null;
                return output;
            }

//        $("#seacher").focus(function () {
//            $("#seacher").attr("placeholder", "");
//        })
//        $("#seacher").blur(function () {
//            $("#seacher").attr("placeholder", "模糊查询");
//        })

    })
</script>