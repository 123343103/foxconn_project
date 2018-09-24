<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '操作管理'];
$this->title="操作管理";
?>
<div class="content">
    <?php  echo $this->render('_search', [
        'queryParam' => $queryParam,
    ]); ?>
    <div class="table-content">
        <div class="table-head">
            <p class="head">操作列表</p>
            <div class="float-right">
                <?= Menu::isAction('/crm/store-setting/create') ?
                    "<a id='add'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                </a>"
                    : '' ?>
                <span style='float: left;'>&nbsp;|&nbsp;</span>
                <a href="<?= Url::to(['/index/index']) ?>">
                    <div class='table-nav'>
                        <p class='return-item-bgc float-left'></p>
                        <p class='nav-font'>返回</p>
                    </div>
                </a>
            </div>
            <div class="space-10"></div>
            <div id="data"></div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "btn_pkid",
            loadMsg: false,
            columns: [[
                <?= $columns ?>
                {
                        field: "action", title: "操作", width: 100, formatter: function (val, row) {
                        return "<a onclick='updateBtn(" + row.btn_pkid + ")'>修改</a>";
                    }
                },
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#data');
                showEmpty($(this), data.total,0);
            }
        });
        //新增
        $("#add").on("click", function () {
            $.fancybox({
                padding: [],
                fitToView: true,
                width: 550,
                height: 300,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: "<?= Url::to(['create'])?>"
            });
        });

    })
    function updateBtn($id) {
        $.fancybox({
            padding: [],
            fitToView: true,
            width: 500,
            height: 300,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['update'])?>?id=" + $id
        });
    }
</script>



