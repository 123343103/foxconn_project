<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/6/9
 * Time: 上午 09:10
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title='类别管理';
$this->params['homeLike'] = ['label' => '商品开发管理'];
$this->params['breadcrumbs'][] = ['label' => '商品类别管理'];
?>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get'
]); ?>
<div class="space-10"></div>
<div class="space-10"></div>
<div class="search-div">
    <div class="inline-block ">
        <label for="catg_name" class="width-80  text-left">请选择大类</label>
        <select name="BsCategorySearch[catg_id]" class="width-160" id="catg_no">
            <?php foreach ($categoryname['catgname'] as $val) { ?>
                <option
                    value="<?= $val['catg_id'] ?>" <?= isset($params['BsCategorySearch']['catg_id']) && $params['BsCategorySearch']['catg_id'] == $val['catg_id'] ? "selected" : null ?>>  <?= $val['catg_name'] ?>  </option>
            <?php } ?>
        </select>
    </div>
    <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-40']) ?>
</div>
<?php ActiveForm::end(); ?>
<div class="content">
    <div class="table-content">
        <div class="table-head">
            <p class="head" style="color: red">温馨提示：黄色背景表示还没添加数据</p>
            <div class="float-right">
                <a id="add">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="add-item-bgc " style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
                <a id="return" href="<?= Url::to(['/index/index']) ?>">
                    <div style="height: 23px;width: 55px;float: left">
                        <p class="return-item-bgc" style="float: left;"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="space-10"></div>
        <div style="clear: right;"></div>
        <div id="data"></div>

    </div>
</div>
<style>
    .fontstyleone {
        color: #1e7fd0;
        font-size: 18px;
        text-align: left;
    }

    .fontstyletwo {
        color: #1e7fd0;
        font-size: 16px;
        text-align: left;
        margin-left: 15px;
    }

    .fontstylethree {
        font-size: 14px;
        text-align: left;
        margin-left: 35px;
    }
    .attrlist ,.category{
        cursor: pointer;
    }
    .update{
        color: #727272;
    }

</style>
<script>
    $(function () {
        var flag = true;
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            method: "get",
            idField: "catg_id",
            loadMsg: "加载数据请稍候。。。",
            singleSelect: true,
            columns: [[
                {field: "catg_attr_id", title: "類別屬性id", width: 286, hidden: 'true'},//類別屬性id
                {field: "r_ctg_id", title: "類別關聯PKID", width: 286, hidden: 'true'},//類別關聯PKID
                {field: "catg_id", title: "类别ID", width: 286, hidden: 'true'},
                {field: "catg_level", title: "类别层级", width: 286, hidden: 'true'},
                {field: "catg_name", title: "类别名称", width: 286, formatter: function (val, row) {
                    if (row.catg_level == '1') {
                        return '<div  class="fontstyleone">' + val + '</div>';
                    } else if (row.catg_level == '2') {
                        return '<div  class="fontstyletwo">' + val + '</div>';
                    } else
                        return '<div  class="fontstylethree">' + val + '</div>';
                }
                },
                {field: "isvalid", title: "是否有效", width: 120, formatter: function (val, row) {
                    if (val == '1') {
                        return '是'
                    } else
                        return '<span style="color: red">否</span>'
                }
                },
                {field: "shux", title: "维护属性", width: 200, formatter: function (val, row,index) {
                    if(row.catg_level != 1 && row.catg_level != 2){
                        return '<div class="update-item-bgc attrlist" style="margin-left:90px" data-id="'+row.catg_id+'"></div>';
                    }
                }
                },
                {field: "Relation", title: "类别关联", width: 180, formatter: function (val, row) {
                    if (row.catg_level != 1 && row.catg_level != 2) {
                        return "<div class='update-item-bgc category' style='margin-left:90px'><a  href='<?=Url::to(['get-cate-tree'])?>?catgid=" + row.catg_id + "' title='关联'><span class='relating' title='关联'></span>&nbsp;&nbsp;</a></a></div>"
                    }
                }
                },
                {field: "operation", title: "操作", width: 160, formatter: function (val, row) {
                    if (val == "" || val == null)
                        return "<a class='update' data-id='" + row.catg_id + "'  title='修改'>修改</a>"
                }
                },
            ]],
            onLoadSuccess: function (data) {
                var rows = data.rows;
                var tbrows = $(".datagrid-row");
                for (var i = 0; i < rows.length; i++){
                        for (var j= 0; j< tbrows.length; j++) {
                            var td = $(tbrows[j]).find('td');
                            if(td[0].innerText==0&&td[3].innerText==3){
                                 td.eq(6).css('background-color', 'yellow');
                            }
                            if(td[1].innerText==0&&td[3].innerText==3){
                                td.eq(7).css('background-color', 'yellow');
                            }
                    }
                }
            }
        })
        $("#add").on("click", function () {
            alert("该功能暂已停用！");
            return false;
            var catg_no = $("#catg_no").val();
            $('#add').fancybox({
                autoSize: true,
                fitToView: false,
                height: 560,
                width: 530,
                closeClick: true,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: "<?= Url::to(['create'])?>?catg_no=" + catg_no
            });
        })
        $(".table-content").delegate(".update", "click", function () {

            alert("该功能暂已停用！");
            return false;

            var catg_no = $("#catg_no").val();
            $('.update').fancybox({
                autoSize: true,
                fitToView: false,
                height: 560,
                width: 530,
                closeClick: true,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: "<?=Url::to(['update'])?>?id=" + $(this).data("id")+"&no="+catg_no
            });
            //window.location.href = "<?=Url::to(['update'])?>?id=" + $(this).data("id");
        })
        $(".table-content").delegate(".attrlist","click",function () {
            window.location.href = "<?=Url::to(['attr-list'])?>?id=" + $(this).data("id");
        })
    })
</script>
