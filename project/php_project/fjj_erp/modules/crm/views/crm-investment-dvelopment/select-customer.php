<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/24
 * Time: 14:09
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;

JqueryUIAsset::register($this);
?>
<style>
    .panel {
        width: 620px;
        padding: 0 20px;
    }
    .fancybox-wrap{
      top:  0px !important;
      left: 0px !important;
    }
</style>
<div class="head-first">选择客户</div>
<div class="ml-20">
    <div class="mb-10 overflow-auto" style="padding: 0 20px">
        <form action="<?= Url::to(['select-customer','ctype'=>$ctype]) ?>" method="get" id="customer_form" class="float-left">
            <input type="hidden" name="ctype" value="<?= $ctype ?>">
            <input type="text" name="keywords" class="width-200" style="height: 30px;"
                   value="<?= $queryParam['keywords'] ?>" id="keywords">
            <img id="img" src="<?= Url::to('@web/img/icon/search.png') ?>" alt="搜索"
                 style="cursor: pointer; vertical-align: bottom; margin-left: -4px;">
            <button id="resetSearch" class="button-blue">重置</button>
        </form>
        <p class="float-right mt-5 mr-80"><a>
<!--                <button type="button" class="button-blue text-center" style="width:80px;" id="add-firm">新增客户</button>-->
            </a></p>
    </div>
    <div id="data"></div>
</div>
<div class="text-center mt-10">
    <button class="button-blue-big" id="confirm">确&nbsp;定</button>
    <button class="button-white-big ml-20" onclick="close_select()">取消</button>
</div>
<script>
    $(function () {
        //搜索
        $("#img").on("click", function () {
            $("#customer_form").submit();
            showEmpty($(this),data.total,0);
        });
        $("#resetSearch").on("click", function () {
            $("#keywords").val(' ');
            $("#data").datagrid("load",{
                'keywords':''
            });

            return false;
        });

        /*新增厂商*/
        $("#add-firm").on('click', function () {
            $("#add-firm").attr("href", "<?= Url::to(['create-customer']) ?>");
            $("#add-firm").fancybox({
                padding: [],
                fitToView: false,
                width: 800,
                height: 570,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe'
            });
        })

        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 10,
            pageList: [10, 20],
            columns: [[
                {field: "cust_filernumber", title: "客户编码", width: 200},
                {field: "cust_sname", title: "客户名称", width: 200},
                {field: "cust_shortname", title: "客户简称", width: 180}
            ]],
            onDblClickRow: function () {
                reload();
            },
            onLoadSuccess: function (data) {
                datagridTip('#data');
                showEmpty($(this),data.total,0);
            }
        });

        //确定
        $("#confirm").on("click", function () {
            reload();
        });

    });
    function reload() {
        var obj = $("#data").datagrid('getSelected');
        if (obj == null) {
            parent.layer.alert('请选择一条信息', {icon: 2, time: 5000});
        } else {
            $(".cust_id", window.parent.document).val(obj.cust_id);
            $(".cust_name", window.parent.document).val(obj.cust_sname);
            $(".cust_shortname", window.parent.document).val(obj.cust_shortname);
            $(".cust_contacts", window.parent.document).val(obj.cust_contacts);
            $(".cust_tel1", window.parent.document).val(obj.cust_tel1);
            $(".cust_tel2", window.parent.document).val(obj.cust_tel2);

            parent.$.fancybox.close();
        }
    }
</script>